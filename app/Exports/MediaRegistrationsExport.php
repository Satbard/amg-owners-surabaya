<?php

namespace App\Exports;

use App\Models\MediaRegistration;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MediaRegistrationsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return MediaRegistration::select([
            'id',
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
            'status',
            'created_at',
        ])->get()->map(function ($item) {
            // Convert position JSON array to comma-separated string for export
            $item->position = is_array($item->position)
                ? implode(', ', $item->position)
                : $item->position;

            return $item;
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Lengkap',
            'Nama Media',
            'Posisi',
            'No HP',
            'Email',
            'Social Media',
            'Jumlah Followers',
            'Jenis Media',
            'Kategori Lomba',
            'Equipment',
            'Status',
            'Tanggal Daftar',
        ];
    }
}
