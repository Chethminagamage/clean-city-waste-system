@extends('admin.layout.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm mb-6 p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.binreports') }}" class="text-gray-500 hover:text-gray-700 transition-colors">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Report Details</h1>
                        <p class="text-gray-600">Reference: <span class="font-semibold text-blue-600">{{ $report->reference_code }}</span></p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Status Badge -->
                    @php
                        $statusColors = [
                            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                            'assigned' => 'bg-blue-100 text-blue-800 border-blue-200',
                            'enroute' => 'bg-purple-100 text-purple-800 border-purple-200',
                            'enroute' => 'bg-purple-100 text-purple-800 border-purple-200',
                            'collected' => 'bg-green-100 text-green-800 border-green-200',
                            'closed' => 'bg-gray-100 text-gray-800 border-gray-200',
                            'cancelled' => 'bg-red-100 text-red-800 border-red-200'
                        ];
                        $statusColor = $statusColors[strtolower($report->status)] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                    @endphp
                    <span class="px-4 py-2 rounded-full text-sm font-medium border {{ $statusColor }}">
                        <i class="fas fa-circle text-xs mr-2"></i>{{ ucfirst($report->status) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Report Image -->
                @if($report->image_path)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-image text-blue-600 mr-2"></i>Report Image
                    </h3>
                    <div class="relative">
                        <img src="{{ asset('storage/' . $report->image_path) }}" 
                             alt="Waste Report Image" 
                             class="w-full h-96 object-cover rounded-lg shadow-md cursor-pointer hover:shadow-lg transition-shadow"
                             onclick="openImageModal('{{ asset('storage/' . $report->image_path) }}')">
                        <div class="absolute top-2 right-2">
                            <button onclick="openImageModal('{{ asset('storage/' . $report->image_path) }}')" 
                                    class="bg-white bg-opacity-80 hover:bg-opacity-100 p-2 rounded-full shadow-md transition-all">
                                <i class="fas fa-expand text-gray-600"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Report Details -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>Report Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Waste Type</label>
                            <p class="text-gray-900 bg-gray-50 p-3 rounded-md">{{ $report->waste_type ?? 'Not specified' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Report Date</label>
                            <p class="text-gray-900 bg-gray-50 p-3 rounded-md">{{ $report->report_date ? $report->report_date->format('M d, Y h:i A') : 'Not specified' }}</p>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                            <p class="text-gray-900 bg-gray-50 p-3 rounded-md">{{ $report->location }}</p>
                        </div>
                        
                        @if($report->additional_details)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Resident's Additional Details</label>
                            <p class="text-gray-900 bg-blue-50 p-3 rounded-md border border-blue-200">{{ $report->additional_details }}</p>
                        </div>
                        @endif
                        
                        @if($report->admin_notes)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Admin Notes</label>
                            <div class="text-gray-900 bg-yellow-50 p-3 rounded-md border border-yellow-200 whitespace-pre-line">{{ $report->admin_notes }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Location Map (if coordinates available) -->
                @if($report->latitude && $report->longitude)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-map-marker-alt text-red-600 mr-2"></i>Location Map
                    </h3>
                    <div id="map" class="w-full h-64 rounded-lg"></div>
                </div>
                @endif

                <!-- Action History -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-history text-purple-600 mr-2"></i>Action History
                    </h3>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3 p-3 bg-blue-50 rounded-md">
                            <i class="fas fa-plus-circle text-blue-600"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Report Created</p>
                                <p class="text-xs text-gray-600">{{ $report->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        
                        @if($report->collector_id)
                        <div class="flex items-center space-x-3 p-3 bg-green-50 rounded-md">
                            <i class="fas fa-user-check text-green-600"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Assigned to Collector</p>
                                <p class="text-xs text-gray-600">{{ $report->collector->name ?? 'Unknown Collector' }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($report->status === 'collected')
                        <div class="flex items-center space-x-3 p-3 bg-purple-50 rounded-md">
                            <i class="fas fa-check-circle text-purple-600"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Waste Collected</p>
                                <p class="text-xs text-gray-600">{{ $report->updated_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                
                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-cog text-gray-600 mr-2"></i>Quick Actions
                    </h3>
                    
                    <div class="space-y-3">
                        @php $status = strtolower($report->status ?? ''); @endphp
                        
                        @if (empty($report->collector_id) && $status === 'pending')
                        <button onclick="assignCollector({{ $report->id }})"
                                class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors">
                            <i class="fas fa-user-plus mr-2"></i>Assign Collector
                        </button>
                        @endif
                        
                        @if ($status === 'collected')
                        <form action="{{ route('admin.reports.close', $report->id) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition-colors">
                                <i class="fas fa-check-double mr-2"></i>Close Report
                            </button>
                        </form>
                        @endif
                        
                        @if (in_array($status, ['pending', 'assigned']))
                        <form action="{{ route('admin.reports.cancel', $report->id) }}" method="POST" class="w-full" 
                              onsubmit="return confirm('Are you sure you want to cancel this report?')">
                            @csrf
                            <button type="submit" class="w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 transition-colors">
                                <i class="fas fa-times mr-2"></i>Cancel Report
                            </button>
                        </form>
                        @endif
                        
                        <button onclick="openNotesModal()" 
                                class="w-full bg-gray-600 text-white py-2 px-4 rounded-md hover:bg-gray-700 transition-colors">
                            <i class="fas fa-sticky-note mr-2"></i>Add Admin Note
                        </button>
                    </div>
                </div>

                <!-- Resident Info -->
                @if($report->resident)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-user text-green-600 mr-2"></i>Resident Information
                    </h3>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <p class="text-gray-900">{{ $report->resident->name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <p class="text-gray-900">{{ $report->resident->email }}</p>
                        </div>
                        
                        @if($report->resident->phone)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Phone</label>
                            <p class="text-gray-900">{{ $report->resident->phone }}</p>
                        </div>
                        @endif
                        
                        <button onclick="contactResident()" 
                                class="w-full bg-blue-500 text-white py-2 px-3 rounded-md hover:bg-blue-600 transition-colors text-sm">
                            <i class="fas fa-envelope mr-2"></i>Contact Resident
                        </button>
                    </div>
                </div>
                @endif

                <!-- Collector Info -->
                @if($report->collector)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-truck text-orange-600 mr-2"></i>Assigned Collector
                    </h3>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <p class="text-gray-900">{{ $report->collector->name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <p class="text-gray-900">{{ $report->collector->email }}</p>
                        </div>
                        
                        @if($report->collector->phone)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Phone</label>
                            <p class="text-gray-900">{{ $report->collector->phone }}</p>
                        </div>
                        @endif
                        
                        <button onclick="contactCollector()" 
                                class="w-full bg-orange-500 text-white py-2 px-3 rounded-md hover:bg-orange-600 transition-colors text-sm">
                            <i class="fas fa-phone mr-2"></i>Contact Collector
                        </button>
                    </div>
                </div>
                @endif

                <!-- System Info -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-info-circle text-gray-600 mr-2"></i>System Information
                    </h3>
                    
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Report ID:</span>
                            <span class="text-gray-900">{{ $report->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Created:</span>
                            <span class="text-gray-900">{{ $report->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Last Updated:</span>
                            <span class="text-gray-900">{{ $report->updated_at->format('M d, Y') }}</span>
                        </div>
                        @if($report->latitude && $report->longitude)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Coordinates:</span>
                            <span class="text-gray-900 text-xs">{{ $report->latitude }}, {{ $report->longitude }}</span>
                        </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 items-center justify-center p-4 hidden">
    <div class="relative max-w-4xl max-h-full">
        <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
            <i class="fas fa-times text-2xl"></i>
        </button>
        <img id="modalImage" src="" alt="Report Image" class="max-w-full max-h-full object-contain rounded-lg">
    </div>
</div>

<!-- Assign Collector Modal -->
<div id="assignModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg w-[400px] shadow-xl">
        <h2 class="text-lg font-semibold mb-4">Assign Collector</h2>
        <form method="POST" id="assignForm">
            @csrf
            <select id="collector_select" name="collector_id" class="w-full p-3 border rounded-md mb-4" required>
                <option value="">Loading...</option>
            </select>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeAssignModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Assign</button>
            </div>
        </form>
    </div>
</div>

<!-- Notes Modal -->
<div id="notesModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg w-[500px] shadow-xl">
        <h2 class="text-lg font-semibold mb-4">Add Admin Note</h2>
        <form method="POST" action="{{ route('admin.reports.add_note', $report->id) }}">
            @csrf
            <textarea name="note" rows="4" class="w-full p-3 border rounded-md mb-4" placeholder="Enter your note..." required></textarea>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeNotesModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</button>
                <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">Add Note</button>
            </div>
        </form>
    </div>
</div>

<script>
// Image Modal Functions
function openImageModal(imageSrc) {
    const modal = document.getElementById('imageModal');
    document.getElementById('modalImage').src = imageSrc;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Assign Collector Functions
function assignCollector(reportId) {
    fetch(`/admin/report/${reportId}/nearby-collectors`)
        .then(res => res.json())
        .then(data => {
            const select = document.getElementById('collector_select');
            select.innerHTML = '';

            if (data.length === 0) {
                const option = document.createElement('option');
                option.text = 'No nearby collectors found';
                option.disabled = true;
                select.appendChild(option);
            } else {
                data.forEach(collector => {
                    const option = document.createElement('option');
                    option.value = collector.id;
                    option.text = `${collector.name} (${collector.distance.toFixed(2)} km)`;
                    select.appendChild(option);
                });
            }

            document.getElementById('assignForm').action = `/admin/assign-collector/${reportId}`;
            const modal = document.getElementById('assignModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
}

function closeAssignModal() {
    const modal = document.getElementById('assignModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Notes Modal Functions
function openNotesModal() {
    const modal = document.getElementById('notesModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeNotesModal() {
    const modal = document.getElementById('notesModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Contact Functions
function contactResident() {
    // Add your contact functionality here
    alert('Contact resident feature - implement as needed');
}

function contactCollector() {
    // Add your contact functionality here
    alert('Contact collector feature - implement as needed');
}

// Map initialization (if coordinates available)
@if($report->latitude && $report->longitude)
function initMap() {
    const location = { lat: {{ $report->latitude }}, lng: {{ $report->longitude }} };
    const map = new google.maps.Map(document.getElementById('map'), {
        zoom: 15,
        center: location,
    });
    const marker = new google.maps.Marker({
        position: location,
        map: map,
        title: 'Waste Report Location',
        icon: {
            url: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png'
        }
    });
}

// Load map when page is ready
window.addEventListener('load', initMap);
@endif

// Close modals when clicking outside
window.addEventListener('click', function(event) {
    const imageModal = document.getElementById('imageModal');
    const assignModal = document.getElementById('assignModal'); 
    const notesModal = document.getElementById('notesModal');
    
    if (event.target === imageModal) closeImageModal();
    if (event.target === assignModal) closeAssignModal();
    if (event.target === notesModal) closeNotesModal();
});
</script>

@if($report->latitude && $report->longitude)
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDgyETTNM7hQ-P9BETdNwTbMr6ggGr73oY&callback=initMap" async defer></script>
@endif

@endsection
