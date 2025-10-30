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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .dashboard-header {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 25px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .dashboard-header h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 10px;
        }

        .dashboard-header p {
            color: #666;
            font-size: 14px;
        }

        .back-button {
            display: inline-block;
            padding: 10px 20px;
            background: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .back-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }

        /* Filters */
        .filters-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .filters-section h3 {
            margin-bottom: 20px;
            color: #333;
            font-size: 18px;
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
            border-color: #667eea;
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
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
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
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-value {
            font-size: 36px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 8px;
        }

        .stat-label {
            font-size: 13px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
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
            font-size: 18px;
            border-bottom: 3px solid #667eea;
            padding-bottom: 10px;
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
            color: #667eea;
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
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
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
    <div class="dashboard-container">
        <a href="{{ route('admin.dashboard') }}" class="back-button">← Back to Dashboard</a>

        <div class="dashboard-header">
            <h1>ISO 21001 Quality Education Analytics</h1>
            <p>Comprehensive metrics and insights for Computer System Servicing (CSS) program</p>
        </div>

        <!-- Filters -->
        <div class="filters-section">
            <h3>Filter Data</h3>
            <form id="filters-form">
                <div class="filters-grid">
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
                    <div class="filter-group">
                        <label>Date From</label>
                        <input type="date" name="date_from" id="date_from">
                    </div>
                    <div class="filter-group">
                        <label>Date To</label>
                        <input type="date" name="date_to" id="date_to">
                    </div>
                </div>
                <div class="filter-actions">
                    <button type="button" class="btn btn-secondary" id="clear-filters">Clear Filters</button>
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div>
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
                    <h3>ISO 21001 Performance Profile</h3>
                    <div class="chart-wrapper">
                        <canvas id="radarChart"></canvas>
                    </div>
                </div>

                <!-- Grade Distribution -->
                <div class="chart-card">
                    <h3>Responses by Grade Level</h3>
                    <div class="chart-wrapper">
                        <canvas id="gradeChart"></canvas>
                    </div>
                </div>

                <!-- Time Series -->
                <div class="chart-card full-width">
                    <h3>Satisfaction Trend Over Time</h3>
                    <div class="chart-wrapper">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>

                <!-- ISO Indices Bar Chart -->
                <div class="chart-card full-width">
                    <h3>ISO 21001 Dimensions Comparison</h3>
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

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        let charts = {};

        // Load dashboard data on page load
        document.addEventListener('DOMContentLoaded', function() {
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
                renderGradeChart(data.distribution.by_grade);
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
            const statsHtml = `
                <div class="stat-card">
                    <div class="stat-value">${data.total_responses}</div>
                    <div class="stat-label">Total Responses</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">${data.overall.satisfaction}</div>
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
                </div>
                <div class="recommendations">
                    <h4>Recommendations:</h4>
                    <ul>${recsHtml}</ul>
                </div>
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
                        backgroundColor: 'rgba(102, 126, 234, 0.2)',
                        borderColor: 'rgba(102, 126, 234, 1)',
                        borderWidth: 2,
                        pointBackgroundColor: 'rgba(102, 126, 234, 1)',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgba(102, 126, 234, 1)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            beginAtZero: true,
                            max: 5,
                            ticks: { stepSize: 1 }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }

        // Render grade distribution chart
        function renderGradeChart(gradeData) {
            const ctx = document.getElementById('gradeChart').getContext('2d');

            if (charts.grade) charts.grade.destroy();

            charts.grade = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: gradeData.map(g => 'Grade ' + g.grade),
                    datasets: [{
                        data: gradeData.map(g => g.count),
                        backgroundColor: [
                            'rgba(102, 126, 234, 0.8)',
                            'rgba(118, 75, 162, 0.8)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        // Render bar chart
        function renderBarChart(indices) {
            const ctx = document.getElementById('barChart').getContext('2d');

            if (charts.bar) charts.bar.destroy();

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
                        backgroundColor: 'rgba(102, 126, 234, 0.8)',
                        borderColor: 'rgba(102, 126, 234, 1)',
                        borderWidth: 2,
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 5
                        }
                    },
                    plugins: {
                        legend: { display: false }
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

                charts.trend = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Average Satisfaction',
                            data: data.data,
                            borderColor: 'rgba(102, 126, 234, 1)',
                            backgroundColor: 'rgba(102, 126, 234, 0.1)',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointRadius: 5,
                            pointHoverRadius: 7
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 5
                            }
                        },
                        plugins: {
                            legend: { display: false }
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
    </script>
</body>
</html>
