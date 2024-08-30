<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('histories', function (Blueprint $table) {
            $table->foreignId('ticket_id')->constrained();
            $table->unsignedTinyInteger('status_id');
            $table->foreign('status_id')->references('id')->on('statuses');
            $table->primary(['ticket_id', 'status_id']);
            $table->foreignId('pic_id')->constrained('users', 'id');
            $table->timestamp('time_change');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statuses');
    }
};
