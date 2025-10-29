<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Response Details - ISO Quality Education</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
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

        /* Enhanced Modern Response Detail Styles */
        body {
            background: linear-gradient(135deg, rgba(66, 133, 244, 1), rgba(255, 215, 0, 1));
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .survey-main {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(10px);
        }

        .detail-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px;
        }

        .detail-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            color: #333;
            padding: 40px 30px;
            border-radius: 20px;
            margin-bottom: 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 20px 60px rgba(66, 133, 244, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
        }

        .detail-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #4285F4, #FF8C00, #FFD700);
        }

        .detail-header h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 800;
            color: #2c3e50;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .detail-header p {
            margin: 10px 0 0 0;
            font-size: 18px;
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
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 1);
            color: #333;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .section-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 35px 30px;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        .section-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.15);
        }

        .section-card h2 {
            margin-top: 0;
            color: #2c3e50;
            border-bottom: 3px solid transparent;
            border-image: linear-gradient(90deg, #4285F4, #FF8C00) 1;
            padding-bottom: 20px;
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 25px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 25px;
        }

        .info-item {
            background: linear-gradient(135deg, rgba(66, 133, 244, 0.05), rgba(255, 140, 0, 0.05));
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 12px;
            border-left: 5px solid #4285F4;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        .info-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            background: linear-gradient(135deg, rgba(66, 133, 244, 0.1), rgba(255, 140, 0, 0.1));
        }

        .info-item[style*="background: white"] {
            background: rgba(255, 255, 255, 0.9) !important;
            backdrop-filter: blur(15px);
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

        .rating-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 20px;
            margin-top: 25px;
        }

        .rating-item {
            background: linear-gradient(135deg, rgba(66, 133, 244, 0.03), rgba(255, 140, 0, 0.03));
            backdrop-filter: blur(15px);
            padding: 20px 25px;
            border-radius: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        .rating-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            background: linear-gradient(135deg, rgba(66, 133, 244, 0.08), rgba(255, 140, 0, 0.08));
        }

        .rating-label {
            font-weight: 600;
            color: #2c3e50;
            flex: 1;
            font-size: 16px;
        }

        .rating-value {
            display: flex;
            gap: 6px;
        }

        .star {
            color: #ddd;
            font-size: 22px;
            transition: all 0.3s ease;
            filter: drop-shadow(0 1px 2px rgba(0,0,0,0.1));
        }

        .star.filled {
            color: #ffc107;
            transform: scale(1.1);
        }

        .comment-section {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.05), rgba(32, 201, 151, 0.05));
            backdrop-filter: blur(15px);
            padding: 25px;
            border-radius: 16px;
            border-left: 5px solid #28a745;
            margin-top: 25px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        .comment-section:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .comment-section[style*="border-left-color: #ffc107"] {
            background: linear-gradient(135deg, rgba(255, 193, 7, 0.05), rgba(255, 152, 0, 0.05));
            border-left-color: #ffc107;
        }

        .comment-section[style*="border-left-color: #17a2b8"] {
            background: linear-gradient(135deg, rgba(23, 162, 184, 0.05), rgba(19, 132, 150, 0.05));
            border-left-color: #17a2b8;
        }

        .comment-label {
            font-weight: 700;
            color: #2c3e50;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 12px;
        }

        .comment-text {
            color: #5a6c7d;
            line-height: 1.7;
            white-space: pre-wrap;
            font-size: 16px;
            font-weight: 500;
        }

        .no-comment {
            color: #999;
            font-style: italic;
            font-size: 16px;
        }

        .metadata-section {
            background: linear-gradient(135deg, rgba(255, 193, 7, 0.1), rgba(255, 152, 0, 0.1));
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 30px;
            border-radius: 20px;
            margin-top: 30px;
        }

        .metadata-section h3 {
            margin-top: 0;
            color: #2c3e50;
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .badge-success {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }

        .badge-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
        }

        .badge-info {
            background: linear-gradient(135deg, #17a2b8, #138496);
            color: white;
            box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);
        }

        .badge-info:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(23, 162, 184, 0.4);
        }

        .badge-warning {
            background: linear-gradient(135deg, #ffc107, #ff9800);
            color: #333;
            font-weight: 800;
            box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
        }

        .badge-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 193, 7, 0.4);
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

        /* Responsive design */
        @media (max-width: 768px) {
            .detail-container {
                padding: 20px;
            }

            .detail-header {
                flex-direction: column;
                gap: 20px;
                text-align: center;
                padding: 30px 20px;
            }

            .detail-header h1 {
                font-size: 24px;
            }

            .section-card {
                padding: 25px 20px;
            }

            .info-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .rating-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .rating-item {
                padding: 15px 20px;
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
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

        /* Page load animations */
        .section-card {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .section-card.loaded {
            opacity: 1;
            transform: translateY(0);
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
        <div class="detail-container">
            <!-- Detail Header -->
            <div class="detail-header">
                <div>
                    <h1>Survey Response Details</h1>
                    <p>Response ID: #{{ $response->id }}</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="back-btn">← Back to Dashboard</a>
            </div>

            <!-- Student Information -->
            <div class="section-card">
                <h2>Student Information</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Student ID</div>
                        <div class="info-value">{{ $response->student_id ?? 'N/A' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Academic Track</div>
                        <div class="info-value">
                            <span class="badge badge-info">{{ $response->track }}</span>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Grade Level</div>
                        <div class="info-value">Grade {{ $response->grade_level }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Academic Year</div>
                        <div class="info-value">{{ $response->academic_year }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Semester</div>
                        <div class="info-value">{{ $response->semester }} Semester</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Submission Date</div>
                        <div class="info-value">{{ $response->created_at->format('M j, Y g:i A') }}</div>
                    </div>
                </div>
            </div>

            <!-- ISO 21001 Learner Needs Assessment -->
            <div class="section-card">
                <h2>ISO 21001 Learner Needs Assessment</h2>
                <div class="rating-grid">
                    <div class="rating-item">
                        <div class="rating-label">Curriculum Relevance</div>
                        <div class="rating-value">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="star {{ $i <= $response->curriculum_relevance_rating ? 'filled' : '' }}">★</span>
                            @endfor
                        </div>
                    </div>
                    <div class="rating-item">
                        <div class="rating-label">Learning Pace Appropriateness</div>
                        <div class="rating-value">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="star {{ $i <= $response->learning_pace_appropriateness ? 'filled' : '' }}">★</span>
                            @endfor
                        </div>
                    </div>
                    <div class="rating-item">
                        <div class="rating-label">Individual Support Availability</div>
                        <div class="rating-value">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="star {{ $i <= $response->individual_support_availability ? 'filled' : '' }}">★</span>
                            @endfor
                        </div>
                    </div>
                    <div class="rating-item">
                        <div class="rating-label">Learning Style Accommodation</div>
                        <div class="rating-value">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="star {{ $i <= $response->learning_style_accommodation ? 'filled' : '' }}">★</span>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            <!-- ISO 21001 Learner Satisfaction Metrics -->
            <div class="section-card">
                <h2>ISO 21001 Learner Satisfaction Metrics</h2>
                <div class="rating-grid">
                    <div class="rating-item">
                        <div class="rating-label">Teaching Quality</div>
                        <div class="rating-value">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="star {{ $i <= $response->teaching_quality_rating ? 'filled' : '' }}">★</span>
                            @endfor
                        </div>
                    </div>
                    <div class="rating-item">
                        <div class="rating-label">Learning Environment</div>
                        <div class="rating-value">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="star {{ $i <= $response->learning_environment_rating ? 'filled' : '' }}">★</span>
                            @endfor
                        </div>
                    </div>
                    <div class="rating-item">
                        <div class="rating-label">Peer Interaction Satisfaction</div>
                        <div class="rating-value">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="star {{ $i <= $response->peer_interaction_satisfaction ? 'filled' : '' }}">★</span>
                            @endfor
                        </div>
                    </div>
                    <div class="rating-item">
                        <div class="rating-label">Extracurricular Satisfaction</div>
                        <div class="rating-value">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="star {{ $i <= $response->extracurricular_satisfaction ? 'filled' : '' }}">★</span>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            <!-- ISO 21001 Learner Success Indicators -->
            <div class="section-card">
                <h2>ISO 21001 Learner Success Indicators</h2>
                <div class="rating-grid">
                    <div class="rating-item">
                        <div class="rating-label">Academic Progress</div>
                        <div class="rating-value">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="star {{ $i <= $response->academic_progress_rating ? 'filled' : '' }}">★</span>
                            @endfor
                        </div>
                    </div>
                    <div class="rating-item">
                        <div class="rating-label">Skill Development</div>
                        <div class="rating-value">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="star {{ $i <= $response->skill_development_rating ? 'filled' : '' }}">★</span>
                            @endfor
                        </div>
                    </div>
                    <div class="rating-item">
                        <div class="rating-label">Critical Thinking Improvement</div>
                        <div class="rating-value">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="star {{ $i <= $response->critical_thinking_improvement ? 'filled' : '' }}">★</span>
                            @endfor
                        </div>
                    </div>
                    <div class="rating-item">
                        <div class="rating-label">Problem Solving Confidence</div>
                        <div class="rating-value">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="star {{ $i <= $response->problem_solving_confidence ? 'filled' : '' }}">★</span>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            <!-- ISO 21001 Learner Safety Assessment -->
            <div class="section-card">
                <h2>ISO 21001 Learner Safety Assessment</h2>
                <div class="rating-grid">
                    <div class="rating-item">
                        <div class="rating-label">Physical Safety</div>
                        <div class="rating-value">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="star {{ $i <= $response->physical_safety_rating ? 'filled' : '' }}">★</span>
                            @endfor
                        </div>
                    </div>
                    <div class="rating-item">
                        <div class="rating-label">Psychological Safety</div>
                        <div class="rating-value">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="star {{ $i <= $response->psychological_safety_rating ? 'filled' : '' }}">★</span>
                            @endfor
                        </div>
                    </div>
                    <div class="rating-item">
                        <div class="rating-label">Bullying Prevention Effectiveness</div>
                        <div class="rating-value">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="star {{ $i <= $response->bullying_prevention_effectiveness ? 'filled' : '' }}">★</span>
                            @endfor
                        </div>
                    </div>
                    <div class="rating-item">
                        <div class="rating-label">Emergency Preparedness</div>
                        <div class="rating-value">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="star {{ $i <= $response->emergency_preparedness_rating ? 'filled' : '' }}">★</span>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            <!-- ISO 21001 Learner Wellbeing Metrics -->
            <div class="section-card">
                <h2>ISO 21001 Learner Wellbeing Metrics</h2>
                <div class="rating-grid">
                    <div class="rating-item">
                        <div class="rating-label">Mental Health Support</div>
                        <div class="rating-value">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="star {{ $i <= $response->mental_health_support_rating ? 'filled' : '' }}">★</span>
                            @endfor
                        </div>
                    </div>
                    <div class="rating-item">
                        <div class="rating-label">Stress Management Support</div>
                        <div class="rating-value">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="star {{ $i <= $response->stress_management_support ? 'filled' : '' }}">★</span>
                            @endfor
                        </div>
                    </div>
                    <div class="rating-item">
                        <div class="rating-label">Physical Health Support</div>
                        <div class="rating-value">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="star {{ $i <= $response->physical_health_support ? 'filled' : '' }}">★</span>
                            @endfor
                        </div>
                    </div>
                    <div class="rating-item">
                        <div class="rating-label">Overall Wellbeing</div>
                        <div class="rating-value">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="star {{ $i <= $response->overall_wellbeing_rating ? 'filled' : '' }}">★</span>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            <!-- Overall Satisfaction & Feedback -->
            <div class="section-card">
                <h2>Overall Satisfaction & Feedback</h2>
                <div class="rating-grid">
                    <div class="rating-item">
                        <div class="rating-label">Overall Satisfaction</div>
                        <div class="rating-value">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="star {{ $i <= $response->overall_satisfaction ? 'filled' : '' }}">★</span>
                            @endfor
                        </div>
                    </div>
                </div>

                @if($response->positive_aspects)
                <div class="comment-section" style="border-left-color: #28a745;">
                    <div class="comment-label">Positive Aspects</div>
                    <div class="comment-text">{{ $response->positive_aspects }}</div>
                </div>
                @endif

                @if($response->improvement_suggestions)
                <div class="comment-section" style="border-left-color: #ffc107;">
                    <div class="comment-label">Improvement Suggestions</div>
                    <div class="comment-text">{{ $response->improvement_suggestions }}</div>
                </div>
                @endif

                @if($response->additional_comments)
                <div class="comment-section" style="border-left-color: #17a2b8;">
                    <div class="comment-label">Additional Comments</div>
                    <div class="comment-text">{{ $response->additional_comments }}</div>
                </div>
                @endif

                @if(!$response->positive_aspects && !$response->improvement_suggestions && !$response->additional_comments)
                <div class="comment-section">
                    <div class="no-comment">No written feedback provided.</div>
                </div>
                @endif
            </div>

            <!-- Indirect Metrics -->
            @if($response->attendance_rate || $response->grade_average || $response->participation_score || $response->extracurricular_hours || $response->counseling_sessions)
            <div class="section-card">
                <h2>Indirect Performance Metrics</h2>
                <div class="info-grid">
                    @if($response->attendance_rate)
                    <div class="info-item">
                        <div class="info-label">Attendance Rate</div>
                        <div class="info-value">{{ $response->attendance_rate }}%</div>
                    </div>
                    @endif
                    @if($response->grade_average)
                    <div class="info-item">
                        <div class="info-label">Grade Average</div>
                        <div class="info-value">{{ $response->grade_average }}</div>
                    </div>
                    @endif
                    @if($response->participation_score)
                    <div class="info-item">
                        <div class="info-label">Participation Score</div>
                        <div class="info-value">{{ $response->participation_score }}/100</div>
                    </div>
                    @endif
                    @if($response->extracurricular_hours)
                    <div class="info-item">
                        <div class="info-label">Extracurricular Hours</div>
                        <div class="info-value">{{ $response->extracurricular_hours }} hours</div>
                    </div>
                    @endif
                    @if($response->counseling_sessions)
                    <div class="info-item">
                        <div class="info-label">Counseling Sessions</div>
                        <div class="info-value">{{ $response->counseling_sessions }} sessions</div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Metadata -->
            <div class="metadata-section">
                <h3>Privacy & Compliance Information</h3>
                <div class="info-grid">
                    <div class="info-item" style="background: white;">
                        <div class="info-label">Consent Status</div>
                        <div class="info-value">
                            @if($response->consent_given)
                                <span class="badge badge-success">Consent Given</span>
                            @else
                                <span class="badge badge-warning">No Consent</span>
                            @endif
                        </div>
                    </div>
                    <div class="info-item" style="background: white;">
                        <div class="info-label">IP Address</div>
                        <div class="info-value">{{ $response->ip_address ?? 'Not recorded' }}</div>
                    </div>
                    <div class="info-item" style="background: white;">
                        <div class="info-label">Last Updated</div>
                        <div class="info-value">{{ $response->updated_at->format('M j, Y g:i A') }}</div>
                    </div>
                </div>
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

        // Page load animations
        document.addEventListener('DOMContentLoaded', function() {
            const sections = document.querySelectorAll('.section-card');

            // Add loaded class with staggered animation
            sections.forEach((section, index) => {
                setTimeout(() => {
                    section.classList.add('loaded');
                }, index * 100);
            });

            // Animate stars on hover
            const stars = document.querySelectorAll('.star');
            stars.forEach(star => {
                star.addEventListener('mouseenter', function() {
                    if (!this.classList.contains('filled')) {
                        this.style.transform = 'scale(1.2)';
                        this.style.color = '#ffc107';
                    }
                });

                star.addEventListener('mouseleave', function() {
                    if (!this.classList.contains('filled')) {
                        this.style.transform = 'scale(1)';
                        this.style.color = '#ddd';
                    }
                });
            });
        });

        console.log('Enhanced Response Detail page loaded');
    </script>
</body>
</html>
