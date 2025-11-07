<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indirect Metrics Upload - ISO Quality Education</title>
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

        /* Enhanced Modern Styles */
        body {
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #e8f4f8;
            background-image:
                repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(66, 133, 244, 0.03) 10px, rgba(66, 133, 244, 0.03) 20px),
                repeating-linear-gradient(-45deg, transparent, transparent 10px, rgba(255, 193, 7, 0.02) 10px, rgba(255, 193, 7, 0.02) 20px),
                radial-gradient(circle at 25% 25%, rgba(66, 133, 244, 0.04) 2px, transparent 2px),
                radial-gradient(circle at 75% 75%, rgba(255, 193, 7, 0.04) 2px, transparent 2px),
                linear-gradient(135deg, rgba(179, 217, 255, 0.4) 0%, rgba(255, 233, 179, 0.3) 50%, rgba(179, 229, 252, 0.4) 100%);
            background-size: 100% 100%, 100% 100%, 20px 20px, 20px 20px, 100% 100%;
            background-attachment: fixed;
        }

        .survey-main {
            background: transparent;
            backdrop-filter: none;
        }

        .metrics-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px;
        }

        .metrics-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            color: #333;
            padding: 40px 30px;
            border-radius: 20px;
            margin-bottom: 40px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(156, 39, 176, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
        }

        .metrics-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #9C27B0, #673AB7, #4285F4);
        }

        .metrics-header h1 {
            margin: 0 0 20px 0;
            font-size: 32px;
            font-weight: 800;
            line-height: 1.3;
            color: #2c3e50;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .metrics-header p {
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
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .stats-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 30px;
            border-radius: 18px;
            box-shadow: 0 15px 40px rgba(156, 39, 176, 0.15);
            margin-bottom: 40px;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .stat-item {
            text-align: center;
            padding: 20px;
            background: linear-gradient(135deg, rgba(156, 39, 176, 0.05), rgba(103, 58, 183, 0.05));
            border-radius: 12px;
            border: 1px solid rgba(156, 39, 176, 0.1);
        }

        .stat-value {
            font-size: 36px;
            font-weight: 900;
            background: linear-gradient(135deg, #9C27B0, #673AB7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 8px;
        }

        .stat-label {
            font-size: 14px;
            color: #5a6c7d;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .upload-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(156, 39, 176, 0.15);
            margin-bottom: 40px;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .upload-section h2 {
            margin-top: 0;
            color: #2c3e50;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 25px;
            border-bottom: 3px solid transparent;
            border-image: linear-gradient(90deg, #9C27B0, #673AB7) 1;
            padding-bottom: 15px;
        }

        .upload-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 30px;
        }

        .upload-option {
            background: linear-gradient(135deg, rgba(156, 39, 176, 0.05), rgba(103, 58, 183, 0.05));
            padding: 30px;
            border-radius: 16px;
            border: 2px solid rgba(156, 39, 176, 0.1);
        }

        .upload-option h3 {
            margin-top: 0;
            color: #9C27B0;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .upload-option p {
            color: #5a6c7d;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid rgba(156, 39, 176, 0.2);
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: white;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #9C27B0;
            box-shadow: 0 0 0 3px rgba(156, 39, 176, 0.1);
        }

        .file-input-wrapper {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .file-input-wrapper input[type="file"] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-input-label {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            border: 2px dashed rgba(156, 39, 176, 0.3);
            border-radius: 12px;
            background: rgba(156, 39, 176, 0.02);
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }

        .file-input-label:hover {
            border-color: #9C27B0;
            background: rgba(156, 39, 176, 0.05);
        }

        .file-input-label.has-file {
            border-color: #9C27B0;
            background: rgba(156, 39, 176, 0.1);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 14px 28px;
            border: none;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: linear-gradient(135deg, #9C27B0, #673AB7);
            color: white;
            box-shadow: 0 8px 25px rgba(156, 39, 176, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 12px 35px rgba(156, 39, 176, 0.6);
            color: white;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d, #5a6268);
            color: white;
            box-shadow: 0 6px 20px rgba(108, 117, 125, 0.3);
        }

        .btn-secondary:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 10px 30px rgba(108, 117, 125, 0.5);
            color: white;
        }

        .btn-success {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
        }

        .btn-success:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 12px 35px rgba(40, 167, 69, 0.6);
            color: white;
        }

        .alert {
            padding: 20px 25px;
            margin-bottom: 25px;
            border-radius: 16px;
            border: none;
            backdrop-filter: blur(15px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-left: 5px solid;
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(32, 201, 151, 0.1));
            border-left-color: #28a745;
            color: #155724;
        }

        .alert-error {
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.1), rgba(232, 62, 97, 0.1));
            border-left-color: #dc3545;
            color: #721c24;
        }

        .alert-warning {
            background: linear-gradient(135deg, rgba(255, 193, 7, 0.1), rgba(255, 152, 0, 0.1));
            border-left-color: #ffc107;
            color: #856404;
        }

        .errors-list {
            margin-top: 15px;
            padding: 15px;
            background: rgba(220, 53, 69, 0.05);
            border-radius: 8px;
            max-height: 200px;
            overflow-y: auto;
        }

        .errors-list ul {
            margin: 0;
            padding-left: 20px;
        }

        .errors-list li {
            margin-bottom: 5px;
            font-size: 13px;
        }

        .header {
            background: linear-gradient(135deg, #1e5a9e 0%, #0d3a6b 100%) !important;
            border-bottom: none;
            box-shadow: 0 4px 15px rgba(30, 90, 158, 0.3);
        }

        .logo a {
            color: #ffff !important;
            font-weight: 800;
        }

        .nav-link {
            color: #ffff !important;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .nav-link:hover {
            color: #ffff !important;
            transform: translateY(-2px);
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        .nav-link.active {
            color: #FFD700 !important;
            font-weight: 700;
            text-shadow: 0 2px 8px rgba(255, 215, 0, 0.5);
        }

        @media (max-width: 768px) {
            .metrics-container {
                padding: 20px;
            }

            .upload-options {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header admin-header">
        <div class="container">
            <div class="nav-wrapper">
                <div class="logo">
                    <a href="{{ route('welcome') }}">ISO Quality Education</a>
                </div>

                <!-- Desktop navigation -->
                <nav class="desktop-nav">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">Dashboard</a>
                    <a href="{{ route('admin.indirect-metrics.index') }}" class="nav-link active">Indirect Metrics</a>
                    <a href="{{ route('api.survey.analytics') }}" class="nav-link" target="_blank">Analytics</a>
                    <a href="{{ route('admin.ai.insights') }}" class="nav-link">AI Insights</a>
                    <a href="{{ route('admin.reports') }}" class="nav-link">Reports</a>
                    <form method="POST" action="{{ route('student.logout') }}" style="display: inline;" onsubmit="handleAdminLogout(event)">
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
        <div class="metrics-container">
            <a href="{{ route('admin.dashboard') }}" class="back-btn">← Back to Dashboard</a>

            <!-- Header -->
            <div class="metrics-header">
                <h1>Upload Indirect Metrics</h1>
                <p>Upload student performance metrics (grades, attendance, participation) to enable ISO 21001 direct vs indirect validation. These metrics are cross-referenced with learner feedback for comprehensive quality assessment.</p>
            </div>

            <!-- Statistics -->
            <div class="stats-card">
                <h2 style="margin-top: 0; color: #2c3e50; font-size: 24px; font-weight: 700; margin-bottom: 25px;">Metrics Coverage</h2>
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-value">{{ $totalResponses }}</div>
                        <div class="stat-label">Total Responses</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ $responsesWithMetrics }}</div>
                        <div class="stat-label">With Metrics</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ $coveragePercentage }}%</div>
                        <div class="stat-label">Coverage</div>
                    </div>
                </div>
            </div>

            <!-- Alerts -->
            @if(session('success'))
                <div class="alert alert-success">
                    <strong>Success!</strong> {{ session('success') }}
                    @if(session('errors') && count(session('errors')) > 0)
                        <div class="errors-list">
                            <strong>Errors encountered:</strong>
                            <ul>
                                @foreach(session('errors') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <strong>Error!</strong> {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <strong>Validation Errors:</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Upload Options -->
            <div class="upload-section">
                <h2>Upload Methods</h2>
                <div class="upload-options">
                    <!-- CSV Upload -->
                    <div class="upload-option">
                        <h3>📊 CSV Bulk Upload</h3>
                        <p>Upload indirect metrics for multiple students at once using a CSV file. Download the template to see the required format.</p>
                        
                        <form action="{{ route('admin.indirect-metrics.upload-csv') }}" method="POST" enctype="multipart/form-data" id="csvUploadForm">
                            @csrf
                            <div class="form-group">
                                <label>CSV File</label>
                                <div class="file-input-wrapper">
                                    <input type="file" name="csv_file" id="csv_file" accept=".csv,.txt" required>
                                    <label for="csv_file" class="file-input-label" id="fileLabel">
                                        <span>📁 Click to select CSV file or drag and drop</span>
                                    </label>
                                </div>
                                <small style="display: block; margin-top: 8px; color: #6c757d;">Required columns: student_id, attendance_rate, grade_average, participation_score, extracurricular_hours, counseling_sessions</small>
                            </div>
                            
                            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                                <button type="submit" class="btn btn-primary">Upload CSV</button>
                                <a href="{{ route('admin.indirect-metrics.template') }}" class="btn btn-secondary">Download Template</a>
                            </div>
                        </form>
                    </div>

                    <!-- Manual Entry -->
                    <div class="upload-option">
                        <h3>✏️ Manual Entry</h3>
                        <p>Enter indirect metrics for a single student manually. All survey responses for that student will be updated.</p>
                        
                        <form action="{{ route('admin.indirect-metrics.update-manual') }}" method="POST" id="manualForm">
                            @csrf
                            <div class="form-group">
                                <label>Student ID *</label>
                                <input type="text" name="student_id" required placeholder="e.g., STU123456">
                            </div>
                            
                            <div class="form-group">
                                <label>Attendance Rate (%)</label>
                                <input type="number" name="attendance_rate" step="0.01" min="0" max="100" placeholder="0-100">
                            </div>
                            
                            <div class="form-group">
                                <label>Grade Average (GPA)</label>
                                <input type="number" name="grade_average" step="0.01" min="0" max="5" placeholder="0-5">
                            </div>
                            
                            <div class="form-group">
                                <label>Participation Score</label>
                                <input type="number" name="participation_score" min="0" max="100" placeholder="0-100">
                            </div>
                            
                            <div class="form-group">
                                <label>Extracurricular Hours</label>
                                <input type="number" name="extracurricular_hours" min="0" placeholder="Monthly hours">
                            </div>
                            
                            <div class="form-group">
                                <label>Counseling Sessions</label>
                                <input type="number" name="counseling_sessions" min="0" placeholder="Number of sessions">
                            </div>
                            
                            <button type="submit" class="btn btn-success">Update Metrics</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Information Section -->
            <div class="stats-card">
                <h2 style="margin-top: 0; color: #2c3e50; font-size: 24px; font-weight: 700; margin-bottom: 20px;">📋 About Indirect Metrics</h2>
                <div style="color: #5a6c7d; line-height: 1.8;">
                    <p><strong>Indirect metrics</strong> are objective performance indicators that complement the direct feedback collected from learners through surveys. These metrics are essential for ISO 21001 compliance validation.</p>
                    
                    <h3 style="color: #9C27B0; margin-top: 25px; margin-bottom: 15px;">Metrics Included:</h3>
                    <ul style="margin-left: 20px;">
                        <li><strong>Attendance Rate:</strong> Percentage of classes attended (0-100%)</li>
                        <li><strong>Grade Average:</strong> Overall GPA or grade point average (0-5 scale)</li>
                        <li><strong>Participation Score:</strong> Class participation rating (0-100)</li>
                        <li><strong>Extracurricular Hours:</strong> Monthly hours spent in extracurricular activities</li>
                        <li><strong>Counseling Sessions:</strong> Number of counseling sessions attended</li>
                    </ul>

                    <h3 style="color: #9C27B0; margin-top: 25px; margin-bottom: 15px;">ISO 21001 Validation:</h3>
                    <p>These metrics are cross-referenced with learner satisfaction ratings to:</p>
                    <ul style="margin-left: 20px;">
                        <li>Identify discrepancies between self-reported satisfaction and actual performance</li>
                        <li>Validate the accuracy of learner feedback</li>
                        <li>Provide comprehensive quality assessment for accreditation</li>
                        <li>Enable data-driven decision making for continuous improvement</li>
                    </ul>
                </div>
            </div>
        </div>
    </main>

    <script>
        // File input label update
        document.getElementById('csv_file').addEventListener('change', function(e) {
            const label = document.getElementById('fileLabel');
            if (e.target.files.length > 0) {
                label.classList.add('has-file');
                label.innerHTML = `<span>📄 ${e.target.files[0].name}</span>`;
            } else {
                label.classList.remove('has-file');
                label.innerHTML = `<span>📁 Click to select CSV file or drag and drop</span>`;
            }
        });

        // Form submission loading states
        document.getElementById('csvUploadForm').addEventListener('submit', function(e) {
            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.textContent = 'Uploading...';
        });

        document.getElementById('manualForm').addEventListener('submit', function(e) {
            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.textContent = 'Updating...';
        });
    </script>

    @include('partials.admin-logout-modal')
</body>
</html>

