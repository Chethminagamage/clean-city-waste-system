@extends('admin.layout.app')

@section('content')
    <div class="p-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Submitted Bin Reports</h1>
            <p class="text-gray-600">Manage and track all waste collection reports</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-40">User</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-48">Location</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Type</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-28">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-28">Submitted</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Collector</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($reports as $report)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 whitespace-nowrap w-40">
                                    <div class="flex items-center">
                                        <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-2 flex-shrink-0">
                                            <i class="fas fa-user text-blue-600 text-xs"></i>
                                        </div>
                                        <div class="text-sm font-medium text-gray-900 truncate">
                                            {{ $report->resident->name ?? 'N/A' }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 w-48">
                                    <div class="text-sm text-gray-900 truncate max-w-48" title="{{ $report->location }}">
                                        {{ $report->location }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap w-24">
                                    @php
                                        $typeColors = [
                                            'organic' => 'bg-green-100 text-green-800',
                                            'plastic' => 'bg-blue-100 text-blue-800',
                                            'hazardous' => 'bg-red-100 text-red-800',
                                            'e-waste' => 'bg-purple-100 text-purple-800',
                                            'mixed' => 'bg-gray-100 text-gray-800'
                                        ];
                                        $typeColor = $typeColors[strtolower($report->waste_type ?? '')] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $typeColor }}">
                                        {{ $report->waste_type ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap w-28">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'assigned' => 'bg-blue-100 text-blue-800',
                                            'enroute' => 'bg-purple-100 text-purple-800',
                                            'enroute' => 'bg-purple-100 text-purple-800',
                                            'collected' => 'bg-green-100 text-green-800',
                                            'closed' => 'bg-gray-100 text-gray-800',
                                            'cancelled' => 'bg-red-100 text-red-800'
                                        ];
                                        $statusColor = $statusColors[strtolower($report->status ?? '')] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColor }} inline-flex items-center">
                                        {{ ucfirst($report->status) }}
                                        @if($report->completion_image_path && in_array(strtolower($report->status), ['collected', 'closed']))
                                            <i class="fas fa-camera ml-1" title="Completion photo available"></i>
                                        @endif
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 w-28">
                                    {{ \Carbon\Carbon::parse($report->created_at)->format('M d') }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap w-32">
                                    @if($report->collector)
                                        <div class="flex items-center">
                                            <div class="w-5 h-5 bg-orange-100 rounded-full flex items-center justify-center mr-1 flex-shrink-0">
                                                <i class="fas fa-truck text-orange-600 text-xs"></i>
                                            </div>
                                            <div class="text-sm text-gray-900 truncate">{{ $report->collector->name }}</div>
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm">Unassigned</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-center w-32">
                                    <!-- Single View Button -->
                                    <a href="{{ route('admin.reports.show', $report->id) }}" 
                                       class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors"
                                       title="View Details">
                                        <i class="fas fa-eye mr-2"></i>View
                                    </a>
                                </td>
                            </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-400">
                                    <i class="fas fa-inbox text-4xl mb-4"></i>
                                    <p class="text-lg font-medium">No reports submitted yet</p>
                                    <p class="text-sm">Reports will appear here once residents submit them</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $reports->links() }}
        </div>
    </div>
@endsection