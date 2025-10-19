<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You - ISO Quality Education</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        .thank-you-container {
            text-align: center;
            padding: 60px 20px;
            max-width: 800px;
            margin: 0 auto;
        }

        .thank-you-icon {
            font-size: 80px;
            color: #28a745;
            margin-bottom: 30px;
        }

        .thank-you-title {
            font-size: 36px;
            color: #333;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .thank-you-message {
            font-size: 18px;
            color: #666;
            line-height: 1.6;
            margin-bottom: 40px;
        }

        .thank-you-actions {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 40px;
        }

        .btn-primary {
            background: linear-gradient(90deg, #4285F4, #2c6cd6);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(66, 133, 244, 0.4);
            color: white;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-secondary:hover {
            background: #5a6268;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.4);
        }

        .survey-info {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 10px;
            margin-top: 40px;
        }

        .survey-info h3 {
            color: #333;
            margin-bottom: 15px;
        }

        .survey-info p {
            color: #666;
            margin-bottom: 10px;
        }

        .footer {
            margin-top: 60px;
            padding: 20px;
            background: #f8f9fa;
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="nav-wrapper">
                <div class="logo">
                    <a href="{{ route('welcome') }}">ISO Quality Education</a>
                </div>

                <!-- Desktop navigation -->
                <nav class="desktop-nav">
                    <a href="{{ route('welcome') }}" class="nav-link">Home</a>
                    <a href="{{ route('survey.form') }}" class="nav-link">Survey</a>
                    @auth
                        <a href="{{ route('student.dashboard') }}" class="nav-link">Dashboard</a>
                        <form method="POST" action="{{ route('student.logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="nav-link" style="background: none; border: none; color: inherit; cursor: pointer;">
                                Logout ({{ Auth::user()->name }})
                            </button>
                        </form>
                    @else
                        <a href="{{ route('student.login') }}" class="nav-link">Login</a>
                    @endauth
                </nav>
            </div>
        </div>
    </header>

    <main class="survey-main">
        <div class="container">
            <div class="thank-you-container">
                <div class="thank-you-icon">✓</div>

                <h1 class="thank-you-title">Thank You!</h1>

                <p class="thank-you-message">
                    Your survey response has been successfully submitted. Your feedback is valuable to us and will help improve the quality of education for all students.
                </p>

                <div class="thank-you-actions">
                    <a href="{{ route('survey.form') }}" class="btn-primary">Submit Another Response</a>
                    <a href="{{ route('student.dashboard') }}" class="btn-secondary">View Dashboard</a>
                </div>

                <div class="survey-info">
                    <h3>Survey Information</h3>
                    <p><strong>Survey Type:</strong> ISO 21001 Learner-Centric Quality Education</p>
                    <p><strong>Submitted:</strong> {{ date('F j, Y \a\t g:i A') }}</p>
                    @auth
                        <p><strong>Student ID:</strong> {{ Auth::user()->student_id }}</p>
                        <p><strong>Name:</strong> {{ Auth::user()->name }}</p>
                    @endauth
                    <p><strong>Reference ID:</strong> <span id="referenceId">{{ session('survey_reference_id', 'N/A') }}</span></p>
                </div>

                <div style="margin-top: 40px; padding-top: 30px; border-top: 1px solid #dee2e6;">
                    <p style="color: #666; font-size: 14px;">
                        For questions or concerns about this survey, please contact the Academic Affairs Office.
                    </p>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-main">
                    <h3 class="footer-title">ISO Learner-Centric Quality Education</h3>
                    <p class="footer-description">
                        Empowering CSS Strand Students through Learner-Centric Quality Education
                    </p>
                </div>
            </div>
            <div class="footer-bottom">
                <p class="footer-copyright">
                    © <span id="currentYear"></span> JRU Senior High School. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        // Set current year
        document.getElementById('currentYear').textContent = new Date().getFullYear();

        // Generate a reference ID for this submission
        const referenceId = 'ISO-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9).toUpperCase();
        document.getElementById('referenceId').textContent = referenceId;

        console.log('Thank you page loaded');
    </script>
</body>
</html>
