<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaEvent extends Model
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
        return $this->hasMany(MediaEventAttendance::class);
    }

    public function mediaRegistrations()
    {
        return $this->belongsToMany(
            MediaRegistration::class,
            'media_event_attendances'
        )->withPivot('status', 'scanned_at')->withTimestamps();
    }
}
