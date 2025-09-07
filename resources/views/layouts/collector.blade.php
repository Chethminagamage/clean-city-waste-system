<!-- resources/views/layouts/collector.blade.php -->
<!DOCTYPE html>
<html lang="en" class="scroll-smooth {{ auth('collector')->check() && auth('collector')->user()->theme_preference === 'dark' ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Collector Dashboard - Clean City Waste Management</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">
    
    <!-- Theme Persistence Script - MUST BE FIRST -->
    <script>
        // Apply theme immediately to prevent flash
        (function() {
            // Get server-side theme preference
            const serverTheme = '{{ auth("collector")->check() ? (auth("collector")->user()->theme_preference ?? "light") : "light" }}';
            const isAuthenticated = {{ auth('collector')->check() ? 'true' : 'false' }};
            
            let currentTheme = 'light';
            let shouldApplyDark = false;
            
            if (isAuthenticated) {
                // For authenticated users, prioritize server theme (database) over localStorage
                currentTheme = serverTheme;
                
                // Update localStorage to match database
                localStorage.setItem('collector-theme', serverTheme);
                
                console.log('Collector authenticated - using database theme:', serverTheme);
            } else {
                // Use localStorage for guests
                const localTheme = localStorage.getItem('collector-theme');
                if (localTheme) {
                    currentTheme = localTheme;
                } else {
                    currentTheme = 'light';
                    localStorage.setItem('collector-theme', 'light');
                }
            }
            
            // Determine if dark mode should be applied
            if (currentTheme === 'dark') {
                shouldApplyDark = true;
            } else if (currentTheme === 'system') {
                shouldApplyDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            }
            
            // Apply theme class immediately to HTML element
            const htmlElement = document.documentElement;
            if (shouldApplyDark) {
                htmlElement.classList.add('dark');
            } else {
                htmlElement.classList.remove('dark');
            }
            
            // Add flags for theme manager
            window.collectorThemeInitialized = true;
            window.currentCollectorTheme = currentTheme;
            window.collectorThemeApplied = shouldApplyDark ? 'dark' : 'light';
            
            console.log('Collector theme initialized:', currentTheme, 'Applied:', shouldApplyDark ? 'dark' : 'light');
        })();
    </script>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="{{ asset('resources/css/collector.css') }}" rel="stylesheet">
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'orange-collector': '#f97316',
                        'orange-collector-dark': '#ea580c',
                        'orange-collector-light': '#fed7aa',
                    },
                }
            }
        }
    </script>
    <link rel="stylesheet" href="{{ asset('css/collector-dark-mode.css') }}">
    
    <style>
        /* Custom orange theme colors */
        :root {
            --orange-collector: #f97316;
            --orange-collector-dark: #ea580c;
            --orange-collector-light: #fed7aa;
        }

        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        /* Smooth transitions */
        * {
            transition: color 0.3s ease, background-color 0.3s ease, border-color 0.3s ease, transform 0.3s ease;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .dark ::-webkit-scrollbar-track {
            background: #374151;
        }

        ::-webkit-scrollbar-thumb {
            background: #f97316;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #ea580c;
        }

        /* Modal animations */
        .modal-enter {
            animation: modalEnter 0.3s ease-out;
        }

        @keyframes modalEnter {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
    
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-gray-50 via-white to-gray-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 text-gray-900 dark:text-gray-100 min-h-screen transition-colors duration-300">
    <div class="min-h-screen">
        @include('collector.partials.navbar')
        
        <main>
            @yield('content')
        </main>
        
        @include('collector.partials.floating-theme-picker')
    </div>

    <!-- Global Scripts -->
    <script src="{{ asset('js/collector-theme.js') }}"></script>
    <script>
        // Global utility functions
        window.showNotification = function(message, type = 'success') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-4 py-2 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
                type === 'success' ? 'bg-green-500 text-white' : 
                type === 'error' ? 'bg-red-500 text-white' : 
                'bg-orange-500 text-white'
            }`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 100);
            
            // Animate out and remove
            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        };
        
        // Handle CSRF token for AJAX requests
        window.axios = window.axios || {};
        window.axios.defaults = window.axios.defaults || {};
        window.axios.defaults.headers = window.axios.defaults.headers || {};
        window.axios.defaults.headers.common = window.axios.defaults.headers.common || {};
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Collector Theme Manager
        window.collectorThemeManager = {
            currentTheme: window.currentCollectorTheme || 'light',
            
            async toggleTheme() {
                try {
                    const response = await fetch('/theme/toggle', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        }
                    });

                    if (!response.ok) {
                        throw new Error('Failed to toggle theme');
                    }
                    
                    const result = await response.json();
                    if (result.success) {
                        this.currentTheme = result.theme;
                        this.applyTheme(result.theme);
                        console.log('Collector theme toggled successfully:', result);
                        
                        // Dispatch theme change event
                        window.dispatchEvent(new CustomEvent('collectorThemeChanged', {
                            detail: { theme: result.theme }
                        }));
                        
                        return result;
                    } else {
                        throw new Error(result.message || 'Failed to toggle theme');
                    }
                } catch (error) {
                    console.warn('Could not toggle theme on server, falling back to local toggle:', error);
                    // Fallback to local toggle
                    const newTheme = this.currentTheme === 'light' ? 'dark' : 'light';
                    this.currentTheme = newTheme;
                    this.applyTheme(newTheme);
                    localStorage.setItem('collector-theme', newTheme);
                    return { success: true, theme: newTheme };
                }
            },
            
            applyTheme(theme) {
                const htmlElement = document.documentElement;
                if (theme === 'dark') {
                    htmlElement.classList.add('dark');
                } else {
                    htmlElement.classList.remove('dark');
                }
                
                // Update meta theme color for mobile browsers
                let metaThemeColor = document.querySelector('meta[name="theme-color"]');
                if (!metaThemeColor) {
                    metaThemeColor = document.createElement('meta');
                    metaThemeColor.name = 'theme-color';
                    document.head.appendChild(metaThemeColor);
                }
                metaThemeColor.content = theme === 'dark' ? '#1f2937' : '#f97316';
            }
        };
    </script>
    
    <!-- Google Maps API -->
    <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_KEY', '') }}&libraries=places"></script>
</body>
</html>