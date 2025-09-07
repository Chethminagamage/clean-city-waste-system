@php
    $showThemePicker = false;
    
    // Check if user is logged in
    if (auth()->check()) {
        $showThemePicker = true;
    }
@endphp

@if($showThemePicker)
<!-- Floating Theme Picker -->
<div id="floating-theme-picker" class="fixed bottom-24 right-6 z-40">
    <!-- Theme Toggle Button -->
    <button id="theme-picker-toggle" 
            class="group relative bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 
                   text-gray-700 dark:text-gray-300 shadow-xl hover:shadow-2xl border border-gray-200 dark:border-gray-600
                   rounded-full p-4 transition-all duration-300 ease-in-out transform hover:scale-110 hover:-translate-y-1
                   backdrop-blur-sm bg-opacity-95 dark:bg-opacity-95"
            aria-label="Theme Options">
        <!-- Light Mode Icon -->
        <i id="floating-sun-icon" class="fas fa-sun text-xl hidden"></i>
        <!-- Dark Mode Icon -->
        <i id="floating-moon-icon" class="fas fa-moon text-xl"></i>
        
        <!-- Tooltip -->
        <div class="absolute bottom-full right-0 mb-3 px-4 py-2 
                    bg-gray-900 dark:bg-gray-700 text-white text-sm rounded-lg opacity-0 
                    group-hover:opacity-100 transition-opacity duration-200 pointer-events-none
                    whitespace-nowrap shadow-lg">
            <span id="theme-tooltip">Click: Toggle • Double-click: Options</span>
            <div class="absolute top-full right-4 border-4 border-transparent border-t-gray-900 dark:border-t-gray-700"></div>
        </div>
    </button>

    <!-- Theme Options Panel -->
    <div id="theme-options-panel" 
         class="absolute bottom-20 right-0 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl 
                border border-gray-200 dark:border-gray-600 overflow-hidden opacity-0 
                transform scale-95 transition-all duration-300 ease-out pointer-events-none
                backdrop-blur-sm bg-opacity-95 dark:bg-opacity-95 min-w-[280px]">
        
        <!-- Panel Header -->
        <div class="px-6 py-4 bg-gradient-to-r from-emerald-500 to-blue-500 text-white">
            <h3 class="font-semibold text-base flex items-center">
                <i class="fas fa-palette mr-2"></i>
                Choose Theme
            </h3>
            <p class="text-sm opacity-90 mt-1">Select your preferred appearance</p>
        </div>
        
        <!-- Theme Options -->
        <div class="p-4">
            <!-- Light Theme -->
            <button id="theme-light" 
                    class="theme-option w-full flex items-center px-4 py-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 
                           transition-all duration-200 group mb-2 border border-transparent hover:border-gray-200 dark:hover:border-gray-600"
                    data-theme="light">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center mr-4 shadow-lg">
                    <i class="fas fa-sun text-white text-lg"></i>
                </div>
                <div class="flex-1 text-left">
                    <div class="font-semibold text-gray-900 dark:text-gray-100 text-sm">Light Mode</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Bright and clean interface</div>
                </div>
                <i class="fas fa-check text-emerald-500 text-lg opacity-0 theme-check transition-opacity duration-200"></i>
            </button>
            
            <!-- Dark Theme -->
            <button id="theme-dark" 
                    class="theme-option w-full flex items-center px-4 py-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 
                           transition-all duration-200 group border border-transparent hover:border-gray-200 dark:hover:border-gray-600"
                    data-theme="dark">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-600 to-indigo-700 flex items-center justify-center mr-4 shadow-lg">
                    <i class="fas fa-moon text-white text-lg"></i>
                </div>
                <div class="flex-1 text-left">
                    <div class="font-semibold text-gray-900 dark:text-gray-100 text-sm">Dark Mode</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Easy on the eyes interface</div>
                </div>
                <i class="fas fa-check text-emerald-500 text-lg opacity-0 theme-check transition-opacity duration-200"></i>
            </button>
        </div>
    </div>
</div>

<!-- Theme Picker JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const themePicker = document.getElementById('floating-theme-picker');
    const toggleBtn = document.getElementById('theme-picker-toggle');
    const optionsPanel = document.getElementById('theme-options-panel');
    const themeOptions = document.querySelectorAll('.theme-option');
    const themeChecks = document.querySelectorAll('.theme-check');
    
    // Icons
    const sunIcon = document.getElementById('floating-sun-icon');
    const moonIcon = document.getElementById('floating-moon-icon');
    const tooltip = document.getElementById('theme-tooltip');
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    if (!csrfToken) {
        console.error('CSRF token not found! Theme saving may fail.');
    }
    
    let isOpen = false;
    let currentTheme = 'light';
    
    // Initialize current theme
    initializeTheme();
    
    // Handle theme toggle vs panel opening
    let clickTimer = null;
    let longPressTimer = null;
    
    // Single click = quick toggle, Double click = open panel
    toggleBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        
        if (clickTimer === null) {
            clickTimer = setTimeout(() => {
                // Single click - quick toggle theme
                quickToggleTheme();
                clickTimer = null;
            }, 200); // Wait 200ms to see if it's a double click
        } else {
            // Double click - open panel
            clearTimeout(clickTimer);
            clickTimer = null;
            togglePanel();
        }
    });
    
    // Long press (500ms) to open panel
    toggleBtn.addEventListener('mousedown', (e) => {
        longPressTimer = setTimeout(() => {
            if (clickTimer) {
                clearTimeout(clickTimer);
                clickTimer = null;
            }
            togglePanel();
        }, 500);
    });
    
    toggleBtn.addEventListener('mouseup', () => {
        if (longPressTimer) {
            clearTimeout(longPressTimer);
            longPressTimer = null;
        }
    });
    
    toggleBtn.addEventListener('mouseleave', () => {
        if (longPressTimer) {
            clearTimeout(longPressTimer);
            longPressTimer = null;
        }
    });
    
    // Quick toggle function
    async function quickToggleTheme() {
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';
        await setTheme(newTheme);
        
        // Show quick feedback
        const originalText = tooltip.textContent;
        tooltip.textContent = `Switched to ${newTheme} mode`;
        setTimeout(() => {
            tooltip.textContent = originalText;
        }, 1500);
    }
    
    // Close panel when clicking outside
    document.addEventListener('click', (e) => {
        if (!themePicker.contains(e.target)) {
            closePanel();
        }
    });
    
    // Theme option click handlers
    themeOptions.forEach(option => {
        option.addEventListener('click', (e) => {
            e.stopPropagation();
            const theme = option.dataset.theme;
            setTheme(theme);
            closePanel();
        });
    });
    
    function togglePanel() {
        isOpen = !isOpen;
        if (isOpen) {
            openPanel();
        } else {
            closePanel();
        }
    }
    
    function openPanel() {
        isOpen = true;
        optionsPanel.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
        optionsPanel.classList.add('opacity-100', 'scale-100');
        toggleBtn.classList.add('scale-110', '-translate-y-1');
    }
    
    function closePanel() {
        isOpen = false;
        optionsPanel.classList.remove('opacity-100', 'scale-100');
        optionsPanel.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
        toggleBtn.classList.remove('scale-110', '-translate-y-1');
    }
    
    async function setTheme(theme) {
        try {
            // Update server preference
            const response = await fetch('/theme/set', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ theme: theme })
            });
            
            if (!response.ok) {
                const errorText = await response.text();
                console.error('Theme API error:', response.status, errorText);
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.success) {
                applyTheme(theme);
                currentTheme = theme;
                updateUI();
                localStorage.setItem('theme', theme);
                console.log('Theme successfully saved to database:', theme);
            } else {
                throw new Error(data.message || 'Failed to save theme preference');
            }
        } catch (error) {
            console.error('Theme update error:', error);
            // Fallback to local storage
            applyTheme(theme);
            currentTheme = theme;
            updateUI();
            localStorage.setItem('theme', theme);
            console.log('Theme saved locally only due to error:', theme);
        }
    }
    
    function applyTheme(theme) {
        const html = document.documentElement;
        
        if (theme === 'dark') {
            html.classList.add('dark');
        } else {
            html.classList.remove('dark');
        }
    }
    
    function updateUI() {
        // Hide all icons
        sunIcon.classList.add('hidden');
        moonIcon.classList.add('hidden');
        
        // Hide all check marks
        themeChecks.forEach(check => check.classList.add('opacity-0'));
        
        // Show appropriate icon and check mark
        if (currentTheme === 'light') {
            sunIcon.classList.remove('hidden');
            tooltip.textContent = 'Click: Dark Mode • Double-click: Options';
            document.getElementById('theme-light').querySelector('.theme-check').classList.remove('opacity-0');
        } else if (currentTheme === 'dark') {
            moonIcon.classList.remove('hidden');
            tooltip.textContent = 'Click: Light Mode • Double-click: Options';
            document.getElementById('theme-dark').querySelector('.theme-check').classList.remove('opacity-0');
        }
    }
    
    async function initializeTheme() {
        try {
            // Get current theme from server
            const response = await fetch('/theme/current');
            const data = await response.json();
            currentTheme = data.theme || 'light';
        } catch (error) {
            // Fallback to localStorage or system preference
            currentTheme = localStorage.getItem('theme') || 'light';
        }
        
        applyTheme(currentTheme);
        updateUI();
    }
});
</script>
@endif
