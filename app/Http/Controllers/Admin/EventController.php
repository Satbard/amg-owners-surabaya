<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Event;
use App\Models\EventAttendance;
use App\Models\Registration;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::latest()->get();

        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
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

        $event = Event::create($validated);

        // Auto-add all approved members to this event
        $approvedMembers = Registration::where('membership_status', 'Approved')
            ->whereNotNull('member_number')
            ->get();

        foreach ($approvedMembers as $member) {
            EventAttendance::create([
                'event_id' => $event->id,
                'registration_id' => $member->id,
                'status' => 'tidak_hadir',
            ]);
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Membuat acara baru: '.$event->title,
            'ip_address' => $request->ip(),
        ]);

        return redirect('/admin/events')
            ->with('success', 'Acara berhasil dibuat.');
    }

    public function show(Event $event)
    {
        $event->load(['attendances.registration']);

        return view('admin.events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'event_date' => 'required|date',
            'location' => 'nullable|max:255',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
        ]);

        $event->update($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Mengupdate acara: '.$event->title,
            'ip_address' => $request->ip(),
        ]);

        return redirect('/admin/events')
            ->with('success', 'Acara berhasil diperbarui.');
    }

    public function destroy(Event $event)
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Menghapus acara: '.$event->title,
            'ip_address' => request()->ip(),
        ]);

        $event->delete();

        return redirect('/admin/events')
            ->with('success', 'Acara berhasil dihapus.');
    }
}
