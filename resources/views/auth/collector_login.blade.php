<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collector Login - Clean City Waste Management</title>
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
                        'orange-collector': '#f97316',
                        'orange-collector-dark': '#ea580c',
                    },
                }
            }
        }
    </script>
</head>
<body class="font-sans text-gray-800 bg-[#f3fef5]">

    <!-- Top Contact Bar -->
    <div class="bg-orange-600 text-white py-2 text-sm">
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
                <a href="#" class="hover:text-orange-300 transition-colors"><i class="fab fa-youtube"></i></a>
                <a href="#" class="hover:text-orange-300 transition-colors"><i class="fab fa-pinterest"></i></a>
                <a href="#" class="hover:text-orange-300 transition-colors"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </div>

    <!-- Header/Navigation -->
    <header class="bg-white/95 backdrop-blur-md sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex justify-between items-center py-3 sm:py-4">
                <!-- Logo -->
                <div class="flex items-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-8 h-8 sm:w-10 sm:h-10 object-contain mr-2 sm:mr-3">
                    <div>
                        <span class="text-xl sm:text-2xl font-bold text-gray-800">Clean City</span>
                        <p class="text-xs sm:text-sm text-orange-600 hidden sm:block">Your Waste, Our Responsibility</p>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="hidden lg:flex items-center space-x-8">
                    <div class="relative group">
                        <a href="#" class="text-gray-700 hover:text-orange-500 font-medium flex items-center">
                            Home <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </a>
                    </div>
                    <div class="relative group">
                        <a href="#" class="text-gray-700 hover:text-orange-500 font-medium flex items-center">
                            Service <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </a>
                    </div>
                    <a href="#" class="text-gray-700 hover:text-orange-500 font-medium">Projects</a>
                    <div class="relative group">
                        <a href="#" class="text-gray-700 hover:text-orange-500 font-medium flex items-center">
                            Company <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </a>
                    </div>
                    <a href="#" class="text-gray-700 hover:text-orange-500 font-medium">Blog</a>
                    <a href="#" class="text-gray-700 hover:text-orange-500 font-medium">Contact</a>
                </nav>

                <!-- CTA Buttons -->
                <div class="hidden sm:flex items-center space-x-3">
                    
                    <a href="{{ route('login') }}" class="border-2 border-orange-500 text-orange-500 bg-orange-50 px-4 sm:px-6 py-2 sm:py-3 rounded-lg font-semibold text-sm sm:text-base">
                        <i class="fas fa-user mr-2"></i>Resident Login
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
                    <a href="#" class="text-gray-700 hover:text-orange-500 font-medium px-4 py-2">Home</a>
                    <a href="#" class="text-gray-700 hover:text-orange-500 font-medium px-4 py-2">Service</a>
                    <a href="#" class="text-gray-700 hover:text-orange-500 font-medium px-4 py-2">Projects</a>
                    <a href="#" class="text-gray-700 hover:text-orange-500 font-medium px-4 py-2">Company</a>
                    <a href="#" class="text-gray-700 hover:text-orange-500 font-medium px-4 py-2">Blog</a>
                    <a href="#" class="text-gray-700 hover:text-orange-500 font-medium px-4 py-2">Contact</a>
                    <a href="#contact" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-3 rounded-lg font-semibold text-center mx-4 transition-colors">
                        Join as Collector
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Login Section -->
    <section class="min-h-screen bg-cover bg-center relative parallax-bg" style="background-image: url('{{ asset('images/CTA2.jpg') }}');">
        <!-- Background overlay -->
        <div class="absolute inset-0 bg-black/50"></div>
        
        <!-- Content -->
        <div class="relative z-10 py-16 px-4">
            <div class="max-w-6xl mx-auto">
                <div class="grid lg:grid-cols-2 gap-12 items-center min-h-[calc(100vh-200px)]">
                    
                    <!-- Left Side - Illustration & Branding -->
                    <div class="text-center lg:text-left animate-on-scroll">
                        

                        <!-- Illustration Area -->
                        <div class="bg-orange-100/20 backdrop-blur-sm rounded-3xl p-8 mb-8">
                            <div class="w-full h-80 bg-orange-50/30 rounded-2xl flex items-center justify-center">
                                <img src="{{ asset('images/login-illustration.png') }}" alt="Waste Collection Illustration" 
                                     class="w-[240px] sm:w-[280px] md:w-[320px] h-auto object-contain">
                            </div>
                        </div>

                        <div class="text-white space-y-4">
                            <div class="flex items-center justify-center lg:justify-start">
                                <i class="fas fa-check-circle text-orange-400 mr-3"></i>
                                <span>Manage collection routes efficiently</span>
                            </div>
                            <div class="flex items-center justify-center lg:justify-start">
                                <i class="fas fa-check-circle text-orange-400 mr-3"></i>
                                <span>Real-time pickup tracking</span>
                            </div>
                            <div class="flex items-center justify-center lg:justify-start">
                                <i class="fas fa-check-circle text-orange-400 mr-3"></i>
                                <span>Optimize your collection schedule</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Login Form -->
                    <div class="flex items-center justify-center">
                        <div class="w-full max-w-md">
                            <div class="bg-white rounded-2xl shadow-2xl p-8 animate-on-scroll stagger-1">
                                <div class="text-center mb-8">
                                    <div class="w-16 h-16 bg-orange-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-hard-hat text-white text-2xl"></i>
                                    </div>
                                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Collector Login</h2>
                                    <p class="text-gray-600">Access your waste collection dashboard securely</p>
                                </div>

                                <!-- Error Messages -->
                                @if ($errors->any())
                                <div class="mb-6 text-sm text-red-600 bg-red-100 border border-red-300 rounded-lg px-4 py-3">
                                    <div class="flex items-center">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>
                                        {{ $errors->first() }}
                                    </div>
                                </div>
                                @endif

                                <form method="POST" action="{{ route('collector.login.submit') }}" class="space-y-6">
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
                                                required
                                                autofocus
                                                class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                                                placeholder="Enter your email"
                                            >
                                        </div>
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
                                                class="w-full pl-10 pr-12 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
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
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <input 
                                                type="checkbox" 
                                                id="remember" 
                                                name="remember"
                                                class="h-4 w-4 text-orange-500 focus:ring-orange-500 border-gray-300 rounded"
                                            >
                                            <label for="remember" class="ml-2 text-sm text-gray-600">
                                                Remember me
                                            </label>
                                        </div>
                                        <a href="#" class="text-sm text-orange-600 hover:text-orange-500 font-medium">
                                            Forgot password?
                                        </a>
                                    </div>

                                    <button 
                                        type="submit" 
                                        class="w-full bg-orange-500 hover:bg-orange-600 text-white py-3 rounded-lg font-semibold text-lg transition-all duration-300 transform hover:scale-105 focus:ring-4 focus:ring-orange-200"
                                    >
                                        <i class="fas fa-sign-in-alt mr-2"></i>
                                        Access Dashboard
                                    </button>
                                </form>

                                <div class="mt-8 text-center space-y-4">
                                    <p class="text-gray-600">
                                        Not a collector? 
                                        <a href="{{ route('login') }}" class="text-orange-600 hover:text-orange-500 font-semibold">
                                            Back to resident login
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