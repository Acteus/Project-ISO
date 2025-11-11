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

    /* Select styling for cross-browser legibility */
    select {
      color: #222;
      background: rgba(255, 255, 255, 0.95);
    }

    select option {
      background: #fff;
      color: #222;
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
      <form
        id="studentForm"
        method="post"
        action="{{ route('student.register.post') }}"
        data-old-year="{{ old('year') }}"
        data-old-section="{{ old('section') }}"
      >
        @csrf
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
          <label>First Name</label>
          <input type="text" name="firstname" required placeholder="Enter your first name" value="{{ old('firstname') }}">
          <div class="error-message">This field is required</div>
        </div>

        <div class="form-group">
          <label>Last Name</label>
          <input type="text" name="lastname" required placeholder="Enter your last name" value="{{ old('lastname') }}">
          <div class="error-message">This field is required</div>
        </div>

        <div class="form-group">
          <label>Email Address</label>
          <input type="email" name="email" required placeholder="your.email@example.com" value="{{ old('email') }}">
          <div class="error-message">Please enter a valid email address</div>
        </div>

        <div class="form-group">
          <label>Year Level</label>
          <div class="year-check">
            <label><input type="radio" name="year" value="11" {{ old('year') == '11' ? 'checked' : '' }}> Grade 11</label>
            <label><input type="radio" name="year" value="12" {{ old('year') == '12' ? 'checked' : '' }}> Grade 12</label>
          </div>
          <div class="error-message">Please select your year level</div>
        </div>

        <div class="form-group">
          <label>Section</label>
          <select name="section" id="section" required>
            @php
              $oldYear = old('year');
              $oldSection = old('section');
              $sections11 = ['C11a','C11b','C11c'];
              $sections12 = ['C12a','C12b','C12c'];
            @endphp
            <option value="">{{ $oldYear ? '-- Select Section --' : '-- Select Year First --' }}</option>
            @if($oldYear === '11')
              @foreach($sections11 as $sec)
                <option value="{{ $sec }}" {{ $oldSection === $sec ? 'selected' : '' }}>{{ $sec }}</option>
              @endforeach
            @elseif($oldYear === '12')
              @foreach($sections12 as $sec)
                <option value="{{ $sec }}" {{ $oldSection === $sec ? 'selected' : '' }}>{{ $sec }}</option>
              @endforeach
            @endif
          </select>
          <div class="error-message">Please select your section</div>
        </div>

        <div class="form-group">
          <label>Student ID</label>
          <input type="text" name="studentid" required placeholder="Enter your student ID" value="{{ old('studentid') }}">
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

  <script src="{{ asset('js/student-register.js') }}" defer></script>
</body>
</html>
