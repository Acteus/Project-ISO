<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About This Survey - ISO Quality Education</title>
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
                    <h1>About This Survey</h1>

                    <p>
                        Welcome to the ISO Learner-Centric Survey for Computer System Servicing (CSS) Strand students at Jose Rizal University Senior High School. This survey is designed to gather valuable insights about your educational experience and help us maintain and improve the quality of education we provide.
                    </p>

                    <h2>Purpose of the Survey</h2>
                    <p>
                        The primary purpose of this survey is to evaluate the effectiveness of our CSS Strand program through the lens of learner-centric principles based on ISO 21001:2018 Educational Organizations Management Systems. Your feedback will help us:
                    </p>
                    <ul>
                        <li>Assess how well the curriculum meets your educational goals and expectations</li>
                        <li>Evaluate the quality of teaching methods and learning resources</li>
                        <li>Understand your satisfaction with assessment methods and feedback mechanisms</li>
                        <li>Identify areas for improvement in support services and learning environment</li>
                        <li>Measure overall satisfaction with the CSS Strand program</li>
                    </ul>

                    <h2>What is ISO 21001?</h2>
                    <p>
                        ISO 21001:2018 is an international standard that specifies requirements for management systems in educational organizations. It emphasizes a learner-centric approach, focusing on:
                    </p>
                    <ul>
                        <li>Meeting learner and stakeholder needs and expectations</li>
                        <li>Enhancing learner satisfaction</li>
                        <li>Improving the effectiveness of educational services</li>
                        <li>Creating an inclusive and accessible learning environment</li>
                        <li>Promoting continuous improvement in educational quality</li>
                    </ul>

                    <div class="highlight-box">
                        <h3>Your Voice Matters</h3>
                        <p>
                            Your honest and thoughtful responses are crucial in helping us understand what we're doing well and where we can improve. This survey is an opportunity for you to directly influence the quality of education you and your peers receive.
                        </p>
                    </div>

                    <h2>Survey Structure</h2>
                    <p>
                        The survey is organized into the following sections:
                    </p>
                    <ul>
                        <li><strong>Learner Needs & Expectations:</strong> How well the program aligns with your educational goals</li>
                        <li><strong>Teaching & Learning Quality:</strong> Effectiveness of teaching methods and instructor expertise</li>
                        <li><strong>Assessments & Outcomes:</strong> Fairness and effectiveness of evaluation methods</li>
                        <li><strong>Support & Resources:</strong> Availability and quality of learning resources and support services</li>
                        <li><strong>Environment & Inclusivity:</strong> The learning environment and its inclusiveness</li>
                        <li><strong>Feedback & Responsiveness:</strong> How well the institution responds to student feedback</li>
                        <li><strong>Overall Satisfaction:</strong> Your general satisfaction with the program</li>
                        <li><strong>Additional Feedback:</strong> Open-ended questions for detailed suggestions</li>
                    </ul>

                    <h2>Confidentiality and Data Use</h2>
                    <p>
                        All responses are treated with strict confidentiality. The information collected will be used solely for the purpose of improving educational quality and will be aggregated for analysis. Individual responses will not be shared in a way that could identify you personally. For more information, please review our <a href="{{ route('survey.privacy') }}" style="color: #4285f4; text-decoration: underline;">Privacy Policy</a>.
                    </p>

                    <h2>Time Commitment</h2>
                    <p>
                        The survey typically takes 10-15 minutes to complete. We encourage you to find a quiet time when you can focus and provide thoughtful responses.
                    </p>

                    <div class="highlight-box">
                        <h3>Ready to Begin?</h3>
                        <p>
                            Your participation in this survey demonstrates your commitment to excellence in education. Thank you for taking the time to help us improve the CSS Strand program!
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

