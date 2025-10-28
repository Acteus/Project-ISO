<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Management - ISO Quality Education</title>
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

        .reports-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .reports-header {
            background: white;
            color: black;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .reports-header h1 {
            margin: 0 0 15px 0;
            font-size: 28px;
            font-weight: 700;
            line-height: 1.2;
            color: black;
        }

        .reports-header p {
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

        .reports-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 30px;
            margin-bottom: 30px;
        }

        .report-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        }

        .report-card h3 {
            margin-top: 0;
            color: #333;
            font-size: 24px;
            border-bottom: 3px solid rgba(66,133,244,1);
            padding-bottom: 15px;
        }

        .report-form {
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #555;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-group select,
        .form-group input[type="email"],
        .form-group input[type="date"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .form-group select:focus,
        .form-group input:focus {
            border-color: rgba(66,133,244,1);
            outline: none;
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
            background: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108,117,125,0.4);
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

        .preview-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            border-left: 4px solid rgba(66,133,244,1);
        }

        .preview-section h4 {
            margin-top: 0;
            color: #333;
            font-size: 18px;
        }

        .preview-data {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .preview-item {
            background: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }

        .preview-value {
            font-size: 24px;
            font-weight: 700;
            color: rgba(66,133,244,1);
            margin-bottom: 5px;
        }

        .preview-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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
                    <a href="{{ route('admin.reports') }}" class="nav-link active">Reports</a>
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
        <div class="reports-container">
            <a href="{{ route('admin.dashboard') }}" class="back-btn">← Back to Dashboard</a>

            <div class="reports-header">
                <h1>Report Management</h1>
                <p>Send weekly progress reports and monthly compliance reports to administrators</p>
            </div>

            <!-- Alert Messages -->
            <div id="alert-container"></div>

            <!-- Reports Grid -->
            <div class="reports-grid">
                <!-- Weekly Progress Report -->
                <div class="report-card">
                    <h3>Weekly Progress Report</h3>
                    <p style="color: #666; margin-bottom: 20px;">Send automated weekly progress summaries with key metrics and insights.</p>

                    <form id="weekly-report-form" class="report-form">
                        <div class="form-group">
                            <label for="weekly_recipient">Recipient Email</label>
                            <select id="weekly_recipient" name="recipient_email" required>
                                <option value="">Select Administrator</option>
                                @foreach($admins as $admin)
                                    <option value="{{ $admin->email }}">{{ $admin->name }} ({{ $admin->email }})</option>
                                @endforeach
                                <option value="custom">Custom Email Address</option>
                            </select>
                        </div>

                        <div class="form-group" id="custom-weekly-email" style="display: none;">
                            <label for="custom_weekly_email">Custom Email Address</label>
                            <input type="email" id="custom_weekly_email" name="custom_email" placeholder="Enter email address">
                        </div>

                        <div class="form-group">
                            <label for="report_week_month">Report Month</label>
                            <select id="report_week_month" name="month" required>
                                <option value="">Select Month</option>
                                <option value="January 2025">January 2025</option>
                                <option value="February 2025">February 2025</option>
                                <option value="March 2025">March 2025</option>
                                <option value="April 2025">April 2025</option>
                                <option value="May 2025">May 2025</option>
                                <option value="June 2025">June 2025</option>
                                <option value="July 2025">July 2025</option>
                                <option value="August 2025">August 2025</option>
                                <option value="September 2025">September 2025</option>
                                <option value="October 2025">October 2025</option>
                                <option value="November 2025">November 2025</option>
                                <option value="December 2025">December 2025</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="week_number">Week Number</label>
                            <select id="week_number" name="week_number" required>
                                <option value="">Select Week</option>
                                <option value="1">Week 1</option>
                                <option value="2">Week 2</option>
                                <option value="3">Week 3</option>
                                <option value="4">Week 4</option>
                                <option value="5">Week 5 (if applicable)</option>
                            </select>
                        </div>

                        <div style="display: flex; gap: 10px;">
                            <button type="button" class="btn btn-secondary" onclick="previewWeeklyReport()">Preview Report</button>
                            <button type="submit" class="btn btn-primary">Send Weekly Report</button>
                        </div>
                    </form>

                    <!-- Weekly Preview Section -->
                    <div id="weekly-preview" class="preview-section" style="display: none;">
                        <h4>Report Preview</h4>
                        <div id="weekly-preview-data" class="preview-data">
                            <!-- Preview data will be populated here -->
                        </div>
                    </div>
                </div>

                <!-- Monthly Compliance Report -->
                <div class="report-card">
                    <h3>Monthly Compliance Report</h3>
                    <p style="color: #666; margin-bottom: 20px;">Send comprehensive monthly compliance reports with detailed analytics and trends.</p>

                    <form id="monthly-report-form" class="report-form">
                        <div class="form-group">
                            <label for="monthly_recipient">Recipient Email</label>
                            <select id="monthly_recipient" name="recipient_email" required>
                                <option value="">Select Administrator</option>
                                @foreach($admins as $admin)
                                    <option value="{{ $admin->email }}">{{ $admin->name }} ({{ $admin->email }})</option>
                                @endforeach
                                <option value="custom">Custom Email Address</option>
                            </select>
                        </div>

                        <div class="form-group" id="custom-monthly-email" style="display: none;">
                            <label for="custom_monthly_email">Custom Email Address</label>
                            <input type="email" id="custom_monthly_email" name="custom_email" placeholder="Enter email address">
                        </div>

                        <div class="form-group">
                            <label for="report_month">Report Month</label>
                            <select id="report_month" name="month" required>
                                <option value="">Select Month</option>
                                <option value="January 2025">January 2025</option>
                                <option value="February 2025">February 2025</option>
                                <option value="March 2025">March 2025</option>
                                <option value="April 2025">April 2025</option>
                                <option value="May 2025">May 2025</option>
                                <option value="June 2025">June 2025</option>
                                <option value="July 2025">July 2025</option>
                                <option value="August 2025">August 2025</option>
                                <option value="September 2025">September 2025</option>
                                <option value="October 2025">October 2025</option>
                                <option value="November 2025">November 2025</option>
                                <option value="December 2025">December 2025</option>
                            </select>
                        </div>

                        <div style="display: flex; gap: 10px;">
                            <button type="button" class="btn btn-secondary" onclick="previewMonthlyReport()">Preview Report</button>
                            <button type="submit" class="btn btn-success">Send Monthly Report</button>
                        </div>
                    </form>

                    <!-- Monthly Preview Section -->
                    <div id="monthly-preview" class="preview-section" style="display: none;">
                        <h4>Report Preview</h4>
                        <div id="monthly-preview-data" class="preview-data">
                            <!-- Preview data will be populated here -->
                        </div>
                    </div>
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

        // Show/hide custom email fields
        document.getElementById('weekly_recipient').addEventListener('change', function() {
            const customField = document.getElementById('custom-weekly-email');
            customField.style.display = this.value === 'custom' ? 'block' : 'none';
            if (this.value === 'custom') {
                document.getElementById('custom_weekly_email').required = true;
            } else {
                document.getElementById('custom_weekly_email').required = false;
            }
        });

        document.getElementById('monthly_recipient').addEventListener('change', function() {
            const customField = document.getElementById('custom-monthly-email');
            customField.style.display = this.value === 'custom' ? 'block' : 'none';
            if (this.value === 'custom') {
                document.getElementById('custom_monthly_email').required = true;
            } else {
                document.getElementById('custom_monthly_email').required = false;
            }
        });

        // Weekly Report Form Submission
        document.getElementById('weekly-report-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const recipientSelect = document.getElementById('weekly_recipient');
            const customEmail = document.getElementById('custom_weekly_email');

            if (recipientSelect.value === 'custom') {
                formData.set('recipient_email', customEmail.value);
            }

            showLoading();

            try {
                const response = await fetch('/admin/reports/send-weekly', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    showAlert('success', result.message);
                    this.reset();
                    document.getElementById('weekly-preview').style.display = 'none';
                } else {
                    showAlert('error', result.message);
                }
            } catch (error) {
                showAlert('error', 'An error occurred while sending the report.');
            } finally {
                hideLoading();
            }
        });

        // Monthly Report Form Submission
        document.getElementById('monthly-report-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const recipientSelect = document.getElementById('monthly_recipient');
            const customEmail = document.getElementById('custom_monthly_email');

            if (recipientSelect.value === 'custom') {
                formData.set('recipient_email', customEmail.value);
            }

            showLoading();

            try {
                const response = await fetch('/admin/reports/send-monthly', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    showAlert('success', result.message);
                    this.reset();
                    document.getElementById('monthly-preview').style.display = 'none';
                } else {
                    showAlert('error', result.message);
                }
            } catch (error) {
                showAlert('error', 'An error occurred while sending the report.');
            } finally {
                hideLoading();
            }
        });

        // Preview functions
        async function previewWeeklyReport() {
            const month = document.getElementById('report_week_month').value;
            const weekNumber = document.getElementById('week_number').value;

            if (!month || !weekNumber) {
                showAlert('error', 'Please select both month and week number.');
                return;
            }

            showLoading();

            try {
                const response = await fetch('/admin/reports/preview-weekly', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        month: month,
                        week_number: weekNumber
                    })
                });

                const result = await response.json();

                if (result.success) {
                    displayWeeklyPreview(result.data);
                    document.getElementById('weekly-preview').style.display = 'block';
                } else {
                    showAlert('error', result.message);
                }
            } catch (error) {
                showAlert('error', 'An error occurred while loading the preview.');
            } finally {
                hideLoading();
            }
        }

        async function previewMonthlyReport() {
            const month = document.getElementById('report_month').value;

            if (!month) {
                showAlert('error', 'Please select a month.');
                return;
            }

            showLoading();

            try {
                const response = await fetch('/admin/reports/preview-monthly', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        month: month
                    })
                });

                const result = await response.json();

                if (result.success) {
                    displayMonthlyPreview(result.data);
                    document.getElementById('monthly-preview').style.display = 'block';
                } else {
                    showAlert('error', result.message);
                }
            } catch (error) {
                showAlert('error', 'An error occurred while loading the preview.');
            } finally {
                hideLoading();
            }
        }

        function displayWeeklyPreview(data) {
            const container = document.getElementById('weekly-preview-data');
            const weekStart = new Date(data.week_start).toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            const weekEnd = new Date(data.week_end).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });

            container.innerHTML = `
                <div class="preview-item" style="grid-column: 1 / -1; background: linear-gradient(135deg, rgba(66, 133, 244, 0.1), rgba(255, 215, 0, 0.1)); border-left: 4px solid rgba(66, 133, 244, 1);">
                    <div class="preview-value" style="font-size: 16px; color: #333;">${weekStart} - ${weekEnd}</div>
                    <div class="preview-label">Week ${data.week_number} (ISO Week ${data.iso_week_number})</div>
                </div>
                <div class="preview-item">
                    <div class="preview-value">${parseFloat(data.current.overall_satisfaction).toFixed(2)}</div>
                    <div class="preview-label">Satisfaction</div>
                </div>
                <div class="preview-item">
                    <div class="preview-value">${parseFloat(data.current.compliance_percentage).toFixed(2)}%</div>
                    <div class="preview-label">Compliance</div>
                </div>
                <div class="preview-item">
                    <div class="preview-value">${data.current.new_responses}</div>
                    <div class="preview-label">New Responses</div>
                </div>
                <div class="preview-item">
                    <div class="preview-value" style="color: ${data.current.risk_level === 'Low' ? '#28a745' : (data.current.risk_level === 'Medium' ? '#ffc107' : '#dc3545')}">${data.current.risk_level}</div>
                    <div class="preview-label">Risk Level</div>
                </div>
            `;
        }

        function displayMonthlyPreview(data) {
            const container = document.getElementById('monthly-preview-data');

            const targetsHtml = `
                <div class="preview-item" style="grid-column: 1 / -1; background: ${data.targets_achieved.satisfaction && data.targets_achieved.compliance ? '#d4edda' : '#f8d7da'}; border-left: 4px solid ${data.targets_achieved.satisfaction && data.targets_achieved.compliance ? '#28a745' : '#dc3545'};">
                    <div class="preview-value" style="font-size: 16px; color: #333;">${data.targets_achieved.satisfaction && data.targets_achieved.compliance ? 'All Targets Met' : 'Some Targets Not Met'}</div>
                    <div class="preview-label">Monthly Target Status</div>
                </div>
            `;

            container.innerHTML = targetsHtml + `
                <div class="preview-item">
                    <div class="preview-value">${parseFloat(data.monthly_averages.overall_satisfaction).toFixed(2)}</div>
                    <div class="preview-label">Avg Satisfaction</div>
                </div>
                <div class="preview-item">
                    <div class="preview-value">${parseFloat(data.monthly_averages.compliance_score).toFixed(2)}</div>
                    <div class="preview-label">Avg Compliance</div>
                </div>
                <div class="preview-item">
                    <div class="preview-value">${Math.round(data.monthly_averages.total_responses)}</div>
                    <div class="preview-label">Total Responses</div>
                </div>
                <div class="preview-item">
                    <div class="preview-value">${data.weekly_data.length}</div>
                    <div class="preview-label">Weeks Covered</div>
                </div>
            `;
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

        function showLoading() {
            document.getElementById('loading-overlay').classList.add('active');
        }

        function hideLoading() {
            document.getElementById('loading-overlay').classList.remove('active');
        }

        console.log('Report management page loaded');
    </script>
</body>
</html>
