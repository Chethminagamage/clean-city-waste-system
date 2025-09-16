<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth {{ auth()->check() && auth()->user()->theme_preference === 'dark' ? 'dark' : '' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Clean city - Smart Waste Management System</title>
        
        <!-- Favicon -->
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/logo.png') }}">
        <link rel="shortcut icon" href="{{ asset('images/logo.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">
        
        <style>[x-cloak]{display:none !important}</style>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Theme Persistence Script - MUST BE FIRST -->
        <script>
            // Apply theme immediately to prevent flash
            (function() {
                // Get server-side theme preference
                const serverTheme = '{{ auth()->check() ? (auth()->user()->theme_preference ?? "light") : "light" }}';
                const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
                
                let shouldApplyDark = false;
                
                if (isAuthenticated) {
                    // Use server theme for authenticated users
                    if (serverTheme === 'dark') {
                        shouldApplyDark = true;
                    }
                } else {
                    // Use localStorage for guests
                    const localTheme = localStorage.getItem('theme');
                    if (localTheme === 'dark') {
                        shouldApplyDark = true;
                    }
                }
                
                // Apply theme class immediately to HTML element
                const htmlElement = document.documentElement;
                if (shouldApplyDark) {
                    htmlElement.classList.add('dark');
                } else {
                    htmlElement.classList.remove('dark');
                }
                
                // Store in localStorage for consistency
                localStorage.setItem('theme', serverTheme);
                
                // Add a flag to indicate theme was initialized
                window.themeInitialized = true;
                window.currentTheme = shouldApplyDark ? 'dark' : 'light';
            })();
        </script>
        
        <!-- Tailwind CSS and Font Awesome -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        colors: {
                            primary: {
                                50: '#f0fdf4',
                                500: '#10b981',
                                600: '#059669',
                                700: '#047857',
                                800: '#065f46',
                                900: '#064e3b',
                            }
                        }
                    }
                }
            }
        </script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Theme Management Script -->
        <script src="{{ asset('js/theme.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-gray-50 to-green-50 dark:from-gray-900 dark:to-gray-800 min-h-screen transition-colors duration-300">
        <div class="min-h-screen dark:bg-gray-900 transition-colors duration-300">
            @include('layouts.navigation')

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>
        </div>
        @if(session('success'))
  <div class="mx-auto max-w-5xl mt-4 rounded-md bg-emerald-50 dark:bg-emerald-900/20 text-emerald-800 dark:text-emerald-200 px-4 py-2 border border-emerald-200 dark:border-emerald-700">
    {{ session('success') }}
  </div>
@endif
@if(session('error'))
  <div class="mx-auto max-w-5xl mt-4 rounded-md bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-200 px-4 py-2 border border-red-200 dark:border-red-700">
    {{ session('error') }}
  </div>
@endif
    
    @include('partials.chat-widget')
    @include('partials.floating-theme-picker')
    
    <!-- Theme Management Script -->
    <script src="{{ asset('js/theme.js') }}"></script>
    </body>
</html>
