<!-- resources/views/collector/partials/navbar.blade.php -->
<nav class="bg-white shadow-lg border-b-4 border-orange-500">
    <div class="container mx-auto px-4 sm:px-6">
        <div class="flex justify-between items-center py-4">
            <!-- Logo and Brand -->
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-lg p-1">
                    <img src="{{ asset('images/logo.png') }}" alt="Clean City Logo" class="w-8 h-8 object-contain">
                </div>
                <div>
                    <h1 class="text-xl font-bold text-orange-600">Clean City</h1>
                    <p class="text-sm text-gray-600">Your Waste, Our Responsibility</p>
                </div>
            </div>

            <!-- Navigation Links (Desktop) -->
            <div class="hidden md:flex items-center space-x-6">
                <a href="{{ route('collector.dashboard') }}" 
                   class="flex items-center text-gray-700 hover:text-orange-600 font-medium transition-colors duration-200 {{ request()->routeIs('collector.dashboard') ? 'text-orange-600' : '' }}">
                    
                    Dashboard
                </a>
                <a href="{{ route('collector.reports.all') }}" 
                   class="flex items-center text-gray-700 hover:text-orange-600 font-medium transition-colors duration-200 {{ request()->routeIs('collector.reports.all') ? 'text-orange-600' : '' }}">
                    <i class="fas fa-list-alt mr-2"></i>
                    All Reports
                </a>
                <a href="{{ route('collector.reports.completed') }}" 
                   class="flex items-center text-gray-700 hover:text-orange-600 font-medium transition-colors duration-200 {{ request()->routeIs('collector.reports.completed') ? 'text-orange-600' : '' }}">
                    <i class="fas fa-history mr-2"></i>
                    History
                </a>
            </div>

            <!-- User Menu -->
            <div class="flex items-center space-x-4">
                <!-- Notification Icon -->
                <button class="relative text-gray-600 hover:text-orange-600 transition-colors duration-200">
                    <i class="fas fa-bell text-xl"></i>
                    <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full"></span>
                </button>

                <!-- User Profile Dropdown -->
                <div class="relative group">
                    <button class="flex items-center space-x-2 text-gray-700 hover:text-orange-600 focus:outline-none transition-colors duration-200">
                        <div class="w-8 h-8 rounded-full overflow-hidden border-2 border-orange-200">
                            @if(Auth::user()->profile_image)
                                <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" 
                                     alt="Profile Picture" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-orange-100 flex items-center justify-center">
                                    <i class="fas fa-user text-orange-600 text-sm"></i>
                                </div>
                            @endif
                        </div>
                        <span class="hidden sm:block font-medium">{{ Auth::user()->name ?? 'Collector' }}</span>
                        <i class="fas fa-chevron-down text-sm"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                        <div class="py-2">
                            <a href="{{ route('collector.profile') }}" class="block px-4 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition-colors duration-200">
                                <i class="fas fa-user-circle mr-2"></i>
                                My Profile
                            </a>
                            
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('collector.logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 transition-colors duration-200">
                                    <i class="fas fa-sign-out-alt mr-2"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <button class="md:hidden text-gray-600 hover:text-orange-600 focus:outline-none transition-colors duration-200" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="md:hidden bg-orange-50 border-t border-orange-200 hidden">
        <div class="px-4 py-3 space-y-2">
            <a href="{{ route('collector.dashboard') }}" 
               class="block py-2 px-3 rounded-lg text-gray-700 hover:bg-orange-100 hover:text-orange-600 font-medium transition-colors duration-200 {{ request()->routeIs('collector.dashboard') ? 'bg-orange-100 text-orange-600' : '' }}">
                <i class="fas fa-tachometer-alt mr-2"></i>
                Dashboard
            </a>
            <a href="{{ route('collector.reports.all') }}" 
               class="block py-2 px-3 rounded-lg text-gray-700 hover:bg-orange-100 hover:text-orange-600 font-medium transition-colors duration-200 {{ request()->routeIs('collector.reports.all') ? 'bg-orange-100 text-orange-600' : '' }}">
                <i class="fas fa-list-alt mr-2"></i>
                All Reports
            </a>
            <a href="{{ route('collector.reports.completed') }}" 
               class="block py-2 px-3 rounded-lg text-gray-700 hover:bg-orange-100 hover:text-orange-600 font-medium transition-colors duration-200 {{ request()->routeIs('collector.reports.completed') ? 'bg-orange-100 text-orange-600' : '' }}">
                <i class="fas fa-history mr-2"></i>
                History
            </a>
            <a href="{{ route('collector.profile') }}" 
               class="block py-2 px-3 rounded-lg text-gray-700 hover:bg-orange-100 hover:text-orange-600 font-medium transition-colors duration-200 {{ request()->routeIs('collector.profile') ? 'bg-orange-100 text-orange-600' : '' }}">
                <i class="fas fa-user-circle mr-2"></i>
                My Profile
            </a>
            <form method="POST" action="{{ route('collector.logout') }}">
                @csrf
                <button type="submit" class="w-full text-left py-2 px-3 rounded-lg text-red-600 hover:bg-red-50 font-medium transition-colors duration-200">
                    <i class="fas fa-sign-out-alt mr-2"></i>
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>

<script>
    function toggleMobileMenu() {
        const menu = document.getElementById('mobileMenu');
        menu.classList.toggle('hidden');
    }

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        const menu = document.getElementById('mobileMenu');
        const button = event.target.closest('button[onclick="toggleMobileMenu()"]');
        
        if (!button && !menu.contains(event.target)) {
            menu.classList.add('hidden');
        }
    });
</script>