<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logged Out - ISO Quality Education</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        .logout-container {
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .logout-card {
            background: white;
            border-radius: 20px;
            padding: 60px 40px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 500px;
            width: 100%;
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logout-icon {
            width: 100px;
            height: 100px;
            margin: 0 auto 30px;
            background: linear-gradient(135deg, #4285F4, #2c6cd6);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(66, 133, 244, 0.7);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 0 0 20px rgba(66, 133, 244, 0);
            }
        }

        .logout-icon svg {
            width: 50px;
            height: 50px;
            fill: white;
        }

        .logout-card h1 {
            color: #333;
            font-size: 32px;
            margin: 0 0 15px 0;
            font-weight: 700;
        }

        .logout-card p {
            color: #666;
            font-size: 18px;
            margin: 0 0 35px 0;
            line-height: 1.6;
        }

        .logout-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            padding: 14px 30px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(90deg, #4285F4, #2c6cd6);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(66, 133, 244, 0.4);
            color: white;
        }

        .btn-secondary {
            background: #f8f9fa;
            color: #333;
            border: 2px solid #e0e0e0;
        }

        .btn-secondary:hover {
            background: #e9ecef;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            color: #333;
        }

        .security-notice {
            margin-top: 40px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 4px solid #28a745;
        }

        .security-notice h3 {
            margin: 0 0 10px 0;
            color: #28a745;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .security-notice p {
            margin: 0;
            font-size: 14px;
            color: #666;
        }

        .checkmark {
            width: 20px;
            height: 20px;
            fill: #28a745;
        }

        @media (max-width: 768px) {
            .logout-card {
                padding: 40px 25px;
            }

            .logout-card h1 {
                font-size: 26px;
            }

            .logout-card p {
                font-size: 16px;
            }

            .logout-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="nav-wrapper">
                <div class="logo">
                    <a href="{{ route('survey.landing') }}">ISO Quality Education</a>
                </div>

                <!-- Desktop navigation -->
                <nav class="desktop-nav">
                    <a href="{{ route('student.login') }}" class="nav-link">Login</a>
                    <a href="{{ route('student.register') }}" class="nav-link">Register</a>
                </nav>
            </div>
        </div>
    </header>

    <main class="survey-main">
        <div class="logout-container">
            <div class="logout-card">
                <div class="logout-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                    </svg>
                </div>

                <h1>Successfully Logged Out</h1>
                <p>You have been safely logged out of the ISO 21001 Quality Education System. Thank you for your participation!</p>

                <div class="logout-actions">
                    <a href="{{ route('student.login') }}" class="btn btn-primary">Log In Again</a>
                    <a href="{{ route('survey.landing') }}" class="btn btn-secondary">Return Home</a>
                </div>

                <div class="security-notice">
                    <h3>
                        <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                        Security Notice
                    </h3>
                    <p>For your security, please close your browser if you're using a shared computer.</p>
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
                        Empowering CSS Students through Learner-Centric Quality Education
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
    </script>
</body>
</html>

