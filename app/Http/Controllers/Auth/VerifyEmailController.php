<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ThankYouMail;

class VerifyEmailController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = User::findOrFail($request->route('id'));

        // 🔒 Validate the hash
        if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            abort(403, 'Invalid verification link.');
        }

        // 🔁 Already verified
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('message', 'Your email is already verified. Please log in.');
        }

        // ✅ Mark email as verified
        $user->markEmailAsVerified();
        event(new Verified($user));

        Mail::to($user->email)->send(new ThankYouMail($user->name));

        // 🎉 Redirect to login with message
        return redirect()->route('login')->with('message', 'Your email has been successfully verified. You can now log in.');
    }
}