<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestbookField extends Model
{
    protected $fillable = [
        'guestbook_id',
        'label',
        'field_type',
        'is_required',
        'sort_order',
    ];

    protected $casts = [
        'is_required' => 'boolean',
    ];

    public function guestbook()
    {
        return $this->belongsTo(Guestbook::class);
    }
}
