<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\MediaRegistration;
use Illuminate\Http\Request;

class ScanMediaController extends Controller
{
    public function index()
    {
        return view('admin.scan-media.index');
    }

    public function lookup(Request $request)
    {
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
                return view('admin.scan-media.result', ['media' => $media->first()]);
            }

            // Multiple results — show selection
            return view('admin.scan-media.select', compact('media'));
        }

        // Search by barcode
        $input = trim($request->input('barcode', ''));

        if (empty($input)) {
            return redirect()
                ->back()
                ->with('error', 'Silakan masukkan barcode atau nama media.');
        }

        // Only find Approved media
        $media = MediaRegistration::where('barcode_token', $input)
            ->where('status', 'Approved')
            ->first();

        if (! $media) {
            return redirect()
                ->back()
                ->with('error', 'Barcode media tidak ditemukan atau status belum disetujui.');
        }

        return view('admin.scan-media.result', compact('media'));
    }

    public function confirm(Request $request)
    {
        $request->validate([
            'media_registration_id' => 'required|exists:media_registrations,id',
        ]);

        $media = MediaRegistration::findOrFail($request->media_registration_id);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Scan media: '.$media->full_name.
                ' ('.$media->media_name.') — hadir',
            'ip_address' => $request->ip(),
        ]);

        return redirect('/admin/scan-media')
            ->with('success', $media->full_name.' ('.$media->media_name.') tercatat hadir.');
    }
}
