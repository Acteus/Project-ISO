'use strict';

(function() {
    function initAuditLogsPage() {
        setCurrentYear();
        animateCards();
        animateLogItems();
        initFilterFocusEffects();
        initExportButton();
        initDetailsToggles();
        initLogoutForm();

        console.log('Enhanced Audit Logs page with filters loaded');
    }

    function setCurrentYear() {
        const currentYearElement = document.getElementById('currentYear');
        if (currentYearElement) {
            currentYearElement.textContent = new Date().getFullYear();
        }
    }

    function animateCards() {
        const cards = document.querySelectorAll('.stat-item, .log-card');

        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';

            window.setTimeout(() => {
                card.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    }

    function animateLogItems() {
        const logItems = document.querySelectorAll('.log-item');

        logItems.forEach((item, index) => {
            item.style.opacity = '0';
            item.style.transform = 'translateX(-20px)';

            window.setTimeout(() => {
                item.style.transition = 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
                item.style.opacity = '1';
                item.style.transform = 'translateX(0)';
            }, index * 50);
        });
    }

    function initFilterFocusEffects() {
        const filterInputs = document.querySelectorAll('.filter-input, .filter-select');

        filterInputs.forEach(input => {
            input.addEventListener('focus', () => {
                input.style.borderColor = '#4285F4';
                input.style.boxShadow = '0 0 0 3px rgba(66, 133, 244, 0.1)';
            });

            input.addEventListener('blur', () => {
                input.style.borderColor = 'rgba(66, 133, 244, 0.2)';
                input.style.boxShadow = 'none';
            });
        });
    }

    function initExportButton() {
        const exportButton = document.getElementById('exportLogsBtn');
        const filterForm = document.getElementById('filterForm');

        if (!exportButton || !filterForm) {
            return;
        }

        exportButton.addEventListener('click', event => {
            event.preventDefault();
            handleExportClick(event.currentTarget, filterForm);
        });
    }

    function handleExportClick(button, filterForm) {
        const originalContent = button.innerHTML;
        button.disabled = true;
        button.dataset.originalContent = originalContent;
        button.innerHTML = '<svg style="width: 16px; height: 16px; vertical-align: middle; margin-right: 6px; animation: spin 1s linear infinite;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6 0-1.01.25-1.97.7-2.8L5.24 7.74C4.46 8.97 4 10.43 4 12c0 4.42 3.58 8 8 8v3l4-4-4-4v3z"/></svg> Exporting...';

        const params = new URLSearchParams(new FormData(filterForm));
        const url = new URL(window.location.href);
        url.searchParams.set('export', 'csv');

        params.forEach((value, key) => {
            if (value) {
                url.searchParams.set(key, value);
            }
        });

        window.setTimeout(() => {
            window.alert('Export functionality would be implemented here. This would generate a CSV file with all filtered audit logs.');
            resetExportButton(button);
        }, 1000);
    }

    function resetExportButton(button) {
        const originalContent = button.dataset.originalContent;
        button.innerHTML = originalContent || 'Export CSV';
        button.disabled = false;
        delete button.dataset.originalContent;
    }

    function initDetailsToggles() {
        const toggleButtons = document.querySelectorAll('.details-toggle-btn[data-log-id]');

        toggleButtons.forEach(button => {
            const logId = button.getAttribute('data-log-id');
            if (!logId) {
                return;
            }

            button.setAttribute('aria-expanded', 'false');

            button.addEventListener('click', event => {
                event.preventDefault();
                toggleDetails(logId, button);
            });

            button.addEventListener('mouseenter', () => {
                button.style.background = 'rgba(66, 133, 244, 0.2)';
                button.style.transform = 'translateY(-1px)';
            });

            button.addEventListener('mouseleave', () => {
                if (button.getAttribute('aria-expanded') !== 'true') {
                    button.style.background = 'rgba(66, 133, 244, 0.1)';
                }
                button.style.transform = 'translateY(0)';
            });
        });
    }

    function toggleDetails(logId, button) {
        const detailsDiv = document.getElementById(`details-${logId}`);
        const toggleText = document.getElementById(`toggle-text-${logId}`);
        const toggleIcon = document.getElementById(`toggle-icon-${logId}`);

        if (!detailsDiv || !toggleText || !toggleIcon) {
            return;
        }

        const isHidden = detailsDiv.style.display === 'none' || !detailsDiv.style.display;

        if (isHidden) {
            detailsDiv.style.display = 'block';
            toggleText.textContent = 'Hide Details';
            toggleIcon.style.transform = 'rotate(180deg)';
            button.style.background = 'rgba(66, 133, 244, 0.2)';
            button.setAttribute('aria-expanded', 'true');
        } else {
            detailsDiv.style.display = 'none';
            toggleText.textContent = 'Show Details';
            toggleIcon.style.transform = 'rotate(0deg)';
            button.style.background = 'rgba(66, 133, 244, 0.1)';
            button.setAttribute('aria-expanded', 'false');
        }
    }

    function initLogoutForm() {
        const logoutForm = document.getElementById('logoutFormDesktop');

        if (logoutForm && typeof window.handleAdminLogout === 'function') {
            logoutForm.addEventListener('submit', window.handleAdminLogout);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAuditLogsPage);
    } else {
        initAuditLogsPage();
    }
})();

