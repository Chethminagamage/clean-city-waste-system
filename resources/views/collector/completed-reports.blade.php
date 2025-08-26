@extends('layouts.collector')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white shadow-xl">
        <div class="container mx-auto px-4 sm:px-6 py-6 sm:py-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('collector.dashboard') }}" 
                       class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center hover:bg-white/30 transition-colors duration-200">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-xl sm:text-3xl font-bold">Completed Reports</h1>
                        <p class="text-green-100 text-sm sm:text-base">History of completed waste collection tasks</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2 bg-white/20 px-3 py-2 rounded-full">
                    <i class="fas fa-check-circle text-green-200"></i>
                    <span class="text-sm font-medium">{{ $completedReports->count() }} Completed</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="container mx-auto px-4 sm:px-6 py-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl p-4 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-600 text-sm font-semibold">This Week</p>
                        <p class="text-2xl font-bold text-gray-800">
                            {{ $completedReports->where('updated_at', '>=', now()->startOfWeek())->count() }}
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-week text-green-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-4 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-600 text-sm font-semibold">This Month</p>
                        <p class="text-2xl font-bold text-gray-800">
                            {{ $completedReports->where('updated_at', '>=', now()->startOfMonth())->count() }}
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-4 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-600 text-sm font-semibold">Avg. per Day</p>
                        <p class="text-2xl font-bold text-gray-800">
                            {{ $completedReports->count() > 0 ? number_format($completedReports->count() / max(1, now()->diffInDays($completedReports->min('updated_at'))), 1) : '0' }}
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-line text-purple-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-4 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-600 text-sm font-semibold">Total</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $completedReports->count() }}</p>
                    </div>
                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-trophy text-orange-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-6">
            <div class="flex flex-col lg:flex-row gap-4">
                <!-- Search -->
                <div class="flex-1">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="searchInput" placeholder="Search completed reports..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>
                
                <!-- Date Range Filter -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <select id="dateFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">All Time</option>
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                        <option value="year">This Year</option>
                    </select>
                    
                    <button onclick="clearFilters()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>Clear
                    </button>
                </div>
            </div>
        </div>

        <!-- Completed Reports List -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-100 to-emerald-200 px-6 py-4 border-b border-green-200">
                <h2 class="text-xl font-bold text-green-800">Completed Reports</h2>
            </div>

            <div class="divide-y divide-gray-100" id="reportsList">
                @forelse ($completedReports as $report)
                    <div class="report-item p-6 hover:bg-green-50 transition-colors duration-200"
                         data-location="{{ strtolower($report->location) }}"
                         data-waste-type="{{ strtolower($report->waste_type ?? '') }}"
                         data-date="{{ $report->updated_at->format('Y-m-d') }}"
                         data-month="{{ $report->updated_at->format('Y-m') }}"
                         data-week="{{ $report->updated_at->format('Y-W') }}"
                         data-year="{{ $report->updated_at->format('Y') }}">
                        
                        <div class="flex flex-col lg:flex-row justify-between items-start gap-4">
                            <!-- Report Info -->
                            <div class="flex-1 space-y-3">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                            {{ $report->waste_type ?? 'General Waste' }}
                                        </h3>
                                        <p class="text-sm text-gray-600">#{{ $report->reference_code ?? $report->id }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            Completed
                                        </span>
                                        <p class="text-xs text-gray-500 mt-1">{{ $report->updated_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 text-sm">
                                    <div class="flex items-center text-gray-700">
                                        <i class="fas fa-map-marker-alt text-green-500 mr-2"></i>
                                        <span>{{ Str::limit($report->location, 40) }}</span>
                                    </div>
                                    <div class="flex items-center text-gray-700">
                                        <i class="fas fa-calendar-check text-green-500 mr-2"></i>
                                        <span>Completed: {{ $report->updated_at->format('M d, Y h:i A') }}</span>
                                    </div>
                                    @if($report->resident)
                                        <div class="flex items-center text-gray-700">
                                            <i class="fas fa-user text-green-500 mr-2"></i>
                                            <span>{{ $report->resident->name }}</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Completion Time -->
                                <div class="bg-green-50 p-3 rounded-lg">
                                    <div class="flex items-center justify-between text-sm">
                                        <div class="flex items-center text-green-700">
                                            <i class="fas fa-clock mr-2"></i>
                                            <span>Completion Time: {{ $report->created_at->diffInHours($report->updated_at) }} hours</span>
                                        </div>
                                        <div class="flex items-center text-green-700">
                                            <i class="fas fa-route mr-2"></i>
                                            <span>Status: {{ ucfirst($report->status) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-col gap-2 min-w-fit">
                                <button onclick="viewReportDetails({{ $report->id }})" 
                                        class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg font-medium transition-colors duration-200">
                                    <i class="fas fa-eye mr-1"></i> View Details
                                </button>

                                @if ($report->latitude && $report->longitude)
                                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $report->latitude }},{{ $report->longitude }}&origin={{ $collectorLat }},{{ $collectorLng }}"
                                       target="_blank"
                                       class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition-colors duration-200 text-center">
                                        <i class="fas fa-map-marker-alt mr-1"></i> View Location
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="w-16 h-16 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-history text-green-500 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">No Completed Reports</h3>
                        <p class="text-gray-600">You haven't completed any waste collection tasks yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Report Details Modal -->
<div id="reportModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" style="display: none;">
    <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden shadow-2xl">
        <div class="bg-gradient-to-r from-green-100 to-emerald-200 px-6 py-4 flex items-center justify-between">
            <h3 class="text-xl font-bold text-green-800">Completed Report Details</h3>
            <button onclick="closeReportModal()" class="text-green-800 hover:text-green-900">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div id="modalContent" class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<script>
    // Search and filter functionality
    function filterReports() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const dateFilter = document.getElementById('dateFilter').value;
        const reportItems = document.querySelectorAll('.report-item');
        const now = new Date();

        reportItems.forEach(item => {
            const location = item.dataset.location;
            const wasteType = item.dataset.wasteType;
            const reportDate = item.dataset.date;
            const reportMonth = item.dataset.month;
            const reportWeek = item.dataset.week;
            const reportYear = item.dataset.year;

            const matchesSearch = location.includes(searchTerm) || wasteType.includes(searchTerm);
            
            let matchesDate = true;
            if (dateFilter) {
                const today = now.toISOString().split('T')[0];
                const currentWeek = now.getFullYear() + '-' + getWeek(now);
                const currentMonth = now.getFullYear() + '-' + String(now.getMonth() + 1).padStart(2, '0');
                const currentYear = now.getFullYear().toString();

                switch (dateFilter) {
                    case 'today':
                        matchesDate = reportDate === today;
                        break;
                    case 'week':
                        matchesDate = reportWeek === currentWeek;
                        break;
                    case 'month':
                        matchesDate = reportMonth === currentMonth;
                        break;
                    case 'year':
                        matchesDate = reportYear === currentYear;
                        break;
                }
            }

            if (matchesSearch && matchesDate) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    function getWeek(date) {
        const firstDayOfYear = new Date(date.getFullYear(), 0, 1);
        const pastDaysOfYear = (date - firstDayOfYear) / 86400000;
        return Math.ceil((pastDaysOfYear + firstDayOfYear.getDay() + 1) / 7);
    }

    function clearFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('dateFilter').value = '';
        filterReports();
    }

    // Event listeners
    document.getElementById('searchInput').addEventListener('input', filterReports);
    document.getElementById('dateFilter').addEventListener('change', filterReports);

    // Modal functions
    function viewReportDetails(reportId) {
        document.getElementById('modalContent').innerHTML = `
            <div class="flex items-center justify-center py-12">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-500"></div>
                <span class="ml-3 text-gray-600">Loading report details...</span>
            </div>
        `;
        
        document.getElementById('reportModal').style.display = 'flex';
        
        fetch(`/collector/report/${reportId}/details`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(reportData => {
            document.getElementById('modalContent').innerHTML = `
                <div class="space-y-6">
                    <div class="bg-green-50 p-4 rounded-xl border border-green-200">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-check-circle text-green-600 text-xl mr-3"></i>
                            <h4 class="font-semibold text-green-800">Waste Collection Completed</h4>
                        </div>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Report ID:</span>
                                <span class="font-medium">${reportData.reference_code || '#' + reportData.id}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Waste Type:</span>
                                <span class="font-medium">${reportData.waste_type || 'General Waste'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Completed:</span>
                                <span class="font-medium">${reportData.updated_at}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 p-4 rounded-xl">
                        <h4 class="font-semibold text-blue-800 mb-3">Location Details</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex items-start">
                                <i class="fas fa-map-marker-alt text-blue-500 mr-2 mt-1"></i>
                                <div>
                                    <strong>Address:</strong><br>
                                    ${reportData.location}
                                </div>
                            </div>
                        </div>
                    </div>

                    ${reportData.resident ? `
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <h4 class="font-semibold text-gray-800 mb-3">Resident Information</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Name:</span>
                                <span class="font-medium">${reportData.resident.name}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Contact:</span>
                                <span class="font-medium">${reportData.resident.contact || 'Not provided'}</span>
                            </div>
                        </div>
                    </div>
                    ` : ''}

                    <div class="flex justify-center pt-4 border-t">
                        <button onclick="closeReportModal()" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-medium">
                            Close
                        </button>
                    </div>
                </div>
            `;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('modalContent').innerHTML = `
                <div class="text-center py-12">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Error Loading Report</h3>
                    <p class="text-gray-600">Unable to load report details.</p>
                </div>
            `;
        });
    }

    function closeReportModal() {
        document.getElementById('reportModal').style.display = 'none';
    }
</script>
@endsection
