<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Exception;

class GoogleController extends Controller
{
    /**
     * Redirect to Google for authentication
     */
    public function redirectToGoogle()
    {
        // Store the intent (signup or signin) in session
        $intent = request()->query('intent', 'signin'); // default to signin
        session(['google_auth_intent' => $intent]);
        
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google callback - behavior depends on intent
     */
    public function handleGoogleCallback()
    {
        try {
            \Log::info('Google callback started');
            
            $googleUser = Socialite::driver('google')->user();
            \Log::info('Google user data received', [
                'id' => $googleUser->id,
                'name' => $googleUser->name,
                'email' => $googleUser->email
            ]);

            // Get the intent from session
            $intent = session('google_auth_intent', 'signin');
            \Log::info('Google auth intent: ' . $intent);
            
            // Check if user already exists with this email
            $existingUser = User::where('email', $googleUser->email)->first();
            
            if ($intent === 'signup') {
                // SIGN UP FLOW - Only for registration page
                if ($existingUser) {
                    \Log::info('User already exists during signup attempt', ['email' => $googleUser->email]);
                    
                    // If user exists but doesn't have Google ID, link it
                    if (!$existingUser->google_id) {
                        $existingUser->update([
                            'google_id' => $googleUser->id,
                            'avatar' => $googleUser->avatar,
                            'email_verified_at' => $existingUser->email_verified_at ?? now(),
                        ]);
                        \Log::info('Linked Google ID to existing user during signup');
                    }
                    
                    session()->forget('google_auth_intent'); // Clear the intent
                    return redirect()->route('login')->with('error', 
                        'An account with this email already exists. Please sign in instead.');
                }
                
                \Log::info('Creating new user from Google signup');
                // Create new user
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'password' => Hash::make(Str::random(32)),
                    'role' => 'resident',
                    'email_verified_at' => now(),
                ]);
                
                \Log::info('User created successfully, logging in', ['user_id' => $user->id]);
                
                // Log the new user in
                Auth::login($user);
                
                session()->forget('google_auth_intent'); // Clear the intent after successful signup
                return redirect()->route('resident.dashboard')->with('success', 
                    'Welcome to CleanCity! Your account has been created and you are now logged in.');
                    
            } else {
                // SIGN IN FLOW - Only for login page
                if (!$existingUser) {
                    \Log::info('User does not exist during signin attempt', ['email' => $googleUser->email]);
                    session()->forget('google_auth_intent'); // Clear the intent
                    return redirect()->route('login')->with('error', 
                        'No account found with this email. Please create an account first or use a different email.');
                }
                
                \Log::info('Existing user found, logging in', ['user_id' => $existingUser->id]);
                
                // Update Google ID, avatar, and email verification if not already set
                if (!$existingUser->google_id) {
                    $existingUser->update([
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar,
                        'email_verified_at' => $existingUser->email_verified_at ?? now(),
                    ]);
                    \Log::info('Linked Google ID to existing user during signin');
                } else {
                    // Update avatar and ensure email is verified
                    $existingUser->update([
                        'avatar' => $googleUser->avatar,
                        'email_verified_at' => $existingUser->email_verified_at ?? now(),
                    ]);
                    \Log::info('Updated existing Google user avatar during signin');
                }
                
                // Log the user in
                Auth::login($existingUser);
                \Log::info('User logged in successfully', [
                    'user_id' => $existingUser->id,
                    'auth_check' => Auth::check()
                ]);
                
                // Redirect based on role
                if ($existingUser->role === 'resident') {
                    \Log::info('Redirecting to resident dashboard');
                    session()->forget('google_auth_intent'); // Clear the intent after successful signin
                    return redirect()->route('resident.dashboard')->with('success', 
                        'Welcome back! You have been logged in with Google.');
                } else {
                    \Log::info('Non-resident user, redirecting to login');
                    session()->forget('google_auth_intent'); // Clear the intent
                    return redirect()->route('login')->with('success', 
                        'Account found! Please use the regular login for non-resident accounts.');
                }
            }
            
        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            \Log::error('Google OAuth Invalid State Exception: ' . $e->getMessage());
            $intent = session('google_auth_intent', 'signin');
            session()->forget('google_auth_intent'); // Clear the intent on error
            $redirectRoute = $intent === 'signup' ? 'register' : 'login';
            return redirect()->route($redirectRoute)->with('error', 
                'Google authentication session expired. Please try again.');
        } catch (\Exception $e) {
            \Log::error('Google OAuth callback error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            $intent = session('google_auth_intent', 'signin');
            session()->forget('google_auth_intent'); // Clear the intent on error
            $redirectRoute = $intent === 'signup' ? 'register' : 'login';
            return redirect()->route($redirectRoute)->with('error', 
                'Something went wrong with Google authentication. Please try again.');
        }
    }
}
