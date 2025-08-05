<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Clean City - Admin Panel')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0fdf4',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d'
                        }
                    }
                }
            }
        }
    </script>

    {{-- Global Sidebar CSS --}}
    <style>
        /* Base sidebar styles */
        #sidebar {
            transition: all 0.2s ease-in-out;
        }

        /* Desktop expanded state */
        @media (min-width: 1024px) {
            .sidebar-expanded #sidebar {
                width: 16rem !important; /* w-64 */
            }
            
            .sidebar-expanded .lg\:sidebar-expanded\:block {
                display: block !important;
            }
            
            .sidebar-expanded .lg\:sidebar-expanded\:opacity-100 {
                opacity: 1 !important;
            }
            
            .sidebar-expanded .lg\:sidebar-expanded\:\!w-64 {
                width: 16rem !important;
            }
            
            .sidebar-expanded .sidebar-expanded\:rotate-180 {
                transform: rotate(180deg);
            }
            
            /* Default collapsed state */
            body:not(.sidebar-expanded) #sidebar {
                width: 5rem !important; /* w-20 */
            }
            
            body:not(.sidebar-expanded) .lg\:sidebar-expanded\:block {
                display: none !important;
            }
            
            body:not(.sidebar-expanded) .lg\:sidebar-expanded\:opacity-100 {
                opacity: 0 !important;
            }
        }

        /* 2xl breakpoint - always expanded */
        @media (min-width: 1536px) {
            #sidebar {
                width: 16rem !important; /* 2xl:!w-64 */
            }
            
            .2xl\:block {
                display: block !important;
            }
            
            .2xl\:opacity-100 {
                opacity: 1 !important;
            }
            
            .2xl\:\!w-64 {
                width: 16rem !important;
            }
            
            /* Hide toggle button on 2xl screens */
            .2xl\:hidden {
                display: none !important;
            }
        }

        /* Mobile styles */
        @media (max-width: 1023px) {
            #sidebar {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                height: 100vh !important;
                width: 16rem !important; /* w-64 */
                z-index: 50 !important;
                transform: translateX(-16rem); /* -translate-x-64 */
            }
            
            #sidebar:not(.-translate-x-64) {
                transform: translateX(0) !important;
            }
            
            #sidebar-backdrop {
                position: fixed;
                inset: 0;
                background-color: rgba(17, 24, 39, 0.3); /* bg-gray-900 bg-opacity-30 */
                z-index: 40;
                transition: opacity 0.2s ease;
            }
            
            #sidebar-backdrop.opacity-0 {
                opacity: 0;
            }
            
            #sidebar-backdrop.pointer-events-none {
                pointer-events: none;
            }
        }

        /* Smooth transitions for all interactive elements */
        .transition-all,
        .transition-opacity,
        .transition-transform {
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }

        .duration-200 {
            transition-duration: 200ms;
        }

        /* Ensure proper text visibility transitions */
        .lg\:opacity-0 {
            transition: opacity 0.2s ease;
        }

        @media (min-width: 1024px) {
            .lg\:opacity-0 {
                opacity: 0;
            }
        }

        /* Icon and text alignment improvements */
        .sidebar-icon-container {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 1.5rem;
            height: 1.5rem;
            flex-shrink: 0;
        }

        /* Scrollbar customization */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Hover effects */
        .hover\:text-green-200:hover {
            color: rgb(187 247 208);
        }

        .hover\:text-gray-300:hover {
            color: rgb(209 213 219);
        }

        /* Active/current page highlighting */
        .bg-white.bg-opacity-20 {
            background-color: rgba(255, 255, 255, 0.2);
        }

        /* Ensure proper layering */
        .z-40 {
            z-index: 40;
        }

        .z-50 {
            z-index: 50;
        }

        /* Additional utility styles */
        .btn-sm {
            @apply inline-flex items-center justify-center text-sm font-medium leading-5 rounded border focus:outline-none px-3 py-1.5;
        }

        .form-input {
            @apply block w-full text-sm placeholder-gray-400 bg-white border border-gray-300 appearance-none rounded-md py-2 px-3 focus:outline-none focus:ring-0;
        }

        /* Chart container improvements */
        canvas {
            max-height: 400px;
        }

        /* Mobile menu button improvements */
        @media (max-width: 1023px) {
            .mobile-menu-button {
                display: block !important;
            }
        }

        @media (min-width: 1024px) {
            .mobile-menu-button {
                display: none !important;
            }
        }

        /* Remove content area margin adjustments - let flexbox handle it */
        .main-content {
            transition: all 0.2s ease-in-out;
        }

        /* Ensure main content takes remaining space without extra margins */
        @media (min-width: 1024px) {
            .main-content {
                margin-left: 0 !important;
            }
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        {{-- Include Sidebar --}}
        @include('admin.layout.sidebar')
        
        {{-- Main content area --}}
        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden main-content">
            {{-- Include Top Bar --}}
            @include('admin.layout.topbar')
            
            {{-- Main content --}}
            <main class="flex-1">
                <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-7xl mx-auto">
                    {{-- Page Header --}}
                    @hasSection('page-title')
                        <div class="mb-8">
                            <h1 class="text-2xl md:text-3xl text-gray-800 font-bold">@yield('page-title')</h1>
                            @hasSection('page-description')
                                <p class="text-gray-600 mt-2">@yield('page-description')</p>
                            @endif
                        </div>
                    @endif

                    {{-- Success Alert --}}
                    @if(session('success'))
                        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('success') }}
                            <button onclick="this.parentElement.remove()" class="ml-auto text-green-600 hover:text-green-800">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif

                    {{-- Error Alert --}}
                    @if(session('error'))
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ session('error') }}
                            <button onclick="this.parentElement.remove()" class="ml-auto text-red-600 hover:text-red-800">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif

                    {{-- Validation Errors --}}
                    @if($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <strong>Please fix the following errors:</strong>
                            </div>
                            <ul class="list-disc list-inside mt-2 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button onclick="this.parentElement.remove()" class="absolute top-3 right-3 text-red-600 hover:text-red-800">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif

                    {{-- Page Content --}}
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    {{-- Global Sidebar Toggle Script --}}
    <script>
        // Global Sidebar Toggle Script
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize sidebar functionality
            initializeSidebar();
        });

        function initializeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const toggleButton = document.getElementById('sidebar-toggle');
            const backdrop = document.getElementById('sidebar-backdrop');
            const closeButton = document.getElementById('sidebar-close');
            
            // Check if required elements exist
            if (!sidebar) {
                console.warn('Sidebar element not found');
                return;
            } 

            // Initialize sidebar state
            const isExpanded = localStorage.getItem('sidebar-expanded') === 'true' || window.innerWidth >= 1536; // 2xl breakpoint
            
            if (isExpanded && window.innerWidth >= 1024) {
                document.body.classList.add('sidebar-expanded');
            }

            // Toggle button functionality
            if (toggleButton) {
                toggleButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    if (window.innerWidth >= 1024) {
                        // Desktop: expand/collapse
                        const isCurrentlyExpanded = document.body.classList.contains('sidebar-expanded');
                        
                        if (isCurrentlyExpanded) {
                            document.body.classList.remove('sidebar-expanded');
                            localStorage.setItem('sidebar-expanded', 'false');
                        } else {
                            document.body.classList.add('sidebar-expanded');
                            localStorage.setItem('sidebar-expanded', 'true');
                        }
                    } else {
                        // Mobile: show/hide
                        toggleMobileSidebar();
                    }
                });
            }

            // Close button functionality (mobile)
            if (closeButton) {
                closeButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    hideMobileSidebar();
                });
            }

            // Backdrop functionality (mobile)
            if (backdrop) {
                backdrop.addEventListener('click', function(e) {
                    if (e.target === backdrop) {
                        hideMobileSidebar();
                    }
                });
            }

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) {
                    // Desktop: reset mobile states
                    sidebar.classList.remove('-translate-x-64');
                    if (backdrop) {
                        backdrop.classList.add('opacity-0', 'pointer-events-none');
                    }
                    
                    // Apply saved desktop state
                    const savedState = localStorage.getItem('sidebar-expanded');
                    if (savedState === 'true' || (savedState === null && window.innerWidth >= 1536)) {
                        document.body.classList.add('sidebar-expanded');
                    }
                } else {
                    // Mobile: reset desktop states
                    document.body.classList.remove('sidebar-expanded');
                    hideMobileSidebar();
                }
            });

            // Mobile helper functions
            function toggleMobileSidebar() {
                const isHidden = sidebar.classList.contains('-translate-x-64');
                
                if (isHidden) {
                    showMobileSidebar();
                } else {
                    hideMobileSidebar();
                }
            }

            function showMobileSidebar() {
                sidebar.classList.remove('-translate-x-64');
                if (backdrop) {
                    backdrop.classList.remove('opacity-0', 'pointer-events-none');
                }
                document.body.style.overflow = 'hidden'; // Prevent scrolling
            }

            function hideMobileSidebar() {
                sidebar.classList.add('-translate-x-64');
                if (backdrop) {
                    backdrop.classList.add('opacity-0', 'pointer-events-none');
                }    
                document.body.style.overflow = ''; // Restore scrolling
            }

            // Handle escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && window.innerWidth < 1024) {
                    hideMobileSidebar();
                }
            });

            // Mobile menu button functionality (if exists in topbar)
            const mobileMenuButton = document.querySelector('[data-mobile-menu-toggle]');
            if (mobileMenuButton) {
                mobileMenuButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    showMobileSidebar();
                });
            }

            console.log('Sidebar initialized successfully');
        }

        // Global utility functions
        window.showAlert = function(message, type = 'success') {
            const alertContainer = document.createElement('div');
            const alertClass = type === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800';
            const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
            
            alertContainer.className = `mb-6 ${alertClass} border px-4 py-3 rounded-lg flex items-center`;
            alertContainer.innerHTML = `
                <i class="fas ${iconClass} mr-2"></i>
                ${message}
                <button onclick="this.parentElement.remove()" class="ml-auto ${type === 'success' ? 'text-green-600 hover:text-green-800' : 'text-red-600 hover:text-red-800'}">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            const mainContent = document.querySelector('main .max-w-7xl');
            if (mainContent) {
                mainContent.insertBefore(alertContainer, mainContent.firstChild);
                
                // Auto-remove after 5 seconds
                setTimeout(() => {
                    if (alertContainer.parentNode) {
                        alertContainer.remove();
                    }
                }, 5000);
            }
        };

        // Loading state utility
        window.showLoading = function(element) {
            if (element) {
                element.disabled = true;
                const originalText = element.innerHTML;
                element.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading...';
                element.dataset.originalText = originalText;
            }
        };

        window.hideLoading = function(element) {
            if (element && element.dataset.originalText) {
                element.disabled = false;
                element.innerHTML = element.dataset.originalText;
                delete element.dataset.originalText;
            }
        };
    </script>

    {{-- Additional page-specific scripts --}}
    @stack('scripts')
</body>
</html>