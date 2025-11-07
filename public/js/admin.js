/**
 * Admin Shared JavaScript
 * Toast notifications, loading states, and common utilities
 */

// Toast Notification System
class ToastManager {
    constructor() {
        this.container = document.getElementById('toast-container');
        if (!this.container) {
            this.container = document.createElement('div');
            this.container.id = 'toast-container';
            this.container.className = 'toast-container';
            document.body.appendChild(this.container);
        }
    }

    show(message, type = 'info', duration = 5000) {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        
        const icon = this.getIcon(type);
        toast.innerHTML = `
            <div class="toast-icon">${icon}</div>
            <div class="toast-message">${message}</div>
            <button class="toast-close" onclick="this.parentElement.remove()">&times;</button>
        `;

        this.container.appendChild(toast);

        // Trigger animation
        setTimeout(() => toast.classList.add('show'), 10);

        // Auto remove
        if (duration > 0) {
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }, duration);
        }

        return toast;
    }

    getIcon(type) {
        const icons = {
            success: '✓',
            error: '✕',
            warning: '⚠',
            info: 'ℹ'
        };
        return icons[type] || icons.info;
    }

    success(message, duration) {
        return this.show(message, 'success', duration);
    }

    error(message, duration) {
        return this.show(message, 'error', duration);
    }

    warning(message, duration) {
        return this.show(message, 'warning', duration);
    }

    info(message, duration) {
        return this.show(message, 'info', duration);
    }
}

// Initialize toast manager
const toast = new ToastManager();

// Loading State Manager
class LoadingManager {
    constructor() {
        this.overlay = document.getElementById('loading-overlay');
    }

    show() {
        if (this.overlay) {
            this.overlay.classList.add('active');
        }
    }

    hide() {
        if (this.overlay) {
            this.overlay.classList.remove('active');
        }
    }
}

// Initialize loading manager
const loading = new LoadingManager();

// Utility Functions
const AdminUtils = {
    // Show loading state
    showLoading() {
        loading.show();
    },

    // Hide loading state
    hideLoading() {
        loading.hide();
    },

    // Show toast notification
    showToast(message, type = 'info', duration = 5000) {
        return toast.show(message, type, duration);
    },

    // Show success toast
    showSuccess(message, duration) {
        return toast.success(message, duration);
    },

    // Show error toast
    showError(message, duration) {
        return toast.error(message, duration);
    },

    // Show warning toast
    showWarning(message, duration) {
        return toast.warning(message, duration);
    },

    // Show info toast
    showInfo(message, duration) {
        return toast.info(message, duration);
    },

    // Format date
    formatDate(date, format = 'short') {
        const d = new Date(date);
        if (format === 'short') {
            return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        } else if (format === 'long') {
            return d.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
        } else if (format === 'datetime') {
            return d.toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' });
        }
        return d.toLocaleDateString();
    },

    // Format number
    formatNumber(num, decimals = 2) {
        return parseFloat(num).toFixed(decimals);
    },

    // Debounce function
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },

    // Throttle function
    throttle(func, limit) {
        let inThrottle;
        return function(...args) {
            if (!inThrottle) {
                func.apply(this, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    },

    // Copy to clipboard
    async copyToClipboard(text) {
        try {
            await navigator.clipboard.writeText(text);
            this.showSuccess('Copied to clipboard!');
            return true;
        } catch (err) {
            this.showError('Failed to copy to clipboard');
            return false;
        }
    },

    // Get CSRF token
    getCSRFToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    },

    // Make API request
    async apiRequest(url, options = {}) {
        const defaultOptions = {
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': this.getCSRFToken()
            }
        };

        const mergedOptions = {
            ...defaultOptions,
            ...options,
            headers: {
                ...defaultOptions.headers,
                ...(options.headers || {})
            }
        };

        try {
            this.showLoading();
            const response = await fetch(url, mergedOptions);
            const data = await response.json();
            
            if (!response.ok) {
                throw new Error(data.message || 'Request failed');
            }
            
            return data;
        } catch (error) {
            this.showError(error.message || 'An error occurred');
            throw error;
        } finally {
            this.hideLoading();
        }
    }
};

// Make utilities globally available
window.toast = toast;
window.loading = loading;
window.AdminUtils = AdminUtils;

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});

// Smooth scroll to top on page load
window.addEventListener('load', function() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
});

