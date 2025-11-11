<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - ISO Quality Education</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @stack('styles')
</head>
<body class="admin-body" data-login-url="{{ route('student.login') }}" data-home-url="{{ route('survey.landing') }}">
    <!-- Header -->
    <header class="header admin-header">
        <div class="container">
            <div class="nav-wrapper">
                <div class="logo">
                    <a href="{{ route('welcome') }}">ISO Quality Education</a>
                </div>

                <!-- Mobile Menu Toggle -->
                <button class="mobile-menu-btn" id="mobileMenuToggle" aria-label="Toggle menu">
                    <div class="hamburger"></div>
                    <div class="hamburger"></div>
                    <div class="hamburger"></div>
                </button>

                <!-- Desktop navigation -->
                <nav class="desktop-nav">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
                    <a href="{{ route('admin.responses') }}" class="nav-link {{ request()->routeIs('admin.responses') ? 'active' : '' }}">Responses</a>
                    <a href="{{ route('admin.audit.logs') }}" class="nav-link {{ request()->routeIs('admin.audit.logs') ? 'active' : '' }}">Audit Logs</a>
                    <a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}">Reports</a>
                    <a href="{{ route('admin.ai.insights') }}" class="nav-link {{ request()->routeIs('admin.ai.insights') ? 'active' : '' }}">AI Insights</a>
                    <a href="{{ route('admin.performance.dashboard') }}" class="nav-link {{ request()->routeIs('admin.performance.*') ? 'active' : '' }}">Performance</a>
                    <a href="{{ route('admin.indirect-metrics.index') }}" class="nav-link {{ request()->routeIs('admin.indirect-metrics.*') ? 'active' : '' }}">Metrics</a>
                    <a href="{{ route('admin.qr-codes.index') }}" class="nav-link {{ request()->routeIs('admin.qr-codes.*') ? 'active' : '' }}">QR Codes</a>
                    <form method="POST" action="{{ route('student.logout') }}" class="logout-form-desktop" id="logoutFormDesktop">
                        @csrf
                        <button type="submit" class="logout-btn" id="adminLogoutBtn">
                            <svg class="logout-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                            </svg>
                            <span class="desktop-text">Logout</span>
                        </button>
                    </form>
                </nav>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <nav class="mobile-nav" id="mobileNav">
            <a href="{{ route('admin.dashboard') }}" class="mobile-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('admin.responses') }}" class="mobile-nav-link {{ request()->routeIs('admin.responses') ? 'active' : '' }}">Responses</a>
            <a href="{{ route('admin.audit.logs') }}" class="mobile-nav-link {{ request()->routeIs('admin.audit.logs') ? 'active' : '' }}">Audit Logs</a>
            <a href="{{ route('admin.reports') }}" class="mobile-nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}">Reports</a>
            <a href="{{ route('admin.ai.insights') }}" class="mobile-nav-link {{ request()->routeIs('admin.ai.insights') ? 'active' : '' }}">AI Insights</a>
            <a href="{{ route('admin.performance.dashboard') }}" class="mobile-nav-link {{ request()->routeIs('admin.performance.*') ? 'active' : '' }}">Performance</a>
            <a href="{{ route('admin.indirect-metrics.index') }}" class="mobile-nav-link {{ request()->routeIs('admin.indirect-metrics.*') ? 'active' : '' }}">Metrics</a>
            <a href="{{ route('admin.qr-codes.index') }}" class="mobile-nav-link {{ request()->routeIs('admin.qr-codes.*') ? 'active' : '' }}">QR Codes</a>
            <form method="POST" action="{{ route('student.logout') }}" class="logout-form-mobile" id="logoutFormMobile">
                @csrf
                <button type="submit" class="logout-btn logout-btn-mobile">
                    <svg class="logout-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                    </svg>
                    Logout
                </button>
            </form>
        </nav>
    </header>

    <main class="survey-main">
        @yield('content')
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

    <!-- Toast Notification Container -->
    <div id="toast-container" class="toast-container"></div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loading-overlay">
        <div class="loading-spinner"></div>
    </div>

    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/admin.js') }}"></script>
    @stack('scripts')

    @include('partials.admin-logout-modal')
</body>
</html>

