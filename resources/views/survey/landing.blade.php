<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISO Quality Education Survey</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
        }

        .nav-link[href*="dashboard"]:hover {
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

    <main class="main">
        <!-- Hero Section -->
        <section class="hero-section" style="background-image: url('{{ asset('images/HomeBG.jpg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
            <div class="hero-overlay"></div>
            <div class="container hero-content">
                <h2 class="hero-subtitle">Sustaining Quality Education</h2>
                <h1 class="hero-title">Take a Survey</h1>
                <p class="hero-description">
                    ISO-Based Learner-Centric Survey for CSS Strand Students, JRU Senior High School
                </p>
                <a href="{{ route('survey.form') }}" class="btn btn-primary hero-btn">
                    Start Survey
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </section>

        <!-- Info Section -->
        <section class="info-section">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">About the Survey</h2>
                    <p class="section-description">
                        This survey helps us validate the quality of education for Senior High School CSS Strand students using ISO learner-centric principles. Your feedback is valuable in improving our educational programs.
                    </p>
                </div>

                <div class="info-grid">
                    <div class="info-card">
                        <div class="info-card-number">1</div>
                        <h3 class="info-card-title">Share Your Experience</h3>
                        <p class="info-card-description">
                            Provide honest feedback about your learning experience in the CSS Strand program.
                        </p>
                    </div>

                    <div class="info-card">
                        <div class="info-card-number">2</div>
                        <h3 class="info-card-title">Help Improve Quality</h3>
                        <p class="info-card-description">
                            Your insights help us identify areas for improvement and maintain high educational standards.
                        </p>
                    </div>

                    <div class="info-card">
                        <div class="info-card-number">3</div>
                        <h3 class="info-card-title">Drive Positive Change</h3>
                        <p class="info-card-description">
                            Be part of the process that enhances the learning experience for current and future students.
                        </p>
                    </div>
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
                        <li><a href="{{ route('survey.landing') }}" class="footer-link">About this Survey</a></li>
                        <li><a href="{{ route('survey.landing') }}" class="footer-link">Privacy Policy</a></li>
                        <li><a href="{{ route('survey.landing') }}" class="footer-link">Contact Academic Affairs</a></li>
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
