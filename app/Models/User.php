<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'username',
        'name',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }
}

