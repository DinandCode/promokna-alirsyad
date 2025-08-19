<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participant;

class AuthController extends Controller
{
    // Form login
    public function showLoginForm($ticketId)
    {
        return view('auth.peserta-login', compact('ticketId'));
    }

    // Proses login
    public function login(Request $request, $ticketId)
    {
        $request->validate([
            'name' => 'required|string',
            'nis'  => 'required|string',
        ]);

        // Cari peserta berdasarkan Nama & NIS
        $participant = Participant::where('name', $request->name)
            ->where('nis', $request->nis)
            ->first();

        if (!$participant) {
            return back()->withErrors([
                'login' => 'Nama atau NIS tidak cocok.',
            ])->withInput();
        }

        // Simpan session login peserta
        session([
            'participant_id' => $participant->id,
            'ticket_id'      => $ticketId,
        ]);

        // Arahkan ke form pendaftaran sesuai ticket
        return redirect()->route('user.register', $ticketId);
    }
}
