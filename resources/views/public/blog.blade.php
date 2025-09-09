<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - Clean City</title>
    
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

        .animate-fade-up {
            animation: fadeInUp 0.8s ease-out;
        }

        .hover-lift {
            transition: all 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
        }

        .blog-card {
            transition: all 0.3s ease;
        }

        .blog-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .blog-image {
            height: 200px;
            transition: transform 0.3s ease;
        }

        .blog-card:hover .blog-image {
            transform: scale(1.05);
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
<body class="bg-gray-50">
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
                    <a href="{{ route('public.projects') }}" class="text-gray-700 hover:text-green-500 font-medium">Projects</a>
                    <a href="{{ route('public.company') }}" class="text-gray-700 hover:text-green-500 font-medium">Company</a>
                    <a href="{{ route('public.blog') }}" class="text-green-500 font-medium">Blog</a>
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
                    <a href="{{ route('public.blog') }}" class="text-green-500 font-medium px-4 py-2">Blog</a>
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
                    Clean City <span class="text-green-500">Blog</span>
                </h1>
                <p class="text-base sm:text-lg md:text-xl text-gray-600 mb-6 sm:mb-8">
                    Stay updated with the latest insights, tips, and news from the world of 
                    sustainable waste management and environmental conservation.
                </p>
            </div>
        </div>
    </section>

    <!-- Featured Article -->
    <section class="py-12 sm:py-16 lg:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="max-w-4xl mx-auto mb-12 sm:mb-16">
                <div class="bg-gradient-to-r from-green-500 to-blue-600 rounded-2xl overflow-hidden">
                    <div class="p-6 sm:p-8 md:p-12 text-white">
                        <span class="bg-yellow-400 text-gray-800 px-3 py-1 rounded-full text-sm font-medium">Featured Article</span>
                        <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold mt-4 mb-4">
                            The Future of Smart Waste Management in Sri Lanka
                        </h2>
                        <p class="text-lg text-green-100 mb-6">
                            Discover how IoT technology and AI are revolutionizing waste collection and processing, 
                            making our cities cleaner and more efficient than ever before.
                        </p>
                        <div class="flex items-center mb-6">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-green-500"></i>
                                </div>
                                <div>
                                    <p class="font-semibold">Dr. Priya Jayawardena</p>
                                    <p class="text-green-100 text-sm">Chief Technology Officer</p>
                                </div>
                            </div>
                            <span class="ml-auto text-green-100">September 8, 2025</span>
                        </div>
                        <a href="#" class="bg-white text-green-500 hover:bg-gray-100 px-6 py-3 rounded-lg font-semibold transition-all duration-300 inline-flex items-center">
                            Read Full Article <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Blog Categories -->
            <div class="text-center mb-12">
                <div class="flex flex-wrap gap-4 justify-center">
                    <button class="bg-green-500 text-white px-6 py-2 rounded-full font-medium">All Posts</button>
                    <button class="bg-white text-gray-700 hover:bg-green-500 hover:text-white px-6 py-2 rounded-full font-medium transition-all duration-300">Sustainability</button>
                    <button class="bg-white text-gray-700 hover:bg-green-500 hover:text-white px-6 py-2 rounded-full font-medium transition-all duration-300">Technology</button>
                    <button class="bg-white text-gray-700 hover:bg-green-500 hover:text-white px-6 py-2 rounded-full font-medium transition-all duration-300">Community</button>
                    <button class="bg-white text-gray-700 hover:bg-green-500 hover:text-white px-6 py-2 rounded-full font-medium transition-all duration-300">Tips & Guides</button>
                    <button class="bg-white text-gray-700 hover:bg-green-500 hover:text-white px-6 py-2 rounded-full font-medium transition-all duration-300">News</button>
                </div>
            </div>

            <!-- Blog Posts Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                <!-- Blog Post 1 -->
                <article class="blog-card bg-white p-6 sm:p-8 rounded-2xl shadow-lg border hover-lift">
                    <div class="overflow-hidden rounded-xl mb-6">
                        <div class="w-full h-48 rounded-t-xl overflow-hidden">
                            <img src="{{ asset('images/1.jpg') }}" 
                                alt="Recycling and Sustainability" 
                                class="w-full h-full object-cover">
                        </div>
                    </div>
                    <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-sm font-medium">Sustainability</span>
                    <h3 class="text-xl font-bold text-gray-800 mt-3 mb-3">10 Simple Ways to Reduce Household Waste</h3>
                    <p class="text-gray-600 mb-4">
                        Practical tips for families to minimize waste generation and maximize recycling efficiency at home.
                    </p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-2">
                                <i class="fas fa-user text-green-500 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Niluka Mendis</p>
                                <p class="text-xs text-gray-500">Sept 7, 2025</p>
                            </div>
                        </div>
                        <a href="#" class="text-green-500 hover:text-green-600 font-medium">Read More →</a>
                    </div>
                </article>

                <!-- Blog Post 2 -->
                <article class="blog-card bg-white p-6 sm:p-8 rounded-2xl shadow-lg border hover-lift">
                    <div class="overflow-hidden rounded-xl mb-6">
                        <div class="w-full h-48 bg-gradient-to-br from-blue-400 to-blue-600 rounded-t-xl overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&h=300&fit=crop&auto=format" 
                                alt="Smart Waste Technology" 
                                class="w-full h-full object-cover">
                        </div>
                    </div>
                    <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-sm font-medium">Technology</span>
                    <h3 class="text-xl font-bold text-gray-800 mt-3 mb-3">How Smart Bins Are Revolutionizing Cities</h3>
                    <p class="text-gray-600 mb-4">
                        Exploring the IoT technology behind our smart waste collection systems and their environmental impact.
                    </p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-2">
                                <i class="fas fa-user text-blue-500 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Tharaka Rathnayake</p>
                                <p class="text-xs text-gray-500">Sept 6, 2025</p>
                            </div>
                        </div>
                        <a href="#" class="text-green-500 hover:text-green-600 font-medium">Read More →</a>
                    </div>
                </article>

                <!-- Blog Post 3 -->
                <article class="blog-card bg-white p-6 sm:p-8 rounded-2xl shadow-lg border hover-lift">
                    <div class="overflow-hidden rounded-xl mb-6">
                        <div class="w-full h-48 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-t-xl overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?w=400&h=300&fit=crop&auto=format" 
                                alt="Composting Guide" 
                                class="w-full h-full object-cover">
                        </div>
                    </div>
                    <span class="bg-yellow-100 text-yellow-600 px-3 py-1 rounded-full text-sm font-medium">Tips & Guides</span>
                    <h3 class="text-xl font-bold text-gray-800 mt-3 mb-3">Complete Guide to Home Composting</h3>
                    <p class="text-gray-600 mb-4">
                        Step-by-step instructions for creating nutrient-rich compost from your kitchen and garden waste.
                    </p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center mr-2">
                                <i class="fas fa-user text-yellow-500 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Dr. Kumara Fernando</p>
                                <p class="text-xs text-gray-500">Sept 5, 2025</p>
                            </div>
                        </div>
                        <a href="#" class="text-green-500 hover:text-green-600 font-medium">Read More →</a>
                    </div>
                </article>

                <!-- Blog Post 4 -->
                <article class="blog-card bg-white p-6 sm:p-8 rounded-2xl shadow-lg border hover-lift">
                    <div class="overflow-hidden rounded-xl mb-6">
                       <div class="w-full h-48 rounded-t-xl overflow-hidden">
                            <img src="{{ asset('images/2.jpg') }}" 
                                alt="Community-Waste-Management" 
                                class="w-full h-full object-cover">
                        </div>
                    </div>
                    <span class="bg-purple-100 text-purple-600 px-3 py-1 rounded-full text-sm font-medium">Community</span>
                    <h3 class="text-xl font-bold text-gray-800 mt-3 mb-3">Community Recycling Success Stories</h3>
                    <p class="text-gray-600 mb-4">
                        How local communities are making a difference through collaborative waste reduction initiatives.
                    </p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-2">
                                <i class="fas fa-user text-purple-500 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Samantha Silva</p>
                                <p class="text-xs text-gray-500">Sept 4, 2025</p>
                            </div>
                        </div>
                        <a href="#" class="text-green-500 hover:text-green-600 font-medium">Read More →</a>
                    </div>
                </article>

                <!-- Blog Post 5 -->
                <article class="blog-card bg-white p-6 sm:p-8 rounded-2xl shadow-lg border hover-lift">
                    <div class="overflow-hidden rounded-xl mb-6">
                        <div class="w-full h-48 rounded-t-xl overflow-hidden">
                            <img src="{{ asset('images/3.jpg') }}" 
                                alt="Revolution" 
                                class="w-full h-full object-cover">
                        </div>
                    </div>
                    <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-sm font-medium">News</span>
                    <h3 class="text-xl font-bold text-gray-800 mt-3 mb-3">Sri Lanka's Waste Management Revolution</h3>
                    <p class="text-gray-600 mb-4">
                        Latest developments in national waste management policies and their impact on local communities.
                    </p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-2">
                                <i class="fas fa-user text-red-500 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Rajesh Perera</p>
                                <p class="text-xs text-gray-500">Sept 3, 2025</p>
                            </div>
                        </div>
                        <a href="#" class="text-green-500 hover:text-green-600 font-medium">Read More →</a>
                    </div>
                </article>

                <!-- Blog Post 6 -->
                <article class="blog-card bg-white p-6 sm:p-8 rounded-2xl shadow-lg border hover-lift">
                    <div class="overflow-hidden rounded-xl mb-6">
                        <div class="w-full h-48 rounded-t-xl overflow-hidden">
                            <img src="{{ asset('images/4.jpg') }}" 
                                alt="Oiffce-Waste" 
                                class="w-full h-full object-cover">
                        </div>
                    </div>
                    <span class="bg-teal-100 text-teal-600 px-3 py-1 rounded-full text-sm font-medium">Tips & Guides</span>
                    <h3 class="text-xl font-bold text-gray-800 mt-3 mb-3">Office Waste Reduction Strategies</h3>
                    <p class="text-gray-600 mb-4">
                        Practical approaches for businesses to minimize waste and create more sustainable workplaces.
                    </p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-teal-100 rounded-full flex items-center justify-center mr-2">
                                <i class="fas fa-user text-teal-500 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Priya Jayawardena</p>
                                <p class="text-xs text-gray-500">Sept 2, 2025</p>
                            </div>
                        </div>
                        <a href="#" class="text-green-500 hover:text-green-600 font-medium">Read More →</a>
                    </div>
                </article>
            </div>

            <!-- Load More Button -->
            <div class="text-center mt-12">
                <button class="bg-green-500 hover:bg-green-600 text-white px-8 py-3 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">
                    Load More Articles
                </button>
            </div>
        </div>
    </section>

    

    <!-- Footer -->
    <footer class="bg-green-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid md:grid-cols-2 lg:grid-cols-5 gap-8">
                <!-- Company Info -->
                <div class="lg:col-span-2">
                    <div class="flex items-center mb-6">
                        <img src="{{ asset('images/logo.png') }}" alt="Clean City Logo" class="h-10 w-10 mr-3">
                        <div>
                            <h3 class="text-xl font-bold">Clean City</h3>
                            <p class="text-green-400 text-sm">Your Waste, Our Responsibility</p>
                        </div>
                    </div>
                    <p class="text-gray-300 mb-6 max-w-md">
                        We are committed to providing dependable, eco-friendly waste management solutions tailored to the diverse needs of homes, businesses, and industries.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-green-800 rounded-full flex items-center justify-center hover:bg-green-700 transition-colors">
                            <i class="fab fa-facebook text-white"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-green-800 rounded-full flex items-center justify-center hover:bg-green-700 transition-colors">
                            <i class="fab fa-twitter text-white"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-green-800 rounded-full flex items-center justify-center hover:bg-green-700 transition-colors">
                            <i class="fab fa-linkedin text-white"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-green-800 rounded-full flex items-center justify-center hover:bg-green-700 transition-colors">
                            <i class="fab fa-instagram text-white"></i>
                        </a>
                    </div>
                </div>

                <!-- Company Links -->
                <div>
                    <h4 class="text-lg font-semibold mb-6">Company</h4>
                    <ul class="space-y-3">
                        <li><a href="{{ route('public.company') }}" class="text-gray-300 hover:text-white transition-colors">About Us</a></li>
                        <li><a href="{{ route('public.services') }}" class="text-gray-300 hover:text-white transition-colors">Services</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Careers</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Blog</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">News & Press</a></li>
                    </ul>
                </div>

                <!-- Projects Links -->
                <div>
                    <h4 class="text-lg font-semibold mb-6">Projects</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Smart City Initiative</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Ocean Cleanup</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">E-Waste Processing</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Zero Waste Campus</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Composting Network</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h4 class="text-lg font-semibold mb-6">Contact Info</h4>
                    <ul class="space-y-3">
                        <li class="flex items-center">
                            <i class="fas fa-phone mr-3 text-green-400"></i>
                            <span class="text-gray-300">+94 81 456 7890</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-3 text-green-400"></i>
                            <span class="text-gray-300">contact@cleancity@gmail.com</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mr-3 text-green-400 mt-1"></i>
                            <span class="text-gray-300">123 Green Street, Colombo, 12345</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Newsletter Section -->
            <div class="border-t border-green-800 pt-12 mt-12">
                <div class="text-center mb-8">
                    <h3 class="text-2xl font-bold mb-2">Subscribe to Newsletter</h3>
                    <p class="text-gray-400">Stay updated with our latest news and offers</p>
                </div>
                <div class="max-w-md mx-auto">
                    <div class="flex gap-2">
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
