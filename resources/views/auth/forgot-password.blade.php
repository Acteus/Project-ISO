<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password - Jose Rizal University</title>
  <!-- Google Fonts: Montserrat + Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Poppins:wght@300;400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/functions.css') }}">

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

    /* Main container */
    .forgot-container {
      background: rgba(0, 0, 0, 0.55);
      border-radius: 24px;
      box-shadow: 0 10px 35px rgba(0,0,0,0.3);
      backdrop-filter: blur(6px);
      width: 500px;
      max-width: 95vw;
      padding: 50px 40px;
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

    .forgot-container h2 {
      font-family: 'Montserrat', sans-serif;
      font-size: 32px;
      font-weight: 700;
      letter-spacing: 2px;
      margin-bottom: 20px;
      background: linear-gradient(90deg, #FFD700, #ffffff);
      -webkit-background-clip: text;
      background-clip: text;
      -webkit-text-fill-color: transparent;
      text-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }

    .forgot-container p {
      color: #f0f0f0;
      font-size: 15px;
      margin-bottom: 30px;
      line-height: 1.5;
    }

    /* Form validation styles */
    .error-message {
      color: #ff6b6b;
      font-size: 13px;
      margin-top: 5px;
      display: none;
      text-shadow: 0 1px 2px rgba(0,0,0,0.3);
    }

    .success-message {
      color: #51cf66;
      font-size: 14px;
      padding: 10px;
      background: rgba(81, 207, 102, 0.1);
      border-radius: 8px;
      margin-bottom: 20px;
      display: none;
    }

    .form-group {
      margin-bottom: 20px;
      text-align: left;
    }

    .form-group label {
      display: block;
      color: #f0f0f0;
      font-size: 14px;
      margin-bottom: 8px;
    }

    .form-group input {
      width: 100%;
      padding: 12px 16px;
      border: 2px solid rgba(255,255,255,0.2);
      border-radius: 10px;
      background: rgba(255,255,255,0.1);
      color: white;
      font-size: 15px;
      transition: all 0.3s ease;
      box-sizing: border-box;
    }

    .form-group input:focus {
      border-color: #FFD700;
      background: rgba(255,255,255,0.15);
      outline: none;
    }

    .form-group.error input {
      border: 2px solid #dc3545;
      box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }

    .form-group.error .error-message {
      display: block;
    }

    /* Button styles */
    .submit-btn {
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
    }

    .submit-btn:hover {
      background: linear-gradient(90deg, #2c6cd6, #1a4fa0);
      transform: translateY(-3px);
      box-shadow: 0 6px 16px rgba(66,133,244,0.6);
    }

    .submit-btn:disabled {
      background: rgba(128,128,128,0.5);
      cursor: not-allowed;
      transform: none;
    }

    .back-link {
      text-align: center;
      margin-top: 20px;
      padding-top: 20px;
      border-top: 1px solid rgba(255,255,255,0.2);
    }

    .back-link a {
      color: #FFD700;
      text-decoration: none;
      font-size: 14px;
      transition: color 0.2s ease;
    }

    .back-link a:hover {
      color: #FFC107;
      text-decoration: underline;
    }

    /* Responsive design */
    @media (max-width: 768px) {
      .forgot-container {
        width: 90vw;
        padding: 30px 20px;
      }

      .forgot-container h2 {
        font-size: 28px;
      }
    }
  </style>
</head>
<body>
  <div class="forgot-container">
    <h2>Forgot Password</h2>
    <p>Enter your JRU email address and we'll send you a link to reset your password.</p>

    <div class="success-message" id="successMessage"></div>

    <form id="forgotPasswordForm" method="POST" action="{{ route('password.email') }}">
      @csrf
      <div class="form-group">
        <label>Email Address</label>
        <input type="email" name="email" required placeholder="yourname@my.jru.edu" autocomplete="email">
        <div class="error-message">Please enter a valid @my.jru.edu email address</div>
      </div>

      <button type="submit" class="submit-btn" id="submitBtn">Send Reset Link</button>
    </form>

    <div class="back-link">
      <a href="{{ route('student.login') }}">← Back to Login</a>
    </div>
  </div>

  <script>
    document.getElementById('forgotPasswordForm').addEventListener('submit', function(e) {
      e.preventDefault();

      const submitBtn = document.getElementById('submitBtn');
      const successMessage = document.getElementById('successMessage');
      const formGroup = this.querySelector('.form-group');
      const emailInput = this.querySelector('input[name="email"]');

      // Reset error states
      formGroup.classList.remove('error');
      successMessage.style.display = 'none';

      // Validate email format
      const emailRegex = /^[a-zA-Z0-9._%+-]+@my\.jru\.edu$/;
      if (!emailRegex.test(emailInput.value)) {
        formGroup.classList.add('error');
        return;
      }

      // Disable button and show loading
      submitBtn.disabled = true;
      submitBtn.textContent = 'Sending...';

      // Submit form via AJAX
      const formData = new FormData(this);

      fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.status === 'success') {
          successMessage.textContent = data.message;
          successMessage.style.display = 'block';
          emailInput.value = '';
        } else if (data.errors) {
          formGroup.classList.add('error');
          const errorDiv = formGroup.querySelector('.error-message');
          if (data.errors.email) {
            errorDiv.textContent = data.errors.email[0];
          }
        } else if (data.message) {
          formGroup.classList.add('error');
          const errorDiv = formGroup.querySelector('.error-message');
          errorDiv.textContent = data.message;
        }
      })
      .catch(error => {
        console.error('Error:', error);
        formGroup.classList.add('error');
        const errorDiv = formGroup.querySelector('.error-message');
        errorDiv.textContent = 'An error occurred. Please try again.';
      })
      .finally(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Send Reset Link';
      });
    });
  </script>
</body>
</html>
