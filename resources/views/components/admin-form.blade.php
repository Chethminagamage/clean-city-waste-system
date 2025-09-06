{{-- Reusable Form Component for Admin CRUD --}}
@props([
    'action',
    'method' => 'POST',
    'submitText' => 'Submit',
    'cancelText' => 'Cancel',
    'modalId' => null,
    'showCancel' => true
])

<form method="POST" action="{{ $action }}" class="space-y-5">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    <!-- Form Fields -->
    {{ $slot }}

    <!-- Form Actions -->
    <div class="flex justify-end pt-4 gap-3">
        @if($showCancel)
        <button type="button" 
                @if($modalId) onclick="closeModal('{{ $modalId }}')" @endif
                class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition">
            {{ $cancelText }}
        </button>
        @endif
        
        <button type="submit"
                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
            {{ $submitText }}
        </button>
    </div>
</form>
