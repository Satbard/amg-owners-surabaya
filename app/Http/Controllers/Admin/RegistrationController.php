<?php

namespace App\Http\Controllers\Admin;

use App\Exports\RegistrationsExport;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Event;
use App\Models\EventAttendance;
use App\Models\Registration;
use App\Services\BarcodeService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Picqer\Barcode\BarcodeGeneratorPNG;
use ZipArchive;

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

        // Generate member_number + barcode_token when newly approved
        if (
            $validated['membership_status'] === 'Approved' &&
            ! $registration->member_number
        ) {
            $registration->member_number =
                Registration::generateMemberNumber(
                    $registration->id
                );

            // Generate unique barcode token
            do {
                $token = BarcodeService::generateToken();
            } while (Registration::where('barcode_token', $token)->exists());

            $registration->barcode_token = $token;
            $registration->save();
        }

        // Auto-add approved members to all active events
        if ($validated['membership_status'] === 'Approved') {
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

        // Remove rejected members from all active event attendance lists
        if ($validated['membership_status'] === 'Rejected') {
            EventAttendance::whereIn('event_id', function ($q) {
                $q->select('id')->from('events')
                    ->whereIn('status', ['upcoming', 'ongoing']);
            })->where('registration_id', $registration->id)
                ->delete();
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

        // Re-add to active events in case new events were created while deleted
        if ($registration->membership_status === 'Approved') {
            $activeEvents = Event::whereIn(
                'status',
                ['upcoming', 'ongoing']
            )->get();

            $addedCount = 0;
            foreach ($activeEvents as $event) {
                $attendance = EventAttendance::firstOrCreate([
                    'event_id' => $event->id,
                    'registration_id' => $registration->id,
                ], [
                    'status' => 'tidak_hadir',
                ]);

                if ($attendance->wasRecentlyCreated) {
                    $addedCount++;
                }
            }
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Restore pendaftaran ID '.
                $registration->id,
            'ip_address' => request()->ip(),
        ]);

        $message = 'Data berhasil dipulihkan.';
        if (isset($addedCount) && $addedCount > 0) {
            $message .= " {$addedCount} acara aktif diperbarui.";
        }

        return redirect()
            ->back()
            ->with('success', $message);
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

    public function exportBarcodes()
    {
        $approvedMembers = Registration::where('membership_status', 'Approved')
            ->whereNotNull('member_number')
            ->get();

        if ($approvedMembers->isEmpty()) {
            return redirect()
                ->back()
                ->with('error', 'Tidak ada member approved dengan barcode.');
        }

        // Create temp directory
        $tempDir = storage_path('app/private/barcode-exports/'.now()->timestamp);
        if (! is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $generator = new BarcodeGeneratorPNG;

        foreach ($approvedMembers as $member) {
            try {
                // Use barcode_token as barcode content
                $barcodeContent = $member->barcode_token;

                // Skip members without a token yet
                if (! $barcodeContent) {
                    continue;
                }

                // Generate barcode (transparent background by default)
                $barcodeData = $generator->getBarcode(
                    $barcodeContent,
                    $generator::TYPE_CODE_128,
                    2,  // width factor
                    50, // height
                );

                // Add white background using GD
                $barcodeImg = imagecreatefromstring($barcodeData);
                $width = imagesx($barcodeImg);
                $height = imagesy($barcodeImg);

                // Create white canvas with padding
                $padding = 20;
                $canvasW = $width + ($padding * 2);
                $canvasH = $height + ($padding * 2);
                $canvas = imagecreatetruecolor($canvasW, $canvasH);

                // Fill with white background
                $white = imagecolorallocate($canvas, 255, 255, 255);
                imagefill($canvas, 0, 0, $white);

                // Copy barcode onto white canvas
                imagecopy($canvas, $barcodeImg, $padding, $padding, 0, 0, $width, $height);

                // Save as PNG
                $safeName = preg_replace('/[^A-Za-z0-9_-]/', '_', $member->full_name);
                imagepng($canvas, $tempDir.'/'.$safeName.'.png');

                // Free memory
                imagedestroy($barcodeImg);
                imagedestroy($canvas);
            } catch (\Exception $e) {
                // Skip individual member if barcode generation fails
                continue;
            }
        }

        // Create ZIP archive
        $zipPath = storage_path('app/private/barcode-exports/barcodes-'.now()->timestamp.'.zip');
        $zip = new ZipArchive;

        if ($zip->open($zipPath, ZipArchive::CREATE) !== true) {
            // Cleanup temp dir
            array_map('unlink', glob($tempDir.'/*.*'));
            rmdir($tempDir);

            return redirect()
                ->back()
                ->with('error', 'Gagal membuat file ZIP.');
        }

        // Add files to ZIP
        $files = glob($tempDir.'/*.png');
        foreach ($files as $file) {
            $zip->addFile($file, basename($file));
        }
        $zip->close();

        // Cleanup temp directory
        array_map('unlink', glob($tempDir.'/*.*'));
        rmdir($tempDir);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Export barcode: '.count($files).' barcode member',
            'ip_address' => request()->ip(),
        ]);

        return response()->download($zipPath, 'barcodes.zip')->deleteFileAfterSend(true);
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

            // Generate member_number + barcode_token when newly approved
            if ($newStatus === 'Approved' && ! $registration->member_number) {
                $registration->member_number = Registration::generateMemberNumber($registration->id);

                // Generate unique barcode token
                do {
                    $token = BarcodeService::generateToken();
                } while (Registration::where('barcode_token', $token)->exists());

                $registration->barcode_token = $token;
                $registration->save();
            }

            // Auto-add approved members to all active events
            if ($newStatus === 'Approved') {
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

            // Remove rejected members from all active event attendance lists
            if ($newStatus === 'Rejected') {
                EventAttendance::whereIn('event_id', function ($q) {
                    $q->select('id')->from('events')
                        ->whereIn('status', ['upcoming', 'ongoing']);
                })->where('registration_id', $registration->id)
                    ->delete();
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
