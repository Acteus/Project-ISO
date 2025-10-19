// Dashboard functionality

let dashboardData = null;
let currentTab = 'trends';

// Initialize dashboard
document.addEventListener('DOMContentLoaded', function() {
    if (window.location.pathname.includes('dashboard.html')) {
        initializeDashboard();
    }
});

async function initializeDashboard() {
    try {
        await loadDashboardData();
        updateDashboard();
    } catch (error) {
        console.error('Dashboard initialization error:', error);
        showNotification('Failed to load dashboard data', 'error');
    }
}

async function loadDashboardData() {
    try {
        // For demo purposes, we'll use mock data
        // In a real application, this would fetch from submit_survey.php
        dashboardData = await getMockDashboardData();
        
        // Uncomment the following line when PHP backend is ready
        // dashboardData = await makeApiRequest('submit_survey.php', { method: 'GET' });
        
    } catch (error) {
        console.error('Error loading dashboard data:', error);
        // Use mock data as fallback
        dashboardData = await getMockDashboardData();
    }
}

async function getMockDashboardData() {
    // Simulate API delay
    await new Promise(resolve => setTimeout(resolve, 500));
    
    return {
        success: true,
        total_responses: 127,
        section_averages: {
            section_a_avg: 4.2,
            section_b_avg: 4.1,
            section_c_avg: 3.9,
            section_d_avg: 4.3,
            section_e_avg: 4.0,
            section_f_avg: 4.2
        },
        question_averages: {
            q1: 4.2, q2: 4.1, q3: 4.0, q4: 4.3, q5: 4.2, q6: 4.1,
            q7: 3.9, q8: 3.8, q9: 4.0, q10: 4.3, q11: 4.1, q12: 4.2,
            q13: 4.0, q14: 4.1, q15: 3.9, q16: 3.8, q17: 3.7, q18: 3.9,
            q19: 4.2, q20: 4.3, q21: 4.1
        },
        recent_responses: [],
        distribution: [
            { date: '2024-01-15', count: 8 },
            { date: '2024-01-14', count: 12 },
            { date: '2024-01-13', count: 6 },
            { date: '2024-01-12', count: 15 },
            { date: '2024-01-11', count: 9 }
        ]
    };
}

function updateDashboard() {
    if (!dashboardData) return;
    
    updateMetricCards();
    updateCharts();
}

function updateMetricCards() {
    // Update total responses
    const totalResponsesElement = document.querySelector('.metric-card .metric-value');
    if (totalResponsesElement && dashboardData.total_responses) {
        totalResponsesElement.textContent = dashboardData.total_responses;
    }
    
    // Calculate overall satisfaction
    if (dashboardData.section_averages) {
        const averages = Object.values(dashboardData.section_averages);
        const overallAverage = averages.reduce((sum, avg) => sum + avg, 0) / averages.length;
        const satisfaction = Math.round(overallAverage * 20); // Convert to percentage
        
        // Find and update satisfaction metric
        const metricCards = document.querySelectorAll('.metric-card');
        metricCards.forEach(card => {
            const title = card.querySelector('.metric-title');
            if (title && title.textContent.includes('Overall')) {
                const value = card.querySelector('.metric-value');
                if (value && !value.textContent.includes('%')) {
                    value.textContent = `${satisfaction}%`;
                }
            }
        });
    }
}

function updateCharts() {
    // Create all interactive line charts
    createTrendsChart();
    createSubjectsChart();
    createDistributionsChart();
    createDetailsChart();
}

function calculateOverallSatisfaction() {
    if (!dashboardData.section_averages) return 0;
    
    const averages = Object.values(dashboardData.section_averages);
    const overallAverage = averages.reduce((sum, avg) => sum + avg, 0) / averages.length;
    return Math.round(overallAverage * 20); // Convert to percentage
}

function getTopPerformingSection() {
    if (!dashboardData.section_averages) {
        return { name: 'Unknown', score: '0.0' };
    }
    
    const sections = {
        section_a_avg: 'Learner Needs',
        section_b_avg: 'Teaching Quality',
        section_c_avg: 'Assessments',
        section_d_avg: 'Support & Resources',
        section_e_avg: 'Environment',
        section_f_avg: 'Feedback'
    };
    
    let topSection = { name: 'Unknown', score: 0 };
    
    Object.entries(dashboardData.section_averages).forEach(([key, value]) => {
        if (value > topSection.score) {
            topSection = {
                name: sections[key] || 'Unknown',
                score: value.toFixed(1)
            };
        }
    });
    
    return topSection;
}

function getPeakResponseDay() {
    if (!dashboardData.distribution || dashboardData.distribution.length === 0) {
        return 'No data available';
    }
    
    const peakDay = dashboardData.distribution.reduce((max, day) => 
        day.count > max.count ? day : max
    );
    
    return new Date(peakDay.date).toLocaleDateString();
}

function switchTab(tabName) {
    // Update tab buttons
    const tabButtons = document.querySelectorAll('.tab-button');
    tabButtons.forEach(button => {
        button.classList.remove('active');
        if (button.textContent.toLowerCase() === tabName.toLowerCase()) {
            button.classList.add('active');
        }
    });
    
    // Update tab content
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(content => {
        content.classList.remove('active');
    });
    
    const activeTab = document.getElementById(tabName);
    if (activeTab) {
        activeTab.classList.add('active');
    }
    
    currentTab = tabName;
}

function updateAnalytics() {
    const yearFilter = document.getElementById('yearFilter');
    const gradeFilter = document.getElementById('gradeFilter');
    
    if (!yearFilter || !gradeFilter) return;
    
    const selectedYear = yearFilter.value;
    const selectedGrade = gradeFilter.value;
    
    // Show loading state
    const chartContainers = document.querySelectorAll('.interactive-chart');
    chartContainers.forEach(container => {
        container.style.opacity = '0.5';
    });
    
    // Simulate data loading
    setTimeout(() => {
        // Update charts with filtered data
        updateCharts();
        
        chartContainers.forEach(container => {
            container.style.opacity = '1';
        });
        
        // Show notification
        showNotification(
            `Analytics updated for ${selectedYear} academic year${selectedGrade !== 'all' ? ` - Grade ${selectedGrade}` : ''}`,
            'success'
        );
    }, 1000);
}

// Charts refresh functionality
function refreshCharts() {
    // Show loading state briefly
    const chartContainers = document.querySelectorAll('.interactive-chart');
    chartContainers.forEach(container => {
        container.style.opacity = '0.5';
    });
    
    // Refresh all charts
    setTimeout(() => {
        updateCharts();
        chartContainers.forEach(container => {
            container.style.opacity = '1';
        });
        showNotification('Charts refreshed with latest data', 'success');
    }, 500);
}

// Real-time data refresh
function startDataRefresh() {
    // Refresh data every 30 seconds
    setInterval(async () => {
        try {
            await loadDashboardData();
            updateDashboard();
        } catch (error) {
            console.error('Auto-refresh error:', error);
        }
    }, 30000);
}

// Interactive Chart Functions
function createTrendsChart() {
    const container = document.getElementById('trendsChart');
    if (!container) return;
    
    const chartHTML = `
        <div class="interactive-chart">
            <div class="chart-canvas">
                <svg width="100%" height="280" viewBox="0 0 600 280">
                    <!-- Grid lines -->
                    <defs>
                        <pattern id="grid1" width="60" height="28" patternUnits="userSpaceOnUse">
                            <path d="M 60 0 L 0 0 0 28" fill="none" stroke="#f3f4f6" stroke-width="1"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid1)" />
                    
                    <!-- Axis titles -->
                    <text x="300" y="270" text-anchor="middle" class="axis-title">Time Period (Months)</text>
                    <text x="15" y="140" text-anchor="middle" transform="rotate(-90 15 140)" class="axis-title">Response Rate (%)</text>
                    
                    <!-- Response trends line -->
                    <polyline points="60,180 120,160 180,170 240,140 300,130 360,120 420,110 480,100 540,90"
                        fill="none" stroke="#4338ca" stroke-width="3" stroke-linecap="round"/>
                    
                    <!-- Data points -->
                    <g class="data-points">
                        <circle cx="60" cy="180" r="4" fill="#4338ca" class="data-point" data-month="Jan" data-value="75" data-type="Response Rate"/>
                        <circle cx="120" cy="160" r="4" fill="#4338ca" class="data-point" data-month="Feb" data-value="82" data-type="Response Rate"/>
                        <circle cx="180" cy="170" r="4" fill="#4338ca" class="data-point" data-month="Mar" data-value="78" data-type="Response Rate"/>
                        <circle cx="240" cy="140" r="4" fill="#4338ca" class="data-point" data-month="Apr" data-value="88" data-type="Response Rate"/>
                        <circle cx="300" cy="130" r="4" fill="#4338ca" class="data-point" data-month="May" data-value="92" data-type="Response Rate"/>
                        <circle cx="360" cy="120" r="4" fill="#4338ca" class="data-point" data-month="Jun" data-value="95" data-type="Response Rate"/>
                        <circle cx="420" cy="110" r="4" fill="#4338ca" class="data-point" data-month="Jul" data-value="97" data-type="Response Rate"/>
                        <circle cx="480" cy="100" r="4" fill="#4338ca" class="data-point" data-month="Aug" data-value="99" data-type="Response Rate"/>
                        <circle cx="540" cy="90" r="4" fill="#4338ca" class="data-point" data-month="Sep" data-value="100" data-type="Response Rate"/>
                    </g>
                    
                    <!-- X-axis labels -->
                    <g class="axis-label" text-anchor="middle">
                        <text x="60" y="255">Jan</text>
                        <text x="120" y="255">Feb</text>
                        <text x="180" y="255">Mar</text>
                        <text x="240" y="255">Apr</text>
                        <text x="300" y="255">May</text>
                        <text x="360" y="255">Jun</text>
                        <text x="420" y="255">Jul</text>
                        <text x="480" y="255">Aug</text>
                        <text x="540" y="255">Sep</text>
                    </g>
                    
                    <!-- Y-axis labels -->
                    <g class="axis-label" text-anchor="end">
                        <text x="50" y="220">60%</text>
                        <text x="50" y="180">75%</text>
                        <text x="50" y="140">90%</text>
                        <text x="50" y="100">100%</text>
                    </g>
                </svg>
            </div>
            <div id="tooltip1" class="chart-tooltip"></div>
        </div>
    `;
    
    container.innerHTML = chartHTML;
    addChartInteractivity(container, 'tooltip1');
}

function createSubjectsChart() {
    const container = document.getElementById('subjectsChart');
    if (!container) return;
    
    const chartHTML = `
        <div class="interactive-chart">
            <div class="chart-canvas">
                <svg width="100%" height="280" viewBox="0 0 600 280">
                    <!-- Grid lines -->
                    <defs>
                        <pattern id="grid2" width="60" height="28" patternUnits="userSpaceOnUse">
                            <path d="M 60 0 L 0 0 0 28" fill="none" stroke="#f3f4f6" stroke-width="1"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid2)" />
                    
                    <!-- Axis titles -->
                    <text x="300" y="270" text-anchor="middle" class="axis-title">Survey Sections</text>
                    <text x="15" y="140" text-anchor="middle" transform="rotate(-90 15 140)" class="axis-title">Average Score (1-5)</text>
                    
                    <!-- Subject performance line -->
                    <polyline points="80,120 140,130 200,140 260,110 320,125 380,115"
                        fill="none" stroke="#16a34a" stroke-width="3" stroke-linecap="round"/>
                    
                    <!-- Data points -->
                    <g class="data-points">
                        <circle cx="80" cy="120" r="4" fill="#16a34a" class="data-point" data-month="Learner Needs" data-value="4.2" data-type="Average Score"/>
                        <circle cx="140" cy="130" r="4" fill="#16a34a" class="data-point" data-month="Teaching Quality" data-value="4.1" data-type="Average Score"/>
                        <circle cx="200" cy="140" r="4" fill="#16a34a" class="data-point" data-month="Assessments" data-value="3.9" data-type="Average Score"/>
                        <circle cx="260" cy="110" r="4" fill="#16a34a" class="data-point" data-month="Support & Resources" data-value="4.3" data-type="Average Score"/>
                        <circle cx="320" cy="125" r="4" fill="#16a34a" class="data-point" data-month="Environment" data-value="4.0" data-type="Average Score"/>
                        <circle cx="380" cy="115" r="4" fill="#16a34a" class="data-point" data-month="Feedback" data-value="4.2" data-type="Average Score"/>
                    </g>
                    
                    <!-- X-axis labels -->
                    <g class="axis-label" text-anchor="middle">
                        <text x="80" y="255">Needs</text>
                        <text x="140" y="255">Quality</text>
                        <text x="200" y="255">Assess</text>
                        <text x="260" y="255">Support</text>
                        <text x="320" y="255">Environ</text>
                        <text x="380" y="255">Feedback</text>
                    </g>
                    
                    <!-- Y-axis labels -->
                    <g class="axis-label" text-anchor="end">
                        <text x="50" y="200">3.0</text>
                        <text x="50" y="160">3.5</text>
                        <text x="50" y="120">4.0</text>
                        <text x="50" y="80">4.5</text>
                    </g>
                </svg>
            </div>
            <div id="tooltip2" class="chart-tooltip"></div>
        </div>
    `;
    
    container.innerHTML = chartHTML;
    addChartInteractivity(container, 'tooltip2');
}

function createDistributionsChart() {
    const container = document.getElementById('distributionsChart');
    if (!container) return;
    
    const chartHTML = `
        <div class="interactive-chart">
            <div class="chart-canvas">
                <svg width="100%" height="280" viewBox="0 0 600 280">
                    <!-- Grid lines -->
                    <defs>
                        <pattern id="grid3" width="60" height="28" patternUnits="userSpaceOnUse">
                            <path d="M 60 0 L 0 0 0 28" fill="none" stroke="#f3f4f6" stroke-width="1"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid3)" />
                    
                    <!-- Axis titles -->
                    <text x="300" y="270" text-anchor="middle" class="axis-title">Grade Levels</text>
                    <text x="15" y="140" text-anchor="middle" transform="rotate(-90 15 140)" class="axis-title">Student Count</text>
                    
                    <!-- Grade distribution line -->
                    <polyline points="100,160 160,140 220,130 280,150 340,170 400,180 460,190"
                        fill="none" stroke="#dc2626" stroke-width="3" stroke-linecap="round"/>
                    
                    <!-- Data points -->
                    <g class="data-points">
                        <circle cx="100" cy="160" r="4" fill="#dc2626" class="data-point" data-month="85-89" data-value="25" data-type="Students"/>
                        <circle cx="160" cy="140" r="4" fill="#dc2626" class="data-point" data-month="90-94" data-value="35" data-type="Students"/>
                        <circle cx="220" cy="130" r="4" fill="#dc2626" class="data-point" data-month="95-99" data-value="42" data-type="Students"/>
                        <circle cx="280" cy="150" r="4" fill="#dc2626" class="data-point" data-month="100-104" data-value="28" data-type="Students"/>
                        <circle cx="340" cy="170" r="4" fill="#dc2626" class="data-point" data-month="105-109" data-value="18" data-type="Students"/>
                        <circle cx="400" cy="180" r="4" fill="#dc2626" class="data-point" data-month="110-114" data-value="12" data-type="Students"/>
                        <circle cx="460" cy="190" r="4" fill="#dc2626" class="data-point" data-month="115+" data-value="8" data-type="Students"/>
                    </g>
                    
                    <!-- X-axis labels -->
                    <g class="axis-label" text-anchor="middle">
                        <text x="100" y="255">85-89</text>
                        <text x="160" y="255">90-94</text>
                        <text x="220" y="255">95-99</text>
                        <text x="280" y="255">100-104</text>
                        <text x="340" y="255">105-109</text>
                        <text x="400" y="255">110-114</text>
                        <text x="460" y="255">115+</text>
                    </g>
                    
                    <!-- Y-axis labels -->
                    <g class="axis-label" text-anchor="end">
                        <text x="50" y="200">10</text>
                        <text x="50" y="160">25</text>
                        <text x="50" y="120">40</text>
                        <text x="50" y="80">50</text>
                    </g>
                </svg>
            </div>
            <div id="tooltip3" class="chart-tooltip"></div>
        </div>
    `;
    
    container.innerHTML = chartHTML;
    addChartInteractivity(container, 'tooltip3');
}

function createDetailsChart() {
    const container = document.getElementById('detailsChart');
    if (!container) return;
    
    const chartHTML = `
        <div class="interactive-chart">
            <div class="chart-canvas">
                <svg width="100%" height="280" viewBox="0 0 600 280">
                    <!-- Grid lines -->
                    <defs>
                        <pattern id="grid4" width="60" height="28" patternUnits="userSpaceOnUse">
                            <path d="M 60 0 L 0 0 0 28" fill="none" stroke="#f3f4f6" stroke-width="1"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid4)" />
                    
                    <!-- Axis titles -->
                    <text x="300" y="270" text-anchor="middle" class="axis-title">Response Timeline (Days)</text>
                    <text x="15" y="140" text-anchor="middle" transform="rotate(-90 15 140)" class="axis-title">Satisfaction Level (%)</text>
                    
                    <!-- Satisfaction details line -->
                    <polyline points="70,190 130,180 190,160 250,150 310,140 370,130 430,120 490,110 550,100"
                        fill="none" stroke="#f59e0b" stroke-width="3" stroke-linecap="round"/>
                    
                    <!-- Data points -->
                    <g class="data-points">
                        <circle cx="70" cy="190" r="4" fill="#f59e0b" class="data-point" data-month="Day 1" data-value="68" data-type="Satisfaction"/>
                        <circle cx="130" cy="180" r="4" fill="#f59e0b" class="data-point" data-month="Day 2" data-value="72" data-type="Satisfaction"/>
                        <circle cx="190" cy="160" r="4" fill="#f59e0b" class="data-point" data-month="Day 3" data-value="80" data-type="Satisfaction"/>
                        <circle cx="250" cy="150" r="4" fill="#f59e0b" class="data-point" data-month="Day 4" data-value="84" data-type="Satisfaction"/>
                        <circle cx="310" cy="140" r="4" fill="#f59e0b" class="data-point" data-month="Day 5" data-value="88" data-type="Satisfaction"/>
                        <circle cx="370" cy="130" r="4" fill="#f59e0b" class="data-point" data-month="Day 6" data-value="92" data-type="Satisfaction"/>
                        <circle cx="430" cy="120" r="4" fill="#f59e0b" class="data-point" data-month="Day 7" data-value="95" data-type="Satisfaction"/>
                        <circle cx="490" cy="110" r="4" fill="#f59e0b" class="data-point" data-month="Day 8" data-value="97" data-type="Satisfaction"/>
                        <circle cx="550" cy="100" r="4" fill="#f59e0b" class="data-point" data-month="Day 9" data-value="99" data-type="Satisfaction"/>
                    </g>
                    
                    <!-- X-axis labels -->
                    <g class="axis-label" text-anchor="middle">
                        <text x="70" y="255">1</text>
                        <text x="130" y="255">2</text>
                        <text x="190" y="255">3</text>
                        <text x="250" y="255">4</text>
                        <text x="310" y="255">5</text>
                        <text x="370" y="255">6</text>
                        <text x="430" y="255">7</text>
                        <text x="490" y="255">8</text>
                        <text x="550" y="255">9</text>
                    </g>
                    
                    <!-- Y-axis labels -->
                    <g class="axis-label" text-anchor="end">
                        <text x="50" y="200">60%</text>
                        <text x="50" y="160">75%</text>
                        <text x="50" y="120">90%</text>
                        <text x="50" y="80">100%</text>
                    </g>
                </svg>
            </div>
            <div id="tooltip4" class="chart-tooltip"></div>
        </div>
    `;
    
    container.innerHTML = chartHTML;
    addChartInteractivity(container, 'tooltip4');
}

function addChartInteractivity(container, tooltipId) {
    const dataPoints = container.querySelectorAll('.data-point');
    const tooltip = document.getElementById(tooltipId);
    
    dataPoints.forEach(point => {
        point.addEventListener('mouseenter', function(e) {
            const month = this.getAttribute('data-month');
            const value = this.getAttribute('data-value');
            const type = this.getAttribute('data-type');
            
            tooltip.innerHTML = `
                <strong>${month}</strong><br>
                ${type}: ${value}${type.includes('Score') ? '' : '%'}
            `;
            tooltip.style.display = 'block';
            tooltip.style.left = (e.pageX + 10) + 'px';
            tooltip.style.top = (e.pageY - 10) + 'px';
            
            // Highlight the point
            this.setAttribute('r', '6');
            this.style.filter = 'drop-shadow(0 0 4px rgba(0,0,0,0.3))';
        });
        
        point.addEventListener('mouseleave', function() {
            tooltip.style.display = 'none';
            this.setAttribute('r', '4');
            this.style.filter = 'none';
        });
        
        point.addEventListener('mousemove', function(e) {
            tooltip.style.left = (e.pageX + 10) + 'px';
            tooltip.style.top = (e.pageY - 10) + 'px';
        });
    });
}

// Initialize auto-refresh when dashboard loads
document.addEventListener('DOMContentLoaded', function() {
    if (window.location.pathname.includes('dashboard.html')) {
        setTimeout(startDataRefresh, 5000); // Start after 5 seconds
        // Charts will be created automatically via updateCharts() in initializeDashboard()
    }
});