<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Consent Required - ISO Quality Education</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .consent-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 800px;
            width: 100%;
            padding: 2.5rem;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .consent-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .consent-header h1 {
            color: #312e81;
            font-size: 2rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .consent-header svg {
            width: 32px;
            height: 32px;
            fill: #4338ca;
        }

        .consent-header p {
            color: #6b7280;
            font-size: 1.1rem;
        }

        .consent-content {
            background: #f8f9fa;
            border: 2px solid #4338ca;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .consent-content h2 {
            color: #312e81;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .consent-content p {
            color: #374151;
            line-height: 1.8;
            margin-bottom: 1rem;
            font-size: 1.05rem;
        }

        .consent-content ul {
            margin-left: 1.5rem;
            margin-bottom: 1rem;
            line-height: 2;
        }

        .consent-content li {
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .consent-content strong {
            color: #312e81;
        }

        .warning-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 1rem;
            margin: 1.5rem 0;
            border-radius: 4px;
        }

        .warning-box p {
            margin: 0;
            color: #856404;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .consent-form {
            background: white;
            border: 2px solid #4338ca;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .consent-checkbox-wrapper {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .consent-checkbox-wrapper input[type="checkbox"] {
            width: 24px;
            height: 24px;
            margin-top: 2px;
            cursor: pointer;
            flex-shrink: 0;
        }

        .consent-checkbox-wrapper label {
            cursor: pointer;
            flex: 1;
            line-height: 1.6;
            color: #374151;
            font-size: 1rem;
        }

        .consent-checkbox-wrapper strong {
            color: #312e81;
        }

        .required-asterisk {
            color: #dc3545;
            font-weight: 700;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 32px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4338ca 0%, #312e81 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(67, 56, 202, 0.4);
        }

        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .btn-outline {
            background: white;
            color: #4338ca;
            border: 2px solid #4338ca;
        }

        .btn-outline:hover {
            background: #f8f9fa;
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: #d1f2eb;
            border: 1px solid #27ae60;
            color: #155724;
        }

        .alert-error {
            background: #f8d7da;
            border: 1px solid #dc3545;
            color: #721c24;
        }

        .alert-info {
            background: #d1ecf1;
            border: 1px solid #17a2b8;
            color: #0c5460;
        }

        @media (max-width: 768px) {
            .consent-container {
                padding: 1.5rem;
            }

            .consent-header h1 {
                font-size: 1.5rem;
            }

            .consent-content {
                padding: 1.5rem;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="consent-container">
        <div class="consent-header">
            <h1>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/>
                </svg>
                Consent Required
            </h1>
            <p>Please provide your consent to continue</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info">
                {{ session('info') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <ul style="margin-left: 1.5rem; margin-top: 0.5rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="consent-content">
            <h2>Data Privacy & Consent (GDPR & ISO 27001)</h2>
            <p>
                <strong>Your privacy is important to us.</strong> This survey system collects and processes data in compliance with GDPR and ISO 27001 standards.
            </p>

            <h3 style="color: #312e81; margin-top: 1.5rem; margin-bottom: 1rem; font-size: 1.2rem;">How We Protect Your Data:</h3>
            <ul>
                <li>Your <strong>student ID and comments</strong> are encrypted using AES-256 encryption</li>
                <li>Only <strong>essential ISO 21001 metrics</strong> are collected (data minimization)</li>
                <li>Your data is used <strong>only for educational quality improvement</strong></li>
                <li>Data is retained for <strong>7 years</strong> as per ISO 21001 requirements</li>
                <li>You can <strong>request data deletion</strong> at any time</li>
                <li>You can <strong>revoke consent</strong> anytime from your dashboard</li>
            </ul>

            <div class="warning-box">
                <p>
                    <strong>Important:</strong> You must provide consent to continue using the survey system.
                    Without consent, you cannot access the dashboard, take surveys, or use other features.
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('student.consent.accept') }}" id="consentForm">
            @csrf
            <input type="hidden" name="redirect_to" value="{{ request()->input('redirect_to', route('student.dashboard')) }}">

            <div class="consent-form">
                <div class="consent-checkbox-wrapper">
                    <input
                        type="checkbox"
                        id="consentGiven"
                        name="consent_given"
                        value="1"
                        required
                    >
                    <label for="consentGiven">
                        <strong>I consent to the collection and processing of my data</strong> as described above for the purpose of educational quality assessment and improvement.
                        <span class="required-asterisk">*</span>
                    </label>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <svg style="width: 20px; height: 20px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                    </svg>
                    Accept & Continue
                </button>
                <a href="{{ route('student.logout') }}" class="btn btn-outline">
                    <svg style="width: 20px; height: 20px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                    </svg>
                    Logout
                </a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('consentForm').addEventListener('submit', function(e) {
            const checkbox = document.getElementById('consentGiven');
            if (!checkbox.checked) {
                e.preventDefault();
                alert('Please check the consent box to continue.');
                checkbox.focus();
                return false;
            }
        });

        // Enable/disable submit button based on checkbox
        document.getElementById('consentGiven').addEventListener('change', function() {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = !this.checked;
        });

        // Initial state
        document.getElementById('submitBtn').disabled = !document.getElementById('consentGiven').checked;
    </script>
</body>
</html>

