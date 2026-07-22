<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaOtp extends Model
{
    protected $fillable = [
        'media_registration_id',
        'otp',
        'expires_at',
        'used_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function mediaRegistration()
    {
        return $this->belongsTo(MediaRegistration::class);
    }

    public function isValid(): bool
    {
        return is_null($this->used_at) && $this->expires_at->isFuture();
    }
}
