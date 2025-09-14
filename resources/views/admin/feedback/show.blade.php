@extends('admin.layout.app')

@section('title', 'Feedback Details')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    <!-- Header -->
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.feedback.index') }}" 
           class="mr-4 p-2 text-gray-600 hover:text-gray-900 rounded-full hover:bg-gray-100">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Feedback Details</h1>
            <p class="text-gray-600">Review and respond to user feedback</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Feedback Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-medium text-gray-900">Feedback Information</h2>
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $feedback->getStatusBadgeClass() }}">
                            {{ ucfirst($feedback->status ?? 'pending') }}
                        </span>
                        @if($feedback->needsAttention())
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                Needs Attention
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Rating -->
                <div class="mb-6">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl text-yellow-400">{{ $feedback->getRatingStars() }}</span>
                        <span class="text-lg font-medium text-gray-900">{{ $feedback->rating }}/5 Rating</span>
                        @if($feedback->isLowRating())
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">
                                Low Rating
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Feedback Subject -->
                @if($feedback->subject)
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Subject</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-900 font-medium">{{ $feedback->subject }}</p>
                    </div>
                </div>
                @endif

                <!-- Feedback Message -->
                @if($feedback->message)
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Message</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-900 whitespace-pre-wrap">{{ $feedback->message }}</p>
                    </div>
                </div>
                @endif

                <!-- Report Context -->
                @if($feedback->wasteReport)
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Related Report</h3>
                    <div class="bg-blue-50 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-blue-900">Report #{{ $feedback->wasteReport->id }}</p>
                                <p class="text-sm text-blue-700">{{ $feedback->wasteReport->description }}</p>
                                <p class="text-xs text-blue-600 mt-1">
                                    Reported on {{ $feedback->wasteReport->created_at->format('M j, Y \a\t g:i A') }}
                                </p>
                                @if($feedback->wasteReport->collector)
                                    <p class="text-xs text-blue-600">
                                        Collector: {{ $feedback->wasteReport->collector->name }}
                                    </p>
                                @endif
                            </div>
                            <a href="{{ route('admin.reports.show', $feedback->wasteReport->id) }}" 
                               class="text-blue-600 hover:text-blue-800 font-medium">
                                View Report
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Admin Response -->
                @if($feedback->admin_response)
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Admin Response</h3>
                    <div class="bg-green-50 border-l-4 border-green-400 p-4">
                        <p class="text-gray-900 whitespace-pre-wrap">{{ $feedback->admin_response }}</p>
                        <div class="text-xs text-green-700 mt-2 flex items-center gap-2">
                            <span>Responded by {{ $feedback->adminRespondedBy->name ?? 'Admin' }}</span>
                            <span>•</span>
                            <span>{{ $feedback->admin_responded_at->format('M j, Y \a\t g:i A') }}</span>
                        </div>
                    </div>
                </div>
                @else
                <!-- Response Form -->
                <div class="border-t pt-6">
                    <h3 class="text-sm font-medium text-gray-700 mb-4">Respond to Feedback</h3>
                    <form action="{{ route('admin.feedback.respond', $feedback->id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <textarea name="admin_response" rows="4" 
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Thank you for your feedback. We appreciate your input..."
                                      required></textarea>
                            @error('admin_response')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex gap-3">
                            <button type="submit" 
                                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                Send Response
                            </button>
                            <button type="button" onclick="markResolved({{ $feedback->id }})"
                                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                                Mark as Resolved
                            </button>
                        </div>
                    </form>
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- User Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">User Information</h3>
                <div class="space-y-3">
                    <div>
                        <span class="text-sm font-medium text-gray-500">Name</span>
                        <p class="text-gray-900">{{ $feedback->user->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Email</span>
                        <p class="text-gray-900">{{ $feedback->user->email ?? 'N/A' }}</p>
                    </div>
                    @if($feedback->user->phone)
                    <div>
                        <span class="text-sm font-medium text-gray-500">Phone</span>
                        <p class="text-gray-900">{{ $feedback->user->phone }}</p>
                    </div>
                    @endif
                    <div>
                        <span class="text-sm font-medium text-gray-500">User Since</span>
                        <p class="text-gray-900">{{ $feedback->user->created_at->format('M j, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Feedback Metadata -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Feedback Details</h3>
                <div class="space-y-3">
                    <div>
                        <span class="text-sm font-medium text-gray-500">Type</span>
                        <p class="text-gray-900">{{ ucfirst(str_replace('_', ' ', $feedback->feedback_type)) }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Submitted</span>
                        <p class="text-gray-900">{{ $feedback->created_at->format('M j, Y \a\t g:i A') }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Last Updated</span>
                        <p class="text-gray-900">{{ $feedback->updated_at->format('M j, Y \a\t g:i A') }}</p>
                    </div>
                    @if($feedback->resolved_at)
                    <div>
                        <span class="text-sm font-medium text-gray-500">Resolved</span>
                        <p class="text-gray-900">{{ $feedback->resolved_at->format('M j, Y \a\t g:i A') }}</p>
                        @if($feedback->resolvedBy)
                            <p class="text-sm text-gray-600">by {{ $feedback->resolvedBy->name }}</p>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    @if(!$feedback->admin_response)
                    <button onclick="focusResponseForm()" 
                            class="w-full text-left px-3 py-2 text-blue-600 hover:bg-blue-50 rounded">
                        📝 Respond to Feedback
                    </button>
                    @endif
                    
                    @if($feedback->status !== 'resolved')
                    <button onclick="markResolved({{ $feedback->id }})" 
                            class="w-full text-left px-3 py-2 text-green-600 hover:bg-green-50 rounded">
                        ✅ Mark as Resolved
                    </button>
                    @endif
                    
                    @if($feedback->wasteReport)
                    <a href="{{ route('admin.reports.show', $feedback->wasteReport->id) }}" 
                       class="block px-3 py-2 text-purple-600 hover:bg-purple-50 rounded">
                        🔍 View Related Report
                    </a>
                    @endif
                    
                    <a href="{{ route('admin.feedback.index', ['user_id' => $feedback->user_id]) }}" 
                       class="block px-3 py-2 text-gray-600 hover:bg-gray-50 rounded">
                        👤 View User's Other Feedback
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function markResolved(feedbackId) {
    if (confirm('Mark this feedback as resolved?')) {
        fetch(`/admin/feedback/${feedbackId}/resolve`, {
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
            } else {
                alert('Error marking as resolved');
            }
        });
    }
}

function focusResponseForm() {
    const textarea = document.querySelector('textarea[name="admin_response"]');
    if (textarea) {
        textarea.focus();
        textarea.scrollIntoView({ behavior: 'smooth' });
    }
}
</script>
@endsection
