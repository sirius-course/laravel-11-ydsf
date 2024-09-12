<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use App\Http\Middleware\UserHasPhoneMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware(UserHasPhoneMiddleware::class)->group(function () {
        Route::get('/tiket', [TicketController::class, 'index'])->name('ticket.index');
        Route::get('/tiket/tambah', [TicketController::class, 'create'])->name('ticket.create');
        Route::post('/tiket', [TicketController::class, 'store'])->name('ticket.store');
        Route::get('/tiket/{ticket}/ubah', [TicketController::class, 'edit'])->name('ticket.edit');
        Route::put('/tiket/{ticket}', [TicketController::class, 'update'])->name('ticket.update');
        Route::delete('/tiket/{ticket}', [TicketController::class, 'delete'])->name('ticket.delete');
        Route::get('/tiket/{ticket}', [TicketController::class, 'show'])->name('ticket.show');
    });
});

require __DIR__.'/auth.php';
