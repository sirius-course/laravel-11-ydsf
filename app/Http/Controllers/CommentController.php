<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller
{
    public function store(CommentRequest $request, Ticket $ticket)
    {
        abort_if(! Gate::authorize('buat komentar'), 403);

        DB::beginTransaction();

        $comment = Comment::create([
            'user_id' => auth()->user()->id,
            'ticket_id' => $ticket->id,
            'comment' => $request->input('komentar'),
        ]);

        // Simpan File
        if ($request->has('lampiran')) {
            $this->saveAttachment($comment, $request->file('lampiran'));
        }

        DB::commit();

        return redirect()->route('ticket.show', ['ticket' => $ticket->id])->with('success', 'Berhasil memberi komentar!');
    }

    public function delete(Comment $comment)
    {
        abort_if(! Gate::authorize('hapus komentar', $comment), 403);

        if (! empty($comment->attachment)) {
            Storage::disk('public')->delete($comment->attachment);
        }

        $ticketId = $comment->ticket_id;

        $comment->delete();

        return redirect()->route('ticket.show', ['ticket' => $ticketId])->with('success', 'Berhasil menghapus komentar!');
    }

    private function saveAttachment($comment, $attachment)
    {
        $path = storage_path('app/public/attachments');
        if (! file_exists($path)) {
            mkdir($path, 0777, true);
        }

        if (! empty($comment->attachment)) {
            Storage::disk('public')->delete($comment->attachment);
        }

        $file = Storage::disk('public')->put('attachments', $attachment);

        $comment->update([
            'attachment' => $file,
        ]);
    }
}
