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
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            /* Textured Background with JRU Colors (Blue, Gold, White) */
            background-color: #e8f4f8;
            background-image:
                /* Diagonal stripes texture */
                repeating-linear-gradient(
                    45deg,
                    transparent,
                    transparent 10px,
                    rgba(66, 133, 244, 0.03) 10px,
                    rgba(66, 133, 244, 0.03) 20px
                ),
                repeating-linear-gradient(
                    -45deg,
                    transparent,
                    transparent 10px,
                    rgba(255, 193, 7, 0.02) 10px,
                    rgba(255, 193, 7, 0.02) 20px
                ),
                /* Dot pattern texture */
                radial-gradient(circle at 25% 25%, rgba(66, 133, 244, 0.04) 2px, transparent 2px),
                radial-gradient(circle at 75% 75%, rgba(255, 193, 7, 0.04) 2px, transparent 2px),
                /* Subtle gradient overlay */
                linear-gradient(135deg,
                    rgba(179, 217, 255, 0.4) 0%,
                    rgba(255, 233, 179, 0.3) 50%,
                    rgba(179, 229, 252, 0.4) 100%
                );
            background-size:
                100% 100%,
                100% 100%,
                20px 20px,
                20px 20px,
                100% 100%;
            background-position:
                0 0,
                0 0,
                0 0,
                10px 10px,
                0 0;
            background-attachment: fixed;
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

        .report-type-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(66, 133, 244, 0.3);
        }

        .report-type-btn.active {
            box-shadow: 0 5px 15px rgba(66, 133, 244, 0.3);
        }

        /* Header styling enhancement */
        .header {
            background: linear-gradient(135deg, #1e5a9e 0%, #0d3a6b 100%) !important;
            border-bottom: none;
            box-shadow: 0 4px 15px rgba(30, 90, 158, 0.3);
        }

        .logo a {
            color: #ffff !important;
            font-weight: 800;
        }

        .nav-link {
            color: #ffff !important;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .nav-link:hover {
            color: #ffff !important;
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
    <header class="header admin-header">
        <div class="container">
            <div class="nav-wrapper">
                <div class="logo">
                    <a href="{{ route('welcome') }}">ISO Quality Education</a>
                </div>

                <!-- Desktop navigation -->
                <nav class="desktop-nav">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">Dashboard</a>
                    <a href="{{ route('admin.reports') }}" class="nav-link active">Reports</a>
                    <form method="POST" action="{{ route('student.logout') }}" style="display: inline;" onsubmit="handleAdminLogout(event)">
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
                <p>Access and manage all system reports including ISO compliance and performance monitoring</p>
                
                <!-- Report Type Selector -->
                <div style="margin-top: 30px; display: flex; gap: 15px; justify-content: center; flex-wrap: wrap; margin-bottom: 20px;">
                    <button type="button" onclick="switchReportType('compliance')" id="btn-compliance" class="report-type-btn active" style="padding: 14px 28px; font-size: 15px; font-weight: 700; border: 2px solid #4285F4; background: linear-gradient(135deg, #4285F4, #1e88e5); color: white; border-radius: 12px; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 5px 15px rgba(66, 133, 244, 0.3);">
                        📋 ISO Compliance Reports
                    </button>
                    <button type="button" onclick="switchReportType('performance')" id="btn-performance" class="report-type-btn" style="padding: 14px 28px; font-size: 15px; font-weight: 700; border: 2px solid #4285F4; background: white; color: #4285F4; border-radius: 12px; cursor: pointer; transition: all 0.3s ease;">
                        📊 Performance Monitoring
                    </button>
                </div>

                <!-- ISO Compliance Section Actions -->
                <div id="compliance-actions" style="margin-top: 25px; display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                    <button type="button" onclick="showTestEmailModal()" class="btn btn-secondary" style="padding: 12px 20px; font-size: 14px;">
                        <svg style="width: 18px; height: 18px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                        </svg>
                        Test Email Configuration
                    </button>
                    <button type="button" onclick="generateWeeklyMetrics()" class="btn btn-primary" style="padding: 12px 20px; font-size: 14px;">
                        <svg style="width: 18px; height: 18px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
                        </svg>
                        Generate Weekly Metrics
                    </button>
                </div>

                <!-- Performance Section Actions (removed - content is now inline) -->
                <div id="performance-actions" style="display: none; margin-top: 25px; gap: 15px; justify-content: center; flex-wrap: wrap;"></div>

                <!-- ISO Compliance Info -->
                <div id="compliance-info" style="margin-top: 20px; padding: 15px 25px; background: linear-gradient(135deg, rgba(66, 133, 244, 0.08), rgba(255, 215, 0, 0.08)); border-radius: 12px; font-size: 14px; color: #5a6c7d; max-width: 800px; margin-left: auto; margin-right: auto;">
                    <strong style="color: #2c3e50;">📊 Important:</strong> Weekly metrics must be generated before you can preview or send <strong>both weekly and monthly reports</strong>. Click "Generate Weekly Metrics" to aggregate survey data from the last 12 weeks. Monthly reports are calculated from weekly metrics.
                </div>

                <!-- Performance Info -->
                <div id="performance-info" style="margin-top: 20px; padding: 15px 25px; background: linear-gradient(135deg, rgba(66, 133, 244, 0.08), rgba(255, 215, 0, 0.08)); border-radius: 12px; font-size: 14px; color: #5a6c7d; max-width: 800px; margin-left: auto; margin-right: auto; display: none;">
                    <strong style="color: #2c3e50;">⚡ Performance Monitoring:</strong> View real-time performance metrics for AI services and analytics queries. Monitor system health, identify bottlenecks, and track performance trends over time.
                </div>
            </div>

            <!-- Alert Messages -->
            <div id="alert-container"></div>

            <!-- ISO Compliance Reports Section -->
            <div id="compliance-reports-section">
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
            </div>

            <!-- Performance Monitoring Section -->
            <div id="performance-reports-section" style="display: none;">
                @if(isset($performanceError))
                    <div class="report-card">
                        <div style="text-align: center; padding: 40px; color: #6c757d;">
                            <h3>Error Loading Performance Data</h3>
                            <p>{{ $performanceError }}</p>
                        </div>
                    </div>
                @else
                    @php
                        // Get performance data if available, otherwise use defaults
                        $perfHours = request()->query('perf_hours', 24);
                        $perfAiSummary = $performanceData['ai_service'] ?? null;
                        $perfAnalyticsSummary = $performanceData['analytics_queries'] ?? null;
                        $perfBottleneckAnalysis = $performanceData['bottlenecks'] ?? null;
                        $perfAiTrends = $performanceData['trends']['ai_service'] ?? [];
                        $perfAnalyticsTrends = $performanceData['trends']['analytics_queries'] ?? [];
                    @endphp

                    <!-- Time Period Selector -->
                    <div class="report-card">
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
                            <div>
                                <h3 style="margin: 0 0 10px 0; color: #2c3e50; font-size: 20px; font-weight: 700;">Time Period</h3>
                                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                    <a href="javascript:void(0)" onclick="switchPerformanceTime(24)" class="perf-time-btn {{ $perfHours == 24 ? 'active' : '' }}" data-hours="24" style="padding: 10px 20px; border: 2px solid #4285F4; background: {{ $perfHours == 24 ? '#4285F4' : 'white' }}; color: {{ $perfHours == 24 ? 'white' : '#4285F4' }}; border-radius: 8px; cursor: pointer; font-weight: 600; text-decoration: none; transition: all 0.3s ease;">Last 24 Hours</a>
                                    <a href="javascript:void(0)" onclick="switchPerformanceTime(48)" class="perf-time-btn {{ $perfHours == 48 ? 'active' : '' }}" data-hours="48" style="padding: 10px 20px; border: 2px solid #4285F4; background: {{ $perfHours == 48 ? '#4285F4' : 'white' }}; color: {{ $perfHours == 48 ? 'white' : '#4285F4' }}; border-radius: 8px; cursor: pointer; font-weight: 600; text-decoration: none; transition: all 0.3s ease;">Last 48 Hours</a>
                                    <a href="javascript:void(0)" onclick="switchPerformanceTime(168)" class="perf-time-btn {{ $perfHours == 168 ? 'active' : '' }}" data-hours="168" style="padding: 10px 20px; border: 2px solid #4285F4; background: {{ $perfHours == 168 ? '#4285F4' : 'white' }}; color: {{ $perfHours == 168 ? 'white' : '#4285F4' }}; border-radius: 8px; cursor: pointer; font-weight: 600; text-decoration: none; transition: all 0.3s ease;">Last 7 Days</a>
                                    <a href="javascript:void(0)" onclick="switchPerformanceTime(720)" class="perf-time-btn {{ $perfHours == 720 ? 'active' : '' }}" data-hours="720" style="padding: 10px 20px; border: 2px solid #4285F4; background: {{ $perfHours == 720 ? '#4285F4' : 'white' }}; color: {{ $perfHours == 720 ? 'white' : '#4285F4' }}; border-radius: 8px; cursor: pointer; font-weight: 600; text-decoration: none; transition: all 0.3s ease;">Last 30 Days</a>
                                </div>
                            </div>
                            <div>
                                <button onclick="exportPerformanceReport()" style="background: linear-gradient(135deg, #28a745, #20c997); color: white; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 700; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);">
                                    📊 Export Report
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Overall Health Status -->
                    @if(isset($perfBottleneckAnalysis))
                    <div class="report-card">
                        <h3 style="display: flex; align-items: center; gap: 10px;">
                            <span>Overall System Health</span>
                            <span style="display: inline-block; padding: 8px 20px; border-radius: 20px; font-weight: 700; font-size: 14px; text-transform: uppercase; letter-spacing: 1px; background: linear-gradient(135deg, {{ $perfBottleneckAnalysis['overall_health'] == 'healthy' ? '#28a745, #20c997' : ($perfBottleneckAnalysis['overall_health'] == 'degraded' ? '#ffc107, #ff9800' : '#dc3545, #c82333') }}); color: white;">
                                {{ ucfirst($perfBottleneckAnalysis['overall_health']) }}
                            </span>
                        </h3>
                        <p style="color: #6c757d; margin: 0;">
                            Based on analysis of the last {{ $perfHours }} hours
                        </p>
                    </div>
                    @endif

                    <!-- AI Service Performance -->
                    @if(isset($perfAiSummary) && $perfAiSummary['total_calls'] > 0)
                    <div class="report-card">
                        <h3>🤖 AI Service Performance</h3>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 15px;">
                            <div style="background: linear-gradient(135deg, rgba(66, 133, 244, 0.05), rgba(255, 140, 0, 0.05)); padding: 15px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); border: 1px solid rgba(255, 255, 255, 0.3);">
                                <div style="font-weight: 700; color: #4285F4; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Total Calls</div>
                                <div style="font-size: 24px; color: #2c3e50; font-weight: 700;">{{ number_format($perfAiSummary['total_calls']) }}</div>
                            </div>
                            <div style="background: linear-gradient(135deg, rgba(66, 133, 244, 0.05), rgba(255, 140, 0, 0.05)); padding: 15px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); border: 1px solid rgba(255, 255, 255, 0.3);">
                                <div style="font-weight: 700; color: #4285F4; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Success Rate</div>
                                <div style="font-size: 24px; color: #2c3e50; font-weight: 700;">{{ number_format($perfAiSummary['success_rate'], 2) }}%</div>
                            </div>
                            <div style="background: linear-gradient(135deg, rgba(66, 133, 244, 0.05), rgba(255, 140, 0, 0.05)); padding: 15px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); border: 1px solid rgba(255, 255, 255, 0.3);">
                                <div style="font-weight: 700; color: #4285F4; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Avg Duration</div>
                                <div style="font-size: 24px; color: #2c3e50; font-weight: 700;">{{ number_format($perfAiSummary['average_duration_ms'], 0) }}ms</div>
                            </div>
                            <div style="background: linear-gradient(135deg, rgba(66, 133, 244, 0.05), rgba(255, 140, 0, 0.05)); padding: 15px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); border: 1px solid rgba(255, 255, 255, 0.3);">
                                <div style="font-weight: 700; color: #4285F4; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">P95 Duration</div>
                                <div style="font-size: 24px; color: #2c3e50; font-weight: 700;">{{ number_format($perfAiSummary['p95_duration_ms'], 0) }}ms</div>
                            </div>
                            <div style="background: linear-gradient(135deg, rgba(66, 133, 244, 0.05), rgba(255, 140, 0, 0.05)); padding: 15px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); border: 1px solid rgba(255, 255, 255, 0.3);">
                                <div style="font-weight: 700; color: #4285F4; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">P99 Duration</div>
                                <div style="font-size: 24px; color: #2c3e50; font-weight: 700;">{{ number_format($perfAiSummary['p99_duration_ms'], 0) }}ms</div>
                            </div>
                            <div style="background: linear-gradient(135deg, rgba(66, 133, 244, 0.05), rgba(255, 140, 0, 0.05)); padding: 15px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); border: 1px solid rgba(255, 255, 255, 0.3);">
                                <div style="font-weight: 700; color: #4285F4; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Error Count</div>
                                <div style="font-size: 24px; color: {{ $perfAiSummary['error_count'] > 0 ? '#dc3545' : '#28a745' }}; font-weight: 700;">{{ $perfAiSummary['error_count'] }}</div>
                            </div>
                            <div style="background: linear-gradient(135deg, rgba(66, 133, 244, 0.05), rgba(255, 140, 0, 0.05)); padding: 15px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); border: 1px solid rgba(255, 255, 255, 0.3);">
                                <div style="font-weight: 700; color: #4285F4; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Slow Calls (>2s)</div>
                                <div style="font-size: 24px; color: {{ $perfAiSummary['slow_call_rate'] > 10 ? '#ffc107' : '#28a745' }}; font-weight: 700;">{{ $perfAiSummary['slow_calls'] }} ({{ number_format($perfAiSummary['slow_call_rate'], 1) }}%)</div>
                            </div>
                        </div>

                        @if(!empty($perfAiSummary['by_endpoint']))
                        <div style="margin-top: 25px;">
                            <h4 style="color: #2c3e50; margin-bottom: 15px; font-size: 18px; font-weight: 700;">Top Endpoints</h4>
                            <div style="display: grid; gap: 10px;">
                                @foreach(array_slice($perfAiSummary['by_endpoint'], 0, 5, true) as $endpoint => $stats)
                                <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: rgba(66, 133, 244, 0.05); border-radius: 8px;">
                                    <div>
                                        <strong>{{ $endpoint }}</strong>
                                        <div style="font-size: 12px; color: #6c757d;">
                                            {{ $stats['count'] }} calls • {{ number_format($stats['average_duration_ms'], 0) }}ms avg • {{ number_format($stats['success_rate'], 1) }}% success
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="report-card">
                        <div style="text-align: center; padding: 40px; color: #6c757d;">
                            <h3>No AI Service Data</h3>
                            <p>No AI service calls recorded in the selected time period.</p>
                        </div>
                    </div>
                    @endif

                    <!-- Analytics Query Performance -->
                    @if(isset($perfAnalyticsSummary) && $perfAnalyticsSummary['total_queries'] > 0)
                    <div class="report-card">
                        <h3>📊 Analytics Query Performance</h3>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 15px;">
                            <div style="background: linear-gradient(135deg, rgba(66, 133, 244, 0.05), rgba(255, 140, 0, 0.05)); padding: 15px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); border: 1px solid rgba(255, 255, 255, 0.3);">
                                <div style="font-weight: 700; color: #4285F4; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Total Queries</div>
                                <div style="font-size: 24px; color: #2c3e50; font-weight: 700;">{{ number_format($perfAnalyticsSummary['total_queries']) }}</div>
                            </div>
                            <div style="background: linear-gradient(135deg, rgba(66, 133, 244, 0.05), rgba(255, 140, 0, 0.05)); padding: 15px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); border: 1px solid rgba(255, 255, 255, 0.3);">
                                <div style="font-weight: 700; color: #4285F4; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Avg Duration</div>
                                <div style="font-size: 24px; color: #2c3e50; font-weight: 700;">{{ number_format($perfAnalyticsSummary['average_duration_ms'], 0) }}ms</div>
                            </div>
                            <div style="background: linear-gradient(135deg, rgba(66, 133, 244, 0.05), rgba(255, 140, 0, 0.05)); padding: 15px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); border: 1px solid rgba(255, 255, 255, 0.3);">
                                <div style="font-weight: 700; color: #4285F4; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">P95 Duration</div>
                                <div style="font-size: 24px; color: #2c3e50; font-weight: 700;">{{ number_format($perfAnalyticsSummary['p95_duration_ms'], 0) }}ms</div>
                            </div>
                            <div style="background: linear-gradient(135deg, rgba(66, 133, 244, 0.05), rgba(255, 140, 0, 0.05)); padding: 15px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); border: 1px solid rgba(255, 255, 255, 0.3);">
                                <div style="font-weight: 700; color: #4285F4; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">P99 Duration</div>
                                <div style="font-size: 24px; color: #2c3e50; font-weight: 700;">{{ number_format($perfAnalyticsSummary['p99_duration_ms'], 0) }}ms</div>
                            </div>
                            <div style="background: linear-gradient(135deg, rgba(66, 133, 244, 0.05), rgba(255, 140, 0, 0.05)); padding: 15px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); border: 1px solid rgba(255, 255, 255, 0.3);">
                                <div style="font-weight: 700; color: #4285F4; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Slow Queries (>1s)</div>
                                <div style="font-size: 24px; color: {{ $perfAnalyticsSummary['slow_query_rate'] > 20 ? '#ffc107' : '#28a745' }}; font-weight: 700;">{{ $perfAnalyticsSummary['slow_queries'] }} ({{ number_format($perfAnalyticsSummary['slow_query_rate'], 1) }}%)</div>
                            </div>
                            <div style="background: linear-gradient(135deg, rgba(66, 133, 244, 0.05), rgba(255, 140, 0, 0.05)); padding: 15px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); border: 1px solid rgba(255, 255, 255, 0.3);">
                                <div style="font-weight: 700; color: #4285F4; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Total Rows</div>
                                <div style="font-size: 24px; color: #2c3e50; font-weight: 700;">{{ number_format($perfAnalyticsSummary['total_rows_processed']) }}</div>
                            </div>
                            <div style="background: linear-gradient(135deg, rgba(66, 133, 244, 0.05), rgba(255, 140, 0, 0.05)); padding: 15px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); border: 1px solid rgba(255, 255, 255, 0.3);">
                                <div style="font-weight: 700; color: #4285F4; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Avg Rows/Query</div>
                                <div style="font-size: 24px; color: #2c3e50; font-weight: 700;">{{ number_format($perfAnalyticsSummary['average_rows_per_query'], 0) }}</div>
                            </div>
                        </div>

                        @if(!empty($perfAnalyticsSummary['by_query_type']))
                        <div style="margin-top: 25px;">
                            <h4 style="color: #2c3e50; margin-bottom: 15px; font-size: 18px; font-weight: 700;">Query Types</h4>
                            <div style="display: grid; gap: 10px;">
                                @foreach(array_slice($perfAnalyticsSummary['by_query_type'], 0, 5, true) as $type => $stats)
                                <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: rgba(66, 133, 244, 0.05); border-radius: 8px;">
                                    <div>
                                        <strong>{{ $type }}</strong>
                                        <div style="font-size: 12px; color: #6c757d;">
                                            {{ $stats['count'] }} queries • {{ number_format($stats['average_duration_ms'], 0) }}ms avg • {{ number_format($stats['total_rows']) }} rows
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="report-card">
                        <div style="text-align: center; padding: 40px; color: #6c757d;">
                            <h3>No Analytics Query Data</h3>
                            <p>No analytics queries recorded in the selected time period.</p>
                        </div>
                    </div>
                    @endif

                    <!-- Bottleneck Analysis -->
                    @if(isset($perfBottleneckAnalysis) && !empty($perfBottleneckAnalysis['bottlenecks']))
                    <div class="report-card">
                        <h3>⚠️ Identified Bottlenecks</h3>
                        @foreach($perfBottleneckAnalysis['bottlenecks'] as $bottleneck)
                        <div style="background: rgba(255, 255, 255, 0.9); padding: 20px; border-radius: 12px; margin-bottom: 15px; border-left: 4px solid {{ $bottleneck['severity'] == 'critical' ? '#dc3545' : ($bottleneck['severity'] == 'high' ? '#ffc107' : '#17a2b8') }}; box-shadow: 0 5px 15px rgba(0,0,0,0.08);">
                            <div style="display: flex; align-items: center; margin-bottom: 10px;">
                                <span style="display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-right: 10px; background: {{ $bottleneck['severity'] == 'critical' ? '#dc3545' : ($bottleneck['severity'] == 'high' ? '#ffc107' : '#17a2b8') }}; color: white;">{{ $bottleneck['severity'] }}</span>
                                <strong style="color: #2c3e50;">{{ $bottleneck['issue'] }}</strong>
                            </div>
                            <p style="margin: 0; color: #6c757d; font-size: 14px;">
                                <strong>Recommendation:</strong> {{ $bottleneck['recommendation'] }}
                            </p>
                        </div>
                        @endforeach
                    </div>
                    @elseif(isset($perfBottleneckAnalysis))
                    <div class="report-card">
                        <div style="text-align: center; padding: 20px;">
                            <h3 style="color: #28a745; margin: 0;">✅ No Bottlenecks Identified</h3>
                            <p style="color: #6c757d; margin: 10px 0 0 0;">System is performing well!</p>
                        </div>
                    </div>
                    @endif

                    <!-- Performance Trends -->
                    @if(isset($perfAiTrends) && !empty($perfAiTrends))
                    <div class="report-card">
                        <h3>📈 AI Service Trends (Last 7 Days)</h3>
                        <div style="overflow-x: auto;">
                            <table style="width: 100%; border-collapse: collapse;">
                                <thead>
                                    <tr style="background: rgba(66, 133, 244, 0.1);">
                                        <th style="padding: 12px; text-align: left; border-bottom: 2px solid #4285F4;">Date</th>
                                        <th style="padding: 12px; text-align: right; border-bottom: 2px solid #4285F4;">Calls</th>
                                        <th style="padding: 12px; text-align: right; border-bottom: 2px solid #4285F4;">Avg Duration</th>
                                        <th style="padding: 12px; text-align: right; border-bottom: 2px solid #4285F4;">P95</th>
                                        <th style="padding: 12px; text-align: right; border-bottom: 2px solid #4285F4;">Success Rate</th>
                                        <th style="padding: 12px; text-align: right; border-bottom: 2px solid #4285F4;">Errors</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($perfAiTrends as $trend)
                                    <tr style="border-bottom: 1px solid rgba(0,0,0,0.1);">
                                        <td style="padding: 10px;">{{ $trend['date'] }}</td>
                                        <td style="padding: 10px; text-align: right;">{{ $trend['count'] }}</td>
                                        <td style="padding: 10px; text-align: right;">{{ number_format($trend['average_duration_ms'], 0) }}ms</td>
                                        <td style="padding: 10px; text-align: right;">{{ number_format($trend['p95_duration_ms'], 0) }}ms</td>
                                        <td style="padding: 10px; text-align: right; color: {{ $trend['success_rate'] < 95 ? '#ffc107' : '#28a745' }};">
                                            {{ number_format($trend['success_rate'], 1) }}%
                                        </td>
                                        <td style="padding: 10px; text-align: right; color: {{ $trend['error_count'] > 0 ? '#dc3545' : '#28a745' }};">
                                            {{ $trend['error_count'] }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    @if(isset($perfAnalyticsTrends) && !empty($perfAnalyticsTrends))
                    <div class="report-card">
                        <h3>📈 Analytics Query Trends (Last 7 Days)</h3>
                        <div style="overflow-x: auto;">
                            <table style="width: 100%; border-collapse: collapse;">
                                <thead>
                                    <tr style="background: rgba(66, 133, 244, 0.1);">
                                        <th style="padding: 12px; text-align: left; border-bottom: 2px solid #4285F4;">Date</th>
                                        <th style="padding: 12px; text-align: right; border-bottom: 2px solid #4285F4;">Queries</th>
                                        <th style="padding: 12px; text-align: right; border-bottom: 2px solid #4285F4;">Avg Duration</th>
                                        <th style="padding: 12px; text-align: right; border-bottom: 2px solid #4285F4;">P95</th>
                                        <th style="padding: 12px; text-align: right; border-bottom: 2px solid #4285F4;">P99</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($perfAnalyticsTrends as $trend)
                                    <tr style="border-bottom: 1px solid rgba(0,0,0,0.1);">
                                        <td style="padding: 10px;">{{ $trend['date'] }}</td>
                                        <td style="padding: 10px; text-align: right;">{{ $trend['count'] }}</td>
                                        <td style="padding: 10px; text-align: right;">{{ number_format($trend['average_duration_ms'], 0) }}ms</td>
                                        <td style="padding: 10px; text-align: right;">{{ number_format($trend['p95_duration_ms'], 0) }}ms</td>
                                        <td style="padding: 10px; text-align: right;">{{ number_format($trend['p99_duration_ms'], 0) }}ms</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                @endif
            </div>

            <!-- QR Codes Section (shown for both report types) -->
            <div class="qr-section" id="qr-section">
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

        // Generate Weekly Metrics
        async function generateWeeklyMetrics() {
            if (!confirm('This will generate weekly metrics from survey responses. This may take a moment. Continue?')) {
                return;
            }

            showLoading();

            try {
                const response = await fetch('/admin/reports/generate-metrics', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                const result = await response.json();

                if (result.success) {
                    showAlert('success', result.message + (result.weeks_generated ? ` (${result.weeks_generated} weeks generated)` : ''));
                } else {
                    showAlert('error', result.message);
                }
            } catch (error) {
                console.error('Generate metrics error:', error);
                showAlert('error', 'An error occurred while generating metrics.');
            } finally {
                hideLoading();
            }
        }

        // Report Type Switching
        function switchReportType(type) {
            const complianceSection = document.getElementById('compliance-reports-section');
            const performanceSection = document.getElementById('performance-reports-section');
            const complianceActions = document.getElementById('compliance-actions');
            const performanceActions = document.getElementById('performance-actions');
            const complianceInfo = document.getElementById('compliance-info');
            const performanceInfo = document.getElementById('performance-info');
            const qrSection = document.getElementById('qr-section');
            const btnCompliance = document.getElementById('btn-compliance');
            const btnPerformance = document.getElementById('btn-performance');

            if (type === 'compliance') {
                // Show compliance reports
                complianceSection.style.display = 'block';
                performanceSection.style.display = 'none';
                complianceActions.style.display = 'flex';
                performanceActions.style.display = 'none';
                complianceInfo.style.display = 'block';
                performanceInfo.style.display = 'none';
                qrSection.style.display = 'block';
                
                // Update button styles
                btnCompliance.style.background = 'linear-gradient(135deg, #4285F4, #1e88e5)';
                btnCompliance.style.color = 'white';
                btnCompliance.style.boxShadow = '0 5px 15px rgba(66, 133, 244, 0.3)';
                btnPerformance.style.background = 'white';
                btnPerformance.style.color = '#4285F4';
                btnPerformance.style.boxShadow = 'none';
            } else {
                // Show performance reports
                complianceSection.style.display = 'none';
                performanceSection.style.display = 'block';
                complianceActions.style.display = 'none';
                performanceActions.style.display = 'none';
                complianceInfo.style.display = 'none';
                performanceInfo.style.display = 'block';
                qrSection.style.display = 'none';
                
                // Update button styles
                btnPerformance.style.background = 'linear-gradient(135deg, #4285F4, #1e88e5)';
                btnPerformance.style.color = 'white';
                btnPerformance.style.boxShadow = '0 5px 15px rgba(66, 133, 244, 0.3)';
                btnCompliance.style.background = 'white';
                btnCompliance.style.color = '#4285F4';
                btnCompliance.style.boxShadow = 'none';
            }
        }

        // Switch Performance Time Period
        function switchPerformanceTime(hours) {
            // Update URL with new time period while preserving report type
            const url = new URL(window.location);
            url.searchParams.set('perf_hours', hours);
            // Reload page to fetch new data
            window.location.href = url.toString();
        }

        // Export Performance Report
        function exportPerformanceReport() {
            const perfHours = new URLSearchParams(window.location.search).get('perf_hours') || 24;
            
            // Create a simple text report
            let report = `Performance Monitoring Report\n`;
            report += `Generated: ${new Date().toLocaleString()}\n`;
            report += `Time Period: Last ${perfHours} hours\n`;
            report += `\n${'='.repeat(60)}\n\n`;
            report += `Report data is available via:\n`;
            report += `- Web Dashboard: /admin/reports (Performance Monitoring tab)\n`;
            report += `- API Endpoint: /api/performance/dashboard?hours=${perfHours}\n`;
            report += `- Command Line: php artisan performance:report --hours=${perfHours}\n`;
            
            // Create blob and download
            const blob = new Blob([report], { type: 'text/plain' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `performance-report-${perfHours}h-${new Date().toISOString().split('T')[0]}.txt`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        }

        // Auto-switch to performance tab if perf_hours parameter is present
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('perf_hours')) {
                switchReportType('performance');
            }
        });

        console.log('Enhanced Report management page loaded');
    </script>

    @include('partials.admin-logout-modal')
</body>
</html>
