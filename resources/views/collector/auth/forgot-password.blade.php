<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Clean City Collector</title>
    <!-- Favicon -->
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/logo.png') }}">
        <link rel="shortcut icon" href="{{ asset('images/logo.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
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

        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .animate-fade-in-left {
            animation: fadeInLeft 0.8s ease-out forwards;
        }

        .animate-fade-in-right {
            animation: fadeInRight 0.8s ease-out forwards;
        }

        /* Initially hidden for animation */
        .animate-on-scroll {
            opacity: 0;
        }

        /* Stagger animation delays */
        .stagger-1 { animation-delay: 0.2s; }

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
                        'orange-collector': '#f97316',
                        'orange-collector-dark': '#ea580c',
                    },
                }
            }
        }
    </script>
</head>
<body class="font-sans text-gray-800 bg-[#fef7f0]">

    <!-- Top Contact Bar -->
    <!-- Desktop/Tablet -->
    <div class="bg-orange-600 text-white py-2 text-sm hidden sm:block">
        <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
            <div class="flex items-center space-x-6">
                <div class="flex items-center">
                    <i class="fas fa-clock mr-2"></i>
                    Monday - Friday 08:00 - 18:00
                </div>
                <div class="flex items-center">
                    <i class="fas fa-envelope mr-2"></i>
                    collector@cleancity@gmail.com
                </div>
                <div class="flex items-center">
                    <i class="fas fa-phone mr-2"></i>
                    +94 81 456 7890
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="#" class="hover:text-orange-300 transition-colors"><i class="fab fa-facebook"></i></a>
                <a href="#" class="hover:text-orange-300 transition-colors"><i class="fab fa-twitter"></i></a>
                <a href="#" class="hover:text-orange-300 transition-colors"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </div>
    <!-- Mobile-only compact bar -->
    <div class="sm:hidden bg-orange-600 text-white py-1.5 text-[11px]">
        <div class="max-w-7xl mx-auto px-3 space-y-1">
            <div class="flex items-center justify-between">
                <span class="flex items-center mr-3"><i class="fas fa-clock mr-1.5"></i>Mon-Fri 08:00-18:00</span>
                <span class="flex items-center"><i class="fas fa-phone mr-1.5"></i>+94 81 456 7890</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="flex items-center truncate"><i class="fas fa-envelope mr-1.5"></i><span class="truncate">collector@cleancity@gmail.com</span></span>
                <span class="flex items-center space-x-2 ml-3">
                    <a href="#" class="hover:text-orange-300"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="hover:text-orange-300"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="hover:text-orange-300"><i class="fab fa-instagram"></i></a>
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

                <!-- CTA Buttons -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('collector.login') }}" class="border-2 border-orange-500 text-orange-500 bg-orange-50 px-4 sm:px-6 py-2 sm:py-3 rounded-lg font-semibold text-sm sm:text-base">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Login
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Forgot Password Section -->
    <section class="min-h-screen bg-cover bg-center relative parallax-bg" style="background-image: url('{{ asset('images/CTA2.jpg') }}');">
        <!-- Background overlay -->
        <div class="absolute inset-0 bg-black/50"></div>
        
        <!-- Content -->
        <div class="relative z-10 py-16 px-4">
            <div class="max-w-6xl mx-auto">
                <div class="flex justify-center items-center min-h-[calc(100vh-200px)]">
                    <!-- Reset Password Form -->
                    <div class="w-full max-w-md">
                        <div class="bg-white rounded-2xl shadow-2xl p-8 animate-on-scroll stagger-1">
                            <div class="text-center mb-8">
                                <div class="w-16 h-16 bg-orange-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-key text-white text-2xl"></i>
                                </div>
                                <h2 class="text-3xl font-bold text-gray-800 mb-2">Forgot Password?</h2>
                                <p class="text-gray-600">Enter your email address and we'll send you a link to reset your password</p>
                            </div>

                            <!-- Session Status -->
                            @if (session('status'))
                                <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 border border-green-300 rounded-lg px-4 py-3">
                                    <div class="flex items-center">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        {{ session('status') }}
                                    </div>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('collector.password.email') }}" class="space-y-6">
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
                                            value="{{ old('email') }}"
                                            required
                                            autofocus
                                            class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                                            placeholder="Enter your email address"
                                        >
                                    </div>
                                    @if($errors->has('email'))
                                        <p class="mt-2 text-sm text-red-600">{{ $errors->first('email') }}</p>
                                    @endif
                                </div>

                                <button 
                                    type="submit" 
                                    class="w-full bg-orange-500 hover:bg-orange-600 text-white py-3 rounded-lg font-semibold text-lg transition-all duration-300 transform hover:scale-105 focus:ring-4 focus:ring-orange-200"
                                >
                                    <i class="fas fa-paper-plane mr-2"></i>
                                    Send Password Reset Link
                                </button>
                            </form>

                            <div class="mt-8 text-center">
                                <p class="text-gray-600">
                                    Remember your password? 
                                    <a href="{{ route('collector.login') }}" class="text-orange-600 hover:text-orange-500 font-semibold">
                                        Back to login
                                    </a>
                                </p>
                                <div class="border-t border-gray-200 pt-4 mt-4">
                                    <p class="text-gray-600">
                                        Not a collector? 
                                        <a href="{{ route('login') }}" class="text-orange-600 hover:text-orange-500 font-semibold">
                                            Resident login
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-orange-900 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Company Info -->
                <div>
                    <div class="flex items-center text-xl font-bold mb-4">
                        <i class="fas fa-truck text-2xl mr-3"></i>
                        Clean City Collectors
                    </div>
                    <p class="text-gray-300 mb-4 leading-relaxed">
                        Empowering waste collectors with smart tools for efficient collection and route management.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-lg font-bold text-orange-300 mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-orange-300 transition-colors">Collector Resources</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-orange-300 transition-colors">Route Planning</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-orange-300 transition-colors">Support</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-orange-300 transition-colors">Training</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h4 class="text-lg font-bold text-orange-300 mb-4">Collector Support</h4>
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <i class="fas fa-phone text-orange-300 mr-3"></i>
                            <span class="text-gray-300">+94 81 456 7890</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-orange-300 mr-3"></i>
                            <span class="text-gray-300">collector@cleancity.gmail.com</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="border-t border-orange-800 pt-6 mt-6 text-center">
                <p class="text-gray-400">&copy; 2025 CleanCity Collector Portal. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Initialize animations when DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            // Animate elements on load
            setTimeout(() => {
                const animateElements = document.querySelectorAll('.animate-on-scroll');
                animateElements.forEach((el, index) => {
                    setTimeout(() => {
                        el.classList.add('animate-fade-in-right');
                    }, index * 200);
                });
            }, 300);

            // Set body as loaded
            document.body.classList.add('loaded');
        });

        // Add smooth transitions on load
        window.addEventListener('load', () => {
            document.body.style.opacity = '1';
        });
    </script>
</body>
</html>
