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
        try {
            // Validate Google OAuth configuration
            if (empty(config('services.google.client_id')) || 
                empty(config('services.google.client_secret')) ||
                empty(config('services.google.redirect'))) {
                
                \Log::error('Google OAuth configuration is incomplete');
                $intent = request()->query('intent', 'signin');
                $redirectRoute = $intent === 'signup' ? 'register' : 'login';
                return redirect()->route($redirectRoute)->with('error', 
                    'Google authentication is not properly configured. Please use regular registration.');
            }
            
            // Store the intent (signup or signin) in session
            $intent = request()->query('intent', 'signin'); // default to signin
            session(['google_auth_intent' => $intent]);
            
            \Log::info('Redirecting to Google OAuth', ['intent' => $intent]);
            
            return Socialite::driver('google')->stateless()->redirect();
            
        } catch (\Exception $e) {
            \Log::error('Error initiating Google OAuth: ' . $e->getMessage());
            $intent = request()->query('intent', 'signin');
            $redirectRoute = $intent === 'signup' ? 'register' : 'login';
            return redirect()->route($redirectRoute)->with('error', 
                'Unable to connect to Google authentication. Please use regular registration.');
        }
    }

    /**
     * Handle Google callback - behavior depends on intent
     */
    public function handleGoogleCallback()
    {
        try {
            \Log::info('Google callback started');
            
            // Check network connectivity first
            $this->checkNetworkConnectivity();
            
            // Try to get Google user with specific error handling for cURL issues
            try {
                $googleUser = Socialite::driver('google')->stateless()->user();
            } catch (\GuzzleHttp\Exception\ConnectException $e) {
                if (str_contains($e->getMessage(), 'cURL error 6') || str_contains($e->getMessage(), 'Could not resolve host')) {
                    \Log::error('DNS resolution issue for Google OAuth: ' . $e->getMessage());
                    $intent = session('google_auth_intent', 'signin');
                    session()->forget('google_auth_intent');
                    $redirectRoute = $intent === 'signup' ? 'register' : 'login';
                    return redirect()->route($redirectRoute)->with('error', 
                        'Unable to connect to Google servers. Please check your internet connection and try again, or use regular registration.');
                }
                throw $e; // Re-throw if it's a different connection error
            }
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
            
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            \Log::error('Google OAuth Network Connection Error: ' . $e->getMessage());
            
            // Handle network connectivity issues specifically
            if (str_contains($e->getMessage(), 'Could not resolve host') || 
                str_contains($e->getMessage(), 'Connection refused') ||
                str_contains($e->getMessage(), 'cURL error')) {
                
                \Log::error('Network connectivity issue detected for Google OAuth');
                $intent = session('google_auth_intent', 'signin');
                session()->forget('google_auth_intent');
                
                $redirectRoute = $intent === 'signup' ? 'register' : 'login';
                $message = 'Unable to connect to Google servers. Please check your internet connection and try again, or use regular registration.';
                
                return redirect()->route($redirectRoute)->with('error', $message);
            }
            
            // Fallback for other connection errors
            $intent = session('google_auth_intent', 'signin');
            session()->forget('google_auth_intent');
            $redirectRoute = $intent === 'signup' ? 'register' : 'login';
            return redirect()->route($redirectRoute)->with('error', 
                'Network connection error. Please try again or use regular registration.');
                
        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            \Log::error('Google OAuth Invalid State Exception: ' . $e->getMessage());
            \Log::info('Attempting stateless fallback for Invalid State Exception');
            
            // Fallback: Try stateless authentication as a recovery mechanism
            try {
                $googleUser = Socialite::driver('google')->stateless()->user();
                \Log::info('Stateless fallback successful for Google OAuth');
                
                // Continue with the normal flow if stateless works
                $intent = session('google_auth_intent', 'signin');
                $existingUser = User::where('email', $googleUser->email)->first();
                
                if ($intent === 'signup') {
                    if ($existingUser) {
                        if (!$existingUser->google_id) {
                            $existingUser->update([
                                'google_id' => $googleUser->id,
                                'avatar' => $googleUser->avatar,
                                'email_verified_at' => $existingUser->email_verified_at ?? now(),
                            ]);
                        }
                        session()->forget('google_auth_intent');
                        return redirect()->route('login')->with('error', 
                            'An account with this email already exists. Please sign in instead.');
                    }
                    
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
                    
                    Auth::login($user);
                    session()->forget('google_auth_intent');
                    return redirect()->route('resident.dashboard')->with('success', 
                        'Welcome to CleanCity! Your account has been created and you are now logged in.');
                        
                } else {
                    // Sign in flow
                    if (!$existingUser) {
                        session()->forget('google_auth_intent');
                        return redirect()->route('login')->with('error', 
                            'No account found with this email. Please create an account first or use a different email.');
                    }
                    
                    if (!$existingUser->google_id) {
                        $existingUser->update([
                            'google_id' => $googleUser->id,
                            'avatar' => $googleUser->avatar,
                            'email_verified_at' => $existingUser->email_verified_at ?? now(),
                        ]);
                    } else {
                        $existingUser->update([
                            'avatar' => $googleUser->avatar,
                            'email_verified_at' => $existingUser->email_verified_at ?? now(),
                        ]);
                    }
                    
                    Auth::login($existingUser);
                    
                    if ($existingUser->role === 'resident') {
                        session()->forget('google_auth_intent');
                        return redirect()->route('resident.dashboard')->with('success', 
                            'Welcome back! You have been logged in with Google.');
                    } else {
                        session()->forget('google_auth_intent');
                        return redirect()->route('login')->with('success', 
                            'Account found! Please use the regular login for non-resident accounts.');
                    }
                }
                
            } catch (\Exception $fallbackException) {
                \Log::error('Stateless fallback also failed: ' . $fallbackException->getMessage());
                $intent = session('google_auth_intent', 'signin');
                session()->forget('google_auth_intent');
                $redirectRoute = $intent === 'signup' ? 'register' : 'login';
                return redirect()->route($redirectRoute)->with('error', 
                    'Google authentication session expired. Please try again or use regular login.');
            }
        } catch (\Exception $e) {
            \Log::error('Google OAuth callback error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            $intent = session('google_auth_intent', 'signin');
            session()->forget('google_auth_intent'); // Clear the intent on error
            $redirectRoute = $intent === 'signup' ? 'register' : 'login';
            
            // Provide specific error messages based on the error type
            $errorMessage = 'Something went wrong with Google authentication. Please try again.';
            
            if (str_contains($e->getMessage(), 'Could not resolve host') || 
                str_contains($e->getMessage(), 'cURL error 6')) {
                $errorMessage = 'Network connection issue. Please check your internet connection and try again, or use regular registration instead.';
            } elseif (str_contains($e->getMessage(), 'invalid_grant') || 
                     str_contains($e->getMessage(), 'invalid_request')) {
                $errorMessage = 'Google authentication session expired. Please try the Google sign-up again.';
            } elseif (str_contains($e->getMessage(), 'access_denied')) {
                $errorMessage = 'Google authentication was cancelled. Please try again or use regular registration.';
            }
            
            return redirect()->route($redirectRoute)->with('error', $errorMessage);
        }
    }
    
    /**
     * Check network connectivity to Google services
     */
    private function checkNetworkConnectivity()
    {
        try {
            // Try to resolve googleapis.com
            $ip = gethostbyname('www.googleapis.com');
            if ($ip === 'www.googleapis.com') {
                throw new \Exception('Cannot resolve googleapis.com');
            }
            
            // Try to make a simple connection test
            $context = stream_context_create([
                'http' => [
                    'timeout' => 5, // 5 seconds timeout
                    'method' => 'HEAD'
                ]
            ]);
            
            $headers = @get_headers('https://www.googleapis.com', false, $context);
            if ($headers === false) {
                throw new \Exception('Cannot connect to googleapis.com');
            }
            
            \Log::info('Network connectivity to Google services verified');
            
        } catch (\Exception $e) {
            \Log::warning('Network connectivity check failed: ' . $e->getMessage());
            // Don't throw here, let the main OAuth flow handle the error
        }
    }
}
