@extends('layouts.app')

@section('content')
<style>
    /* Status-based styling */
    .status-pending { background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); }
    .status-assigned { background: linear-gradient(135deg, #dbeafe 0%, #93c5fd 100%); }
    .status-enroute { background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%); }
    .status-collected { background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); }
    .status-closed { background: linear-gradient(135deg, #f3f4f6 0%, #d1d5db 100%); }
    .status-cancelled { background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); }
    
    /* Button hover animations only */
    .btn-hover {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .catch(error => {
    clearTimeout(timeoutId); // Clear the timeout
    console.error('Error:', error);
    // Show the actual error message instead of generic network error
    alert(error.message || 'An error occurred while processing your request. The request may have been processed - please refresh the page to check.');   .btn-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }
</style>

<div class="min-h-screen bg-gradient-to-br from-gray-50 via-green-50 to-blue-50">
  <!-- Enhanced Header Section -->
  <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white shadow-xl">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-4 sm:py-6">
      <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center space-x-4">
          <a href="{{ route('resident.reports.index') }}" 
             class="flex items-center text-white hover:text-green-200 transition-colors duration-200">
            <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center mr-3">
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
              </svg>
            </div>
            <span class="font-medium">Back to Reports</span>
          </a>
        </div>
        
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
          <h1 class="text-xl sm:text-2xl font-bold">Report Details</h1>
          <div class="flex items-center space-x-2 bg-white/20 px-3 py-1 rounded-full">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.99 1.99 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            <span class="text-sm font-medium">{{ $report->reference_code }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Display Flash Messages -->
  @if(session('error'))
    <div class="max-w-6xl mx-auto px-4 sm:px-6 mb-6">
      <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
        <div class="flex items-center">
          <svg class="w-5 h-5 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <p class="text-red-700 font-medium">{{ session('error') }}</p>
        </div>
      </div>
    </div>
  @endif

  @if(session('success'))
    <div class="max-w-6xl mx-auto px-4 sm:px-6 mb-6">
      <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm">
        <div class="flex items-center">
          <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
          <p class="text-green-700 font-medium">{{ session('success') }}</p>
        </div>
      </div>
    </div>
  @endif

  <!-- Main Content -->
  <div class="max-w-6xl mx-auto px-4 sm:px-6 py-4 sm:py-6 space-y-4 sm:space-y-6">
    <!-- Status Card -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
      <!-- Status Header -->
      <div class="status-{{ strtolower($report->status) }} px-4 sm:px-6 py-4">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
          <div class="flex items-center space-x-3">
            <div class="w-3 h-3 rounded-full bg-current opacity-70"></div>
            <div>
              <h2 class="text-lg sm:text-xl font-bold text-gray-800 capitalize">{{ $report->status }}</h2>
              <p class="text-sm text-gray-600 mt-1">Current status of your waste collection request</p>
            </div>
          </div>
          <div class="flex items-center space-x-2 bg-white/80 px-3 py-1 rounded-full shadow-sm">
            <svg class="w-3 h-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            $current = 0;
          }
        @endphp

        <div class="relative">
          <div class="flex justify-between items-center">
            @foreach($statuses as $idx => $status)
              @php
                $isCompleted = $current !== false && $current >= $idx && strtolower($report->status) !== 'cancelled';
                $isCancelled = $idx == 0 && strtolower($report->status) === 'cancelled';
                
                $iconClasses = $isCompleted 
                  ? 'bg-gradient-to-r from-green-400 to-green-600 text-white shadow-lg'
                  : ($isCancelled 
                    ? 'bg-gradient-to-r from-red-400 to-red-600 text-white shadow-lg'
                    : 'bg-gray-200 text-gray-400');
                    
                $labelClasses = $isCompleted
                  ? 'text-green-700 bg-green-100'
                  : ($isCancelled
                    ? 'text-red-700 bg-red-100'
                    : 'text-gray-500 bg-gray-100');
              @endphp
              
              <div class="flex flex-col items-center flex-1">
                <!-- Status Icon -->
                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full flex items-center justify-center mb-3 {{ $iconClasses }}">
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
                
                <!-- Status Label -->
                <span class="text-xs sm:text-sm font-semibold text-center capitalize px-2 py-1 rounded-full {{ $labelClasses }}">
                  @if($status === 'enroute')
                    En Route
                  @else
                    {{ $status }}
                  @endif
                </span>
                
                <!-- Connection Line -->
                @if(!$loop->last)
                  @php
                    $lineClasses = ($current !== false && $current > $idx && strtolower($report->status) !== 'cancelled')
                      ? 'bg-gradient-to-r from-green-400 to-green-500'
                      : 'bg-gray-300';
                  @endphp
                  <div class="hidden sm:block absolute top-6 left-1/2 w-full h-1 -z-10 transform translate-x-1/2 rounded-full {{ $lineClasses }}">
                  </div>
                @endif
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>

    <!-- Report Information Card -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
      <div class="bg-gradient-to-r from-blue-50 to-green-50 px-4 sm:px-6 py-4 border-b border-gray-100">
        <div class="flex items-center space-x-3">
          <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-green-500 rounded-xl flex items-center justify-center shadow-lg">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
          </div>
          <h3 class="text-lg sm:text-xl font-bold text-gray-800">Report Information</h3>
        </div>
      </div>
      
      <div class="p-4 sm:p-6">
        <div class="grid lg:grid-cols-2 gap-6">
          <div class="space-y-4">
            <!-- Waste Type -->
            <div class="flex items-start space-x-3 p-3 bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl border border-gray-100">
              <div class="w-10 h-10 bg-gradient-to-r from-green-400 to-green-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
              </div>
              <div class="flex-1">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Waste Type</p>
                <p class="text-base font-bold text-gray-800 mt-1">{{ $report->waste_type ?? 'Not specified' }}</p>
              </div>
            </div>

            <!-- Location -->
            <div class="flex items-start space-x-3 p-3 bg-gradient-to-r from-gray-50 to-green-50 rounded-xl border border-gray-100">
              <div class="w-10 h-10 bg-gradient-to-r from-blue-400 to-blue-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
              </div>
              <div class="flex-1">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Location</p>
                <p class="text-base font-bold text-gray-800 mt-1 break-words">{{ $report->location ?? 'Location not specified' }}</p>
              </div>
            </div>

            <!-- Submitted Time -->
            <div class="flex items-start space-x-3 p-3 bg-gradient-to-r from-gray-50 to-purple-50 rounded-xl border border-gray-100">
              <div class="w-10 h-10 bg-gradient-to-r from-purple-400 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
              <div class="flex-1">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Submitted</p>
                <p class="text-base font-bold text-gray-800 mt-1">{{ optional($report->created_at)->format('M d, Y \a\t h:i A') }}</p>
              </div>
            </div>
          </div>

          <div class="space-y-4">
            @if($report->additional_details)
            <!-- Additional Details -->
            <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-xl p-4 border border-yellow-100 shadow-sm">
              <div class="flex items-center space-x-3 mb-3">
                <div class="w-6 h-6 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-lg flex items-center justify-center">
                  <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                  </svg>
                </div>
                <p class="text-xs font-bold text-gray-700 uppercase tracking-wide">Additional Details</p>
              </div>
              <div class="bg-white/70 rounded-lg p-3 border border-yellow-200">
                <p class="text-gray-800 text-sm leading-relaxed">{{ $report->additional_details }}</p>
              </div>
            </div>
            @endif

            @if($report->image_path)
            <!-- Uploaded Image -->
            <div class="bg-gradient-to-br from-green-50 to-teal-50 rounded-xl p-4 border border-green-100 shadow-sm">
              <div class="flex items-center space-x-3 mb-3">
                <div class="w-6 h-6 bg-gradient-to-r from-green-400 to-teal-500 rounded-lg flex items-center justify-center">
                  <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                </div>
                <p class="text-xs font-bold text-gray-700 uppercase tracking-wide">Uploaded Image</p>
              </div>
              <div class="relative overflow-hidden rounded-xl border-2 border-white shadow-lg">
                <img src="{{ asset('storage/' . $report->image_path) }}" 
                     alt="Report image" 
                     class="w-full h-auto max-h-64 object-contain bg-gray-50">
              </div>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>

    <!-- Collector Information -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
      <div class="bg-gradient-to-r from-indigo-50 to-blue-50 px-4 sm:px-6 py-4 border-b border-gray-100">
        <div class="flex items-center space-x-3">
          <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-blue-500 rounded-xl flex items-center justify-center shadow-lg">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
          </div>
          <h3 class="text-lg sm:text-xl font-bold text-gray-800">Assigned Collector</h3>
        </div>
      </div>
      
      <div class="p-4 sm:p-6">
        @if($report->collector)
          <div class="flex flex-col sm:flex-row items-center sm:items-start space-y-4 sm:space-y-0 sm:space-x-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
            <div class="relative">
              <div class="w-16 h-16 sm:w-18 sm:h-18 rounded-2xl overflow-hidden shadow-xl border-3 border-white">
                @if($report->collector->profile_image)
                  <img src="{{ asset('storage/' . $report->collector->profile_image) }}" 
                       alt="{{ $report->collector->name }}" 
                       class="w-full h-full object-cover">
                @else
                  <div class="w-full h-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center text-white text-lg sm:text-xl font-bold">
                    {{ substr($report->collector->name, 0, 1) }}
                  </div>
                @endif
              </div>
              <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-500 rounded-full border-3 border-white flex items-center justify-center shadow-lg">
                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
              </div>
            </div>
            
            <div class="flex-1 text-center sm:text-left">
              <h4 class="text-lg sm:text-xl font-bold text-gray-800 mb-2">{{ $report->collector->name }}</h4>
              <div class="space-y-2">
                <div class="flex items-center justify-center sm:justify-start space-x-2">
                  <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                  </svg>
                  <p class="text-sm font-medium text-gray-700">
                    {{ $report->collector->contact ?? 'Contact not available' }}
                  </p>
                </div>
                
                @if($report->assigned_at)
                <div class="flex items-center justify-center sm:justify-start space-x-2">
                  <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m0 0V7a1 1 0 011 1v8a1 1 0 01-1 1H9a1 1 0 01-1-1V8a1 1 0 011-1h4v0z"/>
                  </svg>
                  <p class="text-xs text-gray-600">
                    Assigned on {{ $report->assigned_at->format('M d, Y \a\t H:i') }}
                  </p>
                </div>
                @endif
              </div>
              
              <!-- Collector Status Badge -->
              <div class="mt-3 inline-flex items-center px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold shadow-sm">
                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                Active Collector
              </div>
            </div>
          </div>
        @else
          <div class="text-center py-8">
            <div class="w-16 h-16 sm:w-18 sm:h-18 bg-gradient-to-r from-gray-200 to-gray-300 rounded-2xl mx-auto flex items-center justify-center mb-4 shadow-lg">
              <svg class="w-8 h-8 sm:w-9 sm:h-9 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
            </div>
            <h4 class="text-lg sm:text-xl font-bold text-gray-800 mb-2">No collector assigned yet</h4>
            <p class="text-gray-600 mb-3 text-sm">Your report is being processed and will be assigned to a collector soon.</p>
            
            <div class="inline-flex items-center px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold shadow-sm">
              <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></div>
              Awaiting Assignment
            </div>
          </div>
        @endif
      </div>
    </div>
    <!-- Map Section -->
    @php
      $lat = $report->verified_lat ?? $report->latitude;
      $lng = $report->verified_lng ?? $report->longitude;
      $collectorLat = optional($report->collector)->latitude;
      $collectorLng = optional($report->collector)->longitude;
    @endphp

    @if($lat && $lng)
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
      <div class="bg-gradient-to-r from-teal-50 to-green-50 px-4 sm:px-6 py-4 border-b border-gray-100">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-gradient-to-r from-teal-500 to-green-500 rounded-xl flex items-center justify-center shadow-lg">
              <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
              </svg>
            </div>
            <h3 class="text-lg sm:text-xl font-bold text-gray-800">Location Map</h3>
          </div>
          <div class="hidden sm:flex items-center space-x-2 text-xs text-gray-600">
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

    <!-- Actions Section -->
    @php
      // Count how many actions we'll have
      $actionCount = 1; // Download PDF is always available
      
      if ($report->canBeMarkedUrgent()) {
        $actionCount++;
      }
      
      if (in_array(strtolower($report->status), ['collected', 'closed'])) {
        $actionCount++; // Feedback button
      }
      
      if (strtolower($report->status) === 'pending') {
        $actionCount++; // Cancel button
      } else {
        $actionCount++; // Info card
      }
      
      // Determine grid classes based on action count
      $gridClasses = match(true) {
        $actionCount >= 4 => 'grid gap-6 lg:grid-cols-4 md:grid-cols-2',
        $actionCount === 3 => 'grid gap-6 lg:grid-cols-3 md:grid-cols-2',
        default => 'grid gap-6 md:grid-cols-2'
      };
    @endphp
    <div class="{{ $gridClasses }}">
      <!-- Download PDF Button -->
      <a href="{{ route('resident.reports.pdf', $report->id) }}" 
         class="btn-hover bg-gradient-to-r from-green-500 to-green-600 text-white py-4 px-6 rounded-2xl font-bold text-center shadow-xl flex items-center justify-center space-x-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <span>Download PDF Report</span>
      </a>

      <!-- Report Full Bin Button -->
      @if ($report->canBeMarkedUrgent())
        <button onclick="markUrgent({{ $report->id }})"
                class="btn-hover bg-gradient-to-r from-red-500 to-red-600 text-white py-4 px-6 rounded-2xl font-bold text-center shadow-xl flex items-center justify-center space-x-3">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
          </svg>
          <span>Report Full Bin</span>
        </button>
      @elseif ($report->is_urgent)
        <div class="bg-gradient-to-r from-red-100 to-red-200 rounded-2xl p-6 text-center shadow-lg border border-red-300">
          <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-r from-red-400 to-red-500 rounded-2xl mb-4 shadow-lg">
            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
          </div>
          <h3 class="text-lg sm:text-xl font-bold text-red-800 mb-2">Marked as Urgent</h3>
          <p class="text-sm text-red-700">Admin has been notified</p>
          @if($report->urgent_message)
            <p class="text-xs text-red-600 mt-2 italic">"{{ $report->urgent_message }}"</p>
          @endif
        </div>
      @endif
      
      <!-- Feedback Button - Show for completed/closed reports -->
      @if(in_array(strtolower($report->status), ['collected', 'closed']))
        <a href="{{ route('feedback.report.create', $report->id) }}" 
           class="btn-hover bg-gradient-to-r from-blue-500 to-blue-600 text-white py-4 px-6 rounded-2xl font-bold text-center shadow-xl flex items-center justify-center space-x-3">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
          </svg>
          <span>Give Feedback</span>
        </a>
      @endif
      
      <!-- Cancel Report or Info Card -->
      @if(strtolower($report->status) === 'pending')
        <form action="{{ route('resident.reports.cancel', $report->id) }}"
              method="POST"
              onsubmit="return confirm('Are you sure you want to cancel this report?')"
              class="w-full">
          @csrf
          <button type="submit" 
                  class="btn-hover w-full bg-gradient-to-r from-red-500 to-red-600 text-white py-4 px-6 rounded-2xl font-bold shadow-xl flex items-center justify-center space-x-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            <span>Cancel Report</span>
          </button>
        </form>
      @else
        @php
          $infoCardClasses = in_array(strtolower($report->status), ['collected', 'closed'])
            ? 'bg-gradient-to-r from-gray-100 to-gray-200 rounded-2xl p-6 text-center shadow-lg border border-gray-200 lg:col-span-1'
            : 'bg-gradient-to-r from-gray-100 to-gray-200 rounded-2xl p-6 text-center shadow-lg border border-gray-200';
        @endphp
        <div class="{{ $infoCardClasses }}">
          <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-r from-gray-300 to-gray-400 rounded-2xl mb-4 shadow-lg">
            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <h4 class="font-bold text-gray-700 mb-2 text-sm sm:text-base">
            @if(in_array(strtolower($report->status), ['collected', 'closed']))
              Report Completed
            @else
              Report Processing
            @endif
          </h4>
          <p class="text-xs sm:text-sm text-gray-600 leading-relaxed">
            @if(in_array(strtolower($report->status), ['collected', 'closed']))
              Your waste has been successfully collected. You can provide feedback about the service.
            @else
              Reports can only be cancelled while in pending status. Your report is currently being processed.
            @endif
          </p>
        </div>
      @endif
    </div>

    <!-- Activity Timeline -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
      <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-4 sm:px-6 py-4 border-b border-gray-100">
        <div class="flex items-center space-x-3">
          <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <h3 class="text-lg sm:text-xl font-bold text-gray-800">Activity Timeline</h3>
        </div>
      </div>
      
      <div class="p-4 sm:p-6">
        <div class="relative">
          <!-- Timeline line -->
          <div class="absolute left-5 top-6 bottom-6 w-0.5 bg-gradient-to-b from-green-400 via-blue-400 to-purple-400"></div>
          
          <div class="space-y-6">
            <!-- Report Created -->
            <div class="relative flex items-start space-x-4">
              <div class="w-10 h-10 bg-gradient-to-r from-green-400 to-green-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
              </div>
              <div class="flex-1 bg-gradient-to-r from-green-50 to-emerald-50 p-3 sm:p-4 rounded-xl border border-green-100">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                  <h4 class="text-base font-bold text-gray-800">Report Created</h4>
                  <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                    <svg class="w-2 h-2 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Completed
                  </span>
                </div>
                <p class="text-gray-600 text-sm mt-2">{{ $report->created_at->format('l, M d, Y \a\t h:i A') }}</p>
                <p class="text-gray-500 text-xs mt-1">Your waste collection request was successfully submitted</p>
              </div>
            </div>

            @if($report->assigned_at)
            <!-- Collector Assigned -->
            <div class="relative flex items-start space-x-4">
              <div class="w-10 h-10 bg-gradient-to-r from-blue-400 to-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
              </div>
              <div class="flex-1 bg-gradient-to-r from-blue-50 to-indigo-50 p-3 sm:p-4 rounded-xl border border-blue-100">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                  <h4 class="text-base font-bold text-gray-800">Collector Assigned</h4>
                  <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                    <svg class="w-2 h-2 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            <div class="relative flex items-start space-x-4">
              <div class="w-10 h-10 bg-gradient-to-r from-emerald-400 to-emerald-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
              <div class="flex-1 bg-gradient-to-r from-emerald-50 to-green-50 p-3 sm:p-4 rounded-xl border border-emerald-100">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                  <h4 class="text-base font-bold text-gray-800">Waste Collected</h4>
                  <span class="inline-flex items-center px-2 py-1 bg-emerald-100 text-emerald-800 text-xs font-semibold rounded-full">
                    <svg class="w-2 h-2 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            <div class="relative flex items-start space-x-4">
              <div class="w-10 h-10 bg-gradient-to-r from-purple-400 to-purple-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
              <div class="flex-1 bg-gradient-to-r from-purple-50 to-indigo-50 p-3 sm:p-4 rounded-xl border border-purple-100">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                  <h4 class="text-base font-bold text-gray-800">Report Closed</h4>
                  <span class="inline-flex items-center px-2 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded-full">
                    <svg class="w-2 h-2 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
// Mark report as urgent functionality
function markUrgent(reportId) {
  const message = prompt("Please provide a reason for marking this as urgent (optional):");
  if (message === null) return; // User cancelled
  
  // Show loading state
  const urgentBtn = document.querySelector(`button[onclick="markUrgent(${reportId})"]`);
  if (urgentBtn) {
    urgentBtn.disabled = true;
    urgentBtn.innerHTML = `
      <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
      <span>Processing...</span>`;
  }
  
  // Create a timeout to handle slow responses
  const timeoutId = setTimeout(() => {
    alert('The request is taking longer than expected. The report will be marked as urgent - please refresh the page in a moment to see the update.');
  }, 3000); // Show message after 3 seconds
  
  // AJAX request to mark as urgent
  fetch(`/resident/reports/${reportId}/urgent`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      'Accept': 'application/json'
    },
    body: JSON.stringify({ message: message })
  })
  .then(response => {
    clearTimeout(timeoutId); // Clear the timeout since we got a response
    // Check if the response is ok
    if (!response.ok) {
      // Try to get error message from response
      return response.text().then(errorText => {
        // Check if response is JSON
        try {
          const errorData = JSON.parse(errorText);
          throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
        } catch (e) {
          // Response is not JSON (likely HTML error page)
          console.error('Non-JSON response received:', errorText);
          throw new Error(`Server error (${response.status}). The request may have been processed - please refresh the page.`);
        }
      });
    }
    return response.text().then(text => {
      try {
        return JSON.parse(text);
      } catch (e) {
        console.error('Invalid JSON response:', text);
        throw new Error('Invalid response from server. The request may have been processed - please refresh the page.');
      }
    });
  })
  .then(data => {
    if (data.success) {
      // Show success message
      alert(data.message || 'Report has been marked as urgent and admin has been notified.');
      // Reload page to show updated state
      window.location.reload();
    } else {
      // Show error message
      alert(data.message || 'Failed to mark report as urgent. Please try again.');
      // Reset button
      if (urgentBtn) {
        urgentBtn.disabled = false;
        urgentBtn.innerHTML = `
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
          </svg>
          <span>Report Full Bin</span>`;
      }
    }
  })
  .catch(error => {
    console.error('Error:', error);
    // Show the actual error message instead of generic network error
    alert(error.message || 'An error occurred while processing your request. Please try refreshing the page.');
    // Reset button
    if (urgentBtn) {
      urgentBtn.disabled = false;
      urgentBtn.innerHTML = `
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
        </svg>
        <span>Report Full Bin</span>`;
    }
  });
}

function initReportMap() {
  const loading = document.getElementById("mapLoading");
  
  try {
    const reportPos = { lat: {{ (float)$lat }}, lng: {{ (float)$lng }} };
    const collectorPosRaw = {!! ($collectorLat ?? null) && ($collectorLng ?? null)
        ? '{ lat: '.(float)$collectorLat.', lng: '.(float)$collectorLng.' }'
        : 'null' !!};

    const el = document.getElementById("reportMap");
    if (!el) {
      if (loading) loading.style.display = 'none';
      return;
    }

    const map = new google.maps.Map(el, {
      zoom: 16,
      center: reportPos,
      mapTypeControl: false,
      streetViewControl: false,
      fullscreenControl: true,
      zoomControl: true
    });

    // Hide loading once map is ready
    google.maps.event.addListenerOnce(map, 'idle', function() {
      if (loading) loading.style.display = 'none';
    });

    const reportMarker = new google.maps.Marker({
      position: reportPos,
      map,
      title: @json($report->reference_code),
      icon: {
        path: google.maps.SymbolPath.CIRCLE,
        scale: 10,
        fillColor: '#10b981',
        fillOpacity: 1,
        strokeColor: '#ffffff',
        strokeWeight: 2
      }
    });

    const reportInfo = new google.maps.InfoWindow({
      content: `<div style="font-family: system-ui; padding: 10px;">
                  <div style="font-weight: bold; color: #10b981; margin-bottom: 5px;">{{ addslashes($report->reference_code) }}</div>
                  <div style="color: #374151; margin-bottom: 5px;">📍 {{ addslashes($report->location ?? 'Reported location') }}</div>
                  <div style="color: #6b7280; font-size: 12px;">🗑️ {{ addslashes($report->waste_type ?? 'Waste type not specified') }}</div>
                </div>`
    });
    
    reportInfo.open({ map, anchor: reportMarker });

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
        title: @json($report->collector->name ?? 'Collector'),
        icon: {
          path: google.maps.SymbolPath.CIRCLE,
          scale: 8,
          fillColor: '#3b82f6',
          fillOpacity: 1,
          strokeColor: '#ffffff',
          strokeWeight: 2
        }
      });

      const collectorInfo = new google.maps.InfoWindow({
        content: `<div style="font-family: system-ui; padding: 10px;">
                    <div style="font-weight: bold; color: #3b82f6;">👷 {{ addslashes($report->collector->name ?? 'Collector') }}</div>
                    <div style="color: #6b7280; font-size: 12px;">📱 {{ addslashes($report->collector->contact ?? 'Contact not available') }}</div>
                  </div>`
      });
      
      collectorMarker.addListener('click', () => collectorInfo.open({ map, anchor: collectorMarker }));

      if (!almostSame) {
        const route = new google.maps.Polyline({
          path: [collectorPos, reportPos],
          geodesic: true,
          strokeColor: '#3b82f6',
          strokeOpacity: 0.8,
          strokeWeight: 3
        });
        route.setMap(map);

        const bounds = new google.maps.LatLngBounds();
        bounds.extend(reportPos);
        bounds.extend(collectorPos);
        map.fitBounds(bounds);
      }
    }
  } catch (error) {
    console.error('Error initializing map:', error);
    if (loading) loading.style.display = 'none';
  }
}

window.initReportMap = initReportMap;

document.addEventListener('DOMContentLoaded', function() {
  const loading = document.getElementById("mapLoading");
  if (loading) loading.style.display = 'flex';
});
</script>
  <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&callback=initReportMap" async defer></script>
  @endif
</div>
@endsection