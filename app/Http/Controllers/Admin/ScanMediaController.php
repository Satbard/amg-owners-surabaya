<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\MediaEvent;
use App\Models\MediaEventAttendance;
use App\Models\MediaRegistration;
use Illuminate\Http\Request;

class ScanMediaController extends Controller
{
    public function index(Request $request)
    {
        $events = MediaEvent::whereIn('status', ['upcoming', 'ongoing'])
            ->latest()
            ->get();

        $preselectedEventId = $request->query('media_event_id');

        return view('admin.scan-media.index', compact('events', 'preselectedEventId'));
    }

    public function lookup(Request $request)
    {
        $eventId = $request->input('media_event_id');

        // Search by name
        if ($request->filled('name')) {
            $keyword = trim($request->name);

            $media = MediaRegistration::where('status', 'Approved')
                ->where(function ($q) use ($keyword) {
                    $q->where('media_name', 'LIKE', "%{$keyword}%")
                        ->orWhere('full_name', 'LIKE', "%{$keyword}%");
                })
                ->orderBy('media_name')
                ->get();

            if ($media->isEmpty()) {
                return redirect()
                    ->back()
                    ->with('error', "Media dengan nama \"{$keyword}\" tidak ditemukan.");
            }

            if ($media->count() === 1) {
                return view('admin.scan-media.result', [
                    'media' => $media->first(),
                    'mediaEventId' => $eventId,
                ]);
            }

            return view('admin.scan-media.select', compact('media', 'eventId'));
        }

        // Search by barcode
        $input = trim($request->input('barcode', ''));

        if (empty($input)) {
            return redirect()
                ->back()
                ->with('error', 'Silakan masukkan barcode atau nama media.');
        }

        $media = MediaRegistration::where('barcode_token', $input)
            ->where('status', 'Approved')
            ->first();

        if (! $media) {
            return redirect()
                ->back()
                ->with('error', 'Barcode media tidak ditemukan atau status belum disetujui.');
        }

        return view('admin.scan-media.result', [
            'media' => $media,
            'mediaEventId' => $eventId,
        ]);
    }

    public function confirm(Request $request)
    {
        $request->validate([
            'media_registration_id' => 'required|exists:media_registrations,id',
            'media_event_id' => 'required|exists:media_events,id',
        ]);

        $media = MediaRegistration::findOrFail($request->media_registration_id);
        $event = MediaEvent::findOrFail($request->media_event_id);

        $attendance = MediaEventAttendance::firstOrCreate(
            [
                'media_event_id' => $event->id,
                'media_registration_id' => $media->id,
            ],
            ['status' => 'tidak_hadir']
        );

        if ($attendance->status === 'hadir') {
            return redirect('/admin/scan-media?media_event_id='.$event->id)
                ->with('warning', $media->full_name.' sudah tercatat hadir di "'.$event->title.'".');
        }

        $attendance->update([
            'status' => 'hadir',
            'scanned_at' => now(),
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Scan media: '.$media->full_name.
                ' ('.$media->media_name.') hadir di '.$event->title,
            'ip_address' => $request->ip(),
        ]);

        return redirect('/admin/scan-media?media_event_id='.$event->id)
            ->with('success', $media->full_name.' ('.$media->media_name.') tercatat hadir di "'.$event->title.'".');
    }
}
