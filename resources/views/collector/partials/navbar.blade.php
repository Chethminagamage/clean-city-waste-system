<!-- resources/views/collector/partials/navbar.blade.php -->
<nav class="bg-white dark:bg-gray-800 shadow-lg border-b-4 border-orange-500 dark:border-orange-600 transition-colors duration-300">
    <div class="container mx-auto px-4 sm:px-6">
        <div class="flex justify-between items-center py-4">
            <!-- Logo and Brand -->
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 bg-white dark:bg-gray-700 rounded-xl flex items-center justify-center shadow-lg p-1 transition-colors duration-300">
                    <img src="{{ asset('images/logo.png') }}" alt="Clean City Logo" class="w-8 h-8 object-contain">
                </div>
                <div>
                    <h1 class="text-xl font-bold text-orange-600 dark:text-orange-400 transition-colors duration-300">Clean City</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 transition-colors duration-300">Your Waste, Our Responsibility</p>
                </div>
            </div>

            <!-- Navigation Links (Desktop) -->
            <div class="hidden md:flex items-center space-x-6">
                <a href="{{ route('collector.dashboard') }}" 
                   class="flex items-center text-gray-700 dark:text-gray-300 hover:text-orange-600 dark:hover:text-orange-400 font-medium transition-colors duration-200 {{ request()->routeIs('collector.dashboard') ? 'text-orange-600 dark:text-orange-400' : '' }}">
                    
                    Dashboard
                </a>
                <a href="{{ route('collector.reports.all') }}" 
                   class="flex items-center text-gray-700 dark:text-gray-300 hover:text-orange-600 dark:hover:text-orange-400 font-medium transition-colors duration-200 {{ request()->routeIs('collector.reports.all') ? 'text-orange-600 dark:text-orange-400' : '' }}">
                    <i class="fas fa-list-alt mr-2"></i>
                    All Reports
                </a>
                <a href="{{ route('collector.reports.completed') }}" 
                   class="flex items-center text-gray-700 dark:text-gray-300 hover:text-orange-600 dark:hover:text-orange-400 font-medium transition-colors duration-200 {{ request()->routeIs('collector.reports.completed') ? 'text-orange-600 dark:text-orange-400' : '' }}">
                    <i class="fas fa-history mr-2"></i>
                    History
                </a>
            </div>

            <!-- User Menu -->
            <div class="flex items-center space-x-4">
                <!-- Theme Toggle Button -->
                <button id="collector-theme-toggle" 
                        class="text-gray-600 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-400 transition-colors duration-200 p-2 rounded-lg hover:bg-orange-50 dark:hover:bg-orange-900/30"
                        title="Toggle Theme">
                    <i id="collector-theme-icon" class="fas fa-moon text-lg"></i>
                </button>

                <!-- Notification Dropdown -->
                <div class="relative" x-data="notificationDropdown()">
                    <button @click="toggleDropdown()" 
                            class="relative text-gray-600 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-400 transition-colors duration-200 p-2 rounded-lg hover:bg-orange-50 dark:hover:bg-orange-900/30">
                        <i class="fas fa-bell text-xl"></i>
                        <!-- Notification Badge -->
                        <span x-show="unreadCount > 0" 
                              x-text="unreadCount > 9 ? '9+' : unreadCount"
                              class="absolute -top-1 -right-1 min-w-[18px] h-[18px] bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-medium"></span>
                    </button>

                    <!-- Notification Dropdown -->
                    <div x-show="isOpen" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-1 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-1 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         @click.away="isOpen = false"
                         class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50 max-h-96 overflow-hidden transition-colors duration-300">
                        
                        <!-- Header -->
                        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Notifications</h3>
                            <button @click="markAllAsRead()" 
                                    x-show="unreadCount > 0"
                                    class="text-xs text-orange-600 dark:text-orange-400 hover:text-orange-700 dark:hover:text-orange-300 font-medium transition-colors duration-200">
                                Mark all as read
                            </button>
                        </div>

                        <!-- Notifications List -->
                        <div class="max-h-64 overflow-y-auto">
                            <template x-if="notifications.length === 0">
                                <div class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-bell-slash text-2xl mb-2"></i>
                                    <p class="text-sm">No notifications yet</p>
                                </div>
                            </template>

                            <template x-for="notification in notifications" :key="notification.id">
                                <div @click="markAsRead(notification.id, notification.url)"
                                     class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors duration-150"
                                     :class="!notification.read_at ? 'bg-orange-50 dark:bg-orange-900/30 border-l-4 border-l-orange-500' : ''">
                                    
                                    <div class="flex items-start space-x-3">
                                        <!-- Icon based on notification type -->
                                        <div class="flex-shrink-0 mt-0.5">
                                            <template x-if="notification.type === 'report_assigned'">
                                                <div class="w-8 h-8 bg-orange-100 dark:bg-orange-900/50 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-clipboard-list text-orange-600 dark:text-orange-400 text-sm"></i>
                                                </div>
                                            </template>
                                            <template x-if="notification.type !== 'report_assigned'">
                                                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/50 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-info text-blue-600 dark:text-blue-400 text-sm"></i>
                                                </div>
                                            </template>
                                        </div>

                                        <!-- Content -->
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm text-gray-900 dark:text-gray-100 font-medium" x-text="notification.message"></p>
                                            <template x-if="notification.reference">
                                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1" x-text="notification.reference"></p>
                                            </template>
                                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1" x-text="notification.created_at"></p>
                                        </div>

                                        <!-- Urgency indicator -->
                                        <template x-if="notification.urgency === 'urgent'">
                                            <div class="flex-shrink-0">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300">
                                                    Urgent
                                                </span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Footer -->
                        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                            <a href="{{ route('collector.notifications.index') }}" 
                               class="text-sm text-orange-600 dark:text-orange-400 hover:text-orange-700 dark:hover:text-orange-300 font-medium transition-colors duration-200">
                                View all notifications
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User Profile Dropdown -->
                <div class="relative group">
                    <button class="flex items-center space-x-2 text-gray-700 dark:text-gray-300 hover:text-orange-600 dark:hover:text-orange-400 focus:outline-none transition-colors duration-200">
                        <div class="w-8 h-8 rounded-full overflow-hidden border-2 border-orange-200 dark:border-orange-600">
                            @if(Auth::user()->profile_image)
                                <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" 
                                     alt="Profile Picture" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-orange-100 dark:bg-orange-900/50 flex items-center justify-center">
                                    <i class="fas fa-user text-orange-600 dark:text-orange-400 text-sm"></i>
                                </div>
                            @endif
                        </div>
                        <span class="hidden sm:block font-medium">{{ Auth::user()->name ?? 'Collector' }}</span>
                        <i class="fas fa-chevron-down text-sm"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                        <div class="py-2">
                            <a href="{{ route('collector.profile') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-orange-50 dark:hover:bg-orange-900/30 hover:text-orange-600 dark:hover:text-orange-400 transition-colors duration-200">
                                <i class="fas fa-user-circle mr-2"></i>
                                My Profile
                            </a>
                            
                            <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
                            <form method="POST" action="{{ route('collector.logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors duration-200">
                                    <i class="fas fa-sign-out-alt mr-2"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <button class="md:hidden text-gray-600 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-400 focus:outline-none transition-colors duration-200" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="md:hidden bg-orange-50 dark:bg-gray-700 border-t border-orange-200 dark:border-gray-600 hidden transition-colors duration-300">
        <div class="px-4 py-3 space-y-2">
            <a href="{{ route('collector.dashboard') }}" 
               class="block py-2 px-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-orange-100 dark:hover:bg-orange-900/50 hover:text-orange-600 dark:hover:text-orange-400 font-medium transition-colors duration-200 {{ request()->routeIs('collector.dashboard') ? 'bg-orange-100 dark:bg-orange-900/50 text-orange-600 dark:text-orange-400' : '' }}">
                <i class="fas fa-tachometer-alt mr-2"></i>
                Dashboard
            </a>
            <a href="{{ route('collector.reports.all') }}" 
               class="block py-2 px-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-orange-100 dark:hover:bg-orange-900/50 hover:text-orange-600 dark:hover:text-orange-400 font-medium transition-colors duration-200 {{ request()->routeIs('collector.reports.all') ? 'bg-orange-100 dark:bg-orange-900/50 text-orange-600 dark:text-orange-400' : '' }}">
                <i class="fas fa-list-alt mr-2"></i>
                All Reports
            </a>
            <a href="{{ route('collector.reports.completed') }}" 
               class="block py-2 px-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-orange-100 dark:hover:bg-orange-900/50 hover:text-orange-600 dark:hover:text-orange-400 font-medium transition-colors duration-200 {{ request()->routeIs('collector.reports.completed') ? 'bg-orange-100 dark:bg-orange-900/50 text-orange-600 dark:text-orange-400' : '' }}">
                <i class="fas fa-history mr-2"></i>
                History
            </a>
            
            <!-- Mobile Theme Toggle -->
            <button id="mobile-collector-theme-toggle"
                    class="w-full text-left py-2 px-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-orange-100 dark:hover:bg-orange-900/50 hover:text-orange-600 dark:hover:text-orange-400 font-medium transition-colors duration-200">
                <i id="mobile-collector-theme-icon" class="fas fa-moon mr-2"></i>
                <span id="mobile-collector-theme-text">Dark Mode</span>
            </button>
            
            <a href="{{ route('collector.profile') }}" 
               class="block py-2 px-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-orange-100 dark:hover:bg-orange-900/50 hover:text-orange-600 dark:hover:text-orange-400 font-medium transition-colors duration-200 {{ request()->routeIs('collector.profile') ? 'bg-orange-100 dark:bg-orange-900/50 text-orange-600 dark:text-orange-400' : '' }}">
                <i class="fas fa-user-circle mr-2"></i>
                My Profile
            </a>
            <form method="POST" action="{{ route('collector.logout') }}">
                @csrf
                <button type="submit" class="w-full text-left py-2 px-3 rounded-lg text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 font-medium transition-colors duration-200">
                    <i class="fas fa-sign-out-alt mr-2"></i>
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>

<script>
    // Notification Dropdown Component
    function notificationDropdown() {
        return {
            isOpen: false,
            notifications: [],
            unreadCount: 0,

            init() {
                this.fetchNotifications();
                this.fetchUnreadCount();
                
                // Refresh notifications every 30 seconds
                setInterval(() => {
                    this.fetchNotifications();
                    this.fetchUnreadCount();
                }, 30000);
            },

            toggleDropdown() {
                this.isOpen = !this.isOpen;
                if (this.isOpen) {
                    this.fetchNotifications();
                }
            },

            async fetchNotifications() {
                try {
                    const response = await fetch('{{ route("collector.notifications.recent") }}', {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    });
                    const data = await response.json();
                    this.notifications = data.notifications;
                } catch (error) {
                    console.error('Error fetching notifications:', error);
                }
            },

            async fetchUnreadCount() {
                try {
                    const response = await fetch('{{ route("collector.notifications.unread-count") }}', {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    });
                    const data = await response.json();
                    this.unreadCount = data.count;
                } catch (error) {
                    console.error('Error fetching unread count:', error);
                }
            },

            async markAsRead(notificationId, url) {
                try {
                    const response = await fetch(`{{ route("collector.notifications.mark-read", ":id") }}`.replace(':id', notificationId), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    });
                    
                    const data = await response.json();
                    if (data.success) {
                        this.fetchNotifications();
                        this.fetchUnreadCount();
                        this.isOpen = false;
                        
                        if (url && url !== '#') {
                            window.location.href = url;
                        }
                    }
                } catch (error) {
                    console.error('Error marking notification as read:', error);
                }
            },

            async markAllAsRead() {
                try {
                    const response = await fetch('{{ route("collector.notifications.mark-all-read") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    });
                    
                    const data = await response.json();
                    if (data.success) {
                        this.fetchNotifications();
                        this.fetchUnreadCount();
                    }
                } catch (error) {
                    console.error('Error marking all notifications as read:', error);
                }
            }
        }
    }

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