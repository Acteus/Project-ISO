<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Jose Rizal University - Student Login</title>
  <!-- Google Fonts: Montserrat + Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Poppins:wght@300;400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/functions.css') }}">
  <script src="{{ asset('js/Socmedlinks.js') }}"></script>

  <script>
    // Auto-initialize social media links with mobile-friendly positioning
    document.addEventListener('DOMContentLoaded', function() {
      // Check if we're on a mobile device
      function isMobile() {
        return window.innerWidth <= 768 || 'ontouchstart' in window;
      }

      // Initialize social media links with proper positioning
      if (isMobile()) {
        // Wait for social media script to load and position at top right
        setTimeout(() => {
          const socialContainer = document.getElementById('social-media-container');
          if (socialContainer) {
            socialContainer.style.top = '10px';
            socialContainer.style.right = '10px';
            socialContainer.style.bottom = 'auto';
            socialContainer.style.left = 'auto';
            console.log('Social media links positioned at top right for mobile');
          }
        }, 500);
      } else {
        // Desktop positioning (bottom left)
        setTimeout(() => {
          const socialContainer = document.getElementById('social-media-container');
          if (socialContainer) {
            socialContainer.style.bottom = '20px';
            socialContainer.style.left = '20px';
            socialContainer.style.top = 'auto';
            socialContainer.style.right = 'auto';
            console.log('Social media links positioned at bottom left for desktop');
          }
        }, 500);
      }
    });
  </script>

  <style>
    /* Override body for full-screen layout */
    body {
      margin: 0;
      padding: 0;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background:
        linear-gradient(135deg, rgba(66,133,244,0.45), rgba(255,215,0,0.35)),
        url('{{ asset('images/JoseRizalUniversityy.jpg') }}') no-repeat center center/cover;
      font-family: 'Poppins', Arial, sans-serif;
      color: white;
      text-align: center;
    }

    /* Main container for two-column layout */
    .login-container {
      display: flex;
      background: rgba(0, 0, 0, 0.55);
      border-radius: 24px;
      box-shadow: 0 10px 35px rgba(0,0,0,0.3);
      backdrop-filter: blur(6px);
      width: 900px;
      max-width: 95vw;
      min-height: 500px;
      overflow: hidden;
      opacity: 0;
      transform: translateY(20px) scale(0.98);
      animation: pageEnter 0.8s ease forwards;
    }

    @keyframes pageEnter {
      from {
        opacity: 0;
        transform: translateY(20px) scale(0.98);
      }
      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    /* Left side: Branding */
    .branding {
      flex: 1;
      padding: 50px 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      background: linear-gradient(135deg, rgba(66,133,244,0.1), rgba(255,215,0,0.1));
    }

    .branding h1 {
      font-family: 'Montserrat', sans-serif;
      font-size: 48px;
      font-weight: 700;
      letter-spacing: 2px;
      margin-bottom: 20px;
      background: linear-gradient(90deg, #FFD700 60%, #ffffff 100%);
      -webkit-background-clip: text;
      background-clip: text;
      -webkit-text-fill-color: transparent;
      text-shadow: 0 4px 12px rgba(0,0,0,0.25);
      text-transform: uppercase;
    }

    .branding .tagline {
      font-size: 22px;
      font-weight: 400;
      color: #FFD700;
      letter-spacing: 1.5px;
      text-shadow: 0 2px 6px rgba(0,0,0,0.3);
      font-family: 'Montserrat', sans-serif;
      text-transform: uppercase;
      margin-bottom: 30px;
    }

    /* Right side: Form */
    .form-side {
      flex: 1;
      padding: 50px 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .form-side h2 {
      font-family: 'Montserrat', sans-serif;
      font-size: 32px;
      font-weight: 700;
      letter-spacing: 2px;
      margin-bottom: 30px;
      background: linear-gradient(90deg, #FFD700, #ffffff);
      -webkit-background-clip: text;
      background-clip: text;
      -webkit-text-fill-color: transparent;
      text-shadow: 0 4px 12px rgba(0,0,0,0.3);
      text-align: center;
    }


    /* Form validation styles */
    .error-message {
      color: #dc3545;
      font-size: 14px;
      margin-top: 5px;
      display: none;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group.error input {
      border-color: #dc3545;
      box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }

    .form-group.error .error-message {
      display: block;
    }

    /* Custom button styles */
    .login-btn {
       background: linear-gradient(90deg, #4285F4, #2c6cd6);
       color: #fff;
       border: none;
       padding: 14px 36px;
       border-radius: 10px;
       width: 100%;
       font-size: 18px;
       font-family: 'Montserrat', sans-serif;
       font-weight: 600;
       cursor: pointer;
       transition: transform 0.2s ease, box-shadow 0.2s ease;
       box-shadow: 0 4px 12px rgba(66,133,244,0.4);
       margin-top: 10px;
       text-align: center;
       display: block;
       box-sizing: border-box;
       line-height: 1.5;
     }

    .login-btn:hover {
      background: linear-gradient(90deg, #2c6cd6, #1a4fa0);
      transform: translateY(-3px);
      box-shadow: 0 6px 16px rgba(66,133,244,0.6);
    }

    .create-account-btn {
      background: linear-gradient(90deg, #28a745, #20c997);
      color: #fff;
      border: none;
      padding: 14px 36px;
      border-radius: 10px;
      width: 100%;
      font-size: 18px;
      font-family: 'Montserrat', sans-serif;
      font-weight: 600;
      cursor: pointer;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      box-shadow: 0 4px 12px rgba(40,167,69,0.4);
      margin-top: 10px;
      text-decoration: none;
      display: block;
      text-align: center;
      box-sizing: border-box;
      line-height: 1.5;
    }

    .create-account-btn:hover {
      background: linear-gradient(90deg, #20c997, #17a2b8);
      transform: translateY(-3px);
      box-shadow: 0 6px 16px rgba(40,167,69,0.6);
    }

    .forgot-password {
      text-align: center;
      margin-top: 10px;
    }

    .forgot-password a {
      color: #FFD700;
      text-decoration: none;
      font-size: 14px;
    }

    .forgot-password a:hover {
      text-decoration: underline;
    }

    /* Responsive design */
    @media (max-width: 768px) {
      .login-container {
        flex-direction: column;
        width: 95vw;
        min-height: auto;
        max-width: 100vw;
        margin: 10px;
      }

      .branding, .form-side {
        padding: 25px 20px;
      }

      .branding h1 {
        font-size: 32px;
      }

      .branding .tagline {
        font-size: 16px;
      }

      .form-side h2 {
        font-size: 28px;
      }

      .login-btn, .create-account-btn {
        font-size: 16px;
        min-height: 48px;
        padding: 16px 24px;
      }

      .form-group input {
        font-size: 16px;
        min-height: 44px;
        padding: 12px;
      }
    }

    @media (max-width: 480px) {
      .login-container {
        width: 98vw;
        margin: 5px;
      }

      .branding, .form-side {
        padding: 20px 15px;
      }

      .branding h1 {
        font-size: 28px;
      }

      .form-side h2 {
        font-size: 24px;
      }
    }
    /* Custom Modal Styles */
    .custom-modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.8);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 10000;
      opacity: 0;
      animation: fadeIn 0.3s ease forwards;
    }

    @keyframes fadeIn {
      to { opacity: 1; }
    }

    .custom-modal-content {
      background: linear-gradient(135deg, #4285f4, #ffd700);
      padding: 40px;
      border-radius: 15px;
      max-width: 450px;
      width: 90%;
      text-align: center;
      color: white;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
      transform: scale(0.9);
      animation: modalEnter 0.3s ease forwards;
    }

    @keyframes modalEnter {
      to { transform: scale(1); }
    }

    .custom-modal-icon {
      font-size: 64px;
      margin-bottom: 20px;
    }

    .custom-modal-title {
      font-size: 24px;
      font-weight: 700;
      margin-bottom: 15px;
      font-family: 'Montserrat', sans-serif;
    }

    .custom-modal-message {
      font-size: 16px;
      line-height: 1.6;
      margin-bottom: 25px;
      opacity: 0.95;
    }

    .custom-modal-button {
      background: white;
      color: #4285f4;
      border: none;
      padding: 15px 40px;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      font-family: 'Poppins', sans-serif;
    }

    .custom-modal-button:hover {
      transform: scale(1.05);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
  </style>
</head>
<body>
  <div class="login-container">
    <!-- Left Side: Branding -->
    <div class="branding">
      <h1>Jose Rizal University</h1>
      <div class="tagline">Validation System</div>
      <p style="font-size: 18px; color: #fff; margin-top: 20px; text-transform: none;">
        Connect with your academic community and access the ISO 21001 Survey System.
      </p>
    </div>

    <!-- Right Side: Form -->
    <div class="form-side">
      <h2 id="loginTitle">Login</h2>
      <form id="loginForm" method="post" action="{{ route('student.login.post') }}">
        @csrf

        <!-- Session messages -->
        @if(session('success'))
        <div style="padding: 10px; margin-bottom: 15px; background: rgba(76, 175, 80, 0.2); border-left: 3px solid #4CAF50; border-radius: 4px; color: #4CAF50; font-size: 14px;">
          {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div style="padding: 10px; margin-bottom: 15px; background: rgba(244, 67, 54, 0.2); border-left: 3px solid #f44336; border-radius: 4px; color: #f44336; font-size: 14px;">
          {{ session('error') }}
        </div>
        @endif

        <div class="form-group">
          <label id="idLabel">Username, Student ID, or Email</label>
          <input type="text" name="student_id" required placeholder="Enter your username, student ID, or email">
          <div class="error-message">Please enter your username, student ID, or email</div>
        </div>

        <div class="form-group">
          <label>Password</label>
          <input type="password" name="password" required placeholder="Enter your password">
          <div class="error-message">Please enter your password</div>
        </div>

        <button type="submit" class="login-btn">Log In</button>

        <div class="forgot-password">
          <a href="{{ route('password.request') }}">Forgot password?</a>
        </div>

        <a href="{{ route('student.register') }}" class="create-account-btn">Create new account</a>

        <!-- Troubleshooting link -->
        <div style="margin-top: 15px; font-size: 12px; color: rgba(255,255,255,0.7);">
          Having login issues? <a href="{{ route('student.clear-sessions') }}" style="color: #4285f4; text-decoration: none;">Clear all sessions</a>
        </div>
      </form>
    </div>
  </div>

  <script>
    // Custom Modal Function
    function showCustomModal(message, icon = '✓', title = 'Notification', isSuccess = true) {
      // Remove any existing modal
      const existingModal = document.querySelector('.custom-modal-overlay');
      if (existingModal) {
        existingModal.remove();
      }

      const modal = document.createElement('div');
      modal.className = 'custom-modal-overlay';

      modal.innerHTML = `
        <div class="custom-modal-content">
          <div class="custom-modal-icon">${icon}</div>
          <div class="custom-modal-title">${title}</div>
          <div class="custom-modal-message">${message}</div>
          <button class="custom-modal-button" onclick="this.closest('.custom-modal-overlay').remove()">
            OK
          </button>
        </div>
      `;

      document.body.appendChild(modal);

      // Auto-close after 5 seconds for success messages
      if (isSuccess) {
        setTimeout(() => {
          if (modal.parentElement) {
            modal.remove();
          }
        }, 5000);
      }
    }

    // Enhanced form validation and submission
    document.getElementById("loginForm").addEventListener("submit", function(e) {
      e.preventDefault(); // Prevent default form submission

      const formGroups = this.querySelectorAll(".form-group");

      // Reset all error states
      formGroups.forEach(group => {
        group.classList.remove('error');
        const errorMsg = group.querySelector('.error-message');
        if (errorMsg) errorMsg.style.display = "none";
      });

      // Validate fields
      const studentId = this.querySelector('input[name="student_id"]');
      const password = this.querySelector('input[name="password"]');

      let valid = true;

      if (!studentId.value.trim()) {
        studentId.closest('.form-group').classList.add('error');
        valid = false;
      }

      if (!password.value.trim()) {
        password.closest('.form-group').classList.add('error');
        valid = false;
      }

      if (valid) {
        // Submit form via AJAX
        const formData = new FormData(this);

        fetch(this.action, {
          method: 'POST',
          body: formData,
          credentials: 'include', // Important: Include cookies in request
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.message && data.redirect) {
            // Show success message and redirect
            showCustomModal(data.message, '✓', 'Success', true);
            setTimeout(() => {
              window.location.href = data.redirect;
            }, 1500);
          } else if (data.errors) {
            // Show validation errors
            Object.keys(data.errors).forEach(key => {
              const errorMsg = data.errors[key][0];
              const field = document.querySelector(`[name="${key}"]`);
              if (field) {
                const formGroup = field.closest('.form-group');
                formGroup.classList.add('error');
                const errorDiv = formGroup.querySelector('.error-message');
                if (errorDiv) {
                  errorDiv.textContent = errorMsg;
                  errorDiv.style.display = 'block';
                }
              }
            });
          } else if (data.message) {
            // Show error message
            showCustomModal(data.message, '⚠️', 'Error', false);
          }
        })
        .catch(error => {
          console.error('Login error:', error);
          showCustomModal('An error occurred during login. Please try again.', '❌', 'Error', false);
        });
      }
    });

    // Login automatically detects admin or student based on credentials

    // Add entrance animation on load
    document.addEventListener('DOMContentLoaded', function() {
       console.log('Login page loaded with Facebook-inspired design');
     });
  </script>
</body>
</html>
