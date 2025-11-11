// Survey functionality for Laravel with static HTML structure
let currentStep = 0; // Start with first survey section (no consent step)
let totalSteps = 7; // 8 total sections indexed 0..7 (no consent step)
let surveyData = {};
let loadedSteps = new Set([0]); // Track which steps have been loaded
let touchStartX = 0;
let touchEndX = 0;
let autoSaveTimeout = null;

// Laravel-specific functions

// Survey sections data
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
        id: 'student-info',
        title: 'Student Information',
        questions: [
            {
                id: 'student_id',
                text: 'Student ID (from your registration):',
                type: 'text',
                placeholder: 'Enter your student ID'
            },
            {
                id: 'year_level',
                text: 'What is your current year level?',
                type: 'select',
                options: ['Grade 11', 'Grade 12']
            },
            {
                id: 'track',
                text: 'Academic Track:',
                type: 'select',
                options: ['STEM']
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

// Initialize survey
document.addEventListener('DOMContentLoaded', function() {
    if (window.location.pathname.includes('survey.html')) {
        initializeSurvey();
        loadStudentDataIntoSurvey();
    }
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

// Load student data from registration into survey form
function loadStudentDataIntoSurvey() {
    const studentDataJSON = sessionStorage.getItem('surveyStudentData');
    if (!studentDataJSON) {
        console.log('No student data found in session');
        return;
    }

    try {
        const studentData = JSON.parse(studentDataJSON);

        // Pre-populate survey form with student data
        if (studentData.student_id) {
            surveyData.student_id = studentData.student_id;
        }

        if (studentData.year_level) {
            surveyData.year_level = studentData.year_level;
        }

        // Auto-fill the student information section when it's loaded
        setTimeout(() => {
            const studentIdInput = document.querySelector('input[name="student_id"]');
            const yearLevelSelect = document.querySelector('select[name="year_level"]');
            const trackSelect = document.querySelector('select[name="track"]');

            if (studentIdInput && studentData.student_id) {
                studentIdInput.value = studentData.student_id;
            }

            if (yearLevelSelect && studentData.year_level) {
                yearLevelSelect.value = studentData.year_level === '11' ? 'Grade 11' : 'Grade 12';
            }

            if (trackSelect) {
                trackSelect.value = 'STEM';
            }

            console.log('Student data loaded into survey:', studentData);
        }, 500);

    } catch (e) {
        console.error('Error loading student data into survey:', e);
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
    } else if (question.type === 'text') {
        html += `
            <input type="text" name="${question.id}" class="form-text" required
                   placeholder="${question.placeholder || ''}">
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
        prevBtn.disabled = currentStep === 0; // Disable on consent step
    }

    // Show submit button on last survey step (step 8), not on consent
    if (currentStep === totalSteps) {
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
    const mappedData = {
        // Student information from registration
        student_id: frontendData.student_id || '',
        track: 'STEM', // CSS strand maps to STEM track
        grade_level: frontendData.year_level ? parseInt(frontendData.year_level) : null,
        academic_year: new Date().getFullYear().toString(),
        semester: getCurrentSemester(),

        // Map survey questions to ISO 21001 fields
        // Learner Needs & Expectations (q1-q3)
        curriculum_relevance_rating: parseInt(frontendData.q1) || null,
        learning_pace_appropriateness: parseInt(frontendData.q2) || null,
        individual_support_availability: parseInt(frontendData.q3) || null,
        learning_style_accommodation: parseInt(frontendData.q1) || null, // Map q1 as backup for learning style

        // Teaching & Learning Quality (q4-q6)
        teaching_quality_rating: parseInt(frontendData.q4) || null,
        learning_environment_rating: parseInt(frontendData.q5) || null,
        peer_interaction_satisfaction: parseInt(frontendData.q6) || null,
        extracurricular_satisfaction: parseInt(frontendData.q4) || null, // Map q4 as backup for extracurricular

        // Assessments & Outcomes (q7-q9)
        academic_progress_rating: parseInt(frontendData.q7) || null,
        skill_development_rating: parseInt(frontendData.q8) || null,
        critical_thinking_improvement: parseInt(frontendData.q9) || null,
        problem_solving_confidence: parseInt(frontendData.q7) || null, // Map q7 as backup for problem solving

        // Support & Resources (q10-q12)
        physical_safety_rating: parseInt(frontendData.q10) || null,
        psychological_safety_rating: parseInt(frontendData.q11) || null,
        bullying_prevention_effectiveness: parseInt(frontendData.q12) || null,
        emergency_preparedness_rating: parseInt(frontendData.q10) || null, // Map q10 as backup for emergency

        // Environment & Inclusivity (q13-q15)
        mental_health_support_rating: parseInt(frontendData.q13) || null,
        stress_management_support: parseInt(frontendData.q14) || null,
        physical_health_support: parseInt(frontendData.q15) || null,
        overall_wellbeing_rating: parseInt(frontendData.q13) || null, // Map q13 as backup for wellbeing

        // Overall Satisfaction (q19-q21)
        overall_satisfaction: parseInt(frontendData.q19) || null,

        // Map open feedback to appropriate fields
        positive_aspects: extractPositiveAspects(frontendData.open_feedback),
        improvement_suggestions: extractImprovementSuggestions(frontendData.open_feedback),
        additional_comments: frontendData.open_feedback || '',

        // Consent and privacy (GDPR & ISO 27001 compliant)
        // If user is authenticated (has student_id), consent was given during registration
        consent_given: (frontendData.student_id) ? true : (document.getElementById('consentGiven') ? document.getElementById('consentGiven').checked : false),

        // Indirect metrics (optional - can be populated from student records later)
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
    const month = new Date().getMonth() + 1; // getMonth() returns 0-11
    return month <= 6 ? '1st' : '2nd'; // Assuming 1st semester ends in June
}

// Helper function to extract positive aspects from open feedback
function extractPositiveAspects(feedback) {
    if (!feedback) return '';
    // Simple extraction of positive content (can be enhanced with AI later)
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
    // Simple extraction of suggestions (can be enhanced with AI later)
    const suggestionKeywords = ['improve', 'better', 'enhance', 'more', 'less', 'should', 'could', 'would like', 'suggest', 'recommend'];
    const sentences = feedback.split(/[.!?]+/).filter(s => s.trim().length > 0);

    const suggestionSentences = sentences.filter(sentence => {
        const lowerSentence = sentence.toLowerCase();
        return suggestionKeywords.some(keyword => lowerSentence.includes(keyword));
    });

    return suggestionSentences.join('. ').trim();
}

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

        // Add timestamp
        submissionData.submitted_at = new Date().toISOString();

        // Submit to Laravel API backend
        const response = await makeApiRequest('/api/survey/submit', {
            method: 'POST',
            body: JSON.stringify(mapFieldsForLaravelAPI(submissionData))
        });

        if (response.success) {
            // Clear saved progress
            removeFromLocalStorage('surveyProgress');

            // Redirect to thank you page
            window.location.href = 'thank-you.html';
        } else {
            throw new Error(response.message || 'Submission failed');
        }

    } catch (error) {
        console.error('Survey submission error:', error);
        showNotification('There was an error submitting your survey. Please try again.', 'error');
    } finally {
        hideLoading(submitBtn);
    }
}

// Keyboard navigation
document.addEventListener('keydown', function(event) {
    if (window.location.pathname.includes('survey.html')) {
        if (event.key === 'ArrowLeft' && currentStep > 0) {
            previousStep();
        } else if (event.key === 'ArrowRight' && currentStep < surveySections.length - 1) {
            const isValid = validateCurrentSection();
            if (isValid) {
                nextStep();
            }
        }
    }
});

// Track if form is being submitted to prevent beforeunload warning
let isSubmitting = false;

// Prevent accidental navigation away (disabled - using auto-save instead)
// Removed beforeunload warning as requested - data is auto-saved

// Laravel-specific functions
async function submitSurveyLaravel(event) {
    event.preventDefault();

    console.log('Submit survey called - Current step:', currentStep);
    console.log('Total steps:', totalSteps);

    // Validate consent before submission
    const consentCheckbox = document.getElementById('consentGiven');
    if (!consentCheckbox) {
        console.warn('Consent checkbox not found. Proceeding with submission...');
    } else if (!consentCheckbox.checked) {
        // Only show alert if checkbox exists and is not checked
        // If checkbox is hidden or doesn't have required attribute, it means consent was already given
        if (consentCheckbox.hasAttribute('required')) {
            alert('You must provide consent before submitting the survey. Please go back to the consent section and check the consent box.');
            // Scroll to consent section
            showStep(0);
            updateProgressBar();
            updateNavigationButtons();
            return;
        }
    }

    // Use validateCurrentStep for static HTML structure
    if (!validateCurrentStep()) {
        console.log('Validation failed for current step');
        return;
    }

    console.log('Validation passed, proceeding with submission...');

    // Set submitting flag to prevent beforeunload warnings
    isSubmitting = true;

    const submitBtn = document.getElementById('submitBtn');

    // Show loading state with overlay
    showLoadingOverlay();

    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.classList.add('loading');
        const btnText = submitBtn.querySelector('.btn-text');
        const btnLoader = submitBtn.querySelector('.btn-loader');
        const btnIcon = submitBtn.querySelector('.btn-icon-check');
        if (btnText) btnText.style.display = 'none';
        if (btnLoader) btnLoader.style.display = 'inline-block';
        if (btnIcon) btnIcon.style.display = 'none';
    }

    try {
        // Collect all form data from the actual form inputs
        const form = document.getElementById('surveyForm');
        const formData = new FormData(form);

        // Get form fields
        const studentIdField = document.querySelector('input[name="student_id"]');
        const trackField = document.querySelector('input[name="track"]');
        const gradeLevelField = document.querySelector('input[name="grade_level"]');
        const yearLevelField = document.querySelector('input[name="year_level"]');
        const additionalFeedbackField = document.querySelector('textarea[name="additional_feedback"]');

        console.log('Student ID field:', studentIdField ? studentIdField.value : 'NOT FOUND');
        console.log('Grade level field:', gradeLevelField ? gradeLevelField.value : 'NOT FOUND');
        console.log('Year level field:', yearLevelField ? yearLevelField.value : 'NOT FOUND');
        console.log('Track field:', trackField ? trackField.value : 'NOT FOUND');

        // Get all question responses
        const getQuestionValue = (qName) => {
            const input = document.querySelector(`input[name="${qName}"]:checked`);
            return input ? parseInt(input.value) : null;
        };

        // Determine grade level from hidden field
        let gradeLevel = 11;
        if (gradeLevelField && gradeLevelField.value) {
            gradeLevel = parseInt(gradeLevelField.value);
        } else if (yearLevelField && yearLevelField.value) {
            // Parse year_level like "Grade 11" or just "11"
            const yearValue = yearLevelField.value.toString().replace(/\D/g, '');
            gradeLevel = parseInt(yearValue) || 11;
        }

        // Get track value
        let track = 'CSS';
        if (trackField && trackField.value) {
            track = trackField.value;
        }

        // Map to Laravel API format with actual form data
        const laravelData = {
            // Student information from Laravel auth - ONLY include if field exists
            ...(studentIdField && studentIdField.value && { student_id: studentIdField.value }),
            track: track, // CSS strand
            grade_level: gradeLevel,
            academic_year: new Date().getFullYear().toString(),
            semester: getCurrentSemester(),

            // Map survey questions to ISO 21001 fields
            // Learner Needs & Expectations (q1-q3)
            curriculum_relevance_rating: getQuestionValue('q1') || 1,
            learning_pace_appropriateness: getQuestionValue('q2') || 1,
            individual_support_availability: getQuestionValue('q3') || 1,
            learning_style_accommodation: getQuestionValue('q1') || 1,

            // Teaching & Learning Quality (q4-q6)
            teaching_quality_rating: getQuestionValue('q4') || 1,
            learning_environment_rating: getQuestionValue('q5') || 1,
            peer_interaction_satisfaction: getQuestionValue('q6') || 1,
            extracurricular_satisfaction: getQuestionValue('q4') || 1,

            // Assessments & Outcomes (q7-q9)
            academic_progress_rating: getQuestionValue('q7') || 1,
            skill_development_rating: getQuestionValue('q8') || 1,
            critical_thinking_improvement: getQuestionValue('q9') || 1,
            problem_solving_confidence: getQuestionValue('q7') || 1,

            // Support & Resources (q10-q12)
            physical_safety_rating: getQuestionValue('q10') || 1,
            psychological_safety_rating: getQuestionValue('q11') || 1,
            bullying_prevention_effectiveness: getQuestionValue('q12') || 1,
            emergency_preparedness_rating: getQuestionValue('q10') || 1,

            // Environment & Inclusivity (q13-q15)
            mental_health_support_rating: getQuestionValue('q13') || 1,
            stress_management_support: getQuestionValue('q14') || 1,
            physical_health_support: getQuestionValue('q15') || 1,
            overall_wellbeing_rating: getQuestionValue('q13') || 1,

            // Overall Satisfaction (q19-q21)
            overall_satisfaction: getQuestionValue('q19') || 1,

            // Map open feedback to appropriate fields
            positive_aspects: extractPositiveAspects(additionalFeedbackField ? additionalFeedbackField.value : ''),
            improvement_suggestions: extractImprovementSuggestions(additionalFeedbackField ? additionalFeedbackField.value : ''),
            additional_comments: additionalFeedbackField ? additionalFeedbackField.value : '',

            // Consent and privacy (GDPR & ISO 27001 compliant)
            // If user is authenticated (has student_id), consent was given during registration
            consent_given: (studentIdField && studentIdField.value) ? true : (document.getElementById('consentGiven') ? document.getElementById('consentGiven').checked : false),

            // Indirect metrics (optional)
            attendance_rate: null,
            grade_average: null,
            participation_score: null,
            extracurricular_hours: null,
            counseling_sessions: null
        };

        // Explicitly remove any fields that are not allowed (data minimization)
        const allowedFields = [
            'student_id', 'track', 'grade_level', 'academic_year', 'semester', 'gender',
            'curriculum_relevance_rating', 'learning_pace_appropriateness', 'individual_support_availability',
            'learning_style_accommodation', 'teaching_quality_rating', 'learning_environment_rating',
            'peer_interaction_satisfaction', 'extracurricular_satisfaction', 'academic_progress_rating',
            'skill_development_rating', 'critical_thinking_improvement', 'problem_solving_confidence',
            'physical_safety_rating', 'psychological_safety_rating', 'bullying_prevention_effectiveness',
            'emergency_preparedness_rating', 'mental_health_support_rating', 'stress_management_support',
            'physical_health_support', 'overall_wellbeing_rating', 'overall_satisfaction',
            'positive_aspects', 'improvement_suggestions', 'additional_comments',
            'feedback_taken_seriously', 'school_responsiveness', 'visible_improvements',
            'attendance_rate', 'grade_average', 'participation_score', 'extracurricular_hours',
            'counseling_sessions', 'consent_given'
        ];
        
        // Filter out any fields that are not in the allowed list
        const filteredData = {};
        Object.keys(laravelData).forEach(key => {
            if (allowedFields.includes(key)) {
                filteredData[key] = laravelData[key];
            } else {
                console.warn('Filtering out disallowed field:', key);
            }
        });
        
        // Also check FormData for any additional allowed fields that might be in the form
        if (formData) {
            for (const [key, value] of formData.entries()) {
                if (allowedFields.includes(key) && !filteredData.hasOwnProperty(key)) {
                    filteredData[key] = value;
                }
            }
        }
        
        // Also remove any q16, q17, q18 fields that might have been included from form data
        delete filteredData.q16;
        delete filteredData.q17;
        delete filteredData.q18;

        console.log('Submitting survey data (filtered):', filteredData);
        console.log('Fields being sent:', Object.keys(filteredData));

        // Get CSRF token safely
        let csrfToken = '';
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        if (csrfMeta) {
            csrfToken = csrfMeta.getAttribute('content');
            console.log('CSRF token found:', csrfToken ? 'Yes' : 'No');
        } else {
            console.warn('CSRF token meta tag not found');
        }

        // Submit to Laravel API backend
        console.log('Sending request to /api/survey/submit...');
        let response;
        try {
            response = await fetch('/api/survey/submit', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(filteredData)
            });
        } catch (fetchError) {
            // Network error (CORS, connection refused, etc.)
            console.error('Network error during fetch:', fetchError);
            throw new Error('Network error: Unable to connect to the server. Please check your internet connection and try again.');
        }

        console.log('Response received. Status:', response.status, 'Status Text:', response.statusText);

        // Check if response has content before trying to parse JSON
        let data;
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            try {
                data = await response.json();
            } catch (jsonError) {
                console.error('Error parsing JSON response:', jsonError);
                throw new Error('Server returned an invalid response. Please try again.');
            }
        } else {
            // Non-JSON response (could be HTML error page)
            const text = await response.text();
            console.error('Non-JSON response received:', text.substring(0, 200));
            throw new Error(`Server error (${response.status}): ${response.statusText}. Please try again.`);
        }

        console.log('Response data:', data);

        if (response.ok && data.message) {
            console.log('Survey submitted successfully!');
            
            // Clear saved progress
            removeFromLocalStorage('surveyProgress');
            
            // Update button to show success
            if (submitBtn) {
                submitBtn.classList.remove('loading');
                const btnText = submitBtn.querySelector('.btn-text');
                const btnLoader = submitBtn.querySelector('.btn-loader');
                const btnIcon = submitBtn.querySelector('.btn-icon-check');
                if (btnText) btnText.textContent = 'Submitted!';
                if (btnLoader) btnLoader.style.display = 'none';
                if (btnIcon) btnIcon.style.display = 'inline-block';
            }
            
            // Update loading overlay with success message
            updateLoadingOverlay('Survey submitted successfully! Redirecting...', 'success');
            
            // Redirect immediately to thank you page
            setTimeout(() => {
                window.location.href = '/thank-you';
            }, 500);
        } else {
            console.error('Survey submission failed. Response:', data);
            let errorMsg = data.message || 'Submission failed';
            if (data.errors) {
                errorMsg += '\nValidation errors: ' + JSON.stringify(data.errors);
            }
            throw new Error(errorMsg);
        }

    } catch (error) {
        console.error('Survey submission error:', error);
        console.error('Error stack:', error.stack);
        
        // Reset submitting flag
        isSubmitting = false;
        
        // Hide loading overlay
        hideLoadingOverlay();
        
        // Show error message
        showValidationMessage('There was an error submitting your survey. Please try again.\n\nError: ' + error.message, 'error');

        // Restore button state
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('loading');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoader = submitBtn.querySelector('.btn-loader');
            const btnIcon = submitBtn.querySelector('.btn-icon-check');
            if (btnText) btnText.style.display = 'inline-block';
            if (btnText) btnText.textContent = 'Submit';
            if (btnLoader) btnLoader.style.display = 'none';
            if (btnIcon) btnIcon.style.display = 'none';
        }
    }
}

// Override the submitSurvey function for Laravel
function submitSurvey(event) {
    submitSurveyLaravel(event);
}

// ====== NEW FUNCTIONS FOR STATIC HTML STRUCTURE ======

// Initialize survey on page load
document.addEventListener('DOMContentLoaded', function() {
    // Check if we're on the survey form page
    if (document.getElementById('surveyForm')) {
        initializeStaticSurvey();
    }
});

function initializeStaticSurvey() {
    // Ensure currentStep is valid (0 to totalSteps which is 7)
    if (currentStep > totalSteps) {
        currentStep = totalSteps;
    }
    if (currentStep < 0) {
        currentStep = 0;
    }
    
    // Show first step (step 0)
    showStep(currentStep);
    updateProgressBar();
    updateNavigationButtons();
    updateStepIndicators();
    initializeEventListeners();
    
    // Load saved progress (this will override currentStep if there's saved data)
    loadSavedProgress();
    
    // Ensure buttons are updated after loading progress
    setTimeout(() => {
        updateNavigationButtons();
    }, 150);

    // Set current year in footer
    const yearElement = document.getElementById('currentYear');
    if (yearElement) {
        yearElement.textContent = new Date().getFullYear();
    }
    
    console.log('Survey initialized. Total steps:', totalSteps, 'Current step:', currentStep);
}

// Initialize event listeners for enhanced functionality
function initializeEventListeners() {
    // Form input listeners with debounced auto-save
    const form = document.getElementById('surveyForm');
    if (form) {
        form.addEventListener('input', handleFormInput);
        form.addEventListener('change', handleFormInput);
        // Bind submit handler to avoid inline onsubmit (CSP-safe)
        form.addEventListener('submit', submitSurveyLaravel);
    }

    // Keyboard navigation
    document.addEventListener('keydown', handleKeyboardNavigation);

    // Mobile swipe gestures
    const surveyCard = document.querySelector('.survey-card');
    if (surveyCard) {
        surveyCard.addEventListener('touchstart', handleTouchStart, { passive: true });
        surveyCard.addEventListener('touchend', handleTouchEnd, { passive: true });
    }

    // Bind navigation buttons (CSP-safe)
    const prevBtn = document.getElementById('prevBtn');
    if (prevBtn) {
        prevBtn.addEventListener('click', previousStep);
    }
    const nextBtn = document.getElementById('nextBtn');
    if (nextBtn) {
        nextBtn.addEventListener('click', nextStep);
    }
    const submitBtn = document.getElementById('submitBtn');
    if (submitBtn && form) {
        // Ensure requestSubmit is used for proper form submission
        submitBtn.addEventListener('click', function () {
            form.requestSubmit();
        });
    }

    // Step indicator click handlers
    const stepItems = document.querySelectorAll('.step-item');
    stepItems.forEach((item, index) => {
        item.addEventListener('click', () => {
            if (index <= currentStep || checkSectionCompletion(index - 1)) {
                goToStep(index);
            }
        });
    });

    // Removed beforeunload warning - data is auto-saved, no need to warn user
    // isSubmitting flag will prevent warnings during submission

    // Retake prompt handler (moved from inline script)
    const retakeBtn = document.getElementById('retakeConfirm');
    const promptBox = document.getElementById('retakePrompt');
    if (retakeBtn && promptBox) {
        retakeBtn.addEventListener('click', function () {
            promptBox.style.display = 'none';
        });
    }
}

// Debounce utility
function debounce(func, wait) {
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(autoSaveTimeout);
            func(...args);
        };
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(later, wait);
    };
}

// Handle form input with debounced auto-save
const handleFormInput = debounce(function(event) {
    const input = event.target;
    
    // Save the value to surveyData immediately
    if (input.type === 'radio' && input.checked) {
        surveyData[input.name] = input.value;
    } else if (input.type === 'checkbox') {
        surveyData[input.name] = input.checked ? input.value : '';
    } else {
        surveyData[input.name] = input.value;
    }
    
    // Validate field immediately
    validateField(input);
    
    // Save progress
    saveProgress();
    
    // Show auto-save indicator
    showAutoSaveIndicator();
    
    // Update section completion
    updateStepIndicators();
}, 1000);

// Show auto-save indicator
function showAutoSaveIndicator() {
    const indicator = document.getElementById('autoSaveIndicator');
    if (indicator) {
        indicator.classList.add('show');
        
        setTimeout(() => {
            indicator.classList.remove('show');
        }, 2000);
    }
}

// Load saved progress
function loadSavedProgress() {
    try {
        const saved = loadFromLocalStorage('surveyProgress');
        if (saved && saved.data) {
            surveyData = saved.data || {};
            // Validate step is within valid range (0 to totalSteps, which is 7)
            // Step 7 (Additional Feedback/Comments) is the LAST step
            let stepToLoad = saved.step !== undefined ? saved.step : 0;
            if (stepToLoad > totalSteps) {
                console.warn('Saved step', stepToLoad, 'exceeds maximum', totalSteps, '. Resetting to last step.');
                stepToLoad = totalSteps;
            }
            if (stepToLoad < 0) {
                console.warn('Saved step is negative. Resetting to first step: 0');
                stepToLoad = 0;
            }
            currentStep = stepToLoad;
            console.log('Loading saved progress - Step:', currentStep, 'Total steps:', totalSteps);
            
            // Restore form values
            Object.keys(surveyData).forEach(key => {
                const input = document.querySelector(`[name="${key}"]`);
                if (input) {
                    if (input.type === 'radio') {
                        const radio = document.querySelector(`[name="${key}"][value="${surveyData[key]}"]`);
                        if (radio) radio.checked = true;
                    } else {
                        input.value = surveyData[key];
                    }
                }
            });
            
            // Show appropriate step (will be validated again in showStep)
            showStep(currentStep);
            updateProgressBar();
            updateNavigationButtons();
            updateStepIndicators();
            
            // Force update navigation buttons again to ensure correct state
            setTimeout(() => {
                updateNavigationButtons();
            }, 100);
        }
    } catch (e) {
        console.error('Error loading saved progress:', e);
    }
}

function showStep(step) {
    // Ensure we don't go beyond the last step (step 7 = Additional Feedback)
    if (step > totalSteps) {
        console.warn('Attempted to navigate beyond last step. Staying on step', totalSteps);
        step = totalSteps;
    }
    
    // Ensure step is not negative
    if (step < 0) {
        console.warn('Attempted to navigate to negative step. Staying on step 0');
        step = 0;
    }

    // Hide all steps with animation
    document.querySelectorAll('.survey-step').forEach(stepElement => {
        stepElement.classList.remove('active');
        stepElement.style.display = 'none';
    });

    // Load step content if lazy loaded
    const currentStepElement = document.querySelector(`.survey-step[data-step="${step}"]`);
    if (currentStepElement) {
        // Mark as loaded
        loadedSteps.add(step);
        currentStepElement.setAttribute('data-loaded', 'true');
        
        // Show step with animation
        currentStepElement.style.display = 'block';
        // Force reflow for animation
        currentStepElement.offsetHeight;
        currentStepElement.classList.add('active');
    } else {
        console.warn('Step element not found for step', step);
        // If step element doesn't exist and we're trying to go beyond, revert to last step
        if (step > totalSteps) {
            const lastStepElement = document.querySelector(`.survey-step[data-step="${totalSteps}"]`);
            if (lastStepElement) {
                lastStepElement.style.display = 'block';
                lastStepElement.classList.add('active');
                step = totalSteps;
            }
        }
    }

    currentStep = step;
    
    console.log('Showing step:', currentStep, 'Total steps:', totalSteps, 'Is last step:', currentStep >= totalSteps);

    // Scroll to top smoothly
    window.scrollTo({ top: 0, behavior: 'smooth' });
    
    // Update UI
    updateProgressBar();
    updateNavigationButtons();
    updateStepIndicators();
    
    // Enforce correct buttons visibility immediately on step change to avoid any flicker
    try {
        const nextBtn = document.getElementById('nextBtn');
        const submitBtn = document.getElementById('submitBtn');
        const isLastStep = currentStep === totalSteps;
        if (isLastStep) {
            if (nextBtn) {
                nextBtn.style.display = 'none';
                nextBtn.style.visibility = 'hidden';
                nextBtn.style.opacity = '0';
                nextBtn.disabled = true;
                nextBtn.setAttribute('aria-hidden', 'true');
                nextBtn.onclick = null;
            }
            if (submitBtn) {
                submitBtn.style.display = 'inline-flex';
                submitBtn.style.visibility = 'visible';
                submitBtn.style.opacity = '1';
                submitBtn.disabled = false;
                submitBtn.setAttribute('aria-hidden', 'false');
            }
        }
    } catch (e) {
        console.warn('Navigation buttons toggle error:', e);
    }

    // Force update navigation buttons after a brief delay to ensure DOM is ready
    setTimeout(() => {
        updateNavigationButtons();
    }, 50);
    
    // Focus first input in step
    setTimeout(() => {
        const firstInput = currentStepElement?.querySelector('input, textarea, select');
        if (firstInput && firstInput.type !== 'hidden') {
            firstInput.focus();
        }
    }, 300);
}

function nextStep() {
    // Step 7 (displayed as step 8 in UI - "Comments"/"Additional Feedback") is the LAST step
    // If already on last step, do nothing - Submit button should be visible, not Next
    if (currentStep >= totalSteps) {
        console.warn('Cannot go to next step - already on last step (step 7). Submit button should be visible.');
        updateNavigationButtons();
        return false;
    }

    // Validate current step before moving forward
    if (!validateCurrentStep()) {
        return false;
    }

    // Move to next step only if not on the last step
    if (currentStep < totalSteps) {
        const nextStepIndex = currentStep + 1;
        // Double check we're not going beyond last step
        if (nextStepIndex > totalSteps) {
            console.warn('Attempted to navigate beyond last step. Staying on current step.');
            updateNavigationButtons();
            return false;
        }
        showStep(nextStepIndex);
        updateProgressBar();
        updateNavigationButtons();
        return true;
    }
    
    return false;
}

function previousStep() {
    // Move to previous step (can go back to consent step 0)
    if (currentStep > 0) {
        showStep(currentStep - 1);
        updateProgressBar();
        updateNavigationButtons();
    }
}

// Enhanced validation with visual feedback
function validateCurrentStep() {
    const currentStepElement = document.querySelector(`.survey-step[data-step="${currentStep}"]`);
    if (!currentStepElement) return true;

    // Clear previous validation messages
    hideValidationMessage();
    clearValidationErrors();

    // Get all required inputs in current step
    const requiredInputs = currentStepElement.querySelectorAll('[required]');
    let isValid = true;
    const errors = [];

    requiredInputs.forEach(input => {
        if (input.type === 'checkbox') {
            if (!input.checked) {
                isValid = false;
                showFieldError(input, 'This field is required');
                const checkboxWrapper = input.closest('.consent-checkbox-wrapper');
                if (checkboxWrapper) {
                    checkboxWrapper.classList.add('validation-error');
                    setTimeout(() => {
                        checkboxWrapper.classList.remove('validation-error');
                    }, 3000);
                }
                errors.push('Consent is required');
            }
        } else if (input.type === 'radio') {
            const radioName = input.getAttribute('name');
            const checkedRadio = currentStepElement.querySelector(`input[name="${radioName}"]:checked`);
            if (!checkedRadio) {
                isValid = false;
                const questionGroup = input.closest('.question-group');
                if (questionGroup && !questionGroup.classList.contains('validation-error')) {
                    questionGroup.classList.add('validation-error');
                    errors.push(`Please answer: ${questionGroup.querySelector('.question-label')?.textContent?.trim() || 'this question'}`);
                    setTimeout(() => {
                        questionGroup.classList.remove('validation-error');
                    }, 3000);
                }
            }
        } else if (input.value.trim() === '') {
            isValid = false;
            showFieldError(input, 'This field is required');
            errors.push('Please fill in all required fields');
        } else {
            showFieldSuccess(input);
        }
    });

    if (!isValid) {
        showValidationMessage(errors.join('. '), 'error');
        // Scroll to first error
        const firstError = currentStepElement.querySelector('.validation-error, [style*="border-color: rgb(220, 38, 38)"]');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    return isValid;
}

// Validate individual field
function validateField(input) {
    if (input.hasAttribute('required')) {
        if (input.type === 'checkbox' && !input.checked) {
            showFieldError(input, '');
            return false;
        } else if (input.type === 'radio') {
            const radioName = input.getAttribute('name');
            const checkedRadio = document.querySelector(`input[name="${radioName}"]:checked`);
            if (!checkedRadio) {
                return false;
            }
        } else if (input.value.trim() === '') {
            showFieldError(input, '');
            return false;
        }
        showFieldSuccess(input);
        return true;
    }
    return true;
}

// Show field error
function showFieldError(input, message) {
    input.classList.add('validation-error');
    input.classList.remove('validation-success');
    
    const questionGroup = input.closest('.question-group');
    if (questionGroup) {
        questionGroup.classList.add('validation-error');
        questionGroup.classList.remove('validation-success');
    }
}

// Show field success
function showFieldSuccess(input) {
    input.classList.remove('validation-error');
    input.classList.add('validation-success');
    
    const questionGroup = input.closest('.question-group');
    if (questionGroup) {
        questionGroup.classList.remove('validation-error');
        questionGroup.classList.add('validation-success');
        
        setTimeout(() => {
            questionGroup.classList.remove('validation-success');
        }, 2000);
    }
}

// Clear validation errors
function clearValidationErrors() {
    document.querySelectorAll('.validation-error').forEach(el => {
        el.classList.remove('validation-error');
    });
}

// Show validation message
function showValidationMessage(message, type = 'error') {
    const container = document.getElementById('validationMessage');
    if (container) {
        container.className = `validation-message ${type}`;
        container.innerHTML = `
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                ${type === 'error' 
                    ? '<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>'
                    : '<path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>'}
            </svg>
            <span>${message}</span>
        `;
        container.style.display = 'flex';
        
        if (type === 'success') {
            setTimeout(() => {
                hideValidationMessage();
            }, 3000);
        }
    }
}

// Hide validation message
function hideValidationMessage() {
    const container = document.getElementById('validationMessage');
    if (container) {
        container.style.display = 'none';
    }
}

function updateProgressBar() {
    // Calculate progress: step 0 = 0%, step 7 (last step, Additional Feedback) = 100%
    // Total steps are 0-7 (8 steps), so we use ((currentStep + 1) / (totalSteps + 1)) * 100
    const progressPercentage = Math.round(((currentStep + 1) / (totalSteps + 1)) * 100);
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

    // Disable/enable previous button (disabled on step 0)
    if (prevBtn) {
        prevBtn.disabled = currentStep === 0;
    }

    // Step 7 (displayed as step 8 in UI - "Comments"/"Additional Feedback") is the LAST step
    // Show Submit button and hide Next button on step 7
    const isLastStep = currentStep >= totalSteps;
    
    console.log('Updating navigation buttons - Current step:', currentStep, 'Total steps:', totalSteps, 'Is last step:', isLastStep);
    
    if (isLastStep) {
        // On last step (step 7), show Submit button and hide Next button completely
        if (nextBtn) {
            nextBtn.style.display = 'none';
            nextBtn.style.visibility = 'hidden';
            nextBtn.style.opacity = '0';
            nextBtn.disabled = true;
            nextBtn.setAttribute('aria-hidden', 'true');
            // Remove onclick to prevent any clicks
            nextBtn.onclick = null;
        }
        if (submitBtn) {
            submitBtn.style.display = 'inline-flex';
            submitBtn.style.visibility = 'visible';
            submitBtn.style.opacity = '1';
            submitBtn.disabled = false;
            submitBtn.setAttribute('aria-hidden', 'false');
        }
        console.log('✓ On last step (step 7/8 - Comments). Submit button shown, Next button hidden.');
    } else {
        // On any other step (0-6), show Next button and hide Submit button
        if (nextBtn) {
            nextBtn.style.display = 'inline-flex';
            nextBtn.style.visibility = 'visible';
            nextBtn.style.opacity = '1';
            nextBtn.disabled = false;
            nextBtn.setAttribute('aria-hidden', 'false');
        }
        if (submitBtn) {
            submitBtn.style.display = 'none';
            submitBtn.style.visibility = 'hidden';
            submitBtn.style.opacity = '0';
            submitBtn.disabled = false;
            submitBtn.setAttribute('aria-hidden', 'true');
        }
    }
}

// Update step indicators
function updateStepIndicators() {
    const stepItems = document.querySelectorAll('.step-item');
    stepItems.forEach((item, index) => {
        item.classList.remove('active', 'completed');
        
        if (index === currentStep) {
            item.classList.add('active');
        } else if (index < currentStep || checkSectionCompletion(index)) {
            item.classList.add('completed');
        }
    });
}

// Check if section is completed
function checkSectionCompletion(step) {
    // If step is before current step, consider it completed
    if (step < currentStep) return true;
    
    const stepElement = document.querySelector(`.survey-step[data-step="${step}"]`);
    if (!stepElement) return false;
    
    // Get unique required field names (radio groups count as one)
    const requiredFields = new Set();
    const radioGroups = new Set();
    
    stepElement.querySelectorAll('[required]').forEach(input => {
        if (input.type === 'radio') {
            radioGroups.add(input.name);
        } else {
            requiredFields.add(input.name || input.id);
        }
    });
    
    // Check if all radio groups have a selected value
    let allRadioGroupsCompleted = true;
    radioGroups.forEach(name => {
        if (!stepElement.querySelector(`input[name="${name}"]:checked`)) {
            allRadioGroupsCompleted = false;
        }
    });
    
    // Check if all other required fields are filled
    let allFieldsCompleted = true;
    requiredFields.forEach(name => {
        const input = stepElement.querySelector(`[name="${name}"], #${name}`);
        if (input) {
            if (input.type === 'checkbox' && !input.checked) {
                allFieldsCompleted = false;
            } else if (input.value.trim() === '') {
                allFieldsCompleted = false;
            }
        }
    });
    
    return allRadioGroupsCompleted && allFieldsCompleted;
}

// Go to specific step
function goToStep(step) {
    if (step >= 0 && step <= totalSteps) {
        // Validate current step before moving
        if (step > currentStep && !validateCurrentStep()) {
            return;
        }
        showStep(step);
    }
}

// Keyboard navigation handler
function handleKeyboardNavigation(e) {
    // Don't interfere with typing in inputs
    if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') {
        // Arrow key navigation for radio buttons
        if (['ArrowLeft', 'ArrowRight'].includes(e.key)) {
            if (e.target.type === 'radio') {
                e.preventDefault();
                const radios = Array.from(
                    document.querySelectorAll(`input[name="${e.target.name}"]`)
                );
                const currentIndex = radios.indexOf(e.target);
                const nextIndex = e.key === 'ArrowRight' 
                    ? (currentIndex + 1) % radios.length
                    : (currentIndex - 1 + radios.length) % radios.length;
                radios[nextIndex].focus();
                radios[nextIndex].click();
            }
        }
        // Enter to submit if on last step and in textarea
        if (e.key === 'Enter' && e.target.tagName === 'TEXTAREA' && e.ctrlKey && currentStep === totalSteps) {
            e.preventDefault();
            const submitBtn = document.getElementById('submitBtn');
            if (submitBtn) {
                document.getElementById('surveyForm').requestSubmit();
            }
        }
        return;
    }
    
    // Global keyboard shortcuts
    if (e.key === 'ArrowLeft' && currentStep > 0) {
        e.preventDefault();
        previousStep();
    } else if (e.key === 'ArrowRight' && currentStep < totalSteps) {
        e.preventDefault();
        if (validateCurrentStep()) {
            nextStep();
        }
    } else if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
        if (currentStep < totalSteps) {
            e.preventDefault();
            if (validateCurrentStep()) {
                nextStep();
            }
        } else if (currentStep === totalSteps) {
            e.preventDefault();
            document.getElementById('surveyForm').requestSubmit();
        }
    }
}

// Touch gesture handlers for mobile swipe
function handleTouchStart(e) {
    touchStartX = e.changedTouches[0].screenX;
}

function handleTouchEnd(e) {
    touchEndX = e.changedTouches[0].screenX;
    handleSwipe();
}

function handleSwipe() {
    const swipeThreshold = 50;
    const diff = touchStartX - touchEndX;
    
    // Swipe right (go to previous step)
    if (diff < -swipeThreshold && currentStep > 0) {
        previousStep();
    }
    // Swipe left (go to next step)
    else if (diff > swipeThreshold && currentStep < totalSteps) {
        if (validateCurrentStep()) {
            nextStep();
        }
    }
}

// Handle before unload - DISABLED
// Removed the beforeunload warning dialog as requested
// Data is auto-saved, so users can safely navigate away
function handleBeforeUnload(e) {
    // Do nothing - allow navigation without warning
    // Data is saved automatically via auto-save feature
}

// Save progress to localStorage
function saveProgress() {
    // Collect all form data
    const form = document.getElementById('surveyForm');
    if (!form) return;
    
    const formData = new FormData(form);
    const data = {};
    
    // Collect all form values
    for (const [key, value] of formData.entries()) {
        data[key] = value;
    }
    
    // Also collect radio button values
    form.querySelectorAll('input[type="radio"]:checked').forEach(radio => {
        data[radio.name] = radio.value;
    });
    
    surveyData = data;
    
    // Save to localStorage
    try {
        saveToLocalStorage('surveyProgress', {
            step: currentStep,
            data: surveyData,
            timestamp: Date.now()
        });
    } catch (e) {
        console.error('Error saving progress:', e);
    }
}

// Loading overlay functions for better UX during submission
function showLoadingOverlay() {
    // Remove existing overlay if any
    const existingOverlay = document.getElementById('surveyLoadingOverlay');
    if (existingOverlay) {
        existingOverlay.remove();
    }

    // Create overlay
    const overlay = document.createElement('div');
    overlay.id = 'surveyLoadingOverlay';
    overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 10000;
        color: white;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
    `;

    // Create spinner
    const spinner = document.createElement('div');
    spinner.style.cssText = `
        width: 50px;
        height: 50px;
        border: 4px solid rgba(255, 255, 255, 0.3);
        border-top-color: white;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-bottom: 20px;
    `;

    // Add spinner animation (only if not already added)
    if (!document.getElementById('surveySpinnerStyle')) {
        const style = document.createElement('style');
        style.id = 'surveySpinnerStyle';
        style.textContent = `
            @keyframes spin {
                to { transform: rotate(360deg); }
            }
            @keyframes scaleIn {
                from { transform: scale(0); }
                to { transform: scale(1); }
            }
        `;
        document.head.appendChild(style);
    }

    // Create message
    const message = document.createElement('div');
    message.id = 'surveyLoadingMessage';
    message.textContent = 'Submitting your survey...';
    message.style.cssText = `
        font-size: 18px;
        font-weight: 500;
        margin-top: 10px;
    `;

    overlay.appendChild(spinner);
    overlay.appendChild(message);
    document.body.appendChild(overlay);

    // Prevent body scroll
    document.body.style.overflow = 'hidden';
}

function hideLoadingOverlay() {
    const overlay = document.getElementById('surveyLoadingOverlay');
    if (overlay) {
        overlay.remove();
    }
    document.body.style.overflow = '';
}

function updateLoadingOverlay(message, type = 'loading') {
    const overlay = document.getElementById('surveyLoadingOverlay');
    const messageEl = document.getElementById('surveyLoadingMessage');
    
    if (overlay && messageEl) {
        messageEl.textContent = message || 'Processing...';
        
        if (type === 'success') {
            overlay.style.background = 'rgba(34, 197, 94, 0.9)';
            // Remove spinner and add checkmark
            const spinner = overlay.querySelector('div[style*="border"]');
            if (spinner) {
                spinner.style.display = 'none';
            }
            
            // Add checkmark if not exists
            if (!overlay.querySelector('.checkmark')) {
                const checkmark = document.createElement('div');
                checkmark.className = 'checkmark';
                checkmark.innerHTML = '✓';
                checkmark.style.cssText = `
                    width: 50px;
                    height: 50px;
                    border-radius: 50%;
                    background: white;
                    color: #22c55e;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 30px;
                    font-weight: bold;
                    margin-bottom: 20px;
                    animation: scaleIn 0.3s ease;
                `;
                
                // Animation style already added above
                
                overlay.insertBefore(checkmark, messageEl);
            }
        }
    }
}
