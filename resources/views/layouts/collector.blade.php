<!-- resources/views/layouts/collector.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Collector Dashboard - Clean City Waste Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('resources/css/collector.css') }}" rel="stylesheet">
    
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
            border-radius: 10px;
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
<body class="bg-gradient-to-br from-gray-50 via-white to-gray-50 text-gray-900 min-h-screen">
    <div class="min-h-screen">
        @include('collector.partials.navbar')
        
        <main>
            @yield('content')
        </main>
    </div>

    <!-- Global Scripts -->
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
    </script>
</body>
</html>