<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>2FA Verification - Smart Waste Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-green-50 min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Two-Factor Verification</h2>
            <p class="mt-1 text-sm text-gray-500">Enter the 6-digit code we sent to your email</p>
        </div>

        @if(session('error'))
            <div class="bg-red-100 text-red-700 font-medium px-4 py-2 rounded mb-4 text-center">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('2fa.verify.store') }}">
            @csrf
            <div class="mb-4">
                <label for="code" class="block text-sm font-medium text-gray-700">Verification Code</label>
                <input id="code" name="code" type="text" maxlength="6" required autofocus
                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-600 focus:border-green-600"
                    placeholder="">
            </div>

            <button type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-md transition">
                Verify & Login
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-4">
            Didn't get the code? Try logging in again or check your spam folder.
        </p>

        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-sm text-green-600 hover:underline">
                ← Back to Login
            </a>
        </div>
    </div>

</body>
</html>