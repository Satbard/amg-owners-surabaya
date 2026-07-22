<?php

namespace App\Http\Controllers\Admin;

use App\Exports\MediaRegistrationsExport;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\MediaRegistration;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MediaRegistrationController extends Controller
{
    public function index(Request $request)
    {
        $query = MediaRegistration::query();

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('full_name', 'like', "%{$keyword}%")
                    ->orWhere('phone', 'like', "%{$keyword}%")
                    ->orWhere('media_name', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $registrations = $query
            ->latest()
            ->get();

        $status = $request->status;
        $keyword = $request->keyword;

        return view(
            'admin.media-registrations.index',
            compact('registrations', 'status', 'keyword')
        );
    }

    public function show(MediaRegistration $mediaRegistration)
    {
        return view(
            'admin.media-registrations.show',
            compact('mediaRegistration')
        );
    }

    public function edit(MediaRegistration $mediaRegistration)
    {
        return view(
            'admin.media-registrations.edit',
            compact('mediaRegistration')
        );
    }

    public function update(Request $request, MediaRegistration $mediaRegistration)
    {
        $validated = $request->validate([

            // Personal Information
            'full_name' => 'required|max:255',
            'media_name' => 'required|max:255',
            'position' => 'required|array',
            'position.*' => 'in:Photographer,Videographer,Journalist,Content Creator,Others',

            // Contact
            'phone' => [
                'required',
                'max:50',
                'regex:/^[0-9+]+$/',
            ],
            'email' => 'required|email:rfc,dns|max:255',

            // Media Information
            'social_media' => 'required|max:255',
            'followers' => 'nullable|max:255',
            'media_type' => 'required|in:Print,Online,TV,Radio,Digital Creator,Community Media,Others',

            // Competition Registration
            'competition_category' => 'required|in:Photography,Videography / Reels',
            'equipment_used' => 'required|in:Camera,Smartphone,Drone',

            // Status
            'status' => 'required|in:Pending,Approved,Rejected',
        ]);

        $mediaRegistration->update($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Mengubah pendaftaran media ID '.$mediaRegistration->id,
            'ip_address' => $request->ip(),
        ]);

        return redirect('/admin/media-registrations')
            ->with('success', 'Data pendaftaran media berhasil diperbarui.');
    }

    public function destroy(MediaRegistration $mediaRegistration)
    {
        $mediaRegistration->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Menghapus pendaftaran media ID '.$mediaRegistration->id,
            'ip_address' => request()->ip(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Data dipindahkan ke Trash Bin.');
    }

    public function trash()
    {
        $registrations = MediaRegistration::onlyTrashed()
            ->latest()
            ->get();

        return view(
            'admin.media-registrations.trash',
            compact('registrations')
        );
    }

    public function restore($id)
    {
        $registration = MediaRegistration::onlyTrashed()
            ->findOrFail($id);

        $registration->restore();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Restore pendaftaran media ID '.$registration->id,
            'ip_address' => request()->ip(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Data berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        $registration = MediaRegistration::onlyTrashed()
            ->findOrFail($id);

        $registration->forceDelete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Hapus permanen pendaftaran media ID '.$id,
            'ip_address' => request()->ip(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Data dihapus permanen.');
    }

    public function batchUpdate(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:media_registrations,id',
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
            $registration = MediaRegistration::findOrFail($id);
            $registration->update(['status' => $newStatus]);
            $count++;
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => "Batch update {$count} pendaftaran media menjadi {$newStatus}",
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->back()
            ->with('success', "{$count} data berhasil diperbarui menjadi {$newStatus}.");
    }

    public function export()
    {
        return Excel::download(
            new MediaRegistrationsExport,
            'media-registrations.xlsx'
        );
    }
}
