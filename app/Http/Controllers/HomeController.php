<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Message;
use App\Models\Participant;
use App\Models\RundownContent;
use App\Models\Setting;
use App\Models\Ticket;
use Illuminate\Http\Request;
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
        return redirect()->route('home.index');

        $registrationStatus = Setting::get(Setting::KEY_REGISTRATION_STATUS);
        $paidTotal = Participant::whereHas('payment', function ($query) {
            $query->whereNot('status', 'expired');
        })->count();
        $freeTotal = Participant::doesntHave('payment')->count();

        Log::info("[Pre-Register] Paid user total: $paidTotal, free user total: $freeTotal");

        $paidLeft = intval(Setting::get(Setting::KEY_EVENT_PAID_MEMBER_LIMIT)) - intval($paidTotal);
        $freeLeft = intval(Setting::get(Setting::KEY_EVENT_FREE_MEMBER_LIMIT)) - intval($freeTotal);

        return view('user.register', compact('registrationStatus', 'paidLeft', 'freeLeft'));
    }

    public function registerTicket($ticketId)
    {
        $ticket = Ticket::find($ticketId);
        $registrationStatus = Setting::get(Setting::KEY_REGISTRATION_STATUS);

        $freeTotal = Participant::doesntHave('payment')->where('ticket_id', $ticketId)->count();
        $quota = $ticket->quota;
        $freeLeft = intval($quota) - intval($freeTotal);

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

    public function previewBib() {
        return view('home.fotobib');
    }
}
