<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Management - ISO Quality Education</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Page Transition Animation */
        @keyframes pageEnter {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        body {
            animation: pageEnter 0.5s ease-out;
        }

        /* Enhanced Modern Report Management Styles */
        body {
            background: linear-gradient(135deg, rgba(66, 133, 244, 1), rgba(255, 215, 0, 1));
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .survey-main {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(10px);
        }

        .reports-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px;
        }

        .reports-header {
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

        .reports-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #4285F4, #FF8C00, #FFD700);
        }

        .reports-header h1 {
            margin: 0 0 20px 0;
            font-size: 32px;
            font-weight: 800;
            line-height: 1.3;
            color: #2c3e50;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .reports-header p {
            margin: 0;
            font-size: 18px;
            line-height: 1.6;
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
            color: #5a6c7d;
            font-weight: 500;
        }

        .back-btn {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            color: #333;
            padding: 12px 24px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.3);
            display: inline-block;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 1);
            color: #333;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .reports-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .report-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 35px 30px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .report-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #4285F4, #FF8C00, #FFD700);
        }

        .report-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 25px 60px rgba(0,0,0,0.2);
        }

        .report-card h3 {
            margin-top: 0;
            color: #2c3e50;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 3px solid transparent;
            border-image: linear-gradient(90deg, #4285F4, #FF8C00) 1;
        }

        .report-card p {
            color: #5a6c7d;
            margin-bottom: 25px;
            font-size: 16px;
            line-height: 1.6;
            font-weight: 500;
        }

        .report-form {
            margin-top: 25px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .form-group select,
        .form-group input[type="email"],
        .form-group input[type="date"] {
            width: 100%;
            padding: 15px;
            border: 2px solid rgba(0,0,0,0.1);
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
        }

        .form-group select:focus,
        .form-group input:focus {
            border-color: #4285F4;
            outline: none;
            box-shadow: 0 0 0 3px rgba(66, 133, 244, 0.1);
            background: rgba(255, 255, 255, 1);
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
            font-weight: 700;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
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

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d, #5a6268);
            color: white;
            box-shadow: 0 8px 25px rgba(108, 117, 125, 0.4);
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #5a6268, #495057);
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 12px 35px rgba(108, 117, 125, 0.6);
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

        .btn-sm {
            padding: 8px 16px;
            font-size: 12px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-info {
            background: linear-gradient(135deg, #17a2b8, #138496);
            color: white;
            box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);
        }

        .btn-info:hover {
            background: linear-gradient(135deg, #138496, #117a8b);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(23, 162, 184, 0.4);
        }

        .preview-section {
            background: linear-gradient(135deg, rgba(66, 133, 244, 0.08), rgba(255, 140, 0, 0.08));
            backdrop-filter: blur(15px);
            border-radius: 16px;
            padding: 25px;
            margin-top: 25px;
            border-left: 5px solid #4285F4;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .preview-section h4 {
            margin-top: 0;
            color: #2c3e50;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .preview-data {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .preview-item {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        .preview-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .preview-value {
            font-size: 28px;
            font-weight: 900;
            background: linear-gradient(135deg, #4285F4, #FF8C00);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 8px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .preview-label {
            font-size: 13px;
            color: #5a6c7d;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .alert {
            padding: 20px 25px;
            border-radius: 16px;
            margin-bottom: 20px;
            border: none;
            backdrop-filter: blur(15px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(32, 201, 151, 0.1));
            border-left: 5px solid #28a745;
            color: #155724;
        }

        .alert-error {
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.1), rgba(232, 62, 97, 0.1));
            border-left: 5px solid #dc3545;
            color: #721c24;
        }

        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(5px);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .loading-overlay.active {
            display: flex;
        }

        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 6px solid rgba(255,255,255,0.3);
            border-top: 6px solid rgba(66, 133, 244, 1);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* QR Codes Section Styles */
        .qr-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 35px 30px;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
            margin-bottom: 40px;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .section-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .section-header h2 {
            margin: 0 0 15px 0;
            color: #2c3e50;
            font-size: 28px;
            font-weight: 700;
        }

        .section-header p {
            margin: 0;
            color: #5a6c7d;
            font-size: 16px;
            font-weight: 500;
        }

        .qr-codes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .qr-code-card {
            background: linear-gradient(135deg, rgba(66, 133, 244, 0.03), rgba(255, 140, 0, 0.03));
            backdrop-filter: blur(15px);
            border-radius: 16px;
            padding: 25px;
            display: flex;
            align-items: center;
            gap: 20px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .qr-code-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 50px rgba(0,0,0,0.15);
            border-color: #4285F4;
        }

        .qr-image {
            flex-shrink: 0;
            width: 110px;
            height: 110px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            border: 2px solid rgba(0,0,0,0.1);
        }

        .qr-info {
            flex: 1;
            min-width: 0;
        }

        .qr-info h4 {
            margin: 0 0 10px 0;
            color: #2c3e50;
            font-size: 18px;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .qr-info p {
            margin: 0 0 15px 0;
            color: #5a6c7d;
            font-size: 14px;
            line-height: 1.5;
        }

        .qr-status {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .status-active {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }

        .status-inactive {
            background: linear-gradient(135deg, #dc3545, #e74c3c);
            color: white;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
        }

        .scan-count {
            font-size: 12px;
            color: #666;
            font-weight: 600;
        }

        .qr-actions {
            display: flex;
            gap: 10px;
        }

        .no-qr-codes {
            text-align: center;
            padding: 50px 20px;
        }

        .no-data-message h3 {
            margin: 0 0 15px 0;
            color: #5a6c7d;
            font-size: 24px;
            font-weight: 600;
        }

        .no-data-message p {
            margin: 0 0 25px 0;
            color: #6c757d;
            font-size: 16px;
        }

        .qr-section-footer {
            text-align: center;
            padding-top: 25px;
            border-top: 1px solid rgba(0,0,0,0.1);
        }

        /* Test Email Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(5px);
            animation: fadeIn 0.3s ease;
        }

        .modal.active {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 35px;
            border-radius: 20px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: slideUp 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 2px solid rgba(0,0,0,0.1);
        }

        .modal-header h3 {
            margin: 0;
            color: #2c3e50;
            font-size: 24px;
            font-weight: 700;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 32px;
            color: #999;
            cursor: pointer;
            line-height: 1;
            padding: 0;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .close-modal:hover {
            color: #333;
            background: rgba(0,0,0,0.05);
        }

        .modal-body {
            margin-bottom: 25px;
        }

        .modal-body p {
            color: #5a6c7d;
            margin-bottom: 20px;
            line-height: 1.6;
            font-size: 16px;
        }

        .email-config-info {
            background: linear-gradient(135deg, rgba(66, 133, 244, 0.05), rgba(255, 140, 0, 0.05));
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 12px;
            margin-top: 20px;
            font-size: 14px;
            color: #5a6c7d;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .email-config-info strong {
            color: #2c3e50;
            font-weight: 700;
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

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }

        .nav-link {
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
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
            .reports-container {
                padding: 20px;
            }

            .reports-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .qr-codes-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .report-card {
                padding: 25px 20px;
            }

            .qr-code-card {
                flex-direction: column;
                text-align: center;
                gap: 15px;
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
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">Dashboard</a>
                    <a href="{{ route('admin.reports') }}" class="nav-link active">Reports</a>
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
        <div class="reports-container">
            <a href="{{ route('admin.dashboard') }}" class="back-btn">← Back to Dashboard</a>

            <div class="reports-header">
                <h1>Report Management</h1>
                <p>Send weekly progress reports and monthly compliance reports to administrators via Google SMTP</p>
                <div style="margin-top: 25px;">
                    <button type="button" onclick="showTestEmailModal()" class="btn btn-secondary" style="padding: 12px 20px; font-size: 14px;">
                        <svg style="width: 18px; height: 18px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                        </svg>
                        Test Email Configuration
                    </button>
                </div>
            </div>

            <!-- Alert Messages -->
            <div id="alert-container"></div>

            <!-- Reports Grid -->
            <div class="reports-grid">
                <!-- Weekly Progress Report -->
                <div class="report-card">
                    <h3>Weekly Progress Report</h3>
                    <p style="color: #666; margin-bottom: 25px;">Send automated weekly progress summaries with key metrics and insights.</p>

                    <form id="weekly-report-form" class="report-form">
                        <div class="form-group">
                            <label for="weekly_recipient">Recipient Email</label>
                            <select id="weekly_recipient" name="recipient_email" required>
                                <option value="">Select Administrator</option>
                                @foreach($admins as $admin)
                                    <option value="{{ $admin->email }}">{{ $admin->name }} ({{ $admin->email }})</option>
                                @endforeach
                                <option value="custom">Custom Email Address</option>
                            </select>
                        </div>

                        <div class="form-group" id="custom-weekly-email" style="display: none;">
                            <label for="custom_weekly_email">Custom Email Address</label>
                            <input type="email" id="custom_weekly_email" name="custom_email" placeholder="Enter email address">
                        </div>

                        <div class="form-group">
                            <label for="report_week_month">Report Month</label>
                            <select id="report_week_month" name="month" required>
                                <option value="">Select Month</option>
                                <option value="January 2025">January 2025</option>
                                <option value="February 2025">February 2025</option>
                                <option value="March 2025">March 2025</option>
                                <option value="April 2025">April 2025</option>
                                <option value="May 2025">May 2025</option>
                                <option value="June 2025">June 2025</option>
                                <option value="July 2025">July 2025</option>
                                <option value="August 2025">August 2025</option>
                                <option value="September 2025">September 2025</option>
                                <option value="October 2025">October 2025</option>
                                <option value="November 2025">November 2025</option>
                                <option value="December 2025">December 2025</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="week_number">Week Number</label>
                            <select id="week_number" name="week_number" required>
                                <option value="">Select Week</option>
                                <option value="1">Week 1</option>
                                <option value="2">Week 2</option>
                                <option value="3">Week 3</option>
                                <option value="4">Week 4</option>
                                <option value="5">Week 5 (if applicable)</option>
                            </select>
                        </div>

                        <div style="display: flex; gap: 12px;">
                            <button type="button" class="btn btn-secondary" onclick="previewWeeklyReport()">Preview Report</button>
                            <button type="submit" class="btn btn-primary">Send Weekly Report</button>
                        </div>
                    </form>

                    <!-- Weekly Preview Section -->
                    <div id="weekly-preview" class="preview-section" style="display: none;">
                        <h4>Report Preview</h4>
                        <div id="weekly-preview-data" class="preview-data">
                            <!-- Preview data will be populated here -->
                        </div>
                    </div>
                </div>

                <!-- Monthly Compliance Report -->
                <div class="report-card">
                    <h3>Monthly Compliance Report</h3>
                    <p style="color: #666; margin-bottom: 25px;">Send comprehensive monthly compliance reports with detailed analytics and trends.</p>

                    <form id="monthly-report-form" class="report-form">
                        <div class="form-group">
                            <label for="monthly_recipient">Recipient Email</label>
                            <select id="monthly_recipient" name="recipient_email" required>
                                <option value="">Select Administrator</option>
                                @foreach($admins as $admin)
                                    <option value="{{ $admin->email }}">{{ $admin->name }} ({{ $admin->email }})</option>
                                @endforeach
                                <option value="custom">Custom Email Address</option>
                            </select>
                        </div>

                        <div class="form-group" id="custom-monthly-email" style="display: none;">
                            <label for="custom_monthly_email">Custom Email Address</label>
                            <input type="email" id="custom_monthly_email" name="custom_email" placeholder="Enter email address">
                        </div>

                        <div class="form-group">
                            <label for="report_month">Report Month</label>
                            <select id="report_month" name="month" required>
                                <option value="">Select Month</option>
                                <option value="January 2025">January 2025</option>
                                <option value="February 2025">February 2025</option>
                                <option value="March 2025">March 2025</option>
                                <option value="April 2025">April 2025</option>
                                <option value="May 2025">May 2025</option>
                                <option value="June 2025">June 2025</option>
                                <option value="July 2025">July 2025</option>
                                <option value="August 2025">August 2025</option>
                                <option value="September 2025">September 2025</option>
                                <option value="October 2025">October 2025</option>
                                <option value="November 2025">November 2025</option>
                                <option value="December 2025">December 2025</option>
                            </select>
                        </div>

                        <div style="display: flex; gap: 12px;">
                            <button type="button" class="btn btn-secondary" onclick="previewMonthlyReport()">Preview Report</button>
                            <button type="submit" class="btn btn-success">Send Monthly Report</button>
                        </div>
                    </form>

                    <!-- Monthly Preview Section -->
                    <div id="monthly-preview" class="preview-section" style="display: none;">
                        <h4>Report Preview</h4>
                        <div id="monthly-preview-data" class="preview-data">
                            <!-- Preview data will be populated here -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- QR Codes Section -->
            <div class="qr-section">
                <div class="section-header">
                    <h2>QR Code Integration</h2>
                    <p>Include QR codes in reports for easy survey access</p>
                </div>

                <!-- QR Codes Grid -->
                <div class="qr-codes-grid">
                    @if(isset($qrCodes) && count($qrCodes) > 0)
                        @foreach($qrCodes->take(6) as $qrCode)
                        <div class="qr-code-card">
                            <div class="qr-image">
                                @if($qrCode->file_path)
                                    <img src="{{ $qrCode->file_url }}" alt="QR Code" style="width: 100px; height: 100px; object-fit: contain;">
                                @else
                                    <div style="width: 100px; height: 100px; background: rgba(240, 240, 240, 0.8); backdrop-filter: blur(10px); display: flex; align-items: center; justify-content: center; color: #666; font-size: 12px; border-radius: 8px;">No QR Code</div>
                                @endif
                            </div>
                            <div class="qr-info">
                                <h4>{{ $qrCode->name }}</h4>
                                <p>{{ $qrCode->track }} Track |
                                   @if($qrCode->grade_level)Grade {{ $qrCode->grade_level }} | @endif
                                   @if($qrCode->section)Section {{ $qrCode->section }} | @endif
                                   {{ $qrCode->academic_year }}
                                </p>
                                <div class="qr-status">
                                    <span class="status-badge {{ $qrCode->is_active ? 'status-active' : 'status-inactive' }}">
                                        {{ $qrCode->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    @if($qrCode->scan_count > 0)
                                        <span class="scan-count">{{ $qrCode->scan_count }} scans</span>
                                    @endif
                                </div>
                                <div class="qr-actions">
                                    <a href="{{ route('admin.qr-codes.show', $qrCode->id) }}" class="btn btn-sm btn-info">View</a>
                                    <a href="{{ route('admin.qr-codes.download', $qrCode->id) }}" class="btn btn-sm btn-success">Download</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="no-qr-codes">
                            <div class="no-data-message">
                                <h3>No QR Codes Found</h3>
                                <p>Create QR codes to include in reports for easy survey access.</p>
                                <a href="{{ route('admin.qr-codes.create') }}" class="btn btn-primary">Create QR Code</a>
                            </div>
                        </div>
                    @endif
                </div>

                @if(isset($qrCodes) && count($qrCodes) > 6)
                    <div class="qr-section-footer">
                        <a href="{{ route('admin.qr-codes.index') }}" class="btn btn-secondary">View All QR Codes ({{ $qrCodes->total() }})</a>
                    </div>
                @endif
            </div>
        </div>
    </main>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loading-overlay">
        <div class="loading-spinner"></div>
    </div>

    <!-- Test Email Modal -->
    <div class="modal" id="test-email-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>📧 Test Email Configuration</h3>
                <button type="button" class="close-modal" onclick="closeTestEmailModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p>Send a test email to verify that your Google SMTP configuration is working correctly.</p>

                <form id="test-email-form">
                    <div class="form-group">
                        <label for="test_email">Recipient Email Address</label>
                        <input type="email" id="test_email" name="test_email" required placeholder="Enter email address to test">
                    </div>

                    <div class="email-config-info">
                        <strong>Current Configuration:</strong><br>
                        Host: {{ config('mail.mailers.smtp.host') }}<br>
                        Port: {{ config('mail.mailers.smtp.port') }}<br>
                        From: {{ config('mail.from.name') }} <{{ config('mail.from.address') }}>
                    </div>

                    <div style="margin-top: 25px; display: flex; gap: 12px;">
                        <button type="submit" class="btn btn-primary" style="flex: 1;">Send Test Email</button>
                        <button type="button" class="btn btn-secondary" onclick="closeTestEmailModal()" style="flex: 1;">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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

    <script>
        // Set current year
        document.getElementById('currentYear').textContent = new Date().getFullYear();

        // CSRF Token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Show/hide custom email fields
        document.getElementById('weekly_recipient').addEventListener('change', function() {
            const customField = document.getElementById('custom-weekly-email');
            customField.style.display = this.value === 'custom' ? 'block' : 'none';
            if (this.value === 'custom') {
                document.getElementById('custom_weekly_email').required = true;
            } else {
                document.getElementById('custom_weekly_email').required = false;
            }
        });

        document.getElementById('monthly_recipient').addEventListener('change', function() {
            const customField = document.getElementById('custom-monthly-email');
            customField.style.display = this.value === 'custom' ? 'block' : 'none';
            if (this.value === 'custom') {
                document.getElementById('custom_monthly_email').required = true;
            } else {
                document.getElementById('custom_monthly_email').required = false;
            }
        });

        // Weekly Report Form Submission
        document.getElementById('weekly-report-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const recipientSelect = document.getElementById('weekly_recipient');
            const customEmail = document.getElementById('custom_weekly_email');

            if (recipientSelect.value === 'custom') {
                formData.set('recipient_email', customEmail.value);
            }

            showLoading();

            try {
                const response = await fetch('/admin/reports/send-weekly', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    showAlert('success', result.message);
                    this.reset();
                    document.getElementById('weekly-preview').style.display = 'none';
                } else {
                    showAlert('error', result.message);
                }
            } catch (error) {
                showAlert('error', 'An error occurred while sending the report.');
            } finally {
                hideLoading();
            }
        });

        // Monthly Report Form Submission
        document.getElementById('monthly-report-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const recipientSelect = document.getElementById('monthly_recipient');
            const customEmail = document.getElementById('custom_monthly_email');

            if (recipientSelect.value === 'custom') {
                formData.set('recipient_email', customEmail.value);
            }

            showLoading();

            try {
                const response = await fetch('/admin/reports/send-monthly', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    showAlert('success', result.message);
                    this.reset();
                    document.getElementById('monthly-preview').style.display = 'none';
                } else {
                    showAlert('error', result.message);
                }
            } catch (error) {
                showAlert('error', 'An error occurred while sending the report.');
            } finally {
                hideLoading();
            }
        });

        // Preview functions
        async function previewWeeklyReport() {
            const month = document.getElementById('report_week_month').value;
            const weekNumber = document.getElementById('week_number').value;

            if (!month || !weekNumber) {
                showAlert('error', 'Please select both month and week number.');
                return;
            }

            showLoading();

            try {
                const response = await fetch('/admin/reports/preview-weekly', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        month: month,
                        week_number: weekNumber
                    })
                });

                const result = await response.json();

                if (result.success) {
                    displayWeeklyPreview(result.data);
                    document.getElementById('weekly-preview').style.display = 'block';
                } else {
                    showAlert('error', result.message);
                }
            } catch (error) {
                showAlert('error', 'An error occurred while loading the preview.');
            } finally {
                hideLoading();
            }
        }

        async function previewMonthlyReport() {
            const month = document.getElementById('report_month').value;

            if (!month) {
                showAlert('error', 'Please select a month.');
                return;
            }

            showLoading();

            try {
                const response = await fetch('/admin/reports/preview-monthly', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        month: month
                    })
                });

                const result = await response.json();

                if (result.success) {
                    displayMonthlyPreview(result.data);
                    document.getElementById('monthly-preview').style.display = 'block';
                } else {
                    showAlert('error', result.message);
                }
            } catch (error) {
                showAlert('error', 'An error occurred while loading the preview.');
            } finally {
                hideLoading();
            }
        }

        function displayWeeklyPreview(data) {
            const container = document.getElementById('weekly-preview-data');
            const weekStart = new Date(data.week_start).toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            const weekEnd = new Date(data.week_end).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });

            container.innerHTML = `
                <div class="preview-item" style="grid-column: 1 / -1; background: linear-gradient(135deg, rgba(66, 133, 244, 0.1), rgba(255, 215, 0, 0.1)); border-left: 4px solid rgba(66, 133, 244, 1);">
                    <div class="preview-value" style="font-size: 16px; color: #333;">${weekStart} - ${weekEnd}</div>
                    <div class="preview-label">Week ${data.week_number} (ISO Week ${data.iso_week_number})</div>
                </div>
                <div class="preview-item">
                    <div class="preview-value">${parseFloat(data.current.overall_satisfaction).toFixed(2)}</div>
                    <div class="preview-label">Satisfaction</div>
                </div>
                <div class="preview-item">
                    <div class="preview-value">${parseFloat(data.current.compliance_percentage).toFixed(2)}%</div>
                    <div class="preview-label">Compliance</div>
                </div>
                <div class="preview-item">
                    <div class="preview-value">${data.current.new_responses}</div>
                    <div class="preview-label">New Responses</div>
                </div>
                <div class="preview-item">
                    <div class="preview-value" style="color: ${data.current.risk_level === 'Low' ? '#28a745' : (data.current.risk_level === 'Medium' ? '#ffc107' : '#dc3545')}">${data.current.risk_level}</div>
                    <div class="preview-label">Risk Level</div>
                </div>
            `;
        }

        function displayMonthlyPreview(data) {
            const container = document.getElementById('monthly-preview-data');

            const targetsHtml = `
                <div class="preview-item" style="grid-column: 1 / -1; background: ${data.targets_achieved.satisfaction && data.targets_achieved.compliance ? '#d4edda' : '#f8d7da'}; border-left: 4px solid ${data.targets_achieved.satisfaction && data.targets_achieved.compliance ? '#28a745' : '#dc3545'};">
                    <div class="preview-value" style="font-size: 16px; color: #333;">${data.targets_achieved.satisfaction && data.targets_achieved.compliance ? 'All Targets Met' : 'Some Targets Not Met'}</div>
                    <div class="preview-label">Monthly Target Status</div>
                </div>
            `;

            container.innerHTML = targetsHtml + `
                <div class="preview-item">
                    <div class="preview-value">${parseFloat(data.monthly_averages.overall_satisfaction).toFixed(2)}</div>
                    <div class="preview-label">Avg Satisfaction</div>
                </div>
                <div class="preview-item">
                    <div class="preview-value">${parseFloat(data.monthly_averages.compliance_score).toFixed(2)}</div>
                    <div class="preview-label">Avg Compliance</div>
                </div>
                <div class="preview-item">
                    <div class="preview-value">${Math.round(data.monthly_averages.total_responses)}</div>
                    <div class="preview-label">Total Responses</div>
                </div>
                <div class="preview-item">
                    <div class="preview-value">${data.weekly_data.length}</div>
                    <div class="preview-label">Weeks Covered</div>
                </div>
            `;
        }

        function showAlert(type, message) {
            const container = document.getElementById('alert-container');
            const alertClass = type === 'success' ? 'alert-success' : 'alert-error';
            container.innerHTML = `<div class="alert ${alertClass}">${message}</div>`;

            // Auto-hide after 5 seconds
            setTimeout(() => {
                container.innerHTML = '';
            }, 5000);
        }

        function showLoading() {
            document.getElementById('loading-overlay').classList.add('active');
        }

        function hideLoading() {
            document.getElementById('loading-overlay').classList.remove('active');
        }

        // Test Email Modal Functions
        function showTestEmailModal() {
            document.getElementById('test-email-modal').classList.add('active');
        }

        function closeTestEmailModal() {
            document.getElementById('test-email-modal').classList.remove('active');
            document.getElementById('test-email-form').reset();
        }

        // Close modal when clicking outside
        document.getElementById('test-email-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeTestEmailModal();
            }
        });

        // Test Email Form Submission
        document.getElementById('test-email-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const testEmail = document.getElementById('test_email').value;

            showLoading();
            closeTestEmailModal();

            try {
                const response = await fetch('/admin/reports/test-email', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        test_email: testEmail
                    })
                });

                const result = await response.json();

                if (result.success) {
                    showAlert('success', result.message + ' Check your inbox at: ' + testEmail);
                    console.log('Email configuration verified:', result.config);
                } else {
                    showAlert('error', result.message);
                }
            } catch (error) {
                console.error('Test email error:', error);
                showAlert('error', 'An error occurred while sending the test email.');
            } finally {
                hideLoading();
            }
        });

        console.log('Enhanced Report management page loaded');
    </script>
</body>
</html>
