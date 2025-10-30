<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISO 21001 Analytics Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, rgba(66, 133, 244, 1), rgba(255, 215, 0, 1));
            min-height: 100vh;
        }

        .survey-main {
            background-image: none !important;
            padding: 20px;
        }

        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .dashboard-header {
            background: white;
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            text-align: center;
        }

        .dashboard-header h1 {
            font-size: 36px;
            color: #333;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .dashboard-header p {
            color: #666;
            font-size: 18px;
        }

        .back-button {
            display: inline-block;
            padding: 12px 30px;
            background: white;
            color: black;
            text-decoration: none;
            border-radius: 25px;
            margin-bottom: 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border: 2px solid white;
        }

        .back-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            color: black;
            background: rgba(255, 255, 255, 0.9);
        }

        /* Filters */
        .filters-section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .filters-section h3 {
            margin-bottom: 20px;
            color: #333;
            font-size: 22px;
            border-bottom: 3px solid rgba(66,133,244,1);
            padding-bottom: 10px;
        }

        /* Quick Date Filters */
        .quick-filters {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .quick-filter-btn {
            padding: 8px 16px;
            border: 2px solid rgba(66,133,244,1);
            background: white;
            color: rgba(66,133,244,1);
            border-radius: 20px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 13px;
        }

        .quick-filter-btn:hover,
        .quick-filter-btn.active {
            background: rgba(66,133,244,1);
            color: white;
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }

        .filter-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #555;
            margin-bottom: 5px;
        }

        .filter-group select,
        .filter-group input {
            width: 100%;
            padding: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .filter-group select:focus,
        .filter-group input:focus {
            outline: none;
            border-color: rgba(66,133,244,1);
        }

        .filter-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 14px;
        }

        .btn-primary {
            background: rgba(66,133,244,1);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(66,133,244,0.4);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108,117,125,0.4);
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, rgba(66,133,244,1), rgba(255,215,0,1));
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .stat-value {
            font-size: 42px;
            font-weight: 700;
            color: rgba(66,133,244,1);
            margin-bottom: 10px;
        }

        .stat-label {
            font-size: 14px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        /* Charts */
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
            margin-bottom: 25px;
        }

        @media (max-width: 1024px) {
            .charts-grid {
                grid-template-columns: 1fr;
            }
        }

        .chart-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .chart-card h3 {
            margin-bottom: 20px;
            color: #333;
            font-size: 20px;
            border-bottom: 3px solid rgba(66,133,244,1);
            padding-bottom: 15px;
        }

        .chart-wrapper {
            position: relative;
            height: 350px;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        /* Compliance Section */
        .compliance-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
        }

        .compliance-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .compliance-score {
            font-size: 48px;
            font-weight: 700;
        }

        .risk-low { color: #28a745; }
        .risk-medium { color: #ffc107; }
        .risk-high { color: #dc3545; }

        .recommendations {
            margin-top: 20px;
        }

        .recommendations h4 {
            font-size: 16px;
            margin-bottom: 10px;
            color: #555;
        }

        .recommendations ul {
            list-style: none;
            padding: 0;
        }

        .recommendations li {
            padding: 8px 0 8px 25px;
            position: relative;
            color: #666;
        }

        .recommendations li:before {
            content: "→";
            position: absolute;
            left: 0;
            color: rgba(66,133,244,1);
            font-weight: bold;
        }

        /* Loading State */
        .loading {
            display: none;
            text-align: center;
            padding: 40px;
        }

        .loading.active {
            display: block;
        }

        .spinner {
            width: 50px;
            height: 50px;
            margin: 0 auto 20px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid rgba(66,133,244,1);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* No Data Message */
        .no-data {
            background: white;
            border-radius: 15px;
            padding: 60px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .no-data h2 {
            color: #666;
            margin-bottom: 15px;
        }

        .no-data p {
            color: #999;
        }
    </style>
</head>
<body>
    <main class="survey-main">
        <div class="dashboard-container">
            <a href="{{ route('admin.dashboard') }}" class="back-button">← Back to Dashboard</a>

            <div class="dashboard-header">
                <h1>ISO 21001 Quality Education Analytics</h1>
                <p>Comprehensive metrics and insights for Computer System Servicing (CSS) program</p>
                <div id="date-range-display" style="margin-top: 15px; padding: 12px 24px; background: rgba(66, 133, 244, 0.1); border-radius: 25px; display: inline-block; font-weight: 600; color: #333; font-size: 15px;">
                    <span id="date-range-text">Viewing All Data</span>
                </div>
            </div>

        <!-- Filters -->
        <div class="filters-section">
            <h3>Filter Data</h3>

            <!-- Quick Date Filters -->
            <div class="quick-filters">
                <button type="button" class="quick-filter-btn" data-filter="today">Today</button>
                <button type="button" class="quick-filter-btn" data-filter="week">This Week</button>
                <button type="button" class="quick-filter-btn" data-filter="month">This Month</button>
                <button type="button" class="quick-filter-btn" data-filter="all">All Time</button>
            </div>

            <form id="filters-form">
                <div class="filters-grid">
                    <div class="filter-group">
                        <label>Week</label>
                        <select name="week" id="week-filter">
                            <option value="">Select Week</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Grade Level</label>
                        <select name="grade_level" id="grade_level">
                            <option value="">All Grades</option>
                            <option value="11">Grade 11</option>
                            <option value="12">Grade 12</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Semester</label>
                        <select name="semester" id="semester">
                            <option value="">All Semesters</option>
                            <option value="1st">1st Semester</option>
                            <option value="2nd">2nd Semester</option>
                        </select>
                    </div>
                </div>
                <div class="filter-actions">
                    <button type="button" class="btn btn-secondary" id="clear-filters">Clear Filters</button>
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div>
                <!-- Hidden date inputs for internal use -->
                <input type="hidden" name="date_from" id="date_from">
                <input type="hidden" name="date_to" id="date_to">
            </form>
        </div>

        <!-- Loading State -->
        <div class="loading" id="loading">
            <div class="spinner"></div>
            <p>Loading analytics data...</p>
        </div>

        <!-- Main Content -->
        <div id="dashboard-content" style="display: none;">
            <!-- Stats Cards -->
            <div class="stats-grid" id="stats-grid"></div>

            <!-- Compliance Assessment -->
            <div class="compliance-card" id="compliance-section"></div>

            <!-- Charts -->
            <div class="charts-grid">
                <!-- ISO Indices Radar -->
                <div class="chart-card">
                    <h3>ISO 21001 Performance Profile (0–5)</h3>
                    <div class="chart-wrapper">
                        <canvas id="radarChart"></canvas>
                    </div>
                </div>

                <!-- Grade Distribution -->
                <div class="chart-card">
                    <h3>Responses by Grade Level (counts & %)</h3>
                    <div class="chart-wrapper">
                        <canvas id="gradeChart"></canvas>
                    </div>
                </div>

                <!-- Time Series -->
                <div class="chart-card full-width">
                    <h3>Average Satisfaction Over Time (0–5)</h3>
                    <div class="chart-wrapper">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>

                <!-- ISO Indices Bar Chart -->
                <div class="chart-card full-width">
                    <h3>ISO 21001 Dimensions Comparison (0–5)</h3>
                    <div class="chart-wrapper">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- No Data Message -->
        <div class="no-data" id="no-data" style="display: none;">
            <h2>No Data Available</h2>
            <p>No survey responses found. Data will appear here once students submit their feedback.</p>
        </div>
        </div>
    </main>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        let charts = {};

        // Load dashboard data on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Populate week filter dropdown
            populateWeekFilter();

            // Clear filters on initial load to show all data
            document.getElementById('week-filter').value = '';
            document.getElementById('date_from').value = '';
            document.getElementById('date_to').value = '';

            // Update date range display
            updateDateRangeDisplay();

            // Setup filter handlers
            setupFilterHandlers();

            loadDashboard();
        });

        // Handle form submission
        document.getElementById('filters-form').addEventListener('submit', function(e) {
            e.preventDefault();
            loadDashboard();
        });

        // Clear filters
        document.getElementById('clear-filters').addEventListener('click', function() {
            document.getElementById('filters-form').reset();
            document.getElementById('week-filter').value = '';
            document.getElementById('date_from').value = '';
            document.getElementById('date_to').value = '';
            updateDateRangeDisplay();
            loadDashboard();
        });

        // Main function to load all dashboard data
        async function loadDashboard() {
            showLoading();

            try {
                // Get filter values
                const formData = new FormData(document.getElementById('filters-form'));
                const params = new URLSearchParams();

                for (const [key, value] of formData) {
                    if (value) params.append(key, value);
                }

                // Single API call for all data
                const response = await fetch('/api/analytics/summary?' + params.toString(), {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    credentials: 'same-origin'
                });

                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('API Error:', errorText);
                    throw new Error('Failed to load analytics: ' + response.status);
                }

                const result = await response.json();
                console.log('Analytics data:', result);

                if (!result.success) {
                    throw new Error(result.message || 'Failed to load analytics');
                }

                const data = result.data;

                if (!data.has_data) {
                    showNoData();
                    return;
                }

                // Render all dashboard components
                renderStats(data);
                renderCompliance(data.compliance);
                renderRadarChart(data.iso_indices);
                // Pass total_responses so grade chart can compute percentages correctly
                renderGradeChart(data.distribution.by_grade, data.total_responses);
                renderBarChart(data.iso_indices);

                // Load time series separately
                await loadTimeSeriesChart(params.toString());

                showContent();
            } catch (error) {
                console.error('Error loading dashboard:', error);
                alert('Failed to load analytics data. Please try again.');
                hideLoading();
            }
        }

        // Render stats cards
        function renderStats(data) {
            // Format satisfaction: if it's on 0-5 scale show with /5, otherwise show value as-is
            let satisfactionDisplay = data.overall && data.overall.satisfaction != null ? data.overall.satisfaction : '-';
            if (typeof satisfactionDisplay === 'number') {
                if (satisfactionDisplay <= 5) satisfactionDisplay = `${satisfactionDisplay} / 5`;
                else satisfactionDisplay = `${satisfactionDisplay}%`;
            }

            const statsHtml = `
                <div class="stat-card">
                    <div class="stat-value">${data.total_responses}</div>
                    <div class="stat-label">Total Responses</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">${satisfactionDisplay}</div>
                    <div class="stat-label">Avg Satisfaction</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">${data.overall.compliance_score}</div>
                    <div class="stat-label">Compliance Score</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">${data.iso_indices.learner_needs}</div>
                    <div class="stat-label">Learner Needs</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">${data.iso_indices.success}</div>
                    <div class="stat-label">Success Index</div>
                </div>
            `;
            document.getElementById('stats-grid').innerHTML = statsHtml;
        }

        // Render compliance section
        function renderCompliance(compliance) {
            const riskClass = 'risk-' + compliance.risk_level.toLowerCase();
            const recsHtml = compliance.recommendations.map(rec => `<li>${rec}</li>`).join('');

            // Build thresholds display if available
            let thresholdsHtml = '';
            if (compliance.thresholds) {
                thresholdsHtml = `
                    <div style="margin-top: 20px; padding: 20px; background: rgba(230, 230, 230, 0.3); border-radius: 10px; border-left: 4px solid rgba(66, 133, 244, 1);">
                        <h4 style="margin-bottom: 15px; color: #333; font-size: 16px;">
                            <span style="margin-right: 8px;">📊</span>Risk Level Thresholds
                        </h4>
                        <div style="display: grid; gap: 12px;">
                            <div style="display: flex; align-items: center; padding: 10px; background: rgba(40, 167, 69, 0.1); border-radius: 6px; border-left: 3px solid #28a745;">
                                <span style="font-weight: bold; color: #28a745; margin-right: 10px; min-width: 80px;">LOW RISK:</span>
                                <span style="color: #555;">${compliance.thresholds.low_risk.range} — ${compliance.thresholds.low_risk.description}</span>
                            </div>
                            <div style="display: flex; align-items: center; padding: 10px; background: rgba(255, 193, 7, 0.1); border-radius: 6px; border-left: 3px solid #ffc107;">
                                <span style="font-weight: bold; color: #d39e00; margin-right: 10px; min-width: 80px;">MEDIUM:</span>
                                <span style="color: #555;">${compliance.thresholds.medium_risk.range} — ${compliance.thresholds.medium_risk.description}</span>
                            </div>
                            <div style="display: flex; align-items: center; padding: 10px; background: rgba(220, 53, 69, 0.1); border-radius: 6px; border-left: 3px solid #dc3545;">
                                <span style="font-weight: bold; color: #dc3545; margin-right: 10px; min-width: 80px;">HIGH RISK:</span>
                                <span style="color: #555;">${compliance.thresholds.high_risk.range} — ${compliance.thresholds.high_risk.description}</span>
                            </div>
                        </div>
                        <p style="margin-top: 12px; font-size: 13px; color: #666; font-style: italic;">
                            ℹ️ Based on ISO 21001 Educational Quality Management Standards
                        </p>
                    </div>
                `;
            }

            const html = `
                <div class="compliance-header">
                    <div>
                        <h3 style="margin: 0 0 10px 0;">Compliance Assessment</h3>
                        <p style="color: #666;">ISO 21001 Quality Standards</p>
                    </div>
                    <div style="text-align: center;">
                        <div class="compliance-score ${riskClass}">${compliance.score}/5.0</div>
                        <div style="font-size: 14px; color: #666;">${compliance.percentage}%</div>
                    </div>
                </div>
                <div style="padding: 20px; background: rgba(102, 126, 234, 0.1); border-radius: 10px; margin-bottom: 15px;">
                    <strong>Risk Level:</strong> <span class="${riskClass}" style="font-size: 18px; font-weight: bold;">${compliance.risk_level}</span>
                    ${compliance.risk_range ? `<span style="color: #666; font-size: 14px; margin-left: 10px;">(${compliance.risk_range})</span>` : ''}
                </div>
                <div class="recommendations">
                    <h4>Recommendations:</h4>
                    <ul>${recsHtml}</ul>
                </div>
                ${thresholdsHtml}
            `;
            document.getElementById('compliance-section').innerHTML = html;
        }

        // Render radar chart
        function renderRadarChart(indices) {
            const ctx = document.getElementById('radarChart').getContext('2d');

            if (charts.radar) charts.radar.destroy();

            charts.radar = new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: ['Learner Needs', 'Satisfaction', 'Success', 'Safety', 'Wellbeing'],
                    datasets: [{
                        label: 'ISO 21001 Indices',
                        data: [
                            indices.learner_needs,
                            indices.satisfaction,
                            indices.success,
                            indices.safety,
                            indices.wellbeing
                        ],
                        backgroundColor: 'rgba(66, 133, 244, 0.2)',
                        borderColor: 'rgba(66, 133, 244, 1)',
                        borderWidth: 3,
                        pointBackgroundColor: 'rgba(66, 133, 244, 1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgba(66, 133, 244, 1)',
                        pointHoverBorderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            beginAtZero: true,
                            max: 5,
                            ticks: {
                                stepSize: 1,
                                backdropColor: 'transparent'
                            },
                            grid: {
                                color: 'rgba(66, 133, 244, 0.1)'
                            },
                            angleLines: {
                                color: 'rgba(66, 133, 244, 0.1)'
                            },
                            pointLabels: {
                                color: '#333',
                                font: {
                                    size: 12,
                                    weight: '600'
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(66, 133, 244, 0.95)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: 'rgba(66, 133, 244, 1)',
                            borderWidth: 2,
                            padding: 12,
                            displayColors: false
                        }
                    }
                }
            });
        }

                // Render grade distribution chart — compute percentages client-side using total responses
        function renderGradeChart(gradeData, totalResponses) {
            const ctx = document.getElementById('gradeChart').getContext('2d');

            if (charts.grade) charts.grade.destroy();

            // Compute total (prefer server-provided totalResponses; fallback to sum of counts)
            const total = (typeof totalResponses === 'number' && totalResponses > 0)
                ? totalResponses
                : gradeData.reduce((s, g) => s + (g.count || 0), 0);

            // Build labels that include percentage so legend reflects the true percent next to counts
            const labels = gradeData.map(g => {
                const pct = total > 0 ? ((g.count / total) * 100).toFixed(1) : '0.0';
                return `Grade ${g.grade} — ${pct}%`;
            });

            const counts = gradeData.map(g => g.count);

            charts.grade = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: counts,
                        backgroundColor: [
                            'rgba(66, 133, 244, 0.85)',
                            'rgba(255, 215, 0, 0.85)'
                        ],
                        borderColor: [
                            'rgba(66, 133, 244, 1)',
                            'rgba(255, 215, 0, 1)'
                        ],
                        borderWidth: 3,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                font: {
                                    size: 13,
                                    weight: '600'
                                },
                                color: '#333'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(66, 133, 244, 0.95)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: 'rgba(66, 133, 244, 1)',
                            borderWidth: 2,
                            padding: 12,
                            callbacks: {
                                // Show both absolute count and percentage in tooltip
                                label: function(context) {
                                    const idx = context.dataIndex;
                                    const count = counts[idx] || 0;
                                    const pct = total > 0 ? ((count / total) * 100).toFixed(1) : '0.0';
                                    return `${count} responses — ${pct}%`;
                                }
                            }
                        }
                    }
                }
            });
        }

        // Render bar chart
        function renderBarChart(indices) {
            const ctx = document.getElementById('barChart').getContext('2d');

            if (charts.bar) charts.bar.destroy();

            // Create gradient for bars
            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(66, 133, 244, 0.9)');
            gradient.addColorStop(1, 'rgba(66, 133, 244, 0.6)');

            charts.bar = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Learner Needs', 'Satisfaction', 'Success', 'Safety', 'Wellbeing'],
                    datasets: [{
                        label: 'Score (out of 5)',
                        data: [
                            indices.learner_needs,
                            indices.satisfaction,
                            indices.success,
                            indices.safety,
                            indices.wellbeing
                        ],
                        backgroundColor: gradient,
                        borderColor: 'rgba(66, 133, 244, 1)',
                        borderWidth: 2,
                        borderRadius: 10,
                        hoverBackgroundColor: 'rgba(255, 215, 0, 0.85)',
                        hoverBorderColor: 'rgba(255, 215, 0, 1)',
                        hoverBorderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 5,
                            grid: {
                                color: 'rgba(66, 133, 244, 0.1)'
                            },
                            ticks: {
                                color: '#666',
                                font: {
                                    size: 12,
                                    weight: '600'
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#333',
                                font: {
                                    size: 12,
                                    weight: '600'
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(66, 133, 244, 0.95)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: 'rgba(66, 133, 244, 1)',
                            borderWidth: 2,
                            padding: 12,
                            displayColors: false
                        }
                    }
                }
            });
        }

        // Load time series chart
        async function loadTimeSeriesChart(filterParams) {
            try {
                const response = await fetch('/api/analytics/time-series?group_by=week&' + filterParams, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    credentials: 'same-origin'
                });

                if (!response.ok) {
                    console.error('Time series error:', await response.text());
                    throw new Error('Failed to load time series');
                }

                const result = await response.json();

                if (!result.success) {
                    throw new Error(result.message || 'Failed to load time series');
                }

                const data = result.data;

                const ctx = document.getElementById('trendChart').getContext('2d');

                if (charts.trend) charts.trend.destroy();

                // Create gradient for line chart
                const lineGradient = ctx.createLinearGradient(0, 0, 0, 400);
                lineGradient.addColorStop(0, 'rgba(66, 133, 244, 0.3)');
                lineGradient.addColorStop(1, 'rgba(66, 133, 244, 0.05)');

                charts.trend = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Average Satisfaction',
                            data: data.data,
                            borderColor: 'rgba(66, 133, 244, 1)',
                            backgroundColor: lineGradient,
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            pointBackgroundColor: 'rgba(66, 133, 244, 1)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointHoverBackgroundColor: 'rgba(255, 215, 0, 1)',
                            pointHoverBorderColor: '#fff',
                            pointHoverBorderWidth: 3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 5,
                                grid: {
                                    color: 'rgba(66, 133, 244, 0.1)'
                                },
                                ticks: {
                                    color: '#666',
                                    font: {
                                        size: 12,
                                        weight: '600'
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    color: 'rgba(66, 133, 244, 0.05)'
                                },
                                ticks: {
                                    color: '#333',
                                    font: {
                                        size: 11,
                                        weight: '600'
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(66, 133, 244, 0.95)',
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                borderColor: 'rgba(66, 133, 244, 1)',
                                borderWidth: 2,
                                padding: 12,
                                displayColors: false
                            }
                        }
                    }
                });
            } catch (error) {
                console.error('Error loading time series:', error);
            }
        }

        // UI State Functions
        function showLoading() {
            document.getElementById('loading').classList.add('active');
            document.getElementById('dashboard-content').style.display = 'none';
            document.getElementById('no-data').style.display = 'none';
        }

        function hideLoading() {
            document.getElementById('loading').classList.remove('active');
        }

        function showContent() {
            hideLoading();
            document.getElementById('dashboard-content').style.display = 'block';
            document.getElementById('no-data').style.display = 'none';
        }

        function showNoData() {
            hideLoading();
            document.getElementById('dashboard-content').style.display = 'none';
            document.getElementById('no-data').style.display = 'block';
        }

        // Populate week filter dropdown
        function populateWeekFilter() {
            const weekFilter = document.getElementById('week-filter');
            const currentYear = new Date().getFullYear();

            for (let week = 1; week <= 52; week++) {
                const option = document.createElement('option');
                option.value = `${currentYear}-W${week.toString().padStart(2, '0')}`;
                option.textContent = `Week ${week}, ${currentYear} (${getWeekDateRange(currentYear, week)})`;
                weekFilter.appendChild(option);
            }
        }

        // Get date range for a specific week
        function getWeekDateRange(year, week) {
            const jan1 = new Date(year, 0, 1);
            const daysToFirstMonday = (8 - jan1.getDay()) % 7;
            const firstMonday = new Date(year, 0, daysToFirstMonday + 1);

            const weekStart = new Date(firstMonday);
            weekStart.setDate(firstMonday.getDate() + (week - 1) * 7);

            const weekEnd = new Date(weekStart);
            weekEnd.setDate(weekStart.getDate() + 6);

            const formatDate = (date) => {
                return `${(date.getMonth() + 1).toString().padStart(2, '0')}/${date.getDate().toString().padStart(2, '0')}`;
            };

            return `${formatDate(weekStart)} - ${formatDate(weekEnd)}`;
        }

        // Update Date Range Display
        function updateDateRangeDisplay() {
            const dateFrom = document.getElementById('date_from').value;
            const dateTo = document.getElementById('date_to').value;
            const weekFilter = document.getElementById('week-filter');
            const dateRangeText = document.getElementById('date-range-text');

            if (!dateFrom && !dateTo) {
                dateRangeText.textContent = 'Viewing All Data';
                dateRangeText.parentElement.style.background = 'rgba(66, 133, 244, 0.1)';
                dateRangeText.parentElement.style.color = '#333';
            } else if (dateFrom && dateTo) {
                const from = new Date(dateFrom);
                const to = new Date(dateTo);
                const fromStr = from.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                const toStr = to.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                dateRangeText.textContent = `📅 ${fromStr} - ${toStr}`;
                dateRangeText.parentElement.style.background = 'rgba(40, 167, 69, 0.15)';
                dateRangeText.parentElement.style.color = '#155724';
            } else if (dateFrom) {
                const from = new Date(dateFrom);
                const fromStr = from.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                dateRangeText.textContent = `📅 From ${fromStr}`;
                dateRangeText.parentElement.style.background = 'rgba(255, 193, 7, 0.15)';
                dateRangeText.parentElement.style.color = '#856404';
            } else if (dateTo) {
                const to = new Date(dateTo);
                const toStr = to.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                dateRangeText.textContent = `📅 Until ${toStr}`;
                dateRangeText.parentElement.style.background = 'rgba(255, 193, 7, 0.15)';
                dateRangeText.parentElement.style.color = '#856404';
            }

            // Add additional filter info
            const gradeLevel = document.getElementById('grade_level').value;
            const semester = document.getElementById('semester').value;

            const additionalFilters = [];
            if (gradeLevel) additionalFilters.push(`Grade: ${gradeLevel}`);
            if (semester) additionalFilters.push(`Semester: ${semester}`);

            if (additionalFilters.length > 0) {
                dateRangeText.textContent += ` • ${additionalFilters.join(' • ')}`;
            }
        }

        // Setup Filter Handlers
        function setupFilterHandlers() {
            // Quick filter buttons
            document.querySelectorAll('.quick-filter-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    // Remove active class from all buttons
                    document.querySelectorAll('.quick-filter-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    const filter = this.dataset.filter;
                    const now = new Date();

                    document.getElementById('week-filter').value = '';

                    switch(filter) {
                        case 'today':
                            const today = now.toISOString().split('T')[0];
                            document.getElementById('date_from').value = today;
                            document.getElementById('date_to').value = today;
                            break;
                        case 'week':
                            const weekStart = new Date(now);
                            weekStart.setDate(now.getDate() - now.getDay());
                            const weekEnd = new Date(weekStart);
                            weekEnd.setDate(weekStart.getDate() + 6);
                            document.getElementById('date_from').value = weekStart.toISOString().split('T')[0];
                            document.getElementById('date_to').value = weekEnd.toISOString().split('T')[0];
                            break;
                        case 'month':
                            const monthStart = new Date(now.getFullYear(), now.getMonth(), 1);
                            const monthEnd = new Date(now.getFullYear(), now.getMonth() + 1, 0);
                            document.getElementById('date_from').value = monthStart.toISOString().split('T')[0];
                            document.getElementById('date_to').value = monthEnd.toISOString().split('T')[0];
                            break;
                        case 'all':
                            document.getElementById('date_from').value = '';
                            document.getElementById('date_to').value = '';
                            break;
                    }

                    updateDateRangeDisplay();
                    loadDashboard();
                });
            });

            // Week filter change handler
            document.getElementById('week-filter').addEventListener('change', function() {
                if (this.value) {
                    const [year, week] = this.value.split('-W');
                    const weekNum = parseInt(week);

                    const jan1 = new Date(year, 0, 1);
                    const daysToFirstMonday = (8 - jan1.getDay()) % 7;
                    const firstMonday = new Date(year, 0, daysToFirstMonday + 1);

                    const weekStart = new Date(firstMonday);
                    weekStart.setDate(firstMonday.getDate() + (weekNum - 1) * 7);

                    const weekEnd = new Date(weekStart);
                    weekEnd.setDate(weekStart.getDate() + 6);

                    document.getElementById('date_from').value = weekStart.toISOString().split('T')[0];
                    document.getElementById('date_to').value = weekEnd.toISOString().split('T')[0];

                    updateDateRangeDisplay();
                    loadDashboard();
                }
            });

            // Auto-apply when date inputs change
            ['date_from', 'date_to'].forEach(dateId => {
                document.getElementById(dateId).addEventListener('change', function() {
                    document.getElementById('week-filter').value = '';
                    updateDateRangeDisplay();
                });
            });

            // Auto-apply when other filters change
            ['grade_level', 'semester'].forEach(filterId => {
                document.getElementById(filterId).addEventListener('change', function() {
                    updateDateRangeDisplay();
                });
            });
        }
    </script>
</body>
</html>
