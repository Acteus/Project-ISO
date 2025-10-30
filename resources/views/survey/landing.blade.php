<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>ISO Quality Education Survey</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        /* Landing Page Header - Clean and Modern */
        .landing-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .landing-header .nav-wrapper {
            padding: 1.25rem 0;
        }

        .landing-header .logo a {
            color: #312e81;
            font-size: 1.5rem;
            font-weight: 800;
            text-decoration: none;
            transition: color 0.3s ease;
            letter-spacing: -0.5px;
        }

        .landing-header .logo a:hover {
            color: #4338ca;
        }

        .landing-header .desktop-nav {
            gap: 1.5rem;
        }

        .landing-header .nav-link {
            color: #374151;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .landing-header .nav-link:hover {
            color: #4338ca;
            background: rgba(67, 56, 202, 0.1);
        }

        .landing-header .btn-login {
            background: transparent;
            color: #4338ca;
            border: 2px solid #4338ca;
            padding: 0.5rem 1.25rem;
            border-radius: 8px;
            font-weight: 700;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .landing-header .btn-login:hover {
            background: #4338ca;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(67, 56, 202, 0.3);
        }

        .landing-header .btn-register {
            background: linear-gradient(135deg, #4338ca, #6366f1);
            color: white;
            padding: 0.5rem 1.25rem;
            border-radius: 8px;
            font-weight: 700;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            border: none;
        }

        .landing-header .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(67, 56, 202, 0.4);
            background: linear-gradient(135deg, #312e81, #4338ca);
        }

        .landing-header .btn-profile {
            background: linear-gradient(135deg, #4338ca, #6366f1);
            color: white;
            padding: 0.5rem 1.25rem;
            border-radius: 8px;
            font-weight: 700;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            border: none;
        }

        .landing-header .btn-profile:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(67, 56, 202, 0.4);
            background: linear-gradient(135deg, #312e81, #4338ca);
            color: white;
        }

        .landing-header .logout-btn {
            background: linear-gradient(90deg, #dc3545, #c82333);
            border: none;
            color: white;
            cursor: pointer;
            padding: 0.5rem 1.25rem;
            border-radius: 8px;
            font-weight: 700;
            transition: all 0.3s ease;
        }

        .landing-header .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
        }

        .landing-header .user-greeting {
            color: #374151;
            font-weight: 600;
            padding: 0.5rem 1rem;
            background: rgba(67, 56, 202, 0.08);
            border-radius: 8px;
        }

        /* Mobile menu for landing page */
        .landing-header .mobile-menu-btn {
            display: block;
        }

        .landing-header .menu-toggle {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .landing-header .hamburger {
            width: 1.5rem;
            height: 2px;
            background-color: #312e81;
            transition: all 0.3s ease;
        }

        .landing-header .mobile-nav {
            display: none;
            padding-top: 1rem;
            padding-bottom: 0.75rem;
            gap: 0.75rem;
            flex-direction: column;
        }

        .landing-header .mobile-nav.show {
            display: flex;
        }

        .landing-header .mobile-nav .nav-link {
            color: #374151;
            text-align: left;
            padding: 0.75rem 1rem;
        }

        @media (min-width: 768px) {
            .landing-header .desktop-nav {
                display: flex !important;
            }

            .landing-header .mobile-menu-btn {
                display: none;
            }
        }

        /* Additional mobile improvements for landing page */
        @media (max-width: 768px) {
            .hero-section {
                height: 400px;
                padding: 20px 0;
            }

            .hero-content {
                text-align: center;
                padding: 0 20px;
            }

            .hero-title {
                font-size: 2.5rem;
                margin-bottom: 1rem;
            }

            .hero-description {
                font-size: 1rem;
                margin-bottom: 1.5rem;
            }

            .hero-btn {
                width: 100%;
                max-width: 300px;
                margin: 0 auto;
                display: block;
                min-height: 48px;
                font-size: 16px;
            }

            .info-section {
                padding: 3rem 0;
            }

            .section-title {
                font-size: 1.75rem;
            }

            .info-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .info-card {
                padding: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .hero-title {
                font-size: 2rem;
            }

            .hero-section {
                height: 350px;
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
                        <svg style="width: 24px; height: 24px; display: inline-block; vertical-align: middle; margin-right: 8px;" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5zm0 11.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V7.3l7-3.11v8.8z"/>
                        </svg>
                        ISO Quality Education
                    </a>
                </div>

                <!-- Desktop navigation -->
                <nav class="desktop-nav">
                    @auth
                        <!-- Show for logged-in students -->
                        <span class="user-greeting">{{ Auth::user()->name }}</span>
                        <a href="{{ route('student.dashboard') }}" class="btn-profile">
                            <svg style="width: 16px; height: 16px; vertical-align: middle; margin-right: 5px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                            Profile
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
                        <span class="user-greeting">{{ session('admin')->name }}</span>
                        <a href="{{ route('admin.dashboard') }}" class="btn-profile">
                            <svg style="width: 16px; height: 16px; vertical-align: middle; margin-right: 5px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5zm0 11.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V7.3l7-3.11v8.8z"/>
                            </svg>
                            Admin Dashboard
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
                    <button class="menu-toggle" onclick="toggleMobileMenu()">
                        <span class="hamburger"></span>
                        <span class="hamburger"></span>
                        <span class="hamburger"></span>
                    </button>
                </div>
            </div>

            <!-- Mobile navigation -->
            <nav class="mobile-nav" id="mobileNav">
                @auth
                    <span class="user-greeting" style="margin-bottom: 0.5rem;">{{ Auth::user()->name }}</span>
                    <a href="{{ route('student.dashboard') }}" class="btn-profile" style="text-align: center; display: block; margin-bottom: 0.5rem;">
                        <svg style="width: 16px; height: 16px; vertical-align: middle; margin-right: 5px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                        Profile
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
                    <span class="user-greeting" style="margin-bottom: 0.5rem;">{{ session('admin')->name }}</span>
                    <a href="{{ route('admin.dashboard') }}" class="btn-profile" style="text-align: center; display: block; margin-bottom: 0.5rem;">
                        <svg style="width: 16px; height: 16px; vertical-align: middle; margin-right: 5px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5zm0 11.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V7.3l7-3.11v8.8z"/>
                        </svg>
                        Admin Dashboard
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

    <!-- Debug Info (Remove in production) -->
    @if(config('app.debug'))
    <div style="position: fixed; bottom: 10px; right: 10px; background: rgba(0,0,0,0.8); color: white; padding: 10px; border-radius: 8px; font-size: 12px; z-index: 9999;">
        <strong>Auth Debug:</strong><br>
        @auth
            ✅ Authenticated<br>
            User: {{ Auth::user()->name }}<br>
            ID: {{ Auth::user()->id }}
        @else
            ❌ Not Authenticated<br>
            Guest User
        @endauth
    </div>
    @endif

    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        // Mobile menu toggle for landing page
        function toggleMobileMenu() {
            const mobileNav = document.getElementById('mobileNav');
            if (mobileNav) {
                mobileNav.classList.toggle('show');
            }
        }

        // Set current year
        document.addEventListener('DOMContentLoaded', function() {
            const yearElement = document.getElementById('currentYear');
            if (yearElement) {
                yearElement.textContent = new Date().getFullYear();
            }
        });
    </script>
</body>
</html>
