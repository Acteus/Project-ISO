<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings - ISO Quality Education</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .dashboard-header {
            background: linear-gradient(135deg, #4285F4, #2c6cd6);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
        }

        .dashboard-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }

        .dashboard-header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
        }

        .student-info-card {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            border-left: 5px solid #4285F4;
        }

        .student-info-card h3 {
            margin-top: 0;
            color: #333;
        }

        .student-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 15px;
        }

        .info-item {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .info-label {
            font-weight: 600;
            color: #666;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 18px;
            color: #333;
            margin-top: 5px;
        }

        .dashboard-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .action-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }

        .action-card-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }

        .action-card.survey .action-card-icon {
            color: #28a745;
        }

        .action-card.analytics .action-card-icon {
            color: #17a2b8;
        }

        .action-card.profile .action-card-icon {
            color: #ffc107;
        }

        .action-card h3 {
            margin: 0 0 10px 0;
            color: #333;
        }

        .action-card p {
            color: #666;
            margin: 0 0 20px 0;
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(90deg, #4285F4, #2c6cd6);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(66, 133, 244, 0.4);
            color: white;
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
            color: white;
        }

        .btn-warning {
            background: #ffc107;
            color: #212529;
        }

        .btn-warning:hover {
            background: #e0a800;
            color: #212529;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 193, 7, 0.4);
        }

        .survey-history {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .survey-history h3 {
            margin-top: 0;
            color: #333;
        }

        .no-history {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 40px 20px;
        }

        .footer {
            margin-top: 50px;
            padding: 20px;
            background: #f8f9fa;
            text-align: center;
            color: #666;
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
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

                <!-- Simple student navigation -->
                <nav class="desktop-nav" style="display: flex !important;">
                    <span class="nav-link" style="font-weight: 600; cursor: default;">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('student.logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="logout-btn" style="background: linear-gradient(90deg, #dc3545, #c82333); border: none; color: white; cursor: pointer; padding: 8px 20px; border-radius: 6px; font-weight: 600; transition: all 0.3s ease;">
                            <svg style="width: 16px; height: 16px; vertical-align: middle; margin-right: 5px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                            </svg>
                            Logout
                        </button>
                    </form>
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
                <span class="mobile-nav-link" style="font-weight: 600;">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('student.logout') }}" style="margin-top: 10px;">
                    @csrf
                    <button type="submit" class="logout-btn" style="background: linear-gradient(90deg, #dc3545, #c82333); border: none; color: white; cursor: pointer; padding: 8px 20px; border-radius: 6px; font-weight: 600; transition: all 0.3s ease; width: 100%;">
                        <svg style="width: 16px; height: 16px; vertical-align: middle; margin-right: 5px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </nav>
        </div>
    </header>

    <main class="survey-main">
        <div class="dashboard-container">
            <!-- Profile Header -->
            <div class="dashboard-header">
                <h1>Profile Settings</h1>
                <p>Manage your account information and preferences</p>
            </div>

            <!-- Student Information Card (Read-only) -->
            <div class="student-info-card">
                <h3>Student Information</h3>
                <div class="student-info-grid">
                    <div class="info-item">
                        <div class="info-label">Student ID</div>
                        <div class="info-value">{{ Auth::user()->student_id }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Full Name</div>
                        <div class="info-value">{{ Auth::user()->name }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Year Level</div>
                        <div class="info-value">Grade {{ Auth::user()->year_level }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Section</div>
                        <div class="info-value">{{ Auth::user()->section }}</div>
                    </div>
                </div>
            </div>

            <!-- Profile Settings Form -->
            <div class="survey-history" style="margin-bottom: 30px;">
                <h3>Update Profile Information</h3>
                <form action="#" method="POST" style="max-width: 600px;">
                    @csrf
                    <div style="margin-bottom: 20px;">
                        <label for="name" style="display: block; font-weight: 600; color: #333; margin-bottom: 8px;">Full Name</label>
                        <input type="text" id="name" name="name" value="{{ Auth::user()->name }}"
                               style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px;"
                               required>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label for="email" style="display: block; font-weight: 600; color: #333; margin-bottom: 8px;">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ Auth::user()->email }}"
                               style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px;"
                               required>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label for="section" style="display: block; font-weight: 600; color: #333; margin-bottom: 8px;">Section</label>
                        <input type="text" id="section" name="section" value="{{ Auth::user()->section }}"
                               style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px;"
                               required>
                    </div>

                    <button type="submit" class="btn btn-primary" onclick="event.preventDefault(); alert('Profile update feature coming soon!');">
                        Save Changes
                    </button>
                </form>
            </div>

            <!-- Change Password Section -->
            <div class="survey-history" style="margin-bottom: 30px;">
                <h3>Change Password</h3>
                <form action="#" method="POST" style="max-width: 600px;">
                    @csrf
                    <div style="margin-bottom: 20px;">
                        <label for="current_password" style="display: block; font-weight: 600; color: #333; margin-bottom: 8px;">Current Password</label>
                        <input type="password" id="current_password" name="current_password"
                               style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px;"
                               required>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label for="new_password" style="display: block; font-weight: 600; color: #333; margin-bottom: 8px;">New Password</label>
                        <input type="password" id="new_password" name="new_password"
                               style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px;"
                               required>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label for="confirm_password" style="display: block; font-weight: 600; color: #333; margin-bottom: 8px;">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password"
                               style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px;"
                               required>
                    </div>

                    <button type="submit" class="btn btn-warning" onclick="event.preventDefault(); alert('Password change feature coming soon!');">
                        Update Password
                    </button>
                </form>
            </div>

            <!-- Quick Actions -->
            <div class="survey-history">
                <h3>Quick Actions</h3>
                <div style="display: flex; gap: 15px; flex-wrap: wrap; padding: 20px 0;">
                    <a href="{{ route('survey.form') }}" class="btn btn-success">
                        <svg style="width: 16px; height: 16px; vertical-align: middle; margin-right: 5px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                        </svg>
                        Take Survey
                    </a>
                    <a href="{{ route('survey.landing') }}" class="btn btn-primary">
                        <svg style="width: 16px; height: 16px; vertical-align: middle; margin-right: 5px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                        </svg>
                        Back to Home
                    </a>
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
                        Empowering CSS Strand Students through Learner-Centric Quality Education
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

        // Mobile menu toggle function
        function toggleMobileMenu() {
            const mobileNav = document.getElementById('mobileNav');
            if (mobileNav) {
                mobileNav.classList.toggle('show');
            }
        }

        console.log('Student dashboard loaded');
    </script>
</body>
</html>
