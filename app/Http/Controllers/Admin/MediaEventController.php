<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\MediaEvent;
use App\Models\MediaEventAttendance;
use App\Models\MediaRegistration;
use Illuminate\Http\Request;

class MediaEventController extends Controller
{
    public function index()
    {
        $events = MediaEvent::latest()->with('attendances.mediaRegistration')->get();

        foreach ($events as $event) {
            $event->attendances = $event->attendances->filter(function ($att) {
                return $att->mediaRegistration !== null;
            })->values();
        }

        return view('admin.media-events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.media-events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'event_date' => 'required|date',
            'location' => 'nullable|max:255',
        ]);

        $validated['status'] = 'upcoming';

        $event = MediaEvent::create($validated);

        // Auto-add all approved media to this event
        $approvedMedia = MediaRegistration::where('status', 'Approved')->get();

        foreach ($approvedMedia as $media) {
            MediaEventAttendance::create([
                'media_event_id' => $event->id,
                'media_registration_id' => $media->id,
                'status' => 'tidak_hadir',
            ]);
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Membuat acara media: '.$event->title,
            'ip_address' => $request->ip(),
        ]);

        return redirect('/admin/media-events')
            ->with('success', 'Acara media berhasil dibuat.');
    }

    public function show(MediaEvent $mediaEvent)
    {
        $mediaEvent->load('attendances.mediaRegistration');

        // Filter orphaned
        $mediaEvent->attendances = $mediaEvent->attendances->filter(function ($att) {
            return $att->mediaRegistration !== null;
        })->values();

        return view('admin.media-events.show', compact('mediaEvent'));
    }

    public function edit(MediaEvent $mediaEvent)
    {
        return view('admin.media-events.edit', compact('mediaEvent'));
    }

    public function update(Request $request, MediaEvent $mediaEvent)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'event_date' => 'required|date',
            'location' => 'nullable|max:255',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
        ]);

        $mediaEvent->update($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Mengubah acara media: '.$mediaEvent->title,
            'ip_address' => $request->ip(),
        ]);

        return redirect('/admin/media-events')
            ->with('success', 'Acara media berhasil diperbarui.');
    }

    public function destroy(MediaEvent $mediaEvent)
    {
        $mediaEvent->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Menghapus acara media: '.$mediaEvent->title,
            'ip_address' => request()->ip(),
        ]);

        return redirect('/admin/media-events')
            ->with('success', 'Acara media berhasil dihapus.');
    }
}
