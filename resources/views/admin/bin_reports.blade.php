@extends('admin.layout.app')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Submitted Bin Reports</h1>

    <table class="min-w-full bg-white border rounded shadow">
        <thead class="bg-gray-100">
            <tr>
                <th class="py-2 px-4">User</th>
                <th class="py-2 px-4">Location</th>
                <th class="py-2 px-4">Type</th>
                <th class="py-2 px-4">Status</th>
                <th class="py-2 px-4">Submitted</th>
                <th class="py-2 px-4">Collector</th>
                <th class="py-2 px-4">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($reports as $report)
                <tr class="border-t">
                    <td class="py-2 px-4">{{ $report->resident->name ?? 'N/A' }}</td>
                    <td class="py-2 px-4">{{ $report->location }}</td>
                    <td class="py-2 px-4">{{ $report->waste_type ?? 'N/A' }}</td>
                    <td class="py-2 px-4">{{ $report->status }}</td>
                    <td class="py-2 px-4">{{ \Carbon\Carbon::parse($report->created_at)->format('Y-m-d') }}</td>
                    <td class="py-2 px-4">
                        {{ $report->collector->name ?? '-' }}
                    </td>
                    <td class="py-2 px-4">
                    @php $status = strtolower($report->status ?? ''); @endphp

                    @if (empty($report->collector_id) && $status === 'pending')
                        <button
                        onclick="openAssignModal({{ $report->id }})"
                        class="bg-blue-600 text-white text-sm px-4 py-1 rounded hover:bg-blue-700 transition">
                        Assign
                        </button>
                    @elseif ($status === 'collected')
                        <form action="{{ route('admin.reports.close', $report->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-green-600 text-white text-sm px-4 py-1 rounded hover:bg-green-700 transition">
                                Close Report
                            </button>
                        </form>
                    @elseif ($status === 'closed')
                        <span class="text-green-600 text-sm">Closed</span>
                    @elseif (!empty($report->collector_id))
                        <span class="text-blue-600 text-sm">Assigned</span>
                    @else
                        <span class="text-gray-500 text-sm">—</span>
                    @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-gray-500">No reports submitted yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $reports->links() }}
    </div>

    <!-- Assign Modal -->
    <div id="assignModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded w-[400px] shadow-xl">
            <h2 class="text-lg font-semibold mb-4">Select Nearest Collector</h2>
            <form method="POST" id="assignForm">
                @csrf
                <select id="collector_select" name="collector_id" class="w-full p-2 border rounded mb-4" required></select>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal()" class="text-gray-600">Cancel</button>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Assign</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function openAssignModal(reportId) {
            fetch(`/admin/report/${reportId}/nearby-collectors`)
                .then(res => res.json())
                .then(data => {
                    const select = document.getElementById('collector_select');
                    select.innerHTML = '';

                    if (data.length === 0) {
                        const option = document.createElement('option');
                        option.text = 'No nearby collectors found';
                        option.disabled = true;
                        select.appendChild(option);
                    } else {
                        data.forEach(collector => {
                            const option = document.createElement('option');
                            option.value = collector.id;
                            option.text = `${collector.name} (${collector.distance.toFixed(2)} km)`;
                            select.appendChild(option);
                        });
                    }

                    document.getElementById('assignForm').action = `/admin/assign-collector/${reportId}`;
                    document.getElementById('assignModal').classList.remove('hidden');
                });
        }

        function closeModal() {
            document.getElementById('assignModal').classList.add('hidden');
        }
    </script>
@endsection