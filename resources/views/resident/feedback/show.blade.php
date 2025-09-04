@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-green-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 transition-colors duration-300">
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <!-- Header -->
    <div class="flex items-center mb-6">
        <a href="{{ route('resident.feedback.index') }}" 
           class="mr-4 p-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Feedback Details</h1>
            <p class="text-gray-600 dark:text-gray-300">Your feedback and our response</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Feedback Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
                <!-- Header -->
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl text-yellow-400">{{ $feedback->getRatingStars() }}</span>
                        <div>
                            <div class="font-medium text-gray-900 dark:text-white">{{ $feedback->rating }}/5 Rating</div>
                            <div class="text-sm text-gray-600 dark:text-gray-300">
                                {{ ucfirst(str_replace('_', ' ', $feedback->feedback_type)) }}
                            </div>
                        </div>
                    </div>
                    @if($feedback->admin_response)
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300">
                            Response Received
                        </span>
                    @else
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300">
                            Awaiting Response
                        </span>
                    @endif
                </div>

                <!-- Your Feedback -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Your Feedback</h3>
                    @if($feedback->message)
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                            <p class="text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $feedback->message }}</p>
                        </div>
                    @else
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                            <p class="text-gray-600 dark:text-gray-400 italic">No written feedback provided - Rating only</p>
                        </div>
                    @endif
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                        Submitted on {{ $feedback->created_at->format('F j, Y \a\t g:i A') }}
                    </p>
                </div>

                <!-- Admin Response -->
                @if($feedback->admin_response)
                    <div class="border-t dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Our Response</h3>
                        <div class="bg-green-50 dark:bg-green-900/30 border-l-4 border-green-400 dark:border-green-500 p-4 rounded-r-lg">
                            <p class="text-green-900 dark:text-green-100 whitespace-pre-wrap">{{ $feedback->admin_response }}</p>
                            <div class="flex items-center justify-between mt-3 pt-3 border-t border-green-200 dark:border-green-700">
                                <div class="text-sm text-green-700 dark:text-green-300">
                                    Responded on {{ $feedback->admin_responded_at->format('F j, Y \a\t g:i A') }}
                                    @if($feedback->adminRespondedBy)
                                        by {{ $feedback->adminRespondedBy->name }}
                                    @endif
                                </div>
                                <div class="flex gap-2">
                                    <button onclick="rateResponse('helpful', {{ $feedback->id }})"
                                            class="px-3 py-1 text-xs bg-green-100 dark:bg-green-800 text-green-700 dark:text-green-300 rounded hover:bg-green-200 dark:hover:bg-green-700">
                                        👍 Helpful
                                    </button>
                                    <button onclick="rateResponse('not_helpful', {{ $feedback->id }})"
                                            class="px-3 py-1 text-xs bg-red-100 dark:bg-red-800 text-red-700 dark:text-red-300 rounded hover:bg-red-200 dark:hover:bg-red-700">
                                        👎 Not Helpful
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="border-t dark:border-gray-700 pt-6">
                        <div class="bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="text-yellow-400 mr-3">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-yellow-800 dark:text-yellow-300 font-medium">Response Pending</h4>
                                    <p class="text-yellow-700 dark:text-yellow-400 text-sm">
                                        We're reviewing your feedback and will respond soon. 
                                        You'll receive a notification when we reply.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Related Report (if exists) -->
            @if($feedback->wasteReport)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Related Waste Report</h3>
                    <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-medium text-blue-900 dark:text-blue-200">Report #{{ $feedback->wasteReport->id }}</h4>
                                <p class="text-blue-700 dark:text-blue-300 text-sm">{{ $feedback->wasteReport->description }}</p>
                                <p class="text-blue-600 dark:text-blue-400 text-xs mt-1">
                                    Reported on {{ $feedback->wasteReport->created_at->format('M j, Y') }} • 
                                    Status: {{ ucfirst($feedback->wasteReport->status) }}
                                </p>
                            </div>
                            <a href="{{ route('resident.reports.show', $feedback->wasteReport->id) }}" 
                               class="px-3 py-1 bg-blue-600 dark:bg-blue-700 text-white text-sm rounded hover:bg-blue-700 dark:hover:bg-blue-800">
                                View Report
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Feedback Info -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Feedback Information</h3>
                <div class="space-y-3">
                    <div>
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Type</span>
                        <p class="text-gray-900 dark:text-gray-100">{{ ucfirst(str_replace('_', ' ', $feedback->feedback_type)) }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Rating</span>
                        <p class="text-gray-900 dark:text-gray-100">{{ $feedback->rating }} out of 5 stars</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Submitted</span>
                        <p class="text-gray-900 dark:text-gray-100">{{ $feedback->created_at->format('M j, Y g:i A') }}</p>
                    </div>
                    @if($feedback->admin_responded_at)
                        <div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Response Time</span>
                            <p class="text-gray-900 dark:text-gray-100">{{ $feedback->created_at->diffInHours($feedback->admin_responded_at) }} hours</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('feedback.create') }}" 
                       class="block w-full text-center px-4 py-2 bg-blue-600 dark:bg-blue-700 text-white rounded hover:bg-blue-700 dark:hover:bg-blue-800">
                        📝 Submit New Feedback
                    </a>
                    
                    @if($feedback->wasteReport)
                        <a href="{{ route('resident.reports.show', $feedback->wasteReport->id) }}" 
                           class="block w-full text-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-50 dark:hover:bg-gray-700">
                            🔍 View Related Report
                        </a>
                    @endif
                    
                    @if($feedback->admin_response)
                        <button onclick="sharePositiveFeedback()" 
                                class="block w-full text-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-50 dark:hover:bg-gray-700">
                            📤 Share Experience
                        </button>
                    @endif
                </div>
            </div>

            <!-- Help -->
            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                <h4 class="font-medium text-gray-900 dark:text-white mb-2">Need More Help?</h4>
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">
                    If this response doesn't fully address your concern, you can submit additional feedback.
                </p>
                <a href="{{ route('feedback.create') }}" 
                   class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">
                    Submit Follow-up Feedback →
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function rateResponse(rating, feedbackId) {
    fetch(`/resident/feedback/${feedbackId}/rate-response`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ rating: rating })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Thank you for rating our response!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function sharePositiveFeedback() {
    const text = `Great service from Clean City! They responded to my feedback professionally and promptly. 🌟`;
    if (navigator.share) {
        navigator.share({
            title: 'Clean City Service',
            text: text,
        });
    } else {
        // Fallback to copy to clipboard
        navigator.clipboard.writeText(text).then(() => {
            alert('Copied to clipboard! Share this on your social media.');
        });
    }
}
</script>
</div>
</div>
@endsection
