{{-- Auto-logout JavaScript and Modal --}}
<script src="{{ asset('js/auto-logout.js') }}"></script>

{{-- CSRF token meta tag for AJAX requests --}}
@if(!isset($csrfTokenSet))
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php($csrfTokenSet = true)
@endif

<script>
    // Configuration for auto-logout
    window.autoLogoutConfig = {
        timeoutMinutes: {{ \App\Services\AutoLogoutService::TIMEOUT_MINUTES }}, // Use auto-logout service timeout
        warningMinutes: 5, // Show warning 5 minutes before timeout
        pingInterval: 60000, // 1 minute
        routes: {
            ping: '{{ route('activity.ping') }}',
            status: '{{ route('activity.status') }}',
            extend: '{{ route('activity.extend') }}'
        }
    };
    
    // Handle session expired responses globally
    document.addEventListener('DOMContentLoaded', function() {
        // Intercept fetch responses for session timeout
        const originalFetch = window.fetch;
        window.fetch = function(...args) {
            return originalFetch.apply(this, args).then(response => {
                if (response.status === 401) {
                    response.clone().json().then(data => {
                        if (data.logout_reason === 'inactivity_timeout' && data.redirect) {
                            window.location.href = data.redirect;
                        }
                    }).catch(() => {
                        // If not JSON or parsing fails, redirect to login based on current path
                        const path = window.location.pathname;
                        if (path.startsWith('/admin')) {
                            window.location.href = '/admin/login?session_expired=1';
                        } else if (path.startsWith('/collector')) {
                            window.location.href = '/collector/login?session_expired=1';
                        } else if (path.startsWith('/resident')) {
                            window.location.href = '/login?session_expired=1';
                        } else {
                            window.location.href = '/login?session_expired=1';
                        }
                    });
                }
                return response;
            });
        };
        
        // Intercept XMLHttpRequest responses
        const originalSend = XMLHttpRequest.prototype.send;
        XMLHttpRequest.prototype.send = function(...args) {
            this.addEventListener('load', function() {
                if (this.status === 401) {
                    try {
                        const data = JSON.parse(this.responseText);
                        if (data.logout_reason === 'inactivity_timeout' && data.redirect) {
                            window.location.href = data.redirect;
                        }
                    } catch (e) {
                        // Fallback redirect logic
                        const path = window.location.pathname;
                        if (path.startsWith('/admin')) {
                            window.location.href = '/admin/login?session_expired=1';
                        } else if (path.startsWith('/collector')) {
                            window.location.href = '/collector/login?session_expired=1';
                        } else if (path.startsWith('/resident')) {
                            window.location.href = '/login?session_expired=1';
                        } else {
                            window.location.href = '/login?session_expired=1';
                        }
                    }
                }
            });
            return originalSend.apply(this, args);
        };
    });
</script>

{{-- Custom styling for the auto-logout modal --}}
<style>
    #timeout-warning-modal {
        backdrop-filter: blur(4px);
        animation: modalFadeIn 0.3s ease-out;
    }
    
    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    #timeout-warning-modal .bg-white {
        animation: modalSlideIn 0.3s ease-out;
    }
    
    @keyframes modalSlideIn {
        from {
            transform: translateY(-20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    #countdown-timer {
        font-family: 'Courier New', monospace;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
    }
</style>