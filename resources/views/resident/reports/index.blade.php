{{-- resources/views/resident/reports/index.blade.php --}}
@extends('layouts.app')

{{-- Alpine (inline to avoid layout issues) --}}
<script src="https://unpkg.com/alpinejs" defer></script>

@section('content')
<div x-data="historyPage()" class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-green-50">
  
  <!-- Header Section -->
  <div class="bg-white shadow-sm border-b mb-8">
    <div class="max-w-7xl mx-auto px-6 py-8">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-bold text-gray-800 flex items-center">
            <svg class="w-8 h-8 text-emerald-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Report History
          </h1>
          <p class="text-gray-600 mt-2">Track and manage your waste collection reports</p>
        </div>
        
        <div class="flex items-center space-x-4">
          <a href="{{ route('resident.reports.export.csv') }}" 
             class="flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl font-medium hover:from-blue-600 hover:to-indigo-700 transition-all duration-200 shadow-lg hover:shadow-blue-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Export CSV
          </a>
        </div>
      </div>
    </div>
  </div>

  <div class="max-w-7xl mx-auto px-6">
    {{-- Filters + Search --}}
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
      <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
        <svg class="w-5 h-5 text-emerald-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"/>
        </svg>
        Filter & Search Reports
      </h3>
      
      <div class="grid md:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="lg:col-span-2">
          <div class="relative">
            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input x-model="filters.q" @input.debounce.400ms="load(1)" type="text"
                   placeholder="Search reference, location, description…" 
                   class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-emerald-100 focus:border-emerald-400 transition-all duration-200">
          </div>
        </div>

        <select x-model="filters.status" @change="load(1)" 
                class="px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-emerald-100 focus:border-emerald-400 transition-all duration-200 bg-white">
          <option value="">All Status</option>
          <option value="pending">Pending</option>
          <option value="assigned">Assigned</option>
          <option value="enroute">En-route</option>
          <option value="collected">Collected</option>
          <option value="closed">Closed</option>
        </select>

        <select x-model="filters.type" @change="load(1)" 
                class="px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-emerald-100 focus:border-emerald-400 transition-all duration-200 bg-white">
          <option value="">Any Type</option>
          <option>Organic</option><option>Plastic</option><option>E-Waste</option><option>Metal</option><option>Glass</option>
        </select>

        <input type="date" x-model="filters.from" @change="load(1)" 
               class="px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-emerald-100 focus:border-emerald-400 transition-all duration-200">
      </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
      <table class="min-w-full divide-y divide-gray-100">
        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
          <tr class="text-sm font-semibold text-gray-700">
            <th class="px-6 py-4 text-left">Date & Reference</th>
            <th class="px-6 py-4">Location</th>
            <th class="px-6 py-4">Waste Type</th>
            <th class="px-6 py-4">Status</th>
            <th class="px-6 py-4">Alerts</th>
            <th class="px-6 py-4 text-right">Actions</th>
          </tr>
        </thead>

      <tbody class="divide-y divide-gray-100" x-show="rows.length">
        <template x-for="r in rows" :key="r.id">
          <tr class="hover:bg-gradient-to-r hover:from-emerald-50 hover:to-green-50 transition-all duration-200">
            <td class="px-6 py-4">
              <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-green-500 rounded-xl flex items-center justify-center text-white font-bold text-sm">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                  </svg>
                </div>
                <div>
                  <div class="font-semibold text-gray-900" x-text="fmt(r.created_at)"></div>
                  <div class="text-sm text-gray-500 font-mono" x-text="r.reference_code"></div>
                  
                  {{-- Enhanced timeline --}}
                  <div class="mt-2 flex items-center gap-1 text-[10px] text-gray-400">
                    <span :class="dot(r.status,'pending')" class="transition-colors duration-200">●</span><span>Submit</span>
                    <span class="text-gray-300">→</span>
                    <span :class="dot(r.status,'assigned')" class="transition-colors duration-200">●</span><span>Assign</span>
                    <span class="text-gray-300">→</span>
                    <span :class="dot(r.status,'collected')" class="transition-colors duration-200">●</span><span>Collect</span>
                    <span class="text-gray-300">→</span>
                    <span :class="dot(r.status,'closed')" class="transition-colors duration-200">●</span><span>Close</span>
                  </div>
                </div>
              </div>
            </td>

            <td class="px-6 py-4">
              <div class="text-sm text-gray-800 font-medium" x-text="r.location"></div>
              <template x-if="r.verified_lat && r.verified_lng">
                <button class="mt-1 inline-flex items-center text-xs text-indigo-600 hover:text-indigo-800 transition-colors duration-200" @click="openMap(r)">
                  <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                  </svg>
                  View on map
                </button>
              </template>
            </td>

            <td class="px-6 py-4">
              <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800" x-text="r.waste_type"></span>
            </td>

            <td class="px-6 py-4">
              <div class="flex items-center space-x-2">
                <div class="w-2 h-2 rounded-full" :class="{
                  'bg-amber-400 animate-pulse': r.status.toLowerCase() === 'pending',
                  'bg-blue-500': r.status.toLowerCase() === 'assigned',
                  'bg-indigo-500': r.status.toLowerCase() === 'enroute',
                  'bg-emerald-500': r.status.toLowerCase() === 'collected',
                  'bg-gray-500': r.status.toLowerCase() === 'closed'
                }"></div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold"
                      :class="chip(r.status)" x-text="cap(r.status)"></span>
              </div>
            </td>

            <td class="px-6 py-4">
              <div class="flex flex-col space-y-1">
                <template x-if="r.is_overdue">
                  <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Overdue
                  </span>
                </template>
                <template x-if="r.status.toLowerCase()==='closed' && r.has_feedback">
                  <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Rated
                  </span>
                </template>
              </div>
            </td>

            <td class="px-6 py-4">
              <div class="flex gap-2 justify-end items-center">
                <a :href="`{{ url('/resident/reports') }}/${r.id}`" 
                   class="inline-flex items-center px-3 py-1 text-indigo-600 hover:text-indigo-800 text-sm font-medium transition-colors duration-200 hover:bg-indigo-50 rounded-lg">
                  <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                  </svg>
                  View
                </a>
                
                <button class="inline-flex items-center px-3 py-1 text-gray-600 hover:text-gray-800 text-sm font-medium transition-colors duration-200 hover:bg-gray-50 rounded-lg" @click="duplicate(r)">
                  <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                  </svg>
                  Copy
                </button>
                
                <button class="inline-flex items-center px-3 py-1 text-red-600 hover:text-red-700 text-sm font-medium transition-colors duration-200 hover:bg-red-50 rounded-lg" 
                        :disabled="r.status.toLowerCase()!=='pending'"
                        :class="{'opacity-50 cursor-not-allowed': r.status.toLowerCase()!=='pending'}"
                        @click="cancel(r)">
                  <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                  </svg>
                  Cancel
                </button>
                
                <a :href="`{{ url('/feedback/report') }}/${r.id}`" 
                   class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-emerald-500 to-green-600 text-white text-xs font-medium rounded-full hover:from-emerald-600 hover:to-green-700 transition-all duration-200 shadow-sm hover:shadow-md transform hover:scale-105"
                   x-show="r.status.toLowerCase()==='closed' && !r.has_feedback">
                   <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.915a1 1 0 00.95-.69l1.519-4.674z"/>
                   </svg>
                   Rate Service
                </a>
                
                <div class="inline-flex items-center px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-medium rounded-full border border-emerald-200"
                     x-show="r.status.toLowerCase()==='closed' && r.has_feedback">
                  <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                  </svg>
                  Feedback Given
                </div>
              </div>
            </td>
          </tr>
        </template>
      </tbody>

      <tbody x-show="!rows.length">
        <tr>
          <td colspan="6" class="px-6 py-20 text-center">
            <div class="flex flex-col items-center space-y-4">
              <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
              </div>
              <div>
                <h3 class="text-lg font-medium text-gray-800">No reports found</h3>
                <p class="text-gray-500">Try adjusting your filters or create a new report</p>
              </div>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  {{-- Enhanced Pagination --}}
  <div class="mt-8 flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
    <div class="text-sm text-gray-600 bg-white px-4 py-2 rounded-xl shadow-sm" x-text="summary()"></div>
    <div class="flex items-center space-x-2">
      <button class="px-4 py-2 bg-white border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed" 
              :disabled="!pagination.prev_page_url" 
              @click="load(pagination.current_page-1)">
        <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Previous
      </button>
      <button class="px-4 py-2 bg-white border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed" 
              :disabled="!pagination.next_page_url" 
              @click="load(pagination.current_page+1)">
        Next
        <svg class="w-4 h-4 ml-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
      </button>
    </div>
  </div>
  </div>

  {{-- Map modal (OpenStreetMap via Leaflet) --}}
<div x-show="map.open" x-cloak x-transition.opacity class="fixed inset-0 z-40">
  <div class="absolute inset-0 bg-black/40" @click="closeMap()"></div>
  <div class="absolute inset-0 m-auto bg-white rounded-xl shadow-2xl w-[90%] max-w-md h-[380px] p-3">
    <div class="flex justify-between items-center mb-2">
      <h3 class="font-semibold text-sm" x-text="'Location for ' + (map.item?.reference_code || '')"></h3>
      <button class="text-sm text-gray-700 hover:text-black" @click="closeMap()">Close</button>
    </div>
    <div id="miniMap" class="w-full h-[320px] rounded-lg"></div>
  </div>
</div>

{{-- Leaflet assets (only needed for Map preview) --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
window.historyPage = function(){
  return {
    rows: [], pagination:{}, filters:{q:'',status:'',type:'',from:'',to:''},
    map: { open:false, item:null, instance:null, marker:null },

    load(page=1){
      const params = new URLSearchParams({...this.filters,page});
      fetch(`{{ route('resident.reports.data') }}?`+params)
        .then(r=>r.json())
        .then(d=>{
          this.rows = d.data;
          this.pagination = d;
        });
    },

    // formatting & helpers
    fmt(v){ return new Date(v).toLocaleString(); },
    cap(s){ return s ? (s[0].toUpperCase()+s.slice(1).toLowerCase()) : ''; },

    chip(s){
      s = (s || '').toLowerCase();
      return {
        pending:'bg-gradient-to-r from-amber-100 to-orange-100 text-amber-800 border border-amber-200',
        assigned:'bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 border border-blue-200',
        enroute:'bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-800 border border-indigo-200',
        collected:'bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-800 border border-emerald-200',
        closed:'bg-gradient-to-r from-gray-100 to-slate-100 text-gray-700 border border-gray-200'
      }[s] || 'bg-gray-100 text-gray-700 border border-gray-200';
    },
    dot(current, step){
      if(!current) return 'text-gray-300';
      const order=['pending','assigned','collected','closed'];
      return order.indexOf(current.toLowerCase()) >= order.indexOf(step) ? 'text-emerald-600' : 'text-gray-300';
    },
    summary(){
      if(!this.pagination.total) return '';
      const start=((this.pagination.current_page-1)*this.pagination.per_page)+1;
      const end=Math.min(this.pagination.current_page*this.pagination.per_page,this.pagination.total);
      return `Showing ${start}-${end} of ${this.pagination.total}`;
    },

    // actions
    duplicate(r){
      fetch(`{{ url('/resident/reports') }}/${r.id}/duplicate`, {
        method:'POST',
        headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}
      }).then(()=> this.load());
    },
    cancel(r){
      if(!confirm('Cancel this pending report?')) return;
      fetch(`{{ url('/resident/reports') }}/${r.id}/cancel`, {
        method:'POST',
        headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}
      }).then(()=> this.load());
    },

    // map preview
    openMap(r){
      this.map.item = r; this.map.open = true;
      this.$nextTick(()=>{
        const lat = Number(r.verified_lat), lng = Number(r.verified_lng);
        if(!this.map.instance){
          this.map.instance = L.map('miniMap');
          L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom: 19}).addTo(this.map.instance);
        }
        this.map.instance.setView([lat,lng], 15);
        if(this.map.marker){ this.map.instance.removeLayer(this.map.marker); }
        this.map.marker = L.marker([lat,lng]).addTo(this.map.instance);
      });
    },
    closeMap(){ this.map.open=false; },

    init(){ this.load(); }
  }
}
</script>
@endsection