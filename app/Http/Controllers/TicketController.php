<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketRequest;
use App\Models\Ticket;
use Illuminate\Support\Facades\Gate;

class TicketController extends Controller
{
    public function index()
    {
        abort_if(! Gate::authorize('lihat tiket'), 403);

        $tickets = Ticket::orderBy('created_at', 'desc')->paginate(10);

        return view('ticket.index', [
            'tickets' => $tickets,
        ]);
    }

    public function create()
    {
        abort_if(! Gate::authorize('buat tiket'), 403);

        return view('ticket.form');
    }

    public function store(TicketRequest $request)
    {
        abort_if(! Gate::authorize('buat tiket'), 403);

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
        abort_if(! Gate::authorize('ubah tiket', $ticket), 403);

        return view('ticket.form', [
            'ticket' => $ticket,
        ]);
    }

    public function update(TicketRequest $request, Ticket $ticket)
    {
        abort_if(! Gate::authorize('ubah tiket', $ticket), 403);

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
        abort_if(! Gate::authorize('hapus tiket', $ticket), 403);

        // Delete
        $ticket->delete();

        // Kembali ke Index
        return redirect()->route('ticket.index')->with('success', 'Tiket berhasil dihapus!');
    }

    public function show(Ticket $ticket)
    {
        abort_if(! Gate::authorize('lihat tiket'), 403);

        return view('ticket.show', [
            'ticket' => $ticket,
        ]);
    }
}
