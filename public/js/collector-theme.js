// Collector Theme Management System
class CollectorThemeManager {
    constructor() {
        this.isInitialized = false;
        this.debug = false;
    }

    init() {
        if (this.isInitialized) return;
        
        this.log('Initializing Collector Theme Manager...');
        this.setupEventListeners();
        this.applyCurrentTheme();
        this.isInitialized = true;
        this.log('Collector Theme Manager initialized successfully');
    }

    async applyCurrentTheme() {
        // Check if theme was already set by inline script
        let theme;
        if (window.collectorThemeInitialized && window.currentCollectorTheme) {
            theme = window.currentCollectorTheme;
            this.log('Using theme from inline initialization:', theme);
        } else {
            theme = await this.getCurrentTheme();
        }
        
        const isDark = this.shouldApplyDarkMode(theme);
        
        // Apply theme immediately
        if (isDark) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        
        // Update icons when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                this.updateThemeIcons(isDark ? 'dark' : 'light');
            });
        } else {
            this.updateThemeIcons(isDark ? 'dark' : 'light');
        }
        
        this.log(`Applied theme: ${theme} (isDark: ${isDark})`);
    }

    async getCurrentTheme() {
        // Check if theme was already set by inline script
        if (window.collectorThemeInitialized && window.currentCollectorTheme) {
            this.log(`Using theme from inline initialization: ${window.currentCollectorTheme}`);
            return window.currentCollectorTheme;
        }
        
        try {
            // Try to get from server first for authenticated users
            const response = await fetch('/theme/current', {
                method: 'GET',
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json'
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                // Store in localStorage for faster future access
                localStorage.setItem('collector-theme', data.theme);
                this.log(`Theme fetched from server: ${data.theme}`);
                return data.theme;
            }
        } catch (error) {
            this.log('Failed to fetch theme from server:', error);
        }
        
        // Fallback to localStorage
        const localTheme = localStorage.getItem('collector-theme') || 'light';
        this.log(`Using local theme: ${localTheme}`);
        return localTheme;
    }

    shouldApplyDarkMode(theme) {
        if (theme === 'dark') return true;
        return false;
    }

    async toggleTheme() {
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            const response = await fetch('/theme/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            });

            if (response.ok) {
                const data = await response.json();
                
                if (data.success) {
                    // Apply new theme immediately
                    const isDark = this.shouldApplyDarkMode(data.theme);
                    
                    if (isDark) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                    
                    // Update localStorage
                    localStorage.setItem('collector-theme', data.theme);
                    
                    // Update icons
                    this.updateThemeIcons(isDark ? 'dark' : 'light');
                    
                    this.log(`Theme toggled to: ${data.theme}`);
                    
                    // Dispatch custom event for other components
                    window.dispatchEvent(new CustomEvent('collectorThemeChanged', { 
                        detail: { theme: data.theme, isDark } 
                    }));
                    
                    return data.theme;
                }
            } else {
                throw new Error('Server responded with error');
            }
        } catch (error) {
            this.log('Theme toggle failed, using localStorage fallback:', error);
            
            // Fallback to localStorage toggle
            const currentTheme = localStorage.getItem('collector-theme') || 'light';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            localStorage.setItem('collector-theme', newTheme);
            
            // Update inline script variables
            window.currentCollectorTheme = newTheme;
            window.collectorThemeApplied = newTheme;
            
            if (newTheme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            
            this.updateThemeIcons(newTheme);
            this.log(`Theme toggled locally to: ${newTheme}`);
            return newTheme;
        }
    }

    async setTheme(theme) {
        this.log(`Setting theme to: ${theme}`);
        
        // Apply theme immediately
        const isDark = this.shouldApplyDarkMode(theme);
        if (isDark) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        
        // Update localStorage
        localStorage.setItem('collector-theme', theme);
        
        // Update inline script variables for consistency
        window.currentCollectorTheme = theme;
        window.collectorThemeApplied = isDark ? 'dark' : 'light';
        
        // Update icons
        this.updateThemeIcons(isDark ? 'dark' : 'light');
        
        // Save to server
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const response = await fetch('/theme/set', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ theme }),
                credentials: 'same-origin'
            });

            if (!response.ok) {
                throw new Error('Failed to save theme to server');
            }
            
            this.log(`Theme successfully saved to server: ${theme}`);
        } catch (error) {
            this.log('Failed to save theme to server:', error);
        }
        
        // Dispatch custom event for other components
        window.dispatchEvent(new CustomEvent('collectorThemeChanged', { 
            detail: { theme, isDark } 
        }));
        
        return theme;
    }

    updateThemeIcons(theme) {
        // Update desktop theme toggle
        const themeIcon = document.getElementById('collector-theme-icon');
        const themeToggle = document.getElementById('collector-theme-toggle');
        
        // Update mobile theme toggle
        const mobileThemeIcon = document.getElementById('mobile-collector-theme-icon');
        const mobileThemeText = document.getElementById('mobile-collector-theme-text');
        
        if (theme === 'dark') {
            // Show sun icon for switching to light mode
            if (themeIcon) {
                themeIcon.className = 'fas fa-sun text-lg';
            }
            if (mobileThemeIcon) {
                mobileThemeIcon.className = 'fas fa-sun mr-2';
            }
            if (mobileThemeText) {
                mobileThemeText.textContent = 'Light Mode';
            }
            if (themeToggle) {
                themeToggle.title = 'Switch to Light Mode';
            }
        } else {
            // Show moon icon for switching to dark mode
            if (themeIcon) {
                themeIcon.className = 'fas fa-moon text-lg';
            }
            if (mobileThemeIcon) {
                mobileThemeIcon.className = 'fas fa-moon mr-2';
            }
            if (mobileThemeText) {
                mobileThemeText.textContent = 'Dark Mode';
            }
            if (themeToggle) {
                themeToggle.title = 'Switch to Dark Mode';
            }
        }
        
        this.log(`Updated theme icons for: ${theme}`);
    }

    setupEventListeners() {
        // Theme toggle button
        const themeToggle = document.getElementById('collector-theme-toggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', (e) => {
                e.preventDefault();
                this.toggleTheme();
            });
        }

        // Mobile theme toggle
        const mobileThemeToggle = document.getElementById('mobile-collector-theme-toggle');
        if (mobileThemeToggle) {
            mobileThemeToggle.addEventListener('click', (e) => {
                e.preventDefault();
                this.toggleTheme();
            });
        }

        this.log('Event listeners setup complete');
    }

    async syncWithServer() {
        try {
            const serverTheme = await this.getCurrentTheme();
            const localTheme = localStorage.getItem('collector-theme');
            
            // If there's a mismatch, use server theme
            if (serverTheme !== localTheme) {
                localStorage.setItem('collector-theme', serverTheme);
                
                const isDark = this.shouldApplyDarkMode(serverTheme);
                if (isDark) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
                
                this.updateThemeIcons(isDark ? 'dark' : 'light');
                this.log(`Synced theme with server: ${serverTheme}`);
            }
        } catch (error) {
            this.log('Theme sync failed:', error);
        }
    }

    log(...args) {
    }

    async setTheme(theme) {
        this.log(`Setting theme to: ${theme}`);
        
        // Apply theme immediately
        const isDark = this.shouldApplyDarkMode(theme);
        if (isDark) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        
        // Update localStorage
        localStorage.setItem('collector-theme', theme);
        
        // Update icons
        this.updateThemeIcons(isDark ? 'dark' : 'light');
        
        // Save to server
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const response = await fetch('/theme/set', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ theme }),
                credentials: 'same-origin'
            });

            if (!response.ok) {
                throw new Error('Failed to save theme to server');
            }
        } catch (error) {
            this.log('Failed to save theme to server:', error);
        }
        
        // Dispatch custom event for other components
        window.dispatchEvent(new CustomEvent('collectorThemeChanged', { 
            detail: { theme, isDark } 
        }));
        
        return theme;
    }

    log(...args) {
        if (this.debug) {
            console.log('[CollectorThemeManager]', ...args);
        }
    }
}

// Initialize immediately for faster theme application
(function() {
    // Create manager instance immediately
    if (!window.collectorThemeManager) {
        window.collectorThemeManager = new CollectorThemeManager();
        
        // Initialize immediately if DOM is ready, otherwise wait
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                window.collectorThemeManager.init();
            });
        } else {
            window.collectorThemeManager.init();
        }
    }
})();

// Global functions for floating theme picker
window.selectCollectorTheme = function(theme) {
    if (window.collectorThemeManager) {
        window.collectorThemeManager.setTheme(theme);
    }
};

window.closeCollectorThemeOptions = function() {
    const themeOptions = document.querySelector('.collector-theme-options');
    if (themeOptions) {
        themeOptions.style.display = 'none';
    }
};

// Export for use in other scripts
window.CollectorThemeManager = CollectorThemeManager;

// Global theme toggle function for compatibility
window.toggleCollectorTheme = function() {
    if (window.collectorThemeManager) {
        return window.collectorThemeManager.toggleTheme();
    }
};
