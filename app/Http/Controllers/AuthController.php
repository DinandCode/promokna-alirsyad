<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participant;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Form login
    public function showLoginForm($ticketId)
    {
        $ticket = Ticket::find($ticketId);
        return view('auth.peserta-login', compact('ticket'));
    }

    // Proses login
    public function login(Request $request, $ticketId)
    {
        $request->validate([
            'nama' => 'required|string',
            'nis'  => 'required|string',
        ], [
            'nama.required' => 'Nama wajib diisi',
            'nis.required' => 'NIS wajib diisi',
        ]);
        $ticket = Ticket::find($ticketId);

        // Cari peserta berdasarkan Nama & NIS
        $user = User::where('email', $request->nama)
            ->where('password', $request->nis)
            ->first();

        if (!$user) {
            return back()->withErrors([
                'login' => 'Nama atau NIS tidak cocok.',
            ])->withInput();
        }

        if ($ticket->type_match != $user->type) {
            return back()->withErrors([
                'login' => 'Akun anda tidak dapat mendaftar kategori ini'
            ]);
        }

        $totalTries = $user->participants()->whereHas('payment', function ($query) {
                $query->where('status', 'paid');
            })->where('ticket_id', $ticket->id)->count();
        if ($totalTries >= $ticket->max_tries) {
            return back()->withErrors([
                'login' => 'Anda sudah menggunakan jatah maksimal ' . $ticket->max_tries . ' untuk kategori ini.'
            ]);
        }

        Auth::login($user);

        // Arahkan ke form pendaftaran sesuai ticket
        return redirect()->route('user.register-ticket', $ticketId);
    }
}
