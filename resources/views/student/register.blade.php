<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Registration</title>
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
      opacity: 1;
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

    .form-group.error input,
    .form-group.error select {
      border-color: #dc3545;
      box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }

    .form-group.error .error-message {
      display: block;
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
          <div class="acknowledge">
            <input type="checkbox" name="acknowledge" value="1" required>
            <span>I have read and understood the acknowledgement letter.</span>
          </div>
          <div class="error-message">You must acknowledge before proceeding</div>
        </div>

        <button type="submit" class="continue-btn">Register & Continue to Survey</button>
      </form>
    </div>

    <div class="text-center" style="margin-top: 20px;">
      <p>Already have an account? <a href="{{ route('student.login') }}">Login here</a></p>
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

          // Also add a manual test function for debugging
          window.testUpdateSections = function() {
              console.log('Manual test of updateSections');
              // Set grade 11
              document.querySelector('input[name="year"][value="11"]').checked = true;
              updateSections();
          };
      });

      // Enhanced form validation with visual feedback
      document.addEventListener('DOMContentLoaded', function() {
          const studentForm = document.getElementById("studentForm");
          if (studentForm) {
              studentForm.addEventListener("submit", function(e) {
                  e.preventDefault(); // Prevent default form submission

                  let valid = true;
                  const formGroups = this.querySelectorAll(".form-group");

                  // Reset all error states
                  formGroups.forEach(group => {
                      group.classList.remove('error');
                      const errorMsg = group.querySelector('.error-message');
                      if (errorMsg) errorMsg.style.display = "none";
                  });

                  // Validate each field
                  const fields = this.querySelectorAll("input[required], select[required]");

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

                  if (valid) {
                      console.log('Form validation passed, submitting...');

                      try {
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
                              }
                          })
                          .catch(error => {
                              console.error('Network error during form submission:', error);
                              alert('Network error: ' + error.message);
                          });
                      } catch (error) {
                          console.error('JavaScript error during form submission:', error);
                          alert('JavaScript error: ' + error.message);
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
