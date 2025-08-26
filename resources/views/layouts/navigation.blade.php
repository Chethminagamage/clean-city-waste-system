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
                    <a href="{{ route('resident.dashboard') }}" class="text-gray-700 hover:text-green-600 font-medium flex items-center transition-colors">
                        Home 
                    </a>
                </div>
                <div class="relative group">
                    <a href="{{ route('resident.dashboard') }}#submit-report" class="text-gray-700 hover:text-green-600 font-medium flex items-center transition-colors">
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

                @auth
                <a href="{{ route('resident.profile.edit') }}" class="relative group">
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
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" class="lg:hidden text-gray-700 p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>

        <!-- Mobile Navigation Menu -->
        <div id="mobile-menu" class="lg:hidden hidden bg-white border-t border-gray-200 py-4 shadow-lg">
            <nav class="flex flex-col space-y-4">
                <a href="{{ route('resident.dashboard') }}" class="text-gray-700 hover:text-green-600 font-medium px-4 py-2 transition-colors">Home</a>
                <a href="{{ route('resident.dashboard') }}#submit-report" class="text-gray-700 hover:text-green-600 font-medium px-4 py-2 transition-colors">Submit Reports</a>
                <a href="{{ route('resident.reports.index') }}" class="text-gray-700 hover:text-green-600 font-medium px-4 py-2 transition-colors">Report History</a>
                <a href="{{ route('resident.schedule.index') }}" class="text-gray-700 hover:text-green-600 font-medium px-4 py-2 transition-colors">Collection Schedule</a>
                
                <!-- User info for mobile -->
                <div class="px-4 py-2 border-t border-gray-200 mt-2">
                    <div class="flex items-center space-x-3 mb-2">
                        @if (auth()->user()->profile_image)
                            <img src="{{ auth()->user()->profile_image_url }}"
                                alt="Profile"
                                class="w-8 h-8 rounded-full object-cover border-2 border-gray-200">
                        @else
                            <div class="w-8 h-8 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-white text-xs"></i>
                            </div>
                        @endif
                        <span class="text-sm font-medium text-gray-900">
                            {{ auth()->user()->first_name ?? '' }}
                        </span>
                    </div>
                    <a href="{{ route('resident.profile.edit') }}" class="text-sm text-blue-600 hover:text-blue-800 mt-1 inline-block">View Profile</a>
                    
                    <!-- Logout for mobile -->
                    <form method="POST" action="{{ route('logout') }}" class="mt-2">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 hover:text-red-800">
                            Log Out
                        </button>
                    </form>
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
