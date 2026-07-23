<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\MediaEvent;
use App\Models\MediaEventAttendance;
use Illuminate\Http\Request;

class MediaAttendanceController extends Controller
{
    public function update(Request $request, MediaEvent $mediaEvent, MediaEventAttendance $mediaAttendance)
    {
        $request->validate([
            'status' => 'required|in:hadir,tidak_hadir',
        ]);

        $data = ['status' => $request->status];

        if ($request->status === 'hadir' && ! $mediaAttendance->scanned_at) {
            $data['scanned_at'] = now();
        } elseif ($request->status === 'tidak_hadir') {
            $data['scanned_at'] = null;
        }

        $mediaAttendance->update($data);

        $mediaName = $mediaAttendance->mediaRegistration->full_name
            ?? $mediaAttendance->mediaRegistration->media_name
            ?? '(Media telah dihapus)';

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Mengubah absensi '.$mediaName.
                ' pada acara '.$mediaEvent->title.
                ' menjadi '.$request->status,
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Status absensi media berhasil diperbarui.');
    }
}
