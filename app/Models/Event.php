<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'event_date',
        'location',
        'status',
    ];

    protected $casts = [
        'event_date' => 'datetime',
    ];

    public function attendances()
    {
        return $this->hasMany(EventAttendance::class);
    }

    public function members()
    {
        return $this->belongsToMany(
            Registration::class,
            'event_attendances'
        )
            ->withPivot('status', 'scanned_at')
            ->withTimestamps();
    }
}
