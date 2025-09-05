<!-- Collector Floating Theme Picker -->
<div id="collector-floating-theme-picker" class="fixed bottom-6 right-6 z-50">
    <!-- Theme Toggle Button -->
    <button id="collector-floating-theme-toggle" 
            class="w-14 h-14 bg-orange-500 hover:bg-orange-600 dark:bg-orange-600 dark:hover:bg-orange-700 text-white rounded-full shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center justify-center group"
            title="Toggle Theme">
        <i id="collector-floating-theme-icon" class="fas fa-moon text-xl transition-transform duration-300 group-hover:rotate-12"></i>
    </button>

    <!-- Theme Options Panel (Hidden by default) -->
    <div id="collector-theme-options-panel" 
         class="absolute bottom-16 right-0 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 p-4 w-64 transform scale-0 origin-bottom-right transition-all duration-300 opacity-0 pointer-events-none">
        
        <!-- Header -->
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                <i class="fas fa-palette text-orange-500 dark:text-orange-400 mr-2"></i>
                Choose Theme
            </h3>
            <button id="collector-close-theme-panel" 
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Select your preferred appearance</p>

        <!-- Theme Options -->
        <div class="space-y-3">
            <!-- Light Mode Option -->
            <div class="collector-theme-option" data-theme="light">
                <label class="flex items-center p-3 rounded-xl border-2 border-gray-200 dark:border-gray-600 hover:border-orange-300 dark:hover:border-orange-500 cursor-pointer transition-all duration-200 group">
                    <div class="flex items-center space-x-3 flex-1">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-gradient-to-br from-orange-100 to-yellow-100 rounded-lg flex items-center justify-center group-hover:scale-105 transition-transform duration-200">
                                <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-base font-semibold text-gray-900 dark:text-white group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors duration-200">Light Mode</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Bright and clean interface with orange accents</p>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="w-5 h-5 border-2 border-gray-300 dark:border-gray-600 rounded-full flex items-center justify-center transition-colors duration-200" id="light-mode-indicator">
                                <div class="w-2.5 h-2.5 bg-orange-500 rounded-full scale-0 transition-transform duration-200"></div>
                            </div>
                        </div>
                    </div>
                </label>
            </div>

            <!-- Dark Mode Option -->
            <div class="collector-theme-option" data-theme="dark">
                <label class="flex items-center p-3 rounded-xl border-2 border-gray-200 dark:border-gray-600 hover:border-orange-300 dark:hover:border-orange-500 cursor-pointer transition-all duration-200 group">
                    <div class="flex items-center space-x-3 flex-1">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-gradient-to-br from-gray-700 to-gray-900 rounded-lg flex items-center justify-center group-hover:scale-105 transition-transform duration-200">
                                <svg class="w-5 h-5 text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-base font-semibold text-gray-900 dark:text-white group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors duration-200">Dark Mode</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Easy on the eyes interface with warm orange tones</p>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="w-5 h-5 border-2 border-gray-300 dark:border-gray-600 rounded-full flex items-center justify-center transition-colors duration-200" id="dark-mode-indicator">
                                <div class="w-2.5 h-2.5 bg-orange-500 rounded-full scale-0 transition-transform duration-200"></div>
                            </div>
                        </div>
                    </div>
                </label>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-4 pt-3 border-t border-gray-200 dark:border-gray-700">
            <p class="text-xs text-gray-500 dark:text-gray-400 text-center">
                <i class="fas fa-info-circle mr-1"></i>
                Theme preference is saved automatically
            </p>
        </div>
    </div>
</div>

<style>
/* Floating Theme Picker Styles */
#collector-floating-theme-picker .collector-theme-option[data-theme="light"] label.active {
    border-color: #f97316;
    background-color: #fff7ed;
}

.dark #collector-floating-theme-picker .collector-theme-option[data-theme="light"] label.active {
    border-color: #f97316;
    background-color: #ea580c20;
}

#collector-floating-theme-picker .collector-theme-option[data-theme="dark"] label.active {
    border-color: #f97316;
    background-color: #fff7ed;
}

.dark #collector-floating-theme-picker .collector-theme-option[data-theme="dark"] label.active {
    border-color: #f97316;
    background-color: #ea580c20;
}

#collector-floating-theme-picker .collector-theme-option label.active .w-2\.5 {
    transform: scale(1);
}

/* Hover animations */
#collector-floating-theme-picker .collector-theme-option:hover label {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(249, 115, 22, 0.15);
}

.dark #collector-floating-theme-picker .collector-theme-option:hover label {
    box-shadow: 0 4px 12px rgba(234, 88, 12, 0.25);
}

/* Panel animation classes */
#collector-theme-options-panel.show {
    transform: scale(1);
    opacity: 1;
    pointer-events: auto;
}

/* Mobile responsive */
@media (max-width: 640px) {
    #collector-theme-options-panel {
        width: 280px;
        right: -8px;
    }
    
    #collector-floating-theme-picker {
        bottom: 20px;
        right: 20px;
    }
    
    #collector-floating-theme-toggle {
        width: 56px;
        height: 56px;
    }
}
</style>

<script>
// Collector Floating Theme Picker Functionality
document.addEventListener('DOMContentLoaded', function() {
    const floatingToggle = document.getElementById('collector-floating-theme-toggle');
    const themePanel = document.getElementById('collector-theme-options-panel');
    const closePanelBtn = document.getElementById('collector-close-theme-panel');
    const themeOptions = document.querySelectorAll('.collector-theme-option');
    const floatingIcon = document.getElementById('collector-floating-theme-icon');
    
    let panelOpen = false;

    // Initialize theme indicators
    function updateThemeIndicators() {
        const currentTheme = localStorage.getItem('collector-theme') || 'light';
        const isDark = document.documentElement.classList.contains('dark');
        const actualTheme = isDark ? 'dark' : 'light';
        
        // Update indicators
        document.querySelectorAll('.collector-theme-option label').forEach(label => {
            label.classList.remove('active');
        });
        
        const activeOption = document.querySelector(`.collector-theme-option[data-theme="${actualTheme}"] label`);
        if (activeOption) {
            activeOption.classList.add('active');
        }
        
        // Update floating icon
        if (actualTheme === 'dark') {
            floatingIcon.className = 'fas fa-sun text-xl transition-transform duration-300 group-hover:rotate-12';
        } else {
            floatingIcon.className = 'fas fa-moon text-xl transition-transform duration-300 group-hover:rotate-12';
        }
    }

    // Toggle panel
    function togglePanel() {
        panelOpen = !panelOpen;
        if (panelOpen) {
            themePanel.classList.add('show');
            updateThemeIndicators();
        } else {
            themePanel.classList.remove('show');
        }
    }

    // Close panel
    function closePanel() {
        panelOpen = false;
        themePanel.classList.remove('show');
    }

    // Quick toggle theme (click the floating button)
    floatingToggle.addEventListener('click', function(e) {
        // If panel is open, close it. If closed, toggle theme directly
        if (panelOpen) {
            closePanel();
        } else {
            // Quick toggle
            if (window.collectorThemeManager) {
                window.collectorThemeManager.toggleTheme().then(() => {
                    updateThemeIndicators();
                });
            }
        }
    });

    // Long press to open panel
    let pressTimer;
    floatingToggle.addEventListener('mousedown', function() {
        pressTimer = setTimeout(() => {
            togglePanel();
        }, 500); // 500ms for long press
    });

    floatingToggle.addEventListener('mouseup', function() {
        clearTimeout(pressTimer);
    });

    floatingToggle.addEventListener('mouseleave', function() {
        clearTimeout(pressTimer);
    });

    // Double click to open panel
    floatingToggle.addEventListener('dblclick', function(e) {
        e.preventDefault();
        togglePanel();
    });

    // Close panel button
    closePanelBtn.addEventListener('click', closePanel);

    // Theme option selection
    themeOptions.forEach(option => {
        option.addEventListener('click', function() {
            const selectedTheme = this.dataset.theme;
            
            // Apply theme immediately
            if (selectedTheme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            
            // Save to server and localStorage
            if (window.collectorThemeManager) {
                // Update localStorage immediately
                localStorage.setItem('collector-theme', selectedTheme);
                
                // Save to server
                fetch('/theme/set', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ theme: selectedTheme })
                }).catch(error => {
                    console.warn('Failed to save theme to server:', error);
                });
            }
            
            // Update indicators and close panel
            updateThemeIndicators();
            setTimeout(closePanel, 300);
            
            // Show notification
            if (window.showNotification) {
                window.showNotification(`Switched to ${selectedTheme} mode`, 'success');
            }
        });
    });

    // Close panel when clicking outside
    document.addEventListener('click', function(e) {
        if (panelOpen && !themePanel.contains(e.target) && !floatingToggle.contains(e.target)) {
            closePanel();
        }
    });

    // Listen for theme changes from other sources
    window.addEventListener('collectorThemeChanged', function(e) {
        updateThemeIndicators();
    });

    // Initialize
    updateThemeIndicators();
});
</script>
