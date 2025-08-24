@extends('layouts.collector')

<!-- Tailwind CSS CDN -->
<script src="https://cdn.tailwindcss.com"></script>

@section('content')
<div class="p-6 space-y-8">
    <!-- Location Update Info -->
    <div class="bg-white p-6 shadow rounded">
        <h1 class="text-2xl font-bold mb-2">Collector Dashboard</h1>
        <p class="text-gray-700">Your current location updates every <strong>30 seconds</strong>.</p>
        <p class="text-sm mt-2 text-gray-600">Last updated: <span id="updated-time">Never</span></p>
        <p class="text-sm text-gray-600">Latitude: <span id="lat-value">-</span>, Longitude: <span id="lng-value">-</span></p>
        <p class="text-green-600 mt-1 hidden" id="update-success">Location updated successfully</p>
    </div>

    <!-- Assigned Reports Section -->
    <div>
        <h2 class="text-xl font-semibold mb-4">Assigned Reports</h2>

        @forelse ($assignedReports as $report)
            <div class="bg-white p-4 rounded shadow mb-4">
                <p><strong>Location:</strong> {{ $report->location }}</p>
                <p><strong>Waste Type:</strong> {{ $report->waste_type }}</p>
                <p><strong>Submitted:</strong> {{ $report->created_at->format('Y-m-d h:i A') }}</p>
                <p><strong>Status:</strong> {{ $report->status }}</p>

                @if ($report->latitude && $report->longitude)
                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $report->latitude }},{{ $report->longitude }}&origin={{ $collectorLat }},{{ $collectorLng }}"
                    target="_blank"
                    class="inline-block px-4 py-2 mt-2 mr-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    📍 Get Directions
                    </a>
                @endif

                @if ($report->status === 'assigned' || $report->status === 'in_progress')
                    <form method="POST" action="{{ route('collector.report.collected', $report->id) }}" class="inline-block">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            Mark as Collected
                        </button>
                    </form>
                @elseif ($report->status === 'collected')
                    <p class="text-green-600 mt-2">✔️ Collected (Awaiting Admin Closure)</p>
                @elseif ($report->status === 'closed')
                    <p class="text-gray-600 mt-2">📁 Closed</p>
                @else
                    <p class="text-green-600 mt-2">✔️ Collected</p>
                @endif
            </div>
        @empty
            <p class="text-gray-500">No assigned reports at the moment.</p>
        @endforelse
    </div>
</div>

<!-- Location Update Script -->
<script>
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
                    document.getElementById('updated-time').innerText = new Date().toLocaleTimeString();
                    document.getElementById('lat-value').innerText = lat.toFixed(6);
                    document.getElementById('lng-value').innerText = lng.toFixed(6);
                    document.getElementById('update-success').classList.remove('hidden');
                }
            }).catch(error => {
                console.error('Error updating location:', error);
            });
        });
    }

    updateCollectorLocation();
    setInterval(updateCollectorLocation, 30000);
</script>
@endsection