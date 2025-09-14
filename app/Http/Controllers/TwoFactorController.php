<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Mail\OTPVerificationMail;

class TwoFactorController extends Controller
{
    public function showVerifyForm()
    {
        $userId = session('2fa:user:id');
        if (!$userId) return redirect()->route('login');

        // Generate OTP and send via email (if not already sent)
        $otp = rand(100000, 999999);
        session(['2fa:otp' => $otp]);

        $user = User::findOrFail($userId);

        // Email logic
        Mail::to($user->email)->send(new OTPVerificationMail($otp));

        return view('auth.2fa-verify');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6'
        ]);

        $userId = session('2fa:user:id');
        if (!$userId) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'No active 2FA session'], 401);
            }
            return redirect()->route('login');
        }

        if ($request->otp == session('2fa:otp')) {
            $user = User::findOrFail($userId);
            Auth::login($user);
            $request->session()->regenerate();

            // Clear 2FA session values
            session()->forget(['2fa:user:id', '2fa:otp']);

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'redirect' => route('resident.dashboard')]);
            }

            return redirect()->route('resident.dashboard');
        }

        if ($request->expectsJson()) {
            return response()->json(['error' => 'Invalid OTP'], 422);
        }

        return redirect()->route('login')->with([
            'show_2fa_modal' => true,
            'otp_error' => 'Invalid OTP. Please try again.',
        ]);
    }

    public function resendOtp(Request $request)
    {
        $userId = session('2fa:user:id');
        if (!$userId) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'No active 2FA session'], 401);
            }
            return redirect()->route('login');
        }

        $user = User::findOrFail($userId);

        // Regenerate and store new OTP
        $otp = rand(100000, 999999);
        session(['2fa:otp' => $otp]);

        // Send email
        Mail::to($user->email)->send(new OTPVerificationMail($otp));

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'OTP sent successfully']);
        }

        return redirect()->route('login')->with([
            'show_2fa_modal' => true,
            'otp_success' => 'A new OTP has been sent to your email.',
        ]);
    }
}
