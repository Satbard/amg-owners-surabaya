<?php

namespace App\Http\Controllers;

use App\Mail\MediaBarcodeMail;
use App\Models\HomepageContent;
use App\Models\MediaRegistration;
use App\Services\BarcodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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

            // Terms — each must be checked individually
            'term_1' => 'accepted',
            'term_2' => 'accepted',
            'term_3' => 'accepted',

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
            'term_1.accepted' => 'Anda harus menyetujui seluruh ketentuan yang berlaku.',
            'term_2.accepted' => 'Anda harus menyetujui seluruh ketentuan yang berlaku.',
            'term_3.accepted' => 'Anda harus menyetujui seluruh ketentuan yang berlaku.',

        ]);

        // Set terms_agreed = true since all 3 terms were validated as accepted
        $validated['terms_agreed'] = true;

        // Generate unique barcode token
        do {
            $token = BarcodeService::generateToken();
        } while (MediaRegistration::where('barcode_token', $token)->exists());

        $validated['barcode_token'] = $token;

        $registration = MediaRegistration::create($validated);

        // Send email with barcode (use send() instead of queue() to avoid queue dependency)
        try {
            Mail::to($registration->email)->send(new MediaBarcodeMail($registration));
        } catch (\Throwable $e) {
            // Log email failure but don't block registration
            Log::warning('Failed to send media barcode email: '.$e->getMessage());
        }

        return redirect('/')
            ->with('success', 'Pendaftaran media berhasil dikirim.')
            ->with('barcode_sent', true)
            ->with('media_email', $registration->email);
    }
}
