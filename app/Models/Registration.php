<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Registration extends Model
{
    use SoftDeletes;

    protected $fillable = [

        'member_number',

        'full_name',
        'nickname',

        'birth_place',
        'birth_date',

        'address',

        'phone',
        'email',

        'instagram',

        'occupation',

        'shirt_size',

        'vehicle_model',
        'vehicle_year',
        'vehicle_color',
        'license_plate',

        'membership_status',
    ];

    public static function generateMemberNumber($id)
    {
        return 'AMG'.str_pad($id, 5, '0', STR_PAD_LEFT);
    }

    public function attendances()
    {
        return $this->hasMany(EventAttendance::class);
    }
}
