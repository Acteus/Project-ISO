<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Jose Rizal University - Student Login</title>
  <!-- Google Fonts: Montserrat + Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Poppins:wght@300;400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/functions.css') }}">
  <script src="{{ asset('js/Socmedlinks.js') }}"></script>

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
        width: 90vw;
        min-height: auto;
      }

      .branding, .form-side {
        padding: 30px 20px;
      }

      .branding h1 {
        font-size: 36px;
      }

      .branding .tagline {
        font-size: 18px;
      }
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
      <h2>Student Login</h2>
      <form id="loginForm" method="post" action="{{ route('student.login.post') }}">
        @csrf
        <div class="form-group">
          <label>Student ID</label>
          <input type="text" name="student_id" required placeholder="Enter your student ID">
          <div class="error-message">Please enter your student ID</div>
        </div>

        <div class="form-group">
          <label>Password</label>
          <input type="password" name="password" required placeholder="Enter your password">
          <div class="error-message">Please enter your password</div>
        </div>

        <button type="submit" class="login-btn">Log In</button>

        <div class="forgot-password">
          <a href="#">Forgot password?</a>
        </div>

        <a href="{{ route('student.register') }}" class="create-account-btn">Create new account</a>
      </form>
    </div>
  </div>

  <script>
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
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.message && data.redirect) {
            // Show success message and redirect
            alert(data.message);
            window.location.href = data.redirect;
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
            alert(data.message);
          }
        })
        .catch(error => {
          console.error('Login error:', error);
          alert('An error occurred during login. Please try again.');
        });
      }
    });

    // Add entrance animation on load
    document.addEventListener('DOMContentLoaded', function() {
       console.log('Login page loaded with Facebook-inspired design');
     });
  </script>
</body>
</html>
