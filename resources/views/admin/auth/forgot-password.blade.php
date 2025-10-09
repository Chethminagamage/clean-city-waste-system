<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Clean City Admin</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center px-4">
    <div class="max-w-md w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
            <div class="flex items-center justify-center mb-4">
                <div class="bg-green-600 p-4 rounded-xl">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                </div>
            </div>
            <h2 class="text-3xl font-bold text-gray-900">Forgot Password</h2>
            <p class="mt-2 text-sm text-gray-600">Clean City Admin Portal</p>
            <p class="mt-1 text-xs text-gray-500">Enter your email to receive a password reset link</p>
        </div>

        <!-- Reset Password Form -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <form method="POST" action="{{ route('admin.password.email') }}" class="space-y-6">
                @csrf
                
                <!-- Success Messages -->
                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 rounded-md p-4 mb-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="text-green-600 text-sm font-medium">{{ session('success') }}</div>
                        </div>
                    </div>
                @endif

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-red-400 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="text-red-600 text-sm">
                                @foreach ($errors->all() as $error)
                                    <div class="font-medium">{{ $error }}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Admin Email Address
                    </label>
                    <input 
                        type="email" 
                        id="email"
                        name="email" 
                        value="{{ old('email') }}"
                        placeholder="admin@cleancity.com"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200"
                        required
                        autofocus
                    >
                    <p class="mt-2 text-xs text-gray-500">
                        We'll send a reset link to your registered admin email
                    </p>
                </div>

                <!-- reCAPTCHA -->
                <div class="flex justify-center">
                    <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                </div>

                <!-- Send Reset Link Button -->
                <div>
                    <button 
                        type="submit" 
                        id="reset-btn"
                        class="w-full bg-green-600 text-white py-3 px-4 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-200 font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span id="btn-text">Send Reset Link</span>
                        <span id="btn-loading" class="hidden">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Sending...
                        </span>
                    </button>
                </div>
            </form>

            <!-- Security Notice -->
            <div class="mt-6 text-center">
                <div class="flex items-center justify-center text-xs text-gray-500 bg-gray-50 rounded-lg p-3">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Reset link expires in 60 minutes for security</span>
                </div>
            </div>
        </div>

        <!-- Back to Login -->
        <div class="text-center">
            <a href="{{ route('admin.login') }}" class="text-sm text-gray-600 hover:text-green-600 transition duration-200">
                ← Back to Login
            </a>
        </div>
    </div>

    <script>
        // Handle form submission with loading state
        document.querySelector('form').addEventListener('submit', function(e) {
            const btn = document.getElementById('reset-btn');
            const btnText = document.getElementById('btn-text');
            const btnLoading = document.getElementById('btn-loading');
            const emailInput = document.getElementById('email');
            
            // Validate reCAPTCHA
            if (typeof grecaptcha !== 'undefined') {
                const recaptchaResponse = grecaptcha.getResponse();
                if (!recaptchaResponse) {
                    alert('Please complete the reCAPTCHA verification.');
                    return false;
                }
            }
            
            // Show loading state
            btn.disabled = true;
            btnText.classList.add('hidden');
            btnLoading.classList.remove('hidden');
            emailInput.disabled = true;
            
            // Change input to processing state
            emailInput.classList.add('border-green-500', 'bg-green-50');
        });

        // Focus management
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('email').focus();
        });

        // Email validation enhancement
        document.getElementById('email').addEventListener('input', function(e) {
            const email = e.target.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email && emailRegex.test(email)) {
                e.target.classList.add('border-green-300');
                e.target.classList.remove('border-gray-300', 'border-red-300');
            } else if (email) {
                e.target.classList.add('border-red-300');
                e.target.classList.remove('border-gray-300', 'border-green-300');
            } else {
                e.target.classList.add('border-gray-300');
                e.target.classList.remove('border-green-300', 'border-red-300');
            }
        });
    </script>
</body>
</html>