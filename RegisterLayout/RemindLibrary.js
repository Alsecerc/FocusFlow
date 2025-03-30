/**
 * Custom confirmation dialog that replaces the standard browser confirm()
 * 
 * @param {string} message - The message to display
 * @param {string} title - Optional title for the dialog
 * @param {Object} options - Optional configuration
 * @param {string} options.okText - Text for the confirm button (default: "OK")
 * @param {string} options.cancelText - Text for the cancel button (default: "Cancel")
 * @param {string} options.type - Type of confirm ('default', 'warning', 'delete')
 * @returns {Promise} Resolves to true if confirmed, false if canceled
 */
export function customConfirm(message, title = "Confirmation", options = {}) {
    return new Promise((resolve) => {
        // Set default options
        const settings = {
            okText: options.okText || "OK",
            cancelText: options.cancelText || "Cancel",
            type: options.type || "default"
        };

        // Create overlay
        const overlay = document.createElement("div");
        overlay.className = "confirm-dialog-overlay";

        // Create dialog
        const dialog = document.createElement("div");
        dialog.className = "confirm-dialog";

        // Create header
        const header = document.createElement("div");
        header.className = "confirm-dialog-header";
        
        const headerTitle = document.createElement("h3");
        headerTitle.textContent = title;
        header.appendChild(headerTitle);

        // Create body
        const body = document.createElement("div");
        body.className = "confirm-dialog-body";
        body.textContent = message;

        // Create actions
        const actions = document.createElement("div");
        actions.className = "confirm-dialog-actions";

        // Create cancel button
        const cancelBtn = document.createElement("button");
        cancelBtn.className = "confirm-dialog-btn confirm-cancel-btn";
        cancelBtn.textContent = settings.cancelText;
        cancelBtn.addEventListener("click", () => {
            document.body.removeChild(overlay);
            resolve(false);
        });

        // Create confirm button
        const okBtn = document.createElement("button");
        
        if (settings.type === "delete") {
            okBtn.className = "confirm-dialog-btn confirm-delete-btn";
        } else {
            okBtn.className = "confirm-dialog-btn confirm-ok-btn";
        }
        
        okBtn.textContent = settings.okText;
        okBtn.addEventListener("click", () => {
            document.body.removeChild(overlay);
            resolve(true);
        });

        // Assemble the dialog
        actions.appendChild(cancelBtn);
        actions.appendChild(okBtn);

        dialog.appendChild(header);
        dialog.appendChild(body);
        dialog.appendChild(actions);

        overlay.appendChild(dialog);

        overlay.addEventListener("click", (e) => {
            if (e.target === overlay) {
                document.body.removeChild(overlay);
                resolve(false);
            }
        });

        overlay.addEventListener("keydown", (e) => {
            if (e.key === "Escape") {
                document.body.removeChild(overlay);
                resolve(false);
            }
        });
        
        document.body.appendChild(overlay);

        // Focus the cancel button by default (safer option)
        cancelBtn.focus();
    });
}

/**
 * Shows a toast error message instead of using alert()
 * @param {string} message - The error message to display
 * @param {number} duration - How long to show the message in ms (default: 3000ms)
 */
function showErrorToast(message, duration = 3000) {
    // Remove any existing toast
    const existingToast = document.querySelector('.error-toast');
    if (existingToast) {
        existingToast.remove();
    }
    
    // Create toast element
    const toast = document.createElement('div');
    toast.className = 'error-toast';
    toast.textContent = message;
    
    // Add to DOM
    document.body.appendChild(toast);
    
    // Remove after duration
    setTimeout(() => {
        toast.style.animation = 'fadeOut 0.3s ease-out forwards';
        setTimeout(() => toast.remove(), 300);
    }, duration);
}

/**
 * Shows a success toast message
 * @param {string} message - The success message to display
 * @param {number} duration - How long to show the message in ms (default: 3000ms)
 */
function showSuccessToast(message, duration = 3000) {
    // Remove any existing toast
    const existingToast = document.querySelector('.success-toast, .error-toast');
    if (existingToast) {
        existingToast.remove();
    }
    
    // Create toast element
    const toast = document.createElement('div');
    toast.className = 'success-toast';
    toast.textContent = message;
    
    // Add to DOM
    document.body.appendChild(toast);
    
    // Remove after duration
    setTimeout(() => {
        toast.style.animation = 'fadeOut 0.3s ease-out forwards';
        setTimeout(() => toast.remove(), 300)
    }, duration);
}

const RemindLibrary = {
    customConfirm,
    showErrorToast,
    showSuccessToast
};

export default RemindLibrary ;

// Usage example:
// Instead of: if (confirm("Are you sure?")) { doSomething(); }
// Use: 
// customConfirm("Are you sure?", "Confirmation").then(result => {
//     if (result) {
//         doSomething();
//     }
// });

// For delete confirmations:
// customConfirm("This action cannot be undone. Are you sure?", "Delete Confirmation", {
//     okText: "Delete",
//     type: "delete"
// }).then(result => {
//     if (result) {
//         deleteItem();
//     }
// });
