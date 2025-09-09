<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Clean City</title>
    
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

        .contact-card {
            transition: all 0.3s ease;
        }

        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
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
                    <a href="{{ route('public.blog') }}" class="text-gray-700 hover:text-green-500 font-medium">Blog</a>
                    <a href="{{ route('public.contact') }}" class="text-green-500 font-medium">Contact</a>
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
                    <a href="{{ route('public.contact') }}" class="text-green-500 font-medium px-4 py-2">Contact</a>
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
                    Get in <span class="text-green-500">Touch</span>
                </h1>
                <p class="text-base sm:text-lg md:text-xl text-gray-600 mb-6 sm:mb-8">
                    Ready to start your journey towards sustainable waste management? 
                    We're here to help you every step of the way.
                </p>
            </div>
        </div>
    </section>

    <!-- Contact Information -->
    <section class="py-12 sm:py-16 lg:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8 mb-16">
                <!-- Phone -->
                <div class="contact-card bg-white p-6 sm:p-8 rounded-2xl shadow-lg text-center border hover-lift">
                    <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-phone text-green-500 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Call Us</h3>
                    <p class="text-gray-600 mb-4">Speak with our customer service team</p>
                    <a href="tel:+94814567890" class="text-green-500 hover:text-green-600 font-semibold text-lg">
                        +94 81 456 7890
                    </a>
                </div>

                <!-- Email -->
                <div class="contact-card bg-white p-6 sm:p-8 rounded-2xl shadow-lg text-center border hover-lift">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-envelope text-blue-500 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Email Us</h3>
                    <p class="text-gray-600 mb-4">Send us your questions anytime</p>
                    <a href="mailto:contact@cleancity@gmail.com" class="text-blue-500 hover:text-blue-600 font-semibold">
                        contact@cleancity@gmail.com
                    </a>
                </div>

                <!-- Address -->
                <div class="contact-card bg-white p-6 sm:p-8 rounded-2xl shadow-lg text-center border hover-lift">
                    <div class="w-16 h-16 bg-yellow-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-map-marker-alt text-yellow-500 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Visit Us</h3>
                    <p class="text-gray-600 mb-4">Come to our main office</p>
                    <p class="text-gray-800 font-semibold">
                        123 Galle Road<br>
                        Colombo 03, Sri Lanka
                    </p>
                </div>

                <!-- Hours -->
                <div class="contact-card bg-white p-6 sm:p-8 rounded-2xl shadow-lg text-center border hover-lift">
                    <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-clock text-purple-500 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Business Hours</h3>
                    <p class="text-gray-600 mb-4">We're here when you need us</p>
                    <p class="text-gray-800 font-semibold">
                        Mon - Fri: 08:00 - 18:00<br>
                        Sat: 09:00 - 15:00
                    </p>
                </div>
            </div>

            <!-- Contact Form and Map -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Contact Form -->
                <div class="bg-white p-6 sm:p-8 md:p-12 rounded-2xl shadow-lg border hover-lift">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">Send Us a Message</h2>
                    <p class="text-gray-600 mb-8">
                        Fill out the form below and we'll get back to you within 24 hours.
                    </p>

                    <form class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                                <input type="text" id="first_name" name="first_name" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-4 focus:ring-green-100 focus:border-green-500 transition-all duration-300">
                            </div>
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                                <input type="text" id="last_name" name="last_name" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-4 focus:ring-green-100 focus:border-green-500 transition-all duration-300">
                            </div>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input type="email" id="email" name="email" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-4 focus:ring-green-100 focus:border-green-500 transition-all duration-300">
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" id="phone" name="phone" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-4 focus:ring-green-100 focus:border-green-500 transition-all duration-300">
                        </div>

                        <div>
                            <label for="service" class="block text-sm font-medium text-gray-700 mb-2">Service Interested In</label>
                            <select id="service" name="service" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-4 focus:ring-green-100 focus:border-green-500 transition-all duration-300">
                                <option value="">Select a service</option>
                                <option value="residential">Residential Collection</option>
                                <option value="commercial">Commercial Services</option>
                                <option value="recycling">Recycling Programs</option>
                                <option value="industrial">Industrial Processing</option>
                                <option value="emergency">Emergency Cleanup</option>
                                <option value="composting">Composting Programs</option>
                            </select>
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                            <textarea id="message" name="message" rows="5" required 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-4 focus:ring-green-100 focus:border-green-500 transition-all duration-300"
                                      placeholder="Tell us about your waste management needs..."></textarea>
                        </div>

                        <div class="flex items-start">
                            <input type="checkbox" id="privacy" name="privacy" required 
                                   class="mt-1 mr-3 w-4 h-4 text-green-500 border-gray-300 rounded focus:ring-green-500">
                            <label for="privacy" class="text-sm text-gray-600">
                                I agree to the <a href="#" class="text-green-500 hover:text-green-600">Privacy Policy</a> 
                                and <a href="#" class="text-green-500 hover:text-green-600">Terms of Service</a>
                            </label>
                        </div>

                        <button type="submit" 
                                class="w-full bg-green-500 hover:bg-green-600 text-white px-8 py-4 rounded-lg font-semibold text-lg transition-all duration-300 transform hover:scale-105">
                            Send Message
                        </button>
                    </form>
                </div>

                <!-- Map and Additional Info -->
                <div class="space-y-8">
                    <!-- Map -->
                    <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-lg border hover-lift">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6">Find Our Office</h3>
                        <div class="w-full rounded-lg overflow-hidden">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15842.916202007658!2d79.84205745172083!3d6.922958039226882!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae259158f32b4a3%3A0x9502607ad9719a99!2sUnion%20Place%2C%20Colombo!5e0!3m2!1sen!2slk!4v1757424559799!5m2!1sen!2slk" 
                                    width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                        <div class="mt-6">
                            <a href="https://www.google.com/maps/place/Union+Place,+Colombo/@6.9229580,79.8420575,15z" target="_blank" 
                               class="inline-flex items-center bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-300">
                                <i class="fas fa-directions mr-2"></i>
                                Get Directions
                            </a>
                            </a>
                        </div>
                    </div>

                    <!-- Why Choose Us -->
                    <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-lg border hover-lift">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6">Why Choose Clean City?</h3>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-1">
                                    <i class="fas fa-check text-green-500 text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">24/7 Support</h4>
                                    <p class="text-gray-600 text-sm">Round-the-clock customer service for all your needs</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-1">
                                    <i class="fas fa-check text-green-500 text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Eco-Friendly</h4>
                                    <p class="text-gray-600 text-sm">Sustainable practices and environmental responsibility</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-1">
                                    <i class="fas fa-check text-green-500 text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Reliable Service</h4>
                                    <p class="text-gray-600 text-sm">Consistent, on-time pickup and processing services</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-1">
                                    <i class="fas fa-check text-green-500 text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Competitive Pricing</h4>
                                    <p class="text-gray-600 text-sm">Affordable solutions without compromising quality</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-12 sm:py-16 lg:py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                    Frequently Asked Questions
                </h2>
                <p class="text-base sm:text-lg md:text-xl text-gray-600">
                    Quick answers to common questions about our services
                </p>
            </div>

            <div class="max-w-3xl mx-auto space-y-6">
                <div class="bg-white p-6 rounded-2xl shadow-lg border hover-lift">
                    <h3 class="text-lg font-bold text-gray-800 mb-3">How often do you collect waste?</h3>
                    <p class="text-gray-600">We offer flexible collection schedules including daily, weekly, and bi-weekly options based on your needs and waste volume.</p>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-lg border hover-lift">
                    <h3 class="text-lg font-bold text-gray-800 mb-3">What types of waste do you accept?</h3>
                    <p class="text-gray-600">We handle all types of waste including residential, commercial, recyclable materials, organic waste, and industrial waste with proper permits.</p>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-lg border hover-lift">
                    <h3 class="text-lg font-bold text-gray-800 mb-3">How can I schedule a pickup?</h3>
                    <p class="text-gray-600">You can schedule pickups through our website, mobile app, or by calling our customer service team at +94 81 456 7890.</p>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-lg border hover-lift">
                    <h3 class="text-lg font-bold text-gray-800 mb-3">Do you offer emergency cleanup services?</h3>
                    <p class="text-gray-600">Yes, we provide 24/7 emergency cleanup services for spills, accidents, and urgent waste removal situations.</p>
                </div>
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
