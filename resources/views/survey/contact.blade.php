<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Academic Affairs - ISO Quality Education</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
        }

        .content-section {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .content-card {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            max-width: 900px;
            margin: 0 auto;
        }

        .content-card h1 {
            color: #2c3e50;
            font-size: 2.5rem;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .content-card h2 {
            color: #34495e;
            font-size: 1.8rem;
            margin-top: 30px;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .content-card p {
            color: #555;
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 20px;
        }

        .contact-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            border-radius: 12px;
            margin: 30px 0;
            text-align: center;
        }

        .contact-box h3 {
            font-size: 2rem;
            margin-bottom: 15px;
        }

        .contact-box p {
            color: rgba(255, 255, 255, 0.95);
            font-size: 1.2rem;
            margin-bottom: 25px;
        }

        .email-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 16px 32px;
            background: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .email-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
            background: #f8f9fa;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin: 30px 0;
        }

        .info-item {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 8px;
            border-left: 4px solid #4285f4;
        }

        .info-item h4 {
            color: #2c3e50;
            font-size: 1.3rem;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-item p {
            color: #555;
            margin-bottom: 0;
            font-size: 1rem;
        }

        .icon {
            width: 24px;
            height: 24px;
            fill: #4285f4;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: linear-gradient(90deg, #4285f4, #0d47a1);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-top: 30px;
        }

        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(66, 133, 244, 0.4);
        }

        .faq-section {
            margin-top: 40px;
        }

        .faq-item {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 15px;
            border-left: 4px solid #667eea;
        }

        .faq-item h4 {
            color: #2c3e50;
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        .faq-item p {
            color: #555;
            margin-bottom: 0;
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

    <main>
        <section class="content-section">
            <div class="container">
                <div class="content-card">
                    <h1>Contact Academic Affairs</h1>

                    <p>
                        The Academic Affairs Office at Jose Rizal University Senior High School is here to support you. Whether you have questions about the ISO Learner-Centric Survey, need clarification on academic policies, or want to provide feedback about your educational experience, we're ready to help.
                    </p>

                    <div class="contact-box">
                        <h3>Get in Touch</h3>
                        <p>
                            For any inquiries, concerns, or feedback regarding the survey or academic matters, please reach out to us via email:
                        </p>
                        <a href="mailto:support@jru.edu" class="email-btn">
                            <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                            </svg>
                            support@jru.edu
                        </a>
                    </div>

                    <h2>What We Can Help You With</h2>
                    <div class="info-grid">
                        <div class="info-item">
                            <h4>
                                <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/>
                                </svg>
                                Survey Questions
                            </h4>
                            <p>Technical issues, access problems, or questions about survey questions</p>
                        </div>

                        <div class="info-item">
                            <h4>
                                <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                                </svg>
                                Privacy Concerns
                            </h4>
                            <p>Questions about data privacy, confidentiality, or how your information is used</p>
                        </div>

                        <div class="info-item">
                            <h4>
                                <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path d="M21 3H3c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H3V5h18v14zM5 10h5v3H5z"/>
                                </svg>
                                Academic Programs
                            </h4>
                            <p>Information about CSS Strand curriculum, requirements, or academic policies</p>
                        </div>

                        <div class="info-item">
                            <h4>
                                <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 9h12v2H6V9zm8 5H6v-2h8v2zm4-6H6V6h12v2z"/>
                                </svg>
                                General Feedback
                            </h4>
                            <p>Share additional feedback, suggestions, or concerns about your educational experience</p>
                        </div>
                    </div>

                    <h2>Response Time</h2>
                    <p>
                        We strive to respond to all inquiries within <strong>2-3 business days</strong>. During peak periods (such as enrollment season or exam weeks), response times may be slightly longer. For urgent matters, please indicate "URGENT" in your email subject line.
                    </p>

                    <div class="faq-section">
                        <h2>Frequently Asked Questions</h2>

                        <div class="faq-item">
                            <h4>Q: I'm having trouble accessing the survey. What should I do?</h4>
                            <p>
                                A: First, ensure you're logged in with your student account. If you continue to experience issues, please email us at support@jru.edu with details about the problem (browser type, error messages, etc.), and we'll assist you promptly.
                            </p>
                        </div>

                        <div class="faq-item">
                            <h4>Q: Can I change my survey responses after submission?</h4>
                            <p>
                                A: Survey responses are typically final after submission to maintain data integrity. However, if you have a valid reason to update your responses, contact us within 48 hours of submission, and we'll review your request.
                            </p>
                        </div>

                        <div class="faq-item">
                            <h4>Q: Will my survey responses affect my grades?</h4>
                            <p>
                                A: Absolutely not. The survey is completely confidential, and your responses will not impact your grades or academic standing in any way. Your honest feedback helps us improve the program for all students.
                            </p>
                        </div>

                        <div class="faq-item">
                            <h4>Q: How long does it take to complete the survey?</h4>
                            <p>
                                A: The survey typically takes 10-15 minutes to complete. We recommend setting aside uninterrupted time to provide thoughtful responses.
                            </p>
                        </div>

                        <div class="faq-item">
                            <h4>Q: Can I request deletion of my survey data?</h4>
                            <p>
                                A: Yes. As outlined in our Privacy Policy, you have the right to request deletion of your data within 30 days of submission. Please email us with your request, including your student ID for verification purposes.
                            </p>
                        </div>
                    </div>

                    <h2>Office Hours</h2>
                    <p>
                        While email is the preferred method of contact, the Academic Affairs Office is also available for in-person consultations:
                    </p>
                    <div class="info-grid">
                        <div class="info-item">
                            <h4>
                                <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                                </svg>
                                Office Hours
                            </h4>
                            <p>Monday - Friday<br>8:00 AM - 5:00 PM</p>
                        </div>

                        <div class="info-item">
                            <h4>
                                <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                </svg>
                                Location
                            </h4>
                            <p>Academic Affairs Office<br>JRU Senior High School Building</p>
                        </div>
                    </div>

                    <div class="contact-box">
                        <h3>We're Here to Help</h3>
                        <p>
                            Your feedback and concerns are important to us. Don't hesitate to reach out—we're committed to providing the support you need for a successful educational experience.
                        </p>
                    </div>

                    <a href="{{ route('survey.landing') }}" class="back-btn">
                        <svg style="width: 20px; height: 20px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Back to Home
                    </a>
                </div>
            </div>
        </section>
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
                <div class="footer-links">
                    <h4 class="footer-links-title">Quick Links</h4>
                    <ul class="footer-links-list">
                        <li><a href="{{ route('survey.about') }}" class="footer-link">About this Survey</a></li>
                        <li><a href="{{ route('survey.privacy') }}" class="footer-link">Privacy Policy</a></li>
                        <li><a href="{{ route('survey.contact') }}" class="footer-link">Contact Academic Affairs</a></li>
                    </ul>
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
</body>
</html>

