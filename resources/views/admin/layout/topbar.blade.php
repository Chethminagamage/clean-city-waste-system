<header class="sticky top-0 bg-white border-b border-gray-200 z-30">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 -mb-px">
            
            {{-- Header: Left side --}}
            <div class="flex">
                {{-- Hamburger button --}}
                <button class="text-gray-500 hover:text-gray-600 lg:hidden" id="sidebar-toggle">
                    <span class="sr-only">Open sidebar</span>
                    <i class="fas fa-bars w-6 h-6"></i>
                </button>
            </div>

            {{-- Header: Right side --}}
            <div class="flex items-center space-x-3">
                {{-- Search --}}
                <div class="relative">
                    <label for="search" class="sr-only">Search</label>
                    <input id="search" class="form-input pl-9 bg-white border-gray-300 hover:border-gray-400 focus:border-green-500 w-full" type="search" placeholder="Search…" />
                    <button class="absolute inset-0 right-auto group" type="submit" aria-label="Search">
                        <i class="fas fa-search w-4 h-4 shrink-0 fill-current text-gray-400 group-hover:text-gray-500 ml-3 mr-2"></i>
                    </button>
                </div>
                
                {{-- Notifications --}}
                <div class="relative inline-flex">
                    <a href="{{ route('admin.alerts') }}" class="w-8 h-8 flex items-center justify-center bg-gray-100 hover:bg-gray-200 transition duration-150 rounded-full">
                        <span class="sr-only">Notifications</span>
                        <i class="fas fa-bell w-4 h-4"></i>
                        @php
                            $admin = \App\Models\Admin::first();
                            $notificationCount = 0;
                            $urgentCount = 0;
                            $newReportCount = 0;
                            
                            if ($admin) {
                                $notificationCount = $admin->unreadNotifications()->count();
                                $urgentCount = $admin->unreadNotifications()
                                    ->where('data->type', 'urgent_bin_report')
                                    ->count();
                                $newReportCount = $admin->unreadNotifications()
                                    ->where('data->type', 'new_waste_report')
                                    ->count();
                            }
                        @endphp
                        @if($notificationCount > 0)
                            <div class="absolute top-0 right-0 w-2.5 h-2.5 bg-red-500 border-2 border-white rounded-full"></div>
                            @if($newReportCount > 0)
                                <div class="absolute -bottom-1 -right-1 bg-blue-500 text-white text-xs rounded-full px-1.5 py-0.5 text-[10px] border border-white">
                                    {{ $newReportCount }}
                                </div>
                            @endif
                        @endif
                    </a>
                </div>
                
                {{-- User --}}
                <div class="relative inline-flex group">
                    <button class="inline-flex justify-center items-center">
                        @if(auth('admin')->user()->profile_photo)
                            <img class="w-8 h-8 rounded-full object-cover" 
                                 src="{{ Storage::url(auth('admin')->user()->profile_photo) }}" 
                                 alt="Admin Profile" />
                        @else
                            <img class="w-8 h-8 rounded-full" 
                                 src="https://ui-avatars.com/api/?name={{ urlencode(auth('admin')->user()->name ?? 'Admin User') }}&background=16a34a&color=fff" 
                                 alt="Admin Profile" />
                        @endif
                        <div class="flex items-center truncate">
                            <span class="truncate ml-2 text-sm font-medium text-gray-600 group-hover:text-gray-800">{{ auth('admin')->user()->name ?? 'Admin User' }}</span>
                            <i class="fas fa-chevron-down ml-1 text-xs text-gray-400 group-hover:text-gray-600"></i>
                        </div>
                    </button>
                    
                    <!-- Admin Dropdown Menu -->
                    <div class="absolute right-0 top-full mt-1 w-48 bg-white rounded-lg shadow-lg border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                        <div class="py-2">
                            <a href="{{ route('admin.profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                <i class="fas fa-user mr-2"></i>
                                My Profile
                            </a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('admin.logout') }}">
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
        </div>
    </div>
</header>

{{-- Page header --}}
<div class="sm:flex sm:justify-between sm:items-center mb-8 bg-white border-b border-gray-200">
    <div class="px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl md:text-3xl text-gray-800 font-bold">@yield('page-title', 'Dashboard')</h1>
            <p class="text-gray-600">@yield('page-description', 'Welcome back! Here\'s what\'s happening with your waste management system.')</p>
        </div>
    </div>
</div>