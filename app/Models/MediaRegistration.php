<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MediaRegistration extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'full_name',
        'media_name',
        'position',
        'phone',
        'email',
        'social_media',
        'followers',
        'media_type',
        'competition_category',
        'equipment_used',
        'terms_agreed',
        'status',
    ];

    protected $casts = [
        'position' => 'array',
        'terms_agreed' => 'boolean',
    ];
}
