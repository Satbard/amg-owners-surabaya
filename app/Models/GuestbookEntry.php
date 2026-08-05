<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestbookEntry extends Model
{
    protected $fillable = [
        'guestbook_id',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function guestbook()
    {
        return $this->belongsTo(Guestbook::class);
    }
}
