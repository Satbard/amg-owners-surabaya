<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Event;
use App\Models\EventAttendance;
use App\Models\Registration;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function update(Request $request, Event $event, EventAttendance $attendance)
    {
        $request->validate([
            'status' => 'required|in:hadir,tidak_hadir',
        ]);

        $data = [
            'status' => $request->status,
        ];

        if ($request->status === 'hadir' && ! $attendance->scanned_at) {
            $data['scanned_at'] = now();
        } elseif ($request->status === 'tidak_hadir') {
            $data['scanned_at'] = null;
        }

        $attendance->update($data);

        $memberName = $attendance->registration->full_name
            ?? $attendance->registration->member_number
            ?? '(Member telah dihapus)';

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Mengubah absensi '.
                $memberName.
                ' pada acara '.$event->title.
                ' menjadi '.$request->status,
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Status absensi berhasil diperbarui.');
    }

    public function scan(Request $request, Event $event)
    {
        $request->validate([
            'member_number' => 'required|string|max:20',
        ]);

        $registration = Registration::where(
            'member_number',
            $request->member_number
        )->first();

        if (! $registration) {
            return redirect()
                ->back()
                ->with('error', 'Member tidak ditemukan.');
        }

        $attendance = EventAttendance::where([
            'event_id' => $event->id,
            'registration_id' => $registration->id,
        ])->first();

        if (! $attendance) {
            return redirect()
                ->back()
                ->with('error', 'Member tidak terdaftar di acara ini.');
        }

        if ($attendance->status === 'hadir') {
            return redirect()
                ->back()
                ->with('warning', 'Member sudah tercatat hadir.');
        }

        $attendance->update([
            'status' => 'hadir',
            'scanned_at' => now(),
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Scan barcode: '.$registration->full_name.
                ' hadir di acara '.$event->title,
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                $registration->full_name.' tercatat hadir.'
            );
    }
}
