{{-- resources/views/resident/schedule/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-green-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
  
  <!-- Header Section -->
  <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 mb-6 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 sm:py-6">
      <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
          <h1 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white flex items-center">
            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Collection Schedule
          </h1>
          <p class="text-gray-600 dark:text-gray-300 mt-1 text-sm">Check waste collection schedules for your area</p>
        </div>
      </div>
    </div>
  </div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6">
    <!-- Filters Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 sm:p-6 mb-6">
      <h3 class="text-base sm:text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-600 dark:text-emerald-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"/>
        </svg>
        Filter Schedule
      </h3>
      
      <form method="GET" action="{{ route('resident.schedule.index') }}" id="scheduleFilters">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
          <!-- Date From -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">From Date</label>
            <div class="relative">
              <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-6 9l4-4-4-4m8 4v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6c0-1.1.9-2 2-2h12a2 2 0 012 2z"/>
              </svg>
              <input type="date" name="from" 
                     value="{{ optional($range[0] ?? null)->toDateString() }}"
                     class="w-full pl-10 pr-4 py-2.5 border-2 border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 transition-all duration-200 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
            </div>
          </div>

          <!-- Date To -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">To Date</label>
            <div class="relative">
              <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-6 9l4-4-4-4m8 4v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6c0-1.1.9-2 2-2h12a2 2 0 012 2z"/>
              </svg>
              <input type="date" name="to" 
                     value="{{ optional($range[1] ?? null)->toDateString() }}"
                     class="w-full pl-10 pr-4 py-2.5 border-2 border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 transition-all duration-200 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
            </div>
          </div>

          <!-- District -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">District</label>
            <div class="relative">
              <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
              <select name="area_id" 
                      onchange="document.getElementById('scheduleFilters').submit()"
                      class="w-full pl-10 pr-8 py-2.5 border-2 border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 transition-all duration-200 bg-white dark:bg-gray-700 text-sm appearance-none text-gray-900 dark:text-white">
                <option value="">Select district…</option>
                @foreach($areas as $a)
                  <option value="{{ $a->id }}" @selected($selectedAreaId == $a->id)>{{ $a->name }}</option>
                @endforeach
              </select>
              <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 dark:text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
              </svg>
            </div>
          </div>

          <!-- Apply Button -->
          <div class="flex items-end">
            <button type="submit" 
                    class="w-full px-4 py-2.5 bg-gradient-to-r from-emerald-500 to-green-600 text-white rounded-lg font-medium hover:from-emerald-600 hover:to-green-700 transition-all duration-200 shadow-lg text-sm flex items-center justify-center">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
              </svg>
              Apply Filters
            </button>
          </div>
        </div>
        
        <!-- Selected Area Display -->
        @if($area)
        <div class="mt-4 flex items-center justify-between p-3 bg-gradient-to-r from-emerald-50 to-green-50 dark:from-emerald-900/30 dark:to-green-900/30 rounded-lg border border-emerald-200 dark:border-emerald-700">
          <div class="flex items-center space-x-2">
            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
            </svg>
            <span class="text-sm font-medium text-emerald-700 dark:text-emerald-300">Selected Area:</span>
            <span class="text-sm font-bold text-emerald-800 dark:text-emerald-200">{{ $area->name }}</span>
          </div>
        </div>
        @endif
      </form>
    </div>

    <!-- Content Area -->
    @if(!$selectedAreaId)
      <!-- No District Selected -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 text-center">
        <div class="w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
          </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Select Your District</h3>
        <p class="text-gray-500 dark:text-gray-400 text-sm">Please select your district from the filter above to view the waste collection schedule.</p>
      </div>

    @elseif(!empty($message))
      <!-- Message Display -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 text-center">
        <div class="w-16 h-16 bg-gradient-to-br from-yellow-100 to-orange-100 dark:from-yellow-900/30 dark:to-orange-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.864-.833-2.634 0L3.232 16.5c-.77.833.192 2.5 1.732 2.5z"/>
          </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Notice</h3>
        <p class="text-gray-600 dark:text-gray-400">{{ $message }}</p>
      </div>

    @else
      <!-- Schedule Table - Desktop View -->
      <div class="hidden md:block bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-100 dark:border-gray-700">
        <div class="bg-gradient-to-r from-emerald-50 to-green-50 dark:from-emerald-900/30 dark:to-green-900/30 px-6 py-4 border-b border-gray-100 dark:border-gray-700">
          <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center">
              <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
              </svg>
              Collection Schedule - {{ $area->name ?? 'Your Area' }}
            </h3>
            <div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-300">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <span>{{ count($schedules) }} scheduled collections</span>
            </div>
          </div>
        </div>

        <div class="overflow-x-auto">
          <table class="min-w-full">
            <thead class="bg-gradient-to-r from-emerald-500 to-green-600 text-white">
              <tr>
                <th class="text-left px-6 py-4 font-semibold text-sm uppercase tracking-wide">
                  <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4"/>
                    </svg>
                    Date
                  </div>
                </th>
                <th class="text-left px-6 py-4 font-semibold text-sm uppercase tracking-wide">Day</th>
                <th class="text-left px-6 py-4 font-semibold text-sm uppercase tracking-wide">
                  <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Time
                  </div>
                </th>
                <th class="text-left px-6 py-4 font-semibold text-sm uppercase tracking-wide">
                  <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7"/>
                    </svg>
                    Waste Type
                  </div>
                </th>
                <th class="text-left px-6 py-4 font-semibold text-sm uppercase tracking-wide">Notes</th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
              @foreach($schedules as $index => $row)
                @php
                  $d = $row['date'] instanceof \Carbon\Carbon
                        ? $row['date']
                        : \Carbon\Carbon::parse($row['date']);
                  $from = \Illuminate\Support\Str::substr($row['start_time'] ?? '', 0, 5);
                  $to   = \Illuminate\Support\Str::substr($row['end_time']   ?? '', 0, 5);
                  $isToday = $d->isToday();
                  $isPast = $d->isPast() && !$isToday;
                  $isFuture = $d->isFuture();
                @endphp
                <tr class="hover:bg-gradient-to-r hover:from-emerald-50 hover:to-green-50 dark:hover:from-emerald-900/20 dark:hover:to-green-900/20 transition-all duration-200 {{ $isToday ? 'bg-gradient-to-r from-emerald-50 to-green-50 dark:from-emerald-900/30 dark:to-green-900/30 border-l-4 border-emerald-500 dark:border-emerald-400' : '' }}">
                  <td class="px-6 py-4">
                    <div class="flex items-center space-x-3">
                      @if($isToday)
                        <div class="w-2 h-2 bg-emerald-500 dark:bg-emerald-400 rounded-full animate-pulse"></div>
                      @elseif($isPast)
                        <div class="w-2 h-2 bg-gray-400 dark:bg-gray-500 rounded-full"></div>
                      @else
                        <div class="w-2 h-2 bg-blue-500 dark:bg-blue-400 rounded-full"></div>
                      @endif
                      <div>
                        <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $d->format('M d, Y') }}</div>
                        @if($isToday)
                          <span class="text-xs bg-emerald-100 dark:bg-emerald-900/50 text-emerald-800 dark:text-emerald-300 px-2 py-1 rounded-full font-medium">Today</span>
                        @elseif($isPast)
                          <span class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 px-2 py-1 rounded-full">Past</span>
                        @endif
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $d->format('l') }}</span>
                  </td>
                  <td class="px-6 py-4">
                    <div class="flex items-center space-x-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                      <svg class="w-3 h-3 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/>
                      </svg>
                      <span>{{ $from }}</span>
                      @if($to)
                        <span class="text-gray-400 dark:text-gray-500">–</span>
                        <span>{{ $to }}</span>
                      @endif
                    </div>
                  </td>
                  <td class="px-6 py-4">
                    @php
                      $wasteType = $row['waste_type'] ?? 'general';
                      $typeColors = [
                        'organic' => [
                          'bg' => 'bg-green-100 dark:bg-green-900/50', 
                          'text' => 'text-green-800 dark:text-green-300'
                        ],
                        'plastic' => [
                          'bg' => 'bg-blue-100 dark:bg-blue-900/50', 
                          'text' => 'text-blue-800 dark:text-blue-300'
                        ],
                        'e-waste' => [
                          'bg' => 'bg-purple-100 dark:bg-purple-900/50', 
                          'text' => 'text-purple-800 dark:text-purple-300'
                        ],
                        'metal' => [
                          'bg' => 'bg-gray-100 dark:bg-gray-700', 
                          'text' => 'text-gray-800 dark:text-gray-300'
                        ],
                        'glass' => [
                          'bg' => 'bg-cyan-100 dark:bg-cyan-900/50', 
                          'text' => 'text-cyan-800 dark:text-cyan-300'
                        ],
                        'hazardous' => [
                          'bg' => 'bg-red-100 dark:bg-red-900/50', 
                          'text' => 'text-red-800 dark:text-red-300'
                        ],
                        'recyclable' => [
                          'bg' => 'bg-indigo-100 dark:bg-indigo-900/50', 
                          'text' => 'text-indigo-800 dark:text-indigo-300'
                        ],
                      ];
                      $colors = $typeColors[$wasteType] ?? [
                        'bg' => 'bg-gray-100 dark:bg-gray-700', 
                        'text' => 'text-gray-700 dark:text-gray-300'
                      ];
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $colors['bg'] }} {{ $colors['text'] }}">
                      {{ ucfirst($wasteType) }}
                    </span>
                  </td>
                  <td class="px-6 py-4">
                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ $row['notes'] ?? 'Regular collection' }}</span>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      <!-- Mobile Card View -->
      <div class="block md:hidden space-y-4">
        @foreach($schedules as $index => $row)
          @php
            $d = $row['date'] instanceof \Carbon\Carbon
                  ? $row['date']
                  : \Carbon\Carbon::parse($row['date']);
            $from = \Illuminate\Support\Str::substr($row['start_time'] ?? '', 0, 5);
            $to   = \Illuminate\Support\Str::substr($row['end_time']   ?? '', 0, 5);
            $isToday = $d->isToday();
            $isPast = $d->isPast() && !$isToday;
            
            $wasteType = $row['waste_type'] ?? 'general';
            $typeColors = [
              'organic' => [
                'bg' => 'bg-green-100 dark:bg-green-900/50', 
                'text' => 'text-green-800 dark:text-green-300'
              ],
              'plastic' => [
                'bg' => 'bg-blue-100 dark:bg-blue-900/50', 
                'text' => 'text-blue-800 dark:text-blue-300'
              ],
              'e-waste' => [
                'bg' => 'bg-purple-100 dark:bg-purple-900/50', 
                'text' => 'text-purple-800 dark:text-purple-300'
              ],
              'metal' => [
                'bg' => 'bg-gray-100 dark:bg-gray-700', 
                'text' => 'text-gray-800 dark:text-gray-300'
              ],
              'glass' => [
                'bg' => 'bg-cyan-100 dark:bg-cyan-900/50', 
                'text' => 'text-cyan-800 dark:text-cyan-300'
              ],
              'hazardous' => [
                'bg' => 'bg-red-100 dark:bg-red-900/50', 
                'text' => 'text-red-800 dark:text-red-300'
              ],
              'recyclable' => [
                'bg' => 'bg-indigo-100 dark:bg-indigo-900/50', 
                'text' => 'text-indigo-800 dark:text-indigo-300'
              ],
            ];
            $colors = $typeColors[$wasteType] ?? [
              'bg' => 'bg-gray-100 dark:bg-gray-700', 
              'text' => 'text-gray-700 dark:text-gray-300'
            ];
          @endphp
          
          <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden {{ $isToday ? 'ring-2 ring-emerald-400 dark:ring-emerald-500' : '' }}">
            <!-- Card Header -->
            <div class="p-4 {{ $isToday ? 'bg-gradient-to-r from-emerald-50 to-green-50 dark:from-emerald-900/30 dark:to-green-900/30' : 'bg-gradient-to-r from-gray-50 to-emerald-50 dark:from-gray-700 dark:to-emerald-900/20' }} border-b border-gray-100 dark:border-gray-700">
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                  @if($isToday)
                    <div class="w-3 h-3 bg-emerald-500 dark:bg-emerald-400 rounded-full animate-pulse"></div>
                  @elseif($isPast)
                    <div class="w-3 h-3 bg-gray-400 dark:bg-gray-500 rounded-full"></div>
                  @else
                    <div class="w-3 h-3 bg-blue-500 dark:bg-blue-400 rounded-full"></div>
                  @endif
                  <div>
                    <h4 class="text-base font-bold text-gray-800 dark:text-white">{{ $d->format('M d, Y') }}</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $d->format('l') }}</p>
                  </div>
                </div>
                @if($isToday)
                  <span class="bg-emerald-100 dark:bg-emerald-900/50 text-emerald-800 dark:text-emerald-300 px-2.5 py-1 rounded-full text-xs font-medium">Today</span>
                @elseif($isPast)
                  <span class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 px-2.5 py-1 rounded-full text-xs font-medium">Past</span>
                @endif
              </div>
            </div>

            <!-- Card Content -->
            <div class="p-4 space-y-3">
              <!-- Time -->
              <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-blue-600 dark:from-blue-500 dark:to-blue-700 rounded-lg flex items-center justify-center">
                  <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </div>
                <div>
                  <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Collection Time</p>
                  <p class="text-sm font-bold text-gray-800 dark:text-white">
                    {{ $from }}@if($to) – {{ $to }}@endif
                  </p>
                </div>
              </div>

              <!-- Waste Type -->
              <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-gradient-to-br from-green-400 to-green-600 dark:from-green-500 dark:to-green-700 rounded-lg flex items-center justify-center">
                  <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7"/>
                  </svg>
                </div>
                <div class="flex-1">
                  <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Waste Type</p>
                  <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $colors['bg'] }} {{ $colors['text'] }} mt-1">
                    {{ ucfirst($wasteType) }}
                  </span>
                </div>
              </div>

              <!-- Notes -->
              @if($row['notes'])
              <div class="flex items-start space-x-3">
                <div class="w-8 h-8 bg-gradient-to-br from-gray-400 to-gray-600 dark:from-gray-500 dark:to-gray-700 rounded-lg flex items-center justify-center">
                  <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5"/>
                  </svg>
                </div>
                <div>
                  <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Notes</p>
                  <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">{{ $row['notes'] }}</p>
                </div>
              </div>
              @endif
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>
  
  <!-- Bottom padding to prevent cropping -->
  <div class="pb-8"></div>
</div>
@endsection