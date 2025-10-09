<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Mail\ThankYouMail;
use Illuminate\Support\Facades\Mail;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle the incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate incoming request
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Create user with 'resident' role and null email_verified_at
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'resident',
        ]);

        // ✅ Dispatch verification email
        event(new Registered($user));

        $message = 'We sent a verification link to your email. Please verify before logging in.';

        // ⛔ Do NOT log in automatically
        // Auth::login($user); // commented out to require email verification first

        // ✅ Redirect to verification notice
        return redirect()->route('verification.notice')
                         ->with('status', $message);
    }
}