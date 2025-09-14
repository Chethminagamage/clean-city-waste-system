@extends('layouts.collector')

@section('title', 'Report Details')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 via-white to-orange-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="container mx-auto px-4 py-6">
        
        {{-- Header Section --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-orange-600 dark:from-orange-600 dark:to-orange-700 rounded-lg flex items-center justify-center">
                        <i class="fas fa-file-alt text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Report Details</h1>
                        <p class="text-gray-600 dark:text-gray-400">ID: {{ $report->id }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="px-4 py-2 rounded-full text-sm font-semibold
                        @if($report->status === 'pending') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                        @elseif($report->status === 'assigned') bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200
                        @elseif($report->status === 'confirmed') bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200
                        @elseif($report->status === 'in_progress') bg-orange-100 dark:bg-orange-900 text-orange-800 dark:text-orange-200
                        @elseif($report->status === 'collected') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                        @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 @endif">
                        {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                    </div>
                    <div class="px-4 py-2 rounded-full text-sm font-semibold
                        @if($report->urgency_level === 'high') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200
                        @elseif($report->urgency_level === 'medium') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                        @else bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 @endif">
                        {{ ucfirst($report->urgency_level) }} Priority
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Left Column: Report Information --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Report Details Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 transition-colors duration-300">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-info-circle text-orange-600 dark:text-orange-400"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">Report Information</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Waste Type</label>
                                <div class="mt-1 flex items-center">
                                    <div class="w-8 h-8 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-trash text-orange-600 dark:text-orange-400"></i>
                                    </div>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $report->waste_type }}</p>
                                </div>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Location</label>
                                <div class="mt-1 flex items-start">
                                    <div class="w-8 h-8 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center mr-3 mt-1">
                                        <i class="fas fa-map-marker-alt text-orange-600 dark:text-orange-400"></i>
                                    </div>
                                    <p class="text-gray-900 dark:text-gray-100 leading-relaxed">{{ $report->location }}</p>
                                </div>
                            </div>

                            @if($report->description)
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Description</label>
                                <div class="mt-1 flex items-start">
                                    <div class="w-8 h-8 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center mr-3 mt-1">
                                        <i class="fas fa-align-left text-orange-600 dark:text-orange-400"></i>
                                    </div>
                                    <p class="text-gray-900 dark:text-gray-100 leading-relaxed">{{ $report->description }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Submitted</label>
                                <div class="mt-1 flex items-center">
                                    <div class="w-8 h-8 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-calendar text-orange-600 dark:text-orange-400"></i>
                                    </div>
                                    <p class="text-gray-900 dark:text-gray-100">{{ $report->created_at->format('M d, Y H:i A') }}</p>
                                </div>
                            </div>
                            
                            @if($report->assigned_at)
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Assigned</label>
                                <div class="mt-1 flex items-center">
                                    <div class="w-8 h-8 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-user-check text-orange-600 dark:text-orange-400"></i>
                                    </div>
                                    <p class="text-gray-900 dark:text-gray-100">{{ $report->assigned_at->format('M d, Y H:i A') }}</p>
                                </div>
                            </div>
                            @endif

                            @if($report->latitude && $report->longitude)
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Coordinates</label>
                                <div class="mt-1 flex items-center">
                                    <div class="w-8 h-8 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-crosshairs text-orange-600 dark:text-orange-400"></i>
                                    </div>
                                    <p class="text-gray-900 dark:text-gray-100 font-mono text-sm">{{ $report->latitude }}, {{ $report->longitude }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Map Section --}}
                @if($report->latitude && $report->longitude)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 transition-colors duration-300">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-map text-orange-600 dark:text-orange-400"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">Location Map</h2>
                    </div>
                    
                    <div id="map" class="w-full h-80 rounded-lg border-2 border-gray-200 dark:border-gray-600"></div>
                </div>
                @endif
            </div>

            {{-- Right Column: Resident Information & Actions --}}
            <div class="space-y-6">
                
                {{-- Resident Information Card --}}
                @if($report->resident)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 transition-colors duration-300">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-user text-orange-600 dark:text-orange-400"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">Resident Information</h2>
                    </div>
                    
                    <div class="space-y-4">
                        {{-- Resident Profile --}}
                        <div class="flex items-center space-x-4 p-4 bg-orange-50 dark:bg-orange-900 rounded-lg border border-orange-100 dark:border-orange-800 transition-colors duration-300">
                            <div class="w-12 h-12 bg-orange-200 dark:bg-orange-700 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-orange-600 dark:text-orange-300 text-lg"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 dark:text-gray-100">{{ $report->resident->name }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $report->resident->email }}</p>
                            </div>
                        </div>

                        {{-- Contact Information --}}
                        @if($report->resident->contact)
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-green-50 dark:bg-green-900 rounded-lg border border-green-200 dark:border-green-800 transition-colors duration-300">
                                <div class="flex items-center">
                                    <i class="fas fa-phone text-green-600 dark:text-green-400 mr-3"></i>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $report->resident->contact }}</span>
                                </div>
                            </div>
                            
                            {{-- Call Button --}}
                            <a href="tel:{{ $report->resident->contact }}" 
                               class="w-full flex items-center justify-center px-4 py-3 bg-green-600 hover:bg-green-700 dark:bg-green-600 dark:hover:bg-green-700 text-white rounded-lg transition-all duration-200 font-semibold shadow-md hover:shadow-lg">
                                <i class="fas fa-phone-alt mr-2"></i>
                                Call Resident
                            </a>
                        </div>
                        @else
                        <div class="p-4 bg-yellow-50 dark:bg-yellow-900 rounded-lg border border-yellow-200 dark:border-yellow-800 transition-colors duration-300">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400 mr-2"></i>
                                <span class="text-sm text-yellow-700 dark:text-yellow-300">No contact information available</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Action Buttons Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 transition-colors duration-300">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-tasks text-orange-600 dark:text-orange-400"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">Actions</h2>
                    </div>
                    
                    <div class="space-y-3">
                        @if($report->status === 'assigned')
                            <form method="POST" action="{{ route('collector.report.confirm', $report->id) }}">
                                @csrf
                                <button type="submit" 
                                        class="w-full flex items-center justify-center px-4 py-3 bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700 text-white rounded-lg transition-all duration-200 font-semibold shadow-md hover:shadow-lg">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Confirm Assignment
                                </button>
                            </form>
                        @endif

                        

                        {{-- Get Directions Button --}}
                        @if($report->latitude && $report->longitude)
                        <a href="https://www.google.com/maps/dir/?api=1&destination={{ $report->latitude }},{{ $report->longitude }}" 
                           target="_blank"
                           class="w-full flex items-center justify-center px-4 py-3 bg-orange-600 hover:bg-orange-700 dark:bg-orange-600 dark:hover:bg-orange-700 text-white rounded-lg transition-all duration-200 font-semibold shadow-md hover:shadow-lg">
                            <i class="fas fa-directions mr-2"></i>
                            Get Directions
                        </a>
                        @endif

                        {{-- Back to Dashboard --}}
                        <a href="{{ route('collector.dashboard.main') }}" 
                           class="w-full flex items-center justify-center px-4 py-3 bg-gray-600 hover:bg-gray-700 dark:bg-gray-600 dark:hover:bg-gray-700 text-white rounded-lg transition-all duration-200 font-semibold shadow-md hover:shadow-lg">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Google Maps Script --}}
@if($report->latitude && $report->longitude)
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDgyETTNM7hQ-P9BETdNwTbMr6ggGr73oY&callback=initMap" async defer></script>

<script>
    function initMap() {
        const reportLocation = { lat: {{ $report->latitude }}, lng: {{ $report->longitude }} };
        
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 16,
            center: reportLocation,
            styles: [
                {
                    featureType: "poi",
                    elementType: "labels",
                    stylers: [{ visibility: "off" }]
                }
            ]
        });
        
        // Create custom orange marker for waste report location
        const marker = new google.maps.Marker({
            position: reportLocation,
            map: map,
            title: "Waste Report Location",
            icon: {
                url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`
                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" fill="#f97316"/>
                        <circle cx="12" cy="9" r="3" fill="white"/>
                        <path d="M12 6.5c-1.38 0-2.5 1.12-2.5 2.5s1.12 2.5 2.5 2.5 2.5-1.12 2.5-2.5-1.12-2.5-2.5-2.5zm0 3.5c-0.55 0-1-0.45-1-1s0.45-1 1-1 1 0.45 1 1-0.45 1-1 1z" fill="#ea580c"/>
                    </svg>
                `),
                scaledSize: new google.maps.Size(50, 50),
                anchor: new google.maps.Point(25, 50)
            }
        });

        // Add info window with report details
        const infoWindow = new google.maps.InfoWindow({
            content: `
                <div class="p-3 max-w-xs">
                    <h4 class="font-bold text-gray-800 mb-2">📍 Waste Report Location</h4>
                    <div class="space-y-1 text-sm">
                        <p><strong>Type:</strong> {{ $report->waste_type }}</p>
                        <p><strong>Urgency:</strong> <span class="px-2 py-1 rounded text-xs font-medium 
                            @if($report->urgency_level === 'high') bg-red-100 text-red-800
                            @elseif($report->urgency_level === 'medium') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800 @endif">
                            {{ ucfirst($report->urgency_level) }}
                        </span></p>
                        <p><strong>Status:</strong> <span class="px-2 py-1 rounded text-xs font-medium
                            @if($report->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($report->status === 'assigned') bg-blue-100 text-blue-800
                            @elseif($report->status === 'confirmed') bg-purple-100 text-purple-800
                            @elseif($report->status === 'in_progress') bg-orange-100 text-orange-800
                            @elseif($report->status === 'collected') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                        </span></p>
                        @if($report->location)
                        <p><strong>Address:</strong> {{ Str::limit($report->location, 50) }}</p>
                        @endif
                    </div>
                </div>
            `
        });

        // Show info window on marker click
        marker.addListener('click', () => {
            infoWindow.open(map, marker);
        });

        // Auto-open info window
        infoWindow.open(map, marker);
    }
</script>
@endif
@endsection
