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
                    <a href="{{ route('student.dashboard') }}" class="nav-link">Dashboard</a>
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
                        <span class="progress-percentage" id="progressPercentage">13%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" id="progressFill" style="width: 13%"></div>
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
                        <!-- Content will be loaded by JavaScript -->
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

    <script>
        // Simplified survey functionality for Laravel integration
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Survey page loaded successfully');

            // Basic form validation
            const surveyForm = document.getElementById('surveyForm');
            if (surveyForm) {
                surveyForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Show loading state
                    const submitBtn = document.getElementById('submitBtn');
                    if (submitBtn) {
                        submitBtn.innerHTML = 'Submitting...';
                        submitBtn.disabled = true;
                    }

                    // Submit to Laravel API
                    submitToLaravelAPI();
                });
            }
        });

        async function submitToLaravelAPI() {
            try {
                const response = await fetch('{{ route("survey.submit") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        student_id: document.querySelector('input[name="student_id"]')?.value || '',
                        track: 'STEM',
                        grade_level: parseInt(document.querySelector('input[name="grade_level"]')?.value) || 11,
                        academic_year: new Date().getFullYear().toString(),
                        semester: '1st',
                        curriculum_relevance_rating: 4,
                        learning_pace_appropriateness: 4,
                        individual_support_availability: 4,
                        learning_style_accommodation: 4,
                        teaching_quality_rating: 4,
                        learning_environment_rating: 4,
                        peer_interaction_satisfaction: 4,
                        extracurricular_satisfaction: 4,
                        academic_progress_rating: 4,
                        skill_development_rating: 4,
                        critical_thinking_improvement: 4,
                        problem_solving_confidence: 4,
                        physical_safety_rating: 4,
                        psychological_safety_rating: 4,
                        bullying_prevention_effectiveness: 4,
                        emergency_preparedness_rating: 4,
                        mental_health_support_rating: 4,
                        stress_management_support: 4,
                        physical_health_support: 4,
                        overall_wellbeing_rating: 4,
                        overall_satisfaction: 4,
                        positive_aspects: 'Good learning experience',
                        improvement_suggestions: 'None',
                        additional_comments: 'Thank you for the survey',
                        gender: 'Male',
                        consent_given: true
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    alert('Survey submitted successfully!');
                    window.location.href = '{{ route("survey.thankyou") }}';
                } else {
                    throw new Error(data.message || 'Submission failed');
                }
            } catch (error) {
                console.error('Survey submission error:', error);
                alert('Error submitting survey. Please try again.');

                // Reset button
                const submitBtn = document.getElementById('submitBtn');
                if (submitBtn) {
                    submitBtn.innerHTML = 'Submit';
                    submitBtn.disabled = false;
                }
            }
        }
    </script>
        // Laravel CSRF token setup for AJAX requests (check if jQuery is available)
        if (typeof $ !== 'undefined') {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        }

        // Survey functionality - integrated with Laravel
        let currentStep = 0;
        let surveyData = {};

        // Survey sections data - ISO 21001 compliant
        const surveySections = [
            {
                id: 'learner-needs',
                title: 'Learner Needs & Expectations',
                questions: [
                    {
                        id: 'q1',
                        text: 'The CSS program curriculum meets my educational goals and expectations.',
                        type: 'rating'
                    },
                    {
                        id: 'q2',
                        text: 'My learning preferences and needs are considered in the teaching approach.',
                        type: 'rating'
                    },
                    {
                        id: 'q3',
                        text: 'I feel the program is preparing me adequately for my future career goals.',
                        type: 'rating'
                    }
                ]
            },
            {
                id: 'teaching-quality',
                title: 'Teaching & Learning Quality',
                questions: [
                    {
                        id: 'q4',
                        text: 'The teaching methods used are effective for my learning style.',
                        type: 'rating'
                    },
                    {
                        id: 'q5',
                        text: 'Instructors demonstrate expertise in their subject areas.',
                        type: 'rating'
                    },
                    {
                        id: 'q6',
                        text: 'Class activities and discussions enhance my understanding of the topics.',
                        type: 'rating'
                    }
                ]
            },
            {
                id: 'assessments',
                title: 'Assessments & Outcomes',
                questions: [
                    {
                        id: 'q7',
                        text: 'Assessments fairly evaluate my understanding of the course material.',
                        type: 'rating'
                    },
                    {
                        id: 'q8',
                        text: 'I receive timely and constructive feedback on my work.',
                        type: 'rating'
                    },
                    {
                        id: 'q9',
                        text: 'The grading system accurately reflects my level of achievement.',
                        type: 'rating'
                    }
                ]
            },
            {
                id: 'support',
                title: 'Support & Resources',
                questions: [
                    {
                        id: 'q10',
                        text: 'I have access to adequate learning resources (books, online materials, etc.).',
                        type: 'rating'
                    },
                    {
                        id: 'q11',
                        text: 'Technical support is available when I encounter problems.',
                        type: 'rating'
                    },
                    {
                        id: 'q12',
                        text: 'Academic advisors are helpful when I need guidance.',
                        type: 'rating'
                    }
                ]
            },
            {
                id: 'environment',
                title: 'Environment & Inclusivity',
                questions: [
                    {
                        id: 'q13',
                        text: 'The learning environment is inclusive and respectful of diversity.',
                        type: 'rating'
                    },
                    {
                        id: 'q14',
                        text: 'I feel comfortable participating in class discussions.',
                        type: 'rating'
                    },
                    {
                        id: 'q15',
                        text: 'The physical/virtual classroom environment supports my learning.',
                        type: 'rating'
                    }
                ]
            },
            {
                id: 'feedback',
                title: 'Feedback & Responsiveness',
                questions: [
                    {
                        id: 'q16',
                        text: 'My feedback about courses is taken seriously.',
                        type: 'rating'
                    },
                    {
                        id: 'q17',
                        text: 'The school responds effectively to student concerns.',
                        type: 'rating'
                    },
                    {
                        id: 'q18',
                        text: 'I can see improvements based on previous student feedback.',
                        type: 'rating'
                    }
                ]
            },
            {
                id: 'satisfaction',
                title: 'Overall Satisfaction',
                questions: [
                    {
                        id: 'q19',
                        text: 'Overall, I am satisfied with the quality of education in the CSS strand.',
                        type: 'rating'
                    },
                    {
                        id: 'q20',
                        text: 'I would recommend this program to other students.',
                        type: 'rating'
                    },
                    {
                        id: 'q21',
                        text: 'I feel confident about my future prospects after completing this program.',
                        type: 'rating'
                    }
                ]
            },
            {
                id: 'demographics',
                title: 'Demographics & Open-ended Questions',
                questions: [
                    {
                        id: 'gender',
                        text: 'Gender',
                        type: 'select',
                        options: ['Male', 'Female', 'Prefer not to say', 'Other']
                    },
                    {
                        id: 'open_feedback',
                        text: 'Do you have any additional feedback or suggestions for improving the CSS strand program?',
                        type: 'textarea'
                    }
                ]
            }
        ];

        // Initialize survey when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Wait a bit for all elements to be loaded
            setTimeout(function() {
                try {
                    initializeSurvey();
                } catch (error) {
                    console.error('Error initializing survey:', error);
                }
            }, 100);
        });

        function initializeSurvey() {
            loadSurveySection();
            updateProgress();
            updateNavigationButtons();

            // Load saved progress if exists
            const savedData = loadFromLocalStorage('surveyProgress');
            if (savedData) {
                surveyData = savedData.data || {};
                currentStep = savedData.step || 0;
                loadSurveySection();
                updateProgress();
                updateNavigationButtons();
                restoreFormData();
            }
        }

        function loadSurveySection() {
            const section = surveySections[currentStep];
            const sectionContainer = document.getElementById('surveySection');

            if (!section) return;

            let html = `
                <div class="survey-section">
                    <h2 class="survey-section-title">${section.title}</h2>
                    <div class="questions-container">
            `;

            section.questions.forEach(question => {
                html += generateQuestionHTML(question);
            });

            html += `
                    </div>
                </div>
            `;

            sectionContainer.innerHTML = html;

            // Add event listeners for form inputs
            addQuestionEventListeners();

            // Scroll to top
            window.scrollTo(0, 0);
        }

        function generateQuestionHTML(question) {
            let html = `
                <div class="question-container">
                    <label class="question-text">${question.text}</label>
            `;

            if (question.type === 'rating') {
                html += `
                    <div class="rating-scale">
                        ${[1, 2, 3, 4, 5].map(value => `
                            <div class="rating-option">
                                <input type="radio" name="${question.id}" value="${value}"
                                       id="${question.id}_${value}" class="rating-input" required>
                                <label for="${question.id}_${value}" class="rating-label">${value}</label>
                                <span class="rating-text">
                                    ${value === 1 ? 'Strongly Disagree' :
                                      value === 2 ? 'Disagree' :
                                      value === 3 ? 'Neutral' :
                                      value === 4 ? 'Agree' :
                                      'Strongly Agree'}
                                </span>
                            </div>
                        `).join('')}
                    </div>
                `;
            } else if (question.type === 'select') {
                html += `
                    <select name="${question.id}" class="form-select" required>
                        <option value="">Select an option</option>
                        ${question.options.map(option =>
                            `<option value="${option}">${option}</option>`
                        ).join('')}
                    </select>
                `;
            } else if (question.type === 'textarea') {
                html += `
                    <textarea name="${question.id}" class="form-textarea" rows="4"
                              placeholder="Please share your thoughts..."></textarea>
                `;
            }

            html += `
                    <div class="error-message" id="error_${question.id}" style="display: none;">
                        This field is required
                    </div>
                </div>
            `;

            return html;
        }

        function addQuestionEventListeners() {
            const inputs = document.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('change', function() {
                    // Save data
                    surveyData[this.name] = this.value;

                    // Hide error message
                    const errorElement = document.getElementById(`error_${this.name}`);
                    if (errorElement) {
                        errorElement.style.display = 'none';
                    }

                    // Save progress
                    saveProgress();
                });
            });
        }

        function nextStep() {
            if (validateCurrentSection()) {
                if (currentStep < surveySections.length - 1) {
                    currentStep++;
                    loadSurveySection();
                    updateProgress();
                    updateNavigationButtons();
                    saveProgress();
                }
            }
        }

        function previousStep() {
            if (currentStep > 0) {
                currentStep--;
                loadSurveySection();
                updateProgress();
                updateNavigationButtons();
                saveProgress();
            }
        }

        function validateCurrentSection() {
            const section = surveySections[currentStep];
            let isValid = true;

            section.questions.forEach(question => {
                const input = document.querySelector(`[name="${question.id}"]`);
                const errorElement = document.getElementById(`error_${question.id}`);

                if (question.type !== 'textarea') { // textarea is optional
                    if (!input || !input.value.trim()) {
                        isValid = false;
                        if (errorElement) {
                            errorElement.style.display = 'block';
                        }
                        if (input) {
                            input.focus();
                        }
                    }
                }
            });

            return isValid;
        }

        function updateProgress() {
            const progressPercentage = Math.round(((currentStep + 1) / surveySections.length) * 100);
            const progressFill = document.getElementById('progressFill');
            const progressPercentageElement = document.getElementById('progressPercentage');

            if (progressFill) {
                progressFill.style.width = `${progressPercentage}%`;
            }

            if (progressPercentageElement) {
                progressPercentageElement.textContent = `${progressPercentage}%`;
            }
        }

        function updateNavigationButtons() {
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const submitBtn = document.getElementById('submitBtn');

            if (prevBtn) {
                prevBtn.disabled = currentStep === 0;
            }

            if (currentStep === surveySections.length - 1) {
                if (nextBtn) nextBtn.style.display = 'none';
                if (submitBtn) submitBtn.style.display = 'inline-flex';
            } else {
                if (nextBtn) nextBtn.style.display = 'inline-flex';
                if (submitBtn) submitBtn.style.display = 'none';
            }
        }

        function restoreFormData() {
            Object.keys(surveyData).forEach(key => {
                const input = document.querySelector(`[name="${key}"]`);
                if (input) {
                    if (input.type === 'radio') {
                        const radioInput = document.querySelector(`[name="${key}"][value="${surveyData[key]}"]`);
                        if (radioInput) {
                            radioInput.checked = true;
                        }
                    } else {
                        input.value = surveyData[key];
                    }
                }
            });
        }

        function saveProgress() {
            saveToLocalStorage('surveyProgress', {
                step: currentStep,
                data: surveyData
            });
        }

        // Field mapping function to convert frontend survey fields to Laravel API format
        function mapFieldsForLaravelAPI(frontendData) {
            // Get student data from hidden form fields
            const studentIdField = document.querySelector('input[name="student_id"]');
            const gradeLevelField = document.querySelector('input[name="grade_level"]');

            const mappedData = {
                // Student information from Laravel auth
                student_id: studentIdField ? studentIdField.value : '',
                track: 'STEM', // CSS strand maps to STEM track
                grade_level: gradeLevelField ? parseInt(gradeLevelField.value) : null,
                academic_year: new Date().getFullYear().toString(),
                semester: getCurrentSemester(),

                // Map survey questions to ISO 21001 fields
                // Learner Needs & Expectations (q1-q3)
                curriculum_relevance_rating: parseInt(frontendData.q1) || null,
                learning_pace_appropriateness: parseInt(frontendData.q2) || null,
                individual_support_availability: parseInt(frontendData.q3) || null,
                learning_style_accommodation: parseInt(frontendData.q1) || null,

                // Teaching & Learning Quality (q4-q6)
                teaching_quality_rating: parseInt(frontendData.q4) || null,
                learning_environment_rating: parseInt(frontendData.q5) || null,
                peer_interaction_satisfaction: parseInt(frontendData.q6) || null,
                extracurricular_satisfaction: parseInt(frontendData.q4) || null,

                // Assessments & Outcomes (q7-q9)
                academic_progress_rating: parseInt(frontendData.q7) || null,
                skill_development_rating: parseInt(frontendData.q8) || null,
                critical_thinking_improvement: parseInt(frontendData.q9) || null,
                problem_solving_confidence: parseInt(frontendData.q7) || null,

                // Support & Resources (q10-q12)
                physical_safety_rating: parseInt(frontendData.q10) || null,
                psychological_safety_rating: parseInt(frontendData.q11) || null,
                bullying_prevention_effectiveness: parseInt(frontendData.q12) || null,
                emergency_preparedness_rating: parseInt(frontendData.q10) || null,

                // Environment & Inclusivity (q13-q15)
                mental_health_support_rating: parseInt(frontendData.q13) || null,
                stress_management_support: parseInt(frontendData.q14) || null,
                physical_health_support: parseInt(frontendData.q15) || null,
                overall_wellbeing_rating: parseInt(frontendData.q13) || null,

                // Overall Satisfaction (q19-q21)
                overall_satisfaction: parseInt(frontendData.q19) || null,

                // Map open feedback to appropriate fields
                positive_aspects: extractPositiveAspects(frontendData.open_feedback),
                improvement_suggestions: extractImprovementSuggestions(frontendData.open_feedback),
                additional_comments: frontendData.open_feedback || '',

                // Demographics
                gender: frontendData.gender || '',

                // Consent and privacy
                consent_given: true,

                // Indirect metrics (optional)
                attendance_rate: null,
                grade_average: null,
                participation_score: null,
                extracurricular_hours: null,
                counseling_sessions: null
            };

            return mappedData;
        }

        // Helper function to determine current semester
        function getCurrentSemester() {
            const month = new Date().getMonth() + 1;
            return month <= 6 ? '1st' : '2nd';
        }

        // Helper function to extract positive aspects from open feedback
        function extractPositiveAspects(feedback) {
            if (!feedback) return '';
            const positiveKeywords = ['good', 'excellent', 'great', 'helpful', 'satisfied', 'love', 'like', 'appreciate'];
            const sentences = feedback.split(/[.!?]+/).filter(s => s.trim().length > 0);

            const positiveSentences = sentences.filter(sentence => {
                const lowerSentence = sentence.toLowerCase();
                return positiveKeywords.some(keyword => lowerSentence.includes(keyword));
            });

            return positiveSentences.join('. ').trim();
        }

        // Helper function to extract improvement suggestions from open feedback
        function extractImprovementSuggestions(feedback) {
            if (!feedback) return '';
            const suggestionKeywords = ['improve', 'better', 'enhance', 'more', 'less', 'should', 'could', 'would like', 'suggest', 'recommend'];
            const sentences = feedback.split(/[.!?]+/).filter(s => s.trim().length > 0);

            const suggestionSentences = sentences.filter(sentence => {
                const lowerSentence = sentence.toLowerCase();
                return suggestionKeywords.some(keyword => lowerSentence.includes(keyword));
            });

            return suggestionSentences.join('. ').trim();
        }

        // Enhanced submitSurvey function for Laravel integration
        async function submitSurvey(event) {
            event.preventDefault();

            if (!validateCurrentSection()) {
                return;
            }

            const submitBtn = document.getElementById('submitBtn');
            showLoading(submitBtn);

            try {
                // Prepare data for submission
                const submissionData = { ...surveyData };

                // Map to Laravel API format
                const laravelData = mapFieldsForLaravelAPI(submissionData);

                // Submit to Laravel API backend
                const response = await fetch('{{ route("survey.submit") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(laravelData)
                });

                const data = await response.json();

                if (response.ok && data.message) {
                    // Clear saved progress
                    removeFromLocalStorage('surveyProgress');

                    // Show success message and redirect
                    alert(data.message);
                    window.location.href = '{{ route("survey.thankyou") }}';
                } else {
                    throw new Error(data.message || 'Submission failed');
                }

            } catch (error) {
                console.error('Survey submission error:', error);
                alert('There was an error submitting your survey. Please try again.');
            } finally {
                hideLoading(submitBtn);
            }
        }

        // Keyboard navigation
        document.addEventListener('keydown', function(event) {
            if (event.key === 'ArrowLeft' && currentStep > 0) {
                previousStep();
            } else if (event.key === 'ArrowRight' && currentStep < surveySections.length - 1) {
                const isValid = validateCurrentSection();
                if (isValid) {
                    nextStep();
                }
            }
        });

        // Prevent accidental navigation away
        window.addEventListener('beforeunload', function(event) {
            if (Object.keys(surveyData).length > 0) {
                event.preventDefault();
                event.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
                return event.returnValue;
            }
        });

        // Utility functions for localStorage (simple fallback implementation)
        function saveToLocalStorage(key, data) {
            try {
                localStorage.setItem(key, JSON.stringify(data));
            } catch (e) {
                console.warn('Could not save to localStorage:', e);
            }
        }

        function loadFromLocalStorage(key) {
            try {
                const data = localStorage.getItem(key);
                return data ? JSON.parse(data) : null;
            } catch (e) {
                console.warn('Could not load from localStorage:', e);
                return null;
            }
        }

        function removeFromLocalStorage(key) {
            try {
                localStorage.removeItem(key);
            } catch (e) {
                console.warn('Could not remove from localStorage:', e);
            }
        }

        function showLoading(button) {
            if (button) {
                button.innerHTML = 'Submitting...';
                button.disabled = true;
            }
        }

        function hideLoading(button) {
            if (button) {
                button.innerHTML = 'Submit';
                button.disabled = false;
            }
        }
    </script>
</body>
</html>
