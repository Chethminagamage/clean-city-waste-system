{{-- resources/views/resident/reports/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div x-data="historyPage()" class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-green-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 transition-colors duration-300">
  
  <!-- Header Section -->
  <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 mb-6 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 sm:py-6">
      <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
          <h1 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white flex items-center">
            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Report History
          </h1>
          <p class="text-gray-600 dark:text-gray-400 mt-1 text-sm">Track and manage your waste collection reports</p>
        </div>
        
        <a href="{{ route('resident.reports.export.csv') }}" 
           class="flex items-center px-3 sm:px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 dark:from-blue-600 dark:to-indigo-700 text-white rounded-lg font-medium hover:from-blue-600 hover:to-indigo-700 dark:hover:from-blue-700 dark:hover:to-indigo-800 transition-all duration-200 shadow-lg text-sm">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
          <span class="hidden sm:inline">Export CSV</span>
          <span class="sm:hidden">Export</span>
        </a>
      </div>
    </div>
  </div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6">
    <!-- Filters + Search -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 sm:p-6 mb-6 transition-colors duration-300">
      <h3 class="text-base sm:text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-600 dark:text-emerald-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"/>
        </svg>
        Filter & Search Reports
      </h3>
      
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <!-- Search Input -->
        <div class="lg:col-span-2">
          <div class="relative">
            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input x-model="filters.q" @input.debounce.400ms="load(1)" type="text"
                   placeholder="Search reference, location, description…" 
                   class="w-full pl-10 pr-4 py-2.5 border-2 border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 transition-all duration-200 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
          </div>
        </div>

        <!-- Status Filter -->
        <select x-model="filters.status" @change="load(1)" 
                class="px-3 py-2.5 border-2 border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm">
          <option value="">All Status</option>
          <option value="pending">Pending</option>
          <option value="assigned">Assigned</option>
          <option value="enroute">En-route</option>
          <option value="collected">Collected</option>
          <option value="closed">Closed</option>
        </select>

        <!-- Type Filter -->
        <select x-model="filters.type" @change="load(1)" 
                class="px-3 py-2.5 border-2 border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm">
          <option value="">Any Type</option>
          <option>Organic</option>
          <option>Plastic</option>
          <option>E-Waste</option>
          <option>Metal</option>
          <option>Glass</option>
        </select>
      </div>
      
      <!-- Clear Filters Button -->
      <div class="mt-4 flex justify-end" x-show="filters.q || filters.status || filters.type">
        <button @click="clearFilters()" 
                class="inline-flex items-center px-3 py-1.5 text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100 text-sm font-medium transition-colors duration-200 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
          <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
          Clear Filters
        </button>
      </div>
    </div>

    <!-- Mobile Card View (Small and Medium Screens) -->
    <div class="block xl:hidden">
      <div class="space-y-4" x-show="rows.length">
        <template x-for="r in rows" :key="r.id">
          <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden transition-colors duration-300">
            <!-- Card Header -->
            <div class="p-4 border-b border-gray-100 dark:border-gray-600 bg-gradient-to-r from-gray-50 to-emerald-50 dark:from-gray-700 dark:to-emerald-900/20">
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                  <div class="w-8 h-8 bg-gradient-to-br from-emerald-400 to-green-500 rounded-lg flex items-center justify-center text-white font-bold text-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                  </div>
                  <div>
                    <div class="font-semibold text-gray-900 dark:text-gray-100 text-sm" x-text="fmt(r.created_at)"></div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 font-mono" x-text="r.reference_code"></div>
                  </div>
                </div>
                <div class="flex items-center space-x-2">
                  <div class="w-2 h-2 rounded-full" :class="statusDot(r.status)"></div>
                  <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold" :class="chip(r.status)" x-text="cap(r.status)"></span>
                </div>
              </div>
            </div>

            <!-- Card Content -->
            <div class="p-4">
              <div class="space-y-3">
                <!-- Location & Waste Type -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                  <div>
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Location</span>
                    <div class="text-sm text-gray-800 dark:text-gray-200 font-medium truncate" x-text="r.location"></div>
                  </div>
                  <div>
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Waste Type</span>
                    <div class="mt-1">
                      <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-200" x-text="r.waste_type"></span>
                    </div>
                  </div>
                </div>

                <!-- Progress Timeline -->
                <div>
                  <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2 block">Progress</span>
                  <div class="flex items-center gap-1 text-xs">
                    <span :class="dot(r.status,'pending')">●</span><span class="text-gray-600 dark:text-gray-400">Submit</span>
                    <span class="text-gray-300 dark:text-gray-600">→</span>
                    <span :class="dot(r.status,'assigned')">●</span><span class="text-gray-600 dark:text-gray-400">Assign</span>
                    <span class="text-gray-300 dark:text-gray-600">→</span>
                    <span :class="dot(r.status,'collected')">●</span><span class="text-gray-600 dark:text-gray-400">Collect</span>
                    <span class="text-gray-300 dark:text-gray-600">→</span>
                    <span :class="dot(r.status,'closed')">●</span><span class="text-gray-600 dark:text-gray-400">Close</span>
                  </div>
                </div>

                <!-- Alerts -->
                <div class="flex flex-wrap gap-2" x-show="r.is_overdue || (r.status.toLowerCase()==='closed' && r.has_feedback)">
                  <template x-if="r.is_overdue">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-200">
                      <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                      </svg>
                      Overdue
                    </span>
                  </template>
                  <template x-if="r.status.toLowerCase()==='closed' && r.has_feedback">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-200">
                      <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                      </svg>
                      Rated
                    </span>
                  </template>
                </div>
              </div>

              <!-- Actions -->
              <div class="flex flex-wrap gap-2 mt-4 pt-4 border-t border-gray-100 dark:border-gray-600">
                <a :href="`{{ url('/resident/reports') }}/${r.id}`" 
                   class="inline-flex items-center px-3 py-1.5 text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 text-sm font-medium transition-colors duration-200 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded-lg border border-indigo-200 dark:border-indigo-700">
                  <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                  </svg>
                  View
                </a>
                
                <button class="inline-flex items-center px-3 py-1.5 text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100 text-sm font-medium transition-colors duration-200 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600" @click="duplicate(r)">
                  <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                  </svg>
                  Copy
                </button>
                
                <button class="inline-flex items-center px-3 py-1.5 text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 text-sm font-medium transition-colors duration-200 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg border border-red-200 dark:border-red-700" 
                        :disabled="r.status.toLowerCase()!=='pending'"
                        :class="{'opacity-50 cursor-not-allowed': r.status.toLowerCase()!=='pending'}"
                        @click="cancel(r)"
                        x-show="r.status.toLowerCase()==='pending'">
                  <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                  </svg>
                  Cancel
                </button>
                
                <a :href="`{{ url('/feedback/report') }}/${r.id}`" 
                   class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-emerald-500 to-green-600 text-white text-sm font-medium rounded-lg hover:from-emerald-600 hover:to-green-700 transition-all duration-200 shadow-sm"
                   x-show="r.status.toLowerCase()==='closed' && !r.has_feedback">
                   <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.915a1 1 0 00.95-.69l1.519-4.674z"/>
                   </svg>
                   Rate
                </a>
                
                <template x-if="r.verified_lat && r.verified_lng">
                  <button class="inline-flex items-center px-3 py-1.5 text-teal-600 hover:text-teal-800 text-sm font-medium transition-colors duration-200 hover:bg-teal-50 rounded-lg border border-teal-200" @click="openMap(r)">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    </svg>
                    Map
                  </button>
                </template>
              </div>
            </div>
          </div>
        </template>
      </div>

      <!-- Mobile Empty State -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 text-center transition-colors duration-300" x-show="!rows.length && !loading">
        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
        </div>
        <div>
          <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200">No reports found</h3>
          <p class="text-gray-500 dark:text-gray-400 text-sm">Try adjusting your filters or create a new report</p>
        </div>
      </div>
    </div>

    <!-- Desktop Table View (Large Screens) -->
    <div class="hidden xl:block bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden transition-colors duration-300">
      <table class="w-full">
        <thead class="bg-gradient-to-r from-gray-50 to-emerald-50 dark:from-gray-700 dark:to-emerald-900/30">
          <tr class="text-sm font-semibold text-gray-700 dark:text-gray-300">
            <th class="px-4 py-3 text-left w-1/4">Date & Reference</th>
            <th class="px-4 py-3 text-left w-1/5">Location</th>
            <th class="px-4 py-3 text-center w-1/8">Type</th>
            <th class="px-4 py-3 text-center w-1/8">Status</th>
            <th class="px-4 py-3 text-center w-1/8">Alerts</th>
            <th class="px-4 py-3 text-right w-1/4">Actions</th>
          </tr>
        </thead>

        <tbody class="divide-y divide-gray-100 dark:divide-gray-600" x-show="rows.length && !loading">
          <template x-for="r in rows" :key="r.id">
            <tr class="hover:bg-gradient-to-r hover:from-emerald-50 hover:to-green-50 dark:hover:from-emerald-900/20 dark:hover:to-green-900/20 transition-all duration-200">
              <td class="px-4 py-4">
                <div class="flex items-center space-x-3">
                  <div class="w-8 h-8 bg-gradient-to-br from-emerald-400 to-green-500 rounded-lg flex items-center justify-center text-white font-bold text-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                  </div>
                  <div>
                    <div class="font-semibold text-gray-900 dark:text-gray-100 text-sm" x-text="fmt(r.created_at)"></div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 font-mono" x-text="r.reference_code"></div>
                    <!-- Progress Timeline -->
                    <div class="mt-1 flex items-center gap-1 text-xs">
                      <span :class="dot(r.status,'pending')">●</span><span class="text-gray-500 dark:text-gray-400">Submit</span>
                      <span class="text-gray-300 dark:text-gray-600">→</span>
                      <span :class="dot(r.status,'assigned')">●</span><span class="text-gray-500 dark:text-gray-400">Assign</span>
                      <span class="text-gray-300 dark:text-gray-600">→</span>
                      <span :class="dot(r.status,'collected')">●</span><span class="text-gray-500 dark:text-gray-400">Collect</span>
                      <span class="text-gray-300 dark:text-gray-600">→</span>
                      <span :class="dot(r.status,'closed')">●</span><span class="text-gray-500 dark:text-gray-400">Close</span>
                    </div>
                  </div>
                </div>
              </td>

              <td class="px-4 py-4">
                <div class="text-sm text-gray-800 dark:text-gray-200 font-medium truncate max-w-32" x-text="r.location" :title="r.location"></div>
                <template x-if="r.verified_lat && r.verified_lng">
                  <button class="mt-1 inline-flex items-center text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition-colors duration-200" @click="openMap(r)">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    </svg>
                    Map
                  </button>
                </template>
              </td>

              <td class="px-4 py-4 text-center">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-200" x-text="r.waste_type"></span>
              </td>

              <td class="px-4 py-4 text-center">
                <div class="flex items-center justify-center space-x-2">
                  <div class="w-2 h-2 rounded-full" :class="statusDot(r.status)"></div>
                  <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold" :class="chip(r.status)" x-text="cap(r.status)"></span>
                </div>
              </td>

              <td class="px-4 py-4 text-center">
                <div class="flex flex-col items-center space-y-1">
                  <template x-if="r.is_overdue">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-200">
                      <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                      </svg>
                      Overdue
                    </span>
                  </template>
                  <template x-if="r.status.toLowerCase()==='closed' && r.has_feedback">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-200">
                      <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                      </svg>
                      Rated
                    </span>
                  </template>
                </div>
              </td>

              <td class="px-4 py-4">
                <div class="flex flex-wrap gap-1 justify-end">
                  <a :href="`{{ url('/resident/reports') }}/${r.id}`" 
                     class="inline-flex items-center px-2 py-1 text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 text-xs font-medium transition-colors duration-200 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded border border-indigo-200 dark:border-indigo-700">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    View
                  </a>
                  
                  <button class="inline-flex items-center px-2 py-1 text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100 text-xs font-medium transition-colors duration-200 hover:bg-gray-50 dark:hover:bg-gray-700 rounded border border-gray-200 dark:border-gray-600" @click="duplicate(r)">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    Copy
                  </button>
                  
                  <button class="inline-flex items-center px-2 py-1 text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 text-xs font-medium transition-colors duration-200 hover:bg-red-50 dark:hover:bg-red-900/30 rounded border border-red-200 dark:border-red-700" 
                          @click="cancel(r)"
                          x-show="r.status.toLowerCase()==='pending'">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Cancel
                  </button>
                  
                  <a :href="`{{ url('/feedback/report') }}/${r.id}`" 
                     class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-emerald-500 to-green-600 text-white text-xs font-medium rounded hover:from-emerald-600 hover:to-green-700 transition-all duration-200"
                     x-show="r.status.toLowerCase()==='closed' && !r.has_feedback">
                     <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.915a1 1 0 00.95-.69l1.519-4.674z"/>
                     </svg>
                     Rate
                  </a>
                </div>
              </td>
            </tr>
          </template>
        </tbody>

        <!-- Desktop Empty State -->
        <tbody x-show="!rows.length && !loading">
          <tr>
            <td colspan="6" class="px-6 py-16 text-center">
              <div class="flex flex-col items-center space-y-4">
                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                  <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                  </svg>
                </div>
                <div>
                  <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200">No reports found</h3>
                  <p class="text-gray-500 dark:text-gray-400">Try adjusting your filters or create a new report</p>
                </div>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0" x-show="rows.length || pagination.total">
      <div class="text-sm text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-800 px-4 py-2 rounded-lg shadow-sm transition-colors duration-300" x-text="summary()"></div>
      <div class="flex items-center space-x-2">
        <button class="px-3 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed text-sm" 
                :disabled="!pagination.prev_page_url || loading" 
                @click="load(pagination.current_page-1)">
          <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
          <span class="hidden sm:inline">Previous</span>
          <span class="sm:hidden">Prev</span>
        </button>
        
        <span class="px-3 py-2 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-200 rounded-lg text-sm font-medium" x-text="pagination.current_page || 1"></span>
        
        <button class="px-3 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed text-sm" 
                :disabled="!pagination.next_page_url || loading" 
                @click="load(pagination.current_page+1)">
          <span class="hidden sm:inline">Next</span>
          <span class="sm:hidden">Next</span>
          <svg class="w-4 h-4 ml-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
        </button>
      </div>
    </div>
  </div>

  <!-- Map Modal -->
  <div x-show="map.open" x-cloak x-transition.opacity class="fixed inset-0 z-50">
    <div class="absolute inset-0 bg-black/40" @click="closeMap()"></div>
    <div class="absolute inset-4 sm:inset-8 lg:inset-auto lg:top-1/2 lg:left-1/2 lg:transform lg:-translate-x-1/2 lg:-translate-y-1/2 bg-white dark:bg-gray-800 rounded-xl shadow-2xl lg:w-[90%] lg:max-w-2xl lg:h-[500px] p-4 transition-colors duration-300">
      <div class="flex justify-between items-center mb-4">
        <h3 class="font-semibold text-base text-gray-900 dark:text-gray-100" x-text="'Location for ' + (map.item?.reference_code || '')"></h3>
        <button class="text-gray-700 dark:text-gray-300 hover:text-black dark:hover:text-white p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded transition-colors" @click="closeMap()">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
      <div id="miniMap" class="w-full h-[calc(100%-4rem)] rounded-lg"></div>
    </div>
  </div>

  <!-- Loading Spinner -->
  <div x-show="loading" x-cloak class="fixed inset-0 bg-black/20 flex items-center justify-center z-40">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-xl transition-colors duration-300">
      <div class="flex items-center space-x-3">
        <div class="w-6 h-6 border-2 border-emerald-500 border-t-transparent rounded-full animate-spin"></div>
        <span class="text-gray-700 dark:text-gray-300 font-medium">Loading reports...</span>
      </div>
    </div>
  </div>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- Alpine.js -->
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<script>
document.addEventListener('alpine:init', () => {
  Alpine.data('historyPage', () => ({
    rows: [], 
    pagination: {}, 
    filters: {q:'', status:'', type:''}, 
    loading: false,
    map: { open: false, item: null, instance: null, marker: null },

    async load(page = 1) {
      this.loading = true;
      try {
        const params = new URLSearchParams({...this.filters, page});
        const response = await fetch(`{{ route('resident.reports.data') }}?` + params);
        
        if (!response.ok) {
          throw new Error('Failed to load reports');
        }
        
        const data = await response.json();
        this.rows = data.data || [];
        this.pagination = data;
      } catch (error) {
        console.error('Error loading reports:', error);
        this.rows = [];
        this.pagination = {};
      } finally {
        this.loading = false;
      }
    },

    // Formatting & helpers
    fmt(v) { 
      try {
        return new Date(v).toLocaleDateString('en-US', {
          month: 'short',
          day: 'numeric',
          year: 'numeric',
          hour: '2-digit',
          minute: '2-digit'
        });
      } catch (e) {
        return v || 'N/A';
      }
    },
    
    cap(s) { 
      return s ? (s[0].toUpperCase() + s.slice(1).toLowerCase()) : 'Unknown'; 
    },

    chip(s) {
      s = (s || '').toLowerCase();
      return {
        pending: 'bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-200 border border-amber-200 dark:border-amber-700',
        assigned: 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 border border-blue-200 dark:border-blue-700',
        enroute: 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-200 border border-indigo-200 dark:border-indigo-700',
        collected: 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-200 border border-emerald-200 dark:border-emerald-700',
        closed: 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-600'
      }[s] || 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-600';
    },

    statusDot(s) {
      s = (s || '').toLowerCase();
      return {
        pending: 'bg-amber-400 animate-pulse',
        assigned: 'bg-blue-500',
        enroute: 'bg-indigo-500',
        collected: 'bg-emerald-500',
        closed: 'bg-gray-500'
      }[s] || 'bg-gray-400';
    },
    
    dot(current, step) {
      if (!current) return 'text-gray-300 dark:text-gray-600';
      const order = ['pending', 'assigned', 'collected', 'closed'];
      return order.indexOf(current.toLowerCase()) >= order.indexOf(step) ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-300 dark:text-gray-600';
    },
    
    summary() {
      if (!this.pagination.total) return 'No reports found';
      const start = ((this.pagination.current_page - 1) * this.pagination.per_page) + 1;
      const end = Math.min(this.pagination.current_page * this.pagination.per_page, this.pagination.total);
      return `Showing ${start}-${end} of ${this.pagination.total} reports`;
    },

    // Actions
    async duplicate(r) {
      if (!confirm('Create a duplicate of this report?')) return;
      
      this.loading = true;
      try {
        const response = await fetch(`{{ url('/resident/reports') }}/${r.id}/duplicate`, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          }
        });
        
        if (response.ok) {
          await this.load();
          alert('Report duplicated successfully!');
        } else {
          throw new Error('Failed to duplicate report');
        }
      } catch (error) {
        console.error('Error duplicating report:', error);
        alert('Failed to duplicate report. Please try again.');
      } finally {
        this.loading = false;
      }
    },
    
    async cancel(r) {
      if (!confirm('Cancel this pending report? This action cannot be undone.')) return;
      
      this.loading = true;
      try {
        const response = await fetch(`{{ url('/resident/reports') }}/${r.id}/cancel`, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          }
        });
        
        if (response.ok) {
          await this.load();
          alert('Report cancelled successfully!');
        } else {
          throw new Error('Failed to cancel report');
        }
      } catch (error) {
        console.error('Error cancelling report:', error);
        alert('Failed to cancel report. Please try again.');
      } finally {
        this.loading = false;
      }
    },

    // Map functionality
    openMap(r) {
      this.map.item = r; 
      this.map.open = true;
      
      this.$nextTick(() => {
        try {
          const lat = Number(r.verified_lat || r.latitude);
          const lng = Number(r.verified_lng || r.longitude);
          
          if (isNaN(lat) || isNaN(lng)) {
            console.error('Invalid coordinates');
            return;
          }
          
          if (!this.map.instance) {
            this.map.instance = L.map('miniMap');
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
              maxZoom: 19,
              attribution: '© OpenStreetMap contributors'
            }).addTo(this.map.instance);
          }
          
          this.map.instance.setView([lat, lng], 15);
          
          if (this.map.marker) { 
            this.map.instance.removeLayer(this.map.marker); 
          }
          
          this.map.marker = L.marker([lat, lng])
            .addTo(this.map.instance)
            .bindPopup(`
              <div style="font-family: system-ui; padding: 8px;">
                <div style="font-weight: bold; color: #10b981; margin-bottom: 4px;">${r.reference_code}</div>
                <div style="color: #374151; font-size: 12px;">📍 ${r.location}</div>
                <div style="color: #6b7280; font-size: 11px;">🗑️ ${r.waste_type}</div>
              </div>
            `)
            .openPopup();
            
          // Ensure map renders properly
          setTimeout(() => {
            this.map.instance.invalidateSize();
          }, 100);
        } catch (error) {
          console.error('Error opening map:', error);
        }
      });
    },
    
    closeMap() { 
      this.map.open = false; 
    },

    // Clear all filters
    clearFilters() {
      this.filters = {q:'', status:'', type:''};
      this.load(1);
    },

    init() { 
      this.load(); 
    }
  }));
});
</script>

<!-- Bottom padding to prevent cropping -->
<div class="pb-8"></div>
@endsection
