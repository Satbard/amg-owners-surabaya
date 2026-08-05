<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\GuestbookEvent;
use Illuminate\Http\Request;

class GuestbookEventController extends Controller
{
    public function index()
    {
        $events = GuestbookEvent::latest()
            ->withCount('guestbooks')
            ->get();

        return view('admin.guestbooks.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.guestbooks.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'event_date' => 'required|date',
            'location' => 'nullable|max:255',
        ]);

        $event = GuestbookEvent::create($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Membuat acara guestbook: '.$event->title,
            'ip_address' => $request->ip(),
        ]);

        return redirect('/admin/guestbooks')
            ->with('success', 'Acara guestbook berhasil dibuat.');
    }

    public function show(GuestbookEvent $guestbookEvent)
    {
        $guestbookEvent->load(['guestbooks.fields', 'guestbooks.entries']);

        return view('admin.guestbooks.events.show', compact('guestbookEvent'));
    }

    public function edit(GuestbookEvent $guestbookEvent)
    {
        return view('admin.guestbooks.events.edit', compact('guestbookEvent'));
    }

    public function update(Request $request, GuestbookEvent $guestbookEvent)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'event_date' => 'required|date',
            'location' => 'nullable|max:255',
        ]);

        $guestbookEvent->update($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Mengupdate acara guestbook: '.$guestbookEvent->title,
            'ip_address' => $request->ip(),
        ]);

        return redirect('/admin/guestbooks')
            ->with('success', 'Acara guestbook berhasil diperbarui.');
    }

    public function destroy(GuestbookEvent $guestbookEvent)
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Menghapus acara guestbook: '.$guestbookEvent->title,
            'ip_address' => request()->ip(),
        ]);

        $guestbookEvent->delete();

        return redirect('/admin/guestbooks')
            ->with('success', 'Acara guestbook berhasil dihapus.');
    }
}
