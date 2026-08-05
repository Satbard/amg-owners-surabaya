<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestbookEvent extends Model
{
    protected $fillable = [
        'title',
        'description',
        'event_date',
        'location',
    ];

    protected $casts = [
        'event_date' => 'datetime',
    ];

    public function guestbooks()
    {
        return $this->hasMany(Guestbook::class);
    }
}
