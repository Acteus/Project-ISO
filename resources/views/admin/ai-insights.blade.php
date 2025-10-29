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

        /* Confidence badge icon variants */
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
                <h1>AI Insights Dashboard</h1>
                <p>Comprehensive machine learning analytics for ISO 21001 compliance, predictive modeling, and proactive quality management</p>
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
                    el.textContent = 'Online'; el.style.color = '#28a745';
                } else {
                    el.textContent = 'Offline'; el.style.color = '#dc3545';
                }
            } catch (err) {
                console.error('AI Service Status Check Error:', err);
                const el = document.getElementById('service-status');
                el.textContent = 'Error'; el.style.color = '#ffc107';
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
                }
            } catch (err) {
                console.error('Error loading AI metrics:', err);
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
                        parts.push(renderItem('Overall Sentiment Analysis', `${total} Comments Analyzed`, summary, { style: 'background:#f0f8ff;border-left:4px solid #4285f4;' }));

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
                        parts.push(renderItem('ISO 21001 Insights', 'Key Findings', `<ul style="margin:0;padding-left:20px;">${c.insights.map(x => `<li>${x}</li>`).join('')}</ul>`, { style: 'background:#f0f8ff;border-left:4px solid #4285f4;' }));
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
            document.getElementById('loading-overlay').innerHTML = `
                <div style="text-align: center; color: white;">
                    <div class="loading-spinner"></div>
                    <p style="margin-top: 20px; font-size: 16px;">${message}</p>
                </div>
            `;
            document.getElementById('loading-overlay').classList.add('active');
        }

        function hideLoading(){ document.getElementById('loading-overlay').classList.remove('active'); }

        console.log('AI Insights page loaded');
    </script>
</body>
</html>
