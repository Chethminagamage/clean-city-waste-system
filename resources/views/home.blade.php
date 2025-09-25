<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clean City - Waste Management Solutions</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
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

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
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

        .animate-fade-in {
            animation: fadeIn 0.8s ease-out forwards;
        }

        .animate-scale-in {
            animation: scaleIn 0.6s ease-out forwards;
        }

        /* Initially hidden for animation */
        .animate-on-scroll {
            opacity: 0;
        }

        /* Stagger animation delays */
        .stagger-1 { animation-delay: 0.1s; }
        .stagger-2 { animation-delay: 0.2s; }
        .stagger-3 { animation-delay: 0.3s; }
        .stagger-4 { animation-delay: 0.4s; }

        /* Hover effects */
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        /* Smooth transitions */
        * {
            transition: color 0.3s ease, background-color 0.3s ease, border-color 0.3s ease, transform 0.3s ease;
        }

        /* Parallax effect for hero */
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
<body class="font-sans text-gray-800">

    <!-- Error/Success Messages -->
    @if(session('error'))
        <div class="bg-red-500 text-white px-4 py-3 text-center font-medium">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-500 text-white px-4 py-3 text-center font-medium">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

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
                    <a href="https://wa.me/94814567890?text=Hi! I'd like to schedule a waste pickup for my location. Please provide me with available time slots and pricing information." target="_blank" class="bg-green-500 hover:bg-green-600 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg font-semibold text-sm sm:text-base transition-all duration-300 transform hover:scale-105">
                        <i class="fab fa-whatsapp mr-2"></i>Schedule Pickup
                    </a>
                    <a href="{{ route('login') }}" class="border-2 border-green-500 text-green-500 hover:bg-green-500 hover:text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg font-semibold text-sm sm:text-base transition-all duration-300">
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
                    <a href="https://wa.me/94814567890?text=Hi! I'd like to schedule a waste pickup for my location. Please provide me with available time slots and pricing information." target="_blank" class="bg-green-500 hover:bg-green-600 text-white px-4 py-3 rounded-lg font-semibold text-center mx-4 transition-colors">
                        <i class="fab fa-whatsapp mr-2"></i>Schedule Pickup
                    </a>
                    <a href="{{ route('login') }}" class="border-2 border-green-500 text-green-500 hover:bg-green-500 hover:text-white px-4 py-3 rounded-lg font-semibold text-center mx-4 transition-colors">
                        <i class="fas fa-user mr-2"></i>Login
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center overflow-hidden parallax-bg" style="background-image: url('{{ asset('images/bg3.png') }}');">
        <!-- Background overlay -->
        <div class="absolute inset-0 bg-black/50"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 py-16 sm:py-20">
            <div class="grid lg:grid-cols-2 gap-8 sm:gap-12 items-center">
                <!-- Hero Content -->
                <div class="text-white text-center lg:text-left animate-on-scroll">
                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-4 sm:mb-6">
                        Efficient Waste Management For a <span class="text-green-400">Greener World</span>
                    </h1>
                    <p class="text-base sm:text-lg md:text-xl lg:text-2xl mb-6 sm:mb-8 text-white/90 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                        Delivering smart waste solutions for homes, businesses & industries to keeping communities clean and protecting the environment every day.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 max-w-md mx-auto lg:mx-0">
                        <a href="#services" class="bg-green-500 hover:bg-green-600 text-white px-6 sm:px-8 py-3 sm:py-4 rounded-lg font-semibold text-base sm:text-lg transition-all duration-300 transform hover:scale-105 text-center">
                            View Project
                        </a>
                        <a href="https://wa.me/94814567890?text=Hi! I'd like to schedule a waste pickup for my location. Please provide me with available time slots and pricing information." target="_blank" class="border-2 border-white text-white hover:bg-white hover:text-green-500 px-6 sm:px-8 py-3 sm:py-4 rounded-lg font-semibold text-base sm:text-lg transition-all duration-300 text-center">
                            </i>Schedule Pickup
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-12 sm:py-16 lg:py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <!-- Section Heading -->
            <div class="text-center mb-8 sm:mb-12 lg:mb-16 animate-on-scroll">
                <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-gray-800 mb-4">
                    Our <span class="text-green-500">Services</span>
                </h2>
                <p class="text-base sm:text-lg md:text-xl text-gray-600 max-w-3xl mx-auto">
                    Comprehensive waste management solutions designed to keep your community clean and sustainable
                </p>
            </div>

            <!-- Services Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 lg:gap-8">
                <!-- Service Card 1 -->
                <div class="bg-white rounded-lg overflow-hidden shadow-lg hover-lift animate-on-scroll stagger-1">
                    <div class="h-32 sm:h-40 lg:h-48 bg-cover bg-center">
                      <img src="{{ asset('images/service1.jpg') }}" alt="service - 1">
                    </div>
                    <div class="p-4 sm:p-6">
                        <h4 class="font-semibold text-base sm:text-lg mb-2">Organic Waste Management</h4>
                        <p class="text-gray-600 text-xs sm:text-sm">Promotes composting and sustainable handling of biodegradable waste.</p>
                    </div>
                </div>

                <!-- Service Card 2 -->
                <div class="bg-white rounded-lg overflow-hidden shadow-lg hover-lift animate-on-scroll stagger-2">
                    <div class="h-32 sm:h-40 lg:h-48 bg-cover bg-center">
                      <img src="{{ asset('images/service2.jpg') }}" alt="service - 2">
                    </div>
                    <div class="p-4 sm:p-6">
                        <h4 class="font-semibold text-base sm:text-lg mb-2">Bin Report Submission</h4>
                        <p class="text-gray-600 text-xs sm:text-sm">Residents can report full bins, missed pickups, or request urgent collection.</p>
                    </div>
                </div>

                <!-- Service Card 3 -->
                <div class="bg-white rounded-lg overflow-hidden shadow-lg hover-lift animate-on-scroll stagger-3">
                    <div class="h-32 sm:h-40 lg:h-48 bg-cover bg-center">
                      <img src="{{ asset('images/service3.jpg') }}" alt="service - 3">
                    </div>
                    <div class="p-4 sm:p-6">
                        <h4 class="font-semibold text-base sm:text-lg mb-2">Waste Collection</h4>
                        <p class="text-gray-600 text-xs sm:text-sm">Regular and scheduled bin pickups for residential zones and city areas.</p>
                    </div>
                </div>

                <!-- Service Card 4 -->
                <div class="bg-white rounded-lg overflow-hidden shadow-lg hover-lift animate-on-scroll stagger-4">
                    <div class="h-32 sm:h-40 lg:h-48 bg-cover bg-center">
                      <img src="{{ asset('images/service4.jpg') }}" alt="service - 4">
                    </div>
                    <div class="p-4 sm:p-6">
                        <h4 class="font-semibold text-base sm:text-lg mb-2">Status Alerts & Notifications</h4>
                        <p class="text-gray-600 text-xs sm:text-sm">Users receive real-time updates on delays, pickups, and alert messages.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section class="py-12 sm:py-16 lg:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-12 sm:mb-16 animate-on-scroll">
                <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-gray-800 mb-4 sm:mb-6">
                    A Simple Process For All Your Waste Management Needs
                </h2>
                <p class="text-base sm:text-lg md:text-xl text-gray-600 max-w-3xl mx-auto">
                    Everything you need to tackle waste collection, recycling, and environmental responsibility.
                </p>
            </div>

            <!-- Process Steps -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
                <!-- Step 1 -->
                <div class="text-center animate-on-scroll stagger-1">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 lg:w-24 lg:h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6 hover-lift">
                        <i class="fas fa-phone text-green-500 text-lg sm:text-xl lg:text-2xl"></i>
                    </div>
                    <h4 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 sm:mb-4">Request & Pickup</h4>
                    <p class="text-gray-600 text-sm sm:text-base">
                        Waste bins reported via dashboard are scheduled for pickup from homes or zones.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="text-center animate-on-scroll stagger-2">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 lg:w-24 lg:h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6 hover-lift">
                        <i class="fas fa-truck text-green-500 text-lg sm:text-xl lg:text-2xl"></i>
                    </div>
                    <h4 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 sm:mb-4">Smart Transportation</h4>
                    <p class="text-gray-600 text-sm sm:text-base">
                        Our vehicles pick up reported waste using the optimal route and time.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="text-center animate-on-scroll stagger-3">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 lg:w-24 lg:h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6 hover-lift">
                        <i class="fas fa-cogs text-green-500 text-lg sm:text-xl lg:text-2xl"></i>
                    </div>
                    <h4 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 sm:mb-4">Sorting & Eco-Processing</h4>
                    <p class="text-gray-600 text-sm sm:text-base">
                        Collected waste is sorted (organic, plastic, etc.) and processed responsibly.
                    </p>
                </div>

                <!-- Step 4 -->
                <div class="text-center animate-on-scroll stagger-4">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 lg:w-24 lg:h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6 hover-lift">
                        <i class="fas fa-recycle text-green-500 text-lg sm:text-xl lg:text-2xl"></i>
                    </div>
                    <h4 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 sm:mb-4">Safe Disposal & Recycling</h4>
                    <p class="text-gray-600 text-sm sm:text-base">
                        Waste is either safely disposed of or sent for material recovery and recycling.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 bg-green-700 text-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
                <div class="animate-on-scroll stagger-1">
                    <div class="text-5xl font-bold text-green-300 mb-2" data-count="28950">0</div>
                    <p class="text-lg">Happy Customers</p>
                </div>
                <div class="animate-on-scroll stagger-2">
                    <div class="text-5xl font-bold text-green-300 mb-2" data-count="240">0</div>
                    <p class="text-lg">Pickup Points</p>
                </div>
                <div class="animate-on-scroll stagger-3">
                    <div class="text-5xl font-bold text-green-300 mb-2" data-count="158">0</div>
                    <p class="text-lg">Skilled Workers</p>
                </div>
                <div class="animate-on-scroll stagger-4">
                    <div class="text-5xl font-bold text-green-300 mb-2" data-count="20">0</div>
                    <p class="text-lg">Provinces</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-cover bg-center relative parallax-bg" style="background-image: url('{{ asset('images/CTA1.jpg') }}');">
        <div class="absolute inset-0 bg-green-700/50"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="text-white animate-on-scroll">
                    <h2 class="text-4xl lg:text-5xl font-bold mb-6">
                        Contact us today to schedule your waste service and keep your space clean
                    </h2>
                </div>
                <div class="bg-green-500 rounded-2xl p-8 text-center text-white hover-lift animate-on-scroll">
                    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-phone text-2xl"></i>
                    </div>
                    <p class="text-lg font-semibold">Call Us Now</p>
                    <p class="text-2xl font-bold">+94 81 456 7890</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-4xl font-bold text-center text-gray-800 mb-16 animate-on-scroll">Our Happy Customers</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-white rounded-lg p-6 shadow-lg hover-lift animate-on-scroll stagger-1">
                    <div class="flex items-center mb-4">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" 
                             alt="Customer" class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h5 class="font-semibold">John Smith</h5>
                            <p class="text-sm text-gray-600">Business Owner</p>
                        </div>
                    </div>
                    <p class="text-gray-700 italic">"Working with this company has been a game changer for our business. Their waste collection is always reliable."</p>
                </div>

                <!-- Testimonial 2 -->
                <div class="bg-white rounded-lg p-6 shadow-lg hover-lift animate-on-scroll stagger-2">
                    <div class="flex items-center mb-4">
                        <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" 
                             alt="Customer" class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h5 class="font-semibold">Sarah Johnson</h5>
                            <p class="text-sm text-gray-600">Facility Manager</p>
                        </div>
                    </div>
                    <p class="text-gray-700 italic">"Working with this company and stuff. I never thought the recycling process will good and have great results."</p>
                </div>

                <!-- Testimonial 3 -->
                <div class="bg-white rounded-lg p-6 shadow-lg hover-lift animate-on-scroll stagger-3">
                    <div class="flex items-center mb-4">
                        <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" 
                             alt="Customer" class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h5 class="font-semibold">Mike Davis</h5>
                            <p class="text-sm text-gray-600">Restaurant Owner</p>
                        </div>
                    </div>
                    <p class="text-gray-700 italic">"I strongly recommend this company, they was awesome and they had great communication with me."</p>
                </div>

                <!-- Testimonial 4 -->
                <div class="bg-white rounded-lg p-6 shadow-lg hover-lift animate-on-scroll stagger-4">
                    <div class="flex items-center mb-4">
                        <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" 
                             alt="Customer" class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h5 class="font-semibold">Lisa Wilson</h5>
                            <p class="text-sm text-gray-600">Office Manager</p>
                        </div>
                    </div>
                    <p class="text-gray-700 italic">"Working with this company and their services is great good. I was great and great one again."</p>
                </div>
            </div>

            <!-- Navigation dots -->
            <div class="flex justify-center mt-8 space-x-2 animate-on-scroll">
                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                <div class="w-3 h-3 bg-gray-300 rounded-full"></div>
                <div class="w-3 h-3 bg-gray-300 rounded-full"></div>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="py-12 sm:py-16 lg:py-20 bg-cover bg-center relative parallax-bg" style="background-image: url('{{ asset('images/CTA2.jpg') }}');">
        <div class="absolute inset-0 bg-black/60"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6">
            <div class="grid lg:grid-cols-2 gap-8 sm:gap-12 items-center">
                <div class="text-white text-center lg:text-left animate-on-scroll">
                    <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold mb-4 sm:mb-6">
                        Efficient Waste Disposal Starts With Your Report
                    </h2>
                    <p class="text-base sm:text-lg md:text-xl mb-6 sm:mb-8">
                        Ready to make a positive impact on the environment? Contact us today to schedule your waste management service.
                    </p>
                    <div class="space-y-3 sm:space-y-4">
                        <div class="flex items-center justify-center lg:justify-start">
                            <i class="fas fa-phone text-green-400 text-lg sm:text-xl mr-3 sm:mr-4"></i>
                            <span class="text-base sm:text-lg">+94 81 456 7890</span>
                        </div>
                        <div class="flex items-center justify-center lg:justify-start">
                            <i class="fas fa-envelope text-green-400 text-lg sm:text-xl mr-3 sm:mr-4"></i>
                            <span class="text-base sm:text-lg">contact@cleancity.gmail.com</span>
                        </div>
                        <div class="flex items-center justify-center lg:justify-start">
                            <i class="fas fa-map-marker-alt text-green-400 text-lg sm:text-xl mr-3 sm:mr-4"></i>
                            <span class="text-base sm:text-lg">123, Green Street, Colombo</span>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-2xl hover-lift animate-on-scroll">
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4 sm:mb-6 text-center">Book An Appointment</h3>
                    <form class="space-y-4 sm:space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                            <input type="text" placeholder="Your Name" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm sm:text-base">
                            <input type="email" placeholder="Email Address" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm sm:text-base">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                            <input type="tel" placeholder="Phone Number" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm sm:text-base">
                            <select class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm sm:text-base">
                                <option>Select Service</option>
                                <option>Waste Collection</option>
                                <option>Recycling</option>
                                <option>Dumpster Rental</option>
                            </select>
                        </div>
                        <textarea placeholder="Message" rows="3" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm sm:text-base"></textarea>
                        <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white py-2 sm:py-3 rounded-lg font-semibold text-sm sm:text-base transition-all duration-300 transform hover:scale-105">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-12 sm:py-16 lg:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="grid lg:grid-cols-2 gap-8 sm:gap-12 items-center">
                <div class="text-center lg:text-left animate-on-scroll">
                  <span class="text-green-500 font-semibold text-lg">Our Mission</span>
                    <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-gray-800 mb-4 sm:mb-6">
                        Responsible Waste Disposal for a Healthier Tomorrow
                    </h2>
                    <div class="space-y-3 sm:space-y-4">
                        <div class="flex items-start text-left">
                            <i class="fas fa-check-circle text-green-500 text-lg sm:text-xl mr-3 sm:mr-4 mt-1 flex-shrink-0"></i>
                            <p class="text-gray-600 text-sm sm:text-base">Provide smart, eco-friendly waste collection and disposal services for all zones.</p>
                        </div>
                        <div class="flex items-start text-left">
                            <i class="fas fa-check-circle text-green-500 text-lg sm:text-xl mr-3 sm:mr-4 mt-1 flex-shrink-0"></i>
                            <p class="text-gray-600 text-sm sm:text-base">Promote sustainability through digital reporting tools and optimized pickup schedules.</p>
                        </div>
                        <div class="flex items-start text-left">
                            <i class="fas fa-check-circle text-green-500 text-lg sm:text-xl mr-3 sm:mr-4 mt-1 flex-shrink-0"></i>
                            <p class="text-gray-600 text-sm sm:text-base">Ensure full compliance with environmental laws using real-time monitoring systems.</p>
                        </div>
                        <div class="flex items-start text-left">
                            <i class="fas fa-check-circle text-green-500 text-lg sm:text-xl mr-3 sm:mr-4 mt-1 flex-shrink-0"></i>
                            <p class="text-gray-600 text-sm sm:text-base">Educate the community on responsible waste management and green lifestyle practices.</p>
                        </div>
                    </div>
                </div>
                <div class="relative animate-on-scroll">
                    <div class="w-full h-64 sm:h-80 lg:h-96 bg-green-100 rounded-3xl flex items-center justify-center">
                        <div class="w-24 h-24 sm:w-28 sm:h-28 lg:w-32 lg:h-32 bg-green-500 rounded-full flex items-center justify-center hover-lift">
                            <i class="fas fa-recycle text-white text-2xl sm:text-3xl lg:text-4xl"></i>
                        </div>
                    </div>
                    <!-- Decorative elements -->
                    <div class="absolute -top-2 -right-2 sm:-top-4 sm:-right-4 w-12 h-12 sm:w-16 sm:h-16 bg-green-400/20 rounded-full"></div>
                    <div class="absolute -bottom-2 -left-2 sm:-bottom-4 sm:-left-4 w-8 h-8 sm:w-12 sm:h-12 bg-green-500/20 rounded-full"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="bg-green-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Footer Content -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                <!-- Company Info -->
                <div class="lg:col-span-1 animate-on-scroll stagger-1">
                    <div class="flex items-center text-2xl font-bold mb-6">
                        <i class="fas fa-recycle text-3xl mr-3"></i>
                        Clean City
                    </div>
                    <p class="text-gray-300 mb-6 leading-relaxed">
                        We are committed to providing dependable, eco-friendly waste management solutions tailored to the unique needs of homes, businesses, and industries.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-green-400 transition-all duration-300 transform hover:-translate-y-1">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-green-400 transition-all duration-300 transform hover:-translate-y-1">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-green-400 transition-all duration-300 transform hover:-translate-y-1">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-green-400 transition-all duration-300 transform hover:-translate-y-1">
                            <i class="fab fa-linkedin"></i>
                        </a>
                    </div>
                </div>

                <!-- Company Links -->
                <div class="animate-on-scroll stagger-2">
                    <h4 class="text-xl font-bold text-green-300 mb-6">Company</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-300 hover:text-green-300 transition-colors duration-300">About Us</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-green-300 transition-colors duration-300">Our Team</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-green-300 transition-colors duration-300">Careers</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-green-300 transition-colors duration-300">Blog</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-green-300 transition-colors duration-300">News</a></li>
                    </ul>
                </div>

                <!-- Services Links -->
                <div class="animate-on-scroll stagger-3">
                    <h4 class="text-xl font-bold text-green-300 mb-6">Our Services</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-300 hover:text-green-300 transition-colors duration-300">Waste Collection</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-green-300 transition-colors duration-300">Medical Waste</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-green-300 transition-colors duration-300">E-waste Disposal</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-green-300 transition-colors duration-300">Organic Waste</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-green-300 transition-colors duration-300">Commercial Recycling</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="animate-on-scroll stagger-4">
                    <h4 class="text-xl font-bold text-green-300 mb-6">Contact Info</h4>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <i class="fas fa-phone text-green-300 mr-3"></i>
                            <span class="text-gray-300">+94 81 456 7890</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-green-300 mr-3"></i>
                            <span class="text-gray-300">contact@cleancity.gmail.com</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-clock text-green-300 mr-3"></i>
                            <span class="text-gray-300">Monday - Friday 08:00 - 18:00</span>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-map-marker-alt text-green-300 mr-3 mt-1"></i>
                            <span class="text-gray-300">123, Green Street, Colombo, 12345</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Newsletter Signup -->
            <div class="border-t border-green-800 pt-8 mb-8">
                <div class="text-center max-w-2xl mx-auto animate-on-scroll">
                    <h3 class="text-2xl font-bold mb-4">Subscribe to Newsletter</h3>
                    <p class="text-gray-300 mb-6">Stay updated with our latest news and offers</p>
                    <div class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
                        <input type="email" placeholder="Enter Your Email" class="flex-1 px-4 py-3 rounded-full bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:outline-none focus:border-green-300">
                        <button class="bg-green-400 text-green-900 px-8 py-3 rounded-full font-bold hover:bg-green-300 transition-colors duration-300">
                            Subscribe
                        </button>
                    </div>
                    <p class="text-sm text-gray-400 mt-4">Your email is safe with us. We don't spam.</p>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="border-t border-green-800 pt-8 text-center">
                <p class="text-gray-400">&copy; 2025 CleanCity. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript for Animations and Interactions -->
    <script>
        // Scroll indicator
        window.addEventListener('scroll', () => {
            const scrollTop = document.documentElement.scrollTop;
            const scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrollProgress = (scrollTop / scrollHeight) * 100;
            document.getElementById('scrollIndicator').style.width = scrollProgress + '%';
        });

        // Intersection Observer for smooth animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const element = entry.target;
                    
                    // Add appropriate animation class based on position
                    if (element.classList.contains('animate-on-scroll')) {
                        if (element.closest('.hero, .cta')) {
                            element.classList.add('animate-fade-in-left');
                        } else if (element.closest('.stats')) {
                            element.classList.add('animate-scale-in');
                        } else {
                            element.classList.add('animate-fade-in-up');
                        }
                    }
                    
                    // Trigger counter animation for stats section
                    if (element.querySelector('[data-count]')) {
                        animateCounters();
                    }
                    
                    // Unobserve after animation
                    observer.unobserve(element);
                }
            });
        }, observerOptions);

        // Counter Animation
        function animateCounters() {
            const counters = document.querySelectorAll('[data-count]');
            
            counters.forEach(counter => {
                const target = parseInt(counter.getAttribute('data-count'));
                const increment = target / 100;
                let current = 0;
                
                const updateCounter = () => {
                    if (current < target) {
                        current += increment;
                        counter.textContent = Math.ceil(current);
                        requestAnimationFrame(updateCounter);
                    } else {
                        counter.textContent = target.toLocaleString();
                    }
                };
                
                updateCounter();
            });
        }

        // Initialize animations when DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            // Observe all elements with animate-on-scroll class
            const animateElements = document.querySelectorAll('.animate-on-scroll');
            animateElements.forEach(el => observer.observe(el));

            // Animate hero section immediately
            const heroContent = document.querySelector('.hero .animate-on-scroll, section:first-of-type .animate-on-scroll');
            if (heroContent) {
                setTimeout(() => {
                    heroContent.classList.add('animate-fade-in-left');
                }, 200);
            }
        });

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
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

            // Close mobile menu when clicking on links
            const mobileLinks = mobileMenu.querySelectorAll('a');
            mobileLinks.forEach(link => {
                link.addEventListener('click', () => {
                    mobileMenu.classList.add('hidden');
                    const icon = mobileMenuBtn.querySelector('i');
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                });
            });
        }

        // Header scroll effect
        window.addEventListener('scroll', () => {
            const header = document.querySelector('header');
            if (window.scrollY > 100) {
                header.classList.add('bg-white', 'shadow-lg');
                header.classList.remove('bg-white/95');
            } else {
                header.classList.remove('bg-white', 'shadow-lg');
                header.classList.add('bg-white/95');
            }
        });

        // Form submission
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            // Show success message with animation
            const button = this.querySelector('button[type="submit"]');
            const originalText = button.textContent;
            button.textContent = 'Sent Successfully!';
            button.classList.add('bg-green-600');
            
            setTimeout(() => {
                button.textContent = originalText;
                button.classList.remove('bg-green-600');
            }, 2000);
        });

        // Add loading state when page loads
        window.addEventListener('load', () => {
            document.body.style.opacity = '1';
        });

        // Set initial body opacity for smooth load
        document.body.style.opacity = '0';
        document.body.style.transition = 'opacity 0.5s ease-in-out';
    </script>
</body>
</html>