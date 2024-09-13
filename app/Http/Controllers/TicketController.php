<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketRequest;
use App\Models\Status;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function index()
    {
        abort_if(! Gate::authorize('lihat tiket'), 403);

        $tickets = Ticket::with('user')->orderBy('created_at', 'desc')->paginate(10);

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

        DB::beginTransaction();

        // Simpan
        $ticket = Ticket::create([
            'title' => $request->input('judul'),
            'description' => $request->input('deskripsi'),
            'user_id' => auth()->user()->id,
        ]);

        // Simpan File
        if ($request->has('gambar')) {
            $this->saveImage($ticket, $request->file('gambar'));
        }

        // Ambil Status Queue
        $status = Status::where('name', 'queue')->first();

        // Tambahkan Histories
        $ticket->histories()->attach([$status->id => [
            'pic_id' => auth()->user()->id,
            'time_change' => now(),
        ]]);

        DB::commit();

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

        // Simpan File
        if ($request->has('gambar')) {
            $this->saveImage($ticket, $request->file('gambar'));
        }

        // Kembali Ke Index
        return redirect()->route('ticket.index')->with('success', 'Tiket berhasil diperbarui!');
    }

    public function delete(Ticket $ticket)
    {
        abort_if(! Gate::authorize('hapus tiket', $ticket), 403);

        // Hapus status
        $statuses = DB::table('histories')->select('status_id')->where('ticket_id', $ticket->id)->get(); // ambil data dari database
        $statuses = $statuses->pluck('status_id')->toArray(); // transformasi array
        $ticket->histories()->detach($statuses); // hapus semua status tiket

        // Hapus gambar
        if (!empty($ticket->image)) {
            Storage::disk('public')->delete($ticket->image); // hapus file sebelumnya bila ada
        }

        // Delete
        $ticket->delete();

        // Kembali ke Index
        return redirect()->route('ticket.index')->with('success', 'Tiket berhasil dihapus!');
    }

    public function show(Ticket $ticket)
    {
        abort_if(! Gate::authorize('lihat tiket'), 403);

        $progressing = Status::where('name', 'progressing')->first();
        $done = Status::where('name', 'done')->first();
        $cancel = Status::where('name', 'cancel')->first();

        return view('ticket.show', [
            'ticket' => $ticket,
            'progressing' => $progressing,
            'done' => $done,
            'cancel' => $cancel,
        ]);
    }

    public function changeStatus(Request $request, Ticket $ticket)
    {
        $ticket->histories()->attach([$request->status => [
            'pic_id' => auth()->user()->id,
            'time_change' => now(),
        ]]);

        return redirect()->route('ticket.show', ['ticket' => $ticket->id]);
    }

    private function saveImage($ticket, $image)
    {
        $path = storage_path("tickets");
        if (!file_exists($path)) {
            mkdir($path, 0777, true); // buat folder tickets jika tidak ada
        }

        if (!empty($ticket->image)) {
            Storage::disk('public')->delete($ticket->image); // hapus file sebelumnya bila ada
        }

        $file = Storage::disk('public')->put("tickets", $image); // simpan file

        $ticket->update([
            'image' => $file // simpan lokasi fil
        ]);
    }
}
