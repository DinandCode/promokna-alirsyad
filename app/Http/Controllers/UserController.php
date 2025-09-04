<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegistrationRequest;
use App\Mail\PaymentNotificationMail;
use App\Mail\RegistrationSuccessMail;
use App\Models\Participant;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Midtrans\Config;
use Midtrans\Snap;
use TCPDF;

class UserController extends Controller
{
    /**
     * Handle user registration form submission.
     */
    public function attemptRegister(UserRegistrationRequest $request)
    {
        $validatedData = $request->validated();

        $paidTotal = Participant::whereHas('payment', function ($query) {
            $query->whereNot('status', 'expired');
        })->count();
        $freeTotal = Participant::doesntHave('payment')->count();

        Log::info("[Attempt Register] Paid user total: $paidTotal, free user total: $freeTotal");

        $freeLimit = Setting::get(Setting::KEY_EVENT_FREE_MEMBER_LIMIT);
        $paidLimit = Setting::get(Setting::KEY_EVENT_PAID_MEMBER_LIMIT);

        if ($paidTotal >= $paidLimit && (isset($validatedData['jersey_size']) && $validatedData['jersey_size'] != null)) {
            return back();
        }

        if ($freeTotal >= $freeLimit && !isset($validatedData['jersey_size'])) {
            return back();
        }

        $registrationStatus = Setting::get(Setting::KEY_REGISTRATION_STATUS);
        if ($registrationStatus != 'open' || ($paidTotal >= $paidLimit && $freeTotal >= $freeLimit)) {
            abort(403);
        }

        try {
            return DB::transaction(function () use ($validatedData) {
                $lastBib = Participant::orderByDesc('bib')->value('bib');

                $lastBibNumber = $lastBib ? intval($lastBib) : 0;

                $validatedData['bib'] = str_pad(strval($lastBibNumber + 1), 4, "0", STR_PAD_LEFT);
                $validatedData['accept_promo'] = isset($validatedData['accept_promo']);
                $participant = Participant::create($validatedData);

                if (isset($validatedData['jersey_size']) && $validatedData['jersey_size'] != null) {
                    $ids = $this->setupPaymentRecord($participant, 0);

                    return redirect()->route('payment.pay', [
                        'participant' => $ids[0],
                        'payment' => $ids[1],
                    ]);
                } else {
                    Mail::to($participant->email)->send(new RegistrationSuccessMail($participant));
                    return redirect()->route('user.register-success', $participant->id);
                }
            });
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan, harap coba beberapa saat lagi: ' . $th->getMessage());
        }
    }

    public function registerSuccess(Participant $participant, Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return view('payments.success', compact('participant'));
    }

    public function attemptRegisterPaid(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required',
            'birth_date' => 'required|date',
            'gender' => 'required|in:L,P',
            'address' => 'required|string',
            'account_name' => 'required|string',
            'payment_amount' => 'required|numeric|min:1',
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Upload bukti pembayaran
        if ($request->hasFile('payment_proof')) {
            $data['payment_proof_path'] = $request->file('payment_proof')->store('bukti_pembayaran', 'public');
        }

        // Simpan ke database
        $participant = Participant::create($data); // Sesuaikan kolom model

        return redirect()->route('user.register-success', ['participant' => $participant->id]);
    }

    public function attemptRegisterFree(Request $request)
    {
        // Validasi input
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required',
            'birth_date' => 'required|date',
            'gender' => 'required|in:L,P',
            'address' => 'required|string',
        ]);

        // Simpan ke tabel participants atau sesuai modelmu
        Participant::create($data);

        return redirect()->route('user.register-success', ['participant' => $data['id'] ?? 1]);
    }

    public function attemptRegisterTicketOpen(UserRegistrationRequest $request)
    {
        $validatedData = $request->validated();
        $ticket = Ticket::find($validatedData['ticket_id']);

        $total = Participant::doesntHave('payment')->where('ticket_id', $ticket->id)->count();

        if ($ticket->price != null) {
            $total = Participant::whereHas('payment', function ($query) {
                $query->where('status', 'paid');
            })->where('ticket_id', $ticket->id)->count();
        }

        $ticketLimit = $ticket->quota;

        if ($ticketLimit != null && $total >= $ticketLimit) {
            return back();
        }

        $registrationStatus = Setting::get(Setting::KEY_REGISTRATION_STATUS);
        if ($registrationStatus != 'open') {
            abort(403);
        }

        try {
            return DB::transaction(function () use ($validatedData) {
                $ticket = Ticket::find($validatedData['ticket_id']);
                $lastBibNumber = $ticket->last_bib;

                $validatedData['bib'] = ($ticket->bib_prefix ?? "80") . str_pad(strval($lastBibNumber + 1), 3, "0", STR_PAD_LEFT);
                $validatedData['accept_promo'] = isset($validatedData['accept_promo']);

                $participant = Participant::create($validatedData);

                $ticket->last_bib = $lastBibNumber + 1;
                $ticket->save();

                if ($ticket->price && $ticket->price > 0) {
                    $ids = $this->setupPaymentRecord($participant, $ticket->price);

                    return redirect()->route('payment.pay', [
                        'participant' => $ids[0],
                        'payment' => $ids[1],
                    ]);
                }

                Mail::to($participant->email)->send(new RegistrationSuccessMail($participant));
                return $participant;
            });
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan, harap coba beberapa saat lagi: ' . $th->getMessage());
        }
    }

    public function attemptRegisterTicket(UserRegistrationRequest $request)
    {
        $validatedData = $request->validated();
        $ticket = Ticket::find($validatedData['ticket_id']);

        $user = User::find(Auth::user()->id);

        if ($ticket->type_match != $user->type) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('peserta.login', $ticket->id)->withErrors([
                'login' => 'Akun anda tidak dapat mendaftar kategori ini'
            ]);
        }

        $total = Participant::doesntHave('payment')->where('ticket_id', $ticket->id)->count();

        if ($ticket->price != null) {
            $total = Participant::whereHas('payment', function ($query) {
                $query->where('status', 'paid');
            })->where('ticket_id', $ticket->id)->count();
        }

        $ticketLimit = $ticket->quota;

        if ($ticketLimit != null && $total >= $ticketLimit) {
            return back();
        }

        $registrationStatus = Setting::get(Setting::KEY_REGISTRATION_STATUS);
        if ($registrationStatus != 'open') {
            abort(403);
        }

        try {
            return DB::transaction(function () use ($validatedData, $user) {
                $ticket = Ticket::find($validatedData['ticket_id']);
                $lastBibNumber = $ticket->last_bib;

                $validatedData['bib'] = ($ticket->bib_prefix ?? "80") . str_pad(strval($lastBibNumber + 1), 3, "0", STR_PAD_LEFT);
                $validatedData['accept_promo'] = isset($validatedData['accept_promo']);
                $validatedData['user_id'] = $user->id;

                $participant = Participant::create($validatedData);

                $ticket->last_bib = $lastBibNumber + 1;
                $ticket->save();

                if ($ticket->price && $ticket->price > 0) {
                    $ids = $this->setupPaymentRecord($participant, $ticket->price);

                    return redirect()->route('payment.pay', [
                        'participant' => $ids[0],
                        'payment' => $ids[1],
                    ]);
                }

                Mail::to($participant->email)->send(new RegistrationSuccessMail($participant));
                return $participant;
            });
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan, harap coba beberapa saat lagi: ' . $th->getMessage());
        }
    }

    protected function setupPaymentRecord(Participant $participant, $price)
    {
        $paymentAmount = floatval($price);
        $paymentRatePercent = floatval(str_replace(',', '.', Setting::get(Setting::KEY_PAYMENT_RATE_PERCENT)));

        $rate = $paymentRatePercent * $paymentAmount / 100;
        $totalPaid = $paymentAmount + $rate;

        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
        Config::$overrideNotifUrl = config('services.midtrans.notif_url');

        $orderId = 'REG-' . $participant->id . '-' . time();
        Log::info("Creating payment $orderId when done captured by: " . config('services.midtrans.notif_url') . "");

        $transaction = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => intval(ceil($totalPaid)),
            ],
            'customer_details' => [
                'first_name' => $participant->first_name,
                'last_name' => $participant->last_name,
                'email' => $participant->email
            ],
            'enabled_payments' => ['other_qris']
        ];

        $snapToken = Snap::getSnapToken($transaction);

        $payment = Payment::create([
            'participant_id' => $participant->id,
            'amount' => $paymentAmount,
            'total_amount' => $totalPaid,
            'rate' => $rate,
            'midtrans_order_id' => $orderId,
            'midtrans_snap_token' => $snapToken
        ]);

        Mail::to($participant->email)->send(new PaymentNotificationMail($participant, $payment));

        return [$participant->id, $payment->id];
    }

    public function listUser()
    {
        $query = request('query');
        $participants = [];

        if ($query) {
            $participantsQuery = Participant::query()
                ->where(function ($q) {
                    $q->whereDoesntHave('payment')
                        ->orWhereHas('payment', function ($paymentQuery) {
                            $paymentQuery->where('status', 'paid');
                        });
                });

            $participantsQuery->where('bib', $query);

            $participants = $participantsQuery->orderBy('bib')->paginate(10);
        }

        return view('home.peserta', compact('participants', 'query'));
    }

    public function printBIB($bib)
    {
        $participant = DB::table('participants')->where('bib', $bib)->first();

        // Ambil ukuran asli gambar
        $templatePath = public_path('images/layar-preview.png'); // pastikan pakai .png atau sesuai ekstensi

        list($widthPx, $heightPx) = getimagesize($templatePath);
        $dpi = 300; // resolusi cetak
        $widthMm = ($widthPx / $dpi) * 25.4;  // px → mm
        $heightMm = ($heightPx / $dpi) * 25.4;

        // Buat PDF dengan ukuran sesuai gambar
        $pdf = new TCPDF('L', 'mm', [$widthMm, $heightMm], true, 'UTF-8', false);
        $pdf->SetMargins(0, 0, 0);
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->AddPage();

        // Background image fit penuh
        $pdf->Image($templatePath, 0, 0, $widthMm, $heightMm, '', '', '', false, 300, '', false, false, 0);

        // Warna teks
        $pdf->SetTextColor(0, 0, 0);

        // Offset margin atas biar lebih rapi
        $yOffset = 1;

        // --- Full Name ---
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetXY(0, $yOffset + 10);
        $pdf->Cell($widthMm, 10, ucwords($participant->full_name), 0, 1, 'C');

        // --- BIB Number ---
        $pdf->SetFont('helvetica', 'B', 24);
        $pdf->SetXY(0, $yOffset + 12);
        $pdf->Cell($widthMm, 20, str_pad($participant->bib, 4, "0", STR_PAD_LEFT), 0, 1, 'C');

        // --- BIB Name ---
        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetXY(0, $yOffset + 20);
        $pdf->Cell($widthMm, 20, strtoupper($participant->bib_name), 0, 1, 'C');

        // Output
        $pdf->Output('BIB_' . $participant->bib . '.pdf', 'I');
    }
}
