<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageContent;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomepageContentController extends Controller
{
    public function edit()
    {
        $content = HomepageContent::firstOrCreate(
            ['id' => 1],
            [
                'title' => 'AMG Owners Surabaya',
                'description' => 'Selamat datang di website resmi AMG Owners Surabaya.',
                'button_text' => 'Daftar Sekarang'
            ]
        );

        return view(
            'admin.content.edit',
            compact('content')
        );
    }

    public function update(Request $request)
    {
        $content = HomepageContent::firstOrCreate(
            ['id' => 1],
            [
                'title' => 'AMG Owners Surabaya',
                'description' => 'Selamat datang di website resmi AMG Owners Surabaya.',
                'button_text' => 'Daftar Sekarang'
            ]
        );

        $validated = $request->validate(

            [

                'title' =>
                    'required|max:255',

                'description' =>
                    'required',

                'button_text' =>
                    'required|max:255',

                'logo' =>
                    'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

                'header_logo' =>
                    'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

                'background' =>
                    'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',

                'registration_background' =>
                    'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',

            ],

            [

                'title.required' =>
                    'Judul homepage wajib diisi.',

                'description.required' =>
                    'Deskripsi homepage wajib diisi.',

                'button_text.required' =>
                    'Tulisan tombol wajib diisi.',

                'logo.image' =>
                    'Logo harus berupa gambar.',

                'logo.mimes' =>
                    'Logo harus berformat JPG, JPEG, PNG, atau WEBP.',

                'logo.max' =>
                    'Ukuran logo maksimal 2 MB.',

                'header_logo.image' =>
                    'Logo header harus berupa gambar.',

                'header_logo.mimes' =>
                    'Logo header harus berformat JPG, JPEG, PNG, atau WEBP.',

                'header_logo.max' =>
                    'Ukuran logo header maksimal 2 MB.',

                'background.image' =>
                    'Background homepage harus berupa gambar.',

                'background.mimes' =>
                    'Background homepage harus berformat JPG, JPEG, PNG, atau WEBP.',

                'background.max' =>
                    'Ukuran background homepage maksimal 4 MB.',

                'registration_background.image' =>
                    'Background halaman pendaftaran harus berupa gambar.',

                'registration_background.mimes' =>
                    'Background halaman pendaftaran harus berformat JPG, JPEG, PNG, atau WEBP.',

                'registration_background.max' =>
                    'Ukuran background halaman pendaftaran maksimal 4 MB.',

            ]

        );

        /*
        |--------------------------------------------------------------------------
        | Upload Logo
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('logo')) {

            if (!empty($content->logo)) {

                Storage::disk('public')->delete(
                    $content->logo
                );

            }

            $validated['logo'] =
                $request->file('logo')
                    ->store(
                        'homepage/logo',
                        'public'
                    );
        }

        /*
        |--------------------------------------------------------------------------
        | Upload Header Logo
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('header_logo')) {

            if (!empty($content->header_logo)) {

                Storage::disk('public')->delete(
                    $content->header_logo
                );

            }

            $validated['header_logo'] =
                $request->file('header_logo')
                    ->store(
                        'homepage/header-logo',
                        'public'
                    );
        }

        /*
        |--------------------------------------------------------------------------
        | Upload Homepage Background
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('background')) {

            if (!empty($content->background)) {

                Storage::disk('public')->delete(
                    $content->background
                );

            }

            $validated['background'] =
                $request->file('background')
                    ->store(
                        'homepage/background',
                        'public'
                    );
        }

        /*
        |--------------------------------------------------------------------------
        | Upload Registration Background
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('registration_background')) {

            if (!empty($content->registration_background)) {

                Storage::disk('public')->delete(
                    $content->registration_background
                );

            }

            $validated['registration_background'] =
                $request->file('registration_background')
                    ->store(
                        'homepage/registration-background',
                        'public'
                    );
        }

        /*
        |--------------------------------------------------------------------------
        | Save Data
        |--------------------------------------------------------------------------
        */

        $validated['updated_by'] = auth()->id();

        $content->update($validated);

        /*
        |--------------------------------------------------------------------------
        | Activity Log
        |--------------------------------------------------------------------------
        */

        ActivityLog::create([

            'user_id' => auth()->id(),

            'activity' =>
                'Mengubah Homepage CMS',

            'ip_address' =>
                $request->ip()

        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'Homepage berhasil diperbarui.'
            );
    }
}