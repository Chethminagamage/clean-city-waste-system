<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resident Dashboard | Clean City</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDgyETTNM7hQ-P9BETdNwTbMr6ggGr73oY&callback=initMap" async defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        .card-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .stat-card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px -5px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
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
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .profile-icon:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(34, 197, 94, 0.3);
        }
        
        /* Smooth transitions */
        * {
            transition: color 0.3s ease, background-color 0.3s ease, border-color 0.3s ease, transform 0.3s ease;
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

        /* Top contact bar responsive */
        @media (max-width: 768px) {
            .top-contact-bar .flex.justify-between {
                flex-direction: column;
                gap: 0.5rem;
                text-align: center;
            }
            
            .top-contact-bar .flex.items-center.space-x-6 {
                flex-direction: column;
                space-x: 0;
                gap: 0.25rem;
            }
            
            .top-contact-bar .flex.items-center.space-x-3 {
                justify-content: center;
            }
        }

        @media (max-width: 640px) {
            .top-contact-bar .flex.items-center.space-x-6 > div {
                font-size: 0.75rem;
            }
            
            .top-contact-bar {
                padding: 0.5rem 0;
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

<body class="bg-gradient-to-br from-gray-50 to-green-50 min-h-screen">
    <!-- Top Contact Bar -->
    <div class="bg-green-700 text-white py-2 text-sm top-contact-bar">
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

    <!-- Header/Navigation -->
    <header class="glass-effect sticky top-0 z-50 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex justify-between items-center py-3 sm:py-4">
                <!-- Logo -->
                <div class="flex items-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-8 h-8 sm:w-10 sm:h-10 object-contain mr-2 sm:mr-3">
                    <div>
                        <span class="text-xl sm:text-2xl font-bold text-gray-800">Clean City</span>
                        <p class="text-xs sm:text-sm text-green-600 hidden sm:block">Your Waste, Our Responsibility</p>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="hidden lg:flex items-center space-x-8">
                    <div class="relative group">
                        <a href="#home" class="text-gray-700 hover:text-green-600 font-medium flex items-center transition-colors">
                            Home 
                        </a>
                    </div>
                    <div class="relative group">
                        <a href="#submit-report" class="text-gray-700 hover:text-green-600 font-medium flex items-center transition-colors">
                            Submit Reports 
                        </a>
                    </div>
                    <div class="relative group">
                        <a href="{{ route('resident.reports.index') }}" 
                        class="text-gray-700 hover:text-green-600 font-medium flex items-center transition-colors">
                            Report History
                        </a>
                    </div>
                    <div class="relative group">
                        <a href="{{ route('resident.schedule.index') }}"
                        class="text-gray-700 hover:text-green-600 font-medium flex items-center transition-colors">
                            Collection Schedule
                        </a>
                    </div>
                    <div class="relative group">
                    <a href="{{ route('resident.feedback.index') }}"
                    class="text-gray-700 hover:text-green-600 font-medium flex items-center transition-colors">
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
                     <!-- Theme Toggle Button -->
                <button class="theme-toggle-btn text-gray-700 hover:text-green-600" aria-label="Toggle dark mode" title="Switch theme">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                    </svg>
                </button>
                    <!-- Notification Bell -->
                    @auth
                    @php $unread = auth()->user()->unreadNotifications()->count(); @endphp
                    <a href="{{ route('notifications.index') }}" class="relative inline-flex items-center ml-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700 hover:text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @if($unread)
                        <span class="absolute -top-1 -right-2 bg-red-600 text-white text-[10px] rounded-full px-1.5">
                            {{ $unread }}
                        </span>
                        @endif
                    </a>
                    @endauth

                    <span class="text-sm font-medium text-gray-900">
                        {{ auth()->user()->first_name ?? '' }}
                    </span>

                    <!-- Profile Dropdown -->
                    <div class="relative group">
                        <a href="{{ route('resident.profile.edit') }}" class="relative">
                            @if (auth()->user()->profile_image)
                                <img src="{{ auth()->user()->profile_image_url }}"
                                    alt="Profile"
                                    class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-md hover:scale-105 transition-transform duration-200">
                            @else
                                <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center shadow-md hover:scale-105 transition-transform duration-200">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                            @endif
                        </a>
                        
                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <div class="py-2">
                                <a href="{{ route('resident.profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-green-50 hover:text-green-600 transition-colors duration-200">
                                    <i class="fas fa-user-circle mr-2"></i>
                                    My Profile
                                </a>
                                
                                <div class="border-t border-gray-100 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}" id="logout-form" class="logout-form">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 transition-colors duration-200">
                                        <i class="fas fa-sign-out-alt mr-2"></i>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="lg:hidden text-gray-700 p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>

            <!-- Mobile Navigation Menu -->
            <div id="mobile-menu" class="lg:hidden hidden bg-white border-t border-gray-200 py-4 shadow-lg">
                <nav class="flex flex-col space-y-4">
                    <a href="#home" class="text-gray-700 hover:text-green-600 font-medium px-4 py-2 transition-colors">Home</a>
                    <a href="#submit-report" class="text-gray-700 hover:text-green-600 font-medium px-4 py-2 transition-colors">Submit Reports</a>
                    
                    <!-- User info for mobile -->
                    <div class="px-4 py-2 border-t border-gray-200 mt-2">
                        <a href="{{ route('resident.profile.edit') }}" class="text-sm text-blue-600 hover:text-blue-800 mt-1 inline-block">View Profile</a>
                    </div>
                    
                    <a href="#contact" class="bg-green-500 hover:bg-green-600 text-white px-4 py-3 rounded-lg font-semibold text-center mx-4 transition-colors">
                        Schedule Pickup
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-green-500 to-green-600 text-white shadow-xl">
        <div class="container mx-auto px-4 sm:px-6 py-4 sm:py-5">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-home text-lg sm:text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-lg sm:text-2xl font-bold">Hello, {{ Auth::user()->name ?? 'Resident' }}!</h1>
                        <p class="text-green-100 text-xs sm:text-sm">Welcome to your Clean City dashboard. Report waste and track collection status.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main id="home" class="max-w-7xl mx-auto px-6 py-8">
        
        <!-- Top Section -->
        <div class="grid lg:grid-cols-3 gap-6 mb-8">
            <!-- Location Map -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <!-- Header -->
                <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-blue-50 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-map-marker-alt text-white text-sm"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">Your Location</h3>
                                <p class="text-xs text-gray-600">Real-time location detection</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div id="location-status" class="flex items-center gap-1">
                                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                <span class="text-xs text-green-600">Active</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Map Container with Modern UI -->
                <div class="relative w-full">
                    <div id="map" class="w-full" style="aspect-ratio: 3 / 2; min-height: 300px;"></div>
                    
                    <!-- Map Overlay Controls -->
                    <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm rounded-lg shadow-lg p-3 border border-white/20">
                        <div class="flex items-center gap-2 text-sm">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="text-gray-700 font-medium">Current Location</span>
                        </div>
                        <div class="text-xs text-gray-500 mt-1" id="accuracy-info">
                            Accuracy: High
                        </div>
                    </div>

                    
                </div>
            </div>

            <!-- Report Statistics -->
            <div class="bg-white rounded-xl card-shadow p-6">
                <div class="flex items-center gap-2 mb-6">
                    <i class="fas fa-chart-bar text-green-600"></i>
                    <h3 class="text-lg font-semibold text-gray-800">Report Statistics</h3>
                </div>
                
                <div class="space-y-4">
                    <div class="stat-card bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-md">
                                    <i class="fas fa-file-alt text-white text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Total Reports</span>
                            </div>
                            <span class="text-2xl font-bold text-blue-600">{{ $stats['total'] ?? 0 }}</span>
                        </div>
                    </div>

                    <div class="stat-card bg-orange-50 border border-orange-200 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-md">
                                    <i class="fas fa-clock text-white text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Pending</span>
                            </div>
                            <span class="text-2xl font-bold text-orange-600">{{ $stats['pending'] ?? 0 }}</span>
                        </div>
                    </div>

                    <div class="stat-card bg-purple-50 border border-purple-200 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-md">
                                    <i class="fas fa-calendar-alt text-white text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Scheduled</span>
                            </div>
                            <span class="text-2xl font-bold text-purple-600">{{ $stats['scheduled'] ?? 0 }}</span>
                        </div>
                    </div>

                    <div class="stat-card bg-green-50 border border-green-200 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-md">
                                    <i class="fas fa-check text-white text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Collected</span>
                            </div>
                            <span class="text-2xl font-bold text-green-600">{{ $stats['collected'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Tips (inside same card) -->
                <div class="mt-6 pt-6 border-t border-gray-100">
                    <div class="flex items-center gap-2 mb-3">
                        <i class="fas fa-lightbulb text-yellow-500"></i>
                        <span class="text-sm font-medium text-gray-700">Quick Tips</span>
                    </div>
                    <div class="space-y-2 text-xs text-gray-600">
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
        

        <!-- Submit Report Section -->
        <div id="submit-report" class="bg-white rounded-xl card-shadow p-6 mb-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-green-600 to-green-700 rounded-xl flex items-center justify-center shadow-md">
                    <i class="fas fa-plus text-white text-sm"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800">Submit New Waste Report</h3>
            </div>
            <p class="text-gray-600 mb-6">Help keep our community clean by reporting waste issues</p>
            
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 p-4 mb-6 rounded-xl">
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                        <input type="text" name="location" id="location" placeholder="Detecting location..." readonly
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 text-gray-600 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all" 
                            value="{{ old('location') }}">
                        <p class="text-xs text-gray-500 mt-1">Location will be detected automatically</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Report Date</label>
                        <input type="date" name="report_date" id="report_date" readonly
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 text-gray-600 transition-all">
                        <p class="text-xs text-gray-500 mt-1">Today's date is automatically set</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Waste Type *</label>
                        <select name="waste_type" id="waste_type" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white text-gray-700 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
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
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Upload Photo</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-green-400 hover:bg-green-50/50 transition-all">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                        <input type="file" name="image" class="hidden" id="imageInput" accept="image/*">
                        <label for="imageInput" class="cursor-pointer">
                            <span class="text-blue-600 hover:text-blue-700 font-medium">Click to upload</span>
                            <span class="text-gray-500"> or drag and drop</span>
                        </label>
                        <p class="text-xs text-gray-500 mt-2">PNG, JPG, GIF up to 10MB</p>
                    </div>
                    @if ($errors->has('image'))
                        <p class="text-red-500 text-sm mt-2">{{ $errors->first('image') }}</p>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Additional Details</label>
                    <textarea name="additional_details" rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all" 
                        placeholder="Describe the waste issue in detail...">{{ old('additional_details') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Provide additional context to help us understand the situation</p>
                </div>

                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">
                
                <div class="flex items-center gap-4">
                    <button type="submit" class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-8 py-3 rounded-xl shadow-lg transition-all flex items-center gap-2 transform hover:scale-105">
                        <i class="fas fa-paper-plane"></i>
                        Submit Waste Report
                    </button>
                    <button type="reset" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-xl transition-all">
                        Clear Form
                    </button>
                </div>
            </form>
        </div>

        <!-- Report History -->
        <div class="bg-white rounded-xl card-shadow p-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-history text-white text-sm"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">Report History</h3>
                </div>
                <span class="text-sm text-gray-500">Track the status of all your submitted reports</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-50 to-green-50 border-b border-gray-200">
                            <th class="text-left px-4 py-4 text-sm font-medium text-gray-700">Date & Time</th>
                            <th class="text-left px-4 py-4 text-sm font-medium text-gray-700">Location</th>
                            <th class="text-left px-4 py-4 text-sm font-medium text-gray-700">Waste Type</th>
                            <th class="text-left px-4 py-4 text-sm font-medium text-gray-700">Status</th>
                            <th class="text-left px-4 py-4 text-sm font-medium text-gray-700">Image</th>
                            <th class="text-left px-4 py-4 text-sm font-medium text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($reports as $report)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($report->report_date)->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($report->created_at)->format('h:i A') }}</div>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-600 max-w-xs truncate">{{ $report->location }}</td>
                                <td class="px-4 py-4">
                                    <span class="@class([
                                        'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium',
                                        'bg-green-100 text-green-800' => $report->waste_type === 'Organic',
                                        'bg-blue-100 text-blue-800' => $report->waste_type === 'Plastic',
                                        'bg-purple-100 text-purple-800' => $report->waste_type === 'E-Waste',
                                        'bg-red-100 text-red-800' => $report->waste_type === 'Hazardous',
                                        'bg-gray-100 text-gray-800' => !in_array($report->waste_type, ['Organic', 'Plastic', 'E-Waste', 'Hazardous']),
                                    ])">
                                        {{ $report->waste_type }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    @if ($report->status === 'pending')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>Pending
                                        </span>
                                    @elseif ($report->status === 'assigned')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-user-check mr-1"></i>Assigned
                                        </span>
                                    @elseif ($report->status === 'enroute')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            <i class="fas fa-truck mr-1"></i>Enroute
                                        </span>
                                    @elseif ($report->status === 'collected')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>Collected
                                        </span>
                                    @elseif ($report->status === 'closed')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-check-double mr-1"></i>Closed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ ucfirst($report->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    @if ($report->image_path)
                                        <img src="{{ asset('storage/' . $report->image_path) }}" 
                                             class="w-14 h-14 rounded-xl object-cover border-2 border-gray-200 shadow-sm" 
                                             alt="Report image">
                                    @else
                                        <div class="w-14 h-14 bg-gray-100 rounded-xl flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
  <a href="{{ route('resident.reports.show', $report->id) }}"
     class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors">
     View Details
  </a>
</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-16 text-center">
                                    <div class="flex flex-col items-center gap-4">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-inbox text-2xl text-gray-300"></i>
                                        </div>
                                        <div>
                                            <p class="text-gray-500 font-medium">No reports submitted yet</p>
                                            <p class="text-sm text-gray-400 mt-1">Submit your first waste report above to get started</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
                    
                    // Create map
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
                        tilt: 0 // Start with flat view, user can tilt if supported
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
    </script>
    
    @include('partials.chat-widget')
</body>
</html>