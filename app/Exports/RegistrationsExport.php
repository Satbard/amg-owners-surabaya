<?php

namespace App\Exports;

use App\Models\Registration;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RegistrationsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Registration::select([
            'id',
            'full_name',
            'nickname',
            'birth_place',
            'birth_date',
            'phone',
            'email',
            'occupation',
            'vehicle_model',
            'vehicle_year',
            'vehicle_color',
            'license_plate',
            'membership_status',
            'created_at'
        ])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Lengkap',
            'Nama Panggilan',
            'Tempat Lahir',
            'Tanggal Lahir',
            'HP',
            'Email',
            'Pekerjaan',
            'Model Kendaraan',
            'Tahun',
            'Warna',
            'Plat',
            'Status',
            'Tanggal Daftar'
        ];
    }
}