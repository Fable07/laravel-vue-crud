<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class OtpController extends Controller
{
    public function show()
    {
        if (session('otp_verified')) {
            return redirect()->route('dashboard');
        }

        return Inertia::render('Auth/OtpVerify');
    }

    public function send(Request $request)
    {
        $user = $request->user();

        $key = 'otp-send:' . $user->id;
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'code' => "Too many attempts. Try again in {$seconds} seconds.",
            ]);
        }
        RateLimiter::hit($key, 60);

        // Invalidate previous OTPs
        Otp::query()->where('user_id', $user->id)->update(['used' => true]);

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        Otp::create([
            'user_id'    => $user->id,
            'code'       => $code,
            'expires_at' => now()->addMinutes(10),
            'used'       => false,
        ]);

        Mail::to($user->email)->queue(new OtpMail($code));

        return back()->with('status', 'OTP sent to your email.');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = $request->user();

        $otp = Otp::query()->where('user_id', $user->id)
            ->where('code', $request->input('code'))
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$otp) {
            throw ValidationException::withMessages([
                'code' => 'Invalid or expired OTP.',
            ]);
        }

        $otp->update(['used' => true]);
        session(['otp_verified' => true]);

        RateLimiter::clear('otp-send:' . $user->id);

        return redirect()->intended(route('products.index'));
    }
}
