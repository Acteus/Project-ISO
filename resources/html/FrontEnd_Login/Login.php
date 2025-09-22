<?php
// frontpage.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Registration</title>
  <!-- Google Fonts: Montserrat + Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Poppins:wght@300;400&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      padding: 0;
      min-height: 100vh;
      width: 100vw;
      overflow: hidden;
      display: flex;
      justify-content: center;
      align-items: center;
      background: 
        linear-gradient(135deg, rgba(66,133,244,0.45), rgba(255,215,0,0.35)),
        url('JoseRizalUniversityy.jpg') no-repeat center center fixed;
      background-size: cover;
      font-family: 'Poppins', Arial, sans-serif;
      color: white;
      text-align: center;
    }
    .container {
      background: rgba(0, 0, 0, 0.55);
      padding: 30px 40px;
      border-radius: 20px;
      box-shadow: 0 10px 35px rgba(0,0,0,0.3);
      backdrop-filter: blur(6px);
      width: 400px;
      max-height: 80vh; /* 👈 Limit container height */
      overflow: hidden;
      color: #fff;
      text-align: left;
      display: flex;
      flex-direction: column;
    }
    .container h2 {
      font-family: 'Montserrat', sans-serif;
      font-size: 32px;
      font-weight: 700;
      letter-spacing: 2px;
      margin-bottom: 20px;
      background: linear-gradient(90deg, #FFD700, #ffffff);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      text-shadow: 0 4px 12px rgba(0,0,0,0.3);
      text-align: center;
      flex-shrink: 0;
    }
    .form-content {
      flex-grow: 1;
      overflow-y: auto; /* 👈 Scrollable form area */
      padding-right: 8px; /* space for scrollbar */
    }
    .form-content::-webkit-scrollbar {
      width: 6px;
    }
    .form-content::-webkit-scrollbar-thumb {
      background: rgba(255,255,255,0.3);
      border-radius: 10px;
    }
    label {
      display: block;
      margin-top: 14px;
      font-weight: 400;
      font-family: 'Poppins', Arial, sans-serif;
      font-size: 16px;
      color: #f0f0f0;
      letter-spacing: 1px;
      text-shadow: 0 2px 6px rgba(0,0,0,0.4);
    }
    input, select {
      width: 100%;
      padding: 10px;
      margin-top: 6px;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-family: 'Poppins', Arial, sans-serif;
      background: rgba(255,255,255,0.15);
      color: #fff;
      box-shadow: 0 2px 8px rgba(66,133,244,0.12);
      outline: none;
      margin-bottom: 4px;
    }
    input::placeholder {
      color: #e0e0e0;
      opacity: 1;
    }
    select {
      background: rgba(255,255,255,0.18);
      color: #222;
    }
    .year-check {
      display: flex;
      justify-content: space-between;
      margin-top: 10px;
      gap: 10px;
    }
    .year-check label {
      background: rgba(66,133,244,0.12);
      padding: 6px 12px;
      border-radius: 8px;
      cursor: pointer;
      color: #fff;
      font-size: 15px;
      transition: background 0.2s;
    }
    .year-check input[type="radio"] {
      accent-color: #FFD700;
      margin-right: 6px;
    }
    .acknowledge {
      margin-top: 18px;
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 15px;
      color: #FFD700;
      font-family: 'Poppins', Arial, sans-serif;
    }
    .acknowledge input[type="checkbox"] {
      accent-color: #4285F4;
    }
    .google-btn, .continue-btn {
      margin-top: 20px;
      border: none;
      padding: 14px 36px;
      border-radius: 10px;
      width: 100%;
      font-size: 18px;
      font-family: 'Montserrat', sans-serif;
      font-weight: 600;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      display: inline-block;
    }
    .google-btn {
      background: linear-gradient(90deg, #C0C0C0, #E0E0E0);
      color: #222;
      cursor: not-allowed;
      box-shadow: 0 4px 12px rgba(66,133,244,0.4);
    }
    .google-btn:hover {
      background: linear-gradient(90deg, #4285F4, #2c6cd6);
      color: #fff;
    }
    .continue-btn {
      background: linear-gradient(90deg, #FFD700, #FFA500);
      color: #222;
      cursor: pointer;
      box-shadow: 0 4px 12px rgba(255,215,0,0.4);
    }
    .continue-btn:hover {
      background: linear-gradient(90deg, #FFC107, #FF9800);
      color: #fff;
      transform: translateY(-3px);
      box-shadow: 0 6px 16px rgba(255,215,0,0.6);
    }
    .error {
      color: #ff8080;
      font-size: 13px;
      margin-top: 4px;
      display: none;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Sign Up</h2>
    <div class="form-content">
      <form id="studentForm" method="post" action="nextpage.php">
        <label>First Name</label>
        <input type="text" name="firstname" required placeholder="Enter your first name">
        <div class="error">This is required</div>

        <label>Last Name</label>
        <input type="text" name="lastname" required placeholder="Enter your last name">
        <div class="error">This is required</div>

        <label>Year Level</label>
        <div class="year-check">
          <label><input type="radio" name="year" value="11" onclick="updateSections()"> Grade 11</label>
          <label><input type="radio" name="year" value="12" onclick="updateSections()"> Grade 12</label>
        </div>
        <div class="error">This is required</div>

        <label>Section</label>
        <select name="section" id="section" required>
          <option value="">-- Select Year First --</option>
        </select>
        <div class="error">This is required</div>

        <label>Student ID</label>
        <input type="text" name="studentid" required placeholder="Enter your student ID">
        <div class="error">This is required</div>

        <div class="acknowledge">
          <input type="checkbox" name="acknowledge" required>
          <span>I have read and understood the acknowledgement letter.</span>
        </div>
        <div class="error">This is required</div>

        <button type="button" class="google-btn">Sign up with Google</button>
        <button type="submit" class="continue-btn">Continue</button>
      </form>
    </div>
  </div>

  <script>
    function updateSections() {
      let year = document.querySelector('input[name="year"]:checked').value;
      let sectionDropdown = document.getElementById("section");
      sectionDropdown.innerHTML = ""; // clear old options

      if (year === "11") {
        ["C11a", "C11b", "C11c"].forEach(sec => {
          let option = document.createElement("option");
          option.value = sec;
          option.textContent = sec;
          sectionDropdown.appendChild(option);
        });
      } else if (year === "12") {
        ["C12a", "C12b", "C12c"].forEach(sec => {
          let option = document.createElement("option");
          option.value = sec;
          option.textContent = sec;
          sectionDropdown.appendChild(option);
        });
      }
    }

    // Show custom "This is required" messages
    document.getElementById("studentForm").addEventListener("submit", function(e) {
      let valid = true;
      const fields = this.querySelectorAll("input[required], select[required]");
      const errors = this.querySelectorAll(".error");

      errors.forEach(err => err.style.display = "none"); // reset

      fields.forEach((field, i) => {
        if ((field.type === "radio" && !document.querySelector('input[name="year"]:checked')) ||
            (field.type === "checkbox" && !field.checked) ||
            (field.type !== "radio" && field.type !== "checkbox" && !field.value.trim())) {
          errors[i].style.display = "block";
          valid = false;
        }
      });

      if (!valid) e.preventDefault();
    });
  </script>
</body>
</html>
