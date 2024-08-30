<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'image', 'user_id'];

    public function comments()
    {
        return $this->hasMany(Comment::class, 'ticket_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function histories()
    {
        return $this->belongsToMany(Status::class, 'histories', 'ticket_id', 'status_id')->withPivot(['pic_id', 'time_change']);
    }
}
