<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - ISO Quality Education</title>
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
            margin: 0 0 15px 0;
            font-size: 28px;
            font-weight: 700;
            line-height: 1.2;
        }

        .dashboard-header p {
            margin: 0;
            opacity: 0.9;
            font-size: 16px;
            line-height: 1.5;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .admin-info-card {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            border-left: 5px solid #4285F4;
        }

        .admin-info-card h3 {
            margin-top: 0;
            color: #333;
        }

        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .metric-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .metric-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #4285F4, #2c6cd6);
        }

        .metric-card:hover {
            transform: translateY(-5px);
        }

        .metric-value {
            font-size: 48px;
            font-weight: 700;
            color: #4285F4;
            margin-bottom: 10px;
        }

        .metric-label {
            font-size: 16px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .recent-responses {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .recent-responses h3 {
            margin-top: 0;
            color: #333;
            border-bottom: 2px solid #4285F4;
            padding-bottom: 10px;
        }

        .response-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background-color 0.2s ease;
        }

        .response-item:hover {
            background-color: #f8f9fa;
        }

        .response-item:last-child {
            border-bottom: none;
        }

        .response-info {
            flex: 1;
        }

        .response-track {
            font-weight: 600;
            color: #4285F4;
        }

        .response-date {
            color: #666;
            font-size: 14px;
        }

        .track-distribution {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .track-distribution h3 {
            margin-top: 0;
            color: #333;
            border-bottom: 2px solid #28a745;
            padding-bottom: 10px;
        }

        .track-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
            transition: all 0.2s ease;
        }

        .track-item:hover {
            background-color: #f8f9fa;
            padding-left: 10px;
        }

        .track-item:last-child {
            border-bottom: none;
        }

        .track-name {
            font-weight: 600;
        }

        .track-count {
            background: #28a745;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
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

        .actions-section {
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
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s ease;
        }

        .action-card:hover .action-card-icon {
            transform: scale(1.1);
        }

        .action-card.analytics .action-card-icon {
            background: linear-gradient(135deg, #17a2b8, #138496);
            box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);
        }

        .action-card.export .action-card-icon {
            background: linear-gradient(135deg, #28a745, #218838);
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }

        .action-card.audit .action-card-icon {
            background: linear-gradient(135deg, #ffc107, #e0a800);
            box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
        }

        .action-card-icon svg {
            width: 40px;
            height: 40px;
            fill: white;
        }

        .action-card h3 {
            margin: 0 0 10px 0;
            color: #333;
        }

        .action-card p {
            color: #666;
            margin: 0 0 20px 0;
        }

        .footer {
            margin-top: 50px;
            padding: 20px;
            background: #f8f9fa;
            text-align: center;
            color: #666;
        }

        .no-data {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 40px 20px;
        }

        .btn-warning {
            background: linear-gradient(90deg, #ffc107, #ff9800);
            color: #333;
            font-weight: 700;
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 193, 7, 0.4);
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
                    <a href="{{ route('welcome') }}">ISO Quality Education</a>
                </div>

                <!-- Desktop navigation -->
                <nav class="desktop-nav">
                    <a href="{{ route('welcome') }}" class="nav-link">Home</a>
                    <a href="{{ route('admin.dashboard') }}" class="nav-link active">Dashboard</a>
                    <span class="nav-link" style="color: rgba(255,255,255,0.8); cursor: default;">{{ $admin->name }}</span>
                    <form method="POST" action="{{ route('student.logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="nav-link logout-btn" style="background: linear-gradient(90deg, #dc3545, #c82333); border: none; color: white; cursor: pointer; padding: 8px 20px; border-radius: 6px; font-weight: 600; transition: all 0.3s ease;">
                            <svg style="width: 16px; height: 16px; vertical-align: middle; margin-right: 5px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                            </svg>
                            Logout
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
                <h1>Admin Dashboard</h1>
                <p>Welcome back, {{ $admin->name }}! Here's your ISO 21001 Survey analytics overview.</p>
            </div>

            <!-- Admin Information -->
            <div class="admin-info-card">
                <h3>Administrator Information</h3>
                <div class="student-info-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 15px;">
                    <div class="info-item" style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                        <div class="info-label" style="font-weight: 600; color: #666; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Username</div>
                        <div class="info-value" style="font-size: 18px; color: #333; margin-top: 5px;">{{ $admin->username }}</div>
                    </div>
                    <div class="info-item" style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                        <div class="info-label" style="font-weight: 600; color: #666; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Full Name</div>
                        <div class="info-value" style="font-size: 18px; color: #333; margin-top: 5px;">{{ $admin->name }}</div>
                    </div>
                    <div class="info-item" style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                        <div class="info-label" style="font-weight: 600; color: #666; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Role</div>
                        <div class="info-value" style="font-size: 18px; color: #333; margin-top: 5px;">System Administrator</div>
                    </div>
                </div>
            </div>

            <!-- Metrics Overview -->
            <div class="metrics-grid">
                <div class="metric-card">
                    <div class="metric-value">{{ $totalResponses }}</div>
                    <div class="metric-label">Total Survey Responses</div>
                </div>
                <div class="metric-card">
                    <div class="metric-value">{{ $responsesByTrack->count() }}</div>
                    <div class="metric-label">Active Tracks</div>
                </div>
                <div class="metric-card">
                    <div class="metric-value">{{ $recentResponses->count() }}</div>
                    <div class="metric-label">Recent Responses</div>
                </div>
                <div class="metric-card">
                    <div class="metric-value">{{ \App\Models\AuditLog::where('action', 'submit_survey_response')->count() }}</div>
                    <div class="metric-label">Audit Events</div>
                </div>
            </div>

            <!-- Action Cards -->
            <div class="actions-section">
                <div class="action-card analytics">
                    <div class="action-card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
                        </svg>
                    </div>
                    <h3>View Detailed Analytics</h3>
                    <p>Access comprehensive survey analytics, trends, and insights from the ISO 21001 quality education system.</p>
                    <a href="{{ route('api.survey.analytics') }}" class="btn btn-primary" target="_blank">View Analytics</a>
                </div>

                <div class="action-card export">
                    <div class="action-card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/>
                        </svg>
                    </div>
                    <h3>Export Data</h3>
                    <p>Export survey responses and analytics reports in Excel, CSV, or PDF format for further analysis.</p>
                    <a href="{{ route('api.export.excel') }}" class="btn btn-success" target="_blank">Export Excel</a>
                </div>

                <div class="action-card audit">
                    <div class="action-card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                        </svg>
                    </div>
                    <h3>Audit Logs</h3>
                    <p>Review system audit logs to ensure compliance with ISO 21001 traceability requirements.</p>
                    <a href="{{ route('admin.audit.logs') }}" class="btn btn-warning">View Logs</a>
                </div>
            </div>

            <!-- Recent Responses -->
            <div class="recent-responses">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <h3 style="margin: 0;">Recent Survey Responses</h3>
                    <a href="{{ route('admin.responses') }}" class="btn btn-success" style="padding: 10px 20px; font-size: 14px; text-decoration: none;">View All Responses →</a>
                </div>
                @if($recentResponses->count() > 0)
                    @foreach($recentResponses as $response)
                        <div class="response-item">
                            <div class="response-info">
                                <div class="response-track">{{ $response->track }} Track - Response #{{ $response->id }}</div>
                                <div class="response-date">{{ $response->created_at->format('M j, Y g:i A') }}</div>
                            </div>
                            <div style="display: flex; gap: 10px; align-items: center;">
                                <span style="background: #28a745; color: white; padding: 5px 10px; border-radius: 15px; font-size: 12px;">Completed</span>
                                <a href="{{ route('admin.response.view', $response->id) }}" class="btn btn-primary" style="padding: 8px 15px; font-size: 14px; text-decoration: none;">View Details</a>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="no-data">
                        <p>No survey responses yet.</p>
                        <p>Survey responses will appear here once students start submitting their feedback.</p>
                    </div>
                @endif
            </div>

            <!-- Track Distribution -->
            <div class="track-distribution">
                <h3>Responses by Academic Track</h3>
                @if($responsesByTrack->count() > 0)
                    @foreach($responsesByTrack as $track)
                        <div class="track-item">
                            <div class="track-name">{{ $track->track }} Track</div>
                            <div class="track-count">{{ $track->count }}</div>
                        </div>
                    @endforeach
                @else
                    <div class="no-data">
                        <p>No track distribution data available yet.</p>
                    </div>
                @endif
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

    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        // Set current year
        document.getElementById('currentYear').textContent = new Date().getFullYear();

        console.log('Admin dashboard loaded');
    </script>
</body>
</html>
