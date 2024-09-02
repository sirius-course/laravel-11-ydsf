<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Status::create(['name' => 'queue']);
        Status::create(['name' => 'progressing']);
        Status::create(['name' => 'cancel']);
        Status::create(['name' => 'done']);
    }
}
