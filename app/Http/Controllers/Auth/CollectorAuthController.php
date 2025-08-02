<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollectorAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.collector_login'); // Create this Blade file
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'collector',
        ])) {
            return redirect()->route('collector.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials or role']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('collector.login.form');
    }
}
