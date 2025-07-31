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
                    <button class="w-8 h-8 flex items-center justify-center bg-gray-100 hover:bg-gray-200 transition duration-150 rounded-full">
                        <span class="sr-only">Notifications</span>
                        <i class="fas fa-bell w-4 h-4"></i>
                        @if(($notificationCount ?? 0) > 0)
                            <div class="absolute top-0 right-0 w-2.5 h-2.5 bg-red-500 border-2 border-white rounded-full"></div>
                        @endif
                    </button>
                </div>
                
                {{-- User --}}
                <div class="relative inline-flex">
                    <button class="inline-flex justify-center items-center group">
                        <img class="w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin User') }}&background=16a34a&color=fff" width="32" height="32" alt="User" />
                        <div class="flex items-center truncate">
                            <span class="truncate ml-2 text-sm font-medium text-gray-600 group-hover:text-gray-800">{{ auth()->user()->name ?? 'Admin User' }}</span>
                        </div>
                    </button>
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