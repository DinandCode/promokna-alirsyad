<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::all();
        return view('admin.tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('admin.tickets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'price' => 'nullable|integer|min:1',
            'quota' => 'nullable|integer|min:1'
        ]);

        Ticket::create($validated);

        return redirect()->route('admin.tickets.index')
            ->with('success', 'Tiket berhasil dibuat!');
    }

    public function show($id)
    {
        $tiket = Ticket::find($id);
        return view('admin.tickets.show', compact('tiket'));
    }

    public function edit($id)
    {
        $ticket = Ticket::find($id);
        return view('admin.tickets.edit', compact('ticket'));
    }

    public function update(Request $request, $id)
    {
        $tiket = Ticket::find($id);
        // Validasi input
        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'price' => ['nullable', 'numeric', 'min:1'],
            'quota' => [
                'nullable',
                'integer',
                'min:1', // pastikan minimal 1 jika diisi
            ],
        ], [
            'quota.min' => 'Quota minimal adalah 1 jika diisi.',
        ]);

        // Update data tiket
        $tiket->update($validated);

        return redirect()
            ->route('admin.tickets.index')
            ->with('success', 'Tiket berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $tiket = Ticket::find($id);
        $tiket->delete();

        return redirect()->route('admin.tickets.index')->with('success', 'Ticket deleted successfully!');
    }
}
