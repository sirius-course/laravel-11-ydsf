<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function histories()
    {
        return $this->belongsToMany(Ticket::class, 'histories', 'status_id', 'ticket_id')->withPivot(['pic_id', 'time_change']);
    }
}
