<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two-Factor Authentication - Clean City Admin</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">
    
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center px-4">
    <div class="max-w-md w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
            <div class="flex items-center justify-center mb-4">
                <div class="bg-blue-600 p-4 rounded-xl">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
            </div>
            <h2 class="text-3xl font-bold text-gray-900">Two-Factor Authentication</h2>
            <p class="mt-2 text-sm text-gray-600">Enter the 6-digit code from your authenticator app</p>
        </div>

        <!-- 2FA Form -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <form method="POST" action="{{ route('admin.2fa.verify') }}" class="space-y-6">
                @csrf
                
                <!-- Error Messages -->
                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="text-red-600 text-sm font-medium">{{ session('error') }}</div>
                        </div>
                    </div>
                @endif

                <!-- Verification Code Field -->
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                        Verification Code
                    </label>
                    <input 
                        type="text" 
                        id="code"
                        name="code" 
                        placeholder="000000"
                        maxlength="6"
                        pattern="[0-9]{6}"
                        class="w-full px-4 py-3 text-center text-2xl font-mono border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 tracking-widest"
                        required
                        autofocus
                        autocomplete="one-time-code"
                    >
                    <p class="mt-2 text-xs text-gray-500">
                        Open your authenticator app and enter the 6-digit code
                    </p>
                </div>

                <!-- Verify Button -->
                <div>
                    <button 
                        type="submit" 
                        class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 font-medium"
                    >
                        Verify & Continue
                    </button>
                </div>
            </form>

            <!-- Recovery Options -->
            <div class="mt-6 space-y-4">
                <div class="border-t border-gray-200 pt-4">
                    <p class="text-sm text-gray-600 mb-3">Having trouble?</p>
                    <div class="space-y-2">
                        <a href="#" class="block text-sm text-blue-600 hover:text-blue-500 transition duration-200">
                            📱 Use a recovery code instead
                        </a>
                        <a href="#" class="block text-sm text-blue-600 hover:text-blue-500 transition duration-200">
                            📧 Contact system administrator
                        </a>
                    </div>
                </div>
            </div>

            <!-- Security Notice -->
            <div class="mt-6 text-center">
                <div class="flex items-center justify-center text-xs text-gray-500">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                    </svg>
                    Session expires in 10 minutes for security
                </div>
            </div>
        </div>

        <!-- Back to Login -->
        <div class="text-center">
            <a href="{{ route('admin.login') }}" class="text-sm text-gray-600 hover:text-blue-600 transition duration-200">
                ← Back to Login
            </a>
        </div>
    </div>

    <script>
        // Auto-submit when 6 digits are entered
        document.getElementById('code').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Only digits
            e.target.value = value;
            
            if (value.length === 6) {
                // Optional: Auto-submit after a short delay
                setTimeout(() => {
                    if (e.target.value.length === 6) {
                        e.target.closest('form').submit();
                    }
                }, 500);
            }
        });

        // Focus management
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('code').focus();
        });
    </script>
</body>
</html>