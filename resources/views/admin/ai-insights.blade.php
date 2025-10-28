<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Insights - ISO Quality Education</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            background: linear-gradient(135deg, rgba(66, 133, 244, 1), rgba(255, 215, 0, 1));
            min-height: 100vh;
        }

        .survey-main {
            background-image: none !important;
        }

        .insights-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .insights-header {
            background: white;
            color: black;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .insights-header h1 {
            margin: 0 0 15px 0;
            font-size: 28px;
            font-weight: 700;
            line-height: 1.2;
            color: black;
        }

        .insights-header p {
            margin: 0;
            font-size: 16px;
            line-height: 1.5;
            color: #666;
        }

        .back-btn {
            background: white;
            color: black;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 2px solid white;
            display: inline-block;
            margin-bottom: 20px;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.9);
            color: black;
        }

        .insights-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 30px;
            margin-bottom: 30px;
        }

        .insight-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .insight-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        }

        .insight-card h3 {
            margin-top: 0;
            color: #333;
            font-size: 24px;
            border-bottom: 3px solid rgba(66,133,244,1);
            padding-bottom: 15px;
        }

        .insight-card p {
            color: #666;
            margin-bottom: 20px;
        }

        .ai-metrics {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .metric-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .metric-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, rgba(66, 133, 244, 1), rgba(255, 215, 0, 1));
        }

        .metric-card:hover {
            transform: translateY(-5px);
        }

        .metric-value {
            font-size: 48px;
            font-weight: 700;
            color: rgba(66, 133, 244, 1);
            margin-bottom: 10px;
        }

        .metric-label {
            font-size: 16px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
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

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40,167,69,0.4);
        }

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

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid;
        }

        .alert-success {
            background: #d4edda;
            border-color: #28a745;
            color: #155724;
        }

        .alert-error {
            background: #f8d7da;
            border-color: #dc3545;
            color: #721c24;
        }

        .alert-warning {
            background: #fff3cd;
            border-color: #ffc107;
            color: #856404;
        }

        .ai-results {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .ai-results h3 {
            margin-top: 0;
            color: #333;
            border-bottom: 2px solid rgba(66, 133, 244, 1);
            padding-bottom: 10px;
        }

        .result-item {
            padding: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            margin-bottom: 15px;
            background: #fafafa;
        }

        .result-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .result-title {
            font-weight: 600;
            color: #333;
        }

        .result-confidence {
            background: rgba(66, 133, 244, 1);
            color: white;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
        }

        .result-details {
            color: #666;
            font-size: 14px;
            line-height: 1.5;
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
                    <a href="{{ route('admin.ai.insights') }}" class="nav-link active">AI Insights</a>
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
        <div class="insights-container">
            <a href="{{ route('admin.dashboard') }}" class="back-btn">← Back to Dashboard</a>

            <div class="insights-header">
                <h1>🤖 AI Insights Dashboard</h1>
                <p>Advanced machine learning analytics for ISO 21001 compliance and student success prediction</p>
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
            </div>

            <!-- AI Analysis Tools -->
            <div class="insights-grid">
                <!-- Compliance Prediction -->
                <div class="insight-card">
                    <h3>📊 Compliance Prediction</h3>
                    <p>AI-powered prediction of ISO 21001 compliance levels based on learner feedback and performance metrics.</p>
                    <button type="button" class="btn btn-primary" onclick="runCompliancePrediction()">Run Prediction</button>
                </div>

                <!-- Sentiment Analysis -->
                <div class="insight-card">
                    <h3>💬 Sentiment Analysis</h3>
                    <p>Analyze student feedback sentiment using advanced NLP models to identify positive and negative trends.</p>
                    <button type="button" class="btn btn-primary" onclick="runSentimentAnalysis()">Analyze Sentiment</button>
                </div>

                <!-- Student Clustering -->
                <div class="insight-card">
                    <h3>👥 Student Clustering</h3>
                    <p>Group students based on survey responses for targeted interventions and personalized support.</p>
                    <button type="button" class="btn btn-primary" onclick="runStudentClustering()">Cluster Students</button>
                </div>

                <!-- Performance Prediction -->
                <div class="insight-card">
                    <h3>📈 Performance Prediction</h3>
                    <p>Predict student academic performance and identify at-risk students early.</p>
                    <button type="button" class="btn btn-primary" onclick="runPerformancePrediction()">Predict Performance</button>
                </div>

                <!-- Dropout Risk Assessment -->
                <div class="insight-card">
                    <h3>⚠️ Dropout Risk Assessment</h3>
                    <p>Identify students at risk of dropping out using machine learning algorithms.</p>
                    <button type="button" class="btn btn-primary" onclick="runDropoutRiskAssessment()">Assess Risk</button>
                </div>

                <!-- Comprehensive Analytics -->
                <div class="insight-card">
                    <h3>🔍 Comprehensive Analytics</h3>
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
        <div class="loading-spinner"></div>
    </div>

    <!-- Footer -->
    <footer class="footer" style="margin-top: 50px; padding: 20px; background: #f8f9fa; text-align: center; color: #666;">
        <div class="container">
            <div class="footer-content">
                <div class="footer-main">
                    <h3 class="footer-title">ISO Learner-Centric Quality Education</h3>
                    <p class="footer-description">
                        Empowering CSS Students through Learner-Centric Quality Education
                    </p>
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
        // Set current year
        document.getElementById('currentYear').textContent = new Date().getFullYear();

        // CSRF Token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            checkAIServiceStatus();
            loadAIMetrics();
        });

        // Check AI service status
        async function checkAIServiceStatus() {
            try {
                const response = await fetch('/api/ai/service-status', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                const result = await response.json();
                const statusElement = document.getElementById('service-status');

                if (result.success && result.data && result.data.available) {
                    statusElement.textContent = 'Online';
                    statusElement.style.color = '#28a745';
                } else {
                    statusElement.textContent = 'Offline';
                    statusElement.style.color = '#dc3545';
                }
            } catch (error) {
                console.error('AI Service Status Check Error:', error);
                document.getElementById('service-status').textContent = 'Error';
                document.getElementById('service-status').style.color = '#ffc107';
            }
        }

        // Load AI metrics
        async function loadAIMetrics() {
            try {
                const response = await fetch('/api/ai/metrics', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                const result = await response.json();
                if (result.success) {
                    document.getElementById('total-predictions').textContent = result.data.total_predictions || 0;
                    document.getElementById('accuracy-rate').textContent = (result.data.accuracy_rate || 0) + '%';
                    document.getElementById('response-time').textContent = (result.data.avg_response_time || 0) + 'ms';
                }
            } catch (error) {
                console.error('Error loading AI metrics:', error);
            }
        }

        // AI Analysis Functions
        async function runCompliancePrediction() {
            await runAIAnalysis('compliance', 'Predicting compliance levels...');
        }

        async function runSentimentAnalysis() {
            await runAIAnalysis('sentiment', 'Analyzing sentiment...');
        }

        async function runStudentClustering() {
            await runAIAnalysis('clustering', 'Clustering students...');
        }

        async function runPerformancePrediction() {
            await runAIAnalysis('performance', 'Predicting performance...');
        }

        async function runDropoutRiskAssessment() {
            await runAIAnalysis('dropout', 'Assessing dropout risk...');
        }

        async function runComprehensiveAnalytics() {
            await runAIAnalysis('comprehensive', 'Running comprehensive analytics...');
        }

        async function runAIAnalysis(type, loadingMessage) {
            showLoading(loadingMessage);

            try {
                const response = await fetch(`/api/ai/analyze/${type}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        // Add any required parameters based on the analysis type
                    })
                });

                const result = await response.json();

                if (result.success) {
                    displayResults(type, result.data);
                    showAlert('success', `${type.charAt(0).toUpperCase() + type.slice(1)} analysis completed successfully!`);
                } else {
                    showAlert('error', result.message || 'Analysis failed');
                }
            } catch (error) {
                showAlert('error', 'An error occurred during analysis');
            } finally {
                hideLoading();
            }
        }

        function displayResults(type, data) {
            const container = document.getElementById('results-container');
            const resultsDiv = document.getElementById('ai-results');

            let html = '';

            switch(type) {
                case 'compliance':
                    // Extract the numeric score
                    const predictionData = data.prediction || data;
                    const weightedScore = predictionData.weighted_score || 0;
                    const predictionProb = predictionData.prediction_probability || (weightedScore / 5);
                    const complianceText = predictionData.prediction || 'Unknown';
                    const riskLevel = predictionData.risk_level || 'Unknown';
                    const confidence = predictionData.confidence || 0;
                    const percentage = Math.round(predictionProb * 100);

                    html = `
                        <div class="result-item">
                            <div class="result-header">
                                <div class="result-title">Compliance Prediction</div>
                                <div class="result-confidence">${percentage}% Probability</div>
                            </div>
                            <div class="result-details">
                                <p><strong>Compliance Level:</strong> ${complianceText}</p>
                                <p><strong>Risk Level:</strong> ${riskLevel}</p>
                                <p><strong>Weighted Score:</strong> ${weightedScore.toFixed(2)}/5.0</p>
                                <p><strong>Confidence:</strong> ${Math.round(confidence * 100)}%</p>
                                <p style="margin-top: 10px; font-size: 12px; color: #666;">Based on learner needs, satisfaction, and safety metrics.</p>
                            </div>
                        </div>
                    `;
                    break;

                case 'sentiment':
                    const sentimentResults = data.sentiment_analysis || data;

                    if (Array.isArray(sentimentResults) && sentimentResults.length > 0) {
                        // Overall summary
                        const totalPositive = sentimentResults.filter(r => r.sentiment === 'positive').length;
                        const totalNeutral = sentimentResults.filter(r => r.sentiment === 'neutral').length;
                        const totalNegative = sentimentResults.filter(r => r.sentiment === 'negative').length;
                        const total = sentimentResults.length;

                        html = `
                            <div class="result-item" style="background: #f0f8ff; border-left: 4px solid #4285f4;">
                                <div class="result-header">
                                    <div class="result-title">Overall Sentiment Analysis</div>
                                    <div class="result-confidence">${total} Comments Analyzed</div>
                                </div>
                                <div class="result-details">
                                    <p><strong>Positive:</strong> ${totalPositive} (${Math.round(totalPositive/total*100)}%)</p>
                                    <p><strong>Neutral:</strong> ${totalNeutral} (${Math.round(totalNeutral/total*100)}%)</p>
                                    <p><strong>Negative:</strong> ${totalNegative} (${Math.round(totalNegative/total*100)}%)</p>
                                </div>
                            </div>
                        `;

                        // Individual comments
                        html += sentimentResults.slice(0, 5).map((item, index) => {
                            const sentimentColor = item.sentiment === 'positive' ? '#28a745' :
                                                  item.sentiment === 'negative' ? '#dc3545' : '#ffc107';
                            return `
                                <div class="result-item">
                                    <div class="result-header">
                                        <div class="result-title">Comment ${index + 1}</div>
                                        <div class="result-confidence" style="background: ${sentimentColor};">${item.sentiment || 'Neutral'}</div>
                                    </div>
                                    <div class="result-details">
                                        <p><strong>Confidence:</strong> ${Math.round((item.confidence || 0) * 100)}%</p>
                                        ${item.probabilities ? `
                                            <p style="font-size: 12px; color: #666;">
                                                Pos: ${Math.round(item.probabilities.positive * 100)}% |
                                                Neu: ${Math.round(item.probabilities.neutral * 100)}% |
                                                Neg: ${Math.round(item.probabilities.negative * 100)}%
                                            </p>
                                        ` : ''}
                                    </div>
                                </div>
                            `;
                        }).join('');

                        if (total > 5) {
                            html += `<p style="text-align: center; color: #666; margin-top: 15px;">Showing 5 of ${total} analyzed comments</p>`;
                        }
                    } else {
                        html = '<div class="result-item"><p>No sentiment data available</p></div>';
                    }
                    break;

                case 'clustering':
                    html = `
                        <div class="result-item">
                            <div class="result-header">
                                <div class="result-title">Student Clustering Results</div>
                                <div class="result-confidence">${data.clusters || 0} Clusters</div>
                            </div>
                            <div class="result-details">
                                <p>Students have been grouped into ${data.clusters || 0} clusters based on survey responses.</p>
                                <p>This enables targeted interventions for different student groups.</p>
                            </div>
                        </div>
                    `;
                    break;

                case 'performance':
                    html = `
                        <div class="result-item">
                            <div class="result-header">
                                <div class="result-title">Performance Prediction</div>
                                <div class="result-confidence">${Math.round((data.prediction || 0) * 100)}% Success Rate</div>
                            </div>
                            <div class="result-details">
                                <p>Predicted academic performance score: ${data.prediction || 'N/A'}</p>
                                <p>Based on curriculum relevance, teaching quality, and participation metrics.</p>
                            </div>
                        </div>
                    `;
                    break;

                case 'dropout':
                    html = `
                        <div class="result-item">
                            <div class="result-header">
                                <div class="result-title">Dropout Risk Assessment</div>
                                <div class="result-confidence">${data.risk_level || 'Unknown'} Risk</div>
                            </div>
                            <div class="result-details">
                                <p>Risk score: ${data.prediction || 'N/A'}</p>
                                <p>Early intervention recommended for at-risk students.</p>
                            </div>
                        </div>
                    `;
                    break;

                case 'comprehensive':
                    html = `
                        <div class="result-item">
                            <div class="result-header">
                                <div class="result-title">Comprehensive Analytics</div>
                                <div class="result-confidence">Complete</div>
                            </div>
                            <div class="result-details">
                                <p>All AI models have been executed successfully.</p>
                                <p>Results include compliance, sentiment, clustering, performance, and risk assessments.</p>
                            </div>
                        </div>
                    `;
                    break;
            }

            container.innerHTML = html;
            resultsDiv.style.display = 'block';
        }

        function showAlert(type, message) {
            const container = document.getElementById('alert-container');
            const alertClass = type === 'success' ? 'alert-success' : 'alert-error';
            container.innerHTML = `<div class="alert ${alertClass}">${message}</div>`;

            // Auto-hide after 5 seconds
            setTimeout(() => {
                container.innerHTML = '';
            }, 5000);
        }

        function showLoading(message = 'Processing...') {
            document.getElementById('loading-overlay').innerHTML = `
                <div style="text-align: center; color: white;">
                    <div class="loading-spinner"></div>
                    <p style="margin-top: 20px; font-size: 16px;">${message}</p>
                </div>
            `;
            document.getElementById('loading-overlay').classList.add('active');
        }

        function hideLoading() {
            document.getElementById('loading-overlay').classList.remove('active');
        }

        console.log('AI Insights page loaded');
    </script>
</body>
</html>
