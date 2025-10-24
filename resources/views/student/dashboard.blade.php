<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - ISO Quality Education</title>
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
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="nav-wrapper">
                <div class="logo">
                    <a href="{{ route('welcome') }}">ISO Quality Education</a>
                </div>

                <!-- Desktop navigation -->
                <nav class="desktop-nav">
                    <a href="{{ route('welcome') }}" class="nav-link">Home</a>
                    <a href="{{ route('survey.form') }}" class="nav-link">Survey</a>
                    <form method="POST" action="{{ route('student.logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="nav-link" style="background: none; border: none; color: inherit; cursor: pointer;">
                            Logout ({{ Auth::user()->name }})
                        </button>
                    </form>
                </nav>
            </div>
        </div>
    </header>

    <main class="survey-main">
        <div class="dashboard-container">
            <!-- Dashboard Header -->
            <div class="dashboard-header">
                <h1>Student Dashboard</h1>
                <p>Welcome back, {{ Auth::user()->name }}! Here's your ISO 21001 Survey overview.</p>
            </div>

            <!-- Student Information -->
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

            <!-- Action Cards -->
            <div class="dashboard-actions">
                <div class="action-card survey">
                    <div class="action-card-icon">📝</div>
                    <h3>Take Survey</h3>
                    <p>Complete the ISO 21001 Learner-Centric Survey to provide feedback on your educational experience.</p>
                    <a href="{{ route('survey.form') }}" class="btn btn-success">Start Survey</a>
                </div>

                <div class="action-card analytics">
                    <div class="action-card-icon">📊</div>
                    <h3>View Analytics</h3>
                    <p>Check out aggregated survey results and insights from the ISO 21001 quality education system.</p>
                    <a href="{{ route('api.survey.analytics') }}" class="btn btn-primary" target="_blank">View Analytics</a>
                </div>

                <div class="action-card profile">
                    <div class="action-card-icon">👤</div>
                    <h3>Profile Settings</h3>
                    <p>Update your profile information and manage your account settings.</p>
                    <button class="btn btn-warning" onclick="alert('Profile management coming soon!')">Manage Profile</button>
                </div>
            </div>

            <!-- Survey History -->
            <div class="survey-history">
                <h3>Survey History</h3>
                <div class="no-history">
                    <p>You haven't submitted any surveys yet.</p>
                    <p>Complete your first survey to see your submission history here!</p>
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

        console.log('Student dashboard loaded');
    </script>
</body>
</html>
