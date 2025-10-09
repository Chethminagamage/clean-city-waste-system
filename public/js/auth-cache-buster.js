/**
 * Auth Cache Buster - Prevents authentication UI caching issues
 */

// Add cache busting to authentication-related AJAX requests
(function() {
    'use strict';
    
    // Override fetch to add cache-busting headers for auth routes
    const originalFetch = window.fetch;
    window.fetch = function(...args) {
        let [resource, config] = args;
        
        // Check if this is an auth-related request
        const url = typeof resource === 'string' ? resource : resource.url;
        const isAuthRoute = url.includes('/login') || 
                           url.includes('/logout') || 
                           url.includes('/dashboard') ||
                           url.includes('/auth');
        
        if (isAuthRoute) {
            config = config || {};
            config.headers = config.headers || {};
            
            // Add cache-busting headers
            config.headers['Cache-Control'] = 'no-cache, no-store, must-revalidate';
            config.headers['Pragma'] = 'no-cache';
            config.headers['Expires'] = '0';
            
            // Add timestamp to prevent browser caching
            const separator = url.includes('?') ? '&' : '?';
            if (typeof resource === 'string') {
                resource = resource + separator + '_t=' + Date.now();
            } else {
                resource.url = resource.url + separator + '_t=' + Date.now();
            }
        }
        
        return originalFetch.apply(this, [resource, config]);
    };
    
    // Force refresh of authentication status on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Clear any cached authentication data in localStorage
        const authKeys = Object.keys(localStorage).filter(key => 
            key.includes('auth') || key.includes('login') || key.includes('user')
        );
        authKeys.forEach(key => localStorage.removeItem(key));
        
        // Add timestamp to current page to prevent back button cache issues
        if (window.history && window.history.replaceState) {
            const url = new URL(window.location);
            url.searchParams.set('_t', Date.now());
            window.history.replaceState(null, '', url.toString());
        }
    });
    
    // Handle browser back/forward button cache issues
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            // Page was loaded from cache, force reload for auth pages
            const isAuthPage = window.location.pathname.includes('/login') ||
                              window.location.pathname.includes('/dashboard') ||
                              window.location.pathname.includes('/admin') ||
                              window.location.pathname.includes('/collector');
            
            if (isAuthPage) {
                window.location.reload();
            }
        }
    });
})();