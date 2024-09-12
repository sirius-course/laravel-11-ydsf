<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::orderBy('created_at', 'desc')->paginate(10);

        return view('ticket.index', [
            'tickets' => $tickets,
        ]);
    }

    public function create()
    {
        return view('ticket.form');
    }

    public function store(Request $request)
    {
        // Validasi
        $validated = $request->validate([
            'judul' => ['required', 'max:255'],
            'deskripsi' => ['required'],
        ]);

        // Simpan
        Ticket::create([
            'title' => $request->input('judul'),
            'description' => $request->input('deskripsi'),
            'user_id' => auth()->user()->id,
        ]);

        // Kembali Ke Index
        return redirect()->route('ticket.index')->with('success', 'Tiket berhasil ditambahkan!');
    }

    public function edit(Ticket $ticket)
    {
        return view('ticket.form', [
            'ticket' => $ticket,
        ]);
    }

    public function update(Request $request, Ticket $ticket)
    {
        // Validasi
        $validated = $request->validate([
            'judul' => ['required', 'max:255'],
            'deskripsi' => ['required'],
        ]);

        // Simpan
        $ticket->update([
            'title' => $request->input('judul'),
            'description' => $request->input('deskripsi'),
        ]);

        // Kembali Ke Index
        return redirect()->route('ticket.index')->with('success', 'Tiket berhasil diperbarui!');
    }

    public function delete(Ticket $ticket)
    {
        // Delete
        $ticket->delete();

        // Kembali ke Index
        return redirect()->route('ticket.index')->with('success', 'Tiket berhasil dihapus!');
    }

    public function show(Ticket $ticket)
    {
        return view('ticket.show', [
            'ticket' => $ticket,
        ]);
    }
}
