<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISO 21001 Analytics - Quality Education Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@3.0.0/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
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

        .analytics-container {
            max-width: 1600px;
            margin: 0 auto;
        }

        .analytics-header {
            background: white;
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            text-align: center;
        }

        .analytics-header h1 {
            font-size: 36px;
            color: #333;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .analytics-header p {
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

        /* Advanced Filter Panel */
        .filter-panel {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .filter-panel h3 {
            margin-bottom: 20px;
            color: #333;
            font-size: 22px;
            border-bottom: 3px solid rgba(66,133,244,1);
            padding-bottom: 10px;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-group label {
            font-weight: 600;
            color: #555;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .filter-group select,
        .filter-group input {
            padding: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .filter-group select:focus,
        .filter-group input:focus {
            border-color: rgba(66,133,244,1);
            outline: none;
        }

        .filter-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
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

        .btn-export {
            background: #28a745;
            color: white;
        }

        .btn-export:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40,167,69,0.4);
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

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
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

        .stat-sublabel {
            font-size: 12px;
            color: #999;
            margin-top: 5px;
        }

        .stat-trend {
            margin-top: 10px;
            font-size: 14px;
            font-weight: 600;
        }

        .trend-up {
            color: #28a745;
        }

        .trend-down {
            color: #dc3545;
        }

        .trend-stable {
            color: #ffc107;
        }

        /* Compliance Risk Meter */
        .risk-meter-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .risk-meter-card h3 {
            margin-bottom: 20px;
            color: #333;
            font-size: 20px;
            border-bottom: 3px solid rgba(66,133,244,1);
            padding-bottom: 15px;
        }

        .risk-meter {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 30px;
        }

        .risk-gauge {
            flex: 1;
            position: relative;
            height: 200px;
        }

        .risk-info {
            flex: 1;
        }

        .risk-level {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .risk-low {
            color: #28a745;
        }

        .risk-medium {
            color: #ffc107;
        }

        .risk-high {
            color: #dc3545;
        }

        .risk-recommendations {
            margin-top: 20px;
        }

        .risk-recommendations h4 {
            font-size: 16px;
            margin-bottom: 10px;
            color: #555;
        }

        .risk-recommendations ul {
            list-style: none;
            padding: 0;
        }

        .risk-recommendations li {
            padding: 8px 0;
            padding-left: 20px;
            position: relative;
            color: #666;
        }

        .risk-recommendations li:before {
            content: "→";
            position: absolute;
            left: 0;
            color: rgba(66,133,244,1);
            font-weight: bold;
        }

        /* Charts Grid */
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 40px;
            margin-bottom: 30px;
        }

        @media (max-width: 1200px) {
            .charts-grid {
                grid-template-columns: 1fr;
            }
        }

        .chart-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            margin-bottom: 10px;
        }

        .chart-card:hover {
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        }

        .chart-card h3 {
            margin-bottom: 20px;
            color: #333;
            font-size: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid rgba(66,133,244,1);
        }

        .chart-wrapper {
            position: relative;
            height: 350px;
        }

        .full-width-chart {
            grid-column: 1 / -1;
            margin-bottom: 30px;
        }

        /* Loading Overlay */
        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .loading-overlay.active {
            display: flex;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid rgba(66,133,244,1);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .no-data-message {
            background: white;
            border-radius: 15px;
            padding: 60px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .no-data-message h2 {
            color: #666;
            margin-bottom: 15px;
        }

        .no-data-message p {
            color: #999;
            font-size: 16px;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .charts-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .analytics-header h1 {
                font-size: 28px;
            }

            .stat-value {
                font-size: 32px;
            }

            .chart-wrapper {
                height: 250px;
            }

            .filter-grid {
                grid-template-columns: 1fr;
            }

            .risk-meter {
                flex-direction: column;
            }
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
        }

        /* Sentiment Analysis Section */
        .sentiment-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .sentiment-card h3 {
            margin-bottom: 20px;
            color: #333;
            font-size: 20px;
            border-bottom: 3px solid rgba(66,133,244,1);
            padding-bottom: 15px;
        }

        .sentiment-breakdown {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .sentiment-item {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .sentiment-item .count {
            font-size: 32px;
            font-weight: 700;
            color: rgba(66,133,244,1);
        }

        .sentiment-item .label {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
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
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">Dashboard</a>
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
        <div class="analytics-container">
            <a href="{{ route('admin.dashboard') }}" class="back-button">← Back to Dashboard</a>

            <div class="analytics-header">
                <h1>Advanced ISO 21001 Analytics Dashboard</h1>
                <p>Comprehensive Quality Education Metrics, Insights & Trends</p>
            </div>

            @if($noData)
                <div class="no-data-message">
                    <h2>No Survey Data Available</h2>
                    <p>There are currently no survey responses to analyze. Data will appear here once students start submitting their feedback.</p>
                </div>
            @else
                <!-- Advanced Filter Panel -->
                <div class="filter-panel">
                    <h3>Advanced Filters & Segmentation</h3>

                    <div class="quick-filters">
                        <button class="quick-filter-btn active" data-range="all">All Time</button>
                        <button class="quick-filter-btn" data-range="7">Last 7 Days</button>
                        <button class="quick-filter-btn" data-range="30">Last 30 Days</button>
                        <button class="quick-filter-btn" data-range="90">Last 90 Days</button>
                        <button class="quick-filter-btn" data-range="custom">Custom Range</button>
                    </div>

                    <form id="analytics-filters">
                        <div class="filter-grid">
                            <div class="filter-group">
                                <label for="date_from">Date From</label>
                                <input type="date" id="date_from" name="date_from">
                            </div>
                            <div class="filter-group">
                                <label for="date_to">Date To</label>
                                <input type="date" id="date_to" name="date_to">
                            </div>
                            <div class="filter-group">
                                <label for="track">Track</label>
                                <select id="track" name="track">
                                    <option value="">All Tracks</option>
                                    <option value="CSS">CSS</option>
                                </select>
                            </div>
                            <div class="filter-group">
                                <label for="grade_level">Grade Level</label>
                                <select id="grade_level" name="grade_level">
                                    <option value="">All Grades</option>
                                    <option value="11">Grade 11</option>
                                    <option value="12">Grade 12</option>
                                </select>
                            </div>
                            <div class="filter-group">
                                <label for="semester">Semester</label>
                                <select id="semester" name="semester">
                                    <option value="">All Semesters</option>
                                    <option value="1st">1st Semester</option>
                                    <option value="2nd">2nd Semester</option>
                                </select>
                            </div>
                            <div class="filter-group">
                                <label for="academic_year">Academic Year</label>
                                <select id="academic_year" name="academic_year">
                                    <option value="">All Years</option>
                                    <option value="2024-2025">2024-2025</option>
                                    <option value="2023-2024">2023-2024</option>
                                </select>
                            </div>
                        </div>
                        <div class="filter-actions">
                            <button type="button" class="btn btn-secondary" id="clear-filters">Clear Filters</button>
                            <button type="button" class="btn btn-primary" id="apply-filters">Apply Filters</button>
                        </div>
                    </form>
                </div>

                <!-- Key Statistics -->
                <div class="stats-grid" id="stats-container">
                    <div class="stat-card">
                        <div class="stat-value">{{ $analytics['total_responses'] }}</div>
                        <div class="stat-label">Total Responses</div>
                        <div class="stat-sublabel">Survey Submissions</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ $analytics['iso_21001_indices']['learner_needs_index'] }}</div>
                        <div class="stat-label">Learner Needs</div>
                        <div class="stat-sublabel">Out of 5.00</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ $analytics['iso_21001_indices']['satisfaction_score'] }}</div>
                        <div class="stat-label">Satisfaction Score</div>
                        <div class="stat-sublabel">Out of 5.00</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ $analytics['iso_21001_indices']['success_index'] }}</div>
                        <div class="stat-label">Success Index</div>
                        <div class="stat-sublabel">Out of 5.00</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ $analytics['iso_21001_indices']['safety_index'] }}</div>
                        <div class="stat-label">Safety Index</div>
                        <div class="stat-sublabel">Out of 5.00</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ $analytics['iso_21001_indices']['wellbeing_index'] }}</div>
                        <div class="stat-label">Wellbeing Index</div>
                        <div class="stat-sublabel">Out of 5.00</div>
                    </div>
                </div>

                <!-- Compliance Risk Meter -->
                <div class="risk-meter-card" id="risk-meter-container">
                    <h3>ISO 21001 Compliance Risk Meter</h3>
                    <div class="risk-meter">
                        <div class="risk-gauge">
                            <canvas id="riskGaugeChart"></canvas>
                        </div>
                        <div class="risk-info">
                            <div class="risk-level risk-low" id="risk-level-text">Loading...</div>
                            <p id="risk-description">Calculating compliance metrics...</p>
                            <div class="risk-recommendations">
                                <h4>Recommendations:</h4>
                                <ul id="risk-recommendations-list">
                                    <li>Loading recommendations...</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Time-Series Trends -->
                <div class="chart-card full-width-chart">
                    <h3>Time-Series Trends - Overall Satisfaction</h3>
                    <div class="chart-wrapper">
                        <canvas id="timeSeriesChart"></canvas>
                    </div>
                </div>

                <!-- Main Charts -->
                <div class="charts-grid">
                    <!-- ISO 21001 Indices Radar Chart -->
                    <div class="chart-card">
                        <h3>ISO 21001 Quality Indices</h3>
                        <div class="chart-wrapper">
                            <canvas id="indicesRadarChart"></canvas>
                        </div>
                    </div>

                    <!-- Grade Level Distribution Pie Chart -->
                    <div class="chart-card">
                        <h3>Grade Level Distribution</h3>
                        <div class="chart-wrapper">
                            <canvas id="gradePieChart"></canvas>
                        </div>
                    </div>

                    <!-- ISO Indices Bar Chart -->
                    <div class="chart-card full-width-chart">
                        <h3>Detailed Quality Metrics Comparison</h3>
                        <div class="chart-wrapper">
                            <canvas id="indicesBarChart"></canvas>
                        </div>
                    </div>

                    <!-- Heat Map -->
                    <div class="chart-card">
                        <h3>Performance Heat Map (Track × Grade)</h3>
                        <div class="chart-wrapper">
                            <canvas id="heatMapChart"></canvas>
                        </div>
                    </div>

                    <!-- Response Rate Analytics -->
                    <div class="chart-card">
                        <h3>Response Rate by Demographics</h3>
                        <div class="chart-wrapper">
                            <canvas id="responseRateChart"></canvas>
                        </div>
                    </div>

                    <!-- Indirect Metrics Bar Chart -->
                    <div class="chart-card full-width-chart">
                        <h3>Indirect Performance Metrics</h3>
                        <div class="chart-wrapper">
                            <canvas id="indirectMetricsChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Sentiment Analysis Section -->
                <div class="sentiment-card" id="sentiment-container">
                    <h3>AI-Powered Sentiment Analysis</h3>
                    <p style="margin-bottom: 20px; color: #666;">Analyzing student feedback using advanced sentiment detection</p>
                    <div class="sentiment-breakdown" id="sentiment-breakdown">
                        <div class="sentiment-item">
                            <div class="count" id="sentiment-positive">-</div>
                            <div class="label">Positive Comments</div>
                        </div>
                        <div class="sentiment-item">
                            <div class="count" id="sentiment-neutral">-</div>
                            <div class="label">Neutral Comments</div>
                        </div>
                        <div class="sentiment-item">
                            <div class="count" id="sentiment-negative">-</div>
                            <div class="label">Negative Comments</div>
                        </div>
                        <div class="sentiment-item">
                            <div class="count" id="sentiment-score">-</div>
                            <div class="label">Overall Score</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </main>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loading-overlay">
        <div class="loading-spinner"></div>
    </div>

    <script>
        @if(!$noData)
        // Global chart instances
        let charts = {};

        // CSRF Token Setup for AJAX
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Chart.js configuration
        Chart.defaults.font.family = "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif";
        Chart.defaults.color = '#666';

        // Initialize analytics on page load
        document.addEventListener('DOMContentLoaded', function() {
            initializeCharts();
            loadComplianceRisk();
            loadTimeSeriesData();
            loadHeatMapData();
            loadResponseRateData();
            loadSentimentAnalysis();
            setupFilterHandlers();
        });

        function initializeCharts() {
            // ISO 21001 Indices Radar Chart
            const radarCtx = document.getElementById('indicesRadarChart').getContext('2d');
            charts.radar = new Chart(radarCtx, {
                type: 'radar',
                data: {
                    labels: [
                        'Learner Needs',
                        'Satisfaction',
                        'Success',
                        'Safety',
                        'Wellbeing',
                        'Overall Satisfaction'
                    ],
                    datasets: [{
                        label: 'ISO 21001 Quality Indices',
                        data: [
                            {{ $analytics['iso_21001_indices']['learner_needs_index'] }},
                            {{ $analytics['iso_21001_indices']['satisfaction_score'] }},
                            {{ $analytics['iso_21001_indices']['success_index'] }},
                            {{ $analytics['iso_21001_indices']['safety_index'] }},
                            {{ $analytics['iso_21001_indices']['wellbeing_index'] }},
                            {{ $analytics['iso_21001_indices']['overall_satisfaction'] }}
                        ],
                        backgroundColor: 'rgba(66, 133, 244, 0.2)',
                        borderColor: 'rgba(66, 133, 244, 1)',
                        borderWidth: 2,
                        pointBackgroundColor: 'rgba(66, 133, 244, 1)',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgba(66, 133, 244, 1)',
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            min: 0,
                            max: 5,
                            ticks: {
                                stepSize: 1
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: {
                                size: 14
                            },
                            bodyFont: {
                                size: 13
                            }
                        }
                    }
                }
            });

            // Grade Level Distribution Pie Chart
            const pieCtx = document.getElementById('gradePieChart').getContext('2d');
            charts.pie = new Chart(pieCtx, {
                type: 'doughnut',
                data: {
                    labels: [
                        @foreach($analytics['distribution']['grade_level'] as $grade => $count)
                            'Grade {{ $grade }}',
                        @endforeach
                    ],
                    datasets: [{
                        data: [
                            @foreach($analytics['distribution']['grade_level'] as $count)
                                {{ $count }},
                            @endforeach
                        ],
                        backgroundColor: [
                            'rgba(66, 133, 244, 0.8)',
                            'rgba(255, 215, 0, 0.8)',
                            'rgba(100, 181, 246, 0.8)',
                            'rgba(255, 235, 59, 0.8)'
                        ],
                        borderColor: [
                            'rgba(66, 133, 244, 1)',
                            'rgba(255, 215, 0, 1)',
                            'rgba(100, 181, 246, 1)',
                            'rgba(255, 235, 59, 1)'
                        ],
                        borderWidth: 2
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
                                    size: 13
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((context.parsed / total) * 100).toFixed(1);
                                    return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });

            // ISO Indices Bar Chart
            const barCtx = document.getElementById('indicesBarChart').getContext('2d');
            charts.bar = new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: ['Learner Needs', 'Satisfaction', 'Success', 'Safety', 'Wellbeing', 'Overall'],
                    datasets: [{
                        label: 'Quality Index Score (out of 5)',
                        data: [
                            {{ $analytics['iso_21001_indices']['learner_needs_index'] }},
                            {{ $analytics['iso_21001_indices']['satisfaction_score'] }},
                            {{ $analytics['iso_21001_indices']['success_index'] }},
                            {{ $analytics['iso_21001_indices']['safety_index'] }},
                            {{ $analytics['iso_21001_indices']['wellbeing_index'] }},
                            {{ $analytics['iso_21001_indices']['overall_satisfaction'] }}
                        ],
                        backgroundColor: [
                            'rgba(66, 133, 244, 0.8)',
                            'rgba(255, 215, 0, 0.8)',
                            'rgba(100, 181, 246, 0.8)',
                            'rgba(255, 235, 59, 0.8)',
                            'rgba(33, 150, 243, 0.8)',
                            'rgba(255, 193, 7, 0.8)'
                        ],
                        borderColor: [
                            'rgba(66, 133, 244, 1)',
                            'rgba(255, 215, 0, 1)',
                            'rgba(100, 181, 246, 1)',
                            'rgba(255, 235, 59, 1)',
                            'rgba(33, 150, 243, 1)',
                            'rgba(255, 193, 7, 1)'
                        ],
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
                            max: 5,
                            ticks: {
                                stepSize: 1
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: {
                                size: 14
                            },
                            bodyFont: {
                                size: 13
                            }
                        }
                    }
                }
            });

            // Indirect Metrics Chart
            const indirectCtx = document.getElementById('indirectMetricsChart').getContext('2d');
            charts.indirect = new Chart(indirectCtx, {
                type: 'bar',
                data: {
                    labels: ['Avg Grade', 'Attendance %', 'Participation', 'Extra Hours', 'Counseling'],
                    datasets: [{
                        label: 'Performance Metrics',
                        data: [
                            {{ $analytics['indirect_metrics']['average_grade'] }},
                            {{ $analytics['indirect_metrics']['average_attendance_rate'] }},
                            {{ $analytics['indirect_metrics']['average_participation_score'] }},
                            {{ $analytics['indirect_metrics']['average_extracurricular_hours'] }},
                            {{ $analytics['indirect_metrics']['average_counseling_sessions'] }}
                        ],
                        backgroundColor: 'rgba(255, 215, 0, 0.6)',
                        borderColor: 'rgba(255, 215, 0, 1)',
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
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: {
                                size: 14
                            },
                            bodyFont: {
                                size: 13
                            }
                        }
                    }
                }
            });
        }

        // Load Compliance Risk Data
        async function loadComplianceRisk() {
            try {
                const response = await fetch('/api/visualizations/compliance-risk', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                const data = result.data;

                // Update risk level text
                const riskLevelText = document.getElementById('risk-level-text');
                riskLevelText.textContent = data.risk_level + ' Risk';
                riskLevelText.className = 'risk-level risk-' + data.risk_level.toLowerCase();

                // Update description
                document.getElementById('risk-description').textContent =
                    `Compliance Score: ${data.compliance_score}/5.00 (${data.compliance_percentage}%)`;

                // Update recommendations
                const recommendationsList = document.getElementById('risk-recommendations-list');
                recommendationsList.innerHTML = '';
                data.recommendations.forEach(rec => {
                    const li = document.createElement('li');
                    li.textContent = rec;
                    recommendationsList.appendChild(li);
                });

                // Create gauge chart
                const gaugeCtx = document.getElementById('riskGaugeChart').getContext('2d');
                charts.gauge = new Chart(gaugeCtx, {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: [data.compliance_percentage, 100 - data.compliance_percentage],
                            backgroundColor: [
                                data.risk_level === 'Low' ? '#28a745' :
                                data.risk_level === 'Medium' ? '#ffc107' : '#dc3545',
                                '#e9ecef'
                            ],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        circumference: 180,
                        rotation: 270,
                        cutout: '75%',
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                enabled: false
                            }
                        }
                    }
                });
            } catch (error) {
                console.error('Error loading compliance risk data:', error);
            }
        }

        // Load Time-Series Data
        async function loadTimeSeriesData(metric = 'overall_satisfaction', groupBy = 'week') {
            try {
                const params = new URLSearchParams({
                    metric: metric,
                    group_by: groupBy
                });

                const response = await fetch('/api/visualizations/time-series?' + params, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                const data = result.data;

                console.log('Time-series data received:', data);

                // Check if we have data
                if (!data || !data.labels || data.labels.length === 0) {
                    console.warn('No time-series data available');
                    // Show a message in the chart area
                    const chartWrapper = document.getElementById('timeSeriesChart').parentElement;
                    chartWrapper.innerHTML = '<div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #999; font-size: 16px;">Insufficient data for time-series analysis. Need responses from multiple time periods.</div>';
                    return;
                }

                const tsCtx = document.getElementById('timeSeriesChart').getContext('2d');

                if (charts.timeSeries) {
                    charts.timeSeries.destroy();
                }

                // Use bar chart if only one data point, line chart otherwise
                const chartType = data.labels.length === 1 ? 'bar' : 'line';

                const datasetConfig = {
                    label: 'Overall Satisfaction Over Time',
                    data: data.data,
                    borderColor: 'rgba(66, 133, 244, 1)',
                    backgroundColor: chartType === 'bar' ? 'rgba(66, 133, 244, 0.8)' : 'rgba(66, 133, 244, 0.1)',
                    borderWidth: chartType === 'bar' ? 2 : 3,
                };

                // Add line-specific properties
                if (chartType === 'line') {
                    datasetConfig.fill = true;
                    datasetConfig.tension = 0.4;
                    datasetConfig.pointRadius = 5;
                    datasetConfig.pointHoverRadius = 7;
                    datasetConfig.pointBackgroundColor = 'rgba(66, 133, 244, 1)';
                    datasetConfig.pointBorderColor = '#fff';
                    datasetConfig.pointBorderWidth = 2;
                } else {
                    datasetConfig.borderRadius = 8;
                }

                charts.timeSeries = new Chart(tsCtx, {
                    type: chartType,
                    data: {
                        labels: data.labels,
                        datasets: [datasetConfig]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 5,
                                ticks: {
                                    stepSize: 1
                                },
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12
                            }
                        }
                    }
                });
            } catch (error) {
                console.error('Error loading time-series data:', error);
                // Show error message in the chart area
                const chartWrapper = document.getElementById('timeSeriesChart').parentElement;
                chartWrapper.innerHTML = '<div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #dc3545; font-size: 16px;">Error loading time-series data. Please check console for details.</div>';
            }
        }

        // Load Heat Map Data
        async function loadHeatMapData() {
            try {
                const response = await fetch('/api/visualizations/heat-map', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                const data = result.data;

                const hmCtx = document.getElementById('heatMapChart').getContext('2d');

                if (charts.heatMap) {
                    charts.heatMap.destroy();
                }

                // Prepare data for chart (simplified bar representation)
                const labels = data.data.map(item => `${item.track} - Grade ${item.grade_level}`);
                const values = data.data.map(item => item.value);
                const counts = data.data.map(item => item.count);

                charts.heatMap = new Chart(hmCtx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Performance Score',
                            data: values,
                            backgroundColor: values.map(v => {
                                if (v >= 4.2) return 'rgba(40, 167, 69, 0.8)';
                                if (v >= 3.5) return 'rgba(255, 193, 7, 0.8)';
                                return 'rgba(220, 53, 69, 0.8)';
                            }),
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
                            tooltip: {
                                callbacks: {
                                    afterLabel: function(context) {
                                        return 'Responses: ' + counts[context.dataIndex];
                                    }
                                }
                            }
                        }
                    }
                });
            } catch (error) {
                console.error('Error loading heat map data:', error);
            }
        }

        // Load Response Rate Data
        async function loadResponseRateData() {
            try {
                const response = await fetch('/api/visualizations/response-rate', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                const data = result.data;

                const rrCtx = document.getElementById('responseRateChart').getContext('2d');

                if (charts.responseRate) {
                    charts.responseRate.destroy();
                }

                // Prepare data for gender distribution
                const genderLabels = Object.keys(data.by_gender);
                const genderData = Object.values(data.by_gender);

                charts.responseRate = new Chart(rrCtx, {
                    type: 'pie',
                    data: {
                        labels: genderLabels,
                        datasets: [{
                            data: genderData,
                            backgroundColor: [
                                'rgba(66, 133, 244, 0.8)',
                                'rgba(255, 215, 0, 0.8)',
                                'rgba(100, 181, 246, 0.8)',
                                'rgba(255, 235, 59, 0.8)'
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
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = ((context.parsed / total) * 100).toFixed(1);
                                        return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }
                    }
                });
            } catch (error) {
                console.error('Error loading response rate data:', error);
            }
        }

        // Load Sentiment Analysis
        async function loadSentimentAnalysis() {
            try {
                const response = await fetch('/api/ai/sentiment-analysis', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                if (!response.ok) {
                    throw new Error('Sentiment analysis not available');
                }

                const result = await response.json();
                const data = result.data || result;

                // Update sentiment breakdown
                if (data.breakdown) {
                    document.getElementById('sentiment-positive').textContent = data.breakdown.positive || 0;
                    document.getElementById('sentiment-neutral').textContent = data.breakdown.neutral || 0;
                    document.getElementById('sentiment-negative').textContent = data.breakdown.negative || 0;
                }

                if (data.sentiment_score) {
                    document.getElementById('sentiment-score').textContent = data.sentiment_score + '%';
                }
            } catch (error) {
                console.error('Error loading sentiment analysis:', error);
                // Hide sentiment card if not available
                document.getElementById('sentiment-container').style.display = 'none';
            }
        }

        // Setup Filter Handlers
        function setupFilterHandlers() {
            // Quick filter buttons
            document.querySelectorAll('.quick-filter-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.quick-filter-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    const range = this.getAttribute('data-range');
                    if (range !== 'custom' && range !== 'all') {
                        const daysAgo = parseInt(range);
                        const dateTo = new Date();
                        const dateFrom = new Date();
                        dateFrom.setDate(dateFrom.getDate() - daysAgo);

                        document.getElementById('date_from').value = dateFrom.toISOString().split('T')[0];
                        document.getElementById('date_to').value = dateTo.toISOString().split('T')[0];
                    } else if (range === 'all') {
                        document.getElementById('date_from').value = '';
                        document.getElementById('date_to').value = '';
                    }
                });
            });

            // Apply filters button
            document.getElementById('apply-filters').addEventListener('click', applyFilters);

            // Clear filters button
            document.getElementById('clear-filters').addEventListener('click', function() {
                document.getElementById('analytics-filters').reset();
                document.querySelectorAll('.quick-filter-btn').forEach(b => b.classList.remove('active'));
                document.querySelector('.quick-filter-btn[data-range="all"]').classList.add('active');
                applyFilters();
            });
        }

        // Apply Filters Function
        async function applyFilters() {
            const overlay = document.getElementById('loading-overlay');
            overlay.classList.add('active');

            try {
                const form = document.getElementById('analytics-filters');
                const formData = new FormData(form);
                const params = new URLSearchParams();

                for (const [key, value] of formData) {
                    if (value) {
                        params.append(key, value);
                    }
                }

                // Reload analytics with filters
                const response = await fetch('/api/survey/analytics?' + params, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
                const result = await response.json();
                const analytics = result.data;

                // Update stats
                updateStats(analytics);

                // Reload all charts
                updateCharts(analytics);
                await loadComplianceRisk();
                await loadTimeSeriesData();
                await loadHeatMapData();
                await loadResponseRateData();
                await loadSentimentAnalysis();

            } catch (error) {
                console.error('Error applying filters:', error);
                alert('Error applying filters. Please try again.');
            } finally {
                overlay.classList.remove('active');
            }
        }

        // Update Stats
        function updateStats(analytics) {
            const statsContainer = document.getElementById('stats-container');
            statsContainer.innerHTML = `
                <div class="stat-card">
                    <div class="stat-value">${analytics.total_responses}</div>
                    <div class="stat-label">Total Responses</div>
                    <div class="stat-sublabel">Survey Submissions</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">${analytics.iso_21001_indices.learner_needs_index}</div>
                    <div class="stat-label">Learner Needs</div>
                    <div class="stat-sublabel">Out of 5.00</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">${analytics.iso_21001_indices.satisfaction_score}</div>
                    <div class="stat-label">Satisfaction Score</div>
                    <div class="stat-sublabel">Out of 5.00</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">${analytics.iso_21001_indices.success_index}</div>
                    <div class="stat-label">Success Index</div>
                    <div class="stat-sublabel">Out of 5.00</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">${analytics.iso_21001_indices.safety_index}</div>
                    <div class="stat-label">Safety Index</div>
                    <div class="stat-sublabel">Out of 5.00</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">${analytics.iso_21001_indices.wellbeing_index}</div>
                    <div class="stat-label">Wellbeing Index</div>
                    <div class="stat-sublabel">Out of 5.00</div>
                </div>
            `;
        }

        // Update Charts
        function updateCharts(analytics) {
            // Update radar chart
            charts.radar.data.datasets[0].data = [
                analytics.iso_21001_indices.learner_needs_index,
                analytics.iso_21001_indices.satisfaction_score,
                analytics.iso_21001_indices.success_index,
                analytics.iso_21001_indices.safety_index,
                analytics.iso_21001_indices.wellbeing_index,
                analytics.iso_21001_indices.overall_satisfaction
            ];
            charts.radar.update();

            // Update bar chart
            charts.bar.data.datasets[0].data = [
                analytics.iso_21001_indices.learner_needs_index,
                analytics.iso_21001_indices.satisfaction_score,
                analytics.iso_21001_indices.success_index,
                analytics.iso_21001_indices.safety_index,
                analytics.iso_21001_indices.wellbeing_index,
                analytics.iso_21001_indices.overall_satisfaction
            ];
            charts.bar.update();

            // Update indirect metrics chart
            charts.indirect.data.datasets[0].data = [
                analytics.indirect_metrics.average_grade,
                analytics.indirect_metrics.average_attendance_rate,
                analytics.indirect_metrics.average_participation_score,
                analytics.indirect_metrics.average_extracurricular_hours,
                analytics.indirect_metrics.average_counseling_sessions
            ];
            charts.indirect.update();
        }
        @endif
    </script>
</body>
</html>
