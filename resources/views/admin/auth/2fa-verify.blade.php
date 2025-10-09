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
                <div class="bg-green-600 p-4 rounded-xl">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
            </div>
            <h2 class="text-3xl font-bold text-gray-900">Two-Factor Authentication</h2>
            <p class="mt-2 text-sm text-gray-600">Clean City Admin Portal</p>
            <p class="mt-1 text-xs text-gray-500">Enter the 6-digit code from your authenticator app</p>
        </div>

        <!-- 2FA Form -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <form method="POST" action="{{ route('admin.2fa.verify') }}" class="space-y-6">
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

                <!-- Validation Errors -->
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
                        class="w-full px-4 py-3 text-center text-2xl font-mono border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 tracking-widest"
                        required
                        autofocus
                        autocomplete="one-time-code"
                    >
                    <p class="mt-2 text-xs text-gray-500">
                        Open your authenticator app and enter the 6-digit code
                    </p>
                    
                    <!-- Code Length Indicator -->
                    <div class="mt-3 flex justify-center space-x-2">
                        <div id="digit-1" class="w-2 h-2 bg-gray-200 rounded-full transition-all duration-200"></div>
                        <div id="digit-2" class="w-2 h-2 bg-gray-200 rounded-full transition-all duration-200"></div>
                        <div id="digit-3" class="w-2 h-2 bg-gray-200 rounded-full transition-all duration-200"></div>
                        <div id="digit-4" class="w-2 h-2 bg-gray-200 rounded-full transition-all duration-200"></div>
                        <div id="digit-5" class="w-2 h-2 bg-gray-200 rounded-full transition-all duration-200"></div>
                        <div id="digit-6" class="w-2 h-2 bg-gray-200 rounded-full transition-all duration-200"></div>
                    </div>
                </div>

                <!-- Verify Button -->
                <div>
                    <button 
                        type="submit" 
                        id="verify-btn"
                        class="w-full bg-green-600 text-white py-3 px-4 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-200 font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span id="btn-text">Verify & Continue</span>
                        <span id="btn-loading" class="hidden">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Verifying...
                        </span>
                    </button>
                </div>
            </form>


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
            <a href="{{ route('admin.login') }}" class="text-sm text-gray-600 hover:text-green-600 transition duration-200">
                ← Back to Login
            </a>
        </div>
    </div>

    <script>
        // Auto-submit when 6 digits are entered
        document.getElementById('code').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Only digits
            e.target.value = value;
            
            // Update progress indicators
            updateProgressIndicators(value.length);
            
            // Update input styling based on length
            if (value.length === 6) {
                e.target.classList.add('border-green-500', 'ring-1', 'ring-green-500');
                e.target.classList.remove('border-gray-300');
                
                // Auto-submit after a short delay
                setTimeout(() => {
                    if (e.target.value.length === 6) {
                        e.target.closest('form').submit();
                    }
                }, 500);
            } else {
                e.target.classList.remove('border-green-500', 'ring-1', 'ring-green-500');
                e.target.classList.add('border-gray-300');
            }
        });

        // Update progress indicators
        function updateProgressIndicators(length) {
            for (let i = 1; i <= 6; i++) {
                const indicator = document.getElementById(`digit-${i}`);
                if (i <= length) {
                    indicator.classList.remove('bg-gray-200');
                    indicator.classList.add('bg-green-500');
                } else {
                    indicator.classList.remove('bg-green-500');
                    indicator.classList.add('bg-gray-200');
                }
            }
        }

        // Focus management and pulse animation
        document.addEventListener('DOMContentLoaded', function() {
            const codeInput = document.getElementById('code');
            codeInput.focus();
            
            // Add subtle pulse animation to the input on focus
            codeInput.addEventListener('focus', function() {
                this.classList.add('animate-pulse');
                setTimeout(() => {
                    this.classList.remove('animate-pulse');
                }, 1000);
            });
        });

        // Prevent non-numeric input
        document.getElementById('code').addEventListener('keypress', function(e) {
            if (!/[0-9]/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'Escape', 'Enter'].includes(e.key)) {
                e.preventDefault();
            }
        });

        // Handle form submission with loading state
        document.querySelector('form').addEventListener('submit', function(e) {
            const btn = document.getElementById('verify-btn');
            const btnText = document.getElementById('btn-text');
            const btnLoading = document.getElementById('btn-loading');
            const codeInput = document.getElementById('code');
            
            // Show loading state
            btn.disabled = true;
            btnText.classList.add('hidden');
            btnLoading.classList.remove('hidden');
            codeInput.disabled = true;
            
            // Change input to success state
            codeInput.classList.add('border-green-500', 'bg-green-50');
        });
    </script>
</body>
</html>