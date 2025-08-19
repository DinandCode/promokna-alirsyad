<?php

namespace App\Http\Controllers;

use App\Mail\ParticipantConfirmationMail;
use App\Mail\RegistrationSuccessMail;
use App\Models\Payment;
use App\Models\Participant;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function pay(Participant $participant, Payment $payment)
    {
        $paidTotal = Participant::whereHas('payment', function ($query) {
            $query->where('status', 'paid');
        })->count();
        $freeTotal = Participant::doesntHave('payment')->count();

        Log::info("[Pre-Register] Paid user total: $paidTotal, free user total: $freeTotal");

        $paidLeft = intval(Setting::get(Setting::KEY_EVENT_PAID_MEMBER_LIMIT)) - intval($paidTotal);
        $freeLeft = intval(Setting::get(Setting::KEY_EVENT_FREE_MEMBER_LIMIT)) - intval($freeTotal);

        return view('payments.payment', compact('participant', 'payment', 'paidLeft', 'freeLeft'));
    }

    public function paymentSuccess(Participant $participant, Payment $payment)
    {
        return view('payments.success', compact('participant', 'payment'));
    }

    public function handleNotification(Request $request)
    {
        // Simpan log untuk debugging
        Log::info('Midtrans Notification:', $request->all());

        // Ambil data dari Midtrans
        $serverKey = config('services.midtrans.server_key');
        $signatureKey = hash(
            "sha512",
            $request->order_id . $request->status_code . $request->gross_amount . $serverKey
        );

        // Validasi Signature Key
        if ($signatureKey !== $request->signature_key) {
            Log::error('Invalid Midtrans Signature Key');
            return response()->json(['message' => 'Invalid Signature Key'], 403);
        }

        // Cek status pembayaran
        if ($request->transaction_status === 'settlement') {
            $this->processPaymentSuccess($request->order_id);
        }

        if ($request->transaction_status === 'expire') {
            $this->processPaymentExpired($request->order_id);
        }

        return response()->json(['message' => 'Notification received']);
    }

    private function processPaymentSuccess($orderId)
    {
        // Cari participant berdasarkan order_id
        $payment = Payment::where('midtrans_order_id', $orderId)->first();

        if (!$payment) {
            Log::error("Payment not found: " . $orderId);
            return;
        }

        // Update status pembayaran
        $payment->status = 'paid';
        $payment->save();

        // Kirim email notifikasi pembayaran berhasil
        Mail::to($payment->participant->email)->send(new RegistrationSuccessMail($payment->participant));
        // Mail::to($payment->participant->email)->send(new ParticipantConfirmationMail($payment->participant));

        Log::info("Payment successfully processed for order: " . $orderId);
    }

    private function processPaymentExpired($orderId)
    {
        $payment = Payment::where('midtrans_order_id', $orderId)->first();

        if (!$payment) {
            Log::error("Payment not found: " . $orderId);
            return;
        }

        // Update status pembayaran
        $payment->status = 'expired';
        $payment->save();

        Log::info("Payment successfully processed for order: " . $orderId);
    }
}
