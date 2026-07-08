<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Event;
use App\Models\EventAttendance;
use App\Models\Registration;
use Illuminate\Http\Request;

class ScanController extends Controller
{
    public function index(Request $request)
    {
        $events = Event::whereIn('status', [
            'upcoming',
            'ongoing',
        ])->latest()->get();

        $preselectedEventId = $request->query('event_id');

        return view(
            'admin.scan.index',
            compact('events', 'preselectedEventId')
        );
    }

    public function lookup(Request $request)
    {
        $request->validate([
            'member_number' => 'required|string|max:255',
        ]);

        $input = trim($request->member_number);
        $member = null;

        // Try to look up by barcode_token first (new secure tokens)
        $member = Registration::where('barcode_token', $input)->first();

        // Fallback: look up by raw member_number (backward compatibility
        // for existing barcodes printed before token system was implemented)
        if (! $member) {
            $member = Registration::where(
                'member_number',
                $input
            )->first();
        }

        if (! $member) {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'Member dengan barcode tersebut tidak ditemukan.'
                );
        }

        // If event_id is provided, auto-confirm attendance
        if ($request->filled('event_id')) {
            $event = Event::find($request->event_id);

            if (! $event) {
                return redirect()
                    ->back()
                    ->with('error', 'Acara tidak ditemukan.');
            }

            $attendance = EventAttendance::firstOrCreate(
                [
                    'event_id' => $event->id,
                    'registration_id' => $member->id,
                ],
                [
                    'status' => 'tidak_hadir',
                ]
            );

            if ($attendance->status === 'hadir') {
                return redirect('/admin/events/'.$event->id)
                    ->with(
                        'warning',
                        $member->full_name.
                        ' sudah tercatat hadir.'
                    );
            }

            $attendance->update([
                'status' => 'hadir',
                'scanned_at' => now(),
            ]);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'activity' => 'Scan barcode: '.$member->full_name.
                    ' hadir di acara '.$event->title,
                'ip_address' => $request->ip(),
            ]);

            return redirect('/admin/events/'.$event->id)
                ->with(
                    'success',
                    $member->full_name.
                    ' tercatat hadir di "'.
                    $event->title.'".'
                );
        }

        // Global scan — show list of active events to choose from
        $activeEvents = Event::whereIn('status', [
            'upcoming',
            'ongoing',
        ])->latest()->get();

        return view(
            'admin.scan.result',
            compact('member', 'activeEvents')
        );
    }

    public function confirm(Request $request)
    {
        $request->validate([
            'registration_id' => 'required|exists:registrations,id',
            'event_id' => 'required|exists:events,id',
        ]);

        $attendance = EventAttendance::firstOrCreate(
            [
                'event_id' => $request->event_id,
                'registration_id' => $request->registration_id,
            ],
            [
                'status' => 'tidak_hadir',
            ]
        );

        if ($attendance->status === 'hadir') {
            return redirect()
                ->back()
                ->with(
                    'warning',
                    'Member sudah tercatat hadir di acara ini.'
                );
        }

        $attendance->update([
            'status' => 'hadir',
            'scanned_at' => now(),
        ]);

        $member = Registration::find($request->registration_id);
        $event = Event::find($request->event_id);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Scan global: '.$member->full_name.
                ' hadir di acara '.$event->title,
            'ip_address' => $request->ip(),
        ]);

        return redirect('/admin/scan')
            ->with(
                'success',
                $member->full_name.
                ' tercatat hadir di acara "'.
                $event->title.'".'
            );
    }
}
