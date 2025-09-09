<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Our Company - Clean City</title>
    
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

        .team-card {
            transition: all 0.3s ease;
        }

        .team-card:hover {
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
                    <a href="{{ route('public.projects') }}" class="text-gray-700 hover:text-green-500 font-medium">Projects</a>
                    <a href="{{ route('public.company') }}" class="text-green-500 font-medium">Company</a>
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
                    <a href="{{ route('public.company') }}" class="text-green-500 font-medium px-4 py-2">Company</a>
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
    <section class="bg-gray-50 py-20">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <div class="animate-fade-up">
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-gray-800 mb-6">
                    About <span class="text-green-500">Clean City</span>
                </h1>
                <p class="text-base sm:text-lg md:text-xl text-gray-600 max-w-3xl mx-auto">
                    Leading the way in sustainable waste management solutions since 2020. Building a cleaner, greener future for communities across Sri Lanka and beyond.
                </p>
            </div>
        </div>
    </section>

    <!-- Company Story -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800 mb-6">Our Story</h2>
                    <p class="text-sm sm:text-base md:text-lg text-gray-600 mb-6">
                        Founded in 2020 with a simple yet powerful vision: to revolutionize waste management 
                        through innovation, sustainability, and community engagement. What started as a small 
                        local initiative has grown into Sri Lanka's leading waste management company.
                    </p>
                    <p class="text-sm sm:text-base md:text-lg text-gray-600 mb-6">
                        Our journey began when our founders witnessed the growing environmental challenges 
                        facing urban communities. We realized that traditional waste management approaches 
                        were no longer sufficient to address the scale and complexity of modern waste streams.
                    </p>
                    <p class="text-sm sm:text-base md:text-lg text-gray-600 mb-8">
                        Today, we serve over 50,000 customers across Sri Lanka, process more than 2,500 tons 
                        of waste monthly, and operate 25+ innovative projects that are setting new standards 
                        for environmental sustainability.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('public.services') }}" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-semibold text-center transition-all duration-300">
                            Our Services
                        </a>
                        <a href="{{ route('public.projects') }}" class="border-2 border-green-500 text-green-500 hover:bg-green-500 hover:text-white px-6 py-3 rounded-lg font-semibold text-center transition-all duration-300">
                            View Projects
                        </a>
                    </div>
                </div>
                <div class="relative">
                    <div class="w-full h-96 bg-gradient-to-br from-green-400 to-blue-500 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-leaf text-white text-8xl"></i>
                    </div>
                    <div class="absolute -bottom-6 -right-6 w-32 h-32 bg-yellow-400 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-recycle text-white text-4xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission, Vision, Values -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800 mb-4">Our Foundation</h2>
                <p class="text-base sm:text-lg md:text-xl text-gray-600">The principles that guide everything we do</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Mission -->
                <div class="bg-white p-8 rounded-2xl shadow-lg text-center hover-lift">
                    <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-bullseye text-green-500 text-2xl"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4">Our Mission</h3>
                    <p class="text-gray-600">
                        To provide innovative, sustainable waste management solutions that protect the environment, 
                        serve communities, and create a cleaner future for generations to come.
                    </p>
                </div>

                <!-- Vision -->
                <div class="bg-white p-8 rounded-2xl shadow-lg text-center hover-lift">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-eye text-blue-500 text-2xl"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4">Our Vision</h3>
                    <p class="text-gray-600">
                        To become the leading force in transforming waste from a problem into a resource, 
                        creating a circular economy that benefits both people and the planet.
                    </p>
                </div>

                <!-- Values -->
                <div class="bg-white p-8 rounded-2xl shadow-lg text-center hover-lift">
                    <div class="w-16 h-16 bg-yellow-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-heart text-yellow-500 text-2xl"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4">Our Values</h3>
                    <p class="text-gray-600">
                        Sustainability, Innovation, Integrity, Community Focus, and Environmental Responsibility 
                        drive our decisions and shape our culture every day.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Leadership Team -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800 mb-4">Leadership Team</h2>
                <p class="text-base sm:text-lg md:text-xl text-gray-600">Meet the visionaries driving our mission forward</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- CEO -->
                <div class="team-card bg-white p-8 rounded-2xl shadow-lg text-center">
                    <div class="w-24 h-24 bg-gradient-to-r from-green-400 to-blue-500 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-user text-white text-3xl"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-2">Rajesh Perera</h3>
                    <p class="text-green-500 font-semibold mb-4">Chief Executive Officer</p>
                    <p class="text-gray-600 mb-4">
                        15+ years in environmental management. Led sustainable initiatives across multiple 
                        industries before founding Clean City.
                    </p>
                    <div class="flex justify-center space-x-3">
                        <a href="#" class="text-gray-400 hover:text-blue-500"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="text-gray-400 hover:text-blue-400"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>

                <!-- CTO -->
                <div class="team-card bg-white p-8 rounded-2xl shadow-lg text-center">
                    <div class="w-24 h-24 bg-gradient-to-r from-purple-400 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-user text-white text-3xl"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-2">Priya Jayawardena</h3>
                    <p class="text-purple-500 font-semibold mb-4">Chief Technology Officer</p>
                    <p class="text-sm sm:text-base text-gray-600 mb-4">
                        Technology innovator specializing in IoT and smart city solutions. Driving our 
                        digital transformation initiatives.
                    </p>
                    <div class="flex justify-center space-x-3">
                        <a href="#" class="text-gray-400 hover:text-blue-500"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="text-gray-400 hover:text-gray-600"><i class="fab fa-github"></i></a>
                    </div>
                </div>

                <!-- COO -->
                <div class="team-card bg-white p-8 rounded-2xl shadow-lg text-center">
                    <div class="w-24 h-24 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-user text-white text-3xl"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-2">Samantha Silva</h3>
                    <p class="text-yellow-500 font-semibold mb-4">Chief Operations Officer</p>
                    <p class="text-sm sm:text-base text-gray-600 mb-4">
                        Operations expert with 12+ years optimizing logistics and supply chains. 
                        Ensures efficient service delivery across all locations.
                    </p>
                    <div class="flex justify-center space-x-3">
                        <a href="#" class="text-gray-400 hover:text-blue-500"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="text-gray-400 hover:text-blue-400"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>

                <!-- Head of Sustainability -->
                <div class="team-card bg-white p-8 rounded-2xl shadow-lg text-center">
                    <div class="w-24 h-24 bg-gradient-to-r from-green-400 to-emerald-500 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-user text-white text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Dr. Kumara Fernando</h3>
                    <p class="text-green-500 font-semibold mb-4">Head of Sustainability</p>
                    <p class="text-gray-600 mb-4">
                        Environmental scientist and sustainability consultant. Leads our research and 
                        development of eco-friendly waste solutions.
                    </p>
                    <div class="flex justify-center space-x-3">
                        <a href="#" class="text-gray-400 hover:text-blue-500"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="text-gray-400 hover:text-green-500"><i class="fas fa-globe"></i></a>
                    </div>
                </div>

                <!-- Head of Customer Success -->
                <div class="team-card bg-white p-8 rounded-2xl shadow-lg text-center">
                    <div class="w-24 h-24 bg-gradient-to-r from-blue-400 to-cyan-500 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-user text-white text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Niluka Mendis</h3>
                    <p class="text-blue-500 font-semibold mb-4">Head of Customer Success</p>
                    <p class="text-gray-600 mb-4">
                        Customer experience specialist ensuring exceptional service delivery and 
                        building lasting relationships with our community partners.
                    </p>
                    <div class="flex justify-center space-x-3">
                        <a href="#" class="text-gray-400 hover:text-blue-500"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="text-gray-400 hover:text-blue-400"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>

                <!-- Head of Operations -->
                <div class="team-card bg-white p-8 rounded-2xl shadow-lg text-center">
                    <div class="w-24 h-24 bg-gradient-to-r from-indigo-400 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-user text-white text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Tharaka Rathnayake</h3>
                    <p class="text-indigo-500 font-semibold mb-4">Head of Field Operations</p>
                    <p class="text-gray-600 mb-4">
                        Field operations manager coordinating our collection teams and ensuring 
                        efficient waste pickup and processing across all service areas.
                    </p>
                    <div class="flex justify-center space-x-3">
                        <a href="#" class="text-gray-400 hover:text-blue-500"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="text-gray-400 hover:text-indigo-500"><i class="fas fa-envelope"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Company Statistics -->
    <section class="py-20 bg-green-500">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-4">Our Impact in Numbers</h2>
                <p class="text-base sm:text-lg md:text-xl text-green-100">Making a measurable difference every day</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <h3 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-2">50,000+</h3>
                    <p class="text-green-100 text-sm sm:text-base">Customers Served</p>
                </div>

                <div class="text-center">
                    <h3 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-2">2,500+</h3>
                    <p class="text-green-100 text-sm sm:text-base">Tons Processed Monthly</p>
                </div>

                <div class="text-center">
                    <h3 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-2">25+</h3>
                    <p class="text-green-100 text-sm sm:text-base">Active Projects</p>
                </div>

                <div class="text-center">
                    <h3 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-2">75%</h3>
                    <p class="text-green-100 text-sm sm:text-base">Waste Diverted from Landfills</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800 mb-6">
                Join Our Mission
            </h2>
            <p class="text-base sm:text-lg md:text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                Be part of the solution. Whether you're a resident, business, or organization, 
                we have sustainable waste management solutions for you.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('public.contact') }}" class="bg-green-500 hover:bg-green-600 text-white px-8 py-4 rounded-lg font-semibold text-lg transition-all duration-300 transform hover:scale-105">
                    Get Started Today
                </a>
                <a href="{{ route('public.services') }}" class="border-2 border-green-500 text-green-500 hover:bg-green-500 hover:text-white px-8 py-4 rounded-lg font-semibold text-lg transition-all duration-300">
                    Explore Services
                </a>
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
