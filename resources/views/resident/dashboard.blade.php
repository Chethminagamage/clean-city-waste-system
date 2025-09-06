<!DOCTYPE html>
<html lang="en" class="scroll-smooth {{ auth()->check() && auth()->user()->theme_preference === 'dark' ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Resident Dashboard | Clean City</title>
    
    <!-- Theme Persistence Script - MUST BE FIRST -->
    <script>
        // Apply theme immediately to prevent flash
        (function() {
            // Get server-side theme preference
            const serverTheme = '{{ auth()->check() ? (auth()->user()->theme_preference ?? "light") : "light" }}';
            const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
            
            let shouldApplyDark = false;
            
            if (isAuthenticated) {
                // Use server theme for authenticated users
                if (serverTheme === 'dark') {
                    shouldApplyDark = true;
                }
            } else {
                // Use localStorage for guests
                const localTheme = localStorage.getItem('theme');
                if (localTheme === 'dark') {
                    shouldApplyDark = true;
                }
            }
            
            // Apply theme class immediately to HTML element
            const htmlElement = document.documentElement;
            if (shouldApplyDark) {
                htmlElement.classList.add('dark');
            } else {
                htmlElement.classList.remove('dark');
            }
            
            // Store in localStorage for consistency
            localStorage.setItem('theme', serverTheme);
            
            // Add a flag to indicate theme was initialized
            window.themeInitialized = true;
            window.currentTheme = shouldApplyDark ? 'dark' : 'light';
        })();
    </script>
    
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDgyETTNM7hQ-P9BETdNwTbMr6ggGr73oY&callback=initMap" async defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0fdf4',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        .card-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .stat-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateY(0);
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px -8px rgba(0, 0, 0, 0.15), 0 8px 16px -4px rgba(0, 0, 0, 0.1);
        }
        
        html {
            scroll-behavior: smooth;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .profile-icon {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            transform: scale(1);
        }
        
        .profile-icon:hover {
            transform: scale(1.08);
            box-shadow: 0 8px 24px rgba(34, 197, 94, 0.4);
        }
        
        /* Smooth transitions for theme changes only */
        .theme-transition {
            transition: color 0.3s ease, background-color 0.3s ease, border-color 0.3s ease;
        }
        
        /* Button hover effects */
        .btn-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .btn-hover:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        /* Navigation link hover effects */
        .nav-link {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }
        
        .nav-link:hover {
            transform: translateY(-1px);
        }
        
        /* Card hover effects */
        .card-hover {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateY(0);
        }
        
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }
        
        .welcome-card {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 50%, #bbf7d0 100%);
            border: 1px solid #86efac;
        }

        /* Enhanced responsive styles */
        @media (max-width: 640px) {
            .grid {
                grid-template-columns: 1fr;
            }
            
            .lg\\:grid-cols-3 {
                grid-template-columns: 1fr;
            }
            
            .lg\\:col-span-2 {
                grid-column: span 1;
            }
            
            .md\\:grid-cols-3 {
                grid-template-columns: 1fr;
            }
            
            .px-6 {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            
            .py-8 {
                padding-top: 1.5rem;
                padding-bottom: 1.5rem;
            }
            
            .text-xl {
                font-size: 1.125rem;
            }
            
            .text-2xl {
                font-size: 1.25rem;
            }
            
            .gap-6 {
                gap: 1rem;
            }
            
            .p-6 {
                padding: 1rem;
            }
            
            .px-4 {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }
            
            .py-4 {
                padding-top: 0.75rem;
                padding-bottom: 0.75rem;
            }
        }

        @media (max-width: 768px) {
            .max-w-7xl {
                max-width: 100%;
            }
            
            .overflow-x-auto table {
                min-width: 600px;
            }
            
            .hidden.sm\\:block {
                display: none !important;
            }
            
            .hidden.sm\\:flex {
                display: none !important;
            }
            
            .grid.md\\:grid-cols-3 {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }

        @media (max-width: 1024px) {
            .lg\\:grid-cols-3 {
                grid-template-columns: 1fr;
            }
            
            .lg\\:col-span-2 {
                grid-column: span 1;
            }
            
            .hidden.lg\\:flex {
                display: none !important;
            }
        }

        /* Top contact bar responsive - HIDE ON MOBILE */
        @media (max-width: 768px) {
            .top-contact-bar {
                display: none !important;
            }
        }
        
        /* Mobile table optimization */
        @media (max-width: 768px) {
            .desktop-table {
                display: none;
            }
            
            .mobile-reports {
                display: block;
            }
        }
        
        @media (min-width: 769px) {
            .desktop-table {
                display: block;
            }
            
            .mobile-reports {
                display: none;
            }
        }
        
        /* Mobile report cards styling */
        .mobile-report-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border: 1px solid rgba(229, 231, 235, 0.8);
        }
        
        .mobile-report-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        }
        
        .dark .mobile-report-card {
            background: rgb(31, 41, 55);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(75, 85, 99, 0.6);
        }
        
        .dark .mobile-report-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.6);
        }
        
        /* Mobile text utilities */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        /* Enhanced dark mode for mobile cards */
        .dark .mobile-report-card .border-gray-100 {
            border-color: rgba(75, 85, 99, 0.6) !important;
        }
        
        .dark .mobile-report-card .border-gray-200 {
            border-color: rgba(75, 85, 99, 0.8) !important;
        }
        
        /* Mobile status badges enhanced contrast */
        .dark .mobile-report-card .bg-yellow-100 {
            background-color: rgba(251, 191, 36, 0.2) !important;
        }
        
        .dark .mobile-report-card .bg-blue-100 {
            background-color: rgba(59, 130, 246, 0.2) !important;
        }
        
        .dark .mobile-report-card .bg-purple-100 {
            background-color: rgba(147, 51, 234, 0.2) !important;
        }
        
        .dark .mobile-report-card .bg-green-100 {
            background-color: rgba(34, 197, 94, 0.2) !important;
        }
        
        .dark .mobile-report-card .bg-red-100 {
            background-color: rgba(239, 68, 68, 0.2) !important;
        }
        
        /* Mobile padding adjustments */
        @media (max-width: 640px) {
            .mobile-reports .mobile-report-card {
                margin-bottom: 0.75rem;
            }
            
            .mobile-reports .mobile-report-card .p-4 {
                padding: 0.75rem;
            }
            
            /* Enhanced mobile spacing for better readability */
            .mobile-reports {
                padding: 0.5rem;
                background: transparent;
            }
            
            /* Mobile container background enhancement */
            main {
                background: transparent;
            }
        }

        /* Google Maps styling - prevent dark theme interference */
        #map, 
        #map * {
            filter: none !important;
            background-color: transparent !important;
        }
        
        /* Ensure Google Maps controls remain visible in both themes */
        .gm-style .gmnoprint,
        .gm-style .gm-bundled-control,
        .gm-style .gm-bundled-control-on-bottom {
            filter: none !important;
            background-color: white !important;
            color: black !important;
        }
        
        /* Map container specific styling */
        .map-container {
            background: transparent !important;
            filter: none !important;
        }
        
        /* Override any dark mode effects on map */
        .dark #map,
        .dark #map *,
        .dark .map-container {
            filter: none !important;
            background-color: transparent !important;
        }
    </style>
    
    <script>
        tailwind.config = {
            darkMode: 'class',
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
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">
</head>

<body class="bg-gradient-to-br from-gray-50 to-green-50 dark:from-gray-900 dark:to-gray-800 min-h-screen transition-colors duration-300">
    <!-- Top Contact Bar -->
    <div class="bg-green-700 dark:bg-green-800 text-white py-2 text-sm top-contact-bar transition-colors duration-300">
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
                <a href="#" class="hover:text-green-300 dark:hover:text-green-200 transition-colors"><i class="fab fa-facebook"></i></a>
                <a href="#" class="hover:text-green-300 dark:hover:text-green-200 transition-colors"><i class="fab fa-twitter"></i></a>
                <a href="#" class="hover:text-green-300 dark:hover:text-green-200 transition-colors"><i class="fab fa-youtube"></i></a>
                <a href="#" class="hover:text-green-300 dark:hover:text-green-200 transition-colors"><i class="fab fa-pinterest"></i></a>
                <a href="#" class="hover:text-green-300 dark:hover:text-green-200 transition-colors"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </div>

    <!-- Header/Navigation -->
    <header class="glass-effect sticky top-0 z-50 shadow-lg bg-white/90 dark:bg-gray-800/90 backdrop-blur-md transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="relative">
                <div class="flex justify-between items-center py-3 sm:py-4">
                <!-- Logo -->
                <a href="{{ route('resident.dashboard') }}" class="flex items-center hover:opacity-80 transition-opacity duration-200">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-8 h-8 sm:w-10 sm:h-10 object-contain mr-2 sm:mr-3">
                    <div>
                        <span class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white">Clean City</span>
                        <p class="text-xs sm:text-sm text-green-600 dark:text-green-400">Your Waste, Our Responsibility</p>
                    </div>
                </a>

                <!-- Navigation -->
                <nav class="hidden lg:flex items-center space-x-8">
                    <div class="relative group">
                        <a href="#home" class="nav-link text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-medium flex items-center">
                            Home 
                        </a>
                    </div>
                    <div class="relative group">
                        <a href="#submit-report" class="nav-link text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-medium flex items-center">
                            Submit Reports 
                        </a>
                    </div>
                    <div class="relative group">
                        <a href="{{ route('resident.reports.index') }}" 
                        class="nav-link text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-medium flex items-center">
                            Report History
                        </a>
                    </div>
                    <div class="relative group">
                        <a href="{{ route('resident.schedule.index') }}"
                        class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-medium flex items-center transition-colors">
                            Collection Schedule
                        </a>
                    </div>
                    <div class="relative group">
                        <a href="{{ route('resident.gamification.index') }}"
                        class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-medium flex items-center transition-colors">
                            
                            Eco Points
                        </a>
                    </div>
                    <div class="relative group">
                    <a href="{{ route('resident.feedback.index') }}"
                    class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-medium flex items-center transition-colors">
                        My Feedback
                        @auth
                        @php 
                            $newResponses = auth()->user()->notifications()
                                ->where('data->type', 'feedback_response')
                                ->whereNull('read_at')
                                ->count(); 
                        @endphp
                        @if($newResponses)
                            <span class="ml-1 bg-green-600 text-white text-xs rounded-full px-1.5 py-0.5">{{ $newResponses }}</span>
                        @endif
                        @endauth
                    </a>
                </div>
                </nav>

                <!-- User Info & Profile -->
                <div class="hidden sm:flex items-center space-x-4">
                    <!-- Notification Bell -->
                    @auth
                    @php $unread = auth()->user()->unreadNotifications()->count(); @endphp
                    <a href="{{ route('notifications.index') }}" class="relative inline-flex items-center ml-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @if($unread)
                        <span class="absolute -top-1 -right-2 bg-red-600 text-white text-[10px] rounded-full px-1.5">
                            {{ $unread }}
                        </span>
                        @endif
                    </a>
                    @endauth

                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                        {{ auth()->user()->first_name ?? '' }}
                    </span>

                    <!-- Profile Dropdown -->
                    <div class="relative group">
                        <a href="{{ route('resident.profile.edit') }}" class="relative">
                            @if (auth()->user()->profile_image)
                                <img src="{{ auth()->user()->profile_image_url }}"
                                    alt="Profile"
                                    class="w-10 h-10 rounded-full object-cover border-2 border-white dark:border-gray-600 shadow-md hover:scale-105 transition-transform duration-200">
                            @else
                                <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center shadow-md hover:scale-105 transition-transform duration-200">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                            @endif
                        </a>
                        
                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-100 dark:border-gray-700 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <div class="py-2">
                                <a href="{{ route('resident.profile.edit') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-green-50 dark:hover:bg-gray-700 hover:text-green-600 dark:hover:text-green-400 transition-colors duration-200">
                                    <i class="fas fa-user-circle mr-2"></i>
                                    My Profile
                                </a>
                                
                                <div class="border-t border-gray-100 dark:border-gray-600 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}" id="logout-form" class="logout-form">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-200">
                                        <i class="fas fa-sign-out-alt mr-2"></i>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile Profile & Menu Button -->
                <div class="lg:hidden flex items-center space-x-3">
                    <!-- Mobile Profile Picture -->
                    @auth
                    <a href="{{ route('resident.profile.edit') }}" class="relative">
                        @if (auth()->user()->profile_image)
                            <img src="{{ auth()->user()->profile_image_url }}"
                                alt="Profile"
                                class="w-9 h-9 rounded-full object-cover border-2 border-green-200 dark:border-green-600 shadow-md hover:scale-105 transition-transform duration-200">
                        @else
                            <div class="w-9 h-9 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center shadow-md hover:scale-105 transition-transform duration-200">
                                <i class="fas fa-user text-white text-sm"></i>
                            </div>
                        @endif
                    </a>
                    @endauth

                    <!-- Mobile Menu Button -->
                    <button id="mobile-menu-btn" class="text-gray-700 dark:text-gray-300 p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation Menu -->
            <div id="mobile-menu" class="lg:hidden hidden absolute top-full left-0 w-full bg-white/95 dark:bg-gray-900/95 backdrop-blur-lg border-t border-gray-200 dark:border-gray-700 shadow-2xl transition-all duration-300 z-50 rounded-b-2xl overflow-hidden max-h-screen overflow-y-auto">
                <nav class="flex flex-col py-2">
                    <a href="#home" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 font-medium px-6 py-4 transition-all duration-200 flex items-center border-b border-gray-100 dark:border-gray-800">
                        <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-home text-green-600 dark:text-green-400"></i>
                        </div>
                        <span class="font-semibold">Home</span>
                    </a>
                    <a href="#submit-report" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 font-medium px-6 py-4 transition-all duration-200 flex items-center border-b border-gray-100 dark:border-gray-800">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-plus-circle text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <span class="font-semibold">Submit Reports</span>
                    </a>
                    <a href="{{ route('resident.reports.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 font-medium px-6 py-4 transition-all duration-200 flex items-center border-b border-gray-100 dark:border-gray-800">
                        <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-history text-purple-600 dark:text-purple-400"></i>
                        </div>
                        <span class="font-semibold">Report History</span>
                    </a>
                    <a href="{{ route('resident.schedule.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 font-medium px-6 py-4 transition-all duration-200 flex items-center border-b border-gray-100 dark:border-gray-800">
                        <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900/30 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-calendar text-orange-600 dark:text-orange-400"></i>
                        </div>
                        <span class="font-semibold">Collection Schedule</span>
                    </a>
                    <a href="{{ route('resident.gamification.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 font-medium px-6 py-4 transition-all duration-200 flex items-center border-b border-gray-100 dark:border-gray-800">
                        <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-trophy text-yellow-600 dark:text-yellow-400"></i>
                        </div>
                        <span class="font-semibold">Eco Points</span>
                    </a>
                    <a href="{{ route('resident.gamification.rewards') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 font-medium px-6 py-4 transition-all duration-200 flex items-center border-b border-gray-100 dark:border-gray-800 ml-6">
                        <div class="w-8 h-8 bg-pink-100 dark:bg-pink-900/30 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-gift text-pink-600 dark:text-pink-400 text-sm"></i>
                        </div>
                        <span class="font-medium text-sm">Rewards Store</span>
                    </a>
                    <a href="{{ route('resident.feedback.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 font-medium px-6 py-4 transition-all duration-200 flex items-center border-b border-gray-100 dark:border-gray-800">
                        <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-comment text-indigo-600 dark:text-indigo-400"></i>
                        </div>
                        <div class="flex items-center justify-between flex-1">
                            <span class="font-semibold">My Feedback</span>
                            @auth
                            @php 
                                $newResponses = auth()->user()->notifications()
                                    ->where('data->type', 'feedback_response')
                                    ->whereNull('read_at')
                                    ->count(); 
                            @endphp
                            @if($newResponses)
                                <span class="bg-green-600 text-white text-xs rounded-full px-2 py-1 font-bold">{{ $newResponses }}</span>
                            @endif
                            @endauth
                        </div>
                    </a>
                    
                    <!-- Notifications for mobile -->
                    @auth
                    @php $unread = auth()->user()->unreadNotifications()->count(); @endphp
                    <a href="{{ route('notifications.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 font-medium px-6 py-4 transition-all duration-200 flex items-center border-b border-gray-100 dark:border-gray-800">
                        <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-bell text-red-600 dark:text-red-400"></i>
                        </div>
                        <div class="flex items-center justify-between flex-1">
                            <span class="font-semibold">Notifications</span>
                            @if($unread)
                                <span class="bg-red-600 text-white text-xs rounded-full px-2 py-1 font-bold">{{ $unread }}</span>
                            @endif
                        </div>
                    </a>
                    @endauth
                    
                    
                    
                    <!-- User info for mobile -->
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 mt-2">
                        <div class="flex items-center space-x-4 mb-4">
                            @if (auth()->user()->profile_image)
                                <img src="{{ auth()->user()->profile_image_url }}"
                                    alt="Profile"
                                    class="w-12 h-12 rounded-full object-cover border-3 border-green-200 dark:border-green-600 shadow-lg">
                            @else
                                <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center shadow-lg">
                                    <i class="fas fa-user text-white text-lg"></i>
                                </div>
                            @endif
                            <div>
                                <span class="text-lg font-bold text-gray-900 dark:text-white">
                                    {{ auth()->user()->first_name ?? '' }}
                                </span>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                        
                        <div class="flex flex-col space-y-2">
                            <a href="{{ route('resident.profile.edit') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 flex items-center px-3 py-2 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all">
                                <i class="fas fa-user-circle mr-3 text-base"></i>View Profile
                            </a>
                            
                            <!-- Logout for mobile -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left text-sm text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 flex items-center px-3 py-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-all">
                                    <i class="fas fa-sign-out-alt mr-3 text-base"></i>Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </nav>
            </div>
            </div>
        </div>
    </header>

    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-green-500 to-green-600 dark:from-green-600 dark:to-green-700 text-white shadow-xl transition-colors duration-300">
        <div class="container mx-auto px-4 sm:px-6 py-4 sm:py-5">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white/20 dark:bg-white/30 rounded-full flex items-center justify-center">
                        <i class="fas fa-home text-lg sm:text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-lg sm:text-2xl font-bold">Hello, {{ Auth::user()->name ?? 'Resident' }}!</h1>
                        <p class="text-green-100 dark:text-green-200 text-xs sm:text-sm">Welcome to your Clean City dashboard. Report waste and track collection status.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main id="home" class="max-w-7xl mx-auto px-4 sm:px-6 py-4 sm:py-8">
        
        <!-- Top Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
            <!-- Location Map -->
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden transition-colors duration-300">
                <!-- Header -->
                <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-blue-50 dark:from-green-900/20 dark:to-blue-900/20 border-b border-gray-100 dark:border-gray-700 transition-colors duration-300">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-map-marker-alt text-white text-sm"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800 dark:text-white">Your Location</h3>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Real-time location detection</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div id="location-status" class="flex items-center gap-1">
                                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                <span class="text-xs text-green-600 dark:text-green-400">Active</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Map Container with Modern UI -->
                <div class="relative w-full">
                    <div id="map" class="w-full map-container" style="aspect-ratio: 3 / 2; min-height: 300px;"></div>
                    
                    <!-- Map Overlay Controls -->
                    <div class="absolute top-4 left-4 bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm rounded-lg shadow-lg p-3 border border-white/20 dark:border-gray-600/20">
                        <div class="flex items-center gap-2 text-sm">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="text-gray-700 dark:text-gray-200 font-medium">Current Location</span>
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1" id="accuracy-info">
                            Accuracy: High
                        </div>
                    </div>

                    
                </div>
            </div>

            <!-- Report Statistics -->
            <div class="card-hover bg-white dark:bg-gray-800 rounded-xl card-shadow p-6 theme-transition">
                <div class="flex items-center gap-2 mb-6">
                    <i class="fas fa-chart-bar text-green-600 dark:text-green-400"></i>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Report Statistics</h3>
                </div>
                
                <div class="space-y-4">
                    <div class="stat-card bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-md">
                                    <i class="fas fa-file-alt text-white text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Total Reports</span>
                            </div>
                            <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['total'] ?? 0 }}</span>
                        </div>
                    </div>

                    <div class="stat-card bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-700 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-md">
                                    <i class="fas fa-clock text-white text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Pending</span>
                            </div>
                            <span class="text-2xl font-bold text-orange-600 dark:text-orange-400">{{ $stats['pending'] ?? 0 }}</span>
                        </div>
                    </div>

                    <div class="stat-card bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-700 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-md">
                                    <i class="fas fa-calendar-alt text-white text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Scheduled</span>
                            </div>
                            <span class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $stats['scheduled'] ?? 0 }}</span>
                        </div>
                    </div>

                    <div class="stat-card bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-md">
                                    <i class="fas fa-check text-white text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Collected</span>
                            </div>
                            <span class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $stats['collected'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Tips (inside same card) -->
                <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700 transition-colors duration-300">
                    <div class="flex items-center gap-2 mb-3">
                        <i class="fas fa-lightbulb text-yellow-500"></i>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Quick Tips</span>
                    </div>
                    <div class="space-y-2 text-xs text-gray-600 dark:text-gray-400">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-camera text-green-500"></i>
                            <span>Upload clear photos for faster processing</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-edit text-blue-500"></i>
                            <span>Provide detailed descriptions</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-exclamation-triangle text-red-500"></i>
                            <span>Report hazardous waste immediately</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Gamification Section -->
        <div class="card-hover bg-white dark:bg-gray-800 rounded-xl card-shadow p-6 mb-8 theme-transition">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-trophy text-white text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Your Eco Progress</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Level up by reporting waste and helping the community!</p>
                    </div>
                </div>
                <a href="{{ route('resident.gamification.index') }}" 
                   class="text-sm text-yellow-600 dark:text-yellow-400 hover:text-yellow-700 dark:hover:text-yellow-300 font-medium flex items-center gap-1">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6" id="gamification-stats">
                <!-- Points Card -->
                <div class="stat-card bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center shadow-md">
                                <i class="fas fa-coins text-white text-sm"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Total Points</span>
                        </div>
                        <span class="text-2xl font-bold text-yellow-600 dark:text-yellow-400" id="total-points">0</span>
                    </div>
                </div>

                <!-- Level Card -->
                <div class="stat-card bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-md">
                                <i class="fas fa-level-up-alt text-white text-sm"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Level</span>
                        </div>
                        <span class="text-2xl font-bold text-blue-600 dark:text-blue-400" id="current-level">1</span>
                    </div>
                </div>

                <!-- Rank Card -->
                <div class="stat-card bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-700 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-md">
                                <i class="fas fa-crown text-white text-sm"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Rank</span>
                        </div>
                        <span class="text-xs font-bold text-purple-600 dark:text-purple-400" id="current-rank">Eco Newbie</span>
                    </div>
                </div>

                <!-- Achievements Card -->
                <div class="stat-card bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-md">
                                <i class="fas fa-medal text-white text-sm"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Badges</span>
                        </div>
                        <span class="text-2xl font-bold text-green-600 dark:text-green-400" id="achievements-count">0</span>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="mb-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Progress to Next Level</span>
                    <span class="text-sm text-gray-600 dark:text-gray-400" id="points-to-next">0 points to go</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                    <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 h-3 rounded-full transition-all duration-300" 
                         style="width: 0%" id="level-progress"></div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid md:grid-cols-2 gap-3">
                <a href="{{ route('resident.gamification.index') }}" 
                   class="flex items-center justify-center gap-2 bg-yellow-100 dark:bg-yellow-900/30 hover:bg-yellow-200 dark:hover:bg-yellow-900/50 text-yellow-700 dark:text-yellow-300 px-4 py-3 rounded-lg font-medium transition-colors">
                    <i class="fas fa-trophy"></i>
                    <span>View Progress & Achievements</span>
                </a>
                <a href="{{ route('resident.gamification.rewards') }}" 
                   class="flex items-center justify-center gap-2 bg-green-100 dark:bg-green-900/30 hover:bg-green-200 dark:hover:bg-green-900/50 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg font-medium transition-colors">
                    <i class="fas fa-gift"></i>
                    <span>Redeem Rewards</span>
                </a>
            </div>
        </div>

        <!-- Submit Report Section -->
        <div id="submit-report" class="card-hover bg-white dark:bg-gray-800 rounded-xl card-shadow p-6 mb-8 theme-transition">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-green-600 to-green-700 rounded-xl flex items-center justify-center shadow-md">
                    <i class="fas fa-plus text-white text-sm"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Submit New Waste Report</h3>
            </div>
            <p class="text-gray-600 dark:text-gray-400 mb-6">Help keep our community clean by reporting waste issues</p>
            
            @if(session('success'))
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 text-green-700 dark:text-green-200 px-4 py-3 rounded-xl mb-6 flex items-center gap-2 transition-colors duration-300">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            
            @if ($errors->any())
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-200 p-4 mb-6 rounded-xl transition-colors duration-300">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-exclamation-circle"></i>
                        <span class="font-medium">Please fix the following errors:</span>
                    </div>
                    <ul class="list-disc ml-6 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('resident.report.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="grid md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Location</label>
                        <input type="text" name="location" id="location" placeholder="Detecting location..." readonly
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all" 
                            value="{{ old('location') }}">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Location will be detected automatically</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Report Date</label>
                        <input type="date" name="report_date" id="report_date" readonly
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 transition-all">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Today's date is automatically set</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Waste Type *</label>
                        <select name="waste_type" id="waste_type" required
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                            <option value="">Select waste type...</option>
                            <option value="Organic">🌱 Organic</option>
                            <option value="Plastic">♻️ Plastic</option>
                            <option value="E-Waste">💻 E-Waste</option>
                            <option value="Hazardous">⚠️ Hazardous</option>
                            <option value="Mixed">📦 Mixed</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Upload Photo</label>
                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-8 text-center hover:border-green-400 dark:hover:border-green-500 hover:bg-green-50/50 dark:hover:bg-green-900/20 transition-all">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 dark:text-gray-500 mb-3"></i>
                        <input type="file" name="image" class="hidden" id="imageInput" accept="image/*">
                        <label for="imageInput" class="cursor-pointer">
                            <span class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium">Click to upload</span>
                            <span class="text-gray-500 dark:text-gray-400"> or drag and drop</span>
                        </label>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">PNG, JPG, GIF up to 10MB</p>
                    </div>
                    @if ($errors->has('image'))
                        <p class="text-red-500 dark:text-red-400 text-sm mt-2">{{ $errors->first('image') }}</p>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Additional Details</label>
                    <textarea name="additional_details" rows="4"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all" 
                        placeholder="Describe the waste issue in detail...">{{ old('additional_details') }}</textarea>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Provide additional context to help us understand the situation</p>
                </div>

                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">
                
                <div class="flex items-center gap-4">
                    <button type="submit" class="btn-hover bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-8 py-3 rounded-xl shadow-lg flex items-center gap-2">
                        <i class="fas fa-paper-plane"></i>
                        Submit Waste Report
                    </button>
                    <button type="reset" class="btn-hover bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-6 py-3 rounded-xl">
                        Clear Form
                    </button>
                </div>
            </form>
        </div>

        <!-- Report History -->
        <div class="card-hover bg-white dark:bg-gray-800 rounded-xl card-shadow p-6 theme-transition">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-history text-white text-sm"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Report History</h3>
                </div>
                <span class="text-sm text-gray-500 dark:text-gray-400">Track the status of all your submitted reports</span>
            </div>

            <div class="overflow-x-auto desktop-table">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-50 to-green-50 dark:from-gray-700 dark:to-green-900/30 border-b border-gray-200 dark:border-gray-600">
                            <th class="text-left px-4 py-4 text-sm font-medium text-gray-700 dark:text-gray-300">Date & Time</th>
                            <th class="text-left px-4 py-4 text-sm font-medium text-gray-700 dark:text-gray-300">Location</th>
                            <th class="text-left px-4 py-4 text-sm font-medium text-gray-700 dark:text-gray-300">Waste Type</th>
                            <th class="text-left px-4 py-4 text-sm font-medium text-gray-700 dark:text-gray-300">Status</th>
                            <th class="text-left px-4 py-4 text-sm font-medium text-gray-700 dark:text-gray-300">Image</th>
                            <th class="text-left px-4 py-4 text-sm font-medium text-gray-700 dark:text-gray-300">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                        @forelse ($reports as $report)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-4 py-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($report->report_date)->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($report->created_at)->format('h:i A') }}</div>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-600 dark:text-gray-300 max-w-xs truncate">{{ $report->location }}</td>
                                <td class="px-4 py-4">
                                    <span class="@class([
                                        'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium',
                                        'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200' => $report->waste_type === 'Organic',
                                        'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200' => $report->waste_type === 'Plastic',
                                        'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-200' => $report->waste_type === 'E-Waste',
                                        'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200' => $report->waste_type === 'Hazardous',
                                        'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200' => !in_array($report->waste_type, ['Organic', 'Plastic', 'E-Waste', 'Hazardous']),
                                    ])">
                                        {{ $report->waste_type }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    @if ($report->status === 'pending')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200">
                                            <i class="fas fa-clock mr-1"></i>Pending
                                        </span>
                                    @elseif ($report->status === 'assigned')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200">
                                            <i class="fas fa-user-check mr-1"></i>Assigned
                                        </span>
                                    @elseif ($report->status === 'enroute')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-200">
                                            <i class="fas fa-truck mr-1"></i>Enroute
                                        </span>
                                    @elseif ($report->status === 'collected')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200">
                                            <i class="fas fa-check mr-1"></i>Collected
                                        </span>
                                    @elseif ($report->status === 'closed')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                            <i class="fas fa-check-double mr-1"></i>Closed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                            {{ ucfirst($report->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    @if ($report->image_path)
                                        <img src="{{ asset('storage/' . $report->image_path) }}" 
                                             class="w-14 h-14 rounded-xl object-cover border-2 border-gray-200 dark:border-gray-600 shadow-sm" 
                                             alt="Report image">
                                    @else
                                        <div class="w-14 h-14 bg-gray-100 dark:bg-gray-700 rounded-xl flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400 dark:text-gray-500"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
  <a href="{{ route('resident.reports.show', $report->id) }}"
     class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium transition-colors">
     View Details
  </a>
</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-16 text-center">
                                    <div class="flex flex-col items-center gap-4">
                                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                            <i class="fas fa-inbox text-2xl text-gray-300 dark:text-gray-500"></i>
                                        </div>
                                        <div>
                                            <p class="text-gray-500 dark:text-gray-400 font-medium">No reports submitted yet</p>
                                            <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Submit your first waste report above to get started</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Reports Layout -->
            <div class="mobile-reports">
                @forelse ($reports as $report)
                    <div class="mobile-report-card">
                        <!-- Report Header -->
                        <div class="flex items-center justify-between p-4 border-b border-gray-100 dark:border-gray-600">
                            <div class="flex items-center space-x-3">
                                @if ($report->image_path)
                                    <img src="{{ asset('storage/' . $report->image_path) }}" 
                                         class="w-12 h-12 rounded-lg object-cover border border-gray-200 dark:border-gray-600" 
                                         alt="Report image">
                                @else
                                    <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400 dark:text-gray-500"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        {{ \Carbon\Carbon::parse($report->report_date)->format('M d, Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($report->created_at)->format('h:i A') }}
                                    </div>
                                </div>
                            </div>
                            <!-- Status Badge -->
                            @if ($report->status === 'pending')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200">
                                    <i class="fas fa-clock mr-1"></i>Pending
                                </span>
                            @elseif ($report->status === 'assigned')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200">
                                    <i class="fas fa-user-check mr-1"></i>Assigned
                                </span>
                            @elseif ($report->status === 'enroute')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-200">
                                    <i class="fas fa-truck mr-1"></i>Enroute
                                </span>
                            @elseif ($report->status === 'collected')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200">
                                    <i class="fas fa-check mr-1"></i>Collected
                                </span>
                            @elseif ($report->status === 'closed')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                    <i class="fas fa-check-double mr-1"></i>Closed
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                    {{ ucfirst($report->status) }}
                                </span>
                            @endif
                        </div>
                        
                        <!-- Report Details -->
                        <div class="p-4 space-y-3">
                            <!-- Location -->
                            <div class="flex items-start space-x-2">
                                <i class="fas fa-map-marker-alt text-gray-400 dark:text-gray-500 mt-1 text-sm flex-shrink-0"></i>
                                <span class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2">{{ $report->location }}</span>
                            </div>
                            
                            <!-- Waste Type -->
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-trash text-gray-400 dark:text-gray-500 text-sm flex-shrink-0"></i>
                                <span class="@class([
                                    'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium',
                                    'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200' => $report->waste_type === 'Organic',
                                    'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200' => $report->waste_type === 'Plastic',
                                    'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-200' => $report->waste_type === 'E-Waste',
                                    'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200' => $report->waste_type === 'Hazardous',
                                    'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200' => !in_array($report->waste_type, ['Organic', 'Plastic', 'E-Waste', 'Hazardous']),
                                ])">
                                    {{ $report->waste_type }}
                                </span>
                            </div>
                            
                            <!-- Action Button -->
                            <div class="pt-2">
                                <a href="{{ route('resident.reports.show', $report->id) }}"
                                   class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 dark:bg-blue-500 text-white text-sm font-medium rounded-lg hover:bg-blue-700 dark:hover:bg-blue-600 transition-colors">
                                   <i class="fas fa-eye mr-2"></i>View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16">
                        <div class="flex flex-col items-center gap-4">
                            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                <i class="fas fa-inbox text-2xl text-gray-300 dark:text-gray-500"></i>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 font-medium">No reports submitted yet</p>
                                <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Submit your first waste report above to get started</p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </main>

    <script>
        // Initialize Google Map
        function initMap() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    const userLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    
                    // Set coordinates in hidden inputs
                    document.getElementById('latitude').value = userLocation.lat;
                    document.getElementById('longitude').value = userLocation.lng;
                    
                    // Create map with consistent satellite view regardless of theme
                    const map = new google.maps.Map(document.getElementById('map'), {
                        zoom: 15,
                        center: userLocation,
                        mapTypeId: google.maps.MapTypeId.HYBRID, // Satellite view with street labels
                        mapTypeControl: true,
                        mapTypeControlOptions: {
                            style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
                            position: google.maps.ControlPosition.TOP_CENTER,
                            mapTypeIds: [
                                google.maps.MapTypeId.ROADMAP,
                                google.maps.MapTypeId.SATELLITE,
                                google.maps.MapTypeId.HYBRID,
                                google.maps.MapTypeId.TERRAIN
                            ]
                        },
                        streetViewControl: true,
                        fullscreenControl: true,
                        zoomControl: true,
                        scaleControl: true,
                        rotateControl: true,
                        tilt: 0, // Start with flat view, user can tilt if supported
                        styles: [] // Empty styles array to prevent theme-based styling
                    });
                    
                    // Force satellite view to remain consistent regardless of theme
                    window.addEventListener('themeChanged', function(event) {
                        // Ensure map stays in satellite/hybrid mode when theme changes
                        if (map.getMapTypeId() !== google.maps.MapTypeId.HYBRID && 
                            map.getMapTypeId() !== google.maps.MapTypeId.SATELLITE) {
                            map.setMapTypeId(google.maps.MapTypeId.HYBRID);
                        }
                    });
                    
                    // Create custom marker
                    new google.maps.Marker({ 
                        position: userLocation, 
                        map: map, 
                        title: "Your Location",
                        icon: {
                            url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="#22c55e">
                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                </svg>
                            `),
                            scaledSize: new google.maps.Size(40, 40),
                            anchor: new google.maps.Point(20, 40)
                        }
                    });
                    
                    // Reverse geocoding to get address
                    const geocoder = new google.maps.Geocoder();
                    geocoder.geocode({ location: userLocation }, (results, status) => {
                        const locationInput = document.getElementById('location');
                        if (status === "OK" && results[0]) {
                            locationInput.value = results[0].formatted_address;
                            locationInput.classList.remove("border-gray-300");
                            locationInput.classList.add("border-green-500", "text-green-700", "bg-green-50");
                        } else {
                            locationInput.value = "Unable to detect address";
                            locationInput.classList.remove("border-gray-300");
                            locationInput.classList.add("border-red-500", "text-red-600", "bg-red-50");
                        }
                    });
                }, function () {
                    alert("❌ Location access denied. Cannot detect your position.");
                    const locationInput = document.getElementById('location');
                    locationInput.value = "Location permission denied";
                    locationInput.classList.add("border-red-500", "text-red-600", "bg-red-50");
                });
            } else {
                alert("❌ Geolocation is not supported by this browser.");
                const locationInput = document.getElementById('location');
                locationInput.value = "Geolocation not supported";
                locationInput.classList.add("border-red-500", "text-red-600", "bg-red-50");
            }
        }

        // Set today's date
        document.addEventListener('DOMContentLoaded', function () {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('report_date').value = today;
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
                header.classList.add('shadow-xl');
                header.classList.remove('shadow-lg');
            } else {
                header.classList.remove('shadow-xl');
                header.classList.add('shadow-lg');
            }
        });

        // File upload preview
        const imageInput = document.getElementById('imageInput');
        if (imageInput) {
            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const uploadArea = imageInput.closest('.border-dashed');
                    uploadArea.classList.add('border-green-400', 'bg-green-50');
                    uploadArea.querySelector('.fa-cloud-upload-alt').classList.add('text-green-500');
                    uploadArea.querySelector('span').textContent = `Selected: ${file.name}`;
                }
            });
        }

        // Form validation enhancement - only for the waste report form
        const reportForm = document.querySelector('#submit-report form');
        if (reportForm) {
            reportForm.addEventListener('submit', function(e) {
                const wasteType = document.getElementById('waste_type');
                if (wasteType && !wasteType.value) {
                    e.preventDefault();
                    wasteType.classList.add('border-red-500');
                    wasteType.focus();
                    
                    // Show error message
                    const errorMsg = document.createElement('p');
                    errorMsg.className = 'text-red-500 text-sm mt-1';
                    errorMsg.textContent = 'Please select a waste type';
                    
                    const existingError = wasteType.parentNode.querySelector('.text-red-500');
                    if (!existingError) {
                        wasteType.parentNode.appendChild(errorMsg);
                    }
                    
                    setTimeout(() => {
                        errorMsg.remove();
                        wasteType.classList.remove('border-red-500');
                    }, 3000);
                }
            });
        }

        // Enhanced stat card animations
        document.querySelectorAll('.stat-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-4px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        

        // Navigation functionality for smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const target = document.querySelector(targetId);
                if (target) {
                    // Close mobile menu if open
                    const mobileMenu = document.getElementById('mobile-menu');
                    if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
                        mobileMenu.classList.add('hidden');
                        const icon = document.getElementById('mobile-menu-btn').querySelector('i');
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    }
                    
                    // Smooth scroll to target
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Load gamification data
        async function loadGamificationStats() {
            try {
                const response = await fetch('{{ route("resident.gamification.api.stats") }}');
                if (response.ok) {
                    const stats = await response.json();
                    
                    // Update stats display
                    document.getElementById('total-points').textContent = stats.total_points || 0;
                    document.getElementById('current-level').textContent = stats.current_level || 1;
                    document.getElementById('current-rank').textContent = stats.current_rank || 'Eco Newbie';
                    document.getElementById('achievements-count').textContent = stats.achievements_count || 0;
                    document.getElementById('points-to-next').textContent = 
                        stats.points_to_next_level > 0 ? `${stats.points_to_next_level} points to go` : 'Max level!';
                    
                    // Update progress bar
                    const progressBar = document.getElementById('level-progress');
                    if (stats.points_to_next_level > 0) {
                        const currentLevelPoints = stats.total_points - (stats.total_points - stats.points_to_next_level);
                        const nextLevelPoints = stats.points_to_next_level;
                        const progress = (currentLevelPoints / (currentLevelPoints + nextLevelPoints)) * 100;
                        progressBar.style.width = Math.min(progress, 100) + '%';
                    } else {
                        progressBar.style.width = '100%';
                    }
                }
            } catch (error) {
                console.error('Error loading gamification stats:', error);
            }
        }

        // Load gamification stats when page loads
        document.addEventListener('DOMContentLoaded', loadGamificationStats);
    </script>
    
    <!-- Theme Management Script -->
    <script>
        // Theme Management System
        class ThemeManager {
            constructor() {
                this.currentTheme = 'light';
                this.init();
            }

            init() {
                // Load theme from user preference or localStorage
                this.loadTheme();
                
                // Initialize theme toggle buttons
                this.initToggleButtons();
                
                // Apply theme immediately
                this.applyTheme(this.currentTheme);
                
                // Listen for system theme changes
                this.listenForSystemThemeChanges();
            }

            async loadTheme() {
                try {
                    // First try to get from server (authenticated users)
                    const response = await fetch('/theme/current');
                    if (response.ok) {
                        const data = await response.json();
                        this.currentTheme = data.theme;
                    }
                } catch (error) {
                    // Fallback to localStorage for guests
                    this.currentTheme = localStorage.getItem('theme') || 'light';
                }
            }

            initToggleButtons() {
                // Theme toggle button
                const themeToggle = document.getElementById('theme-toggle');
                if (themeToggle) {
                    themeToggle.addEventListener('click', () => {
                        this.toggleTheme();
                    });
                }

                // Mobile theme toggle
                const mobileThemeToggle = document.getElementById('mobile-theme-toggle');
                if (mobileThemeToggle) {
                    mobileThemeToggle.addEventListener('click', () => {
                        this.toggleTheme();
                    });
                }

                // Theme selector dropdown
                const themeSelectors = document.querySelectorAll('[data-theme]');
                themeSelectors.forEach(selector => {
                    selector.addEventListener('click', (e) => {
                        e.preventDefault();
                        const theme = selector.getAttribute('data-theme');
                        this.setTheme(theme);
                    });
                });
            }

            toggleTheme() {
                const newTheme = this.currentTheme === 'light' ? 'dark' : 'light';
                this.setTheme(newTheme);
            }

            async setTheme(theme) {
                this.currentTheme = theme;
                this.applyTheme(theme);
                await this.saveTheme(theme);
                this.updateToggleButton();
            }

            applyTheme(theme) {
                const html = document.documentElement;
                
                if (theme === 'dark') {
                    html.classList.add('dark');
                } else {
                    html.classList.remove('dark');
                }

                // Update meta theme-color for mobile browsers
                this.updateMetaThemeColor(theme);
                
                // Ensure map maintains satellite view regardless of theme
                this.maintainMapSatelliteView();
                
                // Trigger custom event for other components
                window.dispatchEvent(new CustomEvent('themeChanged', { 
                    detail: { theme } 
                }));
            }

            maintainMapSatelliteView() {
                // Find the map element and ensure it stays in satellite view
                const mapElement = document.getElementById('map');
                if (mapElement && window.google && window.google.maps) {
                    // Add CSS to override any dark theme styling on map
                    const mapContainer = mapElement.parentElement;
                    if (mapContainer) {
                        mapContainer.style.filter = 'none';
                        mapContainer.style.backgroundColor = 'transparent';
                    }
                }
            }

            updateMetaThemeColor(theme) {
                let metaThemeColor = document.querySelector('meta[name="theme-color"]');
                if (!metaThemeColor) {
                    metaThemeColor = document.createElement('meta');
                    metaThemeColor.name = 'theme-color';
                    document.head.appendChild(metaThemeColor);
                }
                
                metaThemeColor.content = theme === 'dark' ? '#1f2937' : '#10b981';
            }

            async saveTheme(theme) {
                try {
                    // Save to server for authenticated users
                    const response = await fetch('/theme/toggle', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        },
                        body: JSON.stringify({ theme })
                    });

                    if (!response.ok) {
                        throw new Error('Failed to save theme preference');
                    }
                } catch (error) {
                    console.warn('Could not save theme to server, using localStorage:', error);
                    // Fallback to localStorage
                    localStorage.setItem('theme', theme);
                }
            }

            updateToggleButton() {
                const themeToggle = document.getElementById('theme-toggle');
                const themeIcon = document.getElementById('theme-icon');
                const mobileThemeIcon = document.getElementById('mobile-theme-icon');
                const mobileThemeText = document.getElementById('mobile-theme-text');
                
                if (this.currentTheme === 'dark') {
                    if (themeIcon) {
                        themeIcon.className = 'fas fa-sun text-lg';
                    }
                    if (mobileThemeIcon) {
                        mobileThemeIcon.className = 'fas fa-sun mr-3 w-4';
                    }
                    if (mobileThemeText) {
                        mobileThemeText.textContent = 'Light Mode';
                    }
                    if (themeToggle) {
                        themeToggle.title = 'Switch to Light Mode';
                    }
                } else {
                    if (themeIcon) {
                        themeIcon.className = 'fas fa-moon text-lg';
                    }
                    if (mobileThemeIcon) {
                        mobileThemeIcon.className = 'fas fa-moon mr-3 w-4';
                    }
                    if (mobileThemeText) {
                        mobileThemeText.textContent = 'Dark Mode';
                    }
                    if (themeToggle) {
                        themeToggle.title = 'Switch to Dark Mode';
                    }
                }
            }

            getSystemTheme() {
                return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }

            listenForSystemThemeChanges() {
                // Auto theme functionality removed
            }
        }

        // Initialize theme manager when DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            window.themeManager = new ThemeManager();
        });

        // Export for use in other scripts
        window.ThemeManager = ThemeManager;
    </script>
    
    @include('partials.chat-widget')
    @include('partials.floating-theme-picker')
</body>
</html>