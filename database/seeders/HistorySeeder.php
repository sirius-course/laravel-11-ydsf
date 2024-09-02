<?php

namespace Database\Seeders;

use App\Models\Status;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;

class HistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tickets = Ticket::all();
        $statuses = Status::all();

        foreach ($tickets as $ticket) {
            $isDone = rand(0, 1);

            foreach ($statuses as $status) {
                $create = true;

                if ($status->name == 'done' || $status->name == 'cancel') {
                    if ($isDone && $status->name == 'cancel') {
                        $create = false;
                    } elseif (! $isDone && $status->name == 'done') {
                        $create = false;
                    }
                }

                if ($create) {
                    $ticket->histories()->attach($status->id, [
                        'pic_id' => ($status->name == 'queue') ? $ticket->user_id : User::inRandomOrder()->limit(1)->pluck('id')->first(),
                        'time_change' => now(),
                    ]);
                }
            }
        }
    }
}
