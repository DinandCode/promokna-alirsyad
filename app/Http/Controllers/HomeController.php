<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Message;
use App\Models\Participant;
use App\Models\RundownContent;
use App\Models\Setting;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use function Ramsey\Uuid\v1;

class HomeController extends Controller
{
    public function index()
    {
        $rundowns = RundownContent::all();
        $tickets = Ticket::all();

        return view('user.index', compact('rundowns', 'tickets'));
    }

    public function register()
    {
        $ticket = Ticket::where('type_match', 'alumni')->first();
        if (!$ticket) abort(404);
        $registrationStatus = Setting::get(Setting::KEY_REGISTRATION_STATUS);

        $total = Participant::doesntHave('payment')->where('ticket_id', $ticket->id)->count();
        $quota = $ticket->quota;

        if ($ticket->price != null) {
            $total = Participant::whereHas('payment', function ($query) {
                $query->where('status', 'paid');
            })->where('ticket_id', $ticket->id)->count();
        }

        $freeLeft = intval($quota) - intval($total);

        if ($quota == null) $freeLeft = 999999;

        return view('user.register-special', compact('ticket', 'registrationStatus', 'freeLeft'));
    }

    public function registerTicket(Request $request, $ticketId)
    {
        $user = User::find(Auth::user()->id);
        $ticket = Ticket::find($ticketId);
        $registrationStatus = Setting::get(Setting::KEY_REGISTRATION_STATUS);

        $total = Participant::doesntHave('payment')->where('ticket_id', $ticketId)->count();
        $quota = $ticket->quota;

        if ($ticket->price != null) {
            $total = Participant::whereHas('payment', function ($query) {
                $query->where('status', 'paid');
            })->where('ticket_id', $ticket->id)->count();
        }

        $freeLeft = intval($quota) - intval($total);

        if ($ticket->type_match != $user->type) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('peserta.login', $ticketId)->withErrors([
                'login' => 'Akun anda tidak dapat mendaftar kategori ini'
            ]);
        }

        if ($quota == null) $freeLeft = 999999;

        return view('user.register', compact('ticket', 'registrationStatus', 'freeLeft'));
    }

    public function registerFree()
    {
        $registrationStatus = Setting::get(Setting::KEY_REGISTRATION_STATUS);
        $freeTotal = Participant::doesntHave('payment')->count();

        $freeLeft = intval(Setting::get(Setting::KEY_EVENT_FREE_MEMBER_LIMIT)) - intval($freeTotal);
        return view('register.free', compact('registrationStatus', 'freeTotal', 'freeLeft'));
    }

    public function registerPaid()
    {
        $registrationStatus = Setting::get(Setting::KEY_REGISTRATION_STATUS);
        $paidTotal = Participant::whereHas('payment', function ($query) {
            $query->whereNot('status', 'expired');
        })->count();

        $paidLeft = intval(Setting::get(Setting::KEY_EVENT_PAID_MEMBER_LIMIT)) - intval($paidTotal);

        return view('register.paid', compact('registrationStatus', 'paidLeft', 'paidTotal'));
    }


    public function faq()
    {
        $faqs = Faq::all();
        return view('home.faq', compact('faqs'));
    }


    public function sk()
    {
        return view('home.sk');
    }

    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'company' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'subject' => 'required',
            'message' => 'required',
        ]);

        Message::create($validated);

        return back()->with('message', [
            'title' => 'Pesan berhasil dikirimkan!',
            'type' => 'success'
        ]);
    }

    public function previewBib()
    {
        return view('home.fotobib');
    }
}
