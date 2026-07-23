<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaEventAttendance extends Model
{
    protected $fillable = [
        'media_event_id',
        'media_registration_id',
        'status',
        'scanned_at',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    public function mediaEvent()
    {
        return $this->belongsTo(MediaEvent::class);
    }

    public function mediaRegistration()
    {
        return $this->belongsTo(MediaRegistration::class);
    }
}
