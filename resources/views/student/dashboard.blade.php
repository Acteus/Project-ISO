<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Profile Settings - ISO Quality Education</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}?v={{ time() }}">
    <style>
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Mobile-specific dashboard styles */
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 15px;
            }

            .dashboard-header {
                padding: 20px;
                margin-bottom: 20px;
            }

            .dashboard-header h1 {
                font-size: 24px;
            }

            .dashboard-header > div {
                flex-direction: column;
                align-items: flex-start !important;
            }

            .dashboard-header > div > div:last-child {
                width: 100%;
                margin-top: 15px;
            }

            .dashboard-header > div > div:last-child > a {
                flex: 1;
                min-width: 0;
            }

            .student-info-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .student-info-card {
                padding: 20px;
            }

            .dashboard-actions {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .action-card {
                padding: 20px;
            }

            .survey-history {
                padding: 20px;
            }
        }

        .dashboard-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            color: #333;
            padding: 40px 30px;
            border-radius: 20px;
            margin-bottom: 30px;
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
            min-height: 44px; /* Mobile touch target */
            font-size: 16px; /* Prevent zoom on iOS */
        }

        /* Mobile button improvements */
        @media (max-width: 768px) {
            .btn {
                padding: 15px 20px;
                font-size: 16px;
                width: 100%;
                margin-bottom: 10px;
            }
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
            margin-top: 30px;
            padding: 30px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(15px);
            text-align: center;
            color: #5a6c7d;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
        }

        /* Header Action Buttons */
        .header-action-btn {
            transition: all 0.3s ease;
        }

        .header-action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        /* Responsive header actions */
        @media (max-width: 768px) {
            .desktop-nav .desktop-text {
                display: none;
            }

            .desktop-nav .header-action-btn {
                padding: 8px 12px;
                min-width: 44px;
            }

            .desktop-nav {
                flex-wrap: wrap;
            }
        }

        @media (max-width: 480px) {
            .desktop-nav {
                gap: 8px;
            }

            .desktop-nav > div {
                flex-wrap: wrap;
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
                    <a href="{{ route('survey.landing') }}">ISO Quality Education</a>
                </div>

                <!-- Simple student navigation with quick actions -->
                <nav class="desktop-nav" style="display: flex !important; align-items: center; gap: 12px; flex-wrap: wrap;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <span class="nav-link" style="font-weight: 600; cursor: default; color: #2c3e50;">{{ Auth::user()->name }}</span>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <a href="{{ route('survey.form') }}" class="header-action-btn" style="background: linear-gradient(135deg, #28a745, #20c997); color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 14px; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 6px; white-space: nowrap;" title="Take Survey">
                                <svg style="width: 16px; height: 16px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                                </svg>
                                <span class="desktop-text">Survey</span>
                            </a>
                            <a href="{{ route('survey.landing') }}" class="header-action-btn" style="background: linear-gradient(135deg, #4285F4, #2c6cd6); color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 14px; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 6px; white-space: nowrap;" title="Back to Home">
                                <svg style="width: 16px; height: 16px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                                </svg>
                                <span class="desktop-text">Home</span>
                            </a>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('student.logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="logout-btn" style="background: linear-gradient(90deg, #dc3545, #c82333); border: none; color: white; cursor: pointer; padding: 8px 20px; border-radius: 6px; font-weight: 600; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 6px;">
                            <svg style="width: 16px; height: 16px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                            </svg>
                            <span class="desktop-text">Logout</span>
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
                <div style="margin-bottom: 15px;">
                    <span class="mobile-nav-link" style="font-weight: 600; display: block; margin-bottom: 15px; font-size: 18px; color: #2c3e50;">{{ Auth::user()->name }}</span>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <a href="{{ route('survey.form') }}" style="background: linear-gradient(135deg, #28a745, #20c997); color: white; padding: 12px 20px; border-radius: 6px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px; justify-content: center;">
                            <svg style="width: 18px; height: 18px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                            </svg>
                            Take Survey
                        </a>
                        <a href="{{ route('survey.landing') }}" style="background: linear-gradient(135deg, #4285F4, #2c6cd6); color: white; padding: 12px 20px; border-radius: 6px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px; justify-content: center;">
                            <svg style="width: 18px; height: 18px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                            </svg>
                            Back to Home
                        </a>
                    </div>
                </div>
                <form method="POST" action="{{ route('student.logout') }}" style="margin-top: 10px;">
                    @csrf
                    <button type="submit" class="logout-btn" style="background: linear-gradient(90deg, #dc3545, #c82333); border: none; color: white; cursor: pointer; padding: 12px 20px; border-radius: 6px; font-weight: 600; transition: all 0.3s ease; width: 100%; display: flex; align-items: center; gap: 8px; justify-content: center;">
                        <svg style="width: 18px; height: 18px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
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
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
                    <div>
                        <h1>Profile Settings</h1>
                        <p>Manage your account information and preferences</p>
                    </div>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <a href="{{ route('survey.form') }}" class="btn btn-success" style="display: inline-flex; align-items: center; gap: 8px;">
                            <svg style="width: 18px; height: 18px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                            </svg>
                            Take Survey
                        </a>
                        <a href="{{ route('survey.landing') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px;">
                            <svg style="width: 18px; height: 18px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                            </svg>
                            Back to Home
                        </a>
                    </div>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    <svg style="width: 20px; height: 20px; fill: #28a745;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div style="background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    <svg style="width: 20px; height: 20px; fill: #dc3545;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

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
                               style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; min-height: 44px;"
                               required>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label for="email" style="display: block; font-weight: 600; color: #333; margin-bottom: 8px;">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ Auth::user()->email }}"
                               style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; min-height: 44px;"
                               required>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label for="section" style="display: block; font-weight: 600; color: #333; margin-bottom: 8px;">Section</label>
                        <input type="text" id="section" name="section" value="{{ Auth::user()->section }}"
                               style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; min-height: 44px;"
                               required>
                    </div>

                    <button type="submit" class="btn btn-primary" onclick="event.preventDefault(); alert('Profile update feature coming soon!');">
                        Save Changes
                    </button>
                </form>
            </div>

            <!-- Data Privacy & Consent Management (GDPR & ISO 27001) -->
            <div class="survey-history" style="margin-bottom: 30px; border-left: 4px solid #4338ca;">
                <h3 style="display: flex; align-items: center; gap: 10px; color: #312e81;">
                    <svg style="width: 24px; height: 24px; fill: #4338ca;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/>
                    </svg>
                    Data Privacy & Consent Management
                </h3>
                <p style="color: #666; margin-bottom: 20px; line-height: 1.6;">
                    Manage your data privacy settings and consent preferences in compliance with GDPR and ISO 27001 standards.
                </p>

                @php
                    $consentService = app(\App\Services\ConsentService::class);
                    $studentId = Auth::user()->student_id ?? null;
                    $hasValidConsent = $studentId ? $consentService->hasValidConsent($studentId, 'survey_response') : false;
                    $consentHistory = $studentId ? $consentService->getConsentHistory($studentId, 'survey_response') : collect([]);
                    $latestConsent = $consentHistory->first();
                @endphp

                <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <div>
                            <h4 style="margin: 0 0 5px 0; color: #333; font-size: 18px;">Current Consent Status</h4>
                            <p style="margin: 0; color: #666; font-size: 14px;">
                                @if($hasValidConsent)
                                    <span style="color: #28a745; font-weight: 600;">
                                        <svg style="width: 16px; height: 16px; vertical-align: middle; fill: #28a745;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                        </svg>
                                        Active Consent
                                    </span>
                                @else
                                    <span style="color: #dc3545; font-weight: 600;">
                                        <svg style="width: 16px; height: 16px; vertical-align: middle; fill: #dc3545;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                                        </svg>
                                        No Active Consent
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($latestConsent)
                        <div style="background: white; padding: 15px; border-radius: 6px; margin-top: 15px;">
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; font-size: 14px;">
                                <div>
                                    <strong style="color: #666;">Consent Given:</strong>
                                    <div style="color: #333; margin-top: 4px;">
                                        {{ $latestConsent->created_at->format('M j, Y g:i A') }}
                                    </div>
                                </div>
                                @if($latestConsent->expires_at)
                                    <div>
                                        <strong style="color: #666;">Expires:</strong>
                                        <div style="color: #333; margin-top: 4px;">
                                            {{ $latestConsent->expires_at->format('M j, Y') }}
                                        </div>
                                    </div>
                                @endif
                                @if($latestConsent->revoked_at)
                                    <div>
                                        <strong style="color: #666;">Revoked:</strong>
                                        <div style="color: #dc3545; margin-top: 4px;">
                                            {{ $latestConsent->revoked_at->format('M j, Y g:i A') }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <div style="background: #fff3cd; border: 1px solid #ffc107; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                    <h4 style="margin: 0 0 10px 0; color: #856404; font-size: 16px; display: flex; align-items: center; gap: 8px;">
                        <svg style="width: 20px; height: 20px; fill: #856404;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                        </svg>
                        Your Rights
                    </h4>
                    <ul style="margin: 0; padding-left: 20px; color: #856404; line-height: 1.8;">
                        <li>You can <strong>revoke your consent</strong> at any time</li>
                        <li>Revoking consent will prevent future data processing</li>
                        <li>Existing data will be handled according to retention policies</li>
                        <li>You can <strong>request data deletion</strong> by contacting the administrator</li>
                    </ul>
                </div>

                @if($studentId)
                    @if($hasValidConsent)
                        <form method="POST" action="{{ route('student.consent.revoke') }}" id="revokeConsentForm" style="max-width: 600px;">
                            @csrf
                            <div style="background: #f8d7da; border: 1px solid #dc3545; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                                <p style="margin: 0; color: #721c24; font-size: 14px; line-height: 1.6;">
                                    <strong>Warning:</strong> Revoking consent will prevent you from submitting new surveys.
                                    This action will be logged for audit purposes.
                                </p>
                            </div>
                            <button type="submit" class="btn" style="background: linear-gradient(135deg, #dc3545, #c82333); color: white;" onclick="return confirmRevokeConsent(event)">
                                <svg style="width: 16px; height: 16px; vertical-align: middle; margin-right: 5px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                                </svg>
                                Revoke Consent
                            </button>
                        </form>
                    @else
                        <div style="background: #d1ecf1; border: 1px solid #bee5eb; padding: 15px; border-radius: 8px;">
                            <p style="margin: 0; color: #0c5460; font-size: 14px;">
                                You currently do not have active consent. You will need to provide consent when submitting a survey.
                            </p>
                        </div>
                    @endif
                @else
                    <div style="background: #fff3cd; border: 1px solid #ffc107; padding: 15px; border-radius: 8px;">
                        <p style="margin: 0; color: #856404; font-size: 14px;">
                            <strong>Note:</strong> Student ID not found. Consent management requires a valid student ID.
                            Please contact support if you need assistance.
                        </p>
                    </div>
                @endif
            </div>

            <!-- Change Password Section -->
            <div class="survey-history" style="margin-bottom: 30px;">
                <h3>Change Password</h3>
                <form action="#" method="POST" style="max-width: 600px;">
                    @csrf
                    <div style="margin-bottom: 20px;">
                        <label for="current_password" style="display: block; font-weight: 600; color: #333; margin-bottom: 8px;">Current Password</label>
                        <input type="password" id="current_password" name="current_password"
                               style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; min-height: 44px;"
                               required>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label for="new_password" style="display: block; font-weight: 600; color: #333; margin-bottom: 8px;">New Password</label>
                        <input type="password" id="new_password" name="new_password"
                               style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; min-height: 44px;"
                               required>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label for="confirm_password" style="display: block; font-weight: 600; color: #333; margin-bottom: 8px;">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password"
                               style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; min-height: 44px;"
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
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
            <h3 style="color: #2c3e50; font-weight: 700; margin-bottom: 15px; font-size: 24px;">ISO Learner-Centric Quality Education</h3>
            <p style="color: #5a6c7d; font-size: 16px; line-height: 1.6; margin-bottom: 20px;">
                Empowering CSS Strand Students through Learner-Centric Quality Education
            </p>
            <p style="color: #6c757d; font-weight: 500; font-size: 14px; margin: 0;">
                © <span id="currentYear"></span> JRU Senior High School. All rights reserved.
            </p>
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

        // Confirm consent revocation
        function confirmRevokeConsent(event) {
            event.preventDefault();

            if (confirm('Are you sure you want to revoke your consent?\n\nThis will:\n- Prevent you from submitting new surveys\n- Be logged for audit purposes\n- Not delete existing data immediately\n\nYou can contact the administrator to request data deletion.')) {
                // Show loading state
                const form = document.getElementById('revokeConsentForm');
                const button = form.querySelector('button[type="submit"]');
                const originalText = button.innerHTML;
                button.disabled = true;
                button.innerHTML = '<svg style="width: 16px; height: 16px; vertical-align: middle; margin-right: 5px; animation: spin 1s linear infinite;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6 0-1.01.25-1.97.7-2.8L5.24 7.74C4.46 8.97 4 10.43 4 12c0 4.42 3.58 8 8 8v3l4-4-4-4v3z"/></svg> Revoking...';

                // Submit form
                form.submit();
            }

            return false;
        }

        // Add spin animation for loading
        const style = document.createElement('style');
        style.textContent = `
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
