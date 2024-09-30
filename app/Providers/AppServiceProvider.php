<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('lihat tiket', function (User $user) {
            return ! empty($user->phone);
        });

        Gate::define('buat tiket', function (User $user) {
            return ! empty($user->phone);
        });

        Gate::define('ubah tiket', function (User $user, Ticket $ticket) {
            return $user->id == $ticket->user_id;
        });

        Gate::define('hapus tiket', function (User $user, Ticket $ticket) {
            return $user->id == $ticket->user_id;
        });

        Gate::define('buat komentar', function (User $user) {
            return Gate::authorize('lihat tiket');
        });

        Gate::define('hapus komentar', function (User $user, Comment $comment) {
            return $user->id == $comment->user_id;
        });
    }
}
