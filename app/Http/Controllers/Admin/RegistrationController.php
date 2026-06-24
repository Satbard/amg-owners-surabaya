<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;
use App\Exports\RegistrationsExport;
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
                    ->orWhere('license_plate', 'like', "%{$keyword}%");

            });
        }

        $registrations = $query
            ->latest()
            ->get();

        return view(
            'admin.registrations.index',
            compact('registrations')
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
            'AMG GT'
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
    )
    {
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

            'membership_status' => 'required'
        ]);

        $registration->update($validated);

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' =>
                'Mengubah data pendaftaran ID ' .
                $registration->id,
            'ip_address' => $request->ip()
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
    )
    {
        $registration->delete();

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' =>
                'Menghapus pendaftaran ID ' .
                $registration->id,
            'ip_address' => request()->ip()
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

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' =>
                'Restore pendaftaran ID ' .
                $registration->id,
            'ip_address' => request()->ip()
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

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' =>
                'Hapus permanen pendaftaran ID ' .
                $id,
            'ip_address' => request()->ip()
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
}