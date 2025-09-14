<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Clean City Waste Management</title>
    <!-- Favicon -->
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/logo.png') }}">
        <link rel="shortcut icon" href="{{ asset('images/logo.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        /* Smooth scroll behavior */
        html {
            scroll-behavior: smooth;
        }

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

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-40px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(40px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
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

        /* Animation classes */
        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .animate-fade-in-left {
            animation: fadeInLeft 0.8s ease-out forwards;
        }

        .animate-fade-in-right {
            animation: fadeInRight 0.8s ease-out forwards;
        }

        .animate-scale-in {
            animation: scaleIn 0.6s ease-out forwards;
        }

        /* Initially hidden for animation */
        .animate-on-scroll {
            opacity: 0;
        }

        /* Stagger animation delays */
        .stagger-1 { animation-delay: 0.2s; }
        .stagger-2 { animation-delay: 0.4s; }
        .stagger-3 { animation-delay: 0.6s; }

        /* Smooth transitions */
        * {
            transition: color 0.3s ease, background-color 0.3s ease, border-color 0.3s ease, transform 0.3s ease;
        }

        /* Parallax effect for background */
        .parallax-bg {
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        @media (max-width: 768px) {
            .parallax-bg {
                background-attachment: scroll;
            }
        }

        /* Set initial body opacity for smooth load */
        body {
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
            font-family: 'Quicksand', sans-serif;
        }

        body.loaded {
            opacity: 1;
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'green-primary': '#4ade80',
                        'green-secondary': '#22c55e',
                        'green-dark': '#16a34a',
                    },
                }
            }
        }
    </script>
</head>
<body class="font-sans text-gray-800 bg-[#f3fef5]">

    <!-- Top Contact Bar -->
    <!-- Desktop/Tablet -->
    <div class="bg-green-700 text-white py-2 text-sm hidden sm:block">
        <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
            <div class="flex items-center space-x-6">
                <div class="flex items-center">
                    <i class="fas fa-clock mr-2"></i>
                    Monday - Friday 08:00 - 18:00
                </div>
                <div class="flex items-center">
                    <i class="fas fa-envelope mr-2"></i>
                    contact@cleancity@gmail.com
                </div>
                <div class="flex items-center">
                    <i class="fas fa-phone mr-2"></i>
                    +94 81 456 7890
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="#" class="hover:text-green-300 transition-colors"><i class="fab fa-facebook"></i></a>
                <a href="#" class="hover:text-green-300 transition-colors"><i class="fab fa-twitter"></i></a>
                <a href="#" class="hover:text-green-300 transition-colors"><i class="fab fa-youtube"></i></a>
                <a href="#" class="hover:text-green-300 transition-colors"><i class="fab fa-pinterest"></i></a>
                <a href="#" class="hover:text-green-300 transition-colors"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </div>
    <!-- Mobile-only compact bar -->
    <div class="sm:hidden bg-green-700 text-white py-1.5 text-[11px]">
        <div class="max-w-7xl mx-auto px-3 space-y-1">
            <div class="flex items-center justify-between">
                <span class="flex items-center mr-3"><i class="fas fa-clock mr-1.5"></i>Mon-Fri 08:00-18:00</span>
                <span class="flex items-center"><i class="fas fa-phone mr-1.5"></i>+94 81 456 7890</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="flex items-center truncate"><i class="fas fa-envelope mr-1.5"></i><span class="truncate">contact@cleancity@gmail.com</span></span>
                <span class="flex items-center space-x-2 ml-3">
                    <a href="#" class="hover:text-green-300"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="hover:text-green-300"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="hover:text-green-300"><i class="fab fa-instagram"></i></a>
                </span>
            </div>
        </div>
    </div>

    <!-- Header/Navigation -->
    <header class="bg-white/95 backdrop-blur-md sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex justify-between items-center py-3 sm:py-4">
                <!-- Logo -->
                <a href="{{ route('landing.home') }}" class="flex items-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-8 h-8 sm:w-10 sm:h-10 object-contain mr-2 sm:mr-3">
                    <div>
                        <span class="text-xl sm:text-2xl font-bold text-gray-800">Clean City</span>
                        <p class="text-[11px] leading-tight sm:text-sm text-green-600 block sm:block">Your Waste, Our Responsibility</p>
                    </div>
                </a>

                <!-- Navigation -->
                <nav class="hidden lg:flex items-center space-x-8">
                    <div class="relative group">
                        <a href="{{ route('landing.home') }}" class="text-gray-700 hover:text-green-500 font-medium flex items-center">
                            Home 
                        </a>
                    </div>
                    <div class="relative group">
                        <a href="{{ route('public.services') }}" class="text-gray-700 hover:text-green-500 font-medium flex items-center">
                            Service 
                        </a>
                    </div>
                    <a href="{{ route('public.projects') }}" class="text-gray-700 hover:text-green-500 font-medium">Projects</a>
                    <div class="relative group">
                        <a href="{{ route('public.company') }}" class="text-gray-700 hover:text-green-500 font-medium flex items-center">
                            Company 
                        </a>
                    </div>
                    <a href="{{ route('public.blog') }}" class="text-gray-700 hover:text-green-500 font-medium">Blog</a>
                    <a href="{{ route('public.contact') }}" class="text-gray-700 hover:text-green-500 font-medium">Contact</a>
                </nav>

                <!-- CTA Buttons -->
                <div class="hidden sm:flex items-center space-x-3">
                    <a href="https://wa.me/94814567890?text=Hi! I'd like to schedule a waste pickup for my location. Please provide me with available time slots and pricing information." target="_blank" class="bg-green-500 hover:bg-green-600 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg font-semibold text-sm sm:text-base transition-all duration-300 transform hover:scale-105" class="bg-green-500 hover:bg-green-600 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg font-semibold text-sm sm:text-base transition-all duration-300 transform hover:scale-105">
                        Schedule Pickup
                    </a>
                    <a href="{{ route('login') }}" class="border-2 border-green-500 text-green-500 bg-green-50 px-4 sm:px-6 py-2 sm:py-3 rounded-lg font-semibold text-sm sm:text-base">
                        <i class="fas fa-user mr-2"></i>Login
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="lg:hidden text-gray-700 p-2">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>

            <!-- Mobile Navigation Menu -->
            <div id="mobile-menu" class="lg:hidden hidden bg-white border-t border-gray-200 py-4">
                <nav class="flex flex-col space-y-4">
                    <a href="{{ route('landing.home') }}" class="text-gray-700 hover:text-green-500 font-medium px-4 py-2">Home</a>
                    <a href="{{ route('public.services') }}" class="text-gray-700 hover:text-green-500 font-medium px-4 py-2">Service</a>
                    <a href="{{ route('public.projects') }}" class="text-gray-700 hover:text-green-500 font-medium px-4 py-2">Projects</a>
                    <a href="{{ route('public.company') }}" class="text-gray-700 hover:text-green-500 font-medium px-4 py-2">Company</a>
                    <a href="{{ route('public.blog') }}" class="text-gray-700 hover:text-green-500 font-medium px-4 py-2">Blog</a>
                    <a href="{{ route('public.contact') }}" class="text-gray-700 hover:text-green-500 font-medium px-4 py-2">Contact</a>
                    <a href="https://wa.me/94814567890?text=Hi! I'd like to schedule a waste pickup for my location. Please provide me with available time slots and pricing information." target="_blank" class="bg-green-500 hover:bg-green-600 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg font-semibold text-sm sm:text-base transition-all duration-300 transform hover:scale-105" class="bg-green-500 hover:bg-green-600 text-white px-4 py-3 rounded-lg font-semibold text-center mx-4 transition-colors">
                        Schedule Pickup
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Login Section -->
    <section class="min-h-screen bg-cover bg-center relative parallax-bg" style="background-image: url('https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2013&q=80')">
        <!-- Background overlay -->
        <div class="absolute inset-0 bg-black/40"></div>
        
        <!-- Content -->
        <div class="relative z-10 py-16 px-4">
            <div class="max-w-6xl mx-auto">
                <div class="grid lg:grid-cols-2 gap-12 items-center min-h-[calc(100vh-200px)]">
                    
                    <!-- Left Side - Illustration & Branding -->
                    <div class="text-center lg:text-left animate-on-scroll hidden sm:hidden lg:block">
                        <!-- Illustration Area -->
                        <div class="bg-green-100/20 backdrop-blur-sm rounded-3xl p-8 mb-8">
                            <div class="w-full h-80 bg-green-50/30 rounded-2xl flex items-center justify-center">
                                <img src="{{ asset('images/login-illustration.png') }}" alt="Waste Management Illustration" 
                                     class="w-[240px] sm:w-[280px] md:w-[320px] h-auto object-contain">
                            </div>
                        </div>

                        <div class="text-white space-y-4">
                            <div class="flex items-center justify-center lg:justify-start">
                                <i class="fas fa-check-circle text-green-400 mr-3"></i>
                                <span>Efficient waste collection scheduling</span>
                            </div>
                            <div class="flex items-center justify-center lg:justify-start">
                                <i class="fas fa-check-circle text-green-400 mr-3"></i>
                                <span>Real-time pickup notifications</span>
                            </div>
                            <div class="flex items-center justify-center lg:justify-start">
                                <i class="fas fa-check-circle text-green-400 mr-3"></i>
                                <span>Environmental impact tracking</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Login Form -->
                    <div class="flex items-center justify-center w-full">
                        <div class="w-full max-w-md">
                            <div class="bg-white rounded-2xl shadow-2xl p-8 animate-on-scroll stagger-1">
                            
                                <div class="text-center mb-8">
                                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Welcome Back!</h2>
                                    <p class="text-gray-600">Login to manage waste smartly in your community</p>
                                </div>

                                @if(session('success'))
    <div class="mb-4 text-green-700 bg-green-100 border border-green-300 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 text-red-700 bg-red-100 border border-red-300 px-4 py-3 rounded">
        {{ session('error') }}
    </div>
@endif

@if(session('status'))
    <div class="mb-4 text-blue-700 bg-blue-100 border border-blue-300 px-4 py-3 rounded">
        {{ session('status') }}
    </div>
@endif

@if (session('email_verify_alert'))
    <div class="bg-yellow-100 border border-yellow-300 text-yellow-800 px-4 py-3 rounded mb-4">
        Please verify your email before logging in. A verification link was sent to:
        <strong>{{ session('email') }}</strong>
    </div>
@endif

                                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                                    @csrf
                                    
                                    <div>
                                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Email Address
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-envelope text-gray-400"></i>
                                            </div>
                                            <input 
                                                type="email" 
                                                id="email"
                                                name="email"
                                                value="{{ old('email', session('email')) }}"
                                                required
                                                autofocus
                                                class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                                placeholder="Enter your email"
                                            >
                                        </div>
                                        @if($errors->has('email'))
                                            <p class="mt-2 text-sm text-red-600">{{ $errors->first('email') }}</p>
                                        @endif
                                    </div>

                                    <div>
                                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Password
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-lock text-gray-400"></i>
                                            </div>
                                            <input 
                                                type="password" 
                                                id="password"
                                                name="password"
                                                required
                                                class="w-full pl-10 pr-12 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                                placeholder="Enter your password"
                                            >
                                            <button 
                                                type="button" 
                                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                                onclick="togglePassword()"
                                            >
                                                <i class="fas fa-eye text-gray-400 hover:text-gray-600" id="toggleIcon"></i>
                                            </button>
                                        </div>
                                        @if($errors->has('password'))
                                            <p class="mt-2 text-sm text-red-600">{{ $errors->first('password') }}</p>
                                        @endif
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <input 
                                                type="checkbox" 
                                                id="remember" 
                                                name="remember"
                                                class="h-4 w-4 text-green-500 focus:ring-green-500 border-gray-300 rounded"
                                            >
                                            <label for="remember" class="ml-2 text-sm text-gray-600">
                                                Remember me
                                            </label>
                                        </div>
                                        <a href="{{ route('password.request') }}" class="text-sm text-green-600 hover:text-green-500 font-medium">
                                            Forgot password?
                                        </a>
                                    </div>

                                    <button 
                                        type="submit" dusk="login-button"
                                        class="w-full bg-green-500 hover:bg-green-600 text-white py-3 rounded-lg font-semibold text-lg transition-all duration-300 transform hover:scale-105 focus:ring-4 focus:ring-green-200"
                                    >
                                        <i class="fas fa-sign-in-alt mr-2"></i>
                                        Login
                                    </button>
                                </form>

                                <!-- Or Divider -->
                                <div class="my-6 flex items-center">
                                    <div class="flex-grow border-t border-gray-300"></div>
                                    <span class="px-4 text-gray-500 text-sm">or</span>
                                    <div class="flex-grow border-t border-gray-300"></div>
                                </div>

                                <!-- Google Sign In Button -->
                                <a href="{{ route('google.redirect', ['intent' => 'signin']) }}" 
                                   class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 rounded-lg shadow-sm bg-white text-gray-700 hover:bg-gray-50 font-medium transition-all duration-300 transform hover:scale-105">
                                    <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24">
                                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                    </svg>
                                    Sign in with Google
                                </a>

                                <div class="mt-8 text-center space-y-4">
                                    <p class="text-gray-600">
                                        Don't have an account? 
                                        <a href="{{ route('register') }}" class="text-green-600 hover:text-green-500 font-semibold">
                                            Register here
                                        </a>
                                    </p>
                                    <div class="border-t border-gray-200 pt-4">
                                        <p class="text-gray-600">
                                            Are you a waste collector? 
                                            <a href="{{ route('collector.login') }}" class="text-green-600 hover:text-green-500 font-semibold">
                                                Log in as a collector
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-green-900 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Company Info -->
                <div>
                    <div class="flex items-center text-xl font-bold mb-4">
                        <i class="fas fa-recycle text-2xl mr-3"></i>
                        Clean City
                    </div>
                    <p class="text-gray-300 mb-4 leading-relaxed">
                        Committed to providing eco-friendly waste management solutions for a cleaner tomorrow.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-lg font-bold text-green-300 mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-green-300 transition-colors">About Us</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-green-300 transition-colors">Services</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-green-300 transition-colors">Contact</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-green-300 transition-colors">Privacy Policy</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h4 class="text-lg font-bold text-green-300 mb-4">Contact Info</h4>
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <i class="fas fa-phone text-green-300 mr-3"></i>
                            <span class="text-gray-300">+94 81 456 7890</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-green-300 mr-3"></i>
                            <span class="text-gray-300">contact@cleancity.gmail.com</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="border-t border-green-800 pt-6 mt-6 text-center">
                <p class="text-gray-400">&copy; 2025 CleanCity. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- ✅ Two-Factor Modal -->
@if (session('show_2fa_modal'))
    <div id="twoFactorModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-md w-full max-w-md shadow-xl">
            <h2 class="text-lg font-semibold mb-4 text-center">Two-Factor Verification</h2>

            {{-- ✅ Display error --}}
            @if (session('otp_error'))
                <div class="bg-red-100 text-red-700 p-2 text-sm rounded mb-3">
                    {{ session('otp_error') }}
                </div>
            @endif

            @if (session('otp_success'))
                <div class="bg-green-100 text-green-700 p-2 text-sm rounded mb-3">
                    {{ session('otp_success') }}
                </div>
            @endif

            <form action="{{ route('2fa.verify') }}" method="POST">
                @csrf
                <input type="text" name="otp" class="w-full border p-2 rounded mb-4 text-center" placeholder="Enter OTP" required>

                <div class="flex justify-between items-center">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded w-full">Verify</button>
                </div>
            </form>

            {{-- 🔁 Resend OTP --}}
            <div class="text-center mt-4">
                <form action="{{ route('2fa.resend') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-sm text-blue-600 hover:underline">Didn’t receive the code? Resend OTP</button>
                </form>
            </div>

            <div class="text-center mt-3">
                <button onclick="document.getElementById('twoFactorModal').remove()" class="text-sm text-gray-500 hover:underline">Cancel</button>
            </div>
        </div>
    </div>
@endif

    

    <!-- JavaScript -->
    <script>
        // Initialize animations when DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            // Animate elements on load
            setTimeout(() => {
                const animateElements = document.querySelectorAll('.animate-on-scroll');
                animateElements.forEach((el, index) => {
                    setTimeout(() => {
                        if (index === 0) {
                            el.classList.add('animate-fade-in-left');
                        } else {
                            el.classList.add('animate-fade-in-right');
                        }
                    }, index * 200);
                });
            }, 300);

            // Set body as loaded
            document.body.classList.add('loaded');
        });

        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
                const icon = mobileMenuBtn.querySelector('i');
                if (mobileMenu.classList.contains('hidden')) {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                } else {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-times');
                }
            });
        }

        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Add smooth transitions on load
        window.addEventListener('load', () => {
            document.body.style.opacity = '1';
        });
    </script>
</body>
</html>