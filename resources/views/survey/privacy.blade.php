<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - ISO Quality Education</title>
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

        .content-card ul {
            margin: 20px 0;
            padding-left: 30px;
        }

        .content-card ul li {
            color: #555;
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 12px;
        }

        .highlight-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 8px;
            margin: 30px 0;
        }

        .highlight-box h3 {
            margin-bottom: 10px;
            font-size: 1.5rem;
        }

        .highlight-box p {
            color: rgba(255, 255, 255, 0.95);
            margin-bottom: 0;
        }

        .info-box {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }

        .info-box p {
            margin-bottom: 0;
            color: #1565c0;
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
                    <h1>Privacy Policy</h1>

                    <p><strong>Effective Date:</strong> October 26, 2025</p>
                    <p><strong>Last Updated:</strong> October 26, 2025</p>

                    <p>
                        At Jose Rizal University Senior High School, we are committed to protecting your privacy and ensuring the security of your personal information. This Privacy Policy explains how we collect, use, store, and protect information gathered through the ISO Learner-Centric Survey for CSS Strand students.
                    </p>

                    <div class="highlight-box">
                        <h3>Our Commitment to You</h3>
                        <p>
                            Your privacy is paramount. We collect only the information necessary to improve educational quality and will never sell, rent, or share your personal data with third parties without your explicit consent.
                        </p>
                    </div>

                    <h2>1. Information We Collect</h2>
                    <p>When you participate in our survey, we may collect the following types of information:</p>
                    <ul>
                        <li><strong>Personal Identification Information:</strong> Student ID, name, grade level, and strand</li>
                        <li><strong>Demographic Information:</strong> Gender (optional), year level</li>
                        <li><strong>Survey Responses:</strong> Your answers to survey questions, including ratings and written feedback</li>
                        <li><strong>Technical Information:</strong> IP address, browser type, device information, and timestamps (for security and analytics purposes)</li>
                    </ul>

                    <h2>2. How We Use Your Information</h2>
                    <p>The information we collect is used exclusively for the following purposes:</p>
                    <ul>
                        <li>Evaluating and improving the quality of the CSS Strand program</li>
                        <li>Analyzing educational trends and patterns</li>
                        <li>Generating aggregate reports for academic review and planning</li>
                        <li>Identifying areas for curriculum enhancement</li>
                        <li>Complying with ISO 21001:2018 standards for educational quality management</li>
                        <li>Academic research and educational policy development</li>
                    </ul>

                    <h2>3. Data Security</h2>
                    <p>
                        We implement robust security measures to protect your personal information:
                    </p>
                    <ul>
                        <li>All data is stored on secure servers with encryption</li>
                        <li>Access to survey data is restricted to authorized personnel only</li>
                        <li>We use industry-standard SSL/TLS encryption for data transmission</li>
                        <li>Regular security audits and updates are conducted</li>
                        <li>Physical and electronic safeguards are in place to prevent unauthorized access</li>
                    </ul>

                    <h2>4. Data Retention</h2>
                    <p>
                        We retain your survey responses for a period necessary to fulfill the purposes outlined in this policy. Typically, survey data is retained for:
                    </p>
                    <ul>
                        <li><strong>Active Period:</strong> During the academic year in which the survey is conducted</li>
                        <li><strong>Analysis Period:</strong> Up to 3 years for longitudinal studies and trend analysis</li>
                        <li><strong>Archival Period:</strong> Anonymized aggregate data may be retained indefinitely for historical reference</li>
                    </ul>

                    <div class="info-box">
                        <p>
                            <strong>Note:</strong> Personal identifiers are removed or anonymized after the initial analysis period, ensuring that archived data cannot be traced back to individual respondents.
                        </p>
                    </div>

                    <h2>5. Confidentiality and Anonymity</h2>
                    <p>
                        Your responses are treated with strict confidentiality:
                    </p>
                    <ul>
                        <li>Individual responses are never shared publicly or with instructors</li>
                        <li>Reports generated from survey data are aggregated and anonymized</li>
                        <li>No personally identifiable information is included in published reports</li>
                        <li>Your participation or individual responses will not affect your grades or academic standing</li>
                    </ul>

                    <h2>6. Your Rights</h2>
                    <p>As a survey participant, you have the following rights:</p>
                    <ul>
                        <li><strong>Right to Access:</strong> You may request a copy of the data we have collected about you</li>
                        <li><strong>Right to Correction:</strong> You may request corrections to any inaccurate personal information</li>
                        <li><strong>Right to Deletion:</strong> You may request deletion of your survey responses within 30 days of submission</li>
                        <li><strong>Right to Opt-Out:</strong> You may choose not to participate in the survey at any time</li>
                        <li><strong>Right to Withdraw:</strong> You may withdraw your consent and have your data removed (subject to research integrity requirements)</li>
                    </ul>

                    <h2>7. Third-Party Services</h2>
                    <p>
                        We may use third-party services for data analysis and storage. These providers are carefully selected and are required to:
                    </p>
                    <ul>
                        <li>Maintain strict confidentiality standards</li>
                        <li>Comply with applicable data protection laws</li>
                        <li>Use data only for the purposes specified by JRU</li>
                        <li>Implement appropriate security measures</li>
                    </ul>

                    <h2>8. Cookies and Tracking Technologies</h2>
                    <p>
                        Our survey platform may use cookies and similar technologies to:
                    </p>
                    <ul>
                        <li>Maintain your session and prevent duplicate submissions</li>
                        <li>Improve user experience and site functionality</li>
                        <li>Analyze usage patterns (in aggregate form only)</li>
                    </ul>
                    <p>
                        You can control cookie settings through your browser preferences. However, disabling cookies may affect the functionality of the survey platform.
                    </p>

                    <h2>9. Data Sharing</h2>
                    <p>
                        We do not sell, trade, or rent your personal information. Data may only be shared in the following circumstances:
                    </p>
                    <ul>
                        <li><strong>Within JRU:</strong> With authorized academic and administrative personnel for educational improvement purposes</li>
                        <li><strong>Legal Requirements:</strong> When required by law or in response to valid legal requests</li>
                        <li><strong>Institutional Research:</strong> Anonymized data may be used for academic research with proper ethical approval</li>
                    </ul>

                    <h2>10. Children's Privacy</h2>
                    <p>
                        Our survey is designed for Senior High School students. If you are under 18, we encourage you to discuss your participation with your parents or guardians. We collect only necessary information and ensure it is protected according to applicable privacy laws.
                    </p>

                    <h2>11. Changes to This Privacy Policy</h2>
                    <p>
                        We may update this Privacy Policy periodically to reflect changes in our practices or legal requirements. We will notify you of any significant changes by:
                    </p>
                    <ul>
                        <li>Posting the updated policy on our website</li>
                        <li>Updating the "Last Updated" date at the top of this page</li>
                        <li>Sending notifications to registered students (for major changes)</li>
                    </ul>

                    <h2>12. Contact Us</h2>
                    <p>
                        If you have any questions, concerns, or requests regarding this Privacy Policy or how your data is handled, please contact us:
                    </p>
                    <ul>
                        <li><strong>Academic Affairs Office</strong></li>
                        <li><strong>Email:</strong> <a href="mailto:support@jru.edu" style="color: #4285f4; text-decoration: underline;">support@jru.edu</a></li>
                        <li><strong>Alternative:</strong> Visit our <a href="{{ route('survey.contact') }}" style="color: #4285f4; text-decoration: underline;">Contact Academic Affairs</a> page</li>
                    </ul>

                    <div class="highlight-box">
                        <h3>Your Trust Matters</h3>
                        <p>
                            We are committed to maintaining your trust by handling your information responsibly and transparently. Thank you for participating in our efforts to improve educational quality at JRU Senior High School.
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

