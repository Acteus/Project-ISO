<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>ISO Quality Education Survey</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}?v={{ \Illuminate\Support\Facades\File::lastModified(public_path('css/styles.css')) }}">
    <link rel="preload" as="image" href="{{ asset('images/HomeBG.jpg') }}" fetchpriority="high">
    <style>
        /* Mobile Optimizations */
        @media (max-width: 768px) {
            /* Disable heavy effects on mobile */
            .landing-header {
                backdrop-filter: none;
                -webkit-backdrop-filter: none;
                background: rgba(255, 255, 255, 1);
            }
            
            /* Optimize hero section for mobile */
            .hero-section {
                height: auto;
                min-height: 400px;
                padding: 3rem 0;
            }
            
            .hero-overlay {
                background-color: rgba(49, 46, 129, 0.75);
            }
            
            /* Reduce animation complexity */
            .hero-content * {
                animation-duration: 0.3s !important;
            }
            
            /* Optimize info cards */
            .info-card {
                transform: none !important;
                transition: none !important;
            }
            
            /* Improve touch targets */
            .btn, .btn-login, .btn-register, .btn-profile, .logout-btn {
                min-height: 44px;
                min-width: 44px;
                padding: 0.75rem 1.25rem;
                touch-action: manipulation;
            }
            
            /* Optimize footer */
            .footer {
                padding: 2rem 1rem;
            }
        }
        
        @media (max-width: 480px) {
            .hero-section {
                min-height: 350px;
                padding: 2rem 0;
            }
            
            .hero-title {
                font-size: 1.75rem !important;
                line-height: 1.2;
            }
            
            .hero-subtitle {
                font-size: 1rem;
            }
            
            .hero-description {
                font-size: 0.9rem;
                padding: 0 1rem;
            }
            
            .info-section {
                padding: 2rem 0;
            }
            
            .section-title {
                font-size: 1.5rem;
            }
            
            .info-card {
                padding: 1.25rem;
            }
        }
        
        /* Disable animations for reduced motion preference */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
        
        /* Performance: Use will-change sparingly */
        @media (max-width: 768px) {
            .hero-section,
            .hero-overlay,
            .info-card {
                will-change: auto;
            }
        }
    </style>
</head>
<body>
    <!-- Landing Page Header -->
    <header class="landing-header">
        <div class="container">
            <div class="nav-wrapper">
                <div class="logo">
                    <a href="{{ route('survey.landing') }}">
                        ISO Quality Education
                    </a>
                </div>

                <!-- Desktop navigation -->
                <nav class="desktop-nav" role="navigation" aria-label="Primary">
                    @auth
                        <!-- Show for logged-in students -->
                        <a href="{{ route('student.dashboard') }}" class="btn-profile">
                            {{ Auth::user()->name }}
                        </a>
                        <form method="POST" action="{{ route('student.logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="logout-btn">
                                <svg style="width: 16px; height: 16px; vertical-align: middle; margin-right: 5px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                                </svg>
                                Logout
                            </button>
                        </form>
                    @elseif(session('admin'))
                        <!-- Show for logged-in admins -->
                        <a href="{{ route('admin.dashboard') }}" class="btn-profile">
                            {{ session('admin')->name }}
                        </a>
                        <form method="POST" action="{{ route('student.logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="logout-btn">
                                <svg style="width: 16px; height: 16px; vertical-align: middle; margin-right: 5px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                                </svg>
                                Logout
                            </button>
                        </form>
                    @else
                        <!-- Show for non-authenticated users -->
                        <a href="{{ route('student.login') }}" class="btn-login">
                            <svg style="width: 16px; height: 16px; vertical-align: middle; margin-right: 5px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M11 7L9.6 8.4l2.6 2.6H2v2h10.2l-2.6 2.6L11 17l5-5-5-5zm9 12h-8v2h8c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-8v2h8v14z"/>
                            </svg>
                            Login
                        </a>
                        <a href="{{ route('student.register') }}" class="btn-register">
                            <svg style="width: 16px; height: 16px; vertical-align: middle; margin-right: 5px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                            Get Started
                        </a>
                    @endauth
                </nav>

                <!-- Mobile menu button -->
                <div class="mobile-menu-btn">
                    <button id="mobileMenuButton" class="menu-toggle" onclick="toggleMobileMenu()" aria-controls="mobileNav" aria-expanded="false" aria-label="Toggle navigation menu">
                        <span class="hamburger"></span>
                        <span class="hamburger"></span>
                        <span class="hamburger"></span>
                    </button>
                </div>
            </div>

            <!-- Mobile navigation -->
            <nav class="mobile-nav" id="mobileNav" role="navigation" aria-label="Mobile">
                @auth
                    <a href="{{ route('student.dashboard') }}" class="btn-profile" style="text-align: center; display: block; margin-bottom: 0.5rem;">
                        {{ Auth::user()->name }}
                    </a>
                    <form method="POST" action="{{ route('student.logout') }}">
                        @csrf
                        <button type="submit" class="logout-btn" style="width: 100%;">
                            <svg style="width: 16px; height: 16px; vertical-align: middle; margin-right: 5px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                            </svg>
                            Logout
                        </button>
                    </form>
                @elseif(session('admin'))
                    <a href="{{ route('admin.dashboard') }}" class="btn-profile" style="text-align: center; display: block; margin-bottom: 0.5rem;">
                        {{ session('admin')->name }}
                    </a>
                    <form method="POST" action="{{ route('student.logout') }}">
                        @csrf
                        <button type="submit" class="logout-btn" style="width: 100%;">
                            <svg style="width: 16px; height: 16px; vertical-align: middle; margin-right: 5px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                            </svg>
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('student.login') }}" class="btn-login" style="text-align: center; display: block; margin-bottom: 0.5rem;">
                        <svg style="width: 16px; height: 16px; vertical-align: middle; margin-right: 5px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M11 7L9.6 8.4l2.6 2.6H2v2h10.2l-2.6 2.6L11 17l5-5-5-5zm9 12h-8v2h8c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-8v2h8v14z"/>
                        </svg>
                        Login
                    </a>
                    <a href="{{ route('student.register') }}" class="btn-register" style="text-align: center; display: block;">
                        <svg style="width: 16px; height: 16px; vertical-align: middle; margin-right: 5px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                        Get Started
                    </a>
                @endauth
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

                <!-- Privacy Notice (GDPR & ISO 27001) -->
                <div style="background: #f0f4ff; border-left: 4px solid #4338ca; padding: 1.5rem; margin-top: 2rem; border-radius: 8px;">
                    <h3 style="color: #312e81; margin-bottom: 1rem; font-size: 1.1rem; font-weight: 700;">
                        <svg style="width: 20px; height: 20px; vertical-align: middle; margin-right: 8px; fill: #4338ca;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/>
                        </svg>
                        Your Privacy Matters (GDPR & ISO 27001 Compliant)
                    </h3>
                    <p style="color: #374151; line-height: 1.6; margin-bottom: 0.75rem;">
                        We are committed to protecting your privacy. This survey complies with GDPR and ISO 27001 data protection standards:
                    </p>
                    <ul style="color: #374151; line-height: 1.8; margin-left: 1.5rem; margin-bottom: 0.75rem;">
                        <li><strong>Encrypted Data:</strong> Your student ID and comments are encrypted using AES-256 encryption</li>
                        <li><strong>Data Minimization:</strong> We only collect essential ISO 21001 metrics necessary for quality assessment</li>
                        <li><strong>Purpose Limitation:</strong> Data is used solely for educational quality improvement</li>
                        <li><strong>Retention:</strong> Data is retained for 7 years as per ISO 21001 requirements</li>
                        <li><strong>Your Rights:</strong> You can request access, correction, or deletion of your data at any time</li>
                    </ul>
                    <p style="color: #6b7280; font-size: 0.9rem; margin: 0;">
                        By proceeding with the survey, you will be asked to provide explicit consent before submission. 
                        <a href="{{ route('survey.privacy') }}" style="color: #4338ca; text-decoration: underline;">Learn more about our privacy policy</a>.
                    </p>
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

    <script src="{{ asset('js/main.js') }}" defer></script>
    <script>
        // Mobile menu toggle for landing page
        function toggleMobileMenu() {
            const mobileNav = document.getElementById('mobileNav');
            const toggleBtn = document.getElementById('mobileMenuButton');
            if (!mobileNav || !toggleBtn) return;

            const isOpen = mobileNav.classList.toggle('show');
            toggleBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            toggleBtn.classList.toggle('is-open', isOpen);
        }

        // Set current year
        document.addEventListener('DOMContentLoaded', function() {
            const yearElement = document.getElementById('currentYear');
            if (yearElement) {
                yearElement.textContent = new Date().getFullYear();
            }
        });
    </script>

    <!-- Logout Modal Script -->
    @include('partials.logout-modal')
</body>
</html>
