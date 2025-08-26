@extends('layouts.collector')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collector Dashboard | Clean City</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDgyETTNM7hQ-P9BETdNwTbMr6ggGr73oY&callback=initMap" async defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        .card-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .stat-card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px -5px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        html {
            scroll-behavior: smooth;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .orange-gradient {
            background: linear-gradient(135deg, #fed7aa 0%, #fdba74 50%, #fb923c 100%);
        }

        .status-card {
            transition: all 0.3s ease;
        }

        .status-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(249, 115, 22, 0.15);
        }

        /* Enhanced responsive styles */
        @media (max-width: 640px) {
            .grid {
                grid-template-columns: 1fr;
            }
            
            .lg\\:grid-cols-3 {
                grid-template-columns: 1fr;
            }
            
            .lg\\:col-span-2 {
                grid-column: span 1;
            }
            
            .md\\:grid-cols-3 {
                grid-template-columns: 1fr;
            }
            
            .px-6 {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            
            .py-8 {
                padding-top: 1.5rem;
                padding-bottom: 1.5rem;
            }
            
            .text-xl {
                font-size: 1.125rem;
            }
            
            .text-2xl {
                font-size: 1.25rem;
            }
            
            .gap-6 {
                gap: 1rem;
            }
            
            .p-6 {
                padding: 1rem;
            }
        }

        .report-card {
            background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
            border: 1px solid #e5e7eb;
        }

        .btn-orange {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            transition: all 0.3s ease;
        }

        .btn-orange:hover {
            background: linear-gradient(135deg, #ea580c 0%, #dc2626 100%);
            transform: translateY(-1px);
        }
    </style>
</head>

<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50">
    <!-- Welcome Header Section -->
    <div class="gradient-bg text-white shadow-xl">
        <div class="container mx-auto px-4 sm:px-6 py-4 sm:py-5">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-truck text-lg sm:text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-lg sm:text-2xl font-bold">Welcome, {{ Auth::user()->name }}!</h1>
                        <p class="text-orange-100 text-xs sm:text-sm">Ready to collect waste and keep our city clean</p>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="container mx-auto px-4 sm:px-6 py-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Active Reports Card -->
            <div class="stat-card bg-white rounded-2xl p-6 card-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-600 text-sm font-semibold uppercase tracking-wider">Active Reports</p>
                        <p class="text-2xl sm:text-3xl font-bold text-gray-800 mt-1">
                            {{ $activeReports->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-clipboard-list text-orange-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Completed Today Card -->
            <div class="stat-card bg-white rounded-2xl p-6 card-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-600 text-sm font-semibold uppercase tracking-wider">Completed Today</p>
                        <p class="text-2xl sm:text-3xl font-bold text-gray-800 mt-1">
                            {{ $completedReports->where('updated_at', '>=', now()->startOfDay())->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Assigned Card -->
            <div class="stat-card bg-white rounded-2xl p-6 card-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-600 text-sm font-semibold uppercase tracking-wider">Total Assigned</p>
                        <p class="text-2xl sm:text-3xl font-bold text-gray-800 mt-1">
                            {{ $assignedReports->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-tasks text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Location Status Card -->
            <div class="stat-card bg-white rounded-2xl p-6 card-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-600 text-sm font-semibold uppercase tracking-wider">Location Status</p>
                        <p class="text-sm font-medium text-gray-800 mt-1" id="location-status">
                            <span class="text-green-600" id="update-success">📍 Active</span>
                        </p>
                        <p class="text-xs text-gray-500" id="updated-time">Never</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-map-pin text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Assigned Reports Section -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gray-100 px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-orange-500 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-clipboard-list text-white"></i>
                                </div>
                                <h2 class="text-xl font-bold text-gray-800">Assigned Reports</h2>
                            </div>
                            <span class="bg-gray-200 px-3 py-1 rounded-full text-sm font-medium text-gray-700">
                                {{ $assignedReports->count() }} Reports
                            </span>
                        </div>
                    </div>

                    <div class="p-6 space-y-4">
                        @forelse ($assignedReports->take(10) as $report)
                            <div class="report-card p-4 sm:p-6 rounded-xl status-card">
                                <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                                    <div class="flex-1 space-y-3">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-lg font-semibold text-gray-800">
                                                {{ $report->waste_type ?? 'General Waste' }}
                                            </h3>
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                @if($report->status === 'assigned') bg-blue-100 text-blue-800
                                                @elseif($report->status === 'enroute') bg-purple-100 text-purple-800
                                                @elseif($report->status === 'collected') bg-green-100 text-green-800
                                                @else bg-gray-100 text-gray-800
                                                @endif
                                            ">
                                                {{ ucfirst($report->status) }}
                                            </span>
                                        </div>
                                        
                                        <div class="space-y-2">
                                            <p class="text-gray-700 flex items-center">
                                                <i class="fas fa-map-marker-alt text-orange-500 mr-2"></i>
                                                <strong class="mr-1">Location:</strong> {{ $report->location }}
                                            </p>
                                            <p class="text-gray-700 flex items-center">
                                                <i class="fas fa-clock text-orange-500 mr-2"></i>
                                                <strong class="mr-1">Submitted:</strong> {{ $report->created_at->format('M d, Y h:i A') }}
                                            </p>
                                            @if($report->resident)
                                                <p class="text-gray-700 flex items-center">
                                                    <i class="fas fa-user text-orange-500 mr-2"></i>
                                                    <strong class="mr-1">Resident:</strong> {{ $report->resident->name }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex flex-col sm:flex-row gap-3 mt-4 pt-4 border-t border-orange-200">
                                    <button onclick="viewReportDetails({{ $report->id }})" 
                                            class="flex-1 bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                                        <i class="fas fa-eye mr-2"></i>
                                        View Details
                                    </button>

                                    @if ($report->latitude && $report->longitude)
                                        <a href="https://www.google.com/maps/dir/?api=1&destination={{ $report->latitude }},{{ $report->longitude }}&origin={{ $collectorLat }},{{ $collectorLng }}"
                                        target="_blank"
                                        class="flex-1 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center text-center">
                                            <i class="fas fa-directions mr-2"></i>
                                            Get Directions
                                        </a>
                                    @endif

                                    {{-- Action buttons based on status --}}
                                    @if ($report->status === 'assigned')
                                        <form method="POST" action="{{ route('collector.report.confirm', $report->id) }}" class="flex-1">
                                            @csrf
                                            <button type="submit" class="w-full bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                                                <i class="fas fa-check-circle mr-2"></i>
                                                Confirm Assignment
                                            </button>
                                        </form>
                                    @elseif ($report->status === 'enroute')
                                        <form method="POST" action="{{ route('collector.report.start', $report->id) }}" class="flex-1">
                                            @csrf
                                            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                                                <i class="fas fa-check mr-2"></i>
                                                Mark Collected
                                            </button>
                                        </form>
                                    @elseif ($report->status === 'collected')
                                        <div class="flex-1 bg-gray-100 text-gray-600 px-4 py-2 rounded-lg font-medium flex items-center justify-center">
                                            <i class="fas fa-check-double mr-2"></i>
                                            Completed
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <div class="w-16 h-16 mx-auto bg-orange-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-clipboard-list text-orange-500 text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">No Assigned Reports</h3>
                                <p class="text-gray-600">You don't have any waste collection tasks at the moment.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Location & Quick Actions Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Location Card -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gray-100 px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-blue-500 rounded-xl flex items-center justify-center">
                                <i class="fas fa-map-marked-alt text-white"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Location Tracking</h3>
                        </div>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto bg-orange-100 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-crosshairs text-orange-600 text-2xl"></i>
                            </div>
                            <p class="text-gray-700 text-sm">Your location updates every <strong>30 seconds</strong></p>
                        </div>
                        
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Last Updated:</span>
                                <span class="font-medium" id="updated-time-detailed">Never</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Latitude:</span>
                                <span class="font-medium" id="lat-value">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Longitude:</span>
                                <span class="font-medium" id="lng-value">-</span>
                            </div>
                        </div>
                        
                        <button onclick="updateLocationManually()" 
                                class="w-full bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                            <i class="fas fa-sync-alt mr-2"></i>
                            Update Location
                        </button>
                    </div>
                </div>

                <!-- Quick Actions Card -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gray-100 px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-purple-500 rounded-xl flex items-center justify-center">
                                <i class="fas fa-bolt text-white"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Quick Actions</h3>
                        </div>
                    </div>
                    
                    <div class="p-6 space-y-3">
                        <button onclick="refreshDashboard()" 
                                class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                            <i class="fas fa-refresh mr-2"></i>
                            Refresh Dashboard
                        </button>
                        
                        <button onclick="showAllReports()" 
                                class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                            <i class="fas fa-list mr-2"></i>
                            View All Reports
                        </button>
                        
                        <button onclick="showCompletedReports()" 
                                class="w-full bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                            <i class="fas fa-history mr-2"></i>
                            Completed Reports
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Report Details Modal -->
<div id="reportModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex p-4" style="display: none;">
    <div class="flex items-center justify-center min-h-full w-full">
        <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden shadow-2xl">
            <div class="bg-gray-100 px-6 py-4 flex items-center justify-between border-b">
                <h3 class="text-xl font-bold text-gray-800">Report Details</h3>
                <button onclick="closeReportModal()" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div id="modalContent" class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Location Update Script -->
<script>
    let map;
    let marker;

    function updateCollectorLocation() {
        if (!navigator.geolocation) {
            alert('Geolocation is not supported by your browser.');
            return;
        }

        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            fetch('{{ route('collector.updateLocation') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    latitude: lat,
                    longitude: lng
                })
            }).then(response => {
                if (response.ok) {
                    const now = new Date();
                    document.getElementById('updated-time').innerText = now.toLocaleTimeString();
                    document.getElementById('updated-time-detailed').innerText = now.toLocaleString();
                    document.getElementById('lat-value').innerText = lat.toFixed(6);
                    document.getElementById('lng-value').innerText = lng.toFixed(6);
                    
                    // Show success indicator temporarily
                    const successElement = document.getElementById('update-success');
                    if (successElement) {
                        successElement.classList.remove('hidden');
                        setTimeout(() => {
                            successElement.classList.add('hidden');
                        }, 3000);
                    }
                }
            }).catch(error => {
                console.error('Error updating location:', error);
            });
        }, function(error) {
            console.error('Geolocation error:', error);
            document.getElementById('location-status').innerHTML = '<span class="text-red-600">⚠️ Location Error</span>';
        });
    }

    function updateLocationManually() {
        updateCollectorLocation();
    }

    function viewReportDetails(reportId) {
        // Show loading
        document.getElementById('modalContent').innerHTML = `
            <div class="flex items-center justify-center py-12">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-orange-500"></div>
                <span class="ml-3 text-gray-600">Loading report details...</span>
            </div>
        `;
        
        document.getElementById('reportModal').classList.remove('hidden');
        document.getElementById('reportModal').style.display = 'flex';
        
        // Fetch report details from API
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
                    <!-- Report Info -->
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
                            <div class="flex justify-between">
                                <span class="text-gray-600">Priority:</span>
                                <span class="font-medium">${reportData.priority}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Submitted:</span>
                                <span class="font-medium">${reportData.created_at}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    ${reportData.description ? `
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <h4 class="font-semibold text-gray-800 mb-3">Description</h4>
                        <p class="text-gray-700 text-sm">${reportData.description}</p>
                    </div>
                    ` : ''}

                    <!-- Location Info -->
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
                            ${reportData.latitude && reportData.longitude ? `
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Coordinates:</span>
                                    <span class="font-medium">${parseFloat(reportData.latitude).toFixed(4)}, ${parseFloat(reportData.longitude).toFixed(4)}</span>
                                </div>
                            ` : ''}
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

                    <!-- Map -->
                    ${reportData.latitude && reportData.longitude ? `
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <h4 class="font-semibold text-gray-800 mb-3">Location Map</h4>
                        <div id="reportMap" style="height: 200px;" class="rounded-lg"></div>
                    </div>
                    ` : ''}

                    <!-- Actions -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t">
                        ${reportData.latitude && reportData.longitude ? `
                        <a href="https://www.google.com/maps/dir/?api=1&destination=${reportData.latitude},${reportData.longitude}&origin={{ $collectorLat }},{{ $collectorLng }}" 
                           target="_blank" 
                           class="flex-1 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium text-center transition-colors duration-200">
                            <i class="fas fa-directions mr-2"></i>Get Directions
                        </a>
                        ` : ''}
                        
                        ${reportData.status === 'assigned' ? `
                        <form method="POST" action="/collector/report/${reportData.id}/confirm" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                                <i class="fas fa-check-circle mr-2"></i>Confirm Assignment
                            </button>
                        </form>
                        ` : reportData.status === 'enroute' ? `
                        <form method="POST" action="/collector/report/${reportData.id}/start" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                                <i class="fas fa-check mr-2"></i>Mark Collected
                            </button>
                        </form>
                        ` : ''}
                    </div>
                </div>
            `;

            // Initialize map if coordinates are available
            if (reportData.latitude && reportData.longitude) {
                initReportMap(parseFloat(reportData.latitude), parseFloat(reportData.longitude));
            }
        })
        .catch(error => {
            console.error('Error fetching report details:', error);
            document.getElementById('modalContent').innerHTML = `
                <div class="text-center py-12">
                    <div class="w-16 h-16 mx-auto bg-red-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Error Loading Report</h3>
                    <p class="text-gray-600">Unable to load report details. Please try again.</p>
                </div>
            `;
        });
    }

    function closeReportModal() {
        document.getElementById('reportModal').style.display = 'none';
    }

    function initReportMap(lat, lng) {
        const mapOptions = {
            center: { lat: lat, lng: lng },
            zoom: 15,
            mapTypeId: 'roadmap'
        };
        
        const map = new google.maps.Map(document.getElementById('reportMap'), mapOptions);
        
        const marker = new google.maps.Marker({
            position: { lat: lat, lng: lng },
            map: map,
            title: 'Waste Collection Location',
            icon: {
                url: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png',
                scaledSize: new google.maps.Size(32, 32)
            }
        });
    }

    function refreshDashboard() {
        window.location.reload();
    }

    function showAllReports() {
        window.location.href = '{{ route("collector.reports.all") }}';
    }

    function showCompletedReports() {
        window.location.href = '{{ route("collector.reports.completed") }}';
    }

    // Initialize location tracking
    updateCollectorLocation();
    setInterval(updateCollectorLocation, 30000);

    // Close modal when clicking outside
    document.getElementById('reportModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeReportModal();
        }
    });
</script>
@endsection