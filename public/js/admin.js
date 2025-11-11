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

// Admin Layout Initialization
(function() {
    'use strict';
    
    // Set current year in footer
    function setCurrentYear() {
        const currentYearElement = document.getElementById('currentYear');
        if (currentYearElement) {
            currentYearElement.textContent = new Date().getFullYear();
        }
    }
    
    // Mobile menu toggle
    function initMobileMenu() {
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const mobileNav = document.getElementById('mobileNav');
        
        if (mobileMenuToggle && mobileNav) {
            mobileMenuToggle.addEventListener('click', function() {
                mobileNav.classList.toggle('show');
            });
        }
        
        // Close mobile menu when clicking outside
        if (mobileNav && mobileMenuToggle) {
            document.addEventListener('click', function(event) {
                if (!mobileNav.contains(event.target) &&
                    !mobileMenuToggle.contains(event.target)) {
                    mobileNav.classList.remove('show');
                }
            });
        }
    }
    
    // Initialize logout handlers
    function initLogoutHandlers() {
        const logoutFormDesktop = document.getElementById('logoutFormDesktop');
        const logoutFormMobile = document.getElementById('logoutFormMobile');
        
        if (logoutFormDesktop && typeof handleAdminLogout === 'function') {
            logoutFormDesktop.addEventListener('submit', handleAdminLogout);
        }
        
        if (logoutFormMobile && typeof handleAdminLogout === 'function') {
            logoutFormMobile.addEventListener('submit', handleAdminLogout);
        }
    }
    
    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            setCurrentYear();
            initMobileMenu();
            initLogoutHandlers();
        });
    } else {
        setCurrentYear();
        initMobileMenu();
        initLogoutHandlers();
    }
})();

// Admin Logout Handler
(function() {
    'use strict';
    
    // Admin logout function with beautiful modal popup
    window.handleAdminLogout = function(event) {
        event.preventDefault();
        
        const form = event.target.closest('form');
        if (!form) return;
        
        const formData = new FormData(form);
        const csrfToken = document.querySelector('[name="_token"]')?.value || 
                         document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            credentials: 'include',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => {
            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            }
            // If not JSON, still show modal (might be redirect)
            return {};
        })
        .then(data => {
            // Show beautiful logout modal
            showAdminLogoutModal();
        })
        .catch(error => {
            console.error('Logout error:', error);
            // Show modal anyway even if request fails
            showAdminLogoutModal();
        });
    };
    
    // Show the beautiful logout modal for admin
    function showAdminLogoutModal() {
        // Remove any existing modal
        const existingModal = document.querySelector('.logout-modal-overlay');
        if (existingModal) {
            existingModal.remove();
        }
        
        const modal = document.createElement('div');
        modal.className = 'logout-modal-overlay show';
        modal.tabIndex = -1;
        
        // Get login and home URLs from data attributes or use defaults
        const loginUrl = document.body.getAttribute('data-login-url') || '/student/login';
        const homeUrl = document.body.getAttribute('data-home-url') || '/home';
        
        modal.innerHTML = `
            <div class="logout-modal-card">
                <div class="logout-modal-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                    </svg>
                </div>

                <h1>Successfully Logged Out</h1>
                <p>You have been safely logged out of the ISO 21001 Quality Education System. Thank you for your participation!</p>

                <div class="logout-modal-actions">
                    <a href="${loginUrl}" class="logout-modal-btn logout-modal-btn-primary">Log In Again</a>
                    <a href="${homeUrl}" class="logout-modal-btn logout-modal-btn-secondary">Return Home</a>
                </div>

                <div class="logout-modal-security-notice">
                    <h3>
                        <svg class="logout-modal-checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                        Security Notice
                    </h3>
                    <p>For your security, please close your browser if you're using a shared computer.</p>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Focus on modal
        setTimeout(() => {
            modal.focus();
        }, 100);
        
        // Prevent background scrolling
        document.body.style.overflow = 'hidden';
    }
    
    // Make function globally available
    window.showAdminLogoutModal = showAdminLogoutModal;
})();



