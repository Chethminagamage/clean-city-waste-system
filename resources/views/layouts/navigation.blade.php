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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 relative">
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
                    <a href="{{ route('resident.dashboard') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-medium flex items-center transition-colors">
                        Home 
                    </a>
                </div>
                <div class="relative group">
                    <a href="{{ route('resident.dashboard') }}#submit-report" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-medium flex items-center transition-colors">
                        Submit Reports 
                    </a>
                </div>
                <div class="relative group">
                    <a href="{{ route('resident.reports.index') }}" 
                    class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 font-medium flex items-center transition-colors">
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
                            <span class="ml-1 bg-green-600 dark:bg-green-500 text-white text-xs rounded-full px-1.5 py-0.5">{{ $newResponses }}</span>
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
                    <span class="absolute -top-1 -right-2 bg-red-600 dark:bg-red-500 text-white text-[10px] rounded-full px-1.5">
                        {{ $unread }}
                    </span>
                    @endif
                </a>
                @endauth

                <span class="text-sm font-medium text-gray-900 dark:text-white transition-colors">
                    {{ auth()->user()->first_name ?? '' }}
                </span>

                @auth
                <!-- Profile Dropdown -->
                <div class="relative group">
                    <a href="#" class="relative">
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
                    <div class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-100 dark:border-gray-700 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                        <div class="py-2">
                            <a href="{{ route('resident.profile.edit') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-green-50 dark:hover:bg-gray-700 hover:text-green-600 dark:hover:text-green-400 transition-colors duration-200">
                                <i class="fas fa-user-circle mr-2"></i>
                                My Profile
                            </a>
                            
                            <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <i class="fas fa-sign-out-alt mr-2"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endauth
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
        <div id="mobile-menu" class="lg:hidden hidden absolute top-full left-0 w-full bg-white/95 dark:bg-gray-900/95 backdrop-blur-lg border-t border-gray-200 dark:border-gray-700 shadow-2xl transition-all duration-300 z-50 rounded-b-2xl overflow-hidden max-h-[80vh] overflow-y-auto">
            <nav class="flex flex-col py-1">
                <a href="{{ route('resident.dashboard') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 font-medium px-4 py-2.5 transition-all duration-200 flex items-center border-b border-gray-100 dark:border-gray-800">
                    <div class="w-7 h-7 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-home text-green-600 dark:text-green-400 text-sm"></i>
                    </div>
                    <span class="font-medium text-sm">Home</span>
                </a>
                <a href="{{ route('resident.dashboard') }}#submit-report" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 font-medium px-4 py-2.5 transition-all duration-200 flex items-center border-b border-gray-100 dark:border-gray-800">
                    <div class="w-7 h-7 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-plus-circle text-blue-600 dark:text-blue-400 text-sm"></i>
                    </div>
                    <span class="font-medium text-sm">Submit Reports</span>
                </a>
                <a href="{{ route('resident.reports.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 font-medium px-4 py-2.5 transition-all duration-200 flex items-center border-b border-gray-100 dark:border-gray-800">
                    <div class="w-7 h-7 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-history text-purple-600 dark:text-purple-400 text-sm"></i>
                    </div>
                    <span class="font-medium text-sm">Report History</span>
                </a>
                <a href="{{ route('resident.schedule.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 font-medium px-4 py-2.5 transition-all duration-200 flex items-center border-b border-gray-100 dark:border-gray-800">
                    <div class="w-7 h-7 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-calendar text-orange-600 dark:text-orange-400 text-sm"></i>
                    </div>
                    <span class="font-medium text-sm">Collection Schedule</span>
                </a>
                <a href="{{ route('resident.gamification.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 font-medium px-4 py-2.5 transition-all duration-200 flex items-center border-b border-gray-100 dark:border-gray-800">
                    <div class="w-7 h-7 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-trophy text-yellow-600 dark:text-yellow-400 text-sm"></i>
                    </div>
                    <span class="font-medium text-sm">Eco Points</span>
                </a>
                <a href="{{ route('resident.gamification.rewards') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 font-medium px-4 py-2.5 transition-all duration-200 flex items-center border-b border-gray-100 dark:border-gray-800 ml-4">
                    <div class="w-6 h-6 bg-pink-100 dark:bg-pink-900/30 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-gift text-pink-600 dark:text-pink-400 text-xs"></i>
                    </div>
                    <span class="font-medium text-sm">Rewards Store</span>
                </a>
                <a href="{{ route('resident.feedback.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 font-medium px-4 py-2.5 transition-all duration-200 flex items-center border-b border-gray-100 dark:border-gray-800">
                    <div class="w-7 h-7 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-comment text-indigo-600 dark:text-indigo-400 text-sm"></i>
                    </div>
                    <div class="flex items-center justify-between flex-1">
                        <span class="font-medium text-sm">My Feedback</span>
                        @auth
                        @php 
                            $newResponses = auth()->user()->notifications()
                                ->where('data->type', 'feedback_response')
                                ->whereNull('read_at')
                                ->count(); 
                        @endphp
                        @if($newResponses)
                            <span class="bg-green-600 dark:bg-green-500 text-white text-xs rounded-full px-1.5 py-0.5 font-bold min-w-[16px] h-4 flex items-center justify-center">{{ $newResponses }}</span>
                        @endif
                        @endauth
                    </div>
                </a>
                
                <!-- Notifications for mobile -->
                @auth
                @php $unread = auth()->user()->unreadNotifications()->count(); @endphp
                <a href="{{ route('notifications.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 font-medium px-4 py-2.5 transition-all duration-200 flex items-center border-b border-gray-100 dark:border-gray-800">
                    <div class="w-7 h-7 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-bell text-red-600 dark:text-red-400 text-sm"></i>
                    </div>
                    <div class="flex items-center justify-between flex-1">
                        <span class="font-medium text-sm">Notifications</span>
                        @if($unread)
                            <span class="bg-red-600 dark:bg-red-500 text-white text-xs rounded-full px-1.5 py-0.5 font-bold min-w-[16px] h-4 flex items-center justify-center">{{ $unread }}</span>
                        @endif
                    </div>
                </a>
                @endauth
                
                <!-- User info for mobile -->
                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-800/50 mt-1">
                    <div class="flex items-center space-x-3 mb-3">
                        @if (auth()->user()->profile_image)
                            <img src="{{ auth()->user()->profile_image_url }}"
                                alt="Profile"
                                class="w-8 h-8 rounded-full object-cover border-2 border-green-200 dark:border-green-600 shadow-sm">
                        @else
                            <div class="w-8 h-8 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center shadow-sm">
                                <i class="fas fa-user text-white text-xs"></i>
                            </div>
                        @endif
                        <div>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ auth()->user()->first_name ?? '' }}
                            </span>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    
                    <div class="flex flex-col space-y-1">
                        <a href="{{ route('resident.profile.edit') }}" class="text-xs text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 flex items-center px-2 py-1.5 rounded-md hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all">
                            <i class="fas fa-user-circle mr-2 text-sm"></i>View Profile
                        </a>
                        
                        <!-- Logout for mobile -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left text-xs text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 flex items-center px-2 py-1.5 rounded-md hover:bg-red-50 dark:hover:bg-red-900/20 transition-all">
                                <i class="fas fa-sign-out-alt mr-2 text-sm"></i>Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</header>

<style>
    .glass-effect {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .dark .glass-effect {
        background: rgba(31, 41, 55, 0.95);
        border: 1px solid rgba(75, 85, 99, 0.2);
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

    /* Top contact bar responsive - HIDE ON MOBILE */
    @media (max-width: 768px) {
        .top-contact-bar {
            display: none !important;
        }
    }
    
    /* Mobile menu overlay fix */
    #mobile-menu {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        max-height: calc(100vh - 100px);
        overflow-y: auto;
    }
    
    .dark #mobile-menu {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
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
    document.addEventListener('DOMContentLoaded', function() {
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
            if (header && window.scrollY > 100) {
                header.classList.add('shadow-xl');
                header.classList.remove('shadow-lg');
            } else if (header) {
                header.classList.remove('shadow-xl');
                header.classList.add('shadow-lg');
            }
        });
    });
</script>
