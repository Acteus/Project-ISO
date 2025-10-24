<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Response Details - ISO Quality Education</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        .detail-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .detail-header {
            background: linear-gradient(135deg, #4285F4, #2c6cd6);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .detail-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }

        .back-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 2px solid white;
        }

        .back-btn:hover {
            background: white;
            color: #4285F4;
        }

        .section-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .section-card h2 {
            margin-top: 0;
            color: #333;
            border-bottom: 3px solid #4285F4;
            padding-bottom: 15px;
            font-size: 24px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .info-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #4285F4;
        }

        .info-label {
            font-weight: 600;
            color: #666;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 18px;
            color: #333;
            font-weight: 500;
        }

        .rating-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .rating-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .rating-label {
            font-weight: 500;
            color: #333;
            flex: 1;
        }

        .rating-value {
            display: flex;
            gap: 5px;
        }

        .star {
            color: #ddd;
            font-size: 20px;
        }

        .star.filled {
            color: #ffc107;
        }

        .comment-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #28a745;
            margin-top: 20px;
        }

        .comment-label {
            font-weight: 600;
            color: #666;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }

        .comment-text {
            color: #333;
            line-height: 1.6;
            white-space: pre-wrap;
            font-size: 16px;
        }

        .no-comment {
            color: #999;
            font-style: italic;
        }

        .metadata-section {
            background: #fff3cd;
            border: 1px solid #ffc107;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .metadata-section h3 {
            margin-top: 0;
            color: #856404;
        }

        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }

        .badge-success {
            background: #28a745;
            color: white;
        }

        .badge-info {
            background: #17a2b8;
            color: white;
        }

        .badge-warning {
            background: #ffc107;
            color: #333;
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
        <div class="detail-container">
            <!-- Detail Header -->
            <div class="detail-header">
                <div>
                    <h1>Survey Response Details</h1>
                    <p style="margin: 10px 0 0 0; opacity: 0.9;">Response ID: #{{ $response->id }}</p>
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
    <footer class="footer" style="margin-top: 50px; padding: 20px; background: #f8f9fa; text-align: center; color: #666;">
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
    </script>
</body>
</html>

