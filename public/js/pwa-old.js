/**
 * Clean City PWA Installation Manager
 * Handles PWA installation prompts and offline functionality
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
        
        // Setup event listeners
        this.setupEventListeners();
        
        // Show installation prompt if appropriate
        this.handleInstallationPrompt();
        
        // Setup offline/online handlers
        this.setupConnectivityHandlers();
        
        console.log('[CleanCityPWA] PWA Manager initialized');
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
                                this.showUpdateAvailablePrompt();
                            }
                        });
                    }
                });
                
                return registration;
            } catch (error) {
                console.error('[CleanCityPWA] Service Worker registration failed:', error);
            }
        }
    }

    setupEventListeners() {
        // PWA installation prompt
        window.addEventListener('beforeinstallprompt', (e) => {
            console.log('[CleanCityPWA] Install prompt triggered');
            e.preventDefault();
            this.deferredPrompt = e;
            
            // Show install elements immediately when prompt is available
            setTimeout(() => {
                this.createInstallButton();
                // Show banner if not dismissed recently
                if (!localStorage.getItem('pwa-install-dismissed')) {
                    this.handleInstallationPrompt();
                }
            }, 1000);
        });

        // App installed
        window.addEventListener('appinstalled', (e) => {
            console.log('[CleanCityPWA] App installed successfully');
            this.isInstalled = true;
            this.hideInstallButton();
            this.dismissInstallBanner();
            this.dismissInstallNotification();
            this.showInstallationSuccessMessage();
            
            // Clear dismissal flags since app is now installed
            localStorage.removeItem('pwa-install-dismissed');
            localStorage.removeItem('pwa-banner-dismissed');
        });

        // Handle install button click
        document.addEventListener('click', (e) => {
            if (e.target.matches('.pwa-install-btn, .pwa-install-btn *')) {
                e.preventDefault();
                this.triggerInstallPrompt();
            }
        });
        
        // Reset dismissal flags periodically (every 3 days)
        const lastReset = localStorage.getItem('pwa-reset-time');
        if (!lastReset || Date.now() - parseInt(lastReset) > 3 * 24 * 60 * 60 * 1000) {
            localStorage.removeItem('pwa-install-dismissed');
            localStorage.removeItem('pwa-banner-dismissed');
            localStorage.setItem('pwa-reset-time', Date.now().toString());
        }
    }

    setupConnectivityHandlers() {
        window.addEventListener('online', () => {
            this.isOnline = true;
            this.hideOfflineMessage();
            console.log('[CleanCityPWA] Back online');
        });

        window.addEventListener('offline', () => {
            this.isOnline = false;
            this.showOfflineMessage();
            console.log('[CleanCityPWA] Gone offline');
        });

        // Show offline message if already offline
        if (!this.isOnline) {
            this.showOfflineMessage();
        }
    }

    checkInstallationStatus() {
        // Check if running in standalone mode (installed)
        if (window.matchMedia('(display-mode: standalone)').matches || 
            window.navigator.standalone === true) {
            this.isInstalled = true;
            console.log('[CleanCityPWA] App is installed and running in standalone mode');
        }
    }

    handleInstallationPrompt() {
        // Don't show install prompt if already installed
        if (this.isInstalled) {
            return;
        }

        // Show install prompt after a shorter delay for better conversion
        setTimeout(() => {
            if (this.deferredPrompt && !this.isInstalled) {
                this.showInstallBanner();
            }
        }, 2000); // Reduced to 2 seconds
        
        // Also show a less intrusive notification after page load
        setTimeout(() => {
            if (this.deferredPrompt && !this.isInstalled && !localStorage.getItem('pwa-install-dismissed')) {
                this.showInstallNotification();
            }
        }, 5000); // Show notification after 5 seconds
        
        // Show install button immediately if prompt is available
        if (this.deferredPrompt) {
            setTimeout(() => {
                this.createInstallButton();
            }, 1000);
        }
    }

    showInstallButton() {
        // Add install button to navigation or create floating button
        this.createInstallButton();
    }

    hideInstallButton() {
        const installBtn = document.querySelector('.pwa-install-btn');
        if (installBtn) {
            installBtn.style.transform = 'translateX(200px)';
            setTimeout(() => {
                installBtn.remove();
            }, 300);
        }
    }

    createInstallButton() {
        // Check if button already exists or user dismissed it
        if (document.querySelector('.pwa-install-btn') || localStorage.getItem('pwa-install-dismissed')) {
            return;
        }

        const installButton = document.createElement('button');
        installButton.className = 'pwa-install-btn fixed bottom-4 right-4 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-5 py-3 rounded-full shadow-2xl font-bold text-sm transition-all duration-300 transform hover:scale-110 z-40 flex items-center animate-bounce';
        installButton.style.transform = 'translateX(200px)'; // Start off-screen
        installButton.innerHTML = `
            <i class="fas fa-download mr-2 animate-pulse"></i>
            📱 Install App
        `;
        
        // Add close button to the install button
        const closeBtn = document.createElement('button');
        closeBtn.className = 'ml-3 text-white hover:text-gray-200 transition-colors';
        closeBtn.innerHTML = '<i class="fas fa-times"></i>';
        closeBtn.onclick = (e) => {
            e.stopPropagation();
            this.hideInstallButton();
            localStorage.setItem('pwa-install-dismissed', 'true');
        };
        installButton.appendChild(closeBtn);
        
        document.body.appendChild(installButton);
        
        // Animate in with delay
        setTimeout(() => {
            installButton.style.transform = 'translateX(0)';
            // Remove bounce after animation
            setTimeout(() => {
                installButton.classList.remove('animate-bounce');
            }, 3000);
        }, 500);
    }

    showInstallBanner() {
        // Don't show if already dismissed recently
        const lastDismissed = localStorage.getItem('pwa-banner-dismissed');
        if (lastDismissed && Date.now() - parseInt(lastDismissed) < 24 * 60 * 60 * 1000) {
            return; // Don't show for 24 hours after dismissal
        }
        
        // Create install banner
        const banner = document.createElement('div');
        banner.id = 'pwa-install-banner';
        banner.className = 'fixed top-0 left-0 right-0 bg-gradient-to-r from-green-500 to-green-600 text-white p-4 shadow-xl z-50 transform -translate-y-full transition-all duration-500';
        banner.innerHTML = `
            <div class="container mx-auto flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-download text-green-500 text-xl"></i>
                    </div>
                    <div>
                        <p class="font-bold text-lg">📱 Install Clean City App</p>
                        <p class="text-sm opacity-90">⚡ Faster access • 📶 Work offline • 🔔 Get notifications</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <button onclick="cleanCityPWA.triggerInstallPrompt()" class="bg-white text-green-600 px-6 py-3 rounded-lg font-bold text-sm hover:bg-gray-100 transition-all duration-200 transform hover:scale-105 shadow-lg">
                        ⬇️ Install Now
                    </button>
                    <button onclick="cleanCityPWA.dismissInstallBanner(true)" class="text-white hover:bg-green-700 p-3 rounded-lg transition-colors">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(banner);
        
        // Animate in with bounce effect
        setTimeout(() => {
            banner.style.transform = 'translateY(0)';
        }, 100);
        
        // Auto-dismiss after 15 seconds (longer for better conversion)
        setTimeout(() => {
            this.dismissInstallBanner(false);
        }, 15000);
    }
    
    showInstallNotification() {
        // Create a toast-style notification
        const notification = document.createElement('div');
        notification.id = 'pwa-install-notification';
        notification.className = 'fixed bottom-4 right-4 bg-white border-2 border-green-500 rounded-xl shadow-2xl p-4 z-50 max-w-sm transform translate-x-full transition-all duration-500';
        notification.innerHTML = `
            <div class="flex items-start space-x-3">
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-mobile-alt text-green-600"></i>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-gray-800 text-sm">Install Clean City</h4>
                    <p class="text-xs text-gray-600 mt-1">Add to home screen for quick access</p>
                    <div class="flex space-x-2 mt-3">
                        <button onclick="cleanCityPWA.triggerInstallPrompt()" class="bg-green-500 text-white px-3 py-1 rounded text-xs font-semibold hover:bg-green-600 transition-colors">
                            Install
                        </button>
                        <button onclick="cleanCityPWA.dismissInstallNotification()" class="text-gray-500 hover:text-gray-700 px-3 py-1 rounded text-xs transition-colors">
                            Later
                        </button>
                    </div>
                </div>
                <button onclick="cleanCityPWA.dismissInstallNotification()" class="text-gray-400 hover:text-gray-600 p-1">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        // Auto-dismiss after 12 seconds
        setTimeout(() => {
            this.dismissInstallNotification();
        }, 12000);
    }
    
    dismissInstallNotification() {
        const notification = document.getElementById('pwa-install-notification');
        if (notification) {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }
    }

    dismissInstallBanner(remember = false) {
        const banner = document.getElementById('pwa-install-banner');
        if (banner) {
            banner.style.transform = 'translateY(-100%)';
            setTimeout(() => {
                banner.remove();
            }, 300);
        }
        
        if (remember) {
            // Remember dismissal for 24 hours
            localStorage.setItem('pwa-banner-dismissed', Date.now().toString());
            localStorage.setItem('pwa-install-dismissed', 'true');
        }
    }

    async triggerInstallPrompt() {
        if (!this.deferredPrompt) {
            console.log('[CleanCityPWA] Install prompt not available');
            
            // Show manual install instructions for iOS Safari
            if (this.isIOS()) {
                this.showIOSInstallInstructions();
            } else {
                this.showManualInstallInstructions();
            }
            return;
        }

        try {
            // Show the install prompt
            this.deferredPrompt.prompt();
            
            // Wait for the user to respond
            const { outcome } = await this.deferredPrompt.userChoice;
            
            console.log(`[CleanCityPWA] Install prompt outcome: ${outcome}`);
            
            if (outcome === 'accepted') {
                console.log('[CleanCityPWA] User accepted the install prompt');
                this.dismissInstallBanner(false);
                this.dismissInstallNotification();
                this.hideInstallButton();
            } else {
                console.log('[CleanCityPWA] User dismissed the install prompt');
                // Don't show again for a while if user rejected
                localStorage.setItem('pwa-install-dismissed', 'true');
                localStorage.setItem('pwa-banner-dismissed', Date.now().toString());
            }
            
            // Clear the deferred prompt
            this.deferredPrompt = null;
        } catch (error) {
            console.error('[CleanCityPWA] Install prompt error:', error);
        }
    }
    
    isIOS() {
        return /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
    }
    
    showIOSInstallInstructions() {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
        modal.innerHTML = `
            <div class="bg-white rounded-2xl p-6 max-w-sm w-full text-center">
                <div class="text-4xl mb-4">📱</div>
                <h3 class="font-bold text-xl mb-4">Install Clean City on iOS</h3>
                <div class="text-left space-y-3 text-sm">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-blue-600 font-bold">1</span>
                        </div>
                        <span>Tap the <i class="fas fa-share"></i> share button below</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-blue-600 font-bold">2</span>
                        </div>
                        <span>Select "Add to Home Screen" <i class="fas fa-plus-square"></i></span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-blue-600 font-bold">3</span>
                        </div>
                        <span>Tap "Add" to install Clean City</span>
                    </div>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="mt-6 bg-green-500 text-white px-6 py-3 rounded-lg font-semibold w-full">
                    Got it!
                </button>
            </div>
        `;
        document.body.appendChild(modal);
    }
    
    showManualInstallInstructions() {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
        modal.innerHTML = `
            <div class="bg-white rounded-2xl p-6 max-w-sm w-full text-center">
                <div class="text-4xl mb-4">💻</div>
                <h3 class="font-bold text-xl mb-4">Install Clean City</h3>
                <p class="text-gray-600 text-sm mb-4">
                    Look for the install icon <i class="fas fa-download"></i> in your browser's address bar, 
                    or check your browser menu for "Install" or "Add to Home Screen" option.
                </p>
                <button onclick="this.parentElement.parentElement.remove()" class="bg-green-500 text-white px-6 py-3 rounded-lg font-semibold w-full">
                    Got it!
                </button>
            </div>
        `;
        document.body.appendChild(modal);
    }

    showInstallationSuccessMessage() {
        const message = document.createElement('div');
        message.className = 'fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white border-4 border-green-500 rounded-2xl shadow-2xl p-8 z-50 text-center max-w-sm animate-pulse';
        message.innerHTML = `
            <div class="text-6xl mb-4">🎉</div>
            <div class="text-green-600 mb-4">
                <i class="fas fa-check-circle text-4xl"></i>
            </div>
            <h3 class="font-bold text-xl text-gray-800 mb-2">App Installed Successfully! 🚀</h3>
            <p class="text-gray-600 text-sm mb-4">
                Clean City is now available on your home screen for quick access!
            </p>
            <div class="bg-green-50 border border-green-200 rounded-lg p-3 text-sm text-green-800">
                <strong>✨ What's new:</strong><br>
                • ⚡ Faster loading<br>
                • 📱 Native app experience<br>
                • 📶 Works offline<br>
                • 🔔 Push notifications
            </div>
        `;
        
        // Add overlay
        const overlay = document.createElement('div');
        overlay.className = 'fixed inset-0 bg-black bg-opacity-50 z-40';
        document.body.appendChild(overlay);
        document.body.appendChild(message);
        
        // Auto-close after 6 seconds
        setTimeout(() => {
            message.style.transform = 'translate(-50%, -50%) scale(0)';
            overlay.style.opacity = '0';
            setTimeout(() => {
                message.remove();
                overlay.remove();
            }, 300);
        }, 6000);
        
        // Close on click
        overlay.onclick = () => {
            message.style.transform = 'translate(-50%, -50%) scale(0)';
            overlay.style.opacity = '0';
            setTimeout(() => {
                message.remove();
                overlay.remove();
            }, 300);
        };
    }

    showOfflineMessage() {
        // Remove existing offline message
        const existing = document.getElementById('offline-message');
        if (existing) {
            existing.remove();
        }

        const offlineMessage = document.createElement('div');
        offlineMessage.id = 'offline-message';
        offlineMessage.className = 'fixed top-0 left-0 right-0 bg-yellow-500 text-white p-3 text-center z-50 transform -translate-y-full transition-transform duration-300';
        offlineMessage.innerHTML = `
            <div class="flex items-center justify-center">
                <i class="fas fa-wifi-slash mr-2"></i>
                <span>You're offline. Some features may be limited.</span>
            </div>
        `;
        
        document.body.appendChild(offlineMessage);
        
        // Animate in
        setTimeout(() => {
            offlineMessage.style.transform = 'translateY(0)';
        }, 100);
    }

    hideOfflineMessage() {
        const offlineMessage = document.getElementById('offline-message');
        if (offlineMessage) {
            offlineMessage.style.transform = 'translateY(-100%)';
            setTimeout(() => {
                offlineMessage.remove();
            }, 300);
        }
    }

    showUpdateAvailablePrompt() {
        const updatePrompt = document.createElement('div');
        updatePrompt.className = 'fixed bottom-4 left-4 bg-blue-500 text-white p-4 rounded-lg shadow-lg z-50 max-w-sm';
        updatePrompt.innerHTML = `
            <div class="flex items-start">
                <i class="fas fa-sync-alt mr-3 mt-1"></i>
                <div>
                    <p class="font-semibold">Update Available</p>
                    <p class="text-sm opacity-90 mb-3">A new version of Clean City is ready.</p>
                    <div class="flex space-x-2">
                        <button onclick="cleanCityPWA.updateApp()" class="bg-white text-blue-500 px-3 py-1 rounded text-sm font-semibold hover:bg-gray-100 transition-colors">
                            Update
                        </button>
                        <button onclick="this.parentElement.parentElement.parentElement.parentElement.remove()" class="text-white hover:bg-blue-600 px-3 py-1 rounded text-sm transition-colors">
                            Later
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(updatePrompt);
    }

    updateApp() {
        if (this.serviceWorker && this.serviceWorker.waiting) {
            this.serviceWorker.waiting.postMessage({ type: 'SKIP_WAITING' });
        }
        window.location.reload();
    }

    // Utility methods for offline functionality
    async cacheImportantData(urls) {
        if (this.serviceWorker) {
            return new Promise((resolve, reject) => {
                const messageChannel = new MessageChannel();
                messageChannel.port1.onmessage = (event) => {
                    if (event.data.success) {
                        resolve();
                    } else {
                        reject(event.data.error);
                    }
                };
                
                this.serviceWorker.active.postMessage(
                    { type: 'CACHE_URLS', urls: urls },
                    [messageChannel.port2]
                );
            });
        }
    }

    getInstallationStatus() {
        return {
            isInstalled: this.isInstalled,
            isOnline: this.isOnline,
            hasServiceWorker: !!this.serviceWorker,
            canInstall: !!this.deferredPrompt
        };
    }
}

// Initialize PWA manager when DOM is ready
let cleanCityPWA;

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        cleanCityPWA = new CleanCityPWA();
    });
} else {
    cleanCityPWA = new CleanCityPWA();
}

// Export for global access
window.cleanCityPWA = cleanCityPWA;