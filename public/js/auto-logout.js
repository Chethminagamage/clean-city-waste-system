/**
 * Auto Logout and Activity Tracking
 * Tracks user activity and provides session timeout warnings
 */

class AutoLogoutManager {
    constructor(options = {}) {
        this.timeoutMinutes = options.timeoutMinutes || 25;
        this.warningMinutes = options.warningMinutes || 5;
        this.pingInterval = options.pingInterval || 60000; // 1 minute
        
        this.activityTimer = null;
        this.warningTimer = null;
        this.pingTimer = null;
        this.warningShown = false;
        
        this.init();
    }
    
    init() {
        this.bindActivityEvents();
        this.startActivityTimer();
        this.startPingTimer();
        this.createWarningModal();
    }
    
    /**
     * Bind activity tracking events
     */
    bindActivityEvents() {
        const events = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'];
        
        events.forEach(event => {
            document.addEventListener(event, () => {
                this.resetActivityTimer();
            }, { passive: true });
        });
    }
    
    /**
     * Reset the activity timer
     */
    resetActivityTimer() {
        if (this.warningShown) {
            this.hideWarning();
        }
        
        this.clearTimers();
        this.startActivityTimer();
    }
    
    /**
     * Start the activity monitoring timer
     */
    startActivityTimer() {
        const warningTime = (this.timeoutMinutes - this.warningMinutes) * 60 * 1000;
        const logoutTime = this.timeoutMinutes * 60 * 1000;
        
        // Set warning timer
        this.warningTimer = setTimeout(() => {
            this.showWarning();
        }, warningTime);
        
        // Set auto logout timer
        this.activityTimer = setTimeout(() => {
            this.performLogout();
        }, logoutTime);
    }
    
    /**
     * Start the activity ping timer
     */
    startPingTimer() {
        this.pingTimer = setInterval(() => {
            this.sendActivityPing();
        }, this.pingInterval);
    }
    
    /**
     * Clear all timers
     */
    clearTimers() {
        if (this.activityTimer) {
            clearTimeout(this.activityTimer);
        }
        if (this.warningTimer) {
            clearTimeout(this.warningTimer);
        }
    }
    
    /**
     * Send activity ping to server
     */
    async sendActivityPing() {
        try {
            const response = await fetch('/activity-ping', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify({
                    timestamp: new Date().toISOString()
                })
            });
            
            if (!response.ok) {
                console.warn('Activity ping failed:', response.status);
            }
        } catch (error) {
            console.warn('Activity ping error:', error);
        }
    }
    
    /**
     * Show timeout warning modal
     */
    showWarning() {
        this.warningShown = true;
        const modal = document.getElementById('timeout-warning-modal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            // Start countdown
            this.startCountdown();
        }
    }
    
    /**
     * Hide timeout warning modal
     */
    hideWarning() {
        this.warningShown = false;
        const modal = document.getElementById('timeout-warning-modal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }
    
    /**
     * Start countdown in warning modal
     */
    startCountdown() {
        let remainingSeconds = this.warningMinutes * 60;
        const countdownElement = document.getElementById('countdown-timer');
        
        const countdownInterval = setInterval(() => {
            remainingSeconds--;
            
            if (countdownElement) {
                const minutes = Math.floor(remainingSeconds / 60);
                const seconds = remainingSeconds % 60;
                countdownElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            }
            
            if (remainingSeconds <= 0 || !this.warningShown) {
                clearInterval(countdownInterval);
            }
        }, 1000);
    }
    
    /**
     * Extend session when user clicks "Stay Logged In"
     */
    extendSession() {
        this.hideWarning();
        this.resetActivityTimer();
        this.sendActivityPing();
    }
    
    /**
     * Perform logout
     */
    performLogout() {
        // Clear all timers
        this.clearTimers();
        if (this.pingTimer) {
            clearInterval(this.pingTimer);
        }
        
        // Redirect to appropriate login page
        window.location.href = this.getLogoutRedirectUrl();
    }
    
    /**
     * Get logout redirect URL based on current path
     */
    getLogoutRedirectUrl() {
        const path = window.location.pathname;
        
        if (path.startsWith('/admin')) {
            return '/admin/login?session_expired=1';
        } else if (path.startsWith('/collector')) {
            return '/collector/login?session_expired=1';
        } else if (path.startsWith('/resident')) {
            return '/login?session_expired=1';
        } else {
            return '/login?session_expired=1';
        }
    }
    
    /**
     * Create the timeout warning modal
     */
    createWarningModal() {
        // Check if modal already exists
        if (document.getElementById('timeout-warning-modal')) {
            return;
        }
        
        const modalHTML = `
            <div id="timeout-warning-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                <div class="bg-white rounded-lg p-6 max-w-md mx-4 shadow-xl">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-semibold text-gray-900">Session Timeout Warning</h3>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-gray-600 mb-2">
                            You will be automatically logged out due to inactivity in:
                        </p>
                        <div class="text-center">
                            <span id="countdown-timer" class="text-2xl font-bold text-red-600">5:00</span>
                        </div>
                    </div>
                    
                    <div class="flex space-x-3">
                        <button 
                            onclick="autoLogoutManager.extendSession()" 
                            class="flex-1 bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500"
                        >
                            Stay Logged In
                        </button>
                        <button 
                            onclick="autoLogoutManager.performLogout()" 
                            class="flex-1 bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500"
                        >
                            Logout Now
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modalHTML);
    }
    
    /**
     * Destroy the auto logout manager
     */
    destroy() {
        this.clearTimers();
        if (this.pingTimer) {
            clearInterval(this.pingTimer);
        }
        
        // Remove event listeners
        const events = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'];
        events.forEach(event => {
            document.removeEventListener(event, this.resetActivityTimer);
        });
        
        // Remove modal
        const modal = document.getElementById('timeout-warning-modal');
        if (modal) {
            modal.remove();
        }
    }
}

// Initialize auto logout manager when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize for authenticated pages
    if (document.querySelector('meta[name="csrf-token"]')) {
        window.autoLogoutManager = new AutoLogoutManager({
            timeoutMinutes: 25,
            warningMinutes: 5,
            pingInterval: 60000 // 1 minute
        });
    }
});

// Handle page unload
window.addEventListener('beforeunload', function() {
    if (window.autoLogoutManager) {
        window.autoLogoutManager.destroy();
    }
});