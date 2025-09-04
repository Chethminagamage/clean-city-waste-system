// Theme Management System
class ThemeManager {
    constructor() {
        this.init();
    }

    init() {
        // Check if theme was already initialized by the inline script
        if (!window.themeInitialized) {
            // Apply theme immediately on page load if not already done
            this.applyCurrentTheme();
        } else {
            // Just sync the icons if theme was already applied
            const currentTheme = window.currentTheme || 'light';
            this.updateThemeIcons(currentTheme);
        }
        
        // Setup event listeners
        this.setupEventListeners();
        
        // Sync with server on page load (but don't change applied theme)
        this.syncWithServer();
        
        // Listen for system theme changes
        this.setupSystemThemeListener();
    }

    async applyCurrentTheme() {
        const theme = await this.getCurrentTheme();
        const isDark = this.shouldApplyDarkMode(theme);
        
        // Apply theme immediately
        if (isDark) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        
        // Update icons
        this.updateThemeIcons(isDark ? 'dark' : 'light');
    }

    async getCurrentTheme() {
        try {
            // Try to get from server first for authenticated users
            const response = await fetch('/theme/current', {
                method: 'GET',
                credentials: 'same-origin'
            });
            
            if (response.ok) {
                const data = await response.json();
                // Store in localStorage for faster future access
                localStorage.setItem('theme', data.theme);
                return data.theme;
            }
        } catch (error) {
            console.warn('Failed to fetch theme from server:', error);
        }
        
        // Fallback to localStorage
        return localStorage.getItem('theme') || 'light';
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
                    localStorage.setItem('theme', data.theme);
                    
                    // Update icons
                    this.updateThemeIcons(isDark ? 'dark' : 'light');
                    
                    return data.theme;
                }
            } else {
                throw new Error('Server responded with error');
            }
        } catch (error) {
            console.warn('Theme toggle failed, using localStorage fallback:', error);
            
            // Fallback to localStorage toggle
            const currentTheme = localStorage.getItem('theme') || 'light';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            localStorage.setItem('theme', newTheme);
            
            if (newTheme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            
            this.updateThemeIcons(newTheme);
            return newTheme;
        }
    }

    updateThemeIcons(theme) {
        // Desktop icons
        const sunIcon = document.getElementById('sunIcon');
        const moonIcon = document.getElementById('moonIcon');
        
        // Mobile icons
        const mobileSunIcon = document.getElementById('mobileSunIcon');
        const mobileMoonIcon = document.getElementById('mobileMoonIcon');
        
        if (theme === 'dark') {
            sunIcon?.classList.remove('hidden');
            moonIcon?.classList.add('hidden');
            mobileSunIcon?.classList.remove('hidden');
            mobileMoonIcon?.classList.add('hidden');
        } else {
            sunIcon?.classList.add('hidden');
            moonIcon?.classList.remove('hidden');
            mobileSunIcon?.classList.add('hidden');
            mobileMoonIcon?.classList.remove('hidden');
        }
    }

    setupEventListeners() {
        // Desktop theme toggle
        const themeToggle = document.getElementById('themeToggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', () => this.toggleTheme());
        }

        // Mobile theme toggle
        const mobileThemeToggle = document.getElementById('mobileThemeToggle');
        if (mobileThemeToggle) {
            mobileThemeToggle.addEventListener('click', () => this.toggleTheme());
        }
    }

    async syncWithServer() {
        try {
            const serverTheme = await this.getCurrentTheme();
            const localTheme = localStorage.getItem('theme');
            
            // If there's a mismatch, use server theme
            if (serverTheme !== localTheme) {
                localStorage.setItem('theme', serverTheme);
                
                const isDark = this.shouldApplyDarkMode(serverTheme);
                if (isDark) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
                
                this.updateThemeIcons(isDark ? 'dark' : 'light');
            }
        } catch (error) {
            console.warn('Theme sync failed:', error);
        }
    }

    setupSystemThemeListener() {
        // Remove system theme listener as auto mode is no longer supported
    }
}

// Initialize theme manager when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.themeManager = new ThemeManager();
});

// Also initialize immediately if DOM is already loaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.themeManager = new ThemeManager();
    });
} else {
    window.themeManager = new ThemeManager();
}
