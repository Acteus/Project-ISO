<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Insights - ISO Quality Education</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Page Transition Animation */
        @keyframes pageEnter {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        body {
            animation: pageEnter 0.5s ease-out;
        }

        /* Enhanced Modern AI Insights Styles */
        body {
            background: linear-gradient(135deg, rgba(66, 133, 244, 1), rgba(255, 215, 0, 1));
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .survey-main {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(10px);
        }

        .insights-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px;
        }

        .insights-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            color: #333;
            padding: 40px 30px;
            border-radius: 20px;
            margin-bottom: 40px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(66, 133, 244, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
        }

        .insights-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #4285F4, #FF8C00, #FFD700);
        }

        .insights-header h1 {
            margin: 0 0 20px 0;
            font-size: 32px;
            font-weight: 800;
            line-height: 1.3;
            color: #2c3e50;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .insights-header p {
            margin: 0;
            font-size: 18px;
            line-height: 1.6;
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
            color: #5a6c7d;
            font-weight: 500;
        }

        .back-btn {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            color: #333;
            padding: 12px 24px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.3);
            display: inline-block;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 1);
            color: #333;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .insights-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .insight-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 35px 30px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .insight-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #4285F4, #FF8C00, #FFD700);
        }

        .insight-card:hover {
            transform: translateY(-10px) scale(1.03);
            box-shadow: 0 25px 60px rgba(0,0,0,0.2);
        }

        .insight-card h3 {
            margin-top: 0;
            color: #2c3e50;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 3px solid transparent;
            border-image: linear-gradient(90deg, #4285F4, #FF8C00) 1;
        }

        .insight-card p {
            color: #5a6c7d;
            margin-bottom: 25px;
            font-size: 16px;
            line-height: 1.6;
            font-weight: 500;
        }

        .ai-metrics {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .metric-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 30px 20px;
            border-radius: 18px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
            text-align: center;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .metric-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #4285F4, #FF8C00, #FFD700);
        }

        .metric-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 25px 60px rgba(66, 133, 244, 0.25);
        }

        .metric-value {
            font-size: 48px;
            font-weight: 900;
            background: linear-gradient(135deg, #4285F4, #FF8C00);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .metric-label {
            font-size: 14px;
            color: #5a6c7d;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 600;
            line-height: 1.3;
            min-height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Enhanced Button System */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 14px 28px;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.6s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4285F4, #1e88e5);
            color: white;
            box-shadow: 0 8px 25px rgba(66, 133, 244, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 12px 35px rgba(66, 133, 244, 0.6);
            color: white;
        }

        .btn-success {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #218838, #1fa87a);
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 12px 35px rgba(40, 167, 69, 0.6);
            color: white;
        }

        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(5px);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .loading-overlay.active {
            display: flex;
        }

        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 6px solid rgba(255,255,255,0.3);
            border-top: 6px solid rgba(66, 133, 244, 1);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .alert {
            padding: 20px 25px;
            border-radius: 16px;
            margin-bottom: 20px;
            border: none;
            backdrop-filter: blur(15px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(32, 201, 151, 0.1));
            border-left: 5px solid #28a745;
            color: #155724;
        }

        .alert-error {
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.1), rgba(232, 62, 97, 0.1));
            border-left: 5px solid #dc3545;
            color: #721c24;
        }

        .alert-warning {
            background: linear-gradient(135deg, rgba(255, 193, 7, 0.1), rgba(255, 152, 0, 0.1));
            border-left: 5px solid #ffc107;
            color: #856404;
        }

        .ai-results {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .ai-results h3 {
            margin-top: 0;
            color: #2c3e50;
            font-size: 24px;
            font-weight: 700;
            border-bottom: 3px solid transparent;
            border-image: linear-gradient(90deg, #4285F4, #FF8C00) 1;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .result-item {
            padding: 20px 25px;
            border: 1px solid rgba(0,0,0,0.06);
            border-radius: 16px;
            margin-bottom: 15px;
            background: linear-gradient(135deg, rgba(66, 133, 244, 0.03), rgba(255, 140, 0, 0.03));
            transition: all 0.3s ease;
        }

        .result-item:hover {
            background: linear-gradient(135deg, rgba(66, 133, 244, 0.08), rgba(255, 140, 0, 0.08));
            transform: translateX(5px);
            box-shadow: 0 8px 25px rgba(66, 133, 244, 0.15);
        }

        .result-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .result-title {
            font-weight: 700;
            color: #2c3e50;
            font-size: 16px;
        }

        .result-confidence {
            background: linear-gradient(135deg, #4285F4, #1e88e5);
            color: white;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .result-confidence {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .confidence-badge-icon {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
        }

        .conf-high { background: #28a745; }
        .conf-mid  { background: #ffc107; }
        .conf-low  { background: #dc3545; }

        .result-details {
            color: #5a6c7d;
            font-size: 14px;
            line-height: 1.6;
        }

        .footer {
            margin-top: 60px;
            padding: 30px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(15px);
            text-align: center;
            color: #5a6c7d;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }

        .nav-link {
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: #4285F4;
            transform: translateY(-1px);
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
        }

        /* Header styling enhancement */
        .header {
            background: rgba(255, 255, 255, 0.15) !important;
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(66, 133, 244, 0.1), rgba(255, 140, 0, 0.1));
            z-index: -1;
        }

        .logo a {
            color: white !important;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
            font-weight: 800;
        }

        .nav-link {
            color: white !important;
            transition: all 0.3s ease;
            font-weight: 600;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .nav-link:hover {
            color: #FFD700 !important;
            transform: translateY(-2px);
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        .nav-link.active {
            color: #FFD700 !important;
            font-weight: 700;
            text-shadow: 0 2px 8px rgba(255, 215, 0, 0.5);
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .insights-container {
                padding: 20px;
            }

            .insights-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .ai-metrics {
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                gap: 20px;
            }

            .insight-card {
                padding: 25px 20px;
            }
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
                    <a href="{{ route('admin.ai.insights') }}" class="nav-link active">AI Insights</a>
                    <form method="POST" action="{{ route('student.logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="nav-link logout-btn" style="background: linear-gradient(135deg, #dc3545, #c82333); border: none; color: white; cursor: pointer; padding: 10px 20px; border-radius: 8px; font-weight: 700; transition: all 0.3s ease; text-transform: uppercase; letter-spacing: 1px;">
                            <svg style="width: 16px; height: 16px; vertical-align: middle; margin-right: 8px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
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
        <div class="insights-container">
            <a href="{{ route('admin.dashboard') }}" class="back-btn">← Back to Dashboard</a>

            <div class="insights-header">
                <h1>AI Insights Dashboard</h1>
                <p>Comprehensive machine learning analytics for ISO 21001 compliance, predictive modeling, and proactive quality management</p>
                <div id="data-range-display" style="margin-top: 25px; padding: 15px 30px; background: rgba(66, 133, 244, 0.1); backdrop-filter: blur(10px); border-radius: 25px; display: inline-block; font-weight: 600; color: #333; font-size: 14px; border: 1px solid rgba(255, 255, 255, 0.3);">
                    <svg style="width: 20px; height: 20px; vertical-align: middle; margin-right: 10px; fill: rgba(66, 133, 244, 1);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/>
                    </svg>
                    <span id="data-range-text">Loading data range...</span>
                </div>
                <div id="data-stats-display" style="margin-top: 15px; padding: 10px 25px; background: rgba(255, 255, 255, 0.5); backdrop-filter: blur(10px); border-radius: 20px; display: inline-block; font-size: 13px; color: #555; margin-left: 10px;">
                    <span id="data-stats-text">Analyzing <strong id="total-responses-count">0</strong> survey responses</span>
                </div>
            </div>

            <!-- Alert Messages -->
            <div id="alert-container"></div>

            <!-- AI Metrics Overview -->
            <div class="ai-metrics">
                <div class="metric-card">
                    <div class="metric-value" id="service-status">Checking...</div>
                    <div class="metric-label">AI Service Status</div>
                </div>
                <div class="metric-card">
                    <div class="metric-value" id="total-predictions">0</div>
                    <div class="metric-label">AI Predictions Today</div>
                </div>
                <div class="metric-card">
                    <div class="metric-value" id="accuracy-rate">0%</div>
                    <div class="metric-label">Model Accuracy</div>
                </div>
                <div class="metric-card">
                    <div class="metric-value" id="response-time">0ms</div>
                    <div class="metric-label">Avg Response Time</div>
                </div>
                <div class="metric-card">
                    <div class="metric-value" id="iso-compliance">0%</div>
                    <div class="metric-label">ISO 21001 Compliance</div>
                </div>
                <div class="metric-card">
                    <div class="metric-value" id="risk-score">0/100</div>
                    <div class="metric-label">Overall Risk Score</div>
                </div>
            </div>

            <!-- AI Analysis Tools -->
            <div class="insights-grid">
                <!-- Compliance Prediction -->
                <div class="insight-card">
                    <h3>Compliance Prediction</h3>
                    <p>AI-powered prediction of ISO 21001 compliance levels based on learner feedback and performance metrics.</p>
                    <button type="button" class="btn btn-primary" onclick="runCompliancePrediction()">Run Prediction</button>
                </div>

                <!-- Sentiment Analysis -->
                <div class="insight-card">
                    <h3>Sentiment Analysis</h3>
                    <p>Analyze student feedback sentiment using advanced NLP models to identify positive and negative trends.</p>
                    <button type="button" class="btn btn-primary" onclick="runSentimentAnalysis()">Analyze Sentiment</button>
                </div>

                <!-- Student Clustering -->
                <div class="insight-card">
                    <h3>Student Clustering</h3>
                    <p>Group students based on survey responses for targeted interventions and personalized support. ISO 21001:7.1 compliant segmentation.</p>
                    <button type="button" class="btn btn-primary" onclick="runStudentClustering()">Cluster Students</button>
                </div>

                <!-- Predictive Analytics -->
                <div class="insight-card">
                    <h3>Predictive Analytics</h3>
                    <p>Advanced forecasting of student performance, satisfaction trends, and risk factors using time series analysis.</p>
                    <button type="button" class="btn btn-primary" onclick="runPredictiveAnalytics()">Run Predictive Analytics</button>
                </div>

                <!-- Comprehensive Risk Assessment -->
                <div class="insight-card">
                    <h3>Comprehensive Risk Assessment</h3>
                    <p>Complete ISO 21001 compliance risk evaluation across all learner-centric dimensions with intervention recommendations.</p>
                    <button type="button" class="btn btn-primary" onclick="runComprehensiveRiskAssessment()">Assess All Risks</button>
                </div>

                <!-- Trend Analysis -->
                <div class="insight-card">
                    <h3>Satisfaction Trend Analysis</h3>
                    <p>Analyze satisfaction trends over time with forecasting capabilities for proactive quality management.</p>
                    <button type="button" class="btn btn-primary" onclick="runTrendAnalysis()">Analyze Trends</button>
                </div>

                <!-- Performance Prediction -->
                <div class="insight-card">
                    <h3>Performance Prediction</h3>
                    <p>Predict student academic performance and identify at-risk students early.</p>
                    <button type="button" class="btn btn-primary" onclick="runPerformancePrediction()">Predict Performance</button>
                </div>

                <!-- Dropout Risk Assessment -->
                <div class="insight-card">
                    <h3>Dropout Risk Assessment</h3>
                    <p>Identify students at risk of dropping out using machine learning algorithms.</p>
                    <button type="button" class="btn btn-primary" onclick="runDropoutRiskAssessment()">Assess Risk</button>
                </div>

                <!-- Comprehensive Analytics -->
                <div class="insight-card">
                    <h3>Comprehensive Analytics</h3>
                    <p>Run all AI models simultaneously for complete insights into student satisfaction and compliance.</p>
                    <button type="button" class="btn btn-success" onclick="runComprehensiveAnalytics()">Run All Analytics</button>
                </div>
            </div>

            <!-- AI Results Display -->
            <div id="ai-results" class="ai-results" style="display: none;">
                <h3>AI Analysis Results</h3>
                <div id="results-container">
                    <!-- Results will be populated here -->
                </div>
            </div>
        </div>
    </main>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loading-overlay">
        <div style="text-align: center; color: white;">
            <div class="loading-spinner"></div>
            <p style="margin-top: 20px; font-size: 18px; font-weight: 600;">Processing AI Analysis...</p>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-main">
                    <h3 class="footer-title" style="color: #2c3e50; font-weight: 700; margin-bottom: 15px;">ISO Learner-Centric Quality Education</h3>
                    <p class="footer-description" style="color: #5a6c7d; font-size: 16px; line-height: 1.6;">
                        Empowering CSS Students through Learner-Centric Quality Education
                    </p>
                </div>
            </div>
            <div class="footer-bottom" style="margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(0,0,0,0.1);">
                <p class="footer-copyright" style="color: #6c757d; font-weight: 500;">
                    © <span id="currentYear"></span> JRU Senior High School. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <script>
        // Set current year
        document.getElementById('currentYear').textContent = new Date().getFullYear();

        // CSRF Token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        /* ----------------------
           Helpers & Init
           ---------------------- */
        const safeNum = (v, decimals = 0) => {
            const n = Number(v);
            if (Number.isFinite(n)) return decimals === 0 ? Math.round(n) : n.toFixed(decimals);
            return typeof v === 'string' ? v : 'N/A';
        };

        const safePct = (v, decimals = 0) => {
            const n = Number(v);
            if (Number.isFinite(n)) return (decimals === 0 ? Math.round(n * 100) : (n * 100).toFixed(decimals)) + '%';
            return v ?? 'N/A';
        };

        document.addEventListener('DOMContentLoaded', () => {
            checkAIServiceStatus();
            loadAIMetrics();
            loadDataRangeInfo();

            // Add smooth animations
            const cards = document.querySelectorAll('.metric-card, .insight-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';

                setTimeout(() => {
                    card.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });

        /* ----------------------
           Service / Metrics
           ---------------------- */
        async function checkAIServiceStatus() {
            try {
                const res = await fetch('/api/ai/service-status', { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken } });
                const json = await res.json();
                const el = document.getElementById('service-status');
                if (json?.success && json?.data?.available) {
                    el.textContent = 'Online';
                    el.style.background = 'linear-gradient(135deg, #28a745, #20c997)';
                    el.style.webkitBackgroundClip = 'text';
                    el.style.webkitTextFillColor = 'transparent';
                    el.style.backgroundClip = 'text';
                } else {
                    el.textContent = 'Offline';
                    el.style.background = 'linear-gradient(135deg, #dc3545, #e74c3c)';
                    el.style.webkitBackgroundClip = 'text';
                    el.style.webkitTextFillColor = 'transparent';
                    el.style.backgroundClip = 'text';
                }
            } catch (err) {
                console.error('AI Service Status Check Error:', err);
                const el = document.getElementById('service-status');
                el.textContent = 'Error';
                el.style.background = 'linear-gradient(135deg, #ffc107, #ff9800)';
                el.style.webkitBackgroundClip = 'text';
                el.style.webkitTextFillColor = 'transparent';
                el.style.backgroundClip = 'text';
            }
        }

        async function loadAIMetrics() {
            try {
                const res = await fetch('/api/ai/metrics', { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken } });
                const json = await res.json();
                if (json?.success && json?.data) {
                    const d = json.data;
                    document.getElementById('total-predictions').textContent = d.total_predictions ?? 0;
                    document.getElementById('accuracy-rate').textContent = (d.accuracy_rate ?? 0) + '%';
                    document.getElementById('response-time').textContent = (d.avg_response_time ?? 0) + 'ms';
                    document.getElementById('iso-compliance').textContent = (d.iso_compliance_score ?? 0) + '%';
                    document.getElementById('risk-score').textContent = (d.overall_risk_score ?? 0) + '/100';

                    // Update total responses count in the data stats display
                    if (d.total_responses_analyzed !== undefined) {
                        document.getElementById('total-responses-count').textContent = d.total_responses_analyzed;
                    }
                }
            } catch (err) {
                console.error('Error loading AI metrics:', err);
            }
        }

        async function loadDataRangeInfo() {
            try {
                const res = await fetch('/api/survey/analytics', {
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
                });
                const json = await res.json();

                if (json?.success && json?.data) {
                    const analytics = json.data;
                    const dataRangeText = document.getElementById('data-range-text');
                    const dataStatsText = document.getElementById('data-stats-text');

                    // Update total responses
                    const totalResponses = analytics.total_responses || 0;
                    document.getElementById('total-responses-count').textContent = totalResponses;

                    // Get date range from responses
                    if (analytics.date_range) {
                        const startDate = new Date(analytics.date_range.oldest);
                        const endDate = new Date(analytics.date_range.newest);

                        const formatDate = (date) => {
                            const options = { month: 'short', day: 'numeric', year: 'numeric' };
                            return date.toLocaleDateString('en-US', options);
                        };

                        dataRangeText.innerHTML = `<strong>Data Range:</strong> ${formatDate(startDate)} - ${formatDate(endDate)}`;
                    } else {
                        dataRangeText.innerHTML = '<strong>Analyzing All Available Data</strong>';
                    }

                    // Update data stats with more details
                    if (analytics.distribution) {
                        const tracks = Object.keys(analytics.distribution.track || {}).length;
                        const grades = Object.keys(analytics.distribution.grade_level || {}).length;

                        dataStatsText.innerHTML = `Analyzing <strong>${totalResponses}</strong> survey responses across <strong>${tracks}</strong> tracks and <strong>${grades}</strong> grade levels`;
                    } else {
                        dataStatsText.innerHTML = `Analyzing <strong>${totalResponses}</strong> survey responses`;
                    }

                } else {
                    document.getElementById('data-range-text').innerHTML = '<strong>No data available</strong>';
                    document.getElementById('data-stats-text').innerHTML = 'No survey responses found';
                }
            } catch (err) {
                console.error('Error loading data range info:', err);
                document.getElementById('data-range-text').innerHTML = '<strong>Error loading data range</strong>';
            }
        }

        /* ----------------------
           Action wrappers
           ---------------------- */
        function runCompliancePrediction(){ return runAIAnalysis('compliance', 'Predicting compliance levels...'); }
        function runSentimentAnalysis(){ return runAIAnalysis('sentiment', 'Analyzing sentiment...'); }
        function runStudentClustering(){ return runAIAnalysis('clustering', 'Clustering students...'); }
        function runPredictiveAnalytics(){ return runAIAnalysis('predictive', 'Running predictive analytics...'); }
        function runComprehensiveRiskAssessment(){ return runAIAnalysis('risk_assessment', 'Assessing comprehensive risks...'); }
        function runTrendAnalysis(){ return runAIAnalysis('trend_analysis', 'Analyzing satisfaction trends...'); }
        function runPerformancePrediction(){ return runAIAnalysis('performance', 'Predicting performance...'); }
        function runDropoutRiskAssessment(){ return runAIAnalysis('dropout', 'Assessing dropout risk...'); }
        function runComprehensiveAnalytics(){ return runAIAnalysis('comprehensive', 'Running comprehensive analytics...'); }

        async function runAIAnalysis(type, loadingMessage){
            showLoading(loadingMessage);
            try {
                const res = await fetch(`/api/ai/analyze/${type}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({})
                });
                const json = await res.json();
                if (json?.success) {
                    displayResults(type, json.data ?? {});
                    showAlert('success', `${type.charAt(0).toUpperCase() + type.slice(1)} analysis completed successfully!`);
                } else {
                    showAlert('error', json?.message || 'Analysis failed');
                }
            } catch (err) {
                console.error('runAIAnalysis error:', err);
                showAlert('error', 'An error occurred during analysis');
            } finally { hideLoading(); }
        }

        /* ----------------------
           Result rendering
           ---------------------- */
        function renderItem(title, confidence, htmlContent, opts = {}){
            // Build a confidence badge with an icon whose color reflects the numeric level when possible
            let confHtml = '';
            if (confidence !== undefined && confidence !== null && String(confidence).trim() !== '') {
                // Try to extract a numeric value from the confidence string (e.g., "85%" -> 85)
                let confValue = null;
                if (typeof confidence === 'number') confValue = confidence;
                else if (typeof confidence === 'string') {
                    const m = confidence.match(/([0-9]+(?:\.[0-9]+)?)/);
                    if (m) confValue = parseFloat(m[1]);
                }

                let colorClass = 'conf-low';
                if (confValue !== null) {
                    if (confValue >= 70) colorClass = 'conf-high';
                    else if (confValue >= 40) colorClass = 'conf-mid';
                } else {
                    // default neutral color when no numeric value
                    colorClass = 'conf-mid';
                }

                confHtml = `<span class="confidence-badge-icon ${colorClass}" aria-hidden="true"></span><span>${confidence}</span>`;
            }

            return `
                <div class="result-item" style="${opts.style || ''}">
                    <div class="result-header">
                        <div class="result-title">${title}</div>
                        <div class="result-confidence">${confHtml}</div>
                    </div>
                    <div class="result-details">${htmlContent}</div>
                </div>
            `;
        }

        function displayResults(type, data){
            const container = document.getElementById('results-container');
            const resultsDiv = document.getElementById('ai-results');
            const parts = [];

            // Debug logging
            console.log('displayResults called with type:', type);
            console.log('data:', JSON.stringify(data, null, 2));

            switch(type){
                case 'compliance': {
                    const p = data.prediction || data || {};
                    console.log('compliance p:', JSON.stringify(p, null, 2));
                    const weighted = Number(p.weighted_score) || 0;
                    const prob = Number(p.prediction_probability ?? (weighted ? (weighted/5) : 0));
                    const confidence = Number(p.confidence) || 0;
                    const predictionLabel = typeof p.prediction === 'string' ? p.prediction : (p.prediction?.prediction || 'Unknown');
                    const html = `
                        <p><strong>Compliance Level:</strong> ${predictionLabel}</p>
                        <p><strong>Risk Level:</strong> ${p.risk_level ?? 'Unknown'}</p>
                        <p><strong>Weighted Score:</strong> ${typeof weighted === 'number' ? (weighted.toFixed ? weighted.toFixed(2) : weighted) : 'N/A'}/5.0</p>
                        <p><strong>Confidence:</strong> ${safePct(confidence)}</p>
                        <p style="margin-top: 10px; font-size: 12px; color: #666;">Based on learner needs, satisfaction, and safety metrics.</p>
                    `;
                    parts.push(renderItem('Compliance Prediction', safePct(prob), html));
                    break;
                }

                case 'sentiment': {
                    const arr = data.sentiment_analysis || data || [];
                    if (Array.isArray(arr) && arr.length){
                        const total = arr.length;
                        const pos = arr.filter(r => r.sentiment === 'positive').length;
                        const neu = arr.filter(r => r.sentiment === 'neutral').length;
                        const neg = arr.filter(r => r.sentiment === 'negative').length;
                        const summary = `
                            <p><strong>Positive:</strong> ${pos} (${safeNum(pos/total*100)}%)</p>
                            <p><strong>Neutral:</strong> ${neu} (${safeNum(neu/total*100)}%)</p>
                            <p><strong>Negative:</strong> ${neg} (${safeNum(neg/total*100)}%)</p>
                        `;
                        parts.push(renderItem('Overall Sentiment Analysis', `${total} Comments Analyzed`, summary, { style: 'background:linear-gradient(135deg, rgba(66, 133, 244, 0.1), rgba(255, 140, 0, 0.1));border-left:4px solid #4285f4;' }));

                        arr.slice(0,5).forEach((item, i) => {
                            const color = item.sentiment === 'positive' ? '#28a745' : item.sentiment === 'negative' ? '#dc3545' : '#ffc107';
                            const commentHtml = `
                                <p><strong>Confidence:</strong> ${safePct(item.confidence ?? 0)}</p>
                                ${item.probabilities ? `<p style="font-size:12px;color:#666;">Pos: ${safePct(item.probabilities.positive)} | Neu: ${safePct(item.probabilities.neutral)} | Neg: ${safePct(item.probabilities.negative)}</p>` : ''}
                            `;
                            parts.push(renderItem(`Comment ${i+1}`, `<span style="background:${color};padding:4px 8px;border-radius:12px;color:#fff;">${item.sentiment ?? 'Neutral'}</span>`, commentHtml));
                        });
                    } else {
                        parts.push(renderItem('Sentiment Analysis', '', '<p>No sentiment data available</p>'));
                    }
                    break;
                }

                case 'clustering': {
                    const c = data.clustering_result || data || {};
                    const html = `
                        <p><strong>Algorithm:</strong> ${c.algorithm ?? 'K-Means'}</p>
                        <p><strong>Total Students:</strong> ${c.total_samples ?? 0}</p>
                        <p><strong>Silhouette Score:</strong> ${c.metrics?.silhouette_score ?? 'N/A'}</p>
                        <p>Students have been grouped into ${c.clusters ?? 0} clusters based on survey responses.</p>
                    `;
                    parts.push(renderItem('Student Clustering Results', `${c.clusters ?? 0} Clusters Identified`, html));
                    if (Array.isArray(c.detailed_clusters)){
                        c.detailed_clusters.slice(0,3).forEach(cluster => {
                            const details = `
                                <p><strong>Risk Level:</strong> ${cluster.risk_profile?.risk_level ?? 'Unknown'}</p>
                                <p><strong>Avg Satisfaction:</strong> ${cluster.average_satisfaction ?? 'N/A'}/5</p>
                                <p><strong>Avg Performance:</strong> ${cluster.average_performance ?? 'N/A'}/4.0</p>
                                <p><strong>Characteristics:</strong> ${Array.isArray(cluster.characteristics) ? cluster.characteristics.join(', ') : 'N/A'}</p>
                            `;
                            parts.push(renderItem(`Cluster ${cluster.cluster_id}`, `${cluster.size ?? 0} Students (${cluster.percentage ?? 'N/A'}%)`, details));
                        });
                    }
                    if (Array.isArray(c.insights) && c.insights.length){
                        parts.push(renderItem('ISO 21001 Insights', 'Key Findings', `<ul style="margin:0;padding-left:20px;">${c.insights.map(x => `<li>${x}</li>`).join('')}</ul>`, { style: 'background:linear-gradient(135deg, rgba(66, 133, 244, 0.1), rgba(255, 140, 0, 0.1));border-left:4px solid #4285f4;' }));
                    }
                    break;
                }

                case 'performance': {
                    const p = data.prediction || data || {};
                    const conf = Number(p.confidence) || 0;
                    const predGpa = p.predicted_gpa;
                    const html = `
                        <p><strong>Predicted Performance:</strong> ${p.prediction ?? 'Unknown'}</p>
                        <p><strong>Predicted GPA:</strong> ${typeof predGpa === 'number' ? predGpa.toFixed(1) : (predGpa ?? 'N/A')}</p>
                        <p><strong>Risk Level:</strong> ${p.risk_level ?? 'Unknown'}</p>
                        <p><strong>Model Used:</strong> ${p.model_used ?? 'Unknown'}</p>
                    `;
                    parts.push(renderItem('Performance Prediction', safePct(conf), html));
                    break;
                }

                case 'dropout': {
                    const p = data.prediction || data || {};
                    const html = `
                        <p><strong>Risk Level:</strong> ${p.dropout_risk ?? 'Unknown'}</p>
                        <p><strong>Risk Score:</strong> ${p.risk_probability ? (Number(p.risk_probability * 100).toFixed(1) + '%') : 'N/A'}</p>
                        <p><strong>Intervention Urgency:</strong> ${p.intervention_urgency ?? 'Unknown'}</p>
                        <p><strong>Confidence:</strong> ${p.confidence ? (Number(p.confidence * 100).toFixed(1) + '%') : 'N/A'}</p>
                        ${Array.isArray(p.risk_factors) && p.risk_factors.length ? `<p><strong>Risk Factors:</strong> ${p.risk_factors.join(', ')}</p>` : ''}
                    `;
                    parts.push(renderItem('Dropout Risk Assessment', `${p.dropout_risk ?? 'Unknown'} Risk`, html));
                    break;
                }

                case 'predictive': {
                    const p = data.prediction || data || {};
                    const html = `
                        <p><strong>Current Performance:</strong> ${p.current_performance ?? 'N/A'}</p>
                        <p><strong>Predicted Trend:</strong> ${p.trend ?? 'N/A'}</p>
                        <p><strong>Confidence Level:</strong> ${p.confidence ? safePct(p.confidence) : 'N/A'}</p>
                        <p><strong>Forecast Period:</strong> Next 3 months</p>
                    `;
                    parts.push(renderItem('Predictive Analytics Results', 'Future Performance Forecast', html));
                    break;
                }

                case 'risk_assessment': {
                    const r = data.assessment || data || {};
                    const html = `
                        <p><strong>Risk Level:</strong> ${r.risk_level ?? 'Unknown'}</p>
                        <p><strong>Risk Category:</strong> ${r.risk_category ?? 'Unknown'}</p>
                        <p><strong>Compliance Impact:</strong> ${r.compliance_impact ?? 'Unknown'}</p>
                        <p><strong>Confidence:</strong> ${r.confidence ? safePct(r.confidence) : 'N/A'}</p>
                    `;
                    parts.push(renderItem('Comprehensive Risk Assessment', `Risk Score: ${r.overall_risk_score ?? 0}/100`, html));
                    if (r.risk_breakdown){
                        Object.entries(r.risk_breakdown).forEach(([k,v]) => {
                            const names = { learning_environment:'Learning Environment', academic_performance:'Academic Performance', safety:'Safety & Security', wellbeing:'Student Wellbeing', engagement:'Student Engagement' };
                            parts.push(renderItem(names[k] || k, `Risk: ${v}/100`, `<p>Risk score for ${names[k] || k} dimension.</p>`));
                        });
                    }
                    break;
                }

                case 'trend_analysis': {
                    const t = data.trend_prediction || data || {};
                    const html = `
                        <p><strong>Current Satisfaction:</strong> ${t.current_satisfaction ?? 'N/A'}/5.0</p>
                        <p><strong>Trend Direction:</strong> ${t.trend_direction ?? 'Unknown'}</p>
                        <p><strong>Trend Strength:</strong> ${t.trend_strength ?? 'Unknown'}</p>
                        <p><strong>Confidence:</strong> ${t.confidence ? safePct(t.confidence) : 'N/A'}</p>
                    `;
                    parts.push(renderItem('Satisfaction Trend Analysis', t.trend_direction ?? 'Stable', html));
                    if (Array.isArray(t.forecasted_satisfaction) && t.forecasted_satisfaction.length){
                        parts.push(renderItem('3-Month Forecast', 'Predicted Values', `<p><strong>Month 1:</strong> ${t.forecasted_satisfaction[0] ?? 'N/A'}/5.0</p><p><strong>Month 2:</strong> ${t.forecasted_satisfaction[1] ?? 'N/A'}/5.0</p><p><strong>Month 3:</strong> ${t.forecasted_satisfaction[2] ?? 'N/A'}/5.0</p>`));
                    }
                    break;
                }

                case 'comprehensive': {
                    parts.push(renderItem('Comprehensive Analytics', 'Complete', '<p>All AI models have been executed successfully.</p><p>Results include compliance, sentiment, clustering, performance, and risk assessments.</p>'));
                    const a = data.analytics_results || {};
                    if (a.compliance_prediction) parts.push(renderItem('Compliance Prediction', a.compliance_prediction.prediction_probability ? safePct(a.compliance_prediction.prediction_probability) : 'N/A', `<p><strong>Compliance Level:</strong> ${a.compliance_prediction.prediction ?? 'Unknown'}</p><p><strong>Risk Level:</strong> ${a.compliance_prediction.risk_level ?? 'Unknown'}</p>`));
                    if (Array.isArray(a.sentiment_analysis) && a.sentiment_analysis.length) parts.push(renderItem('Sentiment Analysis', `${a.sentiment_analysis.length} Comments`, `<p>Summary available</p>`));
                    if (a.student_clustering) parts.push(renderItem('Student Clustering', `${a.student_clustering.clusters ?? a.student_clustering.cluster_count ?? 0} Clusters`, `<p>Clustering summary available</p>`));
                    if (a.performance_prediction) parts.push(renderItem('Performance Prediction', a.performance_prediction.confidence ? safePct(a.performance_prediction.confidence) : 'N/A', `<p>Performance summary available</p>`));
                    if (a.dropout_risk_prediction) parts.push(renderItem('Dropout Risk Assessment', a.dropout_risk_prediction.dropout_risk ?? 'N/A', `<p>Dropout summary available</p>`));
                    break;
                }

                default:
                    parts.push(renderItem('Analysis Results', '', '<p>No results available for this analysis type.</p>'));
            }

            container.innerHTML = parts.join('');
            resultsDiv.style.display = parts.length ? 'block' : 'none';
        }

        /* ----------------------
           UI helpers
           ---------------------- */
        function showAlert(type, message){
            const container = document.getElementById('alert-container');
            const alertClass = type === 'success' ? 'alert-success' : 'alert-error';
            container.innerHTML = `<div class="alert ${alertClass}">${message}</div>`;
            setTimeout(()=> container.innerHTML = '', 5000);
        }

        function showLoading(message = 'Processing...'){
            const overlay = document.getElementById('loading-overlay');
            overlay.querySelector('p').textContent = message;
            overlay.classList.add('active');
        }

        function hideLoading(){ document.getElementById('loading-overlay').classList.remove('active'); }

        console.log('Enhanced AI Insights page loaded');
    </script>
</body>
</html>
