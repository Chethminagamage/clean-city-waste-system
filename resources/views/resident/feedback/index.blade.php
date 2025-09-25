@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-green-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 transition-colors duration-300">
<div class="container mx-auto px-3 sm:px-4 lg:px-6 py-4 sm:py-6 lg:py-8">
    <!-- Header -->
    <div class="mb-4 sm:mb-5 lg:mb-6">
        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white mb-1 sm:mb-2">My Feedback History</h1>
        <p class="text-sm sm:text-base text-gray-600 dark:text-gray-300">View your feedback submissions and responses from our team</p>
    </div>

    <!-- Quick Stats -->
    @php
        $summary = app('App\Http\Controllers\ResidentFeedbackController')->getSummary();
    @endphp
    
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-3 lg:gap-4 mb-4 sm:mb-6 lg:mb-8">
        <div class="bg-white dark:bg-gray-800 p-3 sm:p-4 rounded-lg shadow">
            <div class="text-lg sm:text-xl lg:text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $summary['total_feedback'] }}</div>
            <div class="text-xs sm:text-sm text-gray-600 dark:text-gray-300">Total Feedback</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-3 sm:p-4 rounded-lg shadow">
            <div class="text-lg sm:text-xl lg:text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $summary['pending_responses'] }}</div>
            <div class="text-xs sm:text-sm text-gray-600 dark:text-gray-300">Pending Responses</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-3 sm:p-4 rounded-lg shadow">
            <div class="text-lg sm:text-xl lg:text-2xl font-bold text-green-600 dark:text-green-400">{{ $summary['recent_responses'] }}</div>
            <div class="text-xs sm:text-sm text-gray-600 dark:text-gray-300">New Responses <span class="hidden sm:inline">(7 days)</span></div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-3 sm:p-4 rounded-lg shadow">
            <div class="text-lg sm:text-xl lg:text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $summary['average_rating_given'] }}/5</div>
            <div class="text-xs sm:text-sm text-gray-600 dark:text-gray-300">Average Rating <span class="hidden sm:inline">Given</span></div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mb-4 sm:mb-5 lg:mb-6 flex flex-col sm:flex-row gap-2 sm:gap-3 lg:gap-4">
        <a href="{{ route('feedback.create') }}" 
           class="bg-blue-600 dark:bg-blue-700 text-white px-3 sm:px-4 py-2 rounded-lg hover:bg-blue-700 dark:hover:bg-blue-800 transition-colors text-sm sm:text-base text-center sm:text-left">
            Submit New Feedback
        </a>
        <button onclick="markAllResponsesRead()" 
                class="bg-green-600 dark:bg-green-700 text-white px-3 sm:px-4 py-2 rounded-lg hover:bg-green-700 dark:hover:bg-green-800 transition-colors text-sm sm:text-base">
            Mark All as Read
        </button>
    </div>

    <!-- Feedback List -->
    <div class="space-y-3 sm:space-y-4 lg:space-y-6">
        @forelse($feedbacks as $feedback)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 sm:p-4 lg:p-6 {{ $feedback->admin_response && !$feedback->user_read_response ? 'border-l-4 border-green-500 dark:border-green-400' : '' }}">
                <!-- Header -->
                <div class="flex flex-col sm:flex-row sm:items-start justify-between mb-3 sm:mb-4 gap-2 sm:gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 sm:gap-3 mb-2">
                            <span class="text-base sm:text-lg text-yellow-400">{{ $feedback->getRatingStars() }}</span>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                {{ ucfirst(str_replace('_', ' ', $feedback->feedback_type)) }}
                            </span>
                            @if($feedback->wasteReport)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300">
                                    Report #{{ $feedback->wasteReport->id }}
                                </span>
                            @endif
                        </div>
                        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">{{ $feedback->created_at->format('M j, Y \a\t g:i A') }}</p>
                    </div>
                    
                    @if($feedback->admin_response)
                        <span class="px-2 sm:px-3 py-1 text-xs sm:text-sm font-semibold rounded-full bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300 flex-shrink-0">
                            Response Received
                        </span>
                    @else
                        <span class="px-2 sm:px-3 py-1 text-xs sm:text-sm font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300 flex-shrink-0">
                            Awaiting Response
                        </span>
                    @endif
                </div>

                <!-- Your Feedback -->
                @if($feedback->message)
                    <div class="mb-3 sm:mb-4">
                        <h4 class="text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 sm:mb-2">Your Feedback:</h4>
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-2.5 sm:p-3">
                            <p class="text-sm sm:text-base text-gray-900 dark:text-gray-100 line-clamp-3 sm:line-clamp-none">{{ $feedback->message }}</p>
                        </div>
                    </div>
                @endif

                <!-- Admin Response -->
                @if($feedback->admin_response)
                    <div class="mb-3 sm:mb-4">
                        <h4 class="text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 sm:mb-2">Our Response:</h4>
                        <div class="bg-green-50 dark:bg-green-900/30 border-l-4 border-green-400 dark:border-green-500 p-2.5 sm:p-3">
                            <p class="text-sm sm:text-base text-green-900 dark:text-green-100 line-clamp-3 sm:line-clamp-none">{{ $feedback->admin_response }}</p>
                            <p class="text-xs text-green-700 dark:text-green-300 mt-1.5 sm:mt-2">
                                Responded on {{ $feedback->admin_responded_at->format('M j, Y \a\t g:i A') }}
                                @if($feedback->adminRespondedBy)
                                    by {{ $feedback->adminRespondedBy->name }}
                                @endif
                            </p>
                        </div>
                    </div>
                @endif

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                    <a href="{{ route('resident.feedback.show', $feedback->id) }}" 
                       class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-xs sm:text-sm font-medium">
                        View Details
                    </a>
                    @if($feedback->wasteReport)
                        <a href="{{ route('resident.reports.show', $feedback->wasteReport->id) }}" 
                           class="text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300 text-xs sm:text-sm font-medium">
                            View Related Report
                        </a>
                    @endif
                    @if($feedback->admin_response)
                        <button onclick="markAsHelpful({{ $feedback->id }})" 
                                class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 text-xs sm:text-sm font-medium text-left">
                            👍 Helpful Response
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-8 sm:py-10 lg:py-12">
                <div class="w-16 h-16 sm:w-20 sm:h-20 lg:w-24 lg:h-24 mx-auto mb-3 sm:mb-4 text-gray-300 dark:text-gray-600">
                    <svg fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-7 12h-2v-2h2v2zm0-6h-2V6h2v2z"/>
                    </svg>
                </div>
                <h3 class="text-base sm:text-lg font-medium text-gray-900 dark:text-white mb-2">No feedback submitted yet</h3>
                <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mb-3 sm:mb-4 px-4">Share your experience to help us improve our services!</p>
                <a href="{{ route('feedback.create') }}" 
                   class="inline-flex items-center px-3 sm:px-4 py-2 bg-blue-600 dark:bg-blue-700 text-white rounded-lg hover:bg-blue-700 dark:hover:bg-blue-800 text-sm sm:text-base">
                    Submit Your First Feedback
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($feedbacks->hasPages())
        <div class="mt-4 sm:mt-6 lg:mt-8">
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
</div>
</div>
@endsection
