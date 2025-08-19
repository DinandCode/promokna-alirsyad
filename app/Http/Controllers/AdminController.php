<?php

namespace App\Http\Controllers;

use App\Events\BIBPreviewUpdated;
use App\Models\Faq;
use App\Models\Participant;
use App\Models\RundownContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ParticipantsExport;
use App\Models\MailLog;
use App\Models\Message;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function index()
    {
        $paidCount = Participant::whereHas('payment', function ($q) {
            $q->where('status', 'paid');
        })->count();

        $freeCount = Participant::doesntHave('payment')->count();

        $takenStats = [];
        $takenStats['taken'] = Participant::where(function ($query) {
            $query->whereHas('payment', function ($q) {
                $q->where('status', 'paid');
            })->orWhereDoesntHave('payment');
        })->whereNotNull('taken_by')->count();

        $takenStats['not_taken'] = Participant::where(function ($query) {
            $query->whereHas('payment', function ($q) {
                $q->where('status', 'paid');
            })->orWhereDoesntHave('payment');
        })->whereNull('taken_by')->count();

        $freeStatsRaw = Participant::selectRaw("
        CASE WHEN taken_by IS NULL THEN 'not_taken' ELSE 'taken' END as taken_status,
        COUNT(*) as total
    ")
            ->whereDoesntHave('payment')
            ->groupBy('taken_status')
            ->get()
            ->pluck('total', 'taken_status');

        // Ensure both keys exist
        $freeStats = [
            'taken' => $freeStatsRaw->get('taken', 0),
            'not_taken' => $freeStatsRaw->get('not_taken', 0),
        ];

        $paidStatsRaw = Participant::selectRaw("
        CASE WHEN taken_by IS NULL THEN 'not_taken' ELSE 'taken' END as taken_status,
        COUNT(*) as total
    ")
            ->whereHas('payment', function ($q) {
                $q->where('status', 'paid');
            })
            ->groupBy('taken_status')
            ->get()
            ->pluck('total', 'taken_status');

        // Ensure both keys exist
        $paidStats = [
            'taken' => $paidStatsRaw->get('taken', 0),
            'not_taken' => $paidStatsRaw->get('not_taken', 0),
        ];

        return view('admin.index', [
            'paidCount' => $paidCount,
            'freeCount' => $freeCount,
            'takenStats' => $takenStats,
            'freeStats' => $freeStats,
            'paidStats' => $paidStats
        ]);
    }

    public function peserta()
    {
        return view('admin.peserta');
    }

    public function administrator()
    {
        return view('admin.administrator');
    }

    public function laporan(Request $request)
    {
        $query = Participant::query();

        // Filter pencarian
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%$search%")
                    ->orWhere('bib_name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%")
                    ->orWhere('bib', 'like', "%$search%");
            });
        }

        // Filter jenis kelamin
        if ($racepack = $request->input('racepack')) {
            if ($racepack == 'diambil') $query->whereNot('taken_by', null);
            else if ($racepack == 'belum') $query->where('taken_by', null);
        }

        if ($request->filled('payment_status')) {
            if ($request->input('payment_status') === 'none') {
                $query->whereDoesntHave('payment');
            } else {
                $query->whereHas('payment', function ($q) use ($request) {
                    $q->where('status', $request->input('payment_status'));
                });
            }
        }

        // Ambil data paginasi
        $participants = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('admin.laporan', compact('participants'));
    }

    public function exportPeserta(Request $request)
    {
        return Excel::download(new ParticipantsExport($request), 'PARTICIPANTS_EXPORT_' . config('app.name') . '_' . time() . '.xlsx');
    }

    public function pengaturan()
    {
        $existingRundowns = RundownContent::select(
            'title as judul',
            'occasion_date as tanggal',
            'description as deskripsi'
        )->get();

        $existingFaqs = Faq::select(
            'question as pertanyaan',
            'answer as jawaban'
        )->get();

        return view('admin.pengaturan', [
            'existingRundowns' => $existingRundowns,
            'existingFaqs' => $existingFaqs
        ]);
    }

    public function login()
    {
        return view('admin.login');
    }

    public function handleLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Attempt to authenticate the user
        if (Auth::attempt($credentials, true)) {
            $request->session()->regenerate();

            return redirect()->route('admin.index');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function pesan(Request $request)
    {
        $query = Message::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $messages = $query->latest()->paginate(10);

        return view('admin.messages', compact('messages'));
    }

    public function mailLogs(Request $request)
    {
        $search = $request->input('search');

        $logs = MailLog::query()
            ->when($search, function ($query, $search) {
                $query->where('to', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.mail-logs', compact('logs', 'search'));
    }

    public function resendMail(Request $request, $id)
    {
        $log = MailLog::findOrFail($id);

        try {
            Mail::html($log->content, function ($message) use ($log) {
                $message->to($log->to)
                    ->subject($log->subject);
            });

            $log->update(['status' => 'sent']);

            return back()->with('success', 'Email resent successfully!');
        } catch (\Throwable $e) {
            $log->update(['status' => 'failed']);
            return back()->with('error', 'Failed to resend email: ' . $e->getMessage());
        }
    }

    public function showFotoBibForm()
    {
        return view('admin.fotobib');
    }

    public function handleFotoBibForm(Request $request)
    {
        $request->validate([
            'bib_number' => 'required|string|exists:participants,bib',
        ]);

        $bib = $request->input('bib_number');
        $participant = Participant::where('bib', $bib)->first();

        if ($participant) {
            // broadcast(new BIBPreviewUpdated(json_encode($participant)));
            return back()->with('success', 'BIB number ' . $bib . ' has been sent for preview.')->withCookie(cookie('preview-bib', json_encode($participant)));
        }

        return back()->with('error', 'Participant with BIB number ' . $bib . ' not found.');
    }

    public function cookieGetFotoBIBValue(Request $request)
    {
        $participant = json_decode($request->cookie('preview-bib'));

        return response()->json($participant);
    }
}
