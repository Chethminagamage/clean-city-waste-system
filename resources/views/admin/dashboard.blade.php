@extends('admin.layout.app')
@section('content')
{{-- Stats Cards --}}
<div class="grid grid-cols-12 gap-6 mb-8">
    {{-- Reports Submitted --}}
    <div class="flex flex-col col-span-full sm:col-span-6 xl:col-span-3 bg-white shadow-lg rounded-sm border border-gray-200">
        <div class="px-5 pt-5">
            <header class="flex justify-between items-start mb-2">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 bg-green-500">
                        <i class="fas fa-clipboard-list text-white"></i>
                    </div>
                </div>
            </header>
            <h2 class="text-lg font-semibold text-gray-800 mb-2">Reports Submitted</h2>
            <div class="text-xs font-semibold text-gray-400 uppercase mb-1">Total</div>
            <div class="flex items-start">
                <div class="text-3xl font-bold text-gray-800 mr-2">{{ $totalReports }}</div>
                <div class="text-sm font-semibold text-white px-1.5 bg-green-500 rounded-full">+{{ $pendingReports }} Pending</div>
            </div>
        </div>
        <div class="grow flex flex-col justify-center">
            <div class="flex items-center justify-between text-green-500 text-sm font-semibold px-5 py-3">
                <div>This month</div>
            </div>
        </div>
    </div>

    {{-- Collections Done --}}
    <div class="flex flex-col col-span-full sm:col-span-6 xl:col-span-3 bg-white shadow-lg rounded-sm border border-gray-200">
        <div class="px-5 pt-5">
            <header class="flex justify-between items-start mb-2">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 bg-blue-500">
                        <i class="fas fa-truck text-white"></i>
                    </div>
                </div>
            </header>
            <h2 class="text-lg font-semibold text-gray-800 mb-2">Collections Done</h2>
            <div class="text-xs font-semibold text-gray-400 uppercase mb-1">Total</div>
            <div class="flex items-start">
                <div class="text-3xl font-bold text-gray-800 mr-2">{{ $completedReports }}</div>
                <div class="text-sm font-semibold text-white px-1.5 bg-blue-500 rounded-full">Completed</div>
            </div>
        </div>
        <div class="grow flex flex-col justify-center">
            <div class="flex items-center justify-between text-blue-500 text-sm font-semibold px-5 py-3">
                <div>This week</div>
            </div>
        </div>
    </div>

    {{-- Active Pickups --}}
    <div class="flex flex-col col-span-full sm:col-span-6 xl:col-span-3 bg-white shadow-lg rounded-sm border border-gray-200">
        <div class="px-5 pt-5">
            <header class="flex justify-between items-start mb-2">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 bg-yellow-500">
                        <i class="fas fa-clock text-white"></i>
                    </div>
                </div>
            </header>
            <h2 class="text-lg font-semibold text-gray-800 mb-2">Active Pickups</h2>
            <div class="text-xs font-semibold text-gray-400 uppercase mb-1">Current</div>
            <div class="flex items-start">
                <div class="text-3xl font-bold text-gray-800 mr-2">12</div>
                <div class="text-sm font-semibold text-white px-1.5 bg-yellow-500 rounded-full">5%</div>
            </div>
        </div>
        <div class="grow flex flex-col justify-center">
            <div class="flex items-center justify-between text-yellow-500 text-sm font-semibold px-5 py-3">
                <div>This week</div>
            </div>
        </div>
    </div>

    {{-- Alerts --}}
    <div class="flex flex-col col-span-full sm:col-span-6 xl:col-span-3 bg-white shadow-lg rounded-sm border border-gray-200">
        <div class="px-5 pt-5">
            <header class="flex justify-between items-start mb-2">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 bg-red-500">
                        <i class="fas fa-exclamation-triangle text-white"></i>
                    </div>
                </div>
            </header>
            <h2 class="text-lg font-semibold text-gray-800 mb-2">Urgent Alerts</h2>
            <div class="text-xs font-semibold text-gray-400 uppercase mb-1">Active</div>
            <div class="flex items-start">
                <div class="text-3xl font-bold text-gray-800 mr-2">{{ $urgentNotifications->count() }}</div>
                @if($urgentReports > 0)
                    <div class="text-sm font-semibold text-white px-1.5 bg-red-500 rounded-full">Critical: {{ $urgentReports }}</div>
                @endif
            </div>
        </div>
        <div class="grow flex flex-col justify-center">
            <div class="flex items-center justify-between text-red-500 text-sm font-semibold px-5 py-3">
                @if($urgentNotifications->count() > 0)
                    <a href="{{ route('admin.alerts') }}" class="hover:underline">View Alerts</a>
                @else
                    <div>No urgent alerts</div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Charts Row --}}
<div class="grid grid-cols-12 gap-6 mb-8">
    {{-- Weekly Collection Chart --}}
    <div class="flex flex-col col-span-full xl:col-span-8 bg-white shadow-lg rounded-sm border border-gray-200">
        <header class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800">Weekly Collection Overview</h2>
            <div class="flex space-x-2">
                <button class="btn-sm bg-green-500 hover:bg-green-600 text-white">7 Days</button>
                <button class="btn-sm border-gray-200 hover:border-gray-300 text-gray-600">30 Days</button>
            </div>
        </header>
        <div class="px-5 py-3">
            <div class="flex items-start">
                <div class="text-3xl font-bold text-gray-800 mr-2">25</div>
                <div class="text-sm font-semibold text-white px-1.5 bg-green-500 rounded-full">+15%</div>
            </div>
        </div>
        {{-- Chart --}}
        <div class="grow">
            <canvas id="collections-chart" width="800" height="300"></canvas>
        </div>
    </div>

    {{-- Recent Pickups --}}
    <div class="flex flex-col col-span-full xl:col-span-4 bg-white shadow-lg rounded-sm border border-gray-200">
        <header class="px-5 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">Recent Pickups</h2>
        </header>
        <div class="p-3">
            {{-- Pickup Item --}}
            <div class="flex items-center py-2">
                <div class="mr-3">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check text-green-500 text-sm"></i>
                    </div>
                </div>
                <div class="grow">
                    <div class="text-sm font-medium text-gray-800">Colombo 07 - Main Street</div>
                    <div class="text-xs text-gray-500">Completed 2 hours ago</div>
                </div>
            </div>
            {{-- Pickup Item --}}
            <div class="flex items-center py-2">
                <div class="mr-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-truck text-blue-500 text-sm"></i>
                    </div>
                </div>
                <div class="grow">
                    <div class="text-sm font-medium text-gray-800">Colombo 03 - Beach Road</div>
                    <div class="text-xs text-gray-500">In progress</div>
                </div>
            </div>
            {{-- Pickup Item --}}
            <div class="flex items-center py-2">
                <div class="mr-3">
                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-500 text-sm"></i>
                    </div>
                </div>
                <div class="grow">
                    <div class="text-sm font-medium text-gray-800">Colombo 05 - Hospital Road</div>
                    <div class="text-xs text-gray-500">Scheduled for 3:00 PM</div>
                </div>
            </div>
        </div>
        <div class="px-5 py-3 border-t border-gray-100">
            <button class="btn-sm w-full bg-white border-gray-200 hover:bg-gray-50 text-gray-600">View All Pickups</button>
        </div>
    </div>
</div>

{{-- Urgent Reports Section --}}
@if($recentUrgentReports->count() > 0)
<div class="col-span-full bg-white shadow-lg rounded-sm border border-gray-200 mb-8">
    <header class="px-5 py-4 border-b border-gray-100 bg-red-50">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-gray-800 flex items-center space-x-2">
                <i class="fas fa-exclamation-triangle text-red-500"></i>
                <span>🚨 Urgent Bin Reports</span>
            </h2>
            <a href="{{ route('admin.alerts') }}" class="text-red-600 hover:text-red-800 text-sm font-medium">View All</a>
        </div>
    </header>
    <div class="p-5">
        <div class="space-y-4">
            @foreach($recentUrgentReports as $report)
            <div class="flex items-center justify-between p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-trash text-white text-lg"></i>
                    </div>
                    <div>
                        <div class="font-semibold text-gray-900">{{ $report->reference_code }}</div>
                        <div class="text-sm text-gray-600">📍 {{ $report->location }}</div>
                        <div class="text-sm text-gray-500">
                            Reported by: <span class="font-medium">{{ $report->resident->name ?? 'Unknown' }}</span>
                            • {{ $report->created_at->diffForHumans() }}
                        </div>
                        @if($report->is_urgent)
                        <div class="text-sm text-red-700 italic mt-1">🚨 Urgent Report</div>
                        @endif
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="px-3 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">
                        {{ ucfirst($report->status) }}
                    </span>
                    <a href="{{ route('admin.reports.show', $report->id) }}" 
                       class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        Handle
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- Quick Actions --}}
<div class="col-span-full xl:col-span-6 bg-white shadow-lg rounded-sm border border-gray-200 mb-8">
    <header class="px-5 py-4 border-b border-gray-100">
        <h2 class="font-semibold text-gray-800">Quick Actions</h2>
    </header>
    <div class="p-5">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Bin Reports -->
            <a href="{{ route('admin.binreports') }}" 
               class="bg-green-50 hover:bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm font-medium flex items-center justify-center space-x-2 transition-colors">
                <i class="fas fa-clipboard-list"></i>
                <span>Bin Reports</span>
            </a>

            <!-- Collectors -->
            <a href="{{ route('admin.collectors') }}" 
               class="bg-blue-50 hover:bg-blue-100 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg text-sm font-medium flex items-center justify-center space-x-2 transition-colors">
                <i class="fas fa-users"></i>
                <span>Collectors</span>
            </a>

            <!-- Users -->
            <a href="{{ route('admin.users') }}" 
               class="bg-purple-50 hover:bg-purple-100 border border-purple-200 text-purple-700 px-4 py-3 rounded-lg text-sm font-medium flex items-center justify-center space-x-2 transition-colors">
                <i class="fas fa-user-friends"></i>
                <span>Users</span>
            </a>

            <!-- Collection Schedules -->
            <a href="{{ route('admin.schedules.index') }}" 
               class="bg-orange-50 hover:bg-orange-100 border border-orange-200 text-orange-700 px-4 py-3 rounded-lg text-sm font-medium flex items-center justify-center space-x-2 transition-colors">
                <i class="fas fa-calendar-alt"></i>
                <span>Schedules</span>
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart
    const ctx = document.getElementById('collections-chart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Collections',
                    data: [12, 19, 8, 15, 22, 18, 25],
                    borderColor: '#22c55e',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#22c55e',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f3f4f6',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#6b7280',
                            font: {
                                size: 12
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#6b7280',
                            font: {
                                size: 12
                            }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    }
});

</script>

<style>
/* Custom styles */
.btn-sm {
    @apply inline-flex items-center justify-center text-sm font-medium leading-5 rounded border focus:outline-none px-3 py-1.5;
}

.form-input {
    @apply block w-full text-sm placeholder-gray-400 bg-white border border-gray-300 appearance-none rounded-md py-2 px-3 focus:outline-none focus:ring-0;
}

.no-scrollbar::-webkit-scrollbar {
    display: none;
}

.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

/* Chart container */
#collections-chart {
    height: 300px !important;
}

/* Animation improvements */
.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
}
</style>
@endsection