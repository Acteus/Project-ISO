// Survey functionality

let currentStep = 0;
let surveyData = {};

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
        id: 'demographics',
        title: 'Demographics & Open-ended Questions',
        questions: [
            {
                id: 'year_level',
                text: 'What is your current year level?',
                type: 'select',
                options: ['Grade 11', 'Grade 12']
            },
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
            if (!input || !input.value) {
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
        
        // Submit to PHP backend
        const response = await makeApiRequest('submit_survey.php', {
            method: 'POST',
            body: JSON.stringify(submissionData)
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
    if (window.location.pathname.includes('survey.html') && Object.keys(surveyData).length > 0) {
        event.preventDefault();
        event.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
        return event.returnValue;
    }
});