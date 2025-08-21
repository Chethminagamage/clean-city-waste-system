@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-6 space-y-6">
  <a href="{{ route('resident.reports.index') }}" class="text-sm text-gray-600 hover:underline">← Back to Report History</a>

  <h1 class="text-xl font-semibold">Report {{ $report->reference_code }}</h1>

  {{-- Report Summary --}}
  <div class="grid sm:grid-cols-2 gap-4">
    <div class="p-4 bg-white rounded-lg shadow">
      <div class="text-sm text-gray-500">Status</div>
      <div class="mt-1 inline-block px-2 py-1 rounded-full text-xs font-semibold
        @class([
          'bg-amber-100 text-amber-800' => strtolower($report->status)==='pending',
          'bg-blue-100 text-blue-800'   => strtolower($report->status)==='assigned',
          'bg-indigo-100 text-indigo-800'=> strtolower($report->status)==='enroute',
          'bg-emerald-100 text-emerald-800'=> strtolower($report->status)==='collected',
          'bg-gray-200 text-gray-700'   => strtolower($report->status)==='closed',
        ])">
        {{ ucfirst($report->status) }}
      </div>

      <dl class="mt-4 text-sm">
        <div class="flex justify-between py-1"><dt>Type</dt><dd>{{ $report->waste_type ?? '—' }}</dd></div>
        <div class="flex justify-between py-1"><dt>Location</dt><dd class="text-right">{{ $report->location ?? '—' }}</dd></div>
        <div class="flex justify-between py-1"><dt>Created</dt><dd>{{ optional($report->created_at)->format('Y-m-d H:i') }}</dd></div>
      </dl>
    </div>

   {{-- Assigned Collector Info --}}
        <div class="p-4 bg-white rounded-lg shadow">
        <div class="text-sm text-gray-500 mb-2">Assigned Collector</div>
        @if($report->collector)
            <p class="text-sm font-medium">{{ $report->collector->name }}</p>
            <p class="text-xs text-gray-600">
            {{ $report->collector->contact ?? 'No contact' }}
            </p>
            <p class="text-xs text-gray-500 mt-1">
            Assigned: {{ optional($report->assigned_at)->format('Y-m-d H:i') }}
            </p>
        @else
            <p class="text-sm text-gray-500">No collector assigned yet.</p>
        @endif
        </div>
  </div>

  {{-- Progress Tracker --}}
  <div class="p-4 bg-white rounded-lg shadow">
    <div class="text-sm text-gray-500 mb-3">Progress</div>
    @php
      $statuses = ['pending', 'assigned', 'enroute', 'collected', 'closed'];
      $current = array_search(strtolower($report->status), $statuses);
    @endphp
    <div class="flex items-center justify-between">
      @foreach($statuses as $idx => $s)
        <div class="flex-1 flex items-center">
          <div class="relative flex flex-col items-center text-xs">
            <div class="w-6 h-6 rounded-full flex items-center justify-center
              {{ $current >= $idx ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-500' }}">
              {{ $idx + 1 }}
            </div>
            <span class="mt-1 text-[11px] capitalize">{{ $s }}</span>
          </div>
          @if(!$loop->last)
            <div class="flex-1 h-0.5 {{ $current >= $idx ? 'bg-green-500' : 'bg-gray-300' }}"></div>
          @endif
        </div>
      @endforeach
    </div>
  </div>

  {{-- Report Actions --}}
  <div class="p-4 bg-white rounded-lg shadow">
    <div class="text-sm text-gray-500 mb-2">Actions</div>
    <div class="flex gap-3">
      @if(!in_array(strtolower($report->status), ['collected','closed']))
        <form action="{{ route('resident.reports.cancel', $report->id) }}" method="POST" onsubmit="return confirm('Cancel this report?')">
          @csrf @method('PATCH')
          <button class="px-3 py-1 text-sm rounded bg-red-100 text-red-700 hover:bg-red-200">Cancel Report</button>
        </form>
      @endif
    </div>
  </div>

  {{-- Location Map --}}
@php
  $lat = $report->verified_lat ?? $report->latitude;
  $lng = $report->verified_lng ?? $report->longitude;
  $collectorLat = optional($report->collector)->latitude;
  $collectorLng = optional($report->collector)->longitude;
@endphp

@if($lat && $lng)
  <div class="p-0 bg-white rounded-lg shadow overflow-hidden border border-gray-100 relative">
    <div class="flex items-center justify-between px-4 py-2">
      <div class="text-sm text-gray-600 font-medium">Location Map</div>
      <a target="_blank" href="https://www.google.com/maps?q={{ $lat }},{{ $lng }}"
         class="text-xs text-indigo-600 hover:underline">Open in Google Maps</a>
    </div>
    <div id="reportMap" class="w-full" style="height: 380px;"></div>

    <div class="absolute right-3 bottom-3 bg-white/90 backdrop-blur rounded-md shadow px-2.5 py-1.5 text-xs text-gray-700 flex items-center gap-3">
      <span class="inline-flex items-center gap-1"><span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Report</span>
      @if($collectorLat && $collectorLng)
        <span class="inline-flex items-center gap-1"><span class="inline-block w-2.5 h-2.5 rounded-full bg-blue-500"></span> Collector</span>
      @endif
    </div>
  </div>

  <script>
function initReportMap() {
  const reportPos = { lat: {{ (float)$lat }}, lng: {{ (float)$lng }} };
  const collectorPos = {!! ($collectorLat && $collectorLng)
      ? '{ lat: '.(float)$collectorLat.', lng: '.(float)$collectorLng.' }'
      : 'null' !!};

  const el = document.getElementById("reportMap");
  if (!el) return;

  const map = new google.maps.Map(el, {
    zoom: 14,
    center: reportPos,
    mapTypeControl: false,
    streetViewControl: false,
    fullscreenControl: true
  });

  // Report marker (green)
  const reportMarker = new google.maps.Marker({
    position: reportPos,
    map,
    icon: {
      url: 'https://maps.gstatic.com/mapfiles/ms2/micons/green-dot.png',
      scaledSize: new google.maps.Size(36, 36),
    }
  });

  // Collector marker (blue)
  if (collectorPos) {
    const collectorMarker = new google.maps.Marker({
      position: collectorPos,
      map,
      icon: {
        url: 'https://maps.gstatic.com/mapfiles/ms2/micons/blue-dot.png',
        scaledSize: new google.maps.Size(36, 36),
      }
    });

    // ✅ Directions API for road path
    const directionsService = new google.maps.DirectionsService();
    const directionsRenderer = new google.maps.DirectionsRenderer({
      map: map,
      suppressMarkers: true, // we already placed custom markers
      polylineOptions: {
        strokeColor: "#2563eb", // Tailwind blue-600
        strokeWeight: 5
      }
    });

    directionsService.route(
      {
        origin: collectorPos,
        destination: reportPos,
        travelMode: google.maps.TravelMode.DRIVING,
      },
      (result, status) => {
        if (status === "OK") {
          directionsRenderer.setDirections(result);
        } else {
          console.error("Directions request failed due to " + status);
        }
      }
    );

    // Fit map bounds
    const bounds = new google.maps.LatLngBounds();
    bounds.extend(reportPos);
    bounds.extend(collectorPos);
    map.fitBounds(bounds);
  }
}
window.initReportMap = initReportMap;
</script>
  <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&callback=initReportMap" async defer></script>
@endif
</div>
@endsection