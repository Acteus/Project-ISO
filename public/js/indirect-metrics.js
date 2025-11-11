(function() {
    'use strict';

    function initFileInput() {
        const fileInput = document.getElementById('csv_file');
        const fileLabel = document.getElementById('fileLabel');

        if (!fileInput || !fileLabel) {
            return;
        }

        fileInput.addEventListener('change', function(event) {
            const files = event.target.files || [];
            if (files.length > 0) {
                fileLabel.classList.add('has-file');
                fileLabel.innerHTML = `<span>📄 ${files[0].name}</span>`;
            } else {
                fileLabel.classList.remove('has-file');
                fileLabel.innerHTML = `<span>📁 Click to select CSV file or drag and drop</span>`;
            }
        });
    }

    function initFormLoadingStates() {
        const csvUploadForm = document.getElementById('csvUploadForm');
        if (csvUploadForm) {
            csvUploadForm.addEventListener('submit', function() {
                const submitButton = csvUploadForm.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.textContent = 'Uploading...';
                }
            });
        }

        const manualForm = document.getElementById('manualForm');
        if (manualForm) {
            manualForm.addEventListener('submit', function() {
                const submitButton = manualForm.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.textContent = 'Updating...';
                }
            });
        }
    }

    function initLogoutHandler() {
        const logoutForm = document.querySelector('[data-admin-logout-form]');
        if (!logoutForm) {
            return;
        }

        logoutForm.addEventListener('submit', function(event) {
            if (typeof window.handleAdminLogout === 'function') {
                window.handleAdminLogout(event);
            }
        });
    }

    function init() {
        initFileInput();
        initFormLoadingStates();
        initLogoutHandler();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();

