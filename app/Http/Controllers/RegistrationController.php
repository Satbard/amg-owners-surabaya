<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;
use App\Models\HomepageContent;

class RegistrationController extends Controller
{
    public function create()
    {
        $content = \App\Models\HomepageContent::first();

        return view(
            'public.register',
            compact('content')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate(

            [

                'full_name' => 'required|max:255',

                'nickname' => 'required|max:255',

                'birth_place' => 'required|max:255',

                'birth_date' => 'required|date',

                'address' => 'required',

                'phone' => 'required|max:50',

                'email' => 'required|email:rfc,dns|max:255',

                'instagram' => 'nullable|max:255',

                'occupation' => 'required|max:255',

                'shirt_size' => 'required',

                'vehicle_model' => 'required',

                'vehicle_year' => 'required|digits:4|integer|min:1900|max:' . date('Y'),

                'vehicle_color' => 'required|max:255',

                'license_plate' => 'required|max:50',

            ],

            [

                'full_name.required' =>
                    'Nama lengkap wajib diisi.',

                'nickname.required' =>
                    'Nama panggilan wajib diisi.',

                'birth_place.required' =>
                    'Tempat lahir wajib diisi.',

                'birth_date.required' =>
                    'Tanggal lahir wajib diisi.',

                'birth_date.date' =>
                    'Tanggal lahir tidak valid.',

                'address.required' =>
                    'Alamat domisili wajib diisi.',

                'phone.required' =>
                    'Nomor HP / WhatsApp wajib diisi.',

                'email.required' =>
                    'Email wajib diisi.',

                'email.email' =>
                    'Format email tidak valid.',

                'occupation.required' =>
                    'Pekerjaan / profesi wajib diisi.',

                'shirt_size.required' =>
                    'Ukuran kemeja / kaos wajib dipilih.',

                'vehicle_model.required' =>
                    'Tipe / model kendaraan wajib dipilih.',

                'vehicle_year.required' =>
                    'Tahun pembuatan kendaraan wajib diisi.',

                'vehicle_year.digits' =>
                    'Tahun kendaraan harus terdiri dari 4 digit.',

                'vehicle_year.integer' =>
                    'Tahun kendaraan harus berupa angka.',

                'vehicle_year.min' =>
                    'Tahun kendaraan tidak valid.',

                'vehicle_year.max' =>
                    'Tahun kendaraan tidak boleh melebihi tahun saat ini.',

                'vehicle_color.required' =>
                    'Nomor rangka / VIN wajib diisi',

                'license_plate.required' =>
                    'Nomor polisi wajib diisi.',

            ]

        );

        Registration::create($validated);

        return redirect('/')
            ->with(
                'success',
                'Pendaftaran berhasil dikirim.'
            );
    }
}