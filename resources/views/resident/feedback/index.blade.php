@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">My Feedback History</h1>
        <p class="text-gray-600">View your feedback submissions and responses from our team</p>
    </div>

    <!-- Quick Stats -->
    @php
        $summary = app('App\Http\Controllers\ResidentFeedbackController')->getSummary();
    @endphp
    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-2xl font-bold text-blue-600">{{ $summary['total_feedback'] }}</div>
            <div class="text-sm text-gray-600">Total Feedback</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-2xl font-bold text-yellow-600">{{ $summary['pending_responses'] }}</div>
            <div class="text-sm text-gray-600">Pending Responses</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-2xl font-bold text-green-600">{{ $summary['recent_responses'] }}</div>
            <div class="text-sm text-gray-600">New Responses (7 days)</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-2xl font-bold text-purple-600">{{ $summary['average_rating_given'] }}/5</div>
            <div class="text-sm text-gray-600">Average Rating Given</div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mb-6 flex gap-4">
        <a href="{{ route('feedback.create') }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
            Submit New Feedback
        </a>
        <button onclick="markAllResponsesRead()" 
                class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
            Mark All as Read
        </button>
    </div>

    <!-- Feedback List -->
    <div class="space-y-6">
        @forelse($feedbacks as $feedback)
            <div class="bg-white rounded-lg shadow p-6 {{ $feedback->admin_response && !$feedback->user_read_response ? 'border-l-4 border-green-500' : '' }}">
                <!-- Header -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-lg text-yellow-400">{{ $feedback->getRatingStars() }}</span>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                {{ ucfirst(str_replace('_', ' ', $feedback->feedback_type)) }}
                            </span>
                            @if($feedback->wasteReport)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Report #{{ $feedback->wasteReport->id }}
                                </span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600">{{ $feedback->created_at->format('M j, Y \a\t g:i A') }}</p>
                    </div>
                    
                    @if($feedback->admin_response)
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                            Response Received
                        </span>
                    @else
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Awaiting Response
                        </span>
                    @endif
                </div>

                <!-- Your Feedback -->
                @if($feedback->message)
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Your Feedback:</h4>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-gray-900">{{ $feedback->message }}</p>
                        </div>
                    </div>
                @endif

                <!-- Admin Response -->
                @if($feedback->admin_response)
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Our Response:</h4>
                        <div class="bg-green-50 border-l-4 border-green-400 p-3">
                            <p class="text-green-900">{{ $feedback->admin_response }}</p>
                            <p class="text-xs text-green-700 mt-2">
                                Responded on {{ $feedback->admin_responded_at->format('M j, Y \a\t g:i A') }}
                                @if($feedback->adminRespondedBy)
                                    by {{ $feedback->adminRespondedBy->name }}
                                @endif
                            </p>
                        </div>
                    </div>
                @endif

                <!-- Actions -->
                <div class="flex gap-3">
                    <a href="{{ route('resident.feedback.show', $feedback->id) }}" 
                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        View Details
                    </a>
                    @if($feedback->wasteReport)
                        <a href="{{ route('resident.reports.show', $feedback->wasteReport->id) }}" 
                           class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                            View Related Report
                        </a>
                    @endif
                    @if($feedback->admin_response)
                        <button onclick="markAsHelpful({{ $feedback->id }})" 
                                class="text-green-600 hover:text-green-800 text-sm font-medium">
                            👍 Helpful Response
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <div class="w-24 h-24 mx-auto mb-4 text-gray-300">
                    <svg fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-7 12h-2v-2h2v2zm0-6h-2V6h2v2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No feedback submitted yet</h3>
                <p class="text-gray-600 mb-4">Share your experience to help us improve our services!</p>
                <a href="{{ route('feedback.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Submit Your First Feedback
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($feedbacks->hasPages())
        <div class="mt-8">
            {{ $feedbacks->links() }}
        </div>
    @endif
</div>

<script>
function markAllResponsesRead() {
    fetch('/resident/feedback/mark-responses-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function markAsHelpful(feedbackId) {
    fetch(`/resident/feedback/${feedbackId}/helpful`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Thank you for your feedback on our response!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
</script>
@endsection
