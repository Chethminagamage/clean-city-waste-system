<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="{{ session('theme', 'light') }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Clean City - Smart Waste Management System</title>
        <style>[x-cloak]{display:none !important}</style>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Tailwind CSS and Font Awesome -->
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        
        <!-- Theme CSS and JS -->
        <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
        <script src="{{ asset('js/theme.js') }}" defer></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-gray-50 to-green-50 min-h-screen">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>
        </div>
        @if(session('success'))
  <div class="mx-auto max-w-5xl mt-4 rounded-md bg-emerald-50 text-emerald-800 px-4 py-2">
    {{ session('success') }}
  </div>
@endif
@if(session('error'))
  <div class="mx-auto max-w-5xl mt-4 rounded-md bg-red-50 text-red-700 px-4 py-2">
    {{ session('error') }}
  </div>
@endif
    </body>
</html>
