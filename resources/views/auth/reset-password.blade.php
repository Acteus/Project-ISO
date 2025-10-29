<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password - Jose Rizal University</title>
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
    .reset-container {
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

    .reset-container h2 {
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

    .reset-container p {
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

    /* Responsive design */
    @media (max-width: 768px) {
      .reset-container {
        width: 90vw;
        padding: 30px 20px;
      }

      .reset-container h2 {
        font-size: 28px;
      }
    }
  </style>
</head>
<body>
  <div class="reset-container">
    <h2>Reset Password</h2>
    <p>Please enter your new password below.</p>

    <form id="resetPasswordForm" method="POST" action="{{ route('password.update') }}">
      @csrf
      <input type="hidden" name="token" value="{{ $token }}">
      <input type="hidden" name="email" value="{{ $email }}">

      <div class="form-group">
        <label>Email Address</label>
        <input type="email" name="email_display" value="{{ $email }}" disabled>
      </div>

      <div class="form-group">
        <label>New Password</label>
        <input type="password" name="password" required placeholder="Enter new password" minlength="8">
        <div class="error-message">Password must be at least 8 characters</div>
      </div>

      <div class="form-group">
        <label>Confirm Password</label>
        <input type="password" name="password_confirmation" required placeholder="Confirm new password">
        <div class="error-message">Passwords do not match</div>
      </div>

      <button type="submit" class="submit-btn" id="submitBtn">Reset Password</button>
    </form>
  </div>

  <script>
    document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
      e.preventDefault();

      const submitBtn = document.getElementById('submitBtn');
      const formGroups = this.querySelectorAll('.form-group');
      const password = this.querySelector('input[name="password"]');
      const passwordConfirmation = this.querySelector('input[name="password_confirmation"]');

      // Reset error states
      formGroups.forEach(group => {
        group.classList.remove('error');
      });

      let valid = true;

      // Validate password length
      if (password.value.length < 8) {
        password.closest('.form-group').classList.add('error');
        valid = false;
      }

      // Validate password confirmation
      if (password.value !== passwordConfirmation.value) {
        passwordConfirmation.closest('.form-group').classList.add('error');
        valid = false;
      }

      if (!valid) return;

      // Disable button and show loading
      submitBtn.disabled = true;
      submitBtn.textContent = 'Resetting...';

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
          alert(data.message);
          if (data.redirect) {
            window.location.href = data.redirect;
          }
        } else if (data.errors) {
          Object.keys(data.errors).forEach(key => {
            const field = document.querySelector(`[name="${key}"]`);
            if (field) {
              const formGroup = field.closest('.form-group');
              formGroup.classList.add('error');
              const errorDiv = formGroup.querySelector('.error-message');
              if (errorDiv) {
                errorDiv.textContent = data.errors[key][0];
                errorDiv.style.display = 'block';
              }
            }
          });
        } else if (data.message) {
          alert(data.message);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
      })
      .finally(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Reset Password';
      });
    });
  </script>
</body>
</html>
