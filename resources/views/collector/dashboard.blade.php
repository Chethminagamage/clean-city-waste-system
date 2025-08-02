@extends('layouts.collector') {{-- Make sure layout exists --}}

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Collector Dashboard</h1>

    <div class="bg-white p-4 shadow rounded">
        <p><strong>Your current location will be updated automatically every 30 seconds.</strong></p>
        <p class="mt-2 text-sm text-gray-600">Last updated: <span id="updated-time">Never</span></p>
    </div>
</div>

<div class="mt-8">
    <h2 class="text-xl font-semibold mb-2">Assigned Reports</h2>

    @foreach ($assignedReports as $report)
        <div class="bg-white p-4 rounded shadow mb-4">
            <p><strong>Location:</strong> {{ $report->location }}</p>
            <p><strong>Waste Type:</strong> {{ $report->waste_type }}</p>
            <p><strong>Submitted:</strong> {{ $report->created_at->format('Y-m-d h:i A') }}</p>
            <p><strong>Status:</strong> {{ $report->status }}</p>

            @if ($report->status === 'Assigned')
                <form method="POST" action="{{ route('collector.report.collected', $report->id) }}">
                    @csrf
                    <button type="submit" class="mt-2 px-4 py-1 bg-green-600 text-white rounded hover:bg-green-700">
                        Mark as Collected
                    </button>
                </form>
            @else
                <p class="text-green-600 mt-2">✔️ Collected</p>
            @endif
        </div>
    @endforeach
</div>

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
                }
            }).catch(error => {
                console.error('Error updating location:', error);
            });
        });
    }

    // Update every 30 seconds
    setInterval(updateCollectorLocation, 30000);
    // Update once immediately on load
    updateCollectorLocation();
</script>
@endsection