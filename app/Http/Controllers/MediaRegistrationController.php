<?php

namespace App\Http\Controllers;

use App\Models\HomepageContent;
use App\Models\MediaRegistration;
use Illuminate\Http\Request;

class MediaRegistrationController extends Controller
{
    public function create()
    {
        $content = HomepageContent::first();

        return view('public.register-media', compact('content'));
    }

    public function store(Request $request)
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

            // Terms
            'terms_agreed' => 'accepted',

        ], [

            // Personal Information
            'full_name.required' => 'Nama lengkap wajib diisi.',
            'media_name.required' => 'Nama media wajib diisi.',
            'position.required' => 'Pilih minimal satu posisi.',
            'position.array' => 'Format posisi tidak valid.',

            // Contact
            'phone.required' => 'Nomor HP / WhatsApp wajib diisi.',
            'phone.regex' => 'Nomor HP hanya boleh berisi angka dan tanda +.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',

            // Media Information
            'social_media.required' => 'Akun media sosial wajib diisi.',
            'media_type.required' => 'Jenis media wajib dipilih.',

            // Competition
            'competition_category.required' => 'Kategori lomba wajib dipilih.',
            'equipment_used.required' => 'Equipment yang digunakan wajib dipilih.',

            // Terms
            'terms_agreed.accepted' => 'Anda harus menyetujui seluruh ketentuan yang berlaku.',

        ]);

        MediaRegistration::create($validated);

        return redirect('/')
            ->with('success', 'Pendaftaran media berhasil dikirim.');
    }
}
