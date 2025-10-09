/**
 * Clean City PWA Manager
 * Minimal implementation that relies on native browser install prompts
 */

class CleanCityPWA {
    constructor() {
        this.deferredPrompt = null;
        this.isInstalled = false;
        this.isOnline = navigator.onLine;
        this.serviceWorker = null;
        
        this.init();
    }

    async init() {
        // Check if already installed
        this.checkInstallationStatus();
        
        // Register service worker
        await this.registerServiceWorker();
        
        // Setup event listeners for browser's native install handling
        this.setupEventListeners();
        
        // Setup offline/online handlers
        this.setupConnectivityHandlers();
        
        console.log('[CleanCityPWA] PWA Manager initialized - relying on native browser prompts');
    }

    async registerServiceWorker() {
        if ('serviceWorker' in navigator) {
            try {
                const registration = await navigator.serviceWorker.register('/sw.js', {
                    scope: '/'
                });
                
                this.serviceWorker = registration;
                console.log('[CleanCityPWA] Service Worker registered:', registration.scope);
                
                // Handle service worker updates
                registration.addEventListener('updatefound', () => {
                    const newWorker = registration.installing;
                    if (newWorker) {
                        newWorker.addEventListener('statechange', () => {
                            if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                console.log('[CleanCityPWA] New content available, refresh to update');
                            }
                        });
                    }
                });
                
            } catch (error) {
                console.error('[CleanCityPWA] Service Worker registration failed:', error);
            }
        }
    }

    setupEventListeners() {
        // Listen for beforeinstallprompt event but DON'T interfere with it
        window.addEventListener('beforeinstallprompt', (e) => {
            console.log('[CleanCityPWA] Browser detected app is installable');
            
            // DON'T prevent the default behavior - let browser handle it naturally
            // Browser will show install prompt in address bar, menu, or as notification
            
            // Optionally store the event for manual triggering if needed
            this.deferredPrompt = e;
            
            // Log browser-specific install locations
            this.logInstallLocations();
        });

        // Listen for successful installation
        window.addEventListener('appinstalled', (e) => {
            console.log('[CleanCityPWA] App successfully installed via browser');
            this.isInstalled = true;
            this.deferredPrompt = null;
            
            // Track installation for analytics
            this.trackInstallation();
        });

        // Listen for display mode changes
        window.addEventListener('resize', () => {
            this.checkInstallationStatus();
        });
    }

    checkInstallationStatus() {
        const wasInstalled = this.isInstalled;
        
        // Check if running as installed PWA
        if (window.matchMedia('(display-mode: standalone)').matches) {
            this.isInstalled = true;
        }
        // Check if installed via navigator (iOS Safari)
        else if (navigator.standalone === true) {
            this.isInstalled = true;
        }
        else {
            this.isInstalled = false;
        }
        
        // Log status changes
        if (wasInstalled !== this.isInstalled) {
            console.log(`[CleanCityPWA] Installation status changed: ${this.isInstalled ? 'installed' : 'not installed'}`);
        }
    }

    logInstallLocations() {
        const userAgent = navigator.userAgent;
        let installLocation = '';
        
        if (userAgent.includes('Chrome')) {
            installLocation = 'Look for install icon (⊕) in address bar or Chrome menu → Install Clean City';
        } else if (userAgent.includes('Firefox')) {
            installLocation = 'Look for install icon in address bar or Firefox menu → Install This Site as an App';
        } else if (userAgent.includes('Safari')) {
            installLocation = 'Safari: Share button → Add to Home Screen';
        } else if (userAgent.includes('Edge')) {
            installLocation = 'Look for install icon in address bar or Edge menu → Apps → Install Clean City';
        } else {
            installLocation = 'Check browser address bar or menu for install option';
        }
        
        console.log(`[CleanCityPWA] Install location: ${installLocation}`);
    }

    setupConnectivityHandlers() {
        window.addEventListener('online', () => {
            this.isOnline = true;
            console.log('[CleanCityPWA] Back online');
            this.handleOnline();
        });

        window.addEventListener('offline', () => {
            this.isOnline = false;
            console.log('[CleanCityPWA] Gone offline - cached content available');
            this.handleOffline();
        });
    }

    handleOnline() {
        // Remove offline indicator if present
        const offlineIndicator = document.querySelector('.offline-indicator');
        if (offlineIndicator) {
            offlineIndicator.remove();
        }
        
        // Sync any pending data if service worker supports it
        if (this.serviceWorker && 'sync' in window.ServiceWorkerRegistration.prototype) {
            this.serviceWorker.sync.register('background-sync').catch(err => {
                console.log('[CleanCityPWA] Background sync registration failed:', err);
            });
        }
    }

    handleOffline() {
        // Show minimal offline indicator
        if (!document.querySelector('.offline-indicator')) {
            const indicator = document.createElement('div');
            indicator.className = 'offline-indicator';
            indicator.innerHTML = '📶 Offline - using cached content';
            indicator.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                background: #f59e0b;
                color: white;
                text-align: center;
                padding: 8px;
                font-size: 14px;
                z-index: 9999;
                font-family: system-ui, -apple-system, sans-serif;
            `;
            document.body.appendChild(indicator);
        }
    }

    trackInstallation() {
        // Track installation for analytics
        if (typeof gtag !== 'undefined') {
            gtag('event', 'pwa_install', {
                event_category: 'PWA',
                event_label: 'App Installed via Browser'
            });
        }
        
        // Also track via fetch if you have an analytics endpoint
        if (this.isOnline) {
            fetch('/api/analytics/pwa-install', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    event: 'pwa_install',
                    timestamp: new Date().toISOString(),
                    userAgent: navigator.userAgent
                })
            }).catch(err => console.log('[CleanCityPWA] Analytics tracking failed:', err));
        }
    }

    // Utility methods for getting PWA status
    getStatus() {
        return {
            isInstalled: this.isInstalled,
            isOnline: this.isOnline,
            hasServiceWorker: !!this.serviceWorker,
            isInstallable: !!this.deferredPrompt,
            displayMode: this.getDisplayMode()
        };
    }

    getDisplayMode() {
        if (window.matchMedia('(display-mode: standalone)').matches) return 'standalone';
        if (window.matchMedia('(display-mode: minimal-ui)').matches) return 'minimal-ui';
        if (window.matchMedia('(display-mode: fullscreen)').matches) return 'fullscreen';
        return 'browser';
    }

    // Optional manual install trigger (only use if absolutely necessary)
    manualInstall() {
        if (this.deferredPrompt) {
            this.deferredPrompt.prompt();
            
            this.deferredPrompt.userChoice.then((choiceResult) => {
                if (choiceResult.outcome === 'accepted') {
                    console.log('[CleanCityPWA] User accepted manual install prompt');
                } else {
                    console.log('[CleanCityPWA] User dismissed manual install prompt');
                }
                this.deferredPrompt = null;
            });
        } else {
            console.log('[CleanCityPWA] No manual install prompt available - check browser UI for install options');
        }
    }
}

// Initialize PWA Manager when DOM is loaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.cleanCityPWA = new CleanCityPWA();
    });
} else {
    window.cleanCityPWA = new CleanCityPWA();
}

// Legacy support
window.pwaManager = {
    promptInstall: () => window.cleanCityPWA?.manualInstall(),
    getStatus: () => window.cleanCityPWA?.getStatus()
};