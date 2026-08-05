<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guestbook extends Model
{
    protected $fillable = [
        'guestbook_event_id',
        'name',
    ];

    public function event()
    {
        return $this->belongsTo(GuestbookEvent::class, 'guestbook_event_id');
    }

    public function fields()
    {
        return $this->hasMany(GuestbookField::class)->orderBy('sort_order');
    }

    public function entries()
    {
        return $this->hasMany(GuestbookEntry::class);
    }
}
