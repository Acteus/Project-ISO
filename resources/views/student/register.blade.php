<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Student Registration</title>
  <!-- Google Fonts: Montserrat + Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Poppins:wght@300;400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/functions.css') }}">
  <script src="{{ asset('js/Socmedlinks.js') }}"></script>

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

    /* Override container for registration form */
    .container {
      width: 500px;
      max-width: 95vw;
      max-height: 90vh;
      opacity: 1;
      margin: 20px auto;
    }

    /* Intro paragraph styling */
    .container > p {
      text-align: center;
      color: #f0f0f0;
      font-size: 15px;
      margin-bottom: 20px;
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
    }

    .form-group label:first-child {
      margin-top: 0;
    }

    .form-group.error input,
    .form-group.error select {
      border: 2px solid #dc3545;
      box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }

    .form-group.error .error-message {
      display: block;
    }

    /* Fix input and select box-sizing */
    input, select {
      box-sizing: border-box;
    }

    /* Override select color for better visibility */
    select {
      color: #fff;
    }

    select option {
      background: rgba(0, 0, 0, 0.9);
      color: #fff;
    }

    /* Year check improvements */
    .year-check {
      margin-top: 10px;
      margin-bottom: 5px;
    }

    .year-check label {
      flex: 1;
      text-align: center;
      margin-top: 0;
      font-size: 14px;
    }

    .year-check input[type="radio"] {
      width: auto;
      margin: 0 6px 0 0;
      padding: 0;
    }

    /* Acknowledge checkbox improvements */
    .acknowledge {
      margin-top: 10px;
      align-items: flex-start;
    }

    .acknowledge input[type="checkbox"] {
      width: auto;
      margin: 3px 0 0 0;
      padding: 0;
      flex-shrink: 0;
    }

    .acknowledge span {
      flex: 1;
      line-height: 1.4;
    }

    /* Link styling */
    .text-center {
      text-align: center;
      padding-top: 10px;
      border-top: 1px solid rgba(255,255,255,0.2);
      margin-top: 10px;
    }

    .text-center p {
      color: #f0f0f0;
      font-size: 14px;
      margin: 0;
    }

    .text-center a {
      color: #FFD700;
      text-decoration: none;
      font-weight: 500;
      transition: color 0.2s ease;
    }

    .text-center a:hover {
      color: #FFC107;
      text-decoration: underline;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      .container {
        width: 95vw;
        padding: 25px 20px;
        max-height: 85vh;
        overflow-y: auto;
      }

      .container h2 {
        font-size: 28px;
        margin-bottom: 15px;
      }

      .container > p {
        font-size: 14px;
        margin-bottom: 15px;
      }

      .year-check {
        flex-direction: column;
        gap: 8px;
      }

      .year-check label {
        text-align: left;
        font-size: 14px;
      }

      .form-group input, .form-group select {
        font-size: 16px;
        min-height: 44px;
        padding: 12px;
      }

      .continue-btn {
        font-size: 16px;
        min-height: 48px;
        padding: 16px;
        margin-top: 20px;
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
  <div class="container">
    <h2>Student Registration</h2>
    <p>Please fill in your information to access the ISO 21001 Survey System.</p>

    <div class="form-content">
      <form id="studentForm" method="post" action="{{ route('student.register.post') }}">
        @csrf
        <div class="form-group">
          <label>First Name</label>
          <input type="text" name="firstname" required placeholder="Enter your first name">
          <div class="error-message">This field is required</div>
        </div>

        <div class="form-group">
          <label>Last Name</label>
          <input type="text" name="lastname" required placeholder="Enter your last name">
          <div class="error-message">This field is required</div>
        </div>

        <div class="form-group">
          <label>Email Address</label>
          <input type="email" name="email" required placeholder="your.email@example.com">
          <div class="error-message">Please enter a valid email address</div>
        </div>

        <div class="form-group">
          <label>Year Level</label>
          <div class="year-check">
            <label><input type="radio" name="year" value="11"> Grade 11</label>
            <label><input type="radio" name="year" value="12"> Grade 12</label>
          </div>
          <div class="error-message">Please select your year level</div>
        </div>

        <div class="form-group">
          <label>Section</label>
          <select name="section" id="section" required>
            <option value="">-- Select Year First --</option>
          </select>
          <div class="error-message">Please select your section</div>
        </div>

        <div class="form-group">
          <label>Student ID</label>
          <input type="text" name="studentid" required placeholder="Enter your student ID">
          <div class="error-message">This field is required</div>
        </div>

        <div class="form-group">
          <label>Password</label>
          <input type="password" name="password" required placeholder="Create a password">
          <div class="error-message">Password is required (minimum 8 characters)</div>
        </div>

        <div class="form-group">
          <label>Confirm Password</label>
          <input type="password" name="password_confirmation" required placeholder="Confirm your password">
          <div class="error-message">Passwords do not match</div>
        </div>

        <div class="form-group">
          <div class="acknowledge">
            <input type="checkbox" name="acknowledge" value="1" required>
            <span>I have read and understood the acknowledgement letter.</span>
          </div>
          <div class="error-message">You must acknowledge before proceeding</div>
        </div>

        <button type="submit" class="continue-btn">Register & Continue to Survey</button>
      </form>

      <div class="text-center">
        <p>Already have an account? <a href="{{ route('student.login') }}">Login here</a></p>
      </div>
    </div>
  </div>

  <script>
      console.log('Registration form loaded');

      function updateSections() {
          console.log('updateSections called');
          let yearRadio = document.querySelector('input[name="year"]:checked');
          if (!yearRadio) {
              console.log('No year selected');
              return;
          }

          let year = yearRadio.value;
          console.log('Selected year:', year);

          let sectionDropdown = document.getElementById("section");
          if (!sectionDropdown) {
              console.error('Section dropdown not found');
              return;
          }

          // Clear old options but keep the first one
          sectionDropdown.innerHTML = '<option value="">-- Select Section --</option>';

          if (year === "11") {
              console.log('Adding Grade 11 sections');
              const sections = ["C11a", "C11b", "C11c"];
              sections.forEach(sec => {
                  let option = document.createElement("option");
                  option.value = sec;
                  option.textContent = sec;
                  sectionDropdown.appendChild(option);
                  console.log('Added section:', sec);
              });
          } else if (year === "12") {
              console.log('Adding Grade 12 sections');
              const sections = ["C12a", "C12b", "C12c"];
              sections.forEach(sec => {
                  let option = document.createElement("option");
                  option.value = sec;
                  option.textContent = sec;
                  sectionDropdown.appendChild(option);
                  console.log('Added section:', sec);
              });
          }

          console.log('Final sections count:', sectionDropdown.children.length);
          console.log('Section options:', Array.from(sectionDropdown.options).map(opt => opt.value));
      }

      // Add event listeners to radio buttons
      document.addEventListener('DOMContentLoaded', function() {
          const yearRadios = document.querySelectorAll('input[name="year"]');
          console.log('Found', yearRadios.length, 'year radio buttons');

          yearRadios.forEach(radio => {
              radio.addEventListener('change', function() {
                  console.log('Year selection changed:', this.value);
                  updateSections();
              });
              console.log('Event listener attached to radio button:', radio.value);
          });

          // Real-time validation removed - all validation happens on submit only
          // This allows users to freely enter their credentials without interference

          // Also add a manual test function for debugging
          window.testUpdateSections = function() {
              console.log('Manual test of updateSections');
              // Set grade 11
              document.querySelector('input[name="year"][value="11"]').checked = true;
              updateSections();
          };
      });

      // Custom Modal Function
      function showCustomModal(message, icon = '✓', title = 'Notification', isError = false) {
          // Remove any existing modal
          const existingModal = document.querySelector('.custom-modal-overlay');
          if (existingModal) {
              existingModal.remove();
          }

          const modal = document.createElement('div');
          modal.className = 'custom-modal-overlay';
          
          // Use different colors for errors
          const bgGradient = isError 
              ? 'linear-gradient(135deg, #dc3545, #ff6b6b)' 
              : 'linear-gradient(135deg, #4285f4, #ffd700)';

          modal.innerHTML = `
              <div class="custom-modal-content" style="background: ${bgGradient};">
                  <div class="custom-modal-icon">${icon}</div>
                  <div class="custom-modal-title">${title}</div>
                  <div class="custom-modal-message">${message}</div>
                  <button class="custom-modal-button" onclick="this.closest('.custom-modal-overlay').remove()">
                      OK
                  </button>
              </div>
          `;

          document.body.appendChild(modal);
      }

      // Enhanced form validation with visual feedback
      document.addEventListener('DOMContentLoaded', function() {
          const studentForm = document.getElementById("studentForm");
          if (studentForm) {
              studentForm.addEventListener("submit", function(e) {
                  e.preventDefault(); // Prevent default form submission

                  console.log('Form submission started');
                  let valid = true;
                  const formGroups = this.querySelectorAll(".form-group");

                  // Reset all error states
                  formGroups.forEach(group => {
                      group.classList.remove('error');
                      const errorMsg = group.querySelector('.error-message');
                      if (errorMsg) errorMsg.style.display = "none";
                  });

                  // Log all form data for debugging
                  const formData = new FormData(this);
                  console.log('Form data being validated:');
                  for (let [key, value] of formData.entries()) {
                      console.log(`  ${key}: ${value}`);
                  }

                  // Validate each field
                  const fields = this.querySelectorAll("input[required], select[required]");
                  const password = this.querySelector('input[name="password"]');
                  const passwordConfirmation = this.querySelector('input[name="password_confirmation"]');

                  fields.forEach((field, i) => {
                      const formGroup = field.closest('.form-group');

                      if (field.type === "radio" && !document.querySelector('input[name="year"]:checked')) {
                          const yearGroup = document.querySelector('.year-check').closest('.form-group');
                          yearGroup.classList.add('error');
                          valid = false;
                      } else if (field.type === "checkbox" && !field.checked) {
                          formGroup.classList.add('error');
                          valid = false;
                      } else if (field.name === "acknowledge" && !field.checked) {
                          formGroup.classList.add('error');
                          valid = false;
                          console.log('Acknowledge validation failed');
                      } else if (field.type !== "radio" && field.type !== "checkbox" && !field.value.trim()) {
                          formGroup.classList.add('error');
                          valid = false;
                          console.log('Field validation failed for:', field.name);
                      } else {
                          console.log('Field passed validation:', field.name, field.value);
                      }
                  });

                  // Validate password length
                  if (password && password.value.length < 8) {
                      const formGroup = password.closest('.form-group');
                      formGroup.classList.add('error');
                      valid = false;
                      console.log('Password too short');
                  }

                  // Validate password confirmation matches
                  if (password && passwordConfirmation && password.value !== passwordConfirmation.value) {
                      const formGroup = passwordConfirmation.closest('.form-group');
                      formGroup.classList.add('error');
                      valid = false;
                      console.log('Passwords do not match');
                  }

                  if (valid) {
                      console.log('Form validation passed, submitting...');

                      try {
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
                          .then(response => {
                              console.log('Response status:', response.status);
                              console.log('Response ok:', response.ok);

                              // Clone response for error handling
                              const responseClone = response.clone();

                              return response.json().catch(err => {
                                  console.error('JSON parsing error:', err);
                                  return responseClone.text().then(text => {
                                      console.error('Response text:', text);
                                      throw new Error('Invalid JSON response from server');
                                  });
                              });
                          })
                          .then(data => {
                              console.log('Response data:', data);

                              if (data.message && data.redirect) {
                                  // Show verification message with custom styling
                                  const modal = document.createElement('div');
                                  modal.style.cssText = `
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
                                  `;

                                  const modalContent = document.createElement('div');
                                  modalContent.style.cssText = `
                                      background: linear-gradient(135deg, #4285f4, #ffd700);
                                      padding: 40px;
                                      border-radius: 15px;
                                      max-width: 500px;
                                      text-align: center;
                                      color: white;
                                      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
                                  `;

                                  modalContent.innerHTML = `
                                      <div style="font-size: 64px; margin-bottom: 20px;">✉️</div>
                                      <h2 style="margin-bottom: 15px; font-family: 'Montserrat', sans-serif;">Registration Successful!</h2>
                                      <p style="margin-bottom: 20px; line-height: 1.6; font-size: 15px;">
                                          ${data.message}
                                      </p>
                                      <p style="margin-bottom: 25px; font-size: 14px; opacity: 0.9;">
                                          Check your email: <strong>${data.user.email}</strong>
                                      </p>
                                      <button onclick="window.location.href='${data.redirect}'" style="
                                          background: white;
                                          color: #4285f4;
                                          border: none;
                                          padding: 15px 40px;
                                          border-radius: 8px;
                                          font-size: 16px;
                                          font-weight: 600;
                                          cursor: pointer;
                                          transition: all 0.3s ease;
                                      " onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                          Continue
                                      </button>
                                  `;

                                  modal.appendChild(modalContent);
                                  document.body.appendChild(modal);

                                  // Redirect after 10 seconds if user doesn't click
                                  setTimeout(() => {
                                      window.location.href = data.redirect;
                                  }, 10000);
                              } else if (data.errors) {
                                  // Show validation errors
                                  console.log('Validation errors:', data.errors);
                                  
                                  // Collect all error messages
                                  let errorMessages = [];
                                  
                                  Object.keys(data.errors).forEach(key => {
                                      const errorMsg = data.errors[key][0];
                                      console.log(`Error for ${key}:`, errorMsg);
                                      errorMessages.push(errorMsg);
                                      
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
                                  
                                  // Show modal with all error messages
                                  const errorList = errorMessages.map(msg => `• ${msg}`).join('<br>');
                                  showCustomModal(
                                      `<div style="text-align: left; margin: 20px 0;">${errorList}</div>`, 
                                      '⚠️', 
                                      'Please Fix These Errors', 
                                      true
                                  );
                                  
                                  // Scroll to first error
                                  const firstError = document.querySelector('.form-group.error');
                                  if (firstError) {
                                      firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                  }
                              } else {
                                  console.error('Unexpected response format:', data);
                                  showCustomModal('Unexpected response from server. Please check console for details.', '❌', 'Error', true);
                              }
                          })
                          .catch(error => {
                              console.error('Network error during form submission:', error);
                              showCustomModal('Network error: ' + error.message, '❌', 'Network Error', true);
                          });
                      } catch (error) {
                          console.error('JavaScript error during form submission:', error);
                          showCustomModal('JavaScript error: ' + error.message, '❌', 'Error', true);
                      }
                  } else {
                      console.log('Form validation failed');
                  }
              });
          }
      });

      // Page entrance animation
      document.addEventListener('DOMContentLoaded', function() {
          const container = document.querySelector('.container');

          // Add entrance animation (optional)
          setTimeout(() => {
              if (container) {
                  container.classList.add('page-entrance');
              }
          }, 100);

          console.log('Registration page loaded with entrance animation');
      });
  </script>
</body>
</html>
