@extends('layouts.collector')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-xl">
        <div class="container mx-auto px-4 sm:px-6 py-6 sm:py-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('collector.dashboard') }}" 
                       class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center hover:bg-white/30 transition-colors duration-200">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-xl sm:text-3xl font-bold">All Reports</h1>
                        <p class="text-orange-100 text-sm sm:text-base">Complete list of assigned waste collection tasks</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2 bg-white/20 px-3 py-2 rounded-full">
                    <i class="fas fa-list-alt text-orange-200"></i>
                    <span class="text-sm font-medium">{{ $assignedReports->count() }} Total Reports</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter and Search Section -->
    <div class="container mx-auto px-4 sm:px-6 py-6">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-6">
            <div class="flex flex-col lg:flex-row gap-4">
                <!-- Search -->
                <div class="flex-1">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="searchInput" placeholder="Search by location, waste type, or report ID..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    </div>
                </div>
                
                <!-- Status Filter -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <select id="statusFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="">All Statuses</option>
                        <option value="assigned">Assigned</option>
                        <option value="enroute">Enroute</option>
                        <option value="collected">Collected</option>
                        <option value="closed">Closed</option>
                    </select>
                    
                    <button onclick="clearFilters()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>Clear
                    </button>
                </div>
            </div>
        </div>

        <!-- Reports List -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-100 to-orange-200 px-6 py-4 border-b border-orange-200">
                <h2 class="text-xl font-bold text-orange-800">Assigned Reports</h2>
            </div>

            <div class="divide-y divide-gray-100" id="reportsList">
                @forelse ($assignedReports as $report)
                    <div class="report-item p-6 hover:bg-orange-50 transition-colors duration-200" 
                         data-status="{{ $report->status }}" 
                         data-location="{{ strtolower($report->location) }}"
                         data-waste-type="{{ strtolower($report->waste_type ?? '') }}"
                         data-report-id="{{ $report->id }}">
                        
                        <div class="flex flex-col lg:flex-row justify-between items-start gap-4">
                            <!-- Report Info -->
                            <div class="flex-1 space-y-3">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-800">
                                            {{ $report->waste_type ?? 'General Waste' }}
                                        </h3>
                                        <p class="text-sm text-gray-600">#{{ $report->reference_code ?? $report->id }}</p>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        @if($report->status === 'assigned') bg-blue-100 text-blue-800
                                        @elseif($report->status === 'enroute') bg-purple-100 text-purple-800
                                        @elseif($report->status === 'collected') bg-green-100 text-green-800
                                        @elseif($report->status === 'closed') bg-gray-100 text-gray-800
                                        @else bg-gray-100 text-gray-800
                                        @endif
                                    ">
                                        {{ ucfirst($report->status) }}
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 text-sm">
                                    <div class="flex items-center text-gray-700">
                                        <i class="fas fa-map-marker-alt text-orange-500 mr-2"></i>
                                        <span>{{ Str::limit($report->location, 50) }}</span>
                                    </div>
                                    <div class="flex items-center text-gray-700">
                                        <i class="fas fa-clock text-orange-500 mr-2"></i>
                                        <span>{{ $report->created_at->format('M d, Y') }}</span>
                                    </div>
                                    @if($report->resident)
                                        <div class="flex items-center text-gray-700">
                                            <i class="fas fa-user text-orange-500 mr-2"></i>
                                            <span>{{ $report->resident->name }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-col sm:flex-row gap-2 min-w-fit">
                                <button onclick="viewReportDetails({{ $report->id }})" 
                                        class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg font-medium transition-colors duration-200">
                                    <i class="fas fa-eye mr-1"></i> View
                                </button>

                                @if ($report->latitude && $report->longitude)
                                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $report->latitude }},{{ $report->longitude }}&origin={{ $collectorLat }},{{ $collectorLng }}"
                                       target="_blank"
                                       class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition-colors duration-200 text-center">
                                        <i class="fas fa-directions mr-1"></i> Navigate
                                    </a>
                                @endif

                                @if ($report->status === 'assigned')
                                    <form method="POST" action="{{ route('collector.report.confirm', $report->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded-lg font-medium transition-colors duration-200">
                                            <i class="fas fa-check-circle mr-1"></i> Confirm
                                        </button>
                                    </form>
                                @elseif ($report->status === 'enroute')
                                    <form method="POST" action="{{ route('collector.report.start', $report->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg font-medium transition-colors duration-200">
                                            <i class="fas fa-check mr-1"></i> Collect
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="w-16 h-16 mx-auto bg-orange-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-clipboard-list text-orange-500 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">No Reports Found</h3>
                        <p class="text-gray-600">You don't have any assigned reports at the moment.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Report Details Modal (reuse from dashboard) -->
<div id="reportModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" style="display: none;">
    <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden shadow-2xl">
        <div class="bg-gradient-to-r from-orange-100 to-orange-200 px-6 py-4 flex items-center justify-between">
            <h3 class="text-xl font-bold text-orange-800">Report Details</h3>
            <button onclick="closeReportModal()" class="text-orange-800 hover:text-orange-900">
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
        const statusFilter = document.getElementById('statusFilter').value;
        const reportItems = document.querySelectorAll('.report-item');

        reportItems.forEach(item => {
            const location = item.dataset.location;
            const wasteType = item.dataset.wasteType;
            const reportId = item.dataset.reportId;
            const status = item.dataset.status;

            const matchesSearch = location.includes(searchTerm) || 
                                wasteType.includes(searchTerm) || 
                                reportId.includes(searchTerm);
            
            const matchesStatus = !statusFilter || status === statusFilter;

            if (matchesSearch && matchesStatus) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    function clearFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('statusFilter').value = '';
        filterReports();
    }

    // Event listeners
    document.getElementById('searchInput').addEventListener('input', filterReports);
    document.getElementById('statusFilter').addEventListener('change', filterReports);

    // Modal functions (reuse from dashboard)
    function viewReportDetails(reportId) {
        document.getElementById('modalContent').innerHTML = `
            <div class="flex items-center justify-center py-12">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-orange-500"></div>
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
                    <div class="bg-orange-50 p-4 rounded-xl">
                        <h4 class="font-semibold text-orange-800 mb-3">Waste Collection Request</h4>
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
                                <span class="text-gray-600">Status:</span>
                                <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                    ${reportData.status === 'assigned' ? 'bg-blue-100 text-blue-800' :
                                      reportData.status === 'enroute' ? 'bg-purple-100 text-purple-800' :
                                      reportData.status === 'collected' ? 'bg-green-100 text-green-800' :
                                      'bg-gray-100 text-gray-800'}">${reportData.status.charAt(0).toUpperCase() + reportData.status.slice(1)}</span>
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

                    <!-- Resident Info -->
                    ${reportData.resident ? `
                    <div class="bg-green-50 p-4 rounded-xl">
                        <h4 class="font-semibold text-green-800 mb-3">Resident Information</h4>
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

                    <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t">
                        ${reportData.latitude && reportData.longitude ? `
                        <a href="https://www.google.com/maps/dir/?api=1&destination=${reportData.latitude},${reportData.longitude}&origin={{ $collectorLat }},{{ $collectorLng }}" 
                           target="_blank" 
                           class="flex-1 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium text-center">
                            <i class="fas fa-directions mr-2"></i>Get Directions
                        </a>
                        ` : ''}
                        
                        ${reportData.status === 'assigned' ? `
                        <form method="POST" action="/collector/report/${reportData.id}/confirm" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg font-medium">
                                <i class="fas fa-check-circle mr-2"></i>Confirm Assignment
                            </button>
                        </form>
                        ` : reportData.status === 'enroute' ? `
                        <form method="POST" action="/collector/report/${reportData.id}/start" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-medium">
                                <i class="fas fa-check mr-2"></i>Mark Collected
                            </button>
                        </form>
                        ` : ''}
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
