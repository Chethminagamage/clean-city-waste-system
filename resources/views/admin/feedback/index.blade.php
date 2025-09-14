@extends('admin.layout.app')

@section('title', 'Feedback Management')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Feedback Management</h1>
            <p class="text-gray-600">Monitor and respond to user feedback</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.feedback.export', ['range' => request('range', 30), 'format' => 'csv']) }}" 
               class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                Export CSV
            </a>
        </div>
    </div>

    <!-- Analytics Dashboard -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.418 8-9 8a9.954 9.954 0 01-4.951-1.308A3.001 3.001 0 013 13.5c0-.133.01-.265.029-.395A5.002 5.002 0 019 7a9.954 9.954 0 014.951 1.308A3.001 3.001 0 0121 12z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Total Feedback</h3>
                    <p class="text-2xl font-semibold text-gray-900">{{ $analytics['total_feedback'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Average Rating</h3>
                    <p class="text-2xl font-semibold text-gray-900">{{ $analytics['average_rating'] }}/5</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Low Ratings</h3>
                    <p class="text-2xl font-semibold text-gray-900">{{ $analytics['low_ratings'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Response Rate</h3>
                    <p class="text-2xl font-semibold text-gray-900">{{ $analytics['response_rate'] }}%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-lg shadow mb-6">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Time Range</label>
                <select name="range" class="border border-gray-300 rounded-lg px-3 py-2">
                    <option value="7" {{ request('range') == '7' ? 'selected' : '' }}>Last 7 days</option>
                    <option value="30" {{ request('range') == '30' ? 'selected' : '' }}>Last 30 days</option>
                    <option value="90" {{ request('range') == '90' ? 'selected' : '' }}>Last 90 days</option>
                    <option value="365" {{ request('range') == '365' ? 'selected' : '' }}>Last year</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Feedback Type</label>
                <select name="type" class="border border-gray-300 rounded-lg px-3 py-2">
                    <option value="all" {{ request('type') == 'all' ? 'selected' : '' }}>All Types</option>
                    <option value="report_feedback" {{ request('type') == 'report_feedback' ? 'selected' : '' }}>Report Feedback</option>
                    <option value="general_feedback" {{ request('type') == 'general_feedback' ? 'selected' : '' }}>General Feedback</option>
                    <option value="service_feedback" {{ request('type') == 'service_feedback' ? 'selected' : '' }}>Service Feedback</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                <select name="rating" class="border border-gray-300 rounded-lg px-3 py-2">
                    <option value="all" {{ request('rating') == 'all' ? 'selected' : '' }}>All Ratings</option>
                    <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 Star</option>
                    <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 Stars</option>
                    <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 Stars</option>
                    <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 Stars</option>
                    <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Stars</option>
                </select>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Apply Filters
            </button>
        </form>
    </div>

    <!-- Rating Distribution Chart -->
    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Rating Distribution</h3>
        @if(!empty($analytics['rating_distribution']))
            <div class="space-y-3">
                @foreach($analytics['rating_distribution'] as $rating => $count)
                    @php
                        $percentage = $analytics['total_feedback'] > 0 ? ($count / $analytics['total_feedback']) * 100 : 0;
                    @endphp
                    <div class="flex items-center">
                        <span class="w-20 text-sm text-gray-600">{{ $rating }} Stars</span>
                        <div class="flex-1 bg-gray-200 rounded-full h-4 mx-4">
                            <div class="bg-yellow-400 h-4 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                        <span class="w-16 text-sm text-gray-600 text-right">{{ $count }} ({{ number_format($percentage, 1) }}%)</span>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-star text-4xl text-gray-300 mb-2"></i>
                <p>No ratings available for the selected period</p>
            </div>
        @endif
    </div>

    <!-- Common Issues -->
    @if(!empty($analytics['common_issues']))
    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Common Issues (Low Ratings)</h3>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            @foreach($analytics['common_issues'] as $issue => $count)
                <div class="text-center p-3 bg-red-50 rounded-lg">
                    <div class="text-2xl font-bold text-red-600">{{ $count }}</div>
                    <div class="text-sm text-gray-600 capitalize">{{ $issue }}</div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Feedback List -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Recent Feedback</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($feedbacks as $feedback)
                    <tr class="{{ $feedback->needsAttention() ? 'bg-red-50' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="text-sm font-medium text-gray-900">{{ $feedback->user->name ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500">{{ $feedback->user->email ?? 'N/A' }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 max-w-xs truncate">
                                {{ $feedback->subject ?? 'No subject' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                {{ ucfirst(str_replace('_', ' ', $feedback->feedback_type)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="text-yellow-400">{{ $feedback->getRatingStars() }}</span>
                                <span class="ml-2 text-sm text-gray-600">({{ $feedback->rating }}/5)</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 max-w-xs truncate">
                                {{ $feedback->message ?? 'No message' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $feedback->getStatusBadgeClass() }}">
                                {{ ucfirst($feedback->status ?? 'pending') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $feedback->created_at->format('M j, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.feedback.show', $feedback->id) }}" 
                               class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                            @if(!$feedback->admin_response)
                                <a href="#" onclick="openResponseModal({{ $feedback->id }})" 
                                   class="text-green-600 hover:text-green-900">Respond</a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            No feedback found for the selected criteria.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($feedbacks->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $feedbacks->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Response Modal -->
<div id="responseModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <form id="responseForm" method="POST">
                @csrf
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Respond to Feedback</h3>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <label for="admin_response" class="block text-sm font-medium text-gray-700 mb-2">
                            Your Response
                        </label>
                        <textarea name="admin_response" id="admin_response" rows="4" 
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Thank you for your feedback..."></textarea>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-2">
                    <button type="button" onclick="closeResponseModal()" 
                            class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Send Response
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openResponseModal(feedbackId) {
    const modal = document.getElementById('responseModal');
    const form = document.getElementById('responseForm');
    form.action = `/admin/feedback/${feedbackId}/respond`;
    modal.classList.remove('hidden');
}

function closeResponseModal() {
    const modal = document.getElementById('responseModal');
    modal.classList.add('hidden');
    document.getElementById('admin_response').value = '';
}
</script>
@endsection
