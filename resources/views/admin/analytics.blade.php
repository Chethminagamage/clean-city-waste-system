@extends('admin.layout.app')

@section('title', 'Collector Efficiency Analytics')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Collector Efficiency Analytics</h1>
            <p class="text-gray-600">Monitor and analyze collector performance metrics</p>
        </div>
        <div class="flex gap-3">
            <!-- Time Range Filter -->
            <select id="timeRange" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="7" {{ $timeRange == 7 ? 'selected' : '' }}>Last 7 days</option>
                <option value="30" {{ $timeRange == 30 ? 'selected' : '' }}>Last 30 days</option>
                <option value="90" {{ $timeRange == 90 ? 'selected' : '' }}>Last 90 days</option>
            </select>
            
            <!-- Collector Filter -->
            <select id="collectorFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="">All Collectors</option>
                @foreach($collectors as $collector)
                    <option value="{{ $collector->id }}" {{ $collectorId == $collector->id ? 'selected' : '' }}>
                        {{ $collector->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Overview Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Collectors -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Collectors</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $overviewStats['total_collectors'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-500">
                <span class="text-green-600 font-medium">{{ $overviewStats['active_collectors'] }}</span> currently active
            </div>
        </div>

        <!-- Total Collections -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Collections Completed</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($overviewStats['total_collections']) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-500">
                Last {{ $timeRange }} days
            </div>
        </div>

        <!-- System Completion Rate -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">System Completion Rate</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $overviewStats['avg_system_completion_rate'] }}%</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-yellow-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center">
                    <div class="w-full bg-gray-200 rounded-full h-2 mr-3">
                        <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ $overviewStats['avg_system_completion_rate'] }}%"></div>
                    </div>
                    <span class="text-sm text-gray-600">{{ $overviewStats['avg_system_completion_rate'] }}%</span>
                </div>
            </div>
        </div>

        <!-- Customer Satisfaction -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Customer Satisfaction</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">
                        @if($overviewStats['customer_satisfaction'])
                            {{ $overviewStats['customer_satisfaction'] }}/5
                        @else
                            N/A
                        @endif
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-star text-purple-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                @if($overviewStats['customer_satisfaction'])
                    <div class="flex text-yellow-400">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= $overviewStats['customer_satisfaction'] ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                        @endfor
                    </div>
                @else
                    <span class="text-sm text-gray-500">No ratings available</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Performance Chart -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Performance Trends</h2>
            <p class="text-sm text-gray-600 mt-1">Daily completion rates and assignment volume</p>
        </div>
        <div class="p-6">
            <div class="relative h-64">
                <canvas id="performanceChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Collector Performance Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Collector Performance Ranking</h2>
                    <p class="text-sm text-gray-600 mt-1">Individual collector efficiency metrics and scores</p>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Collector</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Efficiency Score</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completion Rate</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg. Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Collections</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($collectorMetrics as $index => $collector)
                        <tr class="hover:bg-gray-50">
                            <!-- Rank -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($index == 0)
                                        <span class="inline-flex items-center justify-center w-8 h-8 bg-yellow-100 text-yellow-800 rounded-full text-sm font-bold">
                                            <i class="fas fa-crown"></i>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 text-gray-800 rounded-full text-sm font-bold">
                                            {{ $index + 1 }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <!-- Collector Info -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                            <span class="text-sm font-medium text-green-800">
                                                {{ strtoupper(substr($collector['name'], 0, 1)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $collector['name'] }}</div>
                                        <div class="text-sm text-gray-500">{{ $collector['location'] ?: 'Location not set' }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Efficiency Score -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm font-medium text-gray-900">{{ $collector['efficiency_score'] }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                            <div class="h-2 rounded-full {{ $collector['efficiency_score'] >= 80 ? 'bg-green-500' : ($collector['efficiency_score'] >= 60 ? 'bg-yellow-500' : 'bg-red-500') }}" 
                                                 style="width: {{ $collector['efficiency_score'] }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Completion Rate -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $collector['completion_rate'] >= 90 ? 'bg-green-100 text-green-800' : ($collector['completion_rate'] >= 70 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ $collector['completion_rate'] }}%
                                </span>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ $collector['completed_reports'] }}/{{ $collector['total_assigned'] }}
                                </div>
                            </td>

                            <!-- Average Time -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($collector['avg_completion_time'])
                                    <span class="font-medium">{{ $collector['avg_completion_time'] }}h</span>
                                    <div class="text-xs text-gray-500">avg completion</div>
                                @else
                                    <span class="text-gray-400">No data</span>
                                @endif
                            </td>

                            <!-- Rating -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($collector['avg_rating'])
                                    <div class="flex items-center">
                                        <span class="text-sm font-medium text-gray-900 mr-1">{{ $collector['avg_rating'] }}</span>
                                        <div class="flex text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star text-xs {{ $i <= $collector['avg_rating'] ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">No ratings</span>
                                @endif
                            </td>

                            <!-- Collections -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <span class="font-medium">{{ $collector['completed_reports'] }}</span> completed
                                </div>
                                @if($collector['pending_reports'] > 0)
                                    <div class="text-sm text-orange-600">
                                        {{ $collector['pending_reports'] }} pending
                                    </div>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($collector['status'] == 'active')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <div class="w-1.5 h-1.5 bg-green-400 rounded-full mr-1.5"></div>
                                        Active
                                    </span>
                                @elseif($collector['status'] == 'recently_active')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <div class="w-1.5 h-1.5 bg-yellow-400 rounded-full mr-1.5"></div>
                                        Recently Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <div class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-1.5"></div>
                                        Idle
                                    </span>
                                @endif
                                @if($collector['recent_activity'] > 0)
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $collector['recent_activity'] }} actions (24h)
                                    </div>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button class="text-green-600 hover:text-green-900 transition-colors" 
                                            onclick="viewCollectorDetails({{ $collector['id'] }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="text-blue-600 hover:text-blue-900 transition-colors"
                                            onclick="messageCollector({{ $collector['id'] }})">
                                        <i class="fas fa-envelope"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center py-8">
                                    <i class="fas fa-users text-4xl text-gray-400 mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-1">No collectors found</h3>
                                    <p class="text-gray-500">There are no collectors in the system yet.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Performance Chart Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Performance trends chart
    const ctx = document.getElementById('performanceChart').getContext('2d');
    const trendsData = @json($performanceTrends);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: trendsData.map(item => item.date),
            datasets: [
                {
                    label: 'Assignments',
                    data: trendsData.map(item => item.assigned),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Completed',
                    data: trendsData.map(item => item.completed),
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Completion Rate %',
                    data: trendsData.map(item => item.completion_rate),
                    borderColor: 'rgb(168, 85, 247)',
                    backgroundColor: 'rgba(168, 85, 247, 0.1)',
                    tension: 0.4,
                    yAxisID: 'y1',
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'top',
                },
            },
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Date'
                    }
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Number of Reports'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Completion Rate (%)'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                    max: 100
                }
            }
        }
    });

    // Filter change handlers
    document.getElementById('timeRange').addEventListener('change', function() {
        updateAnalytics();
    });

    document.getElementById('collectorFilter').addEventListener('change', function() {
        updateAnalytics();
    });

    function updateAnalytics() {
        const timeRange = document.getElementById('timeRange').value;
        const collectorId = document.getElementById('collectorFilter').value;
        
        const params = new URLSearchParams();
        params.append('range', timeRange);
        if (collectorId) {
            params.append('collector', collectorId);
        }
        
        window.location.href = '{{ route("admin.analytics") }}?' + params.toString();
    }
});

function viewCollectorDetails(collectorId) {
    // Implement collector detail view
    alert('Collector details view - ID: ' + collectorId);
}

function messageCollector(collectorId) {
    // Implement messaging functionality
    alert('Message collector - ID: ' + collectorId);
}
</script>

<style>
.hover\:bg-gray-50:hover {
    background-color: #f9fafb;
}

/* Custom animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.5s ease-out;
}

/* Status indicators */
.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
    margin-right: 8px;
}

.status-active {
    background-color: #10b981;
}

.status-warning {
    background-color: #f59e0b;
}

.status-inactive {
    background-color: #6b7280;
}
</style>
@endsection