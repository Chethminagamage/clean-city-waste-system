<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - Clean City</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100 font-sans">

    <div class="min-h-screen flex flex-col lg:flex-row">
        <!-- Left Side Image -->
        

        <!-- Right Side Form -->
        <div class="lg:w-1/2 w-full flex items-center justify-center p-8">
            <div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 100)"
                 x-show="loaded"
                 x-transition:enter="transition ease-out duration-700"
                 x-transition:enter-start="opacity-0 translate-y-5 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-8">

                <h2 class="text-3xl font-bold text-center text-green-700 mb-6">🔒 Reset Your Password</h2>

                <form method="POST" action="{{ route('password.store') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <!-- Email -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-1" for="email">Email Address</label>
                        <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500" />
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-1" for="password">New Password</label>
                        <input id="password" type="password" name="password" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500" />
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-medium mb-1" for="password_confirmation">Confirm Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500" />
                        @error('password_confirmation')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <button type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-lg transition duration-300 shadow-md">
                        ✅ Reset Password
                    </button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>