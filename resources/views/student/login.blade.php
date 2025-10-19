<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Login</title>
  <!-- Google Fonts: Montserrat + Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Poppins:wght@300;400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/functions.css') }}">
  <script src="{{ asset('js/Socmedlinks.js') }}"></script>

  <style>
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

    /* Smooth entrance for container */
    .container {
      opacity: 0;
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

    .login-header {
      text-align: center;
      margin-bottom: 30px;
    }

    .login-header h2 {
      color: #333;
      margin-bottom: 10px;
    }

    .login-header p {
      color: #666;
      font-size: 16px;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="login-header">
      <h2>Student Login</h2>
      <p>Welcome back to the ISO 21001 Survey System</p>
    </div>

    <div class="form-content">
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

        <button type="submit" class="continue-btn">Login</button>
      </form>
    </div>

    <div class="text-center" style="margin-top: 20px;">
      <p>Don't have an account? <a href="{{ route('student.register') }}">Register here</a></p>
      <p><a href="{{ route('home') }}">← Back to Home</a></p>
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

    // Page entrance animation
    document.addEventListener('DOMContentLoaded', function() {
      const container = document.querySelector('.container');

      // Add entrance animation
      setTimeout(() => {
        container.classList.add('page-entrance');
        container.style.opacity = '1';
      }, 100);

      console.log('Login page loaded with entrance animation');
    });
  </script>
</body>
</html>
