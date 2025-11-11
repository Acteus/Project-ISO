// Main JavaScript file for common functionality

// Initialize the application
document.addEventListener('DOMContentLoaded', function() {
    // Set current year in footer
    const yearElements = document.querySelectorAll('#currentYear');
    const currentYear = new Date().getFullYear();
    yearElements.forEach(element => {
        element.textContent = currentYear;
    });

    // Initialize mobile menu
    initMobileMenu();

    // Consent revoke confirmation
    const revokeConsentForm = document.getElementById('revokeConsentForm');
    if (revokeConsentForm) {
        revokeConsentForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const confirmText = 'Are you sure you want to revoke your consent?\n\nThis will:\n- Prevent you from submitting new surveys\n- Be logged for audit purposes\n- Not delete existing data immediately\n\nYou can contact the administrator to request data deletion.';
            if (confirm(confirmText)) {
                const button = revokeConsentForm.querySelector('button[type="submit"]');
                if (button) {
                    button.disabled = true;
                    button.innerHTML = '<svg style="width: 16px; height: 16px; vertical-align: middle; margin-right: 5px; animation: spin 1s linear infinite;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6 0-1.01.25-1.97.7-2.8L5.24 7.74C4.46 8.97 4 10.43 4 12c0 4.42 3.58 8 8 8v3l4-4-4-4v3z"/></svg> Revoking...';
                }
                revokeConsentForm.submit();
            }
        });
    }
});

// Mobile menu functionality
function toggleMobileMenu() {
    const mobileNav = document.getElementById('mobileNav');
    const menuToggle = document.getElementById('mobileMenuButton') || document.querySelector('.menu-toggle');

    // Only proceed if elements exist
    if (!mobileNav || !menuToggle) return;

    const isOpen = mobileNav.classList.toggle('show');
    menuToggle.classList.toggle('active', isOpen);
    menuToggle.classList.toggle('is-open', isOpen);
    menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
}

function initMobileMenu() {
    // Check if mobile nav exists on this page
    const mobileNav = document.getElementById('mobileNav');
    const menuToggle = document.getElementById('mobileMenuButton') || document.querySelector('.menu-toggle');
    
    if (!mobileNav) return;

    // Attach click handler to mobile menu button
    if (menuToggle) {
        menuToggle.addEventListener('click', function(event) {
            event.preventDefault();
            event.stopPropagation();
            toggleMobileMenu();
        });
    }

    // Close mobile menu when clicking on a link
    const mobileNavLinks = document.querySelectorAll('.mobile-nav a, .mobile-nav button');
    mobileNavLinks.forEach(link => {
        link.addEventListener('click', () => {
            const mobileNav = document.getElementById('mobileNav');
            const menuToggle = document.getElementById('mobileMenuButton') || document.querySelector('.menu-toggle');
            if (mobileNav && menuToggle) {
                mobileNav.classList.remove('show');
                menuToggle.classList.remove('active');
                menuToggle.classList.remove('is-open');
                menuToggle.setAttribute('aria-expanded', 'false');
            }
        });
    });

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        const mobileNav = document.getElementById('mobileNav');
        const menuToggle = document.getElementById('mobileMenuButton') || document.querySelector('.menu-toggle');
        const header = document.querySelector('.landing-header') || document.querySelector('.header');
        const mobileMenuBtn = document.querySelector('.mobile-menu-btn');

        // Only proceed if all elements exist
        // Don't close if clicking on the menu button or inside the mobile nav
        if (mobileNav && menuToggle && header) {
            const isClickInsideNav = mobileNav.contains(event.target);
            const isClickOnButton = mobileMenuBtn && mobileMenuBtn.contains(event.target);
            const isClickInsideHeader = header.contains(event.target);

            if (!isClickInsideNav && !isClickOnButton && isClickInsideHeader && mobileNav.classList.contains('show')) {
                mobileNav.classList.remove('show');
                menuToggle.classList.remove('active');
                menuToggle.classList.remove('is-open');
                menuToggle.setAttribute('aria-expanded', 'false');
            } else if (!isClickInsideHeader && mobileNav.classList.contains('show')) {
                mobileNav.classList.remove('show');
                menuToggle.classList.remove('active');
                menuToggle.classList.remove('is-open');
                menuToggle.setAttribute('aria-expanded', 'false');
            }
        }
    });
}

// Utility functions
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <span class="notification-message">${message}</span>
            <button class="notification-close" type="button" aria-label="Close">×</button>
        </div>
    `;

    // Add styles if not already present
    if (!document.querySelector('#notification-styles')) {
        const styles = document.createElement('style');
        styles.id = 'notification-styles';
        styles.textContent = `
            .notification {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 1000;
                max-width: 400px;
                padding: 1rem;
                border-radius: 0.5rem;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
                background-color: white;
                border-left: 4px solid;
                animation: slideIn 0.3s ease-out;
            }

            .notification-info {
                border-left-color: #3b82f6;
            }

            .notification-success {
                border-left-color: #10b981;
            }

            .notification-error {
                border-left-color: #ef4444;
            }

            .notification-warning {
                border-left-color: #f59e0b;
            }

            .notification-content {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                gap: 1rem;
            }

            .notification-message {
                flex-grow: 1;
                color: #374151;
                font-size: 0.875rem;
                line-height: 1.5;
            }

            .notification-close {
                background: none;
                border: none;
                font-size: 1.25rem;
                cursor: pointer;
                color: #6b7280;
                padding: 0;
                line-height: 1;
            }

            .notification-close:hover {
                color: #374151;
            }

            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }

            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(styles);
    }

    // Add to page
    document.body.appendChild(notification);

    // Wire close button without inline handlers (CSP-safe)
    const closeBtn = notification.querySelector('.notification-close');
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            closeNotification(closeBtn);
        });
    }

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            closeNotification(notification.querySelector('.notification-close'));
        }
    }, 5000);
}

function closeNotification(button) {
    const notification = button.closest('.notification');
    notification.style.animation = 'slideOut 0.3s ease-in';
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 300);
}

// Form validation utilities
function validateRequired(value) {
    return value && value.trim().length > 0;
}

function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Local storage utilities
function saveToLocalStorage(key, data) {
    try {
        localStorage.setItem(key, JSON.stringify(data));
        return true;
    } catch (error) {
        console.error('Error saving to localStorage:', error);
        return false;
    }
}

function loadFromLocalStorage(key) {
    try {
        const data = localStorage.getItem(key);
        return data ? JSON.parse(data) : null;
    } catch (error) {
        console.error('Error loading from localStorage:', error);
        return null;
    }
}

function removeFromLocalStorage(key) {
    try {
        localStorage.removeItem(key);
        return true;
    } catch (error) {
        console.error('Error removing from localStorage:', error);
        return false;
    }
}

// API request utilities
async function makeApiRequest(url, options = {}) {
    try {
        const defaultOptions = {
            headers: {
                'Content-Type': 'application/json',
            },
        };

        const mergedOptions = {
            ...defaultOptions,
            ...options,
            headers: {
                ...defaultOptions.headers,
                ...options.headers,
            },
        };

        const response = await fetch(url, mergedOptions);
        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Request failed');
        }

        return data;
    } catch (error) {
        console.error('API request error:', error);
        throw error;
    }
}

// Smooth scroll utility
function smoothScrollTo(element) {
    if (element) {
        element.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}

// Loading state utilities
function showLoading(element) {
    if (element) {
        element.disabled = true;
        element.classList.add('loading');
        const originalText = element.textContent;
        element.dataset.originalText = originalText;
        element.textContent = 'Loading...';
    }
}

function hideLoading(element) {
    if (element) {
        element.disabled = false;
        element.classList.remove('loading');
        element.textContent = element.dataset.originalText || element.textContent;
    }
}
