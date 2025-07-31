@extends('admin.layout.app')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Submitted Bin Reports</h1>

    <table class="min-w-full bg-white border rounded shadow">
        <thead class="bg-gray-100">
            <tr>
                <th class="py-2 px-4">User</th>
                <th class="py-2 px-4">Location</th>
                <th class="py-2 px-4">Zone</th>
                <th class="py-2 px-4">Status</th>
                <th class="py-2 px-4">Submitted</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $report)
                <tr class="border-t">
                    <td class="py-2 px-4">{{ $report->user->first_name ?? 'N/A' }}</td>
                    <td class="py-2 px-4">{{ $report->location }}</td>
                    <td class="py-2 px-4">{{ $report->zone }}</td>
                    <td class="py-2 px-4">{{ $report->status }}</td>
                    <td class="py-2 px-4">{{ $report->submitted_at->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $reports->links() }}
    </div>
@endsection