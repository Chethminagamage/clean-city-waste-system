@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-green-50 py-12">
    <div class="max-w-2xl mx-auto px-4">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-br from-emerald-400 to-green-500 rounded-full mx-auto mb-4 flex items-center justify-center shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.915a1 1 0 00.95-.69l1.519-4.674z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Rate Your Experience</h1>
            <p class="text-gray-600">Help us improve our waste collection service</p>
        </div>

        <!-- Feedback Form Card -->
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-emerald-500 to-green-600 px-8 py-6">
                <div class="flex items-center justify-between text-white">
                    <div>
                        <h2 class="text-xl font-semibold">Report #{{ $report->reference_code }}</h2>
                        <p class="text-emerald-100 text-sm">{{ $report->location }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-emerald-100">Completed on</p>
                        <p class="font-medium">{{ $report->closed_at ? $report->closed_at->format('M d, Y') : 'Recently' }}</p>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="p-8">
                <form action="{{ route('feedback.report.store', $report->id) }}" method="POST" class="space-y-8">
                    @csrf
                    
                    <!-- Rating Section -->
                    <div class="text-center">
                        <label class="block text-lg font-semibold text-gray-800 mb-6">How would you rate our service?</label>
                        <div class="flex justify-center space-x-2 mb-4" x-data="{ rating: 0, hover: 0 }">
                            @for ($i = 1; $i <= 5; $i++)
                                <div class="relative">
                                    <input type="radio" 
                                           id="star{{ $i }}" 
                                           name="rating" 
                                           value="{{ $i }}" 
                                           class="hidden peer" 
                                           required
                                           @click="rating = {{ $i }}">
                                    <label for="star{{ $i }}" 
                                           class="cursor-pointer text-4xl transition-all duration-200 peer-checked:text-yellow-400 hover:text-yellow-300"
                                           :class="(hover >= {{ $i }} || rating >= {{ $i }}) ? 'text-yellow-400 transform scale-110' : 'text-gray-300'"
                                           @mouseenter="hover = {{ $i }}"
                                           @mouseleave="hover = 0">
                                        ★
                                    </label>
                                </div>
                            @endfor
                        </div>
                        
                        <div class="flex justify-center space-x-4 text-sm text-gray-600 mb-6">
                            <span>Poor</span>
                            <span>Fair</span>
                            <span>Good</span>
                            <span>Very Good</span>
                            <span>Excellent</span>
                        </div>
                        
                        @error('rating')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Comment Section -->
                    <div>
                        <label for="message" class="block text-lg font-semibold text-gray-800 mb-4">
                            Share your thoughts (optional)
                        </label>
                        <div class="relative">
                            <textarea id="message" 
                                      name="message" 
                                      rows="4" 
                                      placeholder="Tell us about your experience with the waste collection service..."
                                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-emerald-100 focus:border-emerald-400 transition-all duration-200 resize-none"></textarea>
                            <div class="absolute bottom-3 right-3 text-xs text-gray-400">
                                <span id="charCount">0</span>/500
                            </div>
                        </div>
                        @error('message')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                        <a href="{{ route('resident.reports.index') }}" 
                           class="flex items-center text-gray-600 hover:text-gray-800 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Skip for now
                        </a>
                        
                        <button type="submit" 
                                class="bg-gradient-to-r from-emerald-500 to-green-600 text-white px-8 py-3 rounded-2xl font-semibold hover:from-emerald-600 hover:to-green-700 focus:outline-none focus:ring-4 focus:ring-emerald-200 transition-all duration-200 shadow-lg hover:shadow-emerald-200 hover:transform hover:scale-105">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Submit Feedback
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Motivational Message -->
        <div class="mt-8 text-center">
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-lg">
                <svg class="w-12 h-12 text-emerald-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Thank you for using Clean City!</h3>
                <p class="text-gray-600">Your feedback helps us build a cleaner, more sustainable community for everyone.</p>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('message');
    const charCount = document.getElementById('charCount');
    
    if (textarea && charCount) {
        textarea.addEventListener('input', function() {
            charCount.textContent = this.value.length;
            if (this.value.length > 500) {
                charCount.classList.add('text-red-500');
            } else {
                charCount.classList.remove('text-red-500');
            }
        });
    }
});
</script>

<style>
.card-hover {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.card-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.star-rating input[type="radio"]:checked + label {
    color: #fbbf24;
    transform: scale(1.1);
}

.animate-fade-in {
    animation: fadeIn 0.6s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
@endsection
