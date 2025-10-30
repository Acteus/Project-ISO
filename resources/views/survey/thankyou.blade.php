<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Thank You - ISO Quality Education</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.5);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes checkmark {
            0% {
                stroke-dashoffset: 100;
            }
            100% {
                stroke-dashoffset: 0;
            }
        }

        @keyframes circle {
            0% {
                stroke-dashoffset: 240;
            }
            100% {
                stroke-dashoffset: 480;
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }
            100% {
                background-position: 1000px 0;
            }
        }

        .thank-you-container {
            text-align: center;
            padding: 80px 20px 60px;
            max-width: 900px;
            margin: 0 auto;
            position: relative;
        }

        .success-animation {
            width: 180px;
            height: 180px;
            margin: 0 auto 40px;
            animation: scaleIn 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            position: relative;
        }

        .success-circle {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            animation: float 3s ease-in-out infinite;
            position: relative;
            overflow: hidden;
        }

        .success-circle::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            animation: shimmer 3s infinite;
        }

        .checkmark-svg {
            width: 100px;
            height: 100px;
        }

        .checkmark-circle {
            stroke-dasharray: 240;
            stroke-dashoffset: 480;
            stroke: #fff;
            stroke-width: 3;
            fill: none;
            animation: circle 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
            animation-delay: 0.2s;
        }

        .checkmark-check {
            stroke-dasharray: 100;
            stroke-dashoffset: 100;
            stroke: #fff;
            stroke-width: 4;
            fill: none;
            animation: checkmark 0.5s cubic-bezier(0.65, 0, 0.45, 1) forwards;
            animation-delay: 0.5s;
        }

        .message-bubble {
            background: white;
            border: 3px solid transparent;
            background-image: linear-gradient(white, white), linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-origin: border-box;
            background-clip: padding-box, border-box;
            border-radius: 30px;
            padding: 50px 40px;
            margin-bottom: 50px;
            box-shadow: 0 20px 60px rgba(102, 126, 234, 0.2);
            animation: fadeInUp 0.8s ease-out 0.3s both;
            position: relative;
            overflow: hidden;
        }

        .message-bubble::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(102, 126, 234, 0.02), transparent);
            animation: shimmer 4s infinite;
            pointer-events: none;
        }

        .thank-you-title {
            font-size: 48px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 24px;
            font-weight: 800;
            letter-spacing: -0.5px;
            position: relative;
            z-index: 1;
        }

        .thank-you-subtitle {
            font-size: 22px;
            color: #2d3748;
            margin-bottom: 20px;
            font-weight: 600;
            position: relative;
            z-index: 1;
        }

        .thank-you-message {
            font-size: 18px;
            color: #4a5568;
            line-height: 1.8;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 0;
            position: relative;
            z-index: 1;
        }

        .thank-you-actions {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 60px;
            animation: fadeInUp 0.8s ease-out 0.6s both;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 18px 40px;
            border: none;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            font-size: 16px;
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.5);
            color: white;
        }

        .btn-primary:active {
            transform: translateY(-1px) scale(1.02);
        }

        .btn-secondary {
            background: white;
            color: #667eea;
            padding: 18px 40px;
            border: 2px solid #667eea;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 16px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.1);
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: transparent;
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.3);
        }

        .btn-secondary:active {
            transform: translateY(-1px) scale(1.02);
        }

        .btn-icon {
            font-size: 18px;
            transition: transform 0.3s ease;
        }

        .btn-primary:hover .btn-icon,
        .btn-secondary:hover .btn-icon {
            transform: scale(1.2);
        }

        .survey-info {
            background: linear-gradient(135deg, #f6f8fb 0%, #ffffff 100%);
            padding: 40px;
            border-radius: 24px;
            margin-top: 40px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(102, 126, 234, 0.1);
            animation: fadeInUp 0.8s ease-out 0.7s both;
            position: relative;
            overflow: hidden;
        }

        .survey-info::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        }

        .survey-info h3 {
            color: #2d3748;
            margin-bottom: 30px;
            font-size: 24px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .survey-info h3::before {
            content: '';
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            text-align: left;
        }

        .info-item {
            background: white;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .info-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.15);
        }

        .info-label {
            font-weight: 700;
            color: #667eea;
            margin-bottom: 8px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            color: #2d3748;
            font-size: 16px;
            word-break: break-word;
        }

        .contact-section {
            margin-top: 50px;
            padding: 30px;
            background: linear-gradient(135deg, #fef5e7 0%, #fff9e6 100%);
            border-radius: 20px;
            border-left: 4px solid #f39c12;
            animation: fadeInUp 0.8s ease-out 0.8s both;
        }

        .contact-section p {
            color: #856404;
            font-size: 15px;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .contact-section p::before {
            content: '';
        }

        .footer {
            margin-top: 80px;
            padding: 30px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            text-align: center;
            color: white;
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
        }

        /* Confetti animation */
        .confetti {
            position: fixed;
            width: 10px;
            height: 10px;
            background: #667eea;
            position: absolute;
            animation: confetti-fall 3s linear infinite;
        }

        @keyframes confetti-fall {
            to {
                transform: translateY(100vh) rotate(360deg);
                opacity: 0;
            }
        }

        @media (max-width: 768px) {
            .thank-you-container {
                padding: 40px 15px 30px;
            }

            .message-bubble {
                padding: 30px 20px;
                border-radius: 20px;
                margin-bottom: 30px;
            }

            .thank-you-title {
                font-size: 32px;
            }

            .success-animation {
                width: 120px;
                height: 120px;
                margin-bottom: 30px;
            }

            .success-circle {
                width: 120px;
                height: 120px;
            }

            .checkmark-svg {
                width: 70px;
                height: 70px;
            }

            .thank-you-subtitle {
                font-size: 16px;
            }

            .thank-you-message {
                font-size: 15px;
            }

            .thank-you-actions {
                gap: 15px;
            }

            .btn-primary, .btn-secondary {
                padding: 16px 24px;
                font-size: 16px;
                min-height: 48px;
                width: 100%;
                max-width: 280px;
            }

            .survey-info {
                padding: 25px 15px;
                margin-top: 30px;
            }

            .info-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .info-item {
                padding: 15px;
            }

            .contact-section {
                margin-top: 30px;
                padding: 20px;
            }
        }

        @media (max-width: 480px) {
            .thank-you-container {
                padding: 30px 10px 20px;
            }

            .thank-you-title {
                font-size: 28px;
            }

            .message-bubble {
                padding: 25px 15px;
            }

            .success-animation {
                width: 100px;
                height: 100px;
            }

            .success-circle {
                width: 100px;
                height: 100px;
            }

            .checkmark-svg {
                width: 60px;
                height: 60px;
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
                    @auth
                        <!-- Show for logged-in students -->
                        <a href="{{ route('student.dashboard') }}" class="nav-link">{{ Auth::user()->name }}</a>
                        <form method="POST" action="{{ route('student.logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="nav-link logout-btn" style="background: linear-gradient(90deg, #dc3545, #c82333); border: none; color: white; cursor: pointer; padding: 8px 20px; border-radius: 6px; font-weight: 600; transition: all 0.3s ease;">
                                <svg style="width: 16px; height: 16px; vertical-align: middle; margin-right: 5px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                                </svg>
                                Logout
                            </button>
                        </form>
                    @elseif(session('admin'))
                        <!-- Show for logged-in admins -->
                        <a href="{{ route('admin.dashboard') }}" class="nav-link">{{ session('admin')->name }}</a>
                        <form method="POST" action="{{ route('student.logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="nav-link logout-btn" style="background: linear-gradient(90deg, #dc3545, #c82333); border: none; color: white; cursor: pointer; padding: 8px 20px; border-radius: 6px; font-weight: 600; transition: all 0.3s ease;">
                                <svg style="width: 16px; height: 16px; vertical-align: middle; margin-right: 5px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                                </svg>
                                Logout
                            </button>
                        </form>
                    @else
                        <!-- Show for non-authenticated users -->
                        <a href="{{ route('student.login') }}" class="nav-link">Login</a>
                        <a href="{{ route('student.register') }}" class="nav-link">Register</a>
                    @endauth
                </nav>
            </div>
        </div>
    </header>

    <main class="survey-main">
        <div class="container">
            <div class="thank-you-container">
                <!-- Animated Success Icon -->
                <div class="success-animation">
                    <div class="success-circle">
                        <svg class="checkmark-svg" viewBox="0 0 52 52">
                            <circle class="checkmark-circle" cx="26" cy="26" r="25"/>
                            <path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                        </svg>
                    </div>
                </div>

                <div class="message-bubble">
                    <h1 class="thank-you-title">Thank You!</h1>

                    <p class="thank-you-subtitle">Your Response Has Been Recorded</p>

                    <p class="thank-you-message">
                        Your survey response has been successfully submitted. Your feedback is invaluable to us and will contribute to improving the quality of education for all students. We deeply appreciate you taking the time to share your thoughts.
                    </p>
                </div>

                <div class="thank-you-actions">
                    <a href="{{ route('survey.form') }}" class="btn-primary">
                        <span>Submit Another Response</span>
                    </a>
                    <a href="{{ route('survey.landing') }}" class="btn-secondary">
                        <span>Back to Home</span>
                    </a>
                </div>

                <div class="survey-info">
                    <h3>Survey Information</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Survey Type</div>
                            <div class="info-value">ISO 21001 Learner-Centric Quality Education</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Date Submitted</div>
                            <div class="info-value">{{ now()->format('F j, Y') }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Time Submitted</div>
                            <div class="info-value">{{ now()->format('g:i A') }}</div>
                        </div>

                        @auth
                            <div class="info-item">
                                <div class="info-label">Student ID</div>
                                <div class="info-value">{{ Auth::user()->student_id }}</div>
                            </div>

                            <div class="info-item">
                                <div class="info-label">Student Name</div>
                                <div class="info-value">{{ Auth::user()->name }}</div>
                            </div>
                        @endauth

                        <div class="info-item">
                            <div class="info-label">Reference ID</div>
                            <div class="info-value" id="referenceId">{{ session('survey_reference_id', 'N/A') }}</div>
                        </div>
                    </div>
                </div>

                <div class="contact-section">
                    <p>
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
                    <h3 class="footer-title" style="color: white; margin-bottom: 15px; font-size: 24px; font-weight: 700;">ISO Learner-Centric Quality Education</h3>
                    <p class="footer-description" style="color: rgba(255,255,255,0.9); font-size: 16px; margin-bottom: 20px;">
                        Empowering CSS Strand Students through Learner-Centric Quality Education
                    </p>
                    <p style="color: rgba(255,255,255,0.8); font-size: 14px;">
                        Jose Rizal University - Senior High School<br>
                        Contact: academic.affairs@jru.edu
                    </p>
                </div>
            </div>
            <div class="footer-bottom" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.2);">
                <p class="footer-copyright" style="color: rgba(255,255,255,0.9); font-size: 14px; margin: 0;">
                    © <span id="currentYear"></span> JRU Senior High School. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        // Set current year
        document.getElementById('currentYear').textContent = new Date().getFullYear();

        // Generate a reference ID if not already set
        const referenceIdElement = document.getElementById('referenceId');
        if (referenceIdElement.textContent === 'N/A') {
            const referenceId = 'ISO-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9).toUpperCase();
            referenceIdElement.textContent = referenceId;
        }

        // Create confetti effect
        function createConfetti() {
            const colors = ['#667eea', '#764ba2', '#f093fb', '#4facfe', '#43e97b', '#fa709a'];
            const confettiCount = 50;

            for (let i = 0; i < confettiCount; i++) {
                setTimeout(() => {
                    const confetti = document.createElement('div');
                    confetti.style.position = 'fixed';
                    confetti.style.width = Math.random() * 10 + 5 + 'px';
                    confetti.style.height = Math.random() * 10 + 5 + 'px';
                    confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
                    confetti.style.left = Math.random() * 100 + '%';
                    confetti.style.top = '-20px';
                    confetti.style.borderRadius = Math.random() > 0.5 ? '50%' : '0';
                    confetti.style.opacity = Math.random() * 0.7 + 0.3;
                    confetti.style.pointerEvents = 'none';
                    confetti.style.zIndex = '9999';

                    const duration = Math.random() * 3 + 2;
                    const delay = Math.random() * 0.5;
                    confetti.style.animation = `confetti-fall ${duration}s linear ${delay}s forwards`;

                    document.body.appendChild(confetti);

                    setTimeout(() => {
                        confetti.remove();
                    }, (duration + delay) * 1000);
                }, i * 50);
            }
        }

        // Trigger confetti on page load
        window.addEventListener('load', () => {
            setTimeout(createConfetti, 500);
        });

        // Add smooth scroll behavior
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add button click effects
        document.querySelectorAll('.btn-primary, .btn-secondary').forEach(button => {
            button.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                ripple.style.position = 'absolute';
                ripple.style.borderRadius = '50%';
                ripple.style.background = 'rgba(255,255,255,0.6)';
                ripple.style.width = ripple.style.height = '100px';
                ripple.style.left = e.offsetX - 50 + 'px';
                ripple.style.top = e.offsetY - 50 + 'px';
                ripple.style.animation = 'ripple 0.6s ease-out';
                ripple.style.pointerEvents = 'none';

                this.style.position = 'relative';
                this.style.overflow = 'hidden';
                this.appendChild(ripple);

                setTimeout(() => ripple.remove(), 600);
            });
        });

        // Add ripple animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                from {
                    transform: scale(0);
                    opacity: 1;
                }
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);

        console.log('Thank you page loaded with enhanced animations');
    </script>
</body>
</html>
