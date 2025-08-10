<?php

namespace App\Http\Controllers;

use App\Mail\SendOtpMail;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserWebController extends Controller
{
    public function userRequestOtpBlade(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->email;

        // Rate limit
        $key = 'send-otp:' . $email;
        $maxAttempts = 3;
        $decayMinutes = 1;

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            return redirect()->back()->withErrors(['email' => 'Terlalu banyak permintaan. Coba lagi dalam ' . $seconds . ' detik.']);
        }

        RateLimiter::hit($key, $decayMinutes * 60);

        $otp = rand(100000, 999999);
        $hashedOtp = Hash::make((string) $otp);

        OtpCode::create([
            'email' => $email,
            'otp' => $hashedOtp,
            'expires_at' => now()->addMinutes(5),
        ]);

        // Kirim OTP lewat email
        Mail::to($email)->send(new SendOtpMail($otp));

        // Simpan email di session agar bisa dipakai di verifikasi
        session(['email' => $email]);

        return redirect()->route('verify')->with('success', 'OTP telah dikirim ke email Anda.');
    }

    public function userVerifyOtpBlade(Request $request)
    {
        $request->validate([
            'otp' => 'required|string'
        ]);

        $email = session('email');
        $otp = $request->otp;

        if (!$email) {
            return redirect()->route('login')->withErrors(['email' => 'Email tidak ditemukan dalam sesi.']);
        }

        $key = 'verify-otp:' . $email;
        $maxAttempts = 3;
        $decayMinutes = 1;

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            return redirect()->back()->withErrors(['otp' => 'Terlalu banyak percobaan. Coba lagi dalam ' . $seconds . ' detik.']);
        }

        $otpRecord = OtpCode::where('email', $email)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$otpRecord || !Hash::check((string)$otp, $otpRecord->otp)) {
            RateLimiter::hit($key, $decayMinutes * 60);
            return redirect()->back()->withErrors(['otp' => 'OTP tidak valid atau telah kedaluwarsa.']);
        }

        RateLimiter::clear($key);
        $otpRecord->delete();

        $user = User::firstOrCreate(
            ['email' => $email],
            ['full_name' => '', 'is_admin' => false]
        );

        Auth::login($user);

        return redirect()->route('admin')->with('success', 'Login berhasil.');
    }

    public function logout() {
        JWTAuth::invalidate(JWTAuth::getToken());
        
        return redirect()->route('login')->with('success', 'Logout berhasil.');
    }


}
