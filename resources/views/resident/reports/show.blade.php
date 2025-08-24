@extends('layouts.app')

@section('content')
<style>
    /* Enhanced animations and transitions */
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .hover-lift:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }
    
    .gradient-bg {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    
    .glass-effect {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .card-shadow {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    
    .enhanced-shadow {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    /* Status-based styling */
    .status-pending { background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); }
    .status-assigned { background: linear-gradient(135deg, #dbeafe 0%, #93c5fd 100%); }
    .status-enroute { background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%); }
    .status-collected { background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); }
    .status-closed { background: linear-gradient(135deg, #f3f4f6 0%, #d1d5db 100%); }
    .status-cancelled { background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); }
    
    /* Smooth transitions */
    * {
        transition: color 0.3s ease, background-color 0.3s ease, border-color 0.3s ease, transform 0.3s ease;
    }

    /* Mobile responsive enhancements */
    @media (max-width: 640px) {
        .progress-timeline .flex {
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .progress-timeline .flex-1 {
            min-width: 70px;
        }
        
        .mobile-stack {
            flex-direction: column;
            gap: 1rem;
        }
    }
</style>

<div class="min-h-screen bg-gradient-to-br from-gray-50 via-green-50 to-blue-50">
  <!-- Enhanced Header Section with Gradient -->
  <div class="gradient-bg text-white shadow-xl">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
      <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center space-x-4">
          <a href="{{ route('resident.reports.index') }}" 
             class="flex items-center text-white hover:text-green-200 transition-all duration-300 group">
            <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center mr-3 group-hover:bg-white/30 transition-colors">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
              </svg>
            </div>
            <span class="font-medium">Back to Reports</span>
          </a>
        </div>
        
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
          <h1 class="text-2xl sm:text-3xl font-bold">Report Details</h1>
          <div class="flex items-center space-x-2 bg-white/20 px-4 py-2 rounded-full">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.99 1.99 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            <span class="text-sm font-medium">{{ $report->reference_code }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6 sm:py-8 space-y-6 sm:space-y-8">
    <!-- Enhanced Status Card -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden hover-lift">
      <!-- Status Header with Gradient -->
      <div class="status-{{ strtolower($report->status) }} px-6 sm:px-8 py-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
          <div class="flex items-center space-x-4">
            <div class="w-4 h-4 rounded-full bg-current opacity-70 animate-pulse"></div>
            <div>
              <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 capitalize">{{ $report->status }}</h2>
              <p class="text-sm text-gray-600 mt-1">Current status of your waste collection request</p>
            </div>
          </div>
          <div class="flex items-center space-x-2 bg-white/80 px-4 py-2 rounded-full shadow-sm">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm font-medium text-gray-700">
              {{ optional($report->created_at)->format('M d, Y') }}
            </span>
          </div>
        </div>
      </div>

      <!-- Progress Timeline -->
      <div class="px-6 sm:px-8 py-8 bg-gradient-to-r from-gray-50 to-white">
        @php
          $statuses = ['pending', 'assigned', 'enroute', 'collected', 'closed'];
          $current = array_search(strtolower($report->status), $statuses);
          if($current === false && strtolower($report->status) === 'cancelled') {
            $current = 0; // Show only first step for cancelled
          }
        @endphp

        <div class="relative progress-timeline">
          <div class="flex justify-between items-center mobile-stack sm:flex-row">
            @foreach($statuses as $idx => $status)
              <div class="flex flex-col items-center relative flex-1 group">
                <!-- Enhanced Status Icon -->
                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full flex items-center justify-center mb-3 transition-all duration-500 transform group-hover:scale-110
                  @if($current !== false && $current >= $idx && strtolower($report->status) !== 'cancelled') 
                    bg-gradient-to-r from-green-400 to-green-600 text-white shadow-lg shadow-green-200
                  @elseif($idx == 0 && strtolower($report->status) === 'cancelled') 
                    bg-gradient-to-r from-red-400 to-red-600 text-white shadow-lg shadow-red-200
                  @else 
                    bg-gray-200 text-gray-400 @endif">
                  @if($idx == 0)
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                  @elseif($idx == 1)
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                  @elseif($idx == 2)
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                  @elseif($idx == 3)
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                  @else
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                  @endif
                </div>
                
                <!-- Enhanced Status Label -->
                <span class="text-xs sm:text-sm font-semibold text-center capitalize px-2 py-1 rounded-full transition-all duration-300
                  @if($current !== false && $current >= $idx && strtolower($report->status) !== 'cancelled') 
                    text-green-700 bg-green-100
                  @elseif($idx == 0 && strtolower($report->status) === 'cancelled') 
                    text-red-700 bg-red-100
                  @else 
                    text-gray-500 bg-gray-100 @endif">
                  @if($status === 'enroute')
                    En Route
                  @else
                    {{ $status }}
                  @endif
                </span>
                
                <!-- Enhanced Connection Line -->
                @if(!$loop->last)
                  <div class="hidden sm:block absolute top-6 left-1/2 w-full h-1 -z-10 transform translate-x-1/2 rounded-full transition-all duration-500
                    @if($current !== false && $current > $idx && strtolower($report->status) !== 'cancelled') 
                      bg-gradient-to-r from-green-400 to-green-500 shadow-sm
                    @else 
                      bg-gray-300 @endif">
                  </div>
                @endif
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>

    <!-- Enhanced Report Information Card -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden hover-lift">
      <div class="bg-gradient-to-r from-blue-50 to-green-50 px-6 sm:px-8 py-6 border-b border-gray-100">
        <div class="flex items-center space-x-3">
          <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-green-500 rounded-xl flex items-center justify-center shadow-lg">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
          </div>
          <h3 class="text-xl sm:text-2xl font-bold text-gray-800">Report Information</h3>
        </div>
      </div>
      
      <div class="p-6 sm:p-8">
        <div class="grid lg:grid-cols-2 gap-8">
          <div class="space-y-6">
            <!-- Waste Type -->
            <div class="flex items-start space-x-4 p-4 bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl border border-gray-100 hover-lift">
              <div class="w-12 h-12 bg-gradient-to-r from-green-400 to-green-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
              </div>
              <div class="flex-1">
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Waste Type</p>
                <p class="text-lg font-bold text-gray-800 mt-1">{{ $report->waste_type ?? 'Not specified' }}</p>
              </div>
            </div>

            <!-- Location -->
            <div class="flex items-start space-x-4 p-4 bg-gradient-to-r from-gray-50 to-green-50 rounded-xl border border-gray-100 hover-lift">
              <div class="w-12 h-12 bg-gradient-to-r from-blue-400 to-blue-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
              </div>
              <div class="flex-1">
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Location</p>
                <p class="text-lg font-bold text-gray-800 mt-1 break-words">{{ $report->location ?? 'Location not specified' }}</p>
              </div>
            </div>

            <!-- Submitted Time -->
            <div class="flex items-start space-x-4 p-4 bg-gradient-to-r from-gray-50 to-purple-50 rounded-xl border border-gray-100 hover-lift">
              <div class="w-12 h-12 bg-gradient-to-r from-purple-400 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
              <div class="flex-1">
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Submitted</p>
                <p class="text-lg font-bold text-gray-800 mt-1">{{ optional($report->created_at)->format('M d, Y \a\t H:i') }}</p>
              </div>
            </div>
          </div>

          <div class="space-y-6">
            @if($report->additional_details)
            <!-- Additional Details -->
            <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-xl p-6 border border-yellow-100 shadow-sm hover-lift">
              <div class="flex items-center space-x-3 mb-4">
                <div class="w-8 h-8 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-lg flex items-center justify-center">
                  <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                  </svg>
                </div>
                <p class="text-sm font-bold text-gray-700 uppercase tracking-wide">Additional Details</p>
              </div>
              <div class="bg-white/70 rounded-lg p-4 border border-yellow-200">
                <p class="text-gray-800 text-sm leading-relaxed">{{ $report->additional_details }}</p>
              </div>
            </div>
            @endif

            @if($report->image_path)
            <!-- Uploaded Image -->
            <div class="bg-gradient-to-br from-green-50 to-teal-50 rounded-xl p-6 border border-green-100 shadow-sm hover-lift">
              <div class="flex items-center space-x-3 mb-4">
                <div class="w-8 h-8 bg-gradient-to-r from-green-400 to-teal-500 rounded-lg flex items-center justify-center">
                  <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                </div>
                <p class="text-sm font-bold text-gray-700 uppercase tracking-wide">Uploaded Image</p>
              </div>
              <div class="relative overflow-hidden rounded-xl border-2 border-white shadow-lg">
                <img src="{{ asset('storage/' . $report->image_path) }}" 
                     alt="Report image" 
                     class="w-full h-48 object-cover transition-transform duration-300 hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
              </div>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>

    <!-- Enhanced Collector Information -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden hover-lift">
      <div class="bg-gradient-to-r from-indigo-50 to-blue-50 px-6 sm:px-8 py-6 border-b border-gray-100">
        <div class="flex items-center space-x-3">
          <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-blue-500 rounded-xl flex items-center justify-center shadow-lg">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
          </div>
          <h3 class="text-xl sm:text-2xl font-bold text-gray-800">Assigned Collector</h3>
        </div>
      </div>
      
      <div class="p-6 sm:p-8">
        @if($report->collector)
          <div class="flex flex-col sm:flex-row items-center sm:items-start space-y-4 sm:space-y-0 sm:space-x-6 p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
            <div class="relative">
              <div class="w-20 h-20 sm:w-24 sm:h-24 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center text-white text-2xl sm:text-3xl font-bold shadow-xl transform hover:scale-105 transition-transform duration-300">
                {{ substr($report->collector->name, 0, 1) }}
              </div>
              <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-green-500 rounded-full border-4 border-white flex items-center justify-center shadow-lg">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
              </div>
            </div>
            
            <div class="flex-1 text-center sm:text-left">
              <h4 class="text-xl sm:text-2xl font-bold text-gray-800 mb-2">{{ $report->collector->name }}</h4>
              <div class="space-y-2">
                <div class="flex items-center justify-center sm:justify-start space-x-2">
                  <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                  </svg>
                  <p class="text-sm sm:text-base font-medium text-gray-700">
                    {{ $report->collector->contact ?? 'Contact not available' }}
                  </p>
                </div>
                
                @if($report->assigned_at)
                <div class="flex items-center justify-center sm:justify-start space-x-2">
                  <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m0 0V7a1 1 0 011 1v8a1 1 0 01-1 1H9a1 1 0 01-1-1V8a1 1 0 011-1h4v0z"/>
                  </svg>
                  <p class="text-xs sm:text-sm text-gray-600">
                    Assigned on {{ $report->assigned_at->format('M d, Y \a\t H:i') }}
                  </p>
                </div>
                @endif
              </div>
              
              <!-- Collector Status Badge -->
              <div class="mt-4 inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-semibold shadow-sm">
                <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                Active Collector
              </div>
            </div>
          </div>
        @else
          <div class="text-center py-12">
            <div class="relative inline-block">
              <div class="w-20 h-20 sm:w-24 sm:h-24 bg-gradient-to-r from-gray-200 to-gray-300 rounded-2xl mx-auto flex items-center justify-center mb-6 shadow-lg">
                <svg class="w-10 h-10 sm:w-12 sm:h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
              </div>
              <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-yellow-500 rounded-full border-4 border-white flex items-center justify-center shadow-lg">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
            </div>
            
            <div class="max-w-md mx-auto">
              <h4 class="text-xl sm:text-2xl font-bold text-gray-800 mb-3">No collector assigned yet</h4>
              <p class="text-gray-600 mb-4">Your report is being processed and will be assigned to a collector soon.</p>
              
              <div class="inline-flex items-center px-6 py-3 bg-yellow-100 text-yellow-800 rounded-full text-sm font-semibold shadow-sm">
                <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2 animate-pulse"></div>
                Awaiting Assignment
              </div>
            </div>
          </div>
        @endif
      </div>
    </div>

    <!-- Enhanced Map Section -->
    @php
      $lat = $report->verified_lat ?? $report->latitude;
      $lng = $report->verified_lng ?? $report->longitude;
      $collectorLat = optional($report->collector)->latitude;
      $collectorLng = optional($report->collector)->longitude;
    @endphp

    @if($lat && $lng)
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden hover-lift">
      <div class="bg-gradient-to-r from-teal-50 to-green-50 px-6 sm:px-8 py-6 border-b border-gray-100">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-r from-teal-500 to-green-500 rounded-xl flex items-center justify-center shadow-lg">
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
              </svg>
            </div>
            <h3 class="text-xl sm:text-2xl font-bold text-gray-800">Location Map</h3>
          </div>
          <div class="hidden sm:flex items-center space-x-2 text-sm text-gray-600">
            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
            <span>Report Location</span>
            @if($collectorLat && $collectorLng)
              <div class="w-3 h-3 bg-blue-500 rounded-full ml-4"></div>
              <span>Collector Location</span>
            @endif
          </div>
        </div>
      </div>
      
      <div class="p-4 sm:p-6">
        <div class="relative overflow-hidden rounded-xl border-2 border-gray-100 shadow-inner">
          <div id="reportMap" class="w-full h-80 sm:h-96"></div>
          <!-- Map overlay for loading state -->
          <div id="mapLoading" class="absolute inset-0 bg-gradient-to-r from-green-50 to-blue-50 flex items-center justify-center">
            <div class="text-center">
              <div class="w-12 h-12 border-4 border-green-500 border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
              <p class="text-gray-600 font-medium">Loading map...</p>
            </div>
          </div>
        </div>
        
        <!-- Mobile legend -->
        <div class="sm:hidden mt-4 flex flex-wrap justify-center gap-4 text-sm">
          <div class="flex items-center space-x-2">
            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
            <span class="text-gray-600">Report Location</span>
          </div>
          @if($collectorLat && $collectorLng)
            <div class="flex items-center space-x-2">
              <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
              <span class="text-gray-600">Collector Location</span>
            </div>
          @endif
        </div>
      </div>
    </div>
    @endif

    <!-- Enhanced Actions Section -->
    <div class="grid md:grid-cols-2 gap-6">
      <!-- Download PDF Button -->
      <a href="{{ route('resident.reports.pdf', $report->id) }}" 
         class="group bg-gradient-to-r from-green-500 to-green-600 text-white py-4 px-6 rounded-2xl font-bold text-center hover:from-green-600 hover:to-green-700 transform hover:scale-105 transition-all duration-300 shadow-xl hover:shadow-2xl flex items-center justify-center space-x-3">
        <svg class="w-5 h-5 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <span>Download PDF Report</span>
      </a>
      
      <!-- Cancel Report or Info Card -->
      @if(strtolower($report->status) === 'pending')
        <form action="{{ route('resident.reports.cancel', $report->id) }}"
              method="POST"
              onsubmit="return confirm('Are you sure you want to cancel this report?')"
              class="w-full">
          @csrf
          <button type="submit" 
                  class="group w-full bg-gradient-to-r from-red-500 to-red-600 text-white py-4 px-6 rounded-2xl font-bold hover:from-red-600 hover:to-red-700 transform hover:scale-105 transition-all duration-300 shadow-xl hover:shadow-2xl flex items-center justify-center space-x-3">
            <svg class="w-5 h-5 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            <span>Cancel Report</span>
          </button>
        </form>
      @else
        <div class="bg-gradient-to-r from-gray-100 to-gray-200 rounded-2xl p-6 text-center shadow-lg border border-gray-200">
          <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-gray-300 to-gray-400 rounded-2xl mb-4 shadow-lg">
            <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <h4 class="font-bold text-gray-700 mb-2">Report Processing</h4>
          <p class="text-sm text-gray-600 leading-relaxed">Reports can only be cancelled while in pending status. Your report is currently being processed.</p>
        </div>
      @endif
    </div>

    <!-- Enhanced Timeline -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden hover-lift">
      <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 sm:px-8 py-6 border-b border-gray-100">
        <div class="flex items-center space-x-3">
          <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <h3 class="text-xl sm:text-2xl font-bold text-gray-800">Activity Timeline</h3>
        </div>
      </div>
      
      <div class="p-6 sm:p-8">
        <div class="relative">
          <!-- Timeline line -->
          <div class="absolute left-6 top-8 bottom-8 w-0.5 bg-gradient-to-b from-green-400 via-blue-400 to-purple-400"></div>
          
          <div class="space-y-8">
            <!-- Report Created -->
            <div class="relative flex items-start space-x-6 group">
              <div class="relative">
                <div class="w-12 h-12 bg-gradient-to-r from-green-400 to-green-600 rounded-xl flex items-center justify-center text-white shadow-lg transform group-hover:scale-110 transition-transform duration-300">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                  </svg>
                </div>
                <div class="absolute -top-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white animate-pulse"></div>
              </div>
              <div class="flex-1 bg-gradient-to-r from-green-50 to-emerald-50 p-4 sm:p-6 rounded-xl border border-green-100 group-hover:shadow-lg transition-all duration-300">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                  <h4 class="text-lg font-bold text-gray-800">Report Created</h4>
                  <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 text-sm font-semibold rounded-full">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Completed
                  </span>
                </div>
                <p class="text-gray-600 text-sm mt-2">{{ $report->created_at->format('l, M d, Y \a\t H:i') }}</p>
                <p class="text-gray-500 text-xs mt-1">Your waste collection request was successfully submitted</p>
              </div>
            </div>

            @if($report->assigned_at)
            <!-- Collector Assigned -->
            <div class="relative flex items-start space-x-6 group">
              <div class="relative">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-400 to-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg transform group-hover:scale-110 transition-transform duration-300">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                  </svg>
                </div>
                <div class="absolute -top-1 -right-1 w-4 h-4 bg-blue-500 rounded-full border-2 border-white animate-pulse"></div>
              </div>
              <div class="flex-1 bg-gradient-to-r from-blue-50 to-indigo-50 p-4 sm:p-6 rounded-xl border border-blue-100 group-hover:shadow-lg transition-all duration-300">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                  <h4 class="text-lg font-bold text-gray-800">Collector Assigned</h4>
                  <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 text-sm font-semibold rounded-full">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Completed
                  </span>
                </div>
                <p class="text-gray-600 text-sm mt-2">{{ $report->assigned_at->format('l, M d, Y \a\t H:i') }}</p>
                <p class="text-gray-500 text-xs mt-1">A collector has been assigned to handle your request</p>
              </div>
            </div>
            @endif

            @if($report->collected_at)
            <!-- Waste Collected -->
            <div class="relative flex items-start space-x-6 group">
              <div class="relative">
                <div class="w-12 h-12 bg-gradient-to-r from-emerald-400 to-emerald-600 rounded-xl flex items-center justify-center text-white shadow-lg transform group-hover:scale-110 transition-transform duration-300">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </div>
                <div class="absolute -top-1 -right-1 w-4 h-4 bg-emerald-500 rounded-full border-2 border-white animate-pulse"></div>
              </div>
              <div class="flex-1 bg-gradient-to-r from-emerald-50 to-green-50 p-4 sm:p-6 rounded-xl border border-emerald-100 group-hover:shadow-lg transition-all duration-300">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                  <h4 class="text-lg font-bold text-gray-800">Waste Collected</h4>
                  <span class="inline-flex items-center px-3 py-1 bg-emerald-100 text-emerald-800 text-sm font-semibold rounded-full">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Completed
                  </span>
                </div>
                <p class="text-gray-600 text-sm mt-2">{{ $report->collected_at->format('l, M d, Y \a\t H:i') }}</p>
                <p class="text-gray-500 text-xs mt-1">Your waste has been successfully collected</p>
              </div>
            </div>
            @endif

            @if($report->closed_at)
            <!-- Report Closed -->
            <div class="relative flex items-start space-x-6 group">
              <div class="relative">
                <div class="w-12 h-12 bg-gradient-to-r from-purple-400 to-purple-600 rounded-xl flex items-center justify-center text-white shadow-lg transform group-hover:scale-110 transition-transform duration-300">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </div>
                <div class="absolute -top-1 -right-1 w-4 h-4 bg-purple-500 rounded-full border-2 border-white animate-pulse"></div>
              </div>
              <div class="flex-1 bg-gradient-to-r from-purple-50 to-indigo-50 p-4 sm:p-6 rounded-xl border border-purple-100 group-hover:shadow-lg transition-all duration-300">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                  <h4 class="text-lg font-bold text-gray-800">Report Closed</h4>
                  <span class="inline-flex items-center px-3 py-1 bg-purple-100 text-purple-800 text-sm font-semibold rounded-full">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Completed
                  </span>
                </div>
                <p class="text-gray-600 text-sm mt-2">{{ $report->closed_at->format('l, M d, Y \a\t H:i') }}</p>
                <p class="text-gray-500 text-xs mt-1">The waste collection process has been completed</p>
              </div>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  @if($lat && $lng)
  <script>
function initReportMap() {
  // Hide loading overlay
  const loading = document.getElementById("mapLoading");
  if (loading) loading.style.display = 'none';

  const reportPos = { lat: {{ (float)$lat }}, lng: {{ (float)$lng }} };
  const collectorPosRaw = {!! ($collectorLat ?? null) && ($collectorLng ?? null)
      ? '{ lat: '.(float)$collectorLat.', lng: '.(float)$collectorLng.' }'
      : 'null' !!};

  const el = document.getElementById("reportMap");
  if (!el) return;

  // Enhanced map styling
  const map = new google.maps.Map(el, {
    zoom: 16,
    center: reportPos,
    mapTypeControl: false,
    streetViewControl: false,
    fullscreenControl: true,
    zoomControl: true,
    gestureHandling: 'cooperative',
    styles: [
      {"featureType":"poi","stylers":[{"visibility":"off"}]},
      {"featureType":"road","elementType":"labels.icon","stylers":[{"visibility":"off"}]},
      {"featureType":"water","elementType":"all","stylers":[{"color":"#76c7c0"}]},
      {"featureType":"administrative","elementType":"geometry","stylers":[{"weight":0.6},{"color":"#10b981"}]},
      {"featureType":"landscape","elementType":"all","stylers":[{"color":"#f9fafb"}]},
      {"featureType":"road","elementType":"all","stylers":[{"color":"#ffffff"}]},
      {"featureType":"road","elementType":"geometry.stroke","stylers":[{"color":"#e5e7eb"}]}
    ]
  });

  // Enhanced Report Marker with custom icon
  const reportMarker = new google.maps.Marker({
    position: reportPos,
    map,
    zIndex: 100,
    title: @json($report->reference_code),
    animation: google.maps.Animation.DROP,
    icon: {
      path: google.maps.SymbolPath.CIRCLE,
      scale: 12,
      fillColor: '#10b981',
      fillOpacity: 1,
      strokeColor: '#ffffff',
      strokeWeight: 3
    }
  });

  // Enhanced Info Window with better styling
  const reportInfo = new google.maps.InfoWindow({
    content: `<div style="font-family: system-ui, -apple-system, sans-serif; padding: 12px; min-width: 250px;">
                <div style="display: flex; align-items: center; margin-bottom: 8px;">
                  <div style="width: 8px; height: 8px; background: #10b981; border-radius: 50%; margin-right: 8px;"></div>
                  <div style="font-weight: 700; color: #059669; font-size: 16px;">{{ addslashes($report->reference_code) }}</div>
                </div>
                <div style="color: #374151; margin-bottom: 8px; font-size: 14px; font-weight: 500;">📍 {{ addslashes($report->location ?? 'Reported location') }}</div>
                <div style="color: #6b7280; font-size: 13px; padding: 6px 10px; background: #f3f4f6; border-radius: 6px;">🗑️ {{ addslashes($report->waste_type ?? 'Waste type not specified') }}</div>
                <div style="margin-top: 8px; font-size: 12px; color: #9ca3af;">📅 {{ addslashes($report->created_at->format('M d, Y H:i')) }}</div>
              </div>`
  });
  
  reportInfo.open({ map, anchor: reportMarker });
  reportMarker.addListener('click', () => reportInfo.open({ map, anchor: reportMarker }));

  // Enhanced Collector Marker (Blue, if exists)
  if (collectorPosRaw) {
    let collectorPos = { ...collectorPosRaw };
    const near = (a,b) => Math.abs(a-b) < 1e-5;
    const almostSame = near(reportPos.lat, collectorPos.lat) && near(reportPos.lng, collectorPos.lng);
    if (almostSame) {
      collectorPos = { lat: collectorPos.lat + 0.00018, lng: collectorPos.lng + 0.00018 };
    }

    const collectorMarker = new google.maps.Marker({
      position: collectorPos,
      map,
      zIndex: 90,
      title: @json($report->collector->name ?? 'Collector'),
      animation: google.maps.Animation.DROP,
      icon: {
        path: google.maps.SymbolPath.CIRCLE,
        scale: 10,
        fillColor: '#3b82f6',
        fillOpacity: 1,
        strokeColor: '#ffffff',
        strokeWeight: 3
      }
    });

    const collectorInfo = new google.maps.InfoWindow({
      content: `<div style="font-family: system-ui, -apple-system, sans-serif; padding: 12px; min-width: 200px;">
                  <div style="display: flex; align-items: center; margin-bottom: 6px;">
                    <div style="width: 8px; height: 8px; background: #3b82f6; border-radius: 50%; margin-right: 8px;"></div>
                    <div style="font-weight: 700; color: #1e40af; font-size: 16px;">👷 {{ addslashes($report->collector->name ?? 'Collector') }}</div>
                  </div>
                  <div style="color: #6b7280; font-size: 13px;">📱 {{ addslashes($report->collector->contact ?? 'Contact not available') }}</div>
                </div>`
    });
    
    collectorMarker.addListener('click', () => collectorInfo.open({ map, anchor: collectorMarker }));

    if (!almostSame) {
      // Enhanced route line with gradient effect
      const route = new google.maps.Polyline({
        path: [collectorPos, reportPos],
        geodesic: true,
        strokeColor: '#3b82f6',
        strokeOpacity: 0.8,
        strokeWeight: 4,
        icons: [{
          icon: {
            path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
            scale: 3,
            fillColor: '#3b82f6',
            fillOpacity: 1,
            strokeWeight: 0
          },
          offset: '50%'
        }]
      });
      route.setMap(map);

      const bounds = new google.maps.LatLngBounds();
      bounds.extend(reportPos);
      bounds.extend(collectorPos);
      map.fitBounds(bounds);
    }
  }
}

// Initialize map when page loads
window.initReportMap = initReportMap;

// Show loading state initially
document.addEventListener('DOMContentLoaded', function() {
  const loading = document.getElementById("mapLoading");
  if (loading) loading.style.display = 'flex';
});
</script>
  <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&callback=initReportMap" async defer></script>
  @endif
</div>
@endsection