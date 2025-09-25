<div class="flex">
    {{-- Sidebar backdrop (mobile) --}}
    <div class="fixed inset-0 bg-gray-900 bg-opacity-30 z-40 lg:hidden lg:z-auto transition-opacity duration-200 opacity-0 pointer-events-none" id="sidebar-backdrop"></div>
    
    {{-- Sidebar --}}
    <div id="sidebar" class="flex flex-col absolute z-40 left-0 top-0 lg:static lg:left-auto lg:top-auto lg:translate-x-0 h-screen overflow-y-scroll lg:overflow-y-auto no-scrollbar w-64 lg:w-20 lg:sidebar-expanded:!w-64 2xl:!w-64 shrink-0 bg-gradient-to-b from-green-800 to-green-900 p-4 transition-all duration-200 ease-in-out transform -translate-x-64">
        
        {{-- Sidebar header --}}
        <div class="flex items-center justify-between mb-10 pr-3 sm:px-2">
            {{-- Close button --}}
            <button class="lg:hidden text-white hover:text-gray-300" id="sidebar-close">
                <span class="sr-only">Close sidebar</span>
                <i class="fas fa-times w-6 h-6"></i>
            </button>
            {{-- Logo --}}
            <div class="flex items-center justify-center lg:justify-start w-full lg:w-auto">
                <div class="flex items-center justify-center w-8 h-8 bg-white rounded-lg shrink-0 p-1">
                    <img src="{{ asset('images/logo.png') }}" alt="Clean City Logo" class="w-6 h-6 object-contain">
                </div>
                <div class="ml-3 lg:sidebar-expanded:block 2xl:block hidden">
                    <h1 class="text-lg font-bold text-white">Clean City</h1>
                    <p class="text-green-200 text-xs">Admin Panel</p>
                </div>
            </div>
        </div>

        {{-- Links --}}
        <div class="space-y-8">
            {{-- Pages group --}}
            <div>
                <h3 class="text-xs uppercase text-green-300 font-semibold pl-3 lg:sidebar-expanded:block 2xl:block hidden mb-3">
                    <span>Pages</span>
                </h3>
                <ul class="space-y-1">
                    {{-- Dashboard --}}
                    <li class="px-3 py-2 rounded-sm {{ request()->routeIs('admin.dashboard.main') ? 'bg-white bg-opacity-20' : '' }}">
                        <a class="flex items-center text-white hover:text-green-200 truncate transition duration-150 {{ request()->routeIs('admin.dashboard.main') ? 'text-green-100' : 'text-green-200' }}" href="{{ route('admin.dashboard.main') ?? '#' }}">
                            <div class="flex items-center justify-center w-6 h-6 shrink-0">
                                <i class="fas fa-home text-lg"></i>
                            </div>
                            <span class="text-sm font-medium ml-3 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200 whitespace-nowrap">Dashboard</span>
                        </a>
                    </li>
                    {{-- Bin Reports --}}
                    <li class="px-3 py-2 rounded-sm {{ request()->routeIs('admin.binreports') ? 'bg-white bg-opacity-20' : '' }}">
                        <a class="flex items-center justify-between text-white hover:text-green-200 truncate transition duration-150 {{ request()->routeIs('admin.binreports') ? 'text-green-100' : 'text-green-200' }}" href="{{ route('admin.binreports') ?? '#' }}">
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-6 h-6 shrink-0">
                                    <i class="fas fa-clipboard-list text-lg"></i>
                                </div>
                                <span class="text-sm font-medium ml-3 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200 whitespace-nowrap">Bin Reports</span>
                            </div>
                            @php
                                $admin = \App\Models\Admin::first();
                                $newReportCount = $admin ? $admin->unreadNotifications()->where('data->type', 'new_waste_report')->count() : 0;
                            @endphp
                            @if($newReportCount > 0)
                                <div class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                    <span class="inline-flex items-center justify-center h-5 w-5 text-xs font-medium text-white bg-blue-500 rounded-full">{{ $newReportCount }}</span>
                                </div>
                            @endif
                        </a>
                    </li>
                    {{-- Collectors --}}
                    <li class="px-3 py-2 rounded-sm {{ request()->routeIs('admin.collectors') ? 'bg-white bg-opacity-20' : '' }}">
                        <a class="flex items-center text-white hover:text-green-200 truncate transition duration-150 {{ request()->routeIs('admin.collectors') ? 'text-green-100' : 'text-green-200' }}" href="{{ route('admin.collectors') ?? '#' }}">
                            <div class="flex items-center justify-center w-6 h-6 shrink-0">
                                <i class="fas fa-users text-lg"></i>
                            </div>
                            <span class="text-sm font-medium ml-3 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200 whitespace-nowrap">Collectors</span>
                        </a>
                    </li>
                    {{-- Users --}}
                    <li class="px-3 py-2 rounded-sm {{ request()->routeIs('admin.users') ? 'bg-white bg-opacity-20' : '' }}">
                        <a class="flex items-center text-white hover:text-green-200 truncate transition duration-150 {{ request()->routeIs('admin.users') ? 'text-green-100' : 'text-green-200' }}" href="{{ route('admin.users') ?? '#' }}">
                            <div class="flex items-center justify-center w-6 h-6 shrink-0">
                                <i class="fas fa-user-friends text-lg"></i>
                            </div>
                            <span class="text-sm font-medium ml-3 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200 whitespace-nowrap">Users</span>
                        </a>
                    </li>
                    {{-- Chat Management --}}
                    <li class="px-3 py-2 rounded-sm">
                        <a class="flex items-center text-white hover:text-green-200 truncate transition duration-150" href="https://dashboard.tawk.to/" target="_blank">
                            <div class="flex items-center justify-center w-6 h-6 shrink-0">
                                <i class="fas fa-comments text-lg"></i>
                            </div>
                            <span class="text-sm font-medium ml-3 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200 whitespace-nowrap">Chat Management</span>
                            <span class="flex items-center justify-center ml-2 w-4 h-4 text-xs">
                                
                            </span>
                        </a>
                    </li>
                    {{-- Alerts --}}
                    <li class="px-3 py-2 rounded-sm {{ request()->routeIs('admin.alerts') ? 'bg-white bg-opacity-20' : '' }}">
                        <a class="flex items-center justify-between text-white hover:text-green-200 truncate transition duration-150 {{ request()->routeIs('admin.alerts') ? 'text-green-100' : 'text-green-200' }}" href="{{ route('admin.alerts') ?? '#' }}">
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-6 h-6 shrink-0">
                                    <i class="fas fa-exclamation-triangle text-lg"></i>
                                </div>
                                <span class="text-sm font-medium ml-3 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200 whitespace-nowrap">Alerts</span>
                            </div>
                            @php
                                $admin = \App\Models\Admin::first();
                                $unreadCount = $admin ? $admin->unreadNotifications()->count() : 0;
                            @endphp
                            @if($unreadCount > 0)
                                <div class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                    <span class="inline-flex items-center justify-center h-5 w-5 text-xs font-medium text-white bg-red-500 rounded-full">{{ $unreadCount }}</span>
                                </div>
                            @endif
                        </a>
                    </li>
                    {{-- Collection Schedules --}}
                    <li class="px-3 py-2 rounded-sm {{ request()->routeIs('admin.schedules.*') ? 'bg-white bg-opacity-20' : '' }}">
                        <a class="flex items-center text-white hover:text-green-200 truncate transition duration-150 {{ request()->routeIs('admin.schedules.*') ? 'text-green-100' : 'text-green-200' }}" href="{{ route('admin.schedules.index') ?? '#' }}">
                            <div class="flex items-center justify-center w-6 h-6 shrink-0">
                                <i class="fas fa-calendar-alt text-lg"></i>
                            </div>
                            <span class="text-sm font-medium ml-3 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200 whitespace-nowrap">Collection Schedules</span>
                        </a>
                    </li>

                    {{-- Analytics --}}
                    <li class="px-3 py-2 rounded-sm {{ request()->routeIs('admin.analytics') ? 'bg-white bg-opacity-20' : '' }}">
                        <a class="flex items-center text-white hover:text-green-200 truncate transition duration-150 {{ request()->routeIs('admin.analytics') ? 'text-green-100' : 'text-green-200' }}" href="{{ route('admin.analytics') ?? '#' }}">
                            <div class="flex items-center justify-center w-6 h-6 shrink-0">
                                <i class="fas fa-chart-bar text-lg"></i>
                            </div>
                            <span class="text-sm font-medium ml-3 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200 whitespace-nowrap">Analytics</span>
                        </a>
                    </li>
                    {{-- Activity Audit Trail --}}
                    <li class="px-3 py-2 rounded-sm {{ request()->routeIs('admin.audit-trail.*') ? 'bg-white bg-opacity-20' : '' }}">
                        <a class="flex items-center justify-between text-white hover:text-green-200 truncate transition duration-150 {{ request()->routeIs('admin.audit-trail.*') ? 'text-green-100' : 'text-green-200' }}" href="{{ route('admin.audit-trail.index') ?? '#' }}">
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-6 h-6 shrink-0">
                                    <i class="fas fa-shield-alt text-lg"></i>
                                </div>
                                <span class="text-sm font-medium ml-3 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200 whitespace-nowrap">Activity Audit Trail</span>
                            </div>
                            @php
                                $criticalEventsCount = \App\Models\ActivityLog::where('severity', 'critical')
                                    ->where('created_at', '>=', now()->subDays(7))
                                    ->count();
                            @endphp
                            @if($criticalEventsCount > 0)
                                <div class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                    <span class="inline-flex items-center justify-center h-5 w-5 text-xs font-medium text-white bg-red-500 rounded-full">{{ $criticalEventsCount }}</span>
                                </div>
                            @endif
                        </a>
                    </li>
                    {{-- Feedback Management --}}
                    <li class="px-3 py-2 rounded-sm {{ request()->routeIs('admin.feedback.*') ? 'bg-white bg-opacity-20' : '' }}">
                        <a class="flex items-center text-white hover:text-green-200 truncate transition duration-150 {{ request()->routeIs('admin.feedback.*') ? 'text-green-100' : 'text-green-200' }}" href="{{ route('admin.feedback.index') ?? '#' }}">
                            <div class="flex items-center justify-center w-6 h-6 shrink-0">
                                <i class="fas fa-comments text-lg"></i>
                            </div>
                            <span class="text-sm font-medium ml-3 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200 whitespace-nowrap">Feedback</span>
                        </a>
                    </li>

                    {{-- My Profile --}}
                    <li class="px-3 py-2 rounded-sm {{ request()->routeIs('admin.profile.edit') ? 'bg-white bg-opacity-20' : '' }}">
                        <a class="flex items-center justify-between text-white hover:text-green-200 truncate transition duration-150 {{ request()->routeIs('admin.profile.edit') ? 'text-green-100' : 'text-green-200' }}" href="{{ route('admin.profile.edit') ?? '#' }}">
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-6 h-6 shrink-0">
                                    <i class="fas fa-user-cog text-lg"></i>
                                </div>
                                <span class="text-sm font-medium ml-3 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200 whitespace-nowrap">My Profile</span>
                            </div>
                            @php
                                $currentAdmin = auth('admin')->user();
                                $has2FA = $currentAdmin && $currentAdmin->two_factor_enabled;
                            @endphp
                            @if(!$has2FA)
                                <div class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                    <span class="inline-flex items-center justify-center h-5 w-5 text-xs font-medium text-white bg-yellow-500 rounded-full" title="Enable 2FA in profile settings">!</span>
                                </div>
                            @endif
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Expand / collapse button --}}
        <div class="pt-3 hidden lg:inline-flex 2xl:hidden justify-center mt-auto">
            <div class="px-3 py-2">
                <button id="sidebar-toggle" class="flex items-center justify-center w-6 h-6">
                    <span class="sr-only">Expand / collapse sidebar</span>
                    <i class="fas fa-angle-double-right text-white hover:text-green-200 text-lg sidebar-expanded:rotate-180 transition-transform duration-200"></i>
                </button>
            </div>
        </div>
    </div>
</div>