@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-green-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 transition-colors duration-300">
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 dark:from-blue-600 dark:to-purple-700 p-6 text-white">
            <h1 class="text-2xl font-bold">We Value Your Feedback</h1>
            <p class="mt-2 opacity-90">Help us improve our waste management services</p>
        </div>

        <div class="p-6">
            @if(session('success'))
                <div class="mb-6 bg-green-100 dark:bg-green-900/20 border border-green-400 dark:border-green-500/30 text-green-700 dark:text-green-300 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 dark:bg-red-900/20 border border-red-400 dark:border-red-500/30 text-red-700 dark:text-red-300 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('feedback.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Feedback Type -->
                <div>
                    <label for="feedback_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        What would you like to share feedback about? *
                    </label>
                    <select name="feedback_type" id="feedback_type" required
                            class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400">
                        <option value="">Please select...</option>
                        <option value="service_quality">Overall Service Quality</option>
                        <option value="collection_timeliness">Collection Timeliness</option>
                        <option value="staff_behavior">Staff Behavior</option>
                        <option value="app_experience">App Experience</option>
                        <option value="communication">Communication</option>
                        <option value="bin_conditions">Bin Conditions</option>
                        <option value="suggestion">Suggestion for Improvement</option>
                        <option value="complaint">Complaint</option>
                        <option value="compliment">Compliment</option>
                        <option value="other">Other</option>
                    </select>
                    @error('feedback_type')
                        <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Rating -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Overall Rating *
                    </label>
                    <div class="flex items-center space-x-2">
                        <div class="flex space-x-1" id="star-rating">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" 
                                        class="star text-2xl text-gray-300 dark:text-gray-600 hover:text-yellow-400 dark:hover:text-yellow-400 transition-colors duration-150"
                                        data-rating="{{ $i }}">
                                    ⭐
                                </button>
                            @endfor
                        </div>
                        <span id="rating-text" class="text-sm text-gray-600 dark:text-gray-400 ml-3"></span>
                    </div>
                    <input type="hidden" name="rating" id="rating-input" required>
                    @error('rating')
                        <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Message -->
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Details (Optional)
                    </label>
                    <textarea name="message" id="message" rows="4" 
                              placeholder="Please share your experience, suggestions, or any additional details..."
                              class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400"></textarea>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        <span id="char-count">0</span>/1000 characters
                    </div>
                    @error('message')
                        <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Privacy Notice -->
                <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg text-sm text-gray-600 dark:text-gray-300">
                    <p class="font-medium mb-2">📋 Privacy & Response Policy:</p>
                    <ul class="space-y-1 text-xs">
                        <li>• Your feedback is valuable and will be reviewed by our team</li>
                        <li>• We may respond to your feedback via email or app notifications</li>
                        <li>• Low ratings automatically trigger management review</li>
                        <li>• Anonymous feedback is welcome but limits our ability to follow up</li>
                    </ul>
                </div>

                <!-- Submit Button -->
                <div class="flex gap-4">
                    <button type="submit" id="submit-btn" disabled
                            class="flex-1 bg-blue-600 dark:bg-blue-700 text-white py-3 px-6 rounded-lg hover:bg-blue-700 dark:hover:bg-blue-800 disabled:bg-gray-400 dark:disabled:bg-gray-600 disabled:cursor-not-allowed transition-colors duration-200">
                        Submit Feedback
                    </button>
                    <a href="{{ url()->previous() }}" 
                       class="px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Recent Feedback (if user has any) -->
    @if(auth()->check())
        @php
            $recentFeedback = \App\Models\Feedback::where('user_id', auth()->id())
                ->whereNull('waste_report_id')
                ->latest()
                ->limit(3)
                ->get();
        @endphp
        
        @if($recentFeedback->count() > 0)
        <div class="mt-8 bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Your Recent Feedback</h3>
            <div class="space-y-3">
                @foreach($recentFeedback as $feedback)
                    <div class="border-l-4 border-blue-500 dark:border-blue-400 pl-4 py-2">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-yellow-400">{{ $feedback->getRatingStars() }}</span>
                                <span class="text-sm text-gray-600 dark:text-gray-400 ml-2">
                                    {{ ucfirst(str_replace('_', ' ', $feedback->feedback_type)) }}
                                </span>
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $feedback->created_at->diffForHumans() }}
                            </div>
                        </div>
                        @if($feedback->message)
                            <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">{{ $feedback->message }}</p>
                        @endif
                        @if($feedback->admin_response)
                            <div class="mt-2 p-2 bg-green-50 dark:bg-green-900/20 rounded text-sm">
                                <span class="text-green-700 dark:text-green-300 font-medium">Admin Response:</span>
                                <p class="text-green-700 dark:text-green-300">{{ $feedback->admin_response }}</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    @endif
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Star rating functionality
    const stars = document.querySelectorAll('.star');
    const ratingInput = document.getElementById('rating-input');
    const ratingText = document.getElementById('rating-text');
    const submitBtn = document.getElementById('submit-btn');
    const feedbackType = document.getElementById('feedback_type');
    
    const ratingLabels = {
        1: 'Very Poor',
        2: 'Poor', 
        3: 'Average',
        4: 'Good',
        5: 'Excellent'
    };
    
    // Check if form is valid
    function checkFormValid() {
        const hasRating = ratingInput.value;
        const hasType = feedbackType.value;
        submitBtn.disabled = !(hasRating && hasType);
    }
    
    stars.forEach((star, index) => {
        star.addEventListener('click', function() {
            const rating = parseInt(this.dataset.rating);
            ratingInput.value = rating;
            ratingText.textContent = ratingLabels[rating];
            
            // Update star colors
            stars.forEach((s, i) => {
                if (i < rating) {
                    s.classList.remove('text-gray-300', 'dark:text-gray-600');
                    s.classList.add('text-yellow-400');
                } else {
                    s.classList.remove('text-yellow-400');
                    s.classList.add('text-gray-300', 'dark:text-gray-600');
                }
            });
            
            checkFormValid();
        });
        
        // Hover effects
        star.addEventListener('mouseenter', function() {
            const rating = parseInt(this.dataset.rating);
            stars.forEach((s, i) => {
                if (i < rating) {
                    s.classList.add('text-yellow-300');
                }
            });
        });
        
        star.addEventListener('mouseleave', function() {
            stars.forEach(s => {
                s.classList.remove('text-yellow-300');
            });
        });
    });
    
    // Character counter
    const messageTextarea = document.getElementById('message');
    const charCount = document.getElementById('char-count');
    
    messageTextarea.addEventListener('input', function() {
        const count = this.value.length;
        charCount.textContent = count;
        
        if (count > 1000) {
            charCount.classList.add('text-red-500');
            this.value = this.value.substring(0, 1000);
            charCount.textContent = '1000';
        } else {
            charCount.classList.remove('text-red-500');
        }
    });
    
    // Check form validity on type change
    feedbackType.addEventListener('change', checkFormValid);
});
</script>
@endsection
