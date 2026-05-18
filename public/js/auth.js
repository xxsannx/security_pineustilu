/**
 * Authentication page scripts
 * Handles password visibility toggle and other auth-related interactions
 */
(function() {
    'use strict';

    /**
     * Navigate back to previous page or home
     */
    function goBack() {
        if (document.referrer && document.referrer !== '') {
            window.history.back();
        } else {
            window.location.href = '/';
        }
    }

    /**
     * Toggle password visibility for a given input field
     * @param {string} inputId - The ID of the password input element
     */
    function togglePassword(inputId) {
        const passwordInput = document.getElementById(inputId);
        const eyeIcon = document.getElementById('eye-icon-' + inputId);
        
        if (!passwordInput || !eyeIcon) {
            console.warn('[auth.js] Password input or eye icon not found for:', inputId);
            return;
        }
        
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';
        
        // Update icon based on visibility state
        if (isPassword) {
            // Show "eye-off" icon when password is visible
            eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
        } else {
            // Show "eye" icon when password is hidden
            eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
        }
    }

    /**
     * Initialize password toggle buttons
     * Automatically binds click handlers to buttons with data-toggle-password attribute
     */
    function initPasswordToggles() {
        const toggleButtons = document.querySelectorAll('[data-toggle-password]');
        
        toggleButtons.forEach(function(button) {
            const targetId = button.getAttribute('data-toggle-password');
            if (targetId) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    togglePassword(targetId);
                });
            }
        });
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPasswordToggles);
    } else {
        initPasswordToggles();
    }

    // Expose functions globally for backward compatibility
    window.togglePassword = togglePassword;
    window.goBack = goBack;
})();
