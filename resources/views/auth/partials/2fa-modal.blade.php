<div x-data="{ open: true }" x-show="open" class="fixed inset-0 flex items-center justify-center bg-black/40 backdrop-blur-sm z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-xl font-bold text-green-700 text-center mb-4">Two-Factor Authentication</h2>
        <form method="POST" action="{{ route('2fa.verify.store') }}">
            @csrf
            <input type="text" name="code" class="w-full border border-gray-300 px-4 py-2 rounded mb-4" placeholder="Enter 6-digit code" required>
            @error('code')
                <p class="text-sm text-red-500">{{ $message }}</p>
            @enderror
            <button type="submit" class="bg-green-600 text-white w-full py-2 rounded hover:bg-green-700 font-semibold">Verify</button>
        </form>
    </div>
</div>