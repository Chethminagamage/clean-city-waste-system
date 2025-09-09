<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Projects - Clean City</title>
    
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

        .animate-fade-up {
            animation: fadeInUp 0.8s ease-out;
        }

        .project-card {
            transition: all 0.3s ease;
        }

        .project-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .animate-on-scroll {
            opacity: 0;
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .stagger-1 { animation-delay: 0.1s; }
        .stagger-2 { animation-delay: 0.2s; }
        .stagger-3 { animation-delay: 0.3s; }
        .stagger-4 { animation-delay: 0.4s; }

        .hover-lift {
            transition: all 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
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
                    <a href="{{ route('landing.home') }}" class="text-gray-700 hover:text-green-500 font-medium">Home</a>
                    <a href="{{ route('public.services') }}" class="text-gray-700 hover:text-green-500 font-medium">Service</a>
                    <a href="{{ route('public.projects') }}" class="text-green-500 font-medium">Projects</a>
                    <a href="{{ route('public.company') }}" class="text-gray-700 hover:text-green-500 font-medium">Company</a>
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
                    <a href="{{ route('public.projects') }}" class="text-green-500 font-medium px-4 py-2">Projects</a>
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
    <section class="pt-32 pb-12 sm:pb-16 lg:pb-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="max-w-4xl mx-auto text-center animate-fade-up">
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-gray-800 mb-4 sm:mb-6">
                    Our <span class="text-green-500">Projects</span>
                </h1>
                <p class="text-base sm:text-lg md:text-xl text-gray-600 mb-6 sm:mb-8">
                    Explore our innovative waste management projects that are making a real difference 
                    in communities across Sri Lanka and beyond.
                </p>
            </div>
        </div>
    </section>

    <!-- Featured Projects -->
    <section class="py-12 sm:py-16 lg:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-8 sm:mb-12">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800 mb-3 sm:mb-4">Featured Projects</h2>
                <p class="text-base sm:text-lg md:text-xl text-gray-600 max-w-3xl mx-auto">
                    Our flagship projects showcase innovative solutions in waste management and environmental sustainability.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                <!-- Smart City Waste Management -->
                <div class="project-card bg-white rounded-2xl shadow-lg overflow-hidden hover-lift">
                    <div class="overflow-hidden">
                        <div class="w-full h-48 rounded-t-xl overflow-hidden">
                            <img src="{{ asset('images/P1.jpg') }}" 
                                alt="Smart City Waste Management" 
                                class="w-full h-full object-cover">
                        </div>
                    </div>
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center mb-2">
                            <span class="bg-green-100 text-green-600 px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium">Ongoing</span>
                            <span class="ml-2 text-gray-500 text-xs sm:text-sm">2024 - 2026</span>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-2 sm:mb-3">Smart City Waste Management</h3>
                        <p class="text-sm sm:text-base text-gray-600 mb-3 sm:mb-4">
                            Implementing IoT-enabled smart bins and real-time monitoring systems across Colombo to optimize collection routes.
                        </p>
                        <div class="flex items-center justify-between">
                            <span class="text-green-500 font-semibold text-sm sm:text-base">₹2.5M Investment</span>
                            <a href="#" class="text-green-500 hover:text-green-600 font-medium text-sm sm:text-base">Learn More →</a>
                        </div>
                    </div>
                </div>

                <!-- Community Recycling Hub -->
                <div class="project-card bg-white rounded-2xl shadow-lg overflow-hidden hover-lift">
                    <div class="overflow-hidden">
                        <div class="w-full h-48 rounded-t-xl overflow-hidden">
                            <img src="{{ asset('images/P2.jpeg') }}" 
                                alt="Community Recycling Hub" 
                                class="w-full h-full object-cover">
                        </div>
                    </div>
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center mb-2">
                            <span class="bg-blue-100 text-blue-600 px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium">Completed</span>
                            <span class="ml-2 text-gray-500 text-xs sm:text-sm">2023 - 2024</span>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-2 sm:mb-3">Community Recycling Hub</h3>
                        <p class="text-sm sm:text-base text-gray-600 mb-3 sm:mb-4">
                            Established 15 community recycling centers with educational programs, processing 500+ tons monthly.
                        </p>
                        <div class="flex items-center justify-between">
                            <span class="text-green-500 font-semibold text-sm sm:text-base">500+ Tons/Month</span>
                            <a href="#" class="text-green-500 hover:text-green-600 font-medium text-sm sm:text-base">View Results →</a>
                        </div>
                    </div>
                </div>

                <!-- Ocean Cleanup Initiative -->
                <div class="project-card bg-white rounded-2xl shadow-lg overflow-hidden hover-lift">
                    <div class="overflow-hidden">
                        <div class="w-full h-48 rounded-t-xl overflow-hidden">
                            <img src="{{ asset('images/P3.jpg') }}" 
                                alt="Ocean Cleanup Initiative" 
                                class="w-full h-full object-cover">
                        </div>
                    </div>
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center mb-2">
                            <span class="bg-green-100 text-green-600 px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium">Ongoing</span>
                            <span class="ml-2 text-gray-500 text-xs sm:text-sm">2024 - 2025</span>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-2 sm:mb-3">Ocean Cleanup Initiative</h3>
                        <p class="text-sm sm:text-base text-gray-600 mb-3 sm:mb-4">
                            Removing plastic waste from coastal areas and implementing prevention systems around Sri Lanka.
                        </p>
                        <div class="flex items-center justify-between">
                            <span class="text-green-500 font-semibold text-sm sm:text-base">50+ Tons Removed</span>
                            <a href="#" class="text-green-500 hover:text-green-600 font-medium text-sm sm:text-base">Join Mission →</a>
                        </div>
                    </div>
                </div>

                <!-- E-Waste Processing Plant -->
                <div class="project-card bg-white rounded-2xl shadow-lg overflow-hidden hover-lift">
                    <div class="overflow-hidden">
                        <div class="w-full h-48 rounded-t-xl overflow-hidden">
                            <img src="{{ asset('images/P4.jpg') }}" 
                                alt="E-Waste Processing Plant" 
                                class="w-full h-full object-cover">
                        </div>
                    </div>
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center mb-2">
                            <span class="bg-purple-100 text-purple-600 px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium">Planning</span>
                            <span class="ml-2 text-gray-500 text-xs sm:text-sm">2025 - 2027</span>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-2 sm:mb-3">E-Waste Processing Plant</h3>
                        <p class="text-sm sm:text-base text-gray-600 mb-3 sm:mb-4">
                            Building Sri Lanka's first comprehensive electronic waste processing facility.
                        </p>
                        <div class="flex items-center justify-between">
                            <span class="text-green-500 font-semibold text-sm sm:text-base">₹5M Investment</span>
                            <a href="#" class="text-green-500 hover:text-green-600 font-medium text-sm sm:text-base">Coming Soon →</a>
                        </div>
                    </div>
                </div>

                <!-- Composting Network -->
                <div class="project-card bg-white rounded-2xl shadow-lg overflow-hidden hover-lift">
                    <div class="overflow-hidden">
                        <div class="w-full h-48 rounded-t-xl overflow-hidden">
                            <img src="{{ asset('images/P5.jpg') }}" 
                                alt="Composting Network" 
                                class="w-full h-full object-cover">
                        </div>
                    </div>
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center mb-2">
                            <span class="bg-blue-100 text-blue-600 px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium">Completed</span>
                            <span class="ml-2 text-gray-500 text-xs sm:text-sm">2022 - 2024</span>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-2 sm:mb-3">Urban Composting Network</h3>
                        <p class="text-sm sm:text-base text-gray-600 mb-3 sm:mb-4">
                            Created 25 community composting centers converting 1000+ tons of organic waste into fertilizer.
                        </p>
                        <div class="flex items-center justify-between">
                            <span class="text-green-500 font-semibold text-sm sm:text-base">1000+ Tons Processed</span>
                            <a href="#" class="text-green-500 hover:text-green-600 font-medium text-sm sm:text-base">Success Story →</a>
                        </div>
                    </div>
                </div>

                <!-- Zero Waste Campus -->
                <div class="project-card bg-white rounded-2xl shadow-lg overflow-hidden hover-lift">
                    <div class="overflow-hidden">
                        <div class="w-full h-48 rounded-t-xl overflow-hidden">
                            <img src="{{ asset('images/P6.jpg') }}" 
                                alt="Zero Waste Campus Project" 
                                class="w-full h-full object-cover">
                        </div>
                    </div>
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center mb-2">
                            <span class="bg-green-100 text-green-600 px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium">Ongoing</span>
                            <span class="ml-2 text-gray-500 text-xs sm:text-sm">2024 - 2025</span>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-2 sm:mb-3">Zero Waste Campus Project</h3>
                        <p class="text-sm sm:text-base text-gray-600 mb-3 sm:mb-4">
                            Partnering with universities to achieve zero waste to landfill through comprehensive programs.
                        </p>
                        <div class="flex items-center justify-between">
                            <span class="text-green-500 font-semibold text-sm sm:text-base">10 Universities</span>
                            <a href="#" class="text-green-500 hover:text-green-600 font-medium text-sm sm:text-base">Partner With Us →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Project Statistics -->
    <section class="py-12 sm:py-16 lg:py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-8 sm:mb-12">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800 mb-3 sm:mb-4">Project Impact</h2>
                <p class="text-base sm:text-lg md:text-xl text-gray-600">Making a measurable difference in waste management and environmental protection.</p>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-8">
                <div class="text-center bg-white p-4 sm:p-8 rounded-2xl shadow-lg hover-lift">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                        <i class="fas fa-project-diagram text-green-500 text-lg sm:text-2xl"></i>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-1 sm:mb-2">25+</h3>
                    <p class="text-sm sm:text-base text-gray-600">Active Projects</p>
                </div>

                <div class="text-center bg-white p-4 sm:p-8 rounded-2xl shadow-lg hover-lift">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                        <i class="fas fa-recycle text-blue-500 text-lg sm:text-2xl"></i>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-1 sm:mb-2">2,500+</h3>
                    <p class="text-sm sm:text-base text-gray-600">Tons Recycled Monthly</p>
                </div>

                <div class="text-center bg-white p-4 sm:p-8 rounded-2xl shadow-lg hover-lift">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                        <i class="fas fa-users text-yellow-500 text-lg sm:text-2xl"></i>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-1 sm:mb-2">50,000+</h3>
                    <p class="text-sm sm:text-base text-gray-600">People Served</p>
                </div>

                <div class="text-center bg-white p-4 sm:p-8 rounded-2xl shadow-lg hover-lift">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                        <i class="fas fa-leaf text-purple-500 text-lg sm:text-2xl"></i>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-1 sm:mb-2">75%</h3>
                    <p class="text-sm sm:text-base text-gray-600">Waste Reduction</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-12 sm:py-16 lg:py-20 bg-green-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-4 sm:mb-6">
                Want to Partner With Us?
            </h2>
            <p class="text-base sm:text-lg md:text-xl text-green-100 mb-6 sm:mb-8 max-w-2xl mx-auto">
                Join our mission to create sustainable waste management solutions for a cleaner future.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('public.contact') }}" class="bg-white text-green-500 hover:bg-gray-100 px-6 sm:px-8 py-3 sm:py-4 rounded-lg font-semibold text-base sm:text-lg transition-all duration-300 transform hover:scale-105">
                    Discuss Partnership
                </a>
                <a href="{{ route('public.services') }}" class="border-2 border-white text-white hover:bg-white hover:text-green-500 px-6 sm:px-8 py-3 sm:py-4 rounded-lg font-semibold text-base sm:text-lg transition-all duration-300">
                    View Our Services
                </a>
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
                        <li><a href="{{ route('public.company') }}" class="text-gray-300 hover:text-green-300 transition-colors duration-300">About Us</a></li>
                        <li><a href="{{ route('public.company') }}" class="text-gray-300 hover:text-green-300 transition-colors duration-300">Our Team</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-green-300 transition-colors duration-300">Careers</a></li>
                        <li><a href="{{ route('public.blog') }}" class="text-gray-300 hover:text-green-300 transition-colors duration-300">Blog</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-green-300 transition-colors duration-300">News</a></li>
                    </ul>
                </div>

                <!-- Projects Links -->
                <div class="animate-on-scroll stagger-3">
                    <h4 class="text-xl font-bold text-green-300 mb-6">Projects</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-300 hover:text-green-300 transition-colors duration-300">Smart City Initiative</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-green-300 transition-colors duration-300">Ocean Cleanup</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-green-300 transition-colors duration-300">E-Waste Processing</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-green-300 transition-colors duration-300">Zero Waste Campus</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-green-300 transition-colors duration-300">Composting Network</a></li>
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

    <script>
        // Mobile menu functionality
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', function() {
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

        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const element = entry.target;
                    if (element.classList.contains('animate-on-scroll')) {
                        element.classList.add('animate-fade-in-up');
                    }
                    observer.unobserve(element);
                }
            });
        }, observerOptions);

        // Observe all elements with animate-on-scroll class
        document.addEventListener('DOMContentLoaded', () => {
            const animateElements = document.querySelectorAll('.animate-on-scroll');
            animateElements.forEach(el => observer.observe(el));
        });
    </script>
</body>
</html>
