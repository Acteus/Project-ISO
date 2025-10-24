// Survey functionality for Laravel with static HTML structure
let currentStep = 1;
let totalSteps = 8;
let surveyData = {};

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

        // Consent and privacy
        consent_given: true, // Assume consent given when survey is submitted

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

// Prevent accidental navigation away
window.addEventListener('beforeunload', function(event) {
    if (Object.keys(surveyData).length > 0) {
        event.preventDefault();
        event.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
        return event.returnValue;
    }
});

// Laravel-specific functions
async function submitSurveyLaravel(event) {
    event.preventDefault();

    if (!validateCurrentSection()) {
        return;
    }

    const submitBtn = document.getElementById('submitBtn');
    showLoading(submitBtn);

    try {
        // Prepare data for submission - collect all form data
        const submissionData = { ...surveyData };

        // Get form fields
        const studentIdField = document.querySelector('input[name="student_id"]');
        const gradeLevelField = document.querySelector('input[name="grade_level"]');
        const genderField = document.querySelector('select[name="gender"]');
        const demographicsYearLevelField = document.querySelector('select[name="demographics_year_level"]');
        const additionalFeedbackField = document.querySelector('textarea[name="additional_feedback"]');

        // Determine grade level from either hidden field or demographics dropdown
        let gradeLevel = 11;
        if (gradeLevelField && gradeLevelField.value) {
            gradeLevel = parseInt(gradeLevelField.value);
        } else if (demographicsYearLevelField && demographicsYearLevelField.value) {
            // Extract number from "Grade 11" or "Grade 12"
            const match = demographicsYearLevelField.value.match(/\d+/);
            gradeLevel = match ? parseInt(match[0]) : 11;
        }

        // Map to Laravel API format with actual form data
        const laravelData = {
            // Student information from Laravel auth
            student_id: studentIdField ? studentIdField.value : '',
            track: 'CSS', // CSS strand
            grade_level: gradeLevel,
            academic_year: new Date().getFullYear().toString(),
            semester: getCurrentSemester(),

            // Map survey questions to ISO 21001 fields
            // Learner Needs & Expectations (q1-q3)
            curriculum_relevance_rating: parseInt(submissionData.q1) || 1,
            learning_pace_appropriateness: parseInt(submissionData.q2) || 1,
            individual_support_availability: parseInt(submissionData.q3) || 1,
            learning_style_accommodation: parseInt(submissionData.q1) || 1,

            // Teaching & Learning Quality (q4-q6)
            teaching_quality_rating: parseInt(submissionData.q4) || 1,
            learning_environment_rating: parseInt(submissionData.q5) || 1,
            peer_interaction_satisfaction: parseInt(submissionData.q6) || 1,
            extracurricular_satisfaction: parseInt(submissionData.q4) || 1,

            // Assessments & Outcomes (q7-q9)
            academic_progress_rating: parseInt(submissionData.q7) || 1,
            skill_development_rating: parseInt(submissionData.q8) || 1,
            critical_thinking_improvement: parseInt(submissionData.q9) || 1,
            problem_solving_confidence: parseInt(submissionData.q7) || 1,

            // Support & Resources (q10-q12)
            physical_safety_rating: parseInt(submissionData.q10) || 1,
            psychological_safety_rating: parseInt(submissionData.q11) || 1,
            bullying_prevention_effectiveness: parseInt(submissionData.q12) || 1,
            emergency_preparedness_rating: parseInt(submissionData.q10) || 1,

            // Environment & Inclusivity (q13-q15)
            mental_health_support_rating: parseInt(submissionData.q13) || 1,
            stress_management_support: parseInt(submissionData.q14) || 1,
            physical_health_support: parseInt(submissionData.q15) || 1,
            overall_wellbeing_rating: parseInt(submissionData.q13) || 1,

            // Overall Satisfaction (q19-q21)
            overall_satisfaction: parseInt(submissionData.q19) || 1,

            // Map open feedback to appropriate fields
            positive_aspects: extractPositiveAspects(additionalFeedbackField ? additionalFeedbackField.value : ''),
            improvement_suggestions: extractImprovementSuggestions(additionalFeedbackField ? additionalFeedbackField.value : ''),
            additional_comments: additionalFeedbackField ? additionalFeedbackField.value : '',

            // Demographics
            gender: genderField ? genderField.value : 'Prefer not to say',

            // Consent and privacy
            consent_given: true,

            // Indirect metrics (optional)
            attendance_rate: null,
            grade_average: null,
            participation_score: null,
            extracurricular_hours: null,
            counseling_sessions: null
        };

        console.log('Submitting survey data:', laravelData);

        // Submit to Laravel API backend
        const response = await fetch('/api/survey/submit', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify(laravelData)
        });

        const data = await response.json();
        console.log('Response:', response.status, data);

        if (response.ok && data.message) {
            // Clear saved progress
            removeFromLocalStorage('surveyProgress');

            // Show success message and redirect
            alert(data.message);
            window.location.href = '/thank-you';
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
    // Show first step
    showStep(1);
    updateProgressBar();
    updateNavigationButtons();

    // Set current year in footer
    const yearElement = document.getElementById('currentYear');
    if (yearElement) {
        yearElement.textContent = new Date().getFullYear();
    }
}

function showStep(step) {
    // Hide all steps
    document.querySelectorAll('.survey-step').forEach(stepElement => {
        stepElement.style.display = 'none';
    });

    // Show current step
    const currentStepElement = document.querySelector(`.survey-step[data-step="${step}"]`);
    if (currentStepElement) {
        currentStepElement.style.display = 'block';
    }

    currentStep = step;

    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function nextStep() {
    // Validate current step
    if (!validateCurrentStep()) {
        return;
    }

    // Move to next step
    if (currentStep < totalSteps) {
        showStep(currentStep + 1);
        updateProgressBar();
        updateNavigationButtons();
    }
}

function previousStep() {
    // Move to previous step
    if (currentStep > 1) {
        showStep(currentStep - 1);
        updateProgressBar();
        updateNavigationButtons();
    }
}

function validateCurrentStep() {
    const currentStepElement = document.querySelector(`.survey-step[data-step="${currentStep}"]`);
    if (!currentStepElement) return true;

    // Get all required inputs in current step
    const requiredInputs = currentStepElement.querySelectorAll('[required]');
    let isValid = true;

    requiredInputs.forEach(input => {
        if (input.type === 'radio') {
            // Check if at least one radio button with this name is checked
            const radioName = input.getAttribute('name');
            const checkedRadio = currentStepElement.querySelector(`input[name="${radioName}"]:checked`);
            if (!checkedRadio) {
                isValid = false;
                // Highlight the question group
                const questionGroup = input.closest('.question-group');
                if (questionGroup) {
                    questionGroup.style.border = '2px solid #dc2626';
                    setTimeout(() => {
                        questionGroup.style.border = '';
                    }, 2000);
                }
            }
        } else if (input.value.trim() === '') {
            isValid = false;
            input.style.borderColor = '#dc2626';
            setTimeout(() => {
                input.style.borderColor = '';
            }, 2000);
        }
    });

    if (!isValid) {
        alert('Please answer all required questions before proceeding.');
    }

    return isValid;
}

function updateProgressBar() {
    const progressPercentage = Math.round((currentStep / totalSteps) * 100);
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

    // Disable/enable previous button
    if (prevBtn) {
        prevBtn.disabled = currentStep === 1;
    }

    // Show/hide next and submit buttons
    if (currentStep === totalSteps) {
        if (nextBtn) nextBtn.style.display = 'none';
        if (submitBtn) submitBtn.style.display = 'inline-flex';
    } else {
        if (nextBtn) nextBtn.style.display = 'inline-flex';
        if (submitBtn) submitBtn.style.display = 'none';
    }
}
