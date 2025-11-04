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
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
    }

    .resend-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
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

    /* Success/Error message styling */
    .message {
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 20px;
      font-size: 14px;
      font-weight: 500;
    }

    .message.success {
      background: rgba(76, 175, 80, 0.2);
      border: 1px solid rgba(76, 175, 80, 0.5);
      color: #4CAF50;
    }

    .message.error {
      background: rgba(244, 67, 54, 0.2);
      border: 1px solid rgba(244, 67, 54, 0.5);
      color: #ff6b6b;
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

      .resend-btn, .back-btn {
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

    <div id="statusMessage" class="message" style="display: none;"></div>

    <p class="info-text">
      We've sent a verification link to <strong>{{ Auth::user()->email }}</strong>
    </p>

    <p class="info-text">
      Please check your email and click on the verification link to activate your account.
      If you don't see the email, please check your spam folder.
    </p>

    <button id="resendBtn" class="resend-btn" onclick="resendVerificationEmail()">
      Resend Verification Email
    </button>

    <div id="countdown" class="countdown" style="display: none;"></div>

    <a href="{{ route('survey.landing') }}" class="back-btn">Go to Home</a>

    <p class="info-text" style="margin-top: 20px; font-size: 13px;">
      Wrong email address? <a href="{{ route('student.logout') }}" style="color: #FFD700; text-decoration: underline;">Logout</a> and register again.
    </p>
  </div>

  <script>
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

    // Auto-hide messages after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
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
