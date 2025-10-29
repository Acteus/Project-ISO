<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - ISO Quality Education</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        /* Enhanced Modern Dashboard Styles */
        body {
            background: linear-gradient(135deg, rgba(66, 133, 244, 1), rgba(255, 215, 0, 1));
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .survey-main {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(10px);
        }

        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px;
        }

        .dashboard-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            color: #333;
            padding: 40px 30px;
            border-radius: 20px;
            margin-bottom: 40px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(66, 133, 244, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
        }

        .dashboard-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #4285F4, #FF8C00, #FFD700);
        }

        .dashboard-header h1 {
            margin: 0 0 20px 0;
            font-size: 32px;
            font-weight: 800;
            line-height: 1.3;
            color: #2c3e50;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .dashboard-header p {
            margin: 0;
            font-size: 18px;
            line-height: 1.6;
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
            color: #5a6c7d;
            font-weight: 500;
        }

        .admin-info-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(15px);
            padding: 30px;
            border-radius: 16px;
            margin-bottom: 40px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 10px 30px rgba(66, 133, 244, 0.15);
        }

        .admin-info-card h3 {
            margin-top: 0;
            color: #2c3e50;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .metric-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 30px 25px;
            border-radius: 18px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
            text-align: center;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .metric-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #4285F4, #FF8C00, #FFD700);
        }

        .metric-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 25px 60px rgba(66, 133, 244, 0.25);
        }

        .metric-value {
            font-size: 52px;
            font-weight: 900;
            background: linear-gradient(135deg, #4285F4, #FF8C00);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 15px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .metric-label {
            font-size: 16px;
            color: #5a6c7d;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 600;
        }

        .recent-responses {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            padding: 30px;
            border-radius: 18px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
            margin-bottom: 40px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .recent-responses h3 {
            margin-top: 0;
            color: #2c3e50;
            font-size: 24px;
            font-weight: 700;
            border-bottom: 3px solid transparent;
            border-image: linear-gradient(90deg, #4285F4, #FF8C00) 1;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .response-item {
            padding: 18px 20px;
            border-bottom: 1px solid rgba(0,0,0,0.06);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
            border-radius: 12px;
            margin-bottom: 8px;
        }

        .response-item:hover {
            background: linear-gradient(135deg, rgba(66, 133, 244, 0.08), rgba(255, 140, 0, 0.08));
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(66, 133, 244, 0.2);
        }

        .response-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .response-info {
            flex: 1;
        }

        .response-track {
            font-weight: 700;
            color: #4285F4;
            font-size: 16px;
            margin-bottom: 4px;
        }

        .response-date {
            color: #6c757d;
            font-size: 14px;
            font-weight: 500;
        }

        .track-distribution {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            padding: 30px;
            border-radius: 18px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .track-distribution h3 {
            margin-top: 0;
            color: #2c3e50;
            font-size: 24px;
            font-weight: 700;
            border-bottom: 3px solid transparent;
            border-image: linear-gradient(90deg, #FF8C00, #FFD700) 1;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .track-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            border-bottom: 1px solid rgba(0,0,0,0.06);
            transition: all 0.3s ease;
            border-radius: 12px;
            margin-bottom: 8px;
        }

        .track-item:hover {
            background: linear-gradient(135deg, rgba(255, 140, 0, 0.08), rgba(255, 215, 0, 0.08));
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(255, 140, 0, 0.2);
        }

        .track-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .track-name {
            font-weight: 700;
            color: #2c3e50;
            font-size: 16px;
        }

        .track-count {
            background: linear-gradient(135deg, #FF8C00, #FFD700);
            color: white;
            padding: 8px 16px;
            border-radius: 25px;
            font-weight: 700;
            font-size: 14px;
            box-shadow: 0 4px 12px rgba(255, 140, 0, 0.3);
        }

        /* Enhanced Button System */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 14px 28px;
            border: none;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.6s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4285F4, #1e88e5);
            color: white;
            box-shadow: 0 8px 25px rgba(66, 133, 244, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 12px 35px rgba(66, 133, 244, 0.6);
            color: white;
        }

        .btn-success {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #218838, #1fa87a);
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 12px 35px rgba(40, 167, 69, 0.6);
            color: white;
        }

        .btn-warning {
            background: linear-gradient(135deg, #ffc107, #ff9800);
            color: #333;
            font-weight: 800;
            box-shadow: 0 8px 25px rgba(255, 193, 7, 0.4);
        }

        .btn-warning:hover {
            background: linear-gradient(135deg, #e0a800, #f57c00);
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 12px 35px rgba(255, 193, 7, 0.6);
        }

        .actions-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .action-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 35px 30px;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
            text-align: center;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .action-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #4285F4, #FF8C00, #FFD700);
        }

        .action-card:hover {
            transform: translateY(-10px) scale(1.03);
            box-shadow: 0 25px 60px rgba(0,0,0,0.2);
        }

        .action-card-icon {
            width: 90px;
            height: 90px;
            margin: 0 auto 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.4s ease;
            position: relative;
        }

        .action-card:hover .action-card-icon {
            transform: scale(1.15) rotate(5deg);
        }

        .action-card.analytics .action-card-icon {
            background: linear-gradient(135deg, #17a2b8, #138496);
            box-shadow: 0 10px 30px rgba(23, 162, 184, 0.4);
        }

        .action-card.export .action-card-icon {
            background: linear-gradient(135deg, #28a745, #20c997);
            box-shadow: 0 10px 30px rgba(40, 167, 69, 0.4);
        }

        .action-card.audit .action-card-icon {
            background: linear-gradient(135deg, #ffc107, #ff9800);
            box-shadow: 0 10px 30px rgba(255, 193, 7, 0.4);
        }

        .action-card.reports .action-card-icon {
            background: linear-gradient(135deg, #6f42c1, #5a32a3);
            box-shadow: 0 10px 30px rgba(111, 66, 193, 0.4);
        }

        .action-card.qr-codes .action-card-icon {
            background: linear-gradient(135deg, #FF5722, #FF9800);
            box-shadow: 0 10px 30px rgba(255, 87, 34, 0.4);
        }

        .action-card-icon svg {
            width: 45px;
            height: 45px;
            fill: white;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
        }

        .action-card h3 {
            margin: 0 0 15px 0;
            color: #2c3e50;
            font-size: 22px;
            font-weight: 700;
            line-height: 1.3;
        }

        .action-card p {
            color: #5a6c7d;
            margin: 0 0 25px 0;
            font-size: 16px;
            line-height: 1.6;
            font-weight: 500;
        }

        .footer {
            margin-top: 60px;
            padding: 30px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(15px);
            text-align: center;
            color: #5a6c7d;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .no-data {
            text-align: center;
            color: #6c757d;
            font-style: italic;
            padding: 50px 20px;
            font-size: 16px;
        }

        /* Additional modern enhancements */
        .student-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .info-item {
            background: linear-gradient(135deg, rgba(66, 133, 244, 0.05), rgba(255, 140, 0, 0.05));
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        .info-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }

        .info-label {
            font-weight: 700;
            color: #4285F4;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        .info-value {
            font-size: 18px;
            color: #2c3e50;
            font-weight: 600;
        }

        /* Header styling enhancement */
        .header {
            background: rgba(255, 255, 255, 0.15) !important;
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(66, 133, 244, 0.1), rgba(255, 140, 0, 0.1));
            z-index: -1;
        }

        .logo a {
            color: white !important;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
            font-weight: 800;
        }

        .nav-link {
            color: white !important;
            transition: all 0.3s ease;
            font-weight: 600;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .nav-link:hover {
            color: #FFD700 !important;
            transform: translateY(-2px);
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        .nav-link.active {
            color: #FFD700 !important;
            font-weight: 700;
            text-shadow: 0 2px 8px rgba(255, 215, 0, 0.5);
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 20px;
            }

            .metrics-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 20px;
            }

            .actions-section {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .action-card {
                padding: 25px 20px;
            }
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
                    <a href="{{ route('admin.dashboard') }}" class="nav-link active">Dashboard</a>
                    <a href="{{ route('api.survey.analytics') }}" class="nav-link" target="_blank">Analytics</a>
                    <a href="{{ route('admin.ai.insights') }}" class="nav-link">AI Insights</a>
                    <a href="{{ route('admin.reports') }}" class="nav-link">Reports</a>
                    <form method="POST" action="{{ route('student.logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="nav-link logout-btn" style="background: linear-gradient(135deg, #dc3545, #c82333); border: none; color: white; cursor: pointer; padding: 10px 20px; border-radius: 8px; font-weight: 700; transition: all 0.3s ease; text-transform: uppercase; letter-spacing: 1px;">
                            <svg style="width: 16px; height: 16px; vertical-align: middle; margin-right: 8px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
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
                <p>Welcome back, {{ $admin->name }}! Here's your comprehensive ISO 21001 Survey analytics overview with real-time insights and management tools.</p>
            </div>

            <!-- Progress Alerts Section -->
            <div class="progress-alerts" id="progress-alerts" style="margin-bottom: 40px;">
                <!-- Dynamic alerts loaded via JavaScript -->
            </div>

            <!-- Admin Information -->
            <div class="admin-info-card">
                <h3>Administrator Information</h3>
                <div class="student-info-grid">
                    <div class="info-item">
                        <div class="info-label">Username</div>
                        <div class="info-value">{{ $admin->username }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Full Name</div>
                        <div class="info-value">{{ $admin->name }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Role</div>
                        <div class="info-value">System Administrator</div>
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

                <div class="action-card qr-codes">
                    <div class="action-card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M12,2C6.48,2 2,6.48 2,12C2,17.52 6.48,22 12,22C17.52,22 22,17.52 22,12C22,6.48 17.52,2 12,2M8,17C8,15 10,15 10,13C10,11 8,11 8,9C8,7 10,7 10,5C10,3 8,3 6,3H4C2.9,3 2,3.9 2,5V9C2,11.09 3.09,12 4,12H8M13,15C13,17 11,17 11,19C11,21 13,21 13,23C13,25 11,25 9,25H5C3.9,25 3,24.1 3,23V19C3,16.91 4.09,16 5,16H9C10.09,16 11,16.91 11,18V19H13C15.09,19 16,17.09 16,15H13M13,7H9C7.9,7 7,7.9 7,9V11C7,12.09 8.09,13 9,13H11C12.09,13 13,12.09 13,11V9Z"/>
                        </svg>
                    </div>
                    <h3>QR Code Management</h3>
                    <p>Generate and manage QR codes for easy survey access via mobile devices. Create individual or batch QR codes for CSS sections.</p>
                    <a href="{{ route('admin.qr-codes.index') }}" class="btn btn-primary">Manage QR Codes</a>
                </div>

                <div class="action-card reports">
                    <div class="action-card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,4A8,8 0 0,1 20,12A8,8 0 0,1 12,20A8,8 0 0,1 4,12A8,8 0 0,1 12,4M12,6A6,6 0 0,0 6,12A6,6 0 0,0 12,18A6,6 0 0,0 18,12A6,6 0 0,0 12,6M12,8A4,4 0 0,1 16,12A4,4 0 0,1 12,16A4,4 0 0,1 8,12A4,4 0 0,1 12,8Z"/>
                        </svg>
                    </div>
                    <h3>AI Insights Dashboard</h3>
                    <p>Access advanced AI-powered analytics, compliance predictions, and machine learning insights.</p>
                    <a href="{{ route('admin.ai.insights') }}" class="btn btn-primary">AI Insights</a>
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

                <div class="action-card reports">
                    <div class="action-card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                        </svg>
                    </div>
                    <h3>Send Reports</h3>
                    <p>Send weekly progress reports and monthly compliance reports to administrators via email.</p>
                    <a href="{{ route('admin.reports') }}" class="btn btn-primary">Manage Reports</a>
                </div>
            </div>

            <!-- Recent Responses -->
            <div class="recent-responses">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                    <h3 style="margin: 0;">Recent Survey Responses</h3>
                    <a href="{{ route('admin.responses') }}" class="btn btn-success" style="padding: 12px 24px; font-size: 14px; text-decoration: none;">View All Responses →</a>
                </div>
                @if($recentResponses->count() > 0)
                    @foreach($recentResponses as $response)
                        <div class="response-item">
                            <div class="response-info">
                                <div class="response-track">{{ $response->track }} Track - Response #{{ $response->id }}</div>
                                <div class="response-date">{{ $response->created_at->format('M j, Y g:i A') }}</div>
                            </div>
                            <div style="display: flex; gap: 12px; align-items: center;">
                                <span style="background: linear-gradient(135deg, #28a745, #20c997); color: white; padding: 8px 16px; border-radius: 20px; font-size: 12px; font-weight: 600;">Completed</span>
                                <a href="{{ route('admin.response.view', $response->id) }}" class="btn btn-primary" style="padding: 10px 18px; font-size: 14px; text-decoration: none;">View Details</a>
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
                    <h3 class="footer-title" style="color: #2c3e50; font-weight: 700; margin-bottom: 15px;">ISO Learner-Centric Quality Education</h3>
                    <p class="footer-description" style="color: #5a6c7d; font-size: 16px; line-height: 1.6;">
                        Empowering CSS Students through Learner-Centric Quality Education
                    </p>
                </div>
            </div>
            <div class="footer-bottom" style="margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(0,0,0,0.1);">
                <p class="footer-copyright" style="color: #6c757d; font-weight: 500;">
                    © <span id="currentYear"></span> JRU Senior High School. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        // Set current year
        document.getElementById('currentYear').textContent = new Date().getFullYear();

        // Enhanced progress alerts loading
        async function loadProgressAlerts() {
            try {
                const response = await fetch('/api/visualizations/progress-alerts', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                const alerts = result.data;

                updateProgressAlerts(alerts);
            } catch (error) {
                console.error('Error loading progress alerts:', error);
            }
        }

        // Enhanced progress alerts display
        function updateProgressAlerts(alerts) {
            const container = document.getElementById('progress-alerts');

            if (!alerts || alerts.length === 0) {
                container.innerHTML = '';
                return;
            }

            let alertsHtml = '';

            alerts.forEach(alert => {
                const alertClass = alert.type === 'success' ? 'alert-success' :
                                 alert.type === 'warning' ? 'alert-warning' :
                                 alert.type === 'danger' ? 'alert-danger' : 'alert-info';

                const gradientColors = {
                    'alert-success': 'linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(32, 201, 151, 0.1))',
                    'alert-warning': 'linear-gradient(135deg, rgba(255, 193, 7, 0.1), rgba(255, 152, 0, 0.1))',
                    'alert-danger': 'linear-gradient(135deg, rgba(220, 53, 69, 0.1), rgba(232, 62, 97, 0.1))',
                    'alert-info': 'linear-gradient(135deg, rgba(66, 133, 244, 0.1), rgba(255, 140, 0, 0.1))'
                };

                alertsHtml += `
                    <div class="alert ${alertClass}" style="
                        padding: 20px 25px;
                        margin-bottom: 20px;
                        border-radius: 16px;
                        border: none;
                        background: ${gradientColors[alertClass]};
                        backdrop-filter: blur(15px);
                        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                        border-left: 5px solid;
                        transition: all 0.3s ease;
                    ">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            ${alert.icon ? `<div style="font-size: 24px;">${alert.icon}</div>` : ''}
                            <div style="flex: 1;">
                                <strong style="color: #2c3e50; font-size: 16px; font-weight: 700;">${alert.title}</strong>
                                <div style="margin-top: 8px; color: #5a6c7d; font-size: 15px; line-height: 1.5;">${alert.message}</div>
                            </div>
                            ${alert.action ? `<a href="${alert.action.url}" class="btn btn-sm" style="background: linear-gradient(135deg, #4285F4, #1e88e5); color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">${alert.action.text}</a>` : ''}
                        </div>
                    </div>
                `;
            });

            container.innerHTML = alertsHtml;
        }

        // Load progress alerts on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadProgressAlerts();

            // Add smooth animations
            const cards = document.querySelectorAll('.metric-card, .action-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';

                setTimeout(() => {
                    card.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });

        console.log('Enhanced Admin dashboard loaded with modern styling');
    </script>
</body>
</html>
