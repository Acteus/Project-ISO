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

  <script nonce="{{ $cspNonce ?? '' }}">
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

    /* Loading overlay shown on submit to transition while navigating */
    .loading-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.85);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 9999;
      color: #fff;
      text-align: center;
      backdrop-filter: blur(4px);
    }

    .loading-overlay.show {
      display: flex;
      animation: fadeIn 0.25s ease forwards;
    }

    .loading-box {
      background: linear-gradient(135deg, rgba(66,133,244,0.25), rgba(255,215,0,0.25));
      border: 1px solid rgba(255,255,255,0.15);
      border-radius: 16px;
      padding: 28px 32px;
      box-shadow: 0 20px 60px rgba(0,0,0,0.35);
      min-width: 260px;
      max-width: 90vw;
    }

    .spinner {
      width: 56px;
      height: 56px;
      border: 4px solid rgba(255,255,255,0.25);
      border-top-color: #FFD700;
      border-radius: 50%;
      margin: 0 auto 16px;
      animation: spin 0.9s linear infinite;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    .loading-title {
      font-family: 'Montserrat', sans-serif;
      font-weight: 700;
      letter-spacing: 1px;
      margin-bottom: 6px;
      font-size: 18px;
    }

    .loading-message {
      font-size: 14px;
      color: rgba(255,255,255,0.85);
    }

    .loading-progress {
      width: 100%;
      max-width: 320px;
      height: 10px;
      background: rgba(255,255,255,0.15);
      border-radius: 999px;
      overflow: hidden;
      margin: 14px auto 0;
      box-shadow: inset 0 0 0 1px rgba(255,255,255,0.1);
    }

    .loading-progress-bar {
      height: 100%;
      width: 0%;
      background: linear-gradient(90deg, #FFD700, #fff);
      border-radius: 999px;
      transition: width 0.1s linear;
    }

    /* Subtle fade-out on the card to complement navigation */
    .login-container.fade-out {
      animation: fadeCardOut 0.25s ease forwards;
    }

    @keyframes fadeCardOut {
      to {
        opacity: 0;
        transform: translateY(10px) scale(0.99);
      }
    }
  </style>
</head>
<body>
  <!-- Loading Overlay -->
  <div id="loadingOverlay" class="loading-overlay" aria-hidden="true" aria-live="polite">
    <div class="loading-box" role="status">
      <div class="spinner" aria-hidden="true"></div>
      <div class="loading-title">Signing you in…</div>
      <div class="loading-message">Please wait while we take you to your dashboard.</div>
      <div class="loading-progress" aria-hidden="true">
        <div id="loadingProgressBar" class="loading-progress-bar"></div>
      </div>
    </div>
  </div>
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
        @if ($errors->any())
        <div style="padding: 10px; margin-bottom: 15px; background: rgba(244, 67, 54, 0.15); border-left: 3px solid #f44336; border-radius: 4px; color: #ffebee; font-size: 14px;">
          <ul style="margin: 0 0 0 18px; padding: 0;">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif
        @if(session('success'))
        <div style="padding: 10px; margin-bottom: 15px; background: rgba(76, 175, 80, 0.2); border-left: 3px solid #4CAF50; border-radius: 4px; color: #4CAF50; font-size: 14px;">
          {{ session('success') }}
        </div>
        @endif

        @if(session('error') && !$errors->any())
        <div style="padding: 10px; margin-bottom: 15px; background: rgba(244, 67, 54, 0.2); border-left: 3px solid #f44336; border-radius: 4px; color: #f44336; font-size: 14px;">
          {{ session('error') }}
        </div>
        @endif

        <div class="form-group">
          <label id="idLabel">Student ID or Email</label>
          <input type="text" name="student_id" required placeholder="Enter your student ID or email">
          <div class="error-message">Please enter your student ID or email</div>
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

  <script nonce="{{ $cspNonce ?? '' }}">
    document.addEventListener('DOMContentLoaded', function() {
      console.log('Login page loaded with Facebook-inspired design');

      const loginForm = document.getElementById('loginForm');
      let isAutoSubmitting = false;
      if (!loginForm) {
        return;
      }

      loginForm.addEventListener('submit', function(e) {
        if (isAutoSubmitting) {
          // Allow natural submission on the second pass
          return;
        }
        // Show overlay immediately to ensure visibility even on fast redirects
        const overlay = document.getElementById('loadingOverlay');
        const container = document.querySelector('.login-container');
        if (overlay) {
          overlay.classList.add('show');
          overlay.setAttribute('aria-hidden', 'false');
        }
        if (container) {
          container.classList.add('fade-out');
        }

        const formGroups = loginForm.querySelectorAll('.form-group');

        formGroups.forEach(group => {
          group.classList.remove('error');
          const errorMsg = group.querySelector('.error-message');
          if (errorMsg) {
            errorMsg.style.display = 'none';
          }
        });

        const studentId = loginForm.querySelector('input[name="student_id"]');
        const password = loginForm.querySelector('input[name="password"]');

        let valid = true;

        if (!studentId.value.trim()) {
          const idGroup = studentId.closest('.form-group');
          if (idGroup) {
            idGroup.classList.add('error');
            const errorDiv = idGroup.querySelector('.error-message');
            if (errorDiv) {
              errorDiv.style.display = 'block';
            }
          }
          valid = false;
        }

        if (!password.value.trim()) {
          const passwordGroup = password.closest('.form-group');
          if (passwordGroup) {
            passwordGroup.classList.add('error');
            const errorDiv = passwordGroup.querySelector('.error-message');
            if (errorDiv) {
              errorDiv.style.display = 'block';
            }
          }
          valid = false;
        }

        if (!valid) {
          e.preventDefault();
          // Hide overlay again if validation fails
          if (overlay) {
            overlay.classList.remove('show');
            overlay.setAttribute('aria-hidden', 'true');
          }
          if (container) {
            container.classList.remove('fade-out');
          }
          return;
        }
        // Valid: force a short loading period with progress bar, then submit
        e.preventDefault();
        const submitBtn = loginForm.querySelector('button[type="submit"]');
        if (submitBtn) {
          submitBtn.disabled = true;
          submitBtn.style.opacity = '0.8';
          submitBtn.style.cursor = 'not-allowed';
          submitBtn.textContent = 'Signing in…';
        }
        const bar = document.getElementById('loadingProgressBar');
        let progress = 0;
        const durationMs = 2000; // 2.0s forced delay
        const stepMs = 50;
        const increment = 100 / (durationMs / stepMs);
        const timer = setInterval(() => {
          progress = Math.min(100, progress + increment);
          if (bar) {
            bar.style.width = progress + '%';
          }
          if (progress >= 100) {
            clearInterval(timer);
            isAutoSubmitting = true;
            loginForm.submit();
          }
        }, stepMs);
      });

      // Also show overlay on any navigation away from the page (e.g., server redirect)
      window.addEventListener('beforeunload', function() {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
          overlay.classList.add('show');
          overlay.setAttribute('aria-hidden', 'false');
        }
      });

      // Ensure social media icons are present; re-init if missing (fallback)
      setTimeout(function() {
        const socialContainer = document.getElementById('social-media-container');
        if (!socialContainer && window.SocialMediaManager && typeof window.SocialMediaManager.init === 'function') {
          try {
            window.SocialMediaManager.init();
          } catch (err) {
            console.warn('Failed to (re)init social media links:', err);
          }
        }
      }, 600);
    });
  </script>
</body>
</html>
