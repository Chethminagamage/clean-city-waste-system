<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Clean City Admin</title>
    
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                </div>
            </div>
            <h2 class="text-3xl font-bold text-gray-900">Reset Password</h2>
            <p class="mt-2 text-sm text-gray-600">Clean City Admin Portal</p>
            <p class="mt-1 text-xs text-gray-500">Enter your new password below</p>
        </div>

        <!-- Reset Password Form -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <form method="POST" action="{{ route('admin.password.update') }}" class="space-y-6">
                @csrf
                
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

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

                <!-- Email Field (Read Only) -->
                <div>
                    <label for="email_display" class="block text-sm font-medium text-gray-700 mb-2">
                        Admin Email
                    </label>
                    <input 
                        type="email" 
                        id="email_display"
                        value="{{ $email }}"
                        class="w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-600"
                        readonly
                    >
                </div>

                <!-- New Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        New Password
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password"
                            name="password" 
                            placeholder="Enter new password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 pr-12"
                            required
                            autofocus
                        >
                        <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg id="eye-password" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Password Strength Indicator -->
                    <div class="mt-2">
                        <div class="flex space-x-1">
                            <div id="strength-1" class="h-1 w-1/4 bg-gray-200 rounded transition-all duration-300"></div>
                            <div id="strength-2" class="h-1 w-1/4 bg-gray-200 rounded transition-all duration-300"></div>
                            <div id="strength-3" class="h-1 w-1/4 bg-gray-200 rounded transition-all duration-300"></div>
                            <div id="strength-4" class="h-1 w-1/4 bg-gray-200 rounded transition-all duration-300"></div>
                        </div>
                        <p id="strength-text" class="text-xs text-gray-500 mt-1">Password strength will be shown here</p>
                    </div>
                </div>

                <!-- Confirm Password Field -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Confirm New Password
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password_confirmation"
                            name="password_confirmation" 
                            placeholder="Confirm new password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 pr-12"
                            required
                        >
                        <button type="button" onclick="togglePassword('password_confirmation')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg id="eye-password_confirmation" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    <div id="password-match" class="mt-1 text-xs hidden">
                        <span id="match-text"></span>
                    </div>
                </div>

                <!-- Password Requirements -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-blue-800 mb-2">Password Requirements:</h4>
                    <ul class="text-xs text-blue-700 space-y-1">
                        <li id="req-length" class="flex items-center">
                            <span class="w-4 h-4 mr-2 text-gray-400">○</span>
                            At least 8 characters long
                        </li>
                        <li id="req-uppercase" class="flex items-center">
                            <span class="w-4 h-4 mr-2 text-gray-400">○</span>
                            Contains uppercase letter
                        </li>
                        <li id="req-lowercase" class="flex items-center">
                            <span class="w-4 h-4 mr-2 text-gray-400">○</span>
                            Contains lowercase letter
                        </li>
                        <li id="req-number" class="flex items-center">
                            <span class="w-4 h-4 mr-2 text-gray-400">○</span>
                            Contains number
                        </li>
                        <li id="req-special" class="flex items-center">
                            <span class="w-4 h-4 mr-2 text-gray-400">○</span>
                            Contains special character
                        </li>
                    </ul>
                </div>

                <!-- Reset Password Button -->
                <div>
                    <button 
                        type="submit" 
                        id="reset-btn"
                        class="w-full bg-green-600 text-white py-3 px-4 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-200 font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled
                    >
                        <span id="btn-text">Reset Password</span>
                        <span id="btn-loading" class="hidden">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Resetting...
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
                    <span>This will log you out of all devices for security</span>
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
        let passwordRequirements = {
            length: false,
            uppercase: false,
            lowercase: false,
            number: false,
            special: false
        };

        // Toggle password visibility
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const eyeIcon = document.getElementById('eye-' + fieldId);
            
            if (field.type === 'password') {
                field.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                `;
            } else {
                field.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                `;
            }
        }

        // Check password strength and requirements
        function checkPassword(password) {
            passwordRequirements.length = password.length >= 8;
            passwordRequirements.uppercase = /[A-Z]/.test(password);
            passwordRequirements.lowercase = /[a-z]/.test(password);
            passwordRequirements.number = /\d/.test(password);
            passwordRequirements.special = /[^A-Za-z0-9]/.test(password);

            // Update requirement indicators
            updateRequirementIndicator('req-length', passwordRequirements.length);
            updateRequirementIndicator('req-uppercase', passwordRequirements.uppercase);
            updateRequirementIndicator('req-lowercase', passwordRequirements.lowercase);
            updateRequirementIndicator('req-number', passwordRequirements.number);
            updateRequirementIndicator('req-special', passwordRequirements.special);

            // Calculate strength
            const satisfiedRequirements = Object.values(passwordRequirements).filter(Boolean).length;
            updateStrengthIndicator(satisfiedRequirements);

            // Enable/disable submit button
            const allMet = Object.values(passwordRequirements).every(Boolean);
            const passwordMatch = checkPasswordMatch();
            document.getElementById('reset-btn').disabled = !(allMet && passwordMatch);
        }

        // Update requirement indicator
        function updateRequirementIndicator(id, satisfied) {
            const element = document.getElementById(id);
            const icon = element.querySelector('span');
            
            if (satisfied) {
                icon.textContent = '✓';
                icon.className = 'w-4 h-4 mr-2 text-green-500';
                element.classList.add('text-green-700');
                element.classList.remove('text-blue-700');
            } else {
                icon.textContent = '○';
                icon.className = 'w-4 h-4 mr-2 text-gray-400';
                element.classList.add('text-blue-700');
                element.classList.remove('text-green-700');
            }
        }

        // Update strength indicator
        function updateStrengthIndicator(strength) {
            const colors = ['bg-red-400', 'bg-orange-400', 'bg-yellow-400', 'bg-green-400', 'bg-green-500'];
            const texts = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'];
            
            for (let i = 1; i <= 4; i++) {
                const bar = document.getElementById(`strength-${i}`);
                if (i <= strength) {
                    bar.className = `h-1 w-1/4 rounded transition-all duration-300 ${colors[strength - 1] || 'bg-gray-200'}`;
                } else {
                    bar.className = 'h-1 w-1/4 bg-gray-200 rounded transition-all duration-300';
                }
            }
            
            document.getElementById('strength-text').textContent = strength > 0 ? `Password strength: ${texts[strength - 1] || 'Very Weak'}` : 'Password strength will be shown here';
        }

        // Check password match
        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmation = document.getElementById('password_confirmation').value;
            const matchDiv = document.getElementById('password-match');
            const matchText = document.getElementById('match-text');

            if (confirmation.length > 0) {
                matchDiv.classList.remove('hidden');
                if (password === confirmation) {
                    matchText.textContent = '✓ Passwords match';
                    matchText.className = 'text-green-600 font-medium';
                    return true;
                } else {
                    matchText.textContent = '✗ Passwords do not match';
                    matchText.className = 'text-red-600 font-medium';
                    return false;
                }
            } else {
                matchDiv.classList.add('hidden');
                return false;
            }
        }

        // Event listeners
        document.getElementById('password').addEventListener('input', function(e) {
            checkPassword(e.target.value);
            checkPasswordMatch();
        });

        document.getElementById('password_confirmation').addEventListener('input', function() {
            checkPasswordMatch();
        });

        // Handle form submission with loading state
        document.querySelector('form').addEventListener('submit', function(e) {
            const btn = document.getElementById('reset-btn');
            const btnText = document.getElementById('btn-text');
            const btnLoading = document.getElementById('btn-loading');
            
            // Show loading state
            btn.disabled = true;
            btnText.classList.add('hidden');
            btnLoading.classList.remove('hidden');
        });

        // Focus management
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('password').focus();
        });
    </script>
</body>
</html>