<style>
    .logout-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.85);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 99999;
        animation: fadeIn 0.3s ease;
    }

    .logout-modal-overlay.show {
        display: flex;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .logout-modal-card {
        background: white;
        border-radius: 20px;
        padding: 60px 40px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        text-align: center;
        max-width: 500px;
        width: 90%;
        animation: slideIn 0.5s ease-out;
    }

    .logout-modal-icon {
        width: 100px;
        height: 100px;
        margin: 0 auto 30px;
        background: linear-gradient(135deg, #4285F4, #2c6cd6);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(66, 133, 244, 0.7);
        }
        50% {
            transform: scale(1.05);
            box-shadow: 0 0 0 20px rgba(66, 133, 244, 0);
        }
    }

    .logout-modal-icon svg {
        width: 50px;
        height: 50px;
        fill: white;
    }

    .logout-modal-card h1 {
        color: #333;
        font-size: 32px;
        margin: 0 0 15px 0;
        font-weight: 700;
    }

    .logout-modal-card p {
        color: #666;
        font-size: 18px;
        margin: 0 0 35px 0;
        line-height: 1.6;
    }

    .logout-modal-actions {
        display: flex;
        gap: 15px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .logout-modal-btn {
        display: inline-block;
        padding: 14px 30px;
        border: none;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .logout-modal-btn-primary {
        background: linear-gradient(90deg, #4285F4, #2c6cd6);
        color: white;
    }

    .logout-modal-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(66, 133, 244, 0.4);
        color: white;
    }

    .logout-modal-btn-secondary {
        background: #f8f9fa;
        color: #333;
        border: 2px solid #e0e0e0;
    }

    .logout-modal-btn-secondary:hover {
        background: #e9ecef;
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        color: #333;
    }

    .logout-modal-security-notice {
        margin-top: 40px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 10px;
        border-left: 4px solid #28a745;
    }

    .logout-modal-security-notice h3 {
        margin: 0 0 10px 0;
        color: #28a745;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .logout-modal-security-notice p {
        margin: 0;
        font-size: 14px;
        color: #666;
    }

    .logout-modal-checkmark {
        width: 20px;
        height: 20px;
        fill: #28a745;
    }

    @media (max-width: 768px) {
        .logout-modal-card {
            padding: 40px 25px;
        }

        .logout-modal-card h1 {
            font-size: 26px;
        }

        .logout-modal-card p {
            font-size: 16px;
        }

        .logout-modal-actions {
            flex-direction: column;
        }

        .logout-modal-btn {
            width: 100%;
        }
    }
</style>

<script>
// Global logout function with beautiful modal popup
function handleLogout(event) {
    event.preventDefault();
    
    const form = event.target.closest('form');
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        credentials: 'include',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value
        }
    })
    .then(response => response.json())
    .then(data => {
        // Show beautiful logout modal
        showBeautifulLogoutModal();
    })
    .catch(error => {
        console.error('Logout error:', error);
        // Show modal anyway
        showBeautifulLogoutModal();
    });
}

// Show the beautiful logout modal
function showBeautifulLogoutModal() {
    // Remove any existing modal
    const existingModal = document.querySelector('.logout-modal-overlay');
    if (existingModal) {
        existingModal.remove();
    }
    
    const modal = document.createElement('div');
    modal.className = 'logout-modal-overlay show';
    modal.tabIndex = -1;
    
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
                <a href="{{ route('student.login') }}" class="logout-modal-btn logout-modal-btn-primary">Log In Again</a>
                <a href="{{ route('survey.landing') }}" class="logout-modal-btn logout-modal-btn-secondary">Return Home</a>
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

// Attach logout handler to all logout forms on page load
document.addEventListener('DOMContentLoaded', function() {
    const logoutForms = document.querySelectorAll('form[action*="logout"]');
    logoutForms.forEach(form => {
        form.addEventListener('submit', handleLogout);
    });
});
</script>
