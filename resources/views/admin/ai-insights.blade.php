<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>AI Insights - ISO Quality Education</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}?v={{ time() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Accessibility: Visible Focus */
        :focus-visible {
            outline: 3px solid #4285F4;
            outline-offset: 2px;
            transition: outline 0.2s ease;
        }

        /* Screen reader only content */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        /* High contrast mode support */
        @media (prefers-contrast: high) {
            .btn-primary {
                background: #000 !important;
                color: #fff !important;
                border: 2px solid #fff !important;
            }
            .btn-success {
                background: #000 !important;
                color: #fff !important;
                border: 2px solid #fff !important;
            }
            .metric-card, .insight-card, .ai-results {
                border: 2px solid #000 !important;
            }
        }

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

        @media (prefers-reduced-motion: reduce) {
            body, .insight-card, .metric-card {
                animation: none !important;
                transition: none !important;
            }
        }

        /* Enhanced Modern AI Insights Styles */
        body {
            background: linear-gradient(135deg, rgba(66, 133, 244, 1), rgba(255, 215, 0, 1));
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .survey-main {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(10px);
        }

        .insights-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px;
            display: grid;
            grid-template-columns: 1fr 450px;
            gap: 30px;
            align-items: start;
        }

        .insights-content {
            min-width: 0; /* Fix for grid overflow */
        }

        .insights-sidebar {
            position: sticky;
            top: 20px;
            max-height: calc(100vh - 40px);
            overflow-y: auto;
            margin-top: 90px; /* Align with insights-header */
        }

        .insights-header {
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

        .insights-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #4285F4, #FF8C00, #FFD700);
        }

        .insights-header h1 {
            margin: 0 0 20px 0;
            font-size: 32px;
            font-weight: 800;
            line-height: 1.3;
            color: #2c3e50;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .insights-header p {
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

        .insights-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .insight-card {
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

        .insight-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #4285F4, #FF8C00, #FFD700);
        }

        .insight-card:hover {
            transform: translateY(-10px) scale(1.03);
            box-shadow: 0 25px 60px rgba(0,0,0,0.2);
        }

        .insight-card h3 {
            margin-top: 0;
            color: #2c3e50;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 3px solid transparent;
            border-image: linear-gradient(90deg, #4285F4, #FF8C00) 1;
        }

        .insight-card p {
            color: #5a6c7d;
            margin-bottom: 25px;
            font-size: 16px;
            line-height: 1.6;
            font-weight: 500;
        }

        .ai-metrics {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .metric-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 30px 20px;
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
            font-size: 48px;
            font-weight: 900;
            background: linear-gradient(135deg, #4285F4, #FF8C00);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
            /* Fallback for accessibility */
            color: #2c3e50; /* Dark color for contrast */
        }

        /* Ensure gradient text has sufficient contrast fallback */
        @media (prefers-reduced-transparency: reduce) {
            .metric-value {
                -webkit-text-fill-color: #2c3e50 !important;
                background: none !important;
            }
        }

        .metric-label {
            font-size: 14px;
            color: #5a6c7d;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 600;
            line-height: 1.3;
            min-height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
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

        .alert-warning {
            background: linear-gradient(135deg, rgba(255, 193, 7, 0.1), rgba(255, 152, 0, 0.1));
            border-left: 5px solid #ffc107;
            color: #856404;
        }

        .ai-results {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            animation: slideInRight 0.4s ease-out;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .ai-results h3 {
            margin-top: 0;
            color: #2c3e50;
            font-size: 20px;
            font-weight: 700;
            border-bottom: 3px solid transparent;
            border-image: linear-gradient(90deg, #4285F4, #FF8C00) 1;
            padding-bottom: 12px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .results-close-btn {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            border: none;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 700;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .results-close-btn:hover {
            transform: rotate(90deg) scale(1.1);
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
        }

        .ai-results-placeholder {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .ai-results-placeholder svg {
            width: 64px;
            height: 64px;
            fill: #ddd;
            margin-bottom: 20px;
        }

        .ai-results-placeholder p {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
        }

        .result-item {
            padding: 20px 25px;
            border: 1px solid rgba(0,0,0,0.06);
            border-radius: 16px;
            margin-bottom: 15px;
            background: linear-gradient(135deg, rgba(66, 133, 244, 0.03), rgba(255, 140, 0, 0.03));
            transition: all 0.3s ease;
        }

        .result-item:hover {
            background: linear-gradient(135deg, rgba(66, 133, 244, 0.08), rgba(255, 140, 0, 0.08));
            transform: translateX(5px);
            box-shadow: 0 8px 25px rgba(66, 133, 244, 0.15);
        }

        .result-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .result-title {
            font-weight: 700;
            color: #2c3e50;
            font-size: 16px;
        }

        .result-confidence {
            background: linear-gradient(135deg, #4285F4, #1e88e5);
            color: white;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .result-confidence {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .confidence-badge-icon {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
        }

        .conf-high { background: #28a745; }
        .conf-mid  { background: #ffc107; }
        .conf-low  { background: #dc3545; }

        .result-details {
            color: #5a6c7d;
            font-size: 14px;
            line-height: 1.6;
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

        .nav-link:hover {
            color: #4285F4;
            transform: translateY(-1px);
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
        @media (max-width: 1200px) {
            .insights-container {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .insights-sidebar {
                position: relative;
                top: auto;
                max-height: none;
                order: -1; /* Move results to top on mobile */
                margin-top: 0; /* Remove top margin on mobile */
            }
        }

        @media (max-width: 768px) {
            .insights-container {
                padding: 20px;
            }

            .insights-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .ai-metrics {
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                gap: 20px;
            }

            .insight-card {
                padding: 25px 20px;
            }

            /* Ensure touch targets are at least 44x44px on mobile */
            .btn {
                min-height: 44px;
                min-width: 44px;
                padding: 12px 24px;
            }

            .results-close-btn {
                min-height: 44px;
                min-width: 44px;
            }

            /* Improve focus visibility on mobile */
            :focus-visible {
                outline: 4px solid #4285F4;
                outline-offset: 3px;
            }
        }

        /* Touch device optimizations */
        @media (hover: none) and (pointer: coarse) {
            .btn:hover {
                transform: none; /* Remove hover transforms on touch devices */
            }

            .insight-card:hover,
            .metric-card:hover {
                transform: none; /* Remove hover transforms on touch devices */
            }
        }
        .skip-link {
            position: absolute;
            top: -40px;
            left: 0;
            background: #000;
            color: white;
            padding: 8px;
            z-index: 100;
            transition: top 0.3s;
        }

        .skip-link:focus {
            top: 0;
        }
    </style>
</head>
<body>
    <a href="#main-content" class="skip-link">Skip to main content</a>
    <!-- Header -->
    <header class="header admin-header" role="banner">
        <div class="container">
            <div class="nav-wrapper">
                <div class="logo">
                    <a href="{{ route('welcome') }}" aria-label="ISO Quality Education - Go to home page">ISO Quality Education</a>
                </div>

                <!-- Desktop navigation -->
                <nav class="desktop-nav" aria-label="Main navigation">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link" aria-label="Go to Admin Dashboard">Dashboard</a>
                    <a href="{{ route('admin.ai.insights') }}" class="nav-link active" aria-current="page" aria-label="AI Insights - Current page">AI Insights</a>
                    <form method="POST" action="{{ route('student.logout') }}" style="display: inline;" aria-label="Logout form">
                        @csrf
                        <button type="submit" class="nav-link logout-btn" aria-label="Logout from admin account" style="background: linear-gradient(135deg, #dc3545, #c82333); border: none; color: white; cursor: pointer; padding: 10px 20px; border-radius: 8px; font-weight: 700; transition: all 0.3s ease; text-transform: uppercase; letter-spacing: 1px;">
                            <svg style="width: 16px; height: 16px; vertical-align: middle; margin-right: 8px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                            </svg>
                            Logout
                        </button>
                    </form>
                </nav>
            </div>
        </div>
    </header>

    <main class="survey-main" id="main-content" role="main">
        <div class="insights-container">
            <!-- Main Content Area -->
            <div class="insights-content">
                <a href="{{ route('admin.dashboard') }}" class="back-btn">← Back to Dashboard</a>

                <section class="insights-header" aria-labelledby="insights-heading">
                    <h1 id="insights-heading">AI Insights Dashboard</h1>
                    <p>Comprehensive machine learning analytics for ISO 21001 compliance, predictive modeling, and proactive quality management</p>
                    <div id="data-range-display" role="status" aria-live="polite" aria-label="Data range information" style="margin-top: 25px; padding: 15px 30px; background: rgba(66, 133, 244, 0.1); backdrop-filter: blur(10px); border-radius: 25px; display: inline-block; font-weight: 600; color: #333; font-size: 14px; border: 1px solid rgba(255, 255, 255, 0.3);">
                        <svg style="width: 20px; height: 20px; vertical-align: middle; margin-right: 10px; fill: rgba(66, 133, 244, 1);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
                            <title>Data range calendar icon</title>
                            <path d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/>
                        </svg>
                        <span id="data-range-text">Loading data range...</span>
                    </div>
                    <div id="data-stats-display" role="status" aria-live="polite" aria-label="Survey data statistics" style="margin-top: 15px; padding: 10px 25px; background: rgba(255, 255, 255, 0.5); backdrop-filter: blur(10px); border-radius: 20px; display: inline-block; font-size: 13px; color: #555; margin-left: 10px;">
                        <span id="data-stats-text">Analyzing <strong id="total-responses-count">0</strong> survey responses</span>
                    </div>
                </section>

                <!-- Alert Messages -->
                <div id="alert-container" role="status" aria-live="polite" aria-atomic="true"></div>

                <!-- AI Metrics Overview -->
                <section class="ai-metrics" aria-label="AI Metrics Overview">
                    <h2 class="sr-only">AI Service Metrics</h2>
                    <div class="metric-card" role="status" aria-labelledby="service-status-label">
                        <div class="metric-value" id="service-status" aria-describedby="service-status-label">Checking...</div>
                        <div class="metric-label" id="service-status-label">AI Service Status</div>
                    </div>
                    <div class="metric-card" role="status" aria-labelledby="predictions-label">
                        <div class="metric-value" id="total-predictions" aria-describedby="predictions-label">0</div>
                        <div class="metric-label" id="predictions-label">AI Predictions Today</div>
                    </div>
                    <div class="metric-card" role="status" aria-labelledby="accuracy-label">
                        <div class="metric-value" id="accuracy-rate" aria-describedby="accuracy-label">0%</div>
                        <div class="metric-label" id="accuracy-label">Model Accuracy</div>
                    </div>
                    <div class="metric-card" role="status" aria-labelledby="response-time-label">
                        <div class="metric-value" id="response-time" aria-describedby="response-time-label">0ms</div>
                        <div class="metric-label" id="response-time-label">Avg Response Time</div>
                    </div>
                    <div class="metric-card" role="status" aria-labelledby="compliance-label">
                        <div class="metric-value" id="iso-compliance" aria-describedby="compliance-label">0%</div>
                        <div class="metric-label" id="compliance-label">ISO 21001 Compliance</div>
                    </div>
                    <div class="metric-card" role="status" aria-labelledby="risk-score-label">
                        <div class="metric-value" id="risk-score" aria-describedby="risk-score-label">0/100</div>
                        <div class="metric-label" id="risk-score-label">Overall Risk Score</div>
                    </div>
                </section>

                <!-- AI Analysis Tools -->
                <section class="insights-grid" aria-label="AI Analysis Tools">
                    <h2 class="sr-only">Available AI Analysis Tools</h2>
                    <!-- Compliance Prediction -->
                    <article class="insight-card">
                        <h3>Compliance Prediction</h3>
                        <p>AI-powered prediction of ISO 21001 compliance levels based on learner feedback and performance metrics.</p>
                        <button type="button" class="btn btn-primary" onclick="runCompliancePrediction()" aria-describedby="compliance-desc">Run Compliance Prediction</button>
                        <div id="compliance-desc" class="sr-only">Runs AI analysis to predict ISO 21001 compliance levels based on current survey data</div>
                    </article>

                    <!-- Sentiment Analysis -->
                    <article class="insight-card">
                        <h3>Sentiment Analysis</h3>
                        <p>Analyze student feedback sentiment using advanced NLP models to identify positive and negative trends.</p>
                        <button type="button" class="btn btn-primary" onclick="runSentimentAnalysis()" aria-describedby="sentiment-desc">Analyze Feedback Sentiment</button>
                        <div id="sentiment-desc" class="sr-only">Analyzes sentiment in student feedback comments using natural language processing</div>
                    </article>

                    <!-- Student Clustering -->
                    <article class="insight-card">
                        <h3>Student Clustering</h3>
                        <p>Group students based on survey responses for targeted interventions and personalized support. ISO 21001:7.1 compliant segmentation.</p>
                        <button type="button" class="btn btn-primary" onclick="runStudentClustering()" aria-describedby="clustering-desc">Run Student Clustering</button>
                        <div id="clustering-desc" class="sr-only">Groups students into clusters based on survey responses for targeted support</div>
                    </article>

                    <!-- Predictive Analytics -->
                    <article class="insight-card">
                        <h3>Predictive Analytics</h3>
                        <p>Advanced forecasting of student performance, satisfaction trends, and risk factors using time series analysis.</p>
                        <button type="button" class="btn btn-primary" onclick="runPredictiveAnalytics()" aria-describedby="predictive-desc">Forecast Future Performance</button>
                        <div id="predictive-desc" class="sr-only">Forecasts future student performance and satisfaction trends</div>
                    </article>

                    <!-- Comprehensive Risk Assessment -->
                    <article class="insight-card">
                        <h3>Comprehensive Risk Assessment</h3>
                        <p>Complete ISO 21001 compliance risk evaluation across all learner-centric dimensions with intervention recommendations.</p>
                        <button type="button" class="btn btn-primary" onclick="runComprehensiveRiskAssessment()" aria-describedby="risk-desc">Run Comprehensive Risk Assessment</button>
                        <div id="risk-desc" class="sr-only">Evaluates compliance risks across all ISO 21001 dimensions</div>
                    </article>

                    <!-- Trend Analysis -->
                    <article class="insight-card">
                        <h3>Satisfaction Trend Analysis</h3>
                        <p>Analyze satisfaction trends over time with forecasting capabilities for proactive quality management.</p>
                        <button type="button" class="btn btn-primary" onclick="runTrendAnalysis()" aria-describedby="trend-desc">Analyze Satisfaction Trends</button>
                        <div id="trend-desc" class="sr-only">Analyzes satisfaction trends over time with forecasting</div>
                    </article>

                    <!-- Performance Prediction -->
                    <article class="insight-card">
                        <h3>Performance Prediction</h3>
                        <p>Predict student academic performance and identify at-risk students early.</p>
                        <button type="button" class="btn btn-primary" onclick="runPerformancePrediction()" aria-describedby="performance-desc">Predict Student Performance</button>
                        <div id="performance-desc" class="sr-only">Predicts academic performance and identifies at-risk students</div>
                    </article>

                    <!-- Dropout Risk Assessment -->
                    <article class="insight-card">
                        <h3>Dropout Risk Assessment</h3>
                        <p>Identify students at risk of dropping out using machine learning algorithms.</p>
                        <button type="button" class="btn btn-primary" onclick="runDropoutRiskAssessment()" aria-describedby="dropout-desc">Assess Dropout Risk</button>
                        <div id="dropout-desc" class="sr-only">Identifies students at risk of dropping out</div>
                    </article>

                    <!-- Comprehensive Analytics -->
                    <article class="insight-card">
                        <h3>Comprehensive Analytics</h3>
                        <p>Run all AI models simultaneously for complete insights into student satisfaction and compliance.</p>
                        <button type="button" class="btn btn-success" onclick="runComprehensiveAnalytics()" aria-describedby="comprehensive-desc">Run All AI Analytics</button>
                        <div id="comprehensive-desc" class="sr-only">Runs all AI analysis models simultaneously for comprehensive insights</div>
                    </article>
                </section>
            </div>

            <!-- Sticky Results Sidebar -->
            <aside class="insights-sidebar" role="complementary" aria-label="AI Analysis Results">
                <div id="ai-results" class="ai-results" aria-live="polite" aria-atomic="true">
                    <h3>
                        <span>📊 AI Analysis Results</span>
                        <button class="results-close-btn" onclick="clearResults()" title="Clear Results" aria-label="Clear all analysis results" aria-describedby="clear-results-desc">×</button>
                        <div id="clear-results-desc" class="sr-only">Clears all current analysis results from the display</div>
                    </h3>
                    <div id="results-container">
                        <!-- Empty State -->
                        <div class="ai-results-placeholder">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
                                <title>Chart icon indicating analysis results area</title>
                                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
                            </svg>
                            <p>Run an analysis to see results</p>
                            <p style="font-size: 13px; color: #bbb; margin-top: 10px;">Click any analysis button to get started</p>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </main>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loading-overlay" role="dialog" aria-modal="true" aria-labelledby="loading-heading" aria-describedby="loading-desc" aria-busy="false">
        <div style="text-align: center; color: white;">
            <div class="loading-spinner" aria-hidden="true"></div>
            <p id="loading-heading" style="margin-top: 20px; font-size: 18px; font-weight: 600;">Processing AI Analysis...</p>
            <div id="loading-desc" class="sr-only">Please wait while the AI analysis is being processed. This dialog cannot be dismissed until the analysis is complete.</div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer" role="contentinfo">
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

        let lastFocusedElement = null;

        /* ----------------------
           Keyboard Navigation & Accessibility
           ---------------------- */
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Alt + H: Go to header
            if (e.altKey && e.key === 'h') {
                e.preventDefault();
                document.querySelector('header').focus();
            }
            // Alt + M: Go to main content
            if (e.altKey && e.key === 'm') {
                e.preventDefault();
                document.getElementById('main-content').focus();
            }
            // Alt + R: Go to results
            if (e.altKey && e.key === 'r') {
                e.preventDefault();
                document.getElementById('ai-results').focus();
            }
        });

        /* ----------------------
           Helpers & Init
           ---------------------- */
        const safeNum = (v, decimals = 0) => {
            const n = Number(v);
            if (Number.isFinite(n)) return decimals === 0 ? Math.round(n) : n.toFixed(decimals);
            return typeof v === 'string' ? v : 'N/A';
        };

        const safePct = (v, decimals = 0) => {
            const n = Number(v);
            if (Number.isFinite(n)) return (decimals === 0 ? Math.round(n * 100) : (n * 100).toFixed(decimals)) + '%';
            return v ?? 'N/A';
        };

        document.addEventListener('DOMContentLoaded', () => {
            console.log('AI Insights Dashboard loaded successfully');
            console.log('CSRF Token:', csrfToken);

            checkAIServiceStatus();
            loadAIMetrics();
            loadDataRangeInfo();

            // Add smooth animations
            const cards = document.querySelectorAll('.metric-card, .insight-card');
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

        /* ----------------------
           Service / Metrics
           ---------------------- */
        async function checkAIServiceStatus() {
            try {
                const res = await fetch('/api/ai/service-status', { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken } });
                const json = await res.json();
                const el = document.getElementById('service-status');
                if (json?.success && json?.data?.available) {
                    el.textContent = 'Online';
                    el.style.background = 'linear-gradient(135deg, #28a745, #20c997)';
                    el.style.webkitBackgroundClip = 'text';
                    el.style.webkitTextFillColor = 'transparent';
                    el.style.backgroundClip = 'text';
                } else {
                    el.textContent = 'Offline';
                    el.style.background = 'linear-gradient(135deg, #dc3545, #e74c3c)';
                    el.style.webkitBackgroundClip = 'text';
                    el.style.webkitTextFillColor = 'transparent';
                    el.style.backgroundClip = 'text';
                }
            } catch (err) {
                console.error('AI Service Status Check Error:', err);
                const el = document.getElementById('service-status');
                el.textContent = 'Error';
                el.style.background = 'linear-gradient(135deg, #ffc107, #ff9800)';
                el.style.webkitBackgroundClip = 'text';
                el.style.webkitTextFillColor = 'transparent';
                el.style.backgroundClip = 'text';
            }
        }

        async function loadAIMetrics() {
            try {
                console.log('Loading AI metrics...');
                const res = await fetch('/api/ai/metrics', { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken } });
                const json = await res.json();
                console.log('AI Metrics response:', json);

                if (json?.success && json?.data) {
                    const d = json.data;
                    console.log('Updating metrics with data:', d);
                    document.getElementById('total-predictions').textContent = d.total_predictions ?? 0;
                    document.getElementById('accuracy-rate').textContent = (d.accuracy_rate ?? 0) + '%';
                    document.getElementById('response-time').textContent = (d.avg_response_time ?? 0) + 'ms';
                    document.getElementById('iso-compliance').textContent = (d.iso_compliance_score ?? 0) + '%';
                    document.getElementById('risk-score').textContent = (d.overall_risk_score ?? 0) + '/100';

                    // Update total responses count in the data stats display
                    if (d.total_responses_analyzed !== undefined) {
                        document.getElementById('total-responses-count').textContent = d.total_responses_analyzed;
                    }
                    console.log('Metrics updated successfully');
                } else {
                    console.error('Invalid metrics response format:', json);
                }
            } catch (err) {
                console.error('Error loading AI metrics:', err);
            }
        }

        async function loadDataRangeInfo() {
            try {
                console.log('Loading data range info...');
                const res = await fetch('/api/survey/analytics', {
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
                });
                const json = await res.json();
                console.log('Survey analytics response:', json);

                if (json?.success && json?.data) {
                    const analytics = json.data;
                    const dataRangeText = document.getElementById('data-range-text');
                    const dataStatsText = document.getElementById('data-stats-text');

                    // Update total responses
                    const totalResponses = analytics.total_responses || 0;
                    document.getElementById('total-responses-count').textContent = totalResponses;

                    // Get date range from responses
                    if (analytics.date_range) {
                        const startDate = new Date(analytics.date_range.oldest);
                        const endDate = new Date(analytics.date_range.newest);

                        const formatDate = (date) => {
                            const options = { month: 'short', day: 'numeric', year: 'numeric' };
                            return date.toLocaleDateString('en-US', options);
                        };

                        dataRangeText.innerHTML = `<strong>Data Range:</strong> ${formatDate(startDate)} - ${formatDate(endDate)}`;
                    } else {
                        dataRangeText.innerHTML = '<strong>Analyzing All Available Data</strong>';
                    }

                    // Update data stats with more details
                    if (analytics.distribution) {
                        const tracks = Object.keys(analytics.distribution.track || {}).length;
                        const grades = Object.keys(analytics.distribution.grade_level || {}).length;

                        dataStatsText.innerHTML = `Analyzing <strong>${totalResponses}</strong> survey responses across <strong>${tracks}</strong> tracks and <strong>${grades}</strong> grade levels`;
                    } else {
                        dataStatsText.innerHTML = `Analyzing <strong>${totalResponses}</strong> survey responses`;
                    }

                } else {
                    document.getElementById('data-range-text').innerHTML = '<strong>No data available</strong>';
                    document.getElementById('data-stats-text').innerHTML = 'No survey responses found';
                }
            } catch (err) {
                console.error('Error loading data range info:', err);
                document.getElementById('data-range-text').innerHTML = '<strong>Error loading data range</strong>';
            }
        }

        /* ----------------------
           Action wrappers
           ---------------------- */
        function runCompliancePrediction(){ return runAIAnalysis('compliance', 'Predicting compliance levels...'); }
        function runSentimentAnalysis(){ return runAIAnalysis('sentiment', 'Analyzing sentiment...'); }
        function runStudentClustering(){ return runAIAnalysis('clustering', 'Clustering students...'); }
        function runPredictiveAnalytics(){ return runAIAnalysis('predictive', 'Running predictive analytics...'); }
        function runComprehensiveRiskAssessment(){ return runAIAnalysis('risk_assessment', 'Assessing comprehensive risks...'); }
        function runTrendAnalysis(){ return runAIAnalysis('trend_analysis', 'Analyzing satisfaction trends...'); }
        function runPerformancePrediction(){ return runAIAnalysis('performance', 'Predicting performance...'); }
        function runDropoutRiskAssessment(){ return runAIAnalysis('dropout', 'Assessing dropout risk...'); }
        function runComprehensiveAnalytics(){ return runAIAnalysis('comprehensive', 'Running comprehensive analytics...'); }

        async function runAIAnalysis(type, loadingMessage){
            showLoading(loadingMessage);
            try {
                const res = await fetch(`/api/ai/analyze/${type}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({})
                });
                const json = await res.json();
                if (json?.success) {
                    displayResults(type, json.data ?? {});
                    showAlert('success', `${type.charAt(0).toUpperCase() + type.slice(1)} analysis completed successfully!`);
                } else {
                    showAlert('error', json?.message || 'Analysis failed');
                }
            } catch (err) {
                console.error('runAIAnalysis error:', err);
                showAlert('error', 'An error occurred during analysis');
            } finally { hideLoading(); }
        }

        /* ----------------------
           Result rendering
           ---------------------- */
        function renderItem(title, confidence, htmlContent, opts = {}){
            // Build a confidence badge with an icon whose color reflects the numeric level when possible
            let confHtml = '';
            if (confidence !== undefined && confidence !== null && String(confidence).trim() !== '') {
                // Try to extract a numeric value from the confidence string (e.g., "85%" -> 85)
                let confValue = null;
                if (typeof confidence === 'number') confValue = confidence;
                else if (typeof confidence === 'string') {
                    const m = confidence.match(/([0-9]+(?:\.[0-9]+)?)/);
                    if (m) confValue = parseFloat(m[1]);
                }

                let colorClass = 'conf-low';
                if (confValue !== null) {
                    if (confValue >= 70) colorClass = 'conf-high';
                    else if (confValue >= 40) colorClass = 'conf-mid';
                } else {
                    // default neutral color when no numeric value
                    colorClass = 'conf-mid';
                }

                confHtml = `<span class="confidence-badge-icon ${colorClass}" aria-hidden="true"></span><span>${confidence}</span>`;
            }

            return `
                <div class="result-item" style="${opts.style || ''}">
                    <div class="result-header">
                        <div class="result-title">${title}</div>
                        <div class="result-confidence">${confHtml}</div>
                    </div>
                    <div class="result-details">${htmlContent}</div>
                </div>
            `;
        }

        function clearResults() {
            const container = document.getElementById('results-container');
            container.innerHTML = `
                <div class="ai-results-placeholder">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
                    </svg>
                    <p>Run an analysis to see results</p>
                    <p style="font-size: 13px; color: #bbb; margin-top: 10px;">Click any analysis button to get started</p>
                </div>
            `;
        }

        function displayResults(type, data){
            const container = document.getElementById('results-container');
            const resultsDiv = document.getElementById('ai-results');
            const parts = [];

            // Announce results to screen readers
            const announcement = document.createElement('div');
            announcement.setAttribute('aria-live', 'assertive');
            announcement.setAttribute('aria-atomic', 'true');
            announcement.className = 'sr-only';
            announcement.textContent = `AI analysis complete. ${type.charAt(0).toUpperCase() + type.slice(1)} results are now displayed.`;
            document.body.appendChild(announcement);

            setTimeout(() => {
                document.body.removeChild(announcement);
                // Focus management: move focus to results after announcement
                resultsDiv.focus();
            }, 1000);

            // Debug logging
            console.log('displayResults called with type:', type);
            console.log('data:', JSON.stringify(data, null, 2));

            switch(type){
                case 'compliance': {
                    const p = data.prediction || data || {};
                    console.log('compliance p:', JSON.stringify(p, null, 2));
                    const weighted = Number(p.weighted_score) || 0;
                    const prob = Number(p.prediction_probability ?? (weighted ? (weighted/5) : 0));
                    const confidence = Number(p.confidence) || 0;
                    const predictionLabel = typeof p.prediction === 'string' ? p.prediction : (p.prediction?.prediction || 'Unknown');
                    const html = `
                        <p><strong>Compliance Level:</strong> ${predictionLabel}</p>
                        <p><strong>Risk Level:</strong> ${p.risk_level ?? 'Unknown'}</p>
                        <p><strong>Weighted Score:</strong> ${typeof weighted === 'number' ? (weighted.toFixed ? weighted.toFixed(2) : weighted) : 'N/A'}/5.0</p>
                        <p><strong>Confidence:</strong> ${safePct(confidence)}</p>
                        <p style="margin-top: 10px; font-size: 12px; color: #666;">Based on learner needs, satisfaction, and safety metrics.</p>
                    `;
                    parts.push(renderItem('Compliance Prediction', safePct(prob), html));
                    break;
                }

                case 'sentiment': {
                    // Fix: Handle the correct data structure from Python API
                    console.log('Sentiment data:', JSON.stringify(data, null, 2));

                    // The Python API returns: { overall_sentiment, sentiment_score, breakdown, individual_results, ... }
                    const breakdown = data.breakdown || { positive: 0, neutral: 0, negative: 0 };
                    const individualResults = data.individual_results || [];
                    const totalComments = data.total_comments_analyzed || individualResults.length || 0;
                    const sentimentScore = data.sentiment_score || 0;
                    const overallSentiment = data.overall_sentiment || 'Neutral';
                    const avgConfidence = data.average_confidence || 0;

                    // Summary card with overall metrics
                    const summaryHtml = `
                        <div style="background: linear-gradient(135deg, rgba(66, 133, 244, 0.05), rgba(255, 140, 0, 0.05)); padding: 20px; border-radius: 12px; margin-bottom: 15px;">
                            <h4 style="margin: 0 0 15px 0; color: #2c3e50;">Overall Sentiment Analysis</h4>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin-bottom: 15px;">
                                <div>
                                    <p style="margin: 0; font-size: 12px; color: #666;">Overall Sentiment</p>
                                    <p style="margin: 5px 0 0 0; font-size: 20px; font-weight: 700; color: #2c3e50;">${overallSentiment}</p>
                                </div>
                                <div>
                                    <p style="margin: 0; font-size: 12px; color: #666;">Sentiment Score</p>
                                    <p style="margin: 5px 0 0 0; font-size: 20px; font-weight: 700; color: #4285F4;">${sentimentScore}%</p>
                                </div>
                                <div>
                                    <p style="margin: 0; font-size: 12px; color: #666;">Avg Confidence</p>
                                    <p style="margin: 5px 0 0 0; font-size: 20px; font-weight: 700; color: #FF8C00;">${(avgConfidence * 100).toFixed(1)}%</p>
                                </div>
                                <div>
                                    <p style="margin: 0; font-size: 12px; color: #666;">Total Comments</p>
                                    <p style="margin: 5px 0 0 0; font-size: 20px; font-weight: 700; color: #2c3e50;">${totalComments}</p>
                                </div>
                            </div>
                            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;">
                                <div style="background: rgba(40, 167, 69, 0.1); padding: 10px; border-radius: 8px; border-left: 3px solid #28a745;">
                                    <p style="margin: 0; font-size: 12px; color: #155724; font-weight: 600;">Positive</p>
                                    <p style="margin: 5px 0 0 0; font-size: 18px; font-weight: 700; color: #28a745;">${breakdown.positive} <span style="font-size: 12px; color: #666;">(${totalComments > 0 ? ((breakdown.positive/totalComments)*100).toFixed(1) : 0}%)</span></p>
                                </div>
                                <div style="background: rgba(255, 193, 7, 0.1); padding: 10px; border-radius: 8px; border-left: 3px solid #ffc107;">
                                    <p style="margin: 0; font-size: 12px; color: #856404; font-weight: 600;">Neutral</p>
                                    <p style="margin: 5px 0 0 0; font-size: 18px; font-weight: 700; color: #ffc107;">${breakdown.neutral} <span style="font-size: 12px; color: #666;">(${totalComments > 0 ? ((breakdown.neutral/totalComments)*100).toFixed(1) : 0}%)</span></p>
                                </div>
                                <div style="background: rgba(220, 53, 69, 0.1); padding: 10px; border-radius: 8px; border-left: 3px solid #dc3545;">
                                    <p style="margin: 0; font-size: 12px; color: #721c24; font-weight: 600;">Negative</p>
                                    <p style="margin: 5px 0 0 0; font-size: 18px; font-weight: 700; color: #dc3545;">${breakdown.negative} <span style="font-size: 12px; color: #666;">(${totalComments > 0 ? ((breakdown.negative/totalComments)*100).toFixed(1) : 0}%)</span></p>
                                </div>
                            </div>
                            <div style="margin-top: 15px; padding: 12px; background: rgba(66, 133, 244, 0.1); border-radius: 8px; border-left: 3px solid #4285F4;">
                                <p style="margin: 0; font-size: 13px; color: #333; font-weight: 600;">
                                    <svg style="width: 16px; height: 16px; vertical-align: middle; margin-right: 6px; fill: #4285F4;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                                    </svg>
                                    Score Formula: (Positive × 100 + Neutral × 50 + Negative × 0) / Total Comments
                                </p>
                                <p style="margin: 8px 0 0 22px; font-size: 12px; color: #666; line-height: 1.5;">
                                    <strong>Interpretation:</strong> ${sentimentScore >= 70 ? '🟢 Highly Positive (70-100%)' : sentimentScore >= 50 ? '🟡 Moderate/Neutral (50-69%)' : '🔴 Needs Attention (0-49%)'}
                                </p>
                            </div>
                            ${data.iso_21001_insights ? `
                                <div style="margin-top: 15px; padding: 12px; background: rgba(255, 140, 0, 0.1); border-radius: 8px; border-left: 3px solid #FF8C00;">
                                    <p style="margin: 0 0 8px 0; font-size: 13px; color: #333; font-weight: 700;">
                                        <svg style="width: 16px; height: 16px; vertical-align: middle; margin-right: 6px; fill: #FF8C00;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                            <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/>
                                        </svg>
                                        ISO 21001 Insights
                                    </p>
                                    <p style="margin: 0 0 5px 22px; font-size: 12px; color: #333;">
                                        <strong>Learner Satisfaction:</strong> ${data.iso_21001_insights.learner_satisfaction_indicator || 'N/A'}
                                    </p>
                                    <p style="margin: 0 0 5px 22px; font-size: 12px; color: #333;">
                                        <strong>Action Required:</strong> ${data.iso_21001_insights.action_required ? '⚠️ Yes' : '✅ No'}
                                    </p>
                                    <p style="margin: 0 0 0 22px; font-size: 12px; color: #555; font-style: italic;">
                                        ${data.iso_21001_insights.recommendation || 'N/A'}
                                    </p>
                                </div>
                            ` : ''}
                        </div>
                    `;

                    parts.push(renderItem('Sentiment Analysis Summary', `${totalComments} Comments Analyzed`, summaryHtml, {
                        style: 'background:linear-gradient(135deg, rgba(66, 133, 244, 0.05), rgba(255, 140, 0, 0.05));border-left:4px solid #4285F4;'
                    }));

                    // Show sample individual comments (first 5)
                    if (individualResults.length > 0) {
                        individualResults.slice(0, 5).forEach((item, i) => {
                            const sentiment = item.sentiment || 'neutral';
                            const confidence = item.confidence || 0;
                            const color = sentiment === 'positive' ? '#28a745' : sentiment === 'negative' ? '#dc3545' : '#ffc107';

                            let commentHtml = `
                                <div style="margin-bottom: 10px;">
                                    <p style="margin: 0 0 8px 0;">
                                        <span style="background: ${color}; color: white; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 700; text-transform: uppercase;">
                                            ${sentiment}
                                        </span>
                                        <span style="margin-left: 10px; font-size: 12px; color: #666;">
                                            Confidence: ${(confidence * 100).toFixed(1)}%
                                        </span>
                                    </p>
                            `;

                            if (item.probabilities) {
                                commentHtml += `
                                    <div style="margin-top: 10px; padding: 10px; background: rgba(0,0,0,0.02); border-radius: 8px;">
                                        <p style="margin: 0 0 5px 0; font-size: 11px; color: #666; font-weight: 600;">Probability Breakdown:</p>
                                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; font-size: 11px;">
                                            <div style="text-align: center;">
                                                <p style="margin: 0; color: #28a745; font-weight: 600;">Positive</p>
                                                <p style="margin: 2px 0 0 0; color: #666;">${(item.probabilities.positive * 100).toFixed(1)}%</p>
                                            </div>
                                            <div style="text-align: center;">
                                                <p style="margin: 0; color: #ffc107; font-weight: 600;">Neutral</p>
                                                <p style="margin: 2px 0 0 0; color: #666;">${(item.probabilities.neutral * 100).toFixed(1)}%</p>
                                            </div>
                                            <div style="text-align: center;">
                                                <p style="margin: 0; color: #dc3545; font-weight: 600;">Negative</p>
                                                <p style="margin: 2px 0 0 0; color: #666;">${(item.probabilities.negative * 100).toFixed(1)}%</p>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            }

                            if (item.model_used) {
                                commentHtml += `<p style="margin: 10px 0 0 0; font-size: 11px; color: #999;"><em>Model: ${item.model_used}</em></p>`;
                            }

                            commentHtml += `</div>`;

                            parts.push(renderItem(`Sample Comment ${i + 1}`, `${sentiment.charAt(0).toUpperCase() + sentiment.slice(1)}`, commentHtml));
                        });

                        if (individualResults.length > 5) {
                            parts.push(renderItem('Additional Comments', '', `<p style="color: #666; font-style: italic;">+ ${individualResults.length - 5} more comments analyzed (showing first 5)</p>`));
                        }
                    } else if (totalComments === 0) {
                        parts.push(renderItem('Sentiment Analysis', '', '<p>No comments available for sentiment analysis</p>'));
                    }
                    break;
                }

                case 'clustering': {
                    const c = data.clustering_result || data || {};
                    const html = `
                        <p><strong>Algorithm:</strong> ${c.algorithm ?? 'K-Means'}</p>
                        <p><strong>Total Students:</strong> ${c.total_samples ?? 0}</p>
                        <p><strong>Silhouette Score:</strong> ${c.metrics?.silhouette_score ?? 'N/A'}</p>
                        <p>Students have been grouped into ${c.clusters ?? 0} clusters based on survey responses.</p>
                    `;
                    parts.push(renderItem('Student Clustering Results', `${c.clusters ?? 0} Clusters Identified`, html));
                    if (Array.isArray(c.detailed_clusters)){
                        c.detailed_clusters.slice(0,3).forEach(cluster => {
                            const details = `
                                <p><strong>Risk Level:</strong> ${cluster.risk_profile?.risk_level ?? 'Unknown'}</p>
                                <p><strong>Avg Satisfaction:</strong> ${cluster.average_satisfaction ?? 'N/A'}/5</p>
                                <p><strong>Avg Performance:</strong> ${cluster.average_performance ?? 'N/A'}/4.0</p>
                                <p><strong>Characteristics:</strong> ${Array.isArray(cluster.characteristics) ? cluster.characteristics.join(', ') : 'N/A'}</p>
                            `;
                            parts.push(renderItem(`Cluster ${cluster.cluster_id}`, `${cluster.size ?? 0} Students (${cluster.percentage ?? 'N/A'}%)`, details));
                        });
                    }
                    if (Array.isArray(c.insights) && c.insights.length){
                        parts.push(renderItem('ISO 21001 Insights', 'Key Findings', `<ul style="margin:0;padding-left:20px;">${c.insights.map(x => `<li>${x}</li>`).join('')}</ul>`, { style: 'background:linear-gradient(135deg, rgba(66, 133, 244, 0.1), rgba(255, 140, 0, 0.1));border-left:4px solid #4285f4;' }));
                    }
                    break;
                }

                case 'performance': {
                    const p = data.prediction || data || {};
                    const conf = Number(p.confidence) || 0;
                    const predGpa = p.predicted_gpa;
                    const html = `
                        <p><strong>Predicted Performance:</strong> ${p.prediction ?? 'Unknown'}</p>
                        <p><strong>Predicted GPA:</strong> ${typeof predGpa === 'number' ? predGpa.toFixed(1) : (predGpa ?? 'N/A')}</p>
                        <p><strong>Risk Level:</strong> ${p.risk_level ?? 'Unknown'}</p>
                        <p><strong>Model Used:</strong> ${p.model_used ?? 'Unknown'}</p>
                    `;
                    parts.push(renderItem('Performance Prediction', safePct(conf), html));
                    break;
                }

                case 'dropout': {
                    const p = data.prediction || data || {};
                    const html = `
                        <p><strong>Risk Level:</strong> ${p.dropout_risk ?? 'Unknown'}</p>
                        <p><strong>Risk Score:</strong> ${p.risk_probability ? (Number(p.risk_probability * 100).toFixed(1) + '%') : 'N/A'}</p>
                        <p><strong>Intervention Urgency:</strong> ${p.intervention_urgency ?? 'Unknown'}</p>
                        <p><strong>Confidence:</strong> ${p.confidence ? (Number(p.confidence * 100).toFixed(1) + '%') : 'N/A'}</p>
                        ${Array.isArray(p.risk_factors) && p.risk_factors.length ? `<p><strong>Risk Factors:</strong> ${p.risk_factors.join(', ')}</p>` : ''}
                    `;
                    parts.push(renderItem('Dropout Risk Assessment', `${p.dropout_risk ?? 'Unknown'} Risk`, html));
                    break;
                }

                case 'predictive': {
                    const p = data.prediction || data || {};
                    const html = `
                        <p><strong>Current Performance:</strong> ${p.current_performance ?? 'N/A'}</p>
                        <p><strong>Predicted Trend:</strong> ${p.trend ?? 'N/A'}</p>
                        <p><strong>Confidence Level:</strong> ${p.confidence ? safePct(p.confidence) : 'N/A'}</p>
                        <p><strong>Forecast Period:</strong> Next 3 months</p>
                    `;
                    parts.push(renderItem('Predictive Analytics Results', 'Future Performance Forecast', html));
                    break;
                }

                case 'risk_assessment': {
                    const r = data.assessment || data || {};
                    const html = `
                        <p><strong>Risk Level:</strong> ${r.risk_level ?? 'Unknown'}</p>
                        <p><strong>Risk Category:</strong> ${r.risk_category ?? 'Unknown'}</p>
                        <p><strong>Compliance Impact:</strong> ${r.compliance_impact ?? 'Unknown'}</p>
                        <p><strong>Confidence:</strong> ${r.confidence ? safePct(r.confidence) : 'N/A'}</p>
                    `;
                    parts.push(renderItem('Comprehensive Risk Assessment', `Risk Score: ${r.overall_risk_score ?? 0}/100`, html));
                    if (r.risk_breakdown){
                        Object.entries(r.risk_breakdown).forEach(([k,v]) => {
                            const names = { learning_environment:'Learning Environment', academic_performance:'Academic Performance', safety:'Safety & Security', wellbeing:'Student Wellbeing', engagement:'Student Engagement' };
                            parts.push(renderItem(names[k] || k, `Risk: ${v}/100`, `<p>Risk score for ${names[k] || k} dimension.</p>`));
                        });
                    }
                    break;
                }

                case 'trend_analysis': {
                    const t = data.trend_prediction || data || {};
                    const html = `
                        <p><strong>Current Satisfaction:</strong> ${t.current_satisfaction ?? 'N/A'}/5.0</p>
                        <p><strong>Trend Direction:</strong> ${t.trend_direction ?? 'Unknown'}</p>
                        <p><strong>Trend Strength:</strong> ${t.trend_strength ?? 'Unknown'}</p>
                        <p><strong>Confidence:</strong> ${t.confidence ? safePct(t.confidence) : 'N/A'}</p>
                    `;
                    parts.push(renderItem('Satisfaction Trend Analysis', t.trend_direction ?? 'Stable', html));
                    if (Array.isArray(t.forecasted_satisfaction) && t.forecasted_satisfaction.length){
                        parts.push(renderItem('3-Month Forecast', 'Predicted Values', `<p><strong>Month 1:</strong> ${t.forecasted_satisfaction[0] ?? 'N/A'}/5.0</p><p><strong>Month 2:</strong> ${t.forecasted_satisfaction[1] ?? 'N/A'}/5.0</p><p><strong>Month 3:</strong> ${t.forecasted_satisfaction[2] ?? 'N/A'}/5.0</p>`));
                    }
                    break;
                }

                case 'comprehensive': {
                    parts.push(renderItem('Comprehensive Analytics', 'Complete', '<p>All AI models have been executed successfully.</p><p>Results include compliance, sentiment, clustering, performance, and risk assessments.</p>'));
                    const a = data.analytics_results || {};
                    if (a.compliance_prediction) parts.push(renderItem('Compliance Prediction', a.compliance_prediction.prediction_probability ? safePct(a.compliance_prediction.prediction_probability) : 'N/A', `<p><strong>Compliance Level:</strong> ${a.compliance_prediction.prediction ?? 'Unknown'}</p><p><strong>Risk Level:</strong> ${a.compliance_prediction.risk_level ?? 'Unknown'}</p>`));
                    if (Array.isArray(a.sentiment_analysis) && a.sentiment_analysis.length) parts.push(renderItem('Sentiment Analysis', `${a.sentiment_analysis.length} Comments`, `<p>Summary available</p>`));
                    if (a.student_clustering) parts.push(renderItem('Student Clustering', `${a.student_clustering.clusters ?? a.student_clustering.cluster_count ?? 0} Clusters`, `<p>Clustering summary available</p>`));
                    if (a.performance_prediction) parts.push(renderItem('Performance Prediction', a.performance_prediction.confidence ? safePct(a.performance_prediction.confidence) : 'N/A', `<p>Performance summary available</p>`));
                    if (a.dropout_risk_prediction) parts.push(renderItem('Dropout Risk Assessment', a.dropout_risk_prediction.dropout_risk ?? 'N/A', `<p>Dropout summary available</p>`));
                    break;
                }

                default:
                    parts.push(renderItem('Analysis Results', '', '<p>No results available for this analysis type.</p>'));
            }

            container.innerHTML = parts.join('');

            // Scroll to top of results
            if (resultsDiv) {
                resultsDiv.scrollTop = 0;
            }

            // Update results heading with analysis type
            const resultsHeading = resultsDiv.querySelector('h3 span');
            if (resultsHeading) {
                resultsHeading.textContent = `${type.charAt(0).toUpperCase() + type.slice(1)} Results`;
            }
        }

        /* ----------------------
           UI helpers
           ---------------------- */
        function showAlert(type, message){
            const container = document.getElementById('alert-container');
            const alertClass = type === 'success' ? 'alert-success' : type === 'warning' ? 'alert-warning' : 'alert-error';
            const role = type === 'error' ? 'alert' : 'status';
            container.innerHTML = `<div class="alert ${alertClass}" role="${role}" aria-live="assertive">${message}</div>`;
            setTimeout(()=> container.innerHTML = '', 5000);
        }

        function showLoading(message = 'Processing...'){
            const overlay = document.getElementById('loading-overlay');
            lastFocusedElement = document.activeElement;

            overlay.querySelector('p').textContent = message;
            overlay.classList.add('active');
            overlay.setAttribute('aria-busy', 'true');

            // Focus trap for loading overlay
            overlay.focus();

            // Announce loading state to screen readers
            const announcement = document.createElement('div');
            announcement.setAttribute('aria-live', 'assertive');
            announcement.setAttribute('aria-atomic', 'true');
            announcement.className = 'sr-only';
            announcement.textContent = `${message} Please wait.`;
            document.body.appendChild(announcement);

            setTimeout(() => document.body.removeChild(announcement), 1000);

            document.addEventListener('keydown', handleKeyDown);
        }

        function hideLoading(){
            const overlay = document.getElementById('loading-overlay');
            overlay.classList.remove('active');
            overlay.setAttribute('aria-busy', 'false');
            if (lastFocusedElement) {
                lastFocusedElement.focus();
            }
            document.removeEventListener('keydown', handleKeyDown);
        }

        function handleKeyDown(e) {
            const overlay = document.getElementById('loading-overlay');
            if (!overlay.classList.contains('active')) return;

            if (e.key === 'Escape') {
                // Don't allow escape during loading - analysis must complete
                e.preventDefault();
                return;
            }

            if (e.key === 'Tab') {
                // Trap focus within the dialog - prevent tabbing out
                e.preventDefault();
                overlay.focus();
            }
        }

        console.log('Enhanced AI Insights page loaded');
    </script>
</body>
</html>
