<?php

namespace App\Exports;

use App\Models\EventAttendance;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping
{
    protected $eventId;

    public function __construct($eventId)
    {
        $this->eventId = $eventId;
    }

    public function collection()
    {
        return EventAttendance::with('registration')
            ->where('event_id', $this->eventId)
            ->get();
    }

    public function headings(): array
    {
        return [
            'No. Member',
            'Nama Lengkap',
            'Nama Panggilan',
            'No. HP',
            'Status',
            'Waktu Scan',
        ];
    }

    public function map($attendance): array
    {
        $registration = $attendance->registration;

        return [
            $registration->member_number ?? '-',
            $registration->full_name ?? '[Member Dihapus]',
            $registration->nickname ?? '-',
            $registration->phone ?? '-',
            $attendance->status === 'hadir' ? 'Hadir' : 'Tidak Hadir',
            $attendance->scanned_at
                ? Carbon::parse($attendance->scanned_at)->format('d M Y H:i:s')
                : '-',
        ];
    }
}
