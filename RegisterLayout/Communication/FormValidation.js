/**
 * Form validation utilities with nicely styled error messages
 */

/**
 * Shows an error message on a form field
 * @param {HTMLElement} inputElement - The input element with the error
 * @param {string} message - The error message
 * @param {HTMLElement} [errorElement] - Optional error element, if not provided will look for next sibling
 */
function showInputError(inputElement, message, errorElement = null) {
    // Get the error element if not provided
    if (!errorElement) {
        // Look for next sibling that might be an error element
        errorElement = inputElement.nextElementSibling;
        if (!errorElement || !errorElement.classList.contains('error-message-DM') && 
            !errorElement.classList.contains('group-name-error') && 
            !errorElement.classList.contains('email-error')) {
            // Create new error element if none exists
            errorElement = document.createElement('p');
            errorElement.className = 'error-message-DM';
            inputElement.parentNode.insertBefore(errorElement, inputElement.nextSibling);
        }
    }
    
    // Add error styling to input
    inputElement.classList.add('input-error');
    inputElement.style.borderColor = '#fc8181';
    
    // Show error message
    errorElement.textContent = message;
    errorElement.classList.remove('hidden');
    
    // Gentle shake animation for the input
    inputElement.animate([
        { transform: 'translateX(0)' },
        { transform: 'translateX(-5px)' },
        { transform: 'translateX(5px)' },
        { transform: 'translateX(-5px)' },
        { transform: 'translateX(0)' }
    ], {
        duration: 300,
        easing: 'ease-in-out'
    });
    
    // Focus the input
    inputElement.focus();
}

/**
 * Clears error styling from an input element
 * @param {HTMLElement} inputElement - The input element to clear errors from
 * @param {HTMLElement} [errorElement] - Optional error element
 */
function clearInputError(inputElement, errorElement = null) {
    // Remove error styling
    inputElement.classList.remove('input-error');
    inputElement.style.borderColor = '';
    
    // Hide error message if we have an error element
    if (errorElement) {
        errorElement.classList.add('hidden');
    } else {
        // Try to find the error element
        const nextElement = inputElement.nextElementSibling;
        if (nextElement && (
            nextElement.classList.contains('error-message-DM') || 
            nextElement.classList.contains('group-name-error') || 
            nextElement.classList.contains('email-error'))) {
            nextElement.classList.add('hidden');
        }
    }
}

/**
 * Validates an email input field
 * @param {HTMLElement} inputElement - The email input element
 * @param {HTMLElement} [errorElement] - Optional error element
 * @returns {boolean} Whether the email is valid
 */
function validateEmailInput(inputElement, errorElement = null) {
    const email = inputElement.value.trim();
    
    if (!email) {
        showInputError(inputElement, 'Please enter an email address', errorElement);
        return false;
    }
    
    // Use your existing email validation regex
    const re = /\S+@gmail\.com/;
    if (!re.test(email)) {
        showInputError(inputElement, 'Please enter a valid email address', errorElement);
        return false;
    }
    
    // Check if it's the user's own email
    if (email === getCookieValue('EMAIL')) {
        showInputError(inputElement, 'You cannot add yourself as a contact', errorElement);
        return false;
    }
    
    // Clear any errors if valid
    clearInputError(inputElement, errorElement);
    return true;
}

// Export functions
export {
    showInputError,
    clearInputError,
    validateEmailInput
};
