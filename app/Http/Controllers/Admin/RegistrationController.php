<?php

namespace App\Http\Controllers\Admin;

use App\Exports\RegistrationsExport;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Event;
use App\Models\EventAttendance;
use App\Models\Registration;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        $query = Registration::query();

        if ($request->filled('keyword')) {

            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {

                $q->where('full_name', 'like', "%{$keyword}%")
                    ->orWhere('phone', 'like', "%{$keyword}%")
                    ->orWhere('license_plate', 'like', "%{$keyword}%")
                    ->orWhere('member_number', 'like', "%{$keyword}%");

            });
        }

        if ($request->filled('status')) {
            $query->where('membership_status', $request->status);
        }

        $registrations = $query
            ->latest()
            ->get();

        $status = $request->status;
        $keyword = $request->keyword;

        return view(
            'admin.registrations.index',
            compact('registrations', 'status', 'keyword')
        );
    }

    public function show(Registration $registration)
    {
        return view(
            'admin.registrations.show',
            compact('registration')
        );
    }

    public function edit(Registration $registration)
    {
        $vehicleModels = [
            'A-Class',
            'C-Class',
            'CLA',
            'CLS',
            'E-Class',
            'GLA',
            'GLB',
            'GLC',
            'GLE',
            'GLS',
            'SL',
            'AMG GT',
        ];

        return view(
            'admin.registrations.edit',
            compact(
                'registration',
                'vehicleModels'
            )
        );
    }

    public function update(
        Request $request,
        Registration $registration
    ) {
        $validated = $request->validate([

            'full_name' => 'required|max:255',
            'nickname' => 'required|max:255',

            'birth_place' => 'required|max:255',
            'birth_date' => 'required|date',

            'address' => 'required',

            'phone' => 'required',

            'email' => 'required|email:rfc,dns|max:255',

            'instagram' => 'nullable',

            'occupation' => 'required',

            'shirt_size' => 'required',

            'vehicle_model' => 'required',

            'vehicle_year' => 'required',

            'vehicle_color' => 'required',

            'license_plate' => 'required',

            'membership_status' => 'required',
        ]);

        $registration->update($validated);

        // Generate member_number and auto-add to active events when approved
        if (
            $validated['membership_status'] === 'Approved' &&
            ! $registration->member_number
        ) {
            $registration->member_number =
                Registration::generateMemberNumber(
                    $registration->id
                );
            $registration->save();

            // Auto-add to all active events
            $activeEvents = Event::whereIn(
                'status',
                ['upcoming', 'ongoing']
            )->get();

            foreach ($activeEvents as $event) {
                EventAttendance::firstOrCreate([
                    'event_id' => $event->id,
                    'registration_id' => $registration->id,
                ], [
                    'status' => 'tidak_hadir',
                ]);
            }
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Mengubah data pendaftaran ID '.
                $registration->id,
            'ip_address' => $request->ip(),
        ]);

        return redirect(
            '/admin/registrations'
        )->with(
            'success',
            'Data berhasil diperbarui.'
        );
    }

    public function destroy(
        Registration $registration
    ) {
        $registration->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Menghapus pendaftaran ID '.
                $registration->id,
            'ip_address' => request()->ip(),
        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'Data dipindahkan ke Trash Bin.'
            );
    }

    public function trash()
    {
        $registrations = Registration::onlyTrashed()
            ->latest()
            ->get();

        return view(
            'admin.registrations.trash',
            compact('registrations')
        );
    }

    public function restore($id)
    {
        $registration = Registration::onlyTrashed()
            ->findOrFail($id);

        $registration->restore();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Restore pendaftaran ID '.
                $registration->id,
            'ip_address' => request()->ip(),
        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'Data berhasil dipulihkan.'
            );
    }

    public function forceDelete($id)
    {
        $registration = Registration::onlyTrashed()
            ->findOrFail($id);

        $registration->forceDelete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Hapus permanen pendaftaran ID '.
                $id,
            'ip_address' => request()->ip(),
        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'Data dihapus permanen.'
            );
    }

    public function export()
    {
        return Excel::download(
            new RegistrationsExport,
            'registrations.xlsx'
        );
    }

    public function batchUpdate(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:registrations,id',
            'action' => 'required|in:approve,reject,pending',
        ]);

        $statusMap = [
            'approve' => 'Approved',
            'reject' => 'Rejected',
            'pending' => 'Pending',
        ];

        $newStatus = $statusMap[$request->action];
        $count = 0;

        foreach ($request->ids as $id) {
            $registration = Registration::findOrFail($id);

            // Only process if truly changing status
            $oldStatus = $registration->membership_status;

            $registration->update(['membership_status' => $newStatus]);

            // If newly approved, generate member_number & auto-add to active events
            if ($newStatus === 'Approved' && ! $registration->member_number) {
                $registration->member_number = Registration::generateMemberNumber($registration->id);
                $registration->save();

                $activeEvents = Event::whereIn('status', ['upcoming', 'ongoing'])->get();

                foreach ($activeEvents as $event) {
                    EventAttendance::firstOrCreate([
                        'event_id' => $event->id,
                        'registration_id' => $registration->id,
                    ], [
                        'status' => 'tidak_hadir',
                    ]);
                }
            }

            $count++;
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => "Batch update {$count} registrasi menjadi {$newStatus}",
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->back()
            ->with('success', "{$count} data berhasil diperbarui menjadi {$newStatus}.");
    }
}
