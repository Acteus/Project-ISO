<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISO 21001 Survey Analytics - Quality Education Dashboard</title>
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

        .analytics-container {
            max-width: 1400px;
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

        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
            gap: 30px;
            margin-bottom: 30px;
        }

        .chart-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
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
        }

        .metrics-table {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .metrics-table h3 {
            margin-bottom: 20px;
            color: #333;
            font-size: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid rgba(66,133,244,1);
        }

        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .metric-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 4px solid rgba(66,133,244,1);
            transition: all 0.2s ease;
        }

        .metric-item:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }

        .metric-name {
            font-weight: 600;
            color: #333;
        }

        .metric-value {
            font-size: 20px;
            font-weight: 700;
            color: rgba(66,133,244,1);
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
            <h1>ISO 21001 Survey Analytics Dashboard</h1>
            <p>Comprehensive Quality Education Metrics & Insights</p>
        </div>

        @if($noData)
            <div class="no-data-message">
                <h2>No Survey Data Available</h2>
                <p>There are currently no survey responses to analyze. Data will appear here once students start submitting their feedback.</p>
            </div>
        @else
            <!-- Key Statistics -->
            <div class="stats-grid">
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

            <!-- Main Charts -->
            <div class="charts-grid">
                <!-- ISO 21001 Indices Radar Chart -->
                <div class="chart-card">
                    <h3>ISO 21001 Quality Indices</h3>
                    <div class="chart-wrapper">
                        <canvas id="indicesRadarChart"></canvas>
                    </div>
                </div>

                <!-- Distribution Pie Chart -->
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

                <!-- Indirect Metrics Bar Chart -->
                <div class="chart-card full-width-chart">
                    <h3>Indirect Performance Metrics</h3>
                    <div class="chart-wrapper">
                        <canvas id="indirectMetricsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Detailed Metrics Tables -->
            <div class="metrics-table">
                <h3>Detailed Analytics</h3>

                <h4 style="margin-top: 30px; margin-bottom: 15px; color: rgba(66,133,244,1);">ISO 21001 Quality Indices</h4>
                <div class="metrics-grid">
                    <div class="metric-item">
                        <span class="metric-name">Learner Needs Index</span>
                        <span class="metric-value">{{ $analytics['iso_21001_indices']['learner_needs_index'] }}</span>
                    </div>
                    <div class="metric-item">
                        <span class="metric-name">Satisfaction Score</span>
                        <span class="metric-value">{{ $analytics['iso_21001_indices']['satisfaction_score'] }}</span>
                    </div>
                    <div class="metric-item">
                        <span class="metric-name">Success Index</span>
                        <span class="metric-value">{{ $analytics['iso_21001_indices']['success_index'] }}</span>
                    </div>
                    <div class="metric-item">
                        <span class="metric-name">Safety Index</span>
                        <span class="metric-value">{{ $analytics['iso_21001_indices']['safety_index'] }}</span>
                    </div>
                    <div class="metric-item">
                        <span class="metric-name">Wellbeing Index</span>
                        <span class="metric-value">{{ $analytics['iso_21001_indices']['wellbeing_index'] }}</span>
                    </div>
                    <div class="metric-item">
                        <span class="metric-name">Overall Satisfaction</span>
                        <span class="metric-value">{{ $analytics['iso_21001_indices']['overall_satisfaction'] }}</span>
                    </div>
                </div>

                <h4 style="margin-top: 30px; margin-bottom: 15px; color: #D4AF37;">Indirect Performance Metrics</h4>
                <div class="metrics-grid">
                    <div class="metric-item">
                        <span class="metric-name">Average Grade</span>
                        <span class="metric-value">{{ $analytics['indirect_metrics']['average_grade'] }}</span>
                    </div>
                    <div class="metric-item">
                        <span class="metric-name">Attendance Rate</span>
                        <span class="metric-value">{{ $analytics['indirect_metrics']['average_attendance_rate'] }}%</span>
                    </div>
                    <div class="metric-item">
                        <span class="metric-name">Participation Score</span>
                        <span class="metric-value">{{ $analytics['indirect_metrics']['average_participation_score'] }}</span>
                    </div>
                    <div class="metric-item">
                        <span class="metric-name">Extracurricular Hours</span>
                        <span class="metric-value">{{ $analytics['indirect_metrics']['average_extracurricular_hours'] }}</span>
                    </div>
                    <div class="metric-item">
                        <span class="metric-name">Counseling Sessions</span>
                        <span class="metric-value">{{ $analytics['indirect_metrics']['average_counseling_sessions'] }}</span>
                    </div>
                    <div class="metric-item">
                        <span class="metric-name">Consent Rate</span>
                        <span class="metric-value">{{ $analytics['consent_rate'] }}%</span>
                    </div>
                </div>
            </div>
        @endif
        </div>
    </main>

    <script>
        @if(!$noData)
        // Chart.js configuration
        Chart.defaults.font.family = "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif";
        Chart.defaults.color = '#666';

        // ISO 21001 Indices Radar Chart
        const radarCtx = document.getElementById('indicesRadarChart').getContext('2d');
        new Chart(radarCtx, {
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
        new Chart(pieCtx, {
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
        new Chart(barCtx, {
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
        new Chart(indirectCtx, {
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
        @endif
    </script>
</body>
</html>

