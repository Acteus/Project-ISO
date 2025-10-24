<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISO Quality Education Survey</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="nav-wrapper">
                <div class="logo">
                    <a href="{{ route('home') }}">ISO Quality Education</a>
                </div>

                <!-- Mobile menu button -->
                <div class="mobile-menu-btn">
                    <button onclick="toggleMobileMenu()" class="menu-toggle">
                        <span class="hamburger"></span>
                        <span class="hamburger"></span>
                        <span class="hamburger"></span>
                    </button>
                </div>

                <!-- Desktop navigation -->
                <nav class="desktop-nav">
                    <a href="{{ route('home') }}" class="nav-link active">Home</a>
                    <a href="{{ route('survey.form') }}" class="nav-link">Survey</a>
                </nav>
            </div>

            <!-- Mobile navigation -->
            <nav class="mobile-nav" id="mobileNav">
                <a href="{{ route('home') }}" class="mobile-nav-link active">Home</a>
                <a href="{{ route('survey.form') }}" class="mobile-nav-link">Survey</a>
            </nav>
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

                <div class="section-cta">
                    <a href="{{ route('survey.form') }}" class="btn btn-secondary">
                        Take the Survey Now
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
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
                        <li><a href="{{ route('home') }}" class="footer-link">About this Survey</a></li>
                        <li><a href="{{ route('home') }}" class="footer-link">Privacy Policy</a></li>
                        <li><a href="{{ route('home') }}" class="footer-link">Contact Academic Affairs</a></li>
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
