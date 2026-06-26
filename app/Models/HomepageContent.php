<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageContent extends Model
{
    protected $fillable = [

        'logo',

        'background',

        'registration_background',

        'title',

        'description',

        'button_text',

        'header_logo',

        'updated_by'
    ];

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}