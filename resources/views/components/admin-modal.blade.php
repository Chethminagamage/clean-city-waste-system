{{-- Admin Modal Component for CRUD Operations --}}
@props([
    'id' => 'adminModal',
    'title' => 'Modal Title',
    'size' => 'md',
    'showCloseButton' => true
])

@php
$sizeClasses = [
    'sm' => 'max-w-sm',
    'md' => 'max-w-md',
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl',
    '2xl' => 'max-w-2xl',
];
$modalSize = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<div id="{{ $id }}" class="fixed inset-0 bg-black bg-opacity-40 z-50 flex items-center justify-center hidden">
    <div class="bg-white w-full {{ $modalSize }} rounded-2xl shadow-2xl p-8 relative transition-transform transform scale-100">
        
        @if($showCloseButton)
        <!-- Close button -->
        <button type="button" onclick="document.getElementById('{{ $id }}').classList.add('hidden')" 
                class="absolute top-4 right-4 text-gray-500 hover:text-red-600 text-2xl font-bold leading-none focus:outline-none">
            &times;
        </button>
        @endif

        <!-- Modal Header -->
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">{{ $title }}</h2>

        <!-- Modal Content -->
        <div class="modal-content">
            {{ $slot }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Helper functions for modal management
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }
    
    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }
    
    // Close modal on ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            document.querySelectorAll('.fixed.inset-0:not(.hidden)').forEach(modal => {
                modal.classList.add('hidden');
            });
        }
    });
    
    // Close modal on backdrop click
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('fixed') && event.target.classList.contains('inset-0')) {
            event.target.classList.add('hidden');
        }
    });
</script>
@endpush
