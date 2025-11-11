<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Verify Your Email</title>
  <!-- Google Fonts: Montserrat + Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Poppins:wght@300;400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/functions.css') }}">

  <style>
    /* Override body for proper centering */
    body {
      overflow-y: auto;
    }

    /* Page entrance animation */
    .page-entrance {
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

    /* Override container for verification page */
    .container {
      width: 500px;
      max-width: 95vw;
      opacity: 1;
      margin: 20px auto;
      text-align: center;
    }

    /* Icon styling */
    .email-icon {
      font-size: 64px;
      margin-bottom: 20px;
      animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
      0%, 100% {
        transform: scale(1);
      }
      50% {
        transform: scale(1.1);
      }
    }

    /* Info text styling */
    .info-text {
      color: #f0f0f0;
      font-size: 15px;
      margin-bottom: 20px;
      line-height: 1.6;
    }

    .info-text strong {
      color: #FFD700;
    }

    /* Button styling */
    .resend-btn, .back-btn {
      width: 100%;
      padding: 15px;
      margin: 10px 0;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-block;
      box-sizing: border-box;
    }

    .resend-btn {
      background: linear-gradient(135deg, #4285f4, #ffd700);
      color: white;
    }

    .resend-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(66, 133, 244, 0.4);
    }

    .resend-btn:disabled {
      opacity: 0.6;
      cursor: not-allowed;
      transform: none;
    }

    .back-btn {
      background: rgba(255, 255, 255, 0.1);
      color: #fff;
      border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .back-btn:hover {
      background: rgba(255, 255, 255, 0.2);
      border-color: rgba(255, 255, 255, 0.5);
    }

    .continue-btn {
      width: 100%;
      padding: 15px;
      margin: 10px 0;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-block;
      box-sizing: border-box;
      background: linear-gradient(135deg, #10b981, #059669);
      color: white;
    }

    .continue-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(16, 185, 129, 0.4);
    }

    .continue-btn:disabled {
      opacity: 0.6;
      cursor: not-allowed;
      transform: none;
    }

    /* Success/Error message styling */
    .message {
      padding: 15px;
      border-radius: 8px;
      margin: 20px 0;
      font-size: 14px;
      font-weight: 500;
      animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .message.success {
      background: rgba(76, 175, 80, 0.2);
      border: 1px solid rgba(76, 175, 80, 0.5);
      color: #4CAF50;
    }

    .message.error {
      background: rgba(220, 53, 69, 0.4);
      border: 2px solid rgba(220, 53, 69, 1);
      color: #ffffff;
      box-shadow: 0 4px 20px rgba(220, 53, 69, 0.5), inset 0 0 30px rgba(220, 53, 69, 0.2);
      animation: shake 0.5s ease;
      font-weight: 600;
      padding: 20px;
    }

    .message.error strong {
      color: #ffcccb;
      font-size: 16px;
      display: block;
      margin-bottom: 10px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .message.warning {
      background: rgba(255, 152, 0, 0.35);
      border: 2px solid rgba(255, 193, 7, 0.9);
      color: #fff9e6;
      box-shadow: 0 4px 16px rgba(255, 193, 7, 0.4), inset 0 0 20px rgba(255, 193, 7, 0.1);
      animation: shake 0.5s ease;
      font-weight: 600;
      padding: 18px 20px;
    }

    .message.warning strong {
      color: #ffd54f;
      font-size: 16px;
      display: block;
      margin-bottom: 8px;
    }

    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
      20%, 40%, 60%, 80% { transform: translateX(5px); }
    }

    /* Countdown timer */
    .countdown {
      color: #FFD700;
      font-size: 14px;
      margin-top: 10px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      .container {
        width: 95vw;
        padding: 25px 20px;
      }

      .container h2 {
        font-size: 28px;
        margin-bottom: 15px;
      }

      .email-icon {
        font-size: 48px;
      }

      .info-text {
        font-size: 14px;
      }

      .resend-btn, .back-btn, .continue-btn {
        font-size: 16px;
        min-height: 48px;
        padding: 16px;
      }
    }

    @media (max-width: 480px) {
      .container {
        width: 98vw;
        padding: 20px 15px;
      }

      .container h2 {
        font-size: 24px;
      }
    }
  </style>
</head>
<body>
  <div class="container page-entrance">
    <div class="email-icon">📧</div>

    <h2>Verify Your Email Address</h2>

    @if (session('success'))
      <div class="message success">
        {{ session('success') }}
      </div>
    @endif

    @if (session('error'))
      <div class="message error">
        {{ session('error') }}
      </div>
    @endif

    <p class="info-text">
      We've sent a verification link to <strong>{{ Auth::user()->email }}</strong>
    </p>

    <div id="statusMessage" class="message" style="display: none;"></div>

    <p class="info-text">
      Please check your email and click on the verification link to activate your account.
      If you don't see the email, please check your spam folder.
    </p>

    <p class="info-text" style="margin-top: 15px; padding: 15px; background: rgba(66, 133, 244, 0.1); border-left: 3px solid #4285f4; border-radius: 4px; text-align: left;">
      <strong style="color: #4285f4;">What's Next?</strong><br>
      After verifying your email, you'll be asked to provide consent to continue using the survey system. This ensures your data is protected according to GDPR and ISO 27001 standards.
    </p>

    <button id="resendBtn" class="resend-btn" type="button">
      Resend Verification Email
    </button>

    <div id="countdown" class="countdown" style="display: none;"></div>

    <button id="continueBtn" class="continue-btn" type="button">
      Continue to Next Step
    </button>

    <p class="info-text" style="margin-top: 20px; font-size: 13px;">
      Wrong email address? <a href="{{ route('student.logout') }}" style="color: #FFD700; text-decoration: underline;">Logout</a> and register again.
    </p>
  </div>

  <script nonce="{{ $cspNonce ?? '' }}">
    let countdownTimer = null;
    let countdownSeconds = 60;

    function resendVerificationEmail() {
      const btn = document.getElementById('resendBtn');
      const statusMessage = document.getElementById('statusMessage');
      const countdownDiv = document.getElementById('countdown');

      // Disable button
      btn.disabled = true;
      btn.textContent = 'Sending...';

      // Hide any previous messages
      statusMessage.style.display = 'none';

      fetch('{{ route("verification.send") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'include'
      })
      .then(response => response.json())
      .then(data => {
        // Show success message
        statusMessage.className = 'message success';
        statusMessage.textContent = data.message || 'Verification email sent successfully!';
        statusMessage.style.display = 'block';

        // Start countdown
        startCountdown();
      })
      .catch(error => {
        console.error('Error:', error);

        // Show error message
        statusMessage.className = 'message error';
        statusMessage.textContent = 'Failed to send verification email. Please try again.';
        statusMessage.style.display = 'block';

        // Re-enable button
        btn.disabled = false;
        btn.textContent = 'Resend Verification Email';
      });
    }

    function startCountdown() {
      const btn = document.getElementById('resendBtn');
      const countdownDiv = document.getElementById('countdown');

      countdownSeconds = 60;
      countdownDiv.style.display = 'block';

      countdownTimer = setInterval(() => {
        countdownSeconds--;
        countdownDiv.textContent = `You can resend the email in ${countdownSeconds} seconds`;

        if (countdownSeconds <= 0) {
          clearInterval(countdownTimer);
          countdownDiv.style.display = 'none';
          btn.disabled = false;
          btn.textContent = 'Resend Verification Email';
        }
      }, 1000);
    }

    // Check verification status and continue
    function checkVerificationAndContinue() {
      console.log('Continue button clicked');
      const btn = document.getElementById('continueBtn');
      const statusMessage = document.getElementById('statusMessage');
      
      if (!btn || !statusMessage) {
        console.error('Button or status message element not found');
        return;
      }

      const originalText = btn.textContent;

      // Disable button immediately
      btn.disabled = true;
      btn.textContent = 'Checking...';

      // Hide any previous messages
      statusMessage.style.display = 'none';
      statusMessage.className = '';

      // Check verification status via AJAX
      const checkUrl = '{{ route("verification.check") }}';
      console.log('Checking verification at:', checkUrl);

      fetch(checkUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        },
        credentials: 'include'
      })
      .then(response => {
        console.log('Response status:', response.status, response.statusText);
        // Parse JSON response - even error responses (403) return JSON
        return response.json().then(data => {
          console.log('Response data:', data);
          return { status: response.status, ok: response.ok, data: data };
        }).catch(err => {
          console.error('JSON parse error:', err);
          throw new Error('Failed to parse server response. Please try again.');
        });
      })
      .then(result => {
        console.log('Processing result:', result);
        // Check if email is verified (response.ok = true AND data.verified = true)
        if (result.ok === true && result.data && result.data.verified === true) {
          console.log('Email is verified, redirecting...');
          // Email is verified - redirect to appropriate page
          if (result.data.redirect) {
            // Show success message briefly before redirecting
            statusMessage.className = 'message success';
            statusMessage.innerHTML = '<strong>✓</strong> ' + (result.data.message || 'Redirecting...');
            statusMessage.style.display = 'block';
            
            // Redirect after short delay
            setTimeout(() => {
              window.location.href = result.data.redirect;
            }, 800);
            return;
          }
        }
        
        console.log('Email not verified');
        // Email not verified (403 response or verified=false)
        // Extract error message from response
        const errorMessage = (result.data && result.data.message) || 
                           (result.data && result.data.error) || 
                           'Your email address has not been verified yet. Please check your inbox and click the verification link in the email we sent you.';
        throw new Error(errorMessage);
      })
      .catch(error => {
        console.error('Verification check error:', error);
        
        // Show error alert with prominent styling that matches dark background
        statusMessage.className = 'message error';
        const errorMsg = error.message || 'Your email address has not been verified yet. Please check your inbox and click the verification link in the email we sent you.';
        statusMessage.innerHTML = '<strong>⚠️ Email Not Verified</strong><div style="margin-top: 10px; line-height: 1.7; font-size: 15px;">' + errorMsg + '</div>';
        statusMessage.style.display = 'block';
        statusMessage.style.opacity = '1';
        statusMessage.style.visibility = 'visible';

        // Scroll to message to ensure it's visible
        setTimeout(() => {
          statusMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 100);

        // Re-enable button
        btn.disabled = false;
        btn.textContent = originalText;
      });
    }

    // Initialize event listeners on page load (CSP-compliant - no inline handlers)
    document.addEventListener('DOMContentLoaded', function() {
      // Attach event listener to resend button
      const resendBtn = document.getElementById('resendBtn');
      if (resendBtn) {
        resendBtn.addEventListener('click', function(e) {
          e.preventDefault();
          resendVerificationEmail();
        });
      }

      // Attach event listener to continue button
      const continueBtn = document.getElementById('continueBtn');
      if (continueBtn) {
        continueBtn.addEventListener('click', function(e) {
          e.preventDefault();
          checkVerificationAndContinue();
        });
      }

      // Auto-hide session messages after 5 seconds
      const messages = document.querySelectorAll('.message');
      messages.forEach(msg => {
        if (msg.style.display !== 'none') {
          setTimeout(() => {
            msg.style.opacity = '0';
            msg.style.transition = 'opacity 0.5s ease';
            setTimeout(() => {
              msg.style.display = 'none';
            }, 500);
          }, 5000);
        }
      });
    });
  </script>
</body>
</html>
