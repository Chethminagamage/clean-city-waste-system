document.addEventListener('DOMContentLoaded', function() {
    // Get the current theme from localStorage
    const savedTheme = localStorage.getItem('theme');
    
    // Apply saved theme if exists
    if (savedTheme) {
        document.documentElement.setAttribute('data-theme', savedTheme);
        
        // Update the toggle button icon
        updateToggleButton(savedTheme);
    }
    
    // Add click event to theme toggle button
    const toggleButton = document.getElementById('theme-toggle');
    if (toggleButton) {
        toggleButton.addEventListener('click', function() {
            // Make AJAX request to update theme in session
            fetch('/theme/toggle', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                console.log('Theme updated in session');
            })
            .catch(error => {
                console.error('Error updating theme:', error);
            });
            
            // Toggle theme immediately for better UX
            const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            
            // Update HTML attribute
            document.documentElement.setAttribute('data-theme', newTheme);
            
            // Save to localStorage for persistence
            localStorage.setItem('theme', newTheme);
            
            // Update toggle button
            updateToggleButton(newTheme);
        });
    }
});

// Function to update toggle button icon
function updateToggleButton(theme) {
    const toggleButton = document.getElementById('theme-toggle');
    if (toggleButton) {
        if (theme === 'dark') {
            toggleButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" /></svg>';
            toggleButton.setAttribute('title', 'Switch to Light Mode');
        } else {
            toggleButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" /></svg>';
            toggleButton.setAttribute('title', 'Switch to Dark Mode');
        }
    }
}
