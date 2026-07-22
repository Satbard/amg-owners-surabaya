<?php

namespace App\Http\Controllers;

use App\Mail\MediaBarcodeMail;
use App\Mail\MediaOtpMail;
use App\Models\MediaOtp;
use App\Models\MediaRegistration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Picqer\Barcode\BarcodeGeneratorPNG;

class MediaAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('public.media-login');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email:rfc,dns|exists:media_registrations,email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak ditemukan dalam pendaftaran media.',
        ]);

        $registration = MediaRegistration::where('email', $request->email)->first();

        // Generate 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Save OTP
        MediaOtp::create([
            'media_registration_id' => $registration->id,
            'otp' => $otp,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // Send OTP email
        Mail::to($registration->email)->queue(new MediaOtpMail($registration, $otp));

        // Store email in session for verify step
        session()->put('media_login_email', $registration->email);

        return redirect('/media-login/verify')
            ->with('success', 'Kode OTP telah dikirim ke email Anda.');
    }

    public function showVerifyForm()
    {
        if (! session()->has('media_login_email')) {
            return redirect('/media-login');
        }

        return view('public.media-login-verify');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ], [
            'otp.required' => 'Kode OTP wajib diisi.',
            'otp.size' => 'Kode OTP harus 6 digit.',
        ]);

        $email = session('media_login_email');

        if (! $email) {
            return redirect('/media-login')
                ->with('error', 'Sesi login telah berakhir. Silakan mulai lagi.');
        }

        $registration = MediaRegistration::where('email', $email)->first();

        if (! $registration) {
            return redirect('/media-login')
                ->with('error', 'Data tidak ditemukan.');
        }

        // Find valid OTP
        $otpRecord = MediaOtp::where('media_registration_id', $registration->id)
            ->where('otp', $request->otp)
            ->whereNull('used_at')
            ->where('expires_at', '>', Carbon::now())
            ->latest()
            ->first();

        if (! $otpRecord) {
            return back()->withErrors([
                'otp' => 'Kode OTP tidak valid atau sudah kedaluwarsa.',
            ]);
        }

        // Mark OTP as used
        $otpRecord->update(['used_at' => Carbon::now()]);

        // Create session
        session()->put('media_registration_id', $registration->id);
        session()->forget('media_login_email');

        return redirect('/media-dashboard')
            ->with('success', 'Selamat datang, '.$registration->full_name.'!');
    }

    public function dashboard()
    {
        $registrationId = session('media_registration_id');
        $registration = MediaRegistration::findOrFail($registrationId);

        // Generate barcode inline
        $barcodeSrc = null;
        try {
            if (function_exists('imagecreate') && $registration->barcode_token) {
                $generator = new BarcodeGeneratorPNG;
                $barcodeData = $generator->getBarcode(
                    $registration->barcode_token,
                    $generator::TYPE_CODE_128,
                    2,
                    50
                );
                $barcodeSrc = 'data:image/png;base64,'.base64_encode($barcodeData);
            }
        } catch (\Exception $e) {
            $barcodeSrc = null;
        }

        return view('public.media-dashboard', compact('registration', 'barcodeSrc'));
    }

    public function resendBarcode()
    {
        $registrationId = session('media_registration_id');
        $registration = MediaRegistration::findOrFail($registrationId);

        Mail::to($registration->email)->queue(new MediaBarcodeMail($registration));

        return redirect('/media-dashboard')
            ->with('success', 'Barcode telah dikirim ulang ke email '.$registration->email.'.');
    }

    public function logout()
    {
        session()->forget('media_registration_id');
        session()->forget('media_login_email');

        return redirect('/media-login')
            ->with('success', 'Anda telah berhasil logout.');
    }
}
