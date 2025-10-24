<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey - ISO Quality Education</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="nav-wrapper">
                <div class="logo">
                    <a href="{{ route('home') }}">ISO Quality Education</a>
                </div>

                <!-- Desktop navigation -->
                <nav class="desktop-nav">
                    <a href="{{ route('home') }}" class="nav-link">Home</a>
                    <a href="{{ route('survey.form') }}" class="nav-link active">Survey</a>
                    @auth
                        <form method="POST" action="{{ route('student.logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="nav-link" style="background: none; border: none; color: inherit; cursor: pointer;">
                                Logout ({{ Auth::user()->name }})
                            </button>
                        </form>
                    @endauth
                </nav>
            </div>
        </div>
    </header>

    <main class="survey-main">
        <div class="container survey-container">
            <div class="survey-card">
                <h1 class="survey-title">ISO Learner-Centric Survey</h1>
                <p class="survey-subtitle">
                    Your feedback helps us improve the quality of education for CSS Strand students.
                </p>

                @auth
                    <div class="student-info" style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                        <p><strong>Welcome, {{ Auth::user()->name }}!</strong></p>
                        <p>Student ID: {{ Auth::user()->student_id }} | Year: {{ Auth::user()->year_level }} | Section: {{ Auth::user()->section }}</p>
                    </div>
                @endauth

                <!-- Progress bar -->
                <div class="progress-section">
                    <div class="progress-header">
                        <span class="progress-label">Progress</span>
                        <span class="progress-percentage" id="progressPercentage">0%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" id="progressFill" style="width: 0%"></div>
                    </div>
                </div>

                <form id="surveyForm" onsubmit="submitSurvey(event)">
                    @csrf
                    <!-- Hidden fields for student info -->
                    @auth
                        <input type="hidden" name="student_id" value="{{ Auth::user()->student_id }}">
                        <input type="hidden" name="track" value="STEM">
                        <input type="hidden" name="grade_level" value="{{ Auth::user()->year_level }}">
                        <input type="hidden" name="year_level" value="{{ Auth::user()->year_level === 11 ? 'Grade 11' : 'Grade 12' }}">
                    @endauth

                    <!-- Survey sections will be dynamically loaded here -->
                    <div id="surveySection">
                        <!-- Section 1: Learner Needs & Expectations -->
                        <div class="survey-step" data-step="1">
                            <h2 class="section-title">Learner Needs & Expectations</h2>

                            <div class="question-group">
                                <label class="question-label">1. The CSS program curriculum meets my educational goals and expectations.</label>
                                <div class="likert-scale">
                                    <label class="likert-option">
                                        <input type="radio" name="q1" value="1" required>
                                        <span class="likert-label">1<br><small>Strongly Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q1" value="2" required>
                                        <span class="likert-label">2<br><small>Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q1" value="3" required>
                                        <span class="likert-label">3<br><small>Neutral</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q1" value="4" required>
                                        <span class="likert-label">4<br><small>Agree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q1" value="5" required>
                                        <span class="likert-label">5<br><small>Strongly Agree</small></span>
                                    </label>
                                </div>
                            </div>

                            <div class="question-group">
                                <label class="question-label">2. My learning preferences and needs are considered in the teaching approach.</label>
                                <div class="likert-scale">
                                    <label class="likert-option">
                                        <input type="radio" name="q2" value="1" required>
                                        <span class="likert-label">1<br><small>Strongly Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q2" value="2" required>
                                        <span class="likert-label">2<br><small>Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q2" value="3" required>
                                        <span class="likert-label">3<br><small>Neutral</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q2" value="4" required>
                                        <span class="likert-label">4<br><small>Agree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q2" value="5" required>
                                        <span class="likert-label">5<br><small>Strongly Agree</small></span>
                                    </label>
                                </div>
                            </div>

                            <div class="question-group">
                                <label class="question-label">3. I feel the program is preparing me adequately for my future career goals.</label>
                                <div class="likert-scale">
                                    <label class="likert-option">
                                        <input type="radio" name="q3" value="1" required>
                                        <span class="likert-label">1<br><small>Strongly Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q3" value="2" required>
                                        <span class="likert-label">2<br><small>Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q3" value="3" required>
                                        <span class="likert-label">3<br><small>Neutral</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q3" value="4" required>
                                        <span class="likert-label">4<br><small>Agree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q3" value="5" required>
                                        <span class="likert-label">5<br><small>Strongly Agree</small></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Teaching & Learning Quality -->
                        <div class="survey-step" data-step="2" style="display: none;">
                            <h2 class="section-title">Teaching & Learning Quality</h2>

                            <div class="question-group">
                                <label class="question-label">4. The teaching methods used are effective for my learning style.</label>
                                <div class="likert-scale">
                                    <label class="likert-option">
                                        <input type="radio" name="q4" value="1" required>
                                        <span class="likert-label">1<br><small>Strongly Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q4" value="2" required>
                                        <span class="likert-label">2<br><small>Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q4" value="3" required>
                                        <span class="likert-label">3<br><small>Neutral</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q4" value="4" required>
                                        <span class="likert-label">4<br><small>Agree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q4" value="5" required>
                                        <span class="likert-label">5<br><small>Strongly Agree</small></span>
                                    </label>
                                </div>
                            </div>

                            <div class="question-group">
                                <label class="question-label">5. Instructors demonstrate expertise in their subject areas.</label>
                                <div class="likert-scale">
                                    <label class="likert-option">
                                        <input type="radio" name="q5" value="1" required>
                                        <span class="likert-label">1<br><small>Strongly Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q5" value="2" required>
                                        <span class="likert-label">2<br><small>Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q5" value="3" required>
                                        <span class="likert-label">3<br><small>Neutral</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q5" value="4" required>
                                        <span class="likert-label">4<br><small>Agree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q5" value="5" required>
                                        <span class="likert-label">5<br><small>Strongly Agree</small></span>
                                    </label>
                                </div>
                            </div>

                            <div class="question-group">
                                <label class="question-label">6. Class activities and discussions enhance my understanding of the topics.</label>
                                <div class="likert-scale">
                                    <label class="likert-option">
                                        <input type="radio" name="q6" value="1" required>
                                        <span class="likert-label">1<br><small>Strongly Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q6" value="2" required>
                                        <span class="likert-label">2<br><small>Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q6" value="3" required>
                                        <span class="likert-label">3<br><small>Neutral</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q6" value="4" required>
                                        <span class="likert-label">4<br><small>Agree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q6" value="5" required>
                                        <span class="likert-label">5<br><small>Strongly Agree</small></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Section 3: Assessments & Outcomes -->
                        <div class="survey-step" data-step="3" style="display: none;">
                            <h2 class="section-title">Assessments & Outcomes</h2>

                            <div class="question-group">
                                <label class="question-label">7. Assessments fairly evaluate my understanding of the course material.</label>
                                <div class="likert-scale">
                                    <label class="likert-option">
                                        <input type="radio" name="q7" value="1" required>
                                        <span class="likert-label">1<br><small>Strongly Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q7" value="2" required>
                                        <span class="likert-label">2<br><small>Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q7" value="3" required>
                                        <span class="likert-label">3<br><small>Neutral</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q7" value="4" required>
                                        <span class="likert-label">4<br><small>Agree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q7" value="5" required>
                                        <span class="likert-label">5<br><small>Strongly Agree</small></span>
                                    </label>
                                </div>
                            </div>

                            <div class="question-group">
                                <label class="question-label">8. I receive timely and constructive feedback on my work.</label>
                                <div class="likert-scale">
                                    <label class="likert-option">
                                        <input type="radio" name="q8" value="1" required>
                                        <span class="likert-label">1<br><small>Strongly Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q8" value="2" required>
                                        <span class="likert-label">2<br><small>Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q8" value="3" required>
                                        <span class="likert-label">3<br><small>Neutral</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q8" value="4" required>
                                        <span class="likert-label">4<br><small>Agree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q8" value="5" required>
                                        <span class="likert-label">5<br><small>Strongly Agree</small></span>
                                    </label>
                                </div>
                            </div>

                            <div class="question-group">
                                <label class="question-label">9. The grading system accurately reflects my level of achievement.</label>
                                <div class="likert-scale">
                                    <label class="likert-option">
                                        <input type="radio" name="q9" value="1" required>
                                        <span class="likert-label">1<br><small>Strongly Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q9" value="2" required>
                                        <span class="likert-label">2<br><small>Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q9" value="3" required>
                                        <span class="likert-label">3<br><small>Neutral</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q9" value="4" required>
                                        <span class="likert-label">4<br><small>Agree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q9" value="5" required>
                                        <span class="likert-label">5<br><small>Strongly Agree</small></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Section 4: Support & Resources -->
                        <div class="survey-step" data-step="4" style="display: none;">
                            <h2 class="section-title">Support & Resources</h2>

                            <div class="question-group">
                                <label class="question-label">10. I have access to adequate learning resources (books, online materials, etc.).</label>
                                <div class="likert-scale">
                                    <label class="likert-option">
                                        <input type="radio" name="q10" value="1" required>
                                        <span class="likert-label">1<br><small>Strongly Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q10" value="2" required>
                                        <span class="likert-label">2<br><small>Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q10" value="3" required>
                                        <span class="likert-label">3<br><small>Neutral</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q10" value="4" required>
                                        <span class="likert-label">4<br><small>Agree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q10" value="5" required>
                                        <span class="likert-label">5<br><small>Strongly Agree</small></span>
                                    </label>
                                </div>
                            </div>

                            <div class="question-group">
                                <label class="question-label">11. Technical support is available when I encounter problems.</label>
                                <div class="likert-scale">
                                    <label class="likert-option">
                                        <input type="radio" name="q11" value="1" required>
                                        <span class="likert-label">1<br><small>Strongly Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q11" value="2" required>
                                        <span class="likert-label">2<br><small>Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q11" value="3" required>
                                        <span class="likert-label">3<br><small>Neutral</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q11" value="4" required>
                                        <span class="likert-label">4<br><small>Agree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q11" value="5" required>
                                        <span class="likert-label">5<br><small>Strongly Agree</small></span>
                                    </label>
                                </div>
                            </div>

                            <div class="question-group">
                                <label class="question-label">12. Academic advisors are helpful when I need guidance.</label>
                                <div class="likert-scale">
                                    <label class="likert-option">
                                        <input type="radio" name="q12" value="1" required>
                                        <span class="likert-label">1<br><small>Strongly Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q12" value="2" required>
                                        <span class="likert-label">2<br><small>Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q12" value="3" required>
                                        <span class="likert-label">3<br><small>Neutral</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q12" value="4" required>
                                        <span class="likert-label">4<br><small>Agree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q12" value="5" required>
                                        <span class="likert-label">5<br><small>Strongly Agree</small></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Section 5: Environment & Inclusivity -->
                        <div class="survey-step" data-step="5" style="display: none;">
                            <h2 class="section-title">Environment & Inclusivity</h2>

                            <div class="question-group">
                                <label class="question-label">13. The learning environment is inclusive and respectful of diversity.</label>
                                <div class="likert-scale">
                                    <label class="likert-option">
                                        <input type="radio" name="q13" value="1" required>
                                        <span class="likert-label">1<br><small>Strongly Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q13" value="2" required>
                                        <span class="likert-label">2<br><small>Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q13" value="3" required>
                                        <span class="likert-label">3<br><small>Neutral</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q13" value="4" required>
                                        <span class="likert-label">4<br><small>Agree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q13" value="5" required>
                                        <span class="likert-label">5<br><small>Strongly Agree</small></span>
                                    </label>
                                </div>
                            </div>

                            <div class="question-group">
                                <label class="question-label">14. I feel comfortable participating in class discussions.</label>
                                <div class="likert-scale">
                                    <label class="likert-option">
                                        <input type="radio" name="q14" value="1" required>
                                        <span class="likert-label">1<br><small>Strongly Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q14" value="2" required>
                                        <span class="likert-label">2<br><small>Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q14" value="3" required>
                                        <span class="likert-label">3<br><small>Neutral</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q14" value="4" required>
                                        <span class="likert-label">4<br><small>Agree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q14" value="5" required>
                                        <span class="likert-label">5<br><small>Strongly Agree</small></span>
                                    </label>
                                </div>
                            </div>

                            <div class="question-group">
                                <label class="question-label">15. The physical/virtual classroom environment supports my learning.</label>
                                <div class="likert-scale">
                                    <label class="likert-option">
                                        <input type="radio" name="q15" value="1" required>
                                        <span class="likert-label">1<br><small>Strongly Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q15" value="2" required>
                                        <span class="likert-label">2<br><small>Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q15" value="3" required>
                                        <span class="likert-label">3<br><small>Neutral</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q15" value="4" required>
                                        <span class="likert-label">4<br><small>Agree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q15" value="5" required>
                                        <span class="likert-label">5<br><small>Strongly Agree</small></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Section 6: Feedback & Responsiveness -->
                        <div class="survey-step" data-step="6" style="display: none;">
                            <h2 class="section-title">Feedback & Responsiveness</h2>

                            <div class="question-group">
                                <label class="question-label">16. My feedback about courses is taken seriously.</label>
                                <div class="likert-scale">
                                    <label class="likert-option">
                                        <input type="radio" name="q16" value="1" required>
                                        <span class="likert-label">1<br><small>Strongly Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q16" value="2" required>
                                        <span class="likert-label">2<br><small>Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q16" value="3" required>
                                        <span class="likert-label">3<br><small>Neutral</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q16" value="4" required>
                                        <span class="likert-label">4<br><small>Agree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q16" value="5" required>
                                        <span class="likert-label">5<br><small>Strongly Agree</small></span>
                                    </label>
                                </div>
                            </div>

                            <div class="question-group">
                                <label class="question-label">17. The school responds effectively to student concerns.</label>
                                <div class="likert-scale">
                                    <label class="likert-option">
                                        <input type="radio" name="q17" value="1" required>
                                        <span class="likert-label">1<br><small>Strongly Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q17" value="2" required>
                                        <span class="likert-label">2<br><small>Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q17" value="3" required>
                                        <span class="likert-label">3<br><small>Neutral</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q17" value="4" required>
                                        <span class="likert-label">4<br><small>Agree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q17" value="5" required>
                                        <span class="likert-label">5<br><small>Strongly Agree</small></span>
                                    </label>
                                </div>
                            </div>

                            <div class="question-group">
                                <label class="question-label">18. I can see improvements based on previous student feedback.</label>
                                <div class="likert-scale">
                                    <label class="likert-option">
                                        <input type="radio" name="q18" value="1" required>
                                        <span class="likert-label">1<br><small>Strongly Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q18" value="2" required>
                                        <span class="likert-label">2<br><small>Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q18" value="3" required>
                                        <span class="likert-label">3<br><small>Neutral</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q18" value="4" required>
                                        <span class="likert-label">4<br><small>Agree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q18" value="5" required>
                                        <span class="likert-label">5<br><small>Strongly Agree</small></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Section 7: Overall Satisfaction -->
                        <div class="survey-step" data-step="7" style="display: none;">
                            <h2 class="section-title">Overall Satisfaction</h2>

                            <div class="question-group">
                                <label class="question-label">19. Overall, I am satisfied with the quality of education in the CSS strand.</label>
                                <div class="likert-scale">
                                    <label class="likert-option">
                                        <input type="radio" name="q19" value="1" required>
                                        <span class="likert-label">1<br><small>Strongly Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q19" value="2" required>
                                        <span class="likert-label">2<br><small>Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q19" value="3" required>
                                        <span class="likert-label">3<br><small>Neutral</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q19" value="4" required>
                                        <span class="likert-label">4<br><small>Agree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q19" value="5" required>
                                        <span class="likert-label">5<br><small>Strongly Agree</small></span>
                                    </label>
                                </div>
                            </div>

                            <div class="question-group">
                                <label class="question-label">20. I would recommend this program to other students.</label>
                                <div class="likert-scale">
                                    <label class="likert-option">
                                        <input type="radio" name="q20" value="1" required>
                                        <span class="likert-label">1<br><small>Strongly Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q20" value="2" required>
                                        <span class="likert-label">2<br><small>Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q20" value="3" required>
                                        <span class="likert-label">3<br><small>Neutral</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q20" value="4" required>
                                        <span class="likert-label">4<br><small>Agree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q20" value="5" required>
                                        <span class="likert-label">5<br><small>Strongly Agree</small></span>
                                    </label>
                                </div>
                            </div>

                            <div class="question-group">
                                <label class="question-label">21. I feel confident about my future prospects after completing this program.</label>
                                <div class="likert-scale">
                                    <label class="likert-option">
                                        <input type="radio" name="q21" value="1" required>
                                        <span class="likert-label">1<br><small>Strongly Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q21" value="2" required>
                                        <span class="likert-label">2<br><small>Disagree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q21" value="3" required>
                                        <span class="likert-label">3<br><small>Neutral</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q21" value="4" required>
                                        <span class="likert-label">4<br><small>Agree</small></span>
                                    </label>
                                    <label class="likert-option">
                                        <input type="radio" name="q21" value="5" required>
                                        <span class="likert-label">5<br><small>Strongly Agree</small></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Section 8: Demographics & Open-ended Questions -->
                        <div class="survey-step" data-step="8" style="display: none;">
                            <h2 class="section-title">Demographics & Open-ended Questions</h2>

                            <div class="question-group">
                                <label class="question-label">What is your current year level? <span class="required">*</span></label>
                                <select name="demographics_year_level" class="form-select" required>
                                    <option value="">Select your year level</option>
                                    <option value="Grade 11">Grade 11</option>
                                    <option value="Grade 12">Grade 12</option>
                                </select>
                            </div>

                            <div class="question-group">
                                <label class="question-label">Gender <span class="required">*</span></label>
                                <select name="gender" class="form-select" required>
                                    <option value="">Select your gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Non-binary">Non-binary</option>
                                    <option value="Prefer not to say">Prefer not to say</option>
                                </select>
                            </div>

                            <div class="question-group">
                                <label class="question-label">Do you have any additional feedback or suggestions for improving the CSS strand program? <span class="required">*</span></label>
                                <textarea name="additional_feedback" class="form-textarea" rows="6" placeholder="Please share your thoughts and suggestions here..." required></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation buttons -->
                    <div class="survey-navigation">
                        <button type="button" id="prevBtn" onclick="previousStep()" class="btn btn-outline" disabled>
                            <svg class="btn-icon btn-icon-left" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Previous
                        </button>
                        <button type="button" id="nextBtn" onclick="nextStep()" class="btn btn-secondary">
                            Next
                            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        <button type="submit" id="submitBtn" class="btn btn-success" style="display: none;">
                            Submit
                            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </button>
                    </div>
                </form>
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
                <div class="footer-links">
                    <h4 class="footer-links-title">Quick Links</h4>
                    <ul class="footer-links-list">
                        <li><a href="{{ route('home') }}" class="footer-link">About this Survey</a></li>
                        <li><a href="#" class="footer-link">Privacy Policy</a></li>
                        <li><a href="#" class="footer-link">Contact Academic Affairs</a></li>
                    </ul>
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
    <script src="{{ asset('js/survey.js') }}"></script>
</body>
</html>
