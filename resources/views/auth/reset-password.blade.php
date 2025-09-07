<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Clean City Waste Management</title>
    <!-- Favicon -->
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/logo.png') }}">
        <link rel="shortcut icon" href="{{ asset('images/logo.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <style>
        /* Custom animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .animate-scale-in {
            animation: scaleIn 0.6s ease-out forwards;
        }

        body {
            font-family: 'Figtree', sans-serif;
        }

        /* Floating animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .float-animation {
            animation: float 6s ease-in-out infinite;
        }

        /* Input focus effects */
        .input-focus {
            transition: all 0.3s ease;
        }

        .input-focus:focus {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.15);
        }
    </style>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'green-primary': '#22c55e',
                        'green-secondary': '#16a34a',
                        'green-light': '#4ade80',
                    },
                }
            }
        }
    </script>
</head>

<body class="min-h-screen bg-gradient-to-br from-green-600 via-green-500 to-green-400 flex">
    
    <!-- Left Side - Features & Illustration -->
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-black/20"></div>
        
        <!-- Floating Elements -->
        <div class="absolute top-20 left-20 w-16 h-16 bg-white/10 rounded-full float-animation" style="animation-delay: 0s;"></div>
        <div class="absolute top-1/4 right-20 w-12 h-12 bg-white/10 rounded-full float-animation" style="animation-delay: 2s;"></div>
        <div class="absolute bottom-1/3 left-1/4 w-20 h-20 bg-white/10 rounded-full float-animation" style="animation-delay: 4s;"></div>
        
        <!-- Content -->
        <div class="relative z-10 flex flex-col justify-center items-center w-full p-12 text-white">
            
            <!-- Main Illustration -->
            <div class="mb-12">
                <div class="w-80 h-64 bg-white/10 backdrop-blur-sm rounded-3xl flex items-center justify-center mb-8">
                    <div class="text-center">
                        <div class="w-24 h-24 bg-green-400 rounded-full flex items-center justify-center mx-auto mb-4 float-animation">
                            <i class="fas fa-shield-alt text-3xl text-white"></i>
                        </div>
                        <div class="flex items-center justify-center space-x-4">
                            <div class="w-16 h-16 bg-blue-400 rounded-full flex items-center justify-center float-animation" style="animation-delay: 1s;">
                                <i class="fas fa-user text-xl text-white"></i>
                            </div>
                            <div class="w-16 h-16 bg-blue-400 rounded-full flex items-center justify-center float-animation" style="animation-delay: 3s;">
                                <i class="fas fa-user text-xl text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features List -->
            <div class="space-y-6 text-left">
                <div class="flex items-center space-x-4">
                    <div class="w-6 h-6 bg-green-400 rounded-full flex items-center justify-center">
                        <i class="fas fa-check text-white text-sm"></i>
                    </div>
                    <span class="text-lg">Efficient waste collection scheduling</span>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="w-6 h-6 bg-green-400 rounded-full flex items-center justify-center">
                        <i class="fas fa-check text-white text-sm"></i>
                    </div>
                    <span class="text-lg">Real-time pickup notifications</span>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="w-6 h-6 bg-green-400 rounded-full flex items-center justify-center">
                        <i class="fas fa-check text-white text-sm"></i>
                    </div>
                    <span class="text-lg">Environmental impact tracking</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Side - Reset Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white">
        <div class="w-full max-w-md">
            
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Reset Password</h1>
                <p class="text-gray-600">Enter your email to receive a password reset link</p>
            </div>

            <!-- Success/Error Messages -->
            @if (session('status'))
                <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-700 rounded-lg flex items-center">
                    <i class="fas fa-check-circle mr-3 text-green-500"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-100 border border-red-300 text-red-700 rounded-lg">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-exclamation-circle mr-2 text-red-500"></i>
                        <span class="font-semibold">Please fix the following errors:</span>
                    </div>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Reset Password Form -->
            <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email', $request->email) }}" 
                               required 
                               readonly
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent input-focus" 
                               placeholder="Enter your email" />
                    </div>
                    @error('email')
                        <p class="text-red-500 text-sm mt-2 flex items-center">
                            <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- New Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="password" 
                               type="password" 
                               name="password" 
                               required 
                               autocomplete="new-password"
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent input-focus" 
                               placeholder="Enter your new password" />
                    </div>
                    @error('password')
                        <p class="text-red-500 text-sm mt-2 flex items-center">
                            <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="password_confirmation" 
                               type="password" 
                               name="password_confirmation" 
                               required 
                               autocomplete="new-password"
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent input-focus" 
                               placeholder="Confirm your new password" />
                    </div>
                    @error('password_confirmation')
                        <p class="text-red-500 text-sm mt-2 flex items-center">
                            <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center justify-center space-x-2 shadow-lg hover:shadow-xl">
                    <i class="fas fa-envelope"></i>
                    <span>Reset Password</span>
                </button>

                <!-- Back to Login Link -->
                <div class="text-center pt-4">
                    <a href="{{ route('login') }}" 
                       class="text-green-600 hover:text-green-700 font-medium text-sm transition-colors duration-300 flex items-center justify-center space-x-2">
                        <i class="fas fa-arrow-left"></i>
                        <span>Back to Login</span>
                    </a>
                </div>
            </form>

            <!-- Help Section -->
            <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                <h4 class="text-sm font-semibold text-gray-800 mb-2">Need help?</h4>
                <div class="space-y-2 text-sm text-gray-600">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-envelope text-green-500"></i>
                        <span>contact@cleancity.gmail.com</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-phone text-green-500"></i>
                        <span>+94 81 456 7890</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for enhanced interactions -->
    <script>
        // Add smooth loading animation
        document.addEventListener('DOMContentLoaded', function() {
            document.body.style.opacity = '1';
        });

        // Real-time password confirmation validation
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password_confirmation');

        confirmPasswordInput.addEventListener('input', function() {
            if (passwordInput.value !== '' && confirmPasswordInput.value !== '') {
                if (passwordInput.value === confirmPasswordInput.value) {
                    confirmPasswordInput.classList.remove('border-red-300');
                    confirmPasswordInput.classList.add('border-green-300');
                } else {
                    confirmPasswordInput.classList.remove('border-green-300');
                    confirmPasswordInput.classList.add('border-red-300');
                }
            }
        });

        // Form validation feedback
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const submitButton = form.querySelector('button[type="submit"]');
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
            submitButton.disabled = true;
        });
    </script>
</body>
</html>