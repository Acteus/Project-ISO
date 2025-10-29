<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Logs - ISO Quality Education</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
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

        body {
            background: linear-gradient(135deg, rgba(66, 133, 244, 1), rgba(255, 215, 0, 1));
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .survey-main {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(10px);
        }

        .logs-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px;
        }

        .logs-header {
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

        .logs-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #4285F4, #FF8C00, #FFD700);
        }

        .logs-header h1 {
            margin: 0 0 20px 0;
            font-size: 32px;
            font-weight: 800;
            line-height: 1.3;
            color: #2c3e50;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .logs-header p {
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

        .logs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .log-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .log-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #4285F4, #FF8C00, #FFD700);
        }

        .log-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 25px 60px rgba(0,0,0,0.2);
        }

        .log-item {
            padding: 20px 25px;
            border: 1px solid rgba(0,0,0,0.06);
            border-radius: 16px;
            margin-bottom: 15px;
            background: linear-gradient(135deg, rgba(66, 133, 244, 0.03), rgba(255, 140, 0, 0.03));
            transition: all 0.3s ease;
        }

        .log-item:hover {
            background: linear-gradient(135deg, rgba(66, 133, 244, 0.08), rgba(255, 140, 0, 0.08));
            transform: translateX(5px);
            box-shadow: 0 8px 25px rgba(66, 133, 244, 0.15);
        }

        .log-item:last-child {
            margin-bottom: 0;
        }

        .log-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .log-action {
            font-weight: 700;
            color: #2c3e50;
            font-size: 16px;
        }

        .log-timestamp {
            color: #5a6c7d;
            font-size: 13px;
            font-weight: 600;
        }

        .log-details {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
        }

        .log-ip {
            background: rgba(66, 133, 244, 0.1);
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            color: #4285F4;
            margin-top: 8px;
            display: inline-block;
        }

        .action-type {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .action-login { background: linear-gradient(135deg, #28a745, #20c997); color: white; }
        .action-logout { background: linear-gradient(135deg, #6c757d, #5a6268); color: white; }
        .action-submit { background: linear-gradient(135deg, #17a2b8, #138496); color: white; }
        .action-access { background: linear-gradient(135deg, #ffc107, #ff9800); color: #333; }
        .action-error { background: linear-gradient(135deg, #dc3545, #e74c3c); color: white; }
        .action-export { background: linear-gradient(135deg, #6f42c1, #5a32a3); color: white; }

        .stats-bar {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-item {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 25px;
            border-radius: 18px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .stat-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #4285F4, #FF8C00, #FFD700);
        }

        .stat-item:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 20px 50px rgba(66, 133, 244, 0.2);
        }

        .stat-value {
            font-size: 36px;
            font-weight: 900;
            background: linear-gradient(135deg, #4285F4, #FF8C00);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 8px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .stat-label {
            font-size: 14px;
            color: #5a6c7d;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 600;
        }

        .logs-table-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
            overflow-x: auto;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .logs-table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 12px;
            overflow: hidden;
        }

        .logs-table thead {
            background: linear-gradient(135deg, #4285F4, #FF8C00);
        }

        .logs-table th {
            padding: 18px 15px;
            text-align: left;
            font-weight: 700;
            color: white;
            border-bottom: none;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .logs-table td {
            padding: 18px 15px;
            border-bottom: 1px solid rgba(0,0,0,0.06);
            vertical-align: middle;
            transition: all 0.3s ease;
        }

        .logs-table tbody tr {
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.5);
        }

        .logs-table tbody tr:hover {
            background: linear-gradient(135deg, rgba(66, 133, 244, 0.08), rgba(255, 140, 0, 0.08));
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(66, 133, 244, 0.15);
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 30px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .pagination a,
        .pagination span {
            padding: 12px 16px;
            border: 1px solid rgba(0,0,0,0.1);
            border-radius: 10px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
            font-weight: 600;
            background: rgba(255, 255, 255, 0.8);
        }

        .pagination a:hover {
            background: linear-gradient(135deg, #4285F4, #1e88e5);
            color: white;
            border-color: #4285F4;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(66, 133, 244, 0.3);
        }

        .pagination .active {
            background: linear-gradient(135deg, #4285F4, #1e88e5);
            color: white;
            border-color: #4285F4;
            font-weight: 700;
        }

        .pagination .disabled {
            opacity: 0.5;
            pointer-events: none;
            background: rgba(200, 200, 200, 0.5);
        }

        .no-data {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
            font-style: italic;
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
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
            .logs-container {
                padding: 20px;
            }

            .logs-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .stats-bar {
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                gap: 15px;
            }

            .logs-table-container {
                padding: 20px;
            }

            .logs-table {
                font-size: 14px;
            }

            .logs-table th,
            .logs-table td {
                padding: 12px 8px;
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
                    <a href="{{ route('admin.audit.logs') }}" class="nav-link active">Audit Logs</a>
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
        <div class="logs-container">
            <a href="{{ route('admin.dashboard') }}" class="back-btn">← Back to Dashboard</a>

            <div class="logs-header">
                <h1>System Audit Logs</h1>
                <p>Comprehensive audit trail for ISO 21001 compliance and system security monitoring</p>
            </div>

            <!-- Stats Bar -->
            <div class="stats-bar">
                <div class="stat-item">
                    <div class="stat-value">{{ $auditLogs->total() }}</div>
                    <div class="stat-label">Total Audit Events</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $auditLogs->currentPage() }}</div>
                    <div class="stat-label">Current Page</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $auditLogs->lastPage() }}</div>
                    <div class="stat-label">Total Pages</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ \App\Models\AuditLog::where('action', 'login')->count() }}</div>
                    <div class="stat-label">Login Events</div>
                </div>
            </div>

            <!-- Logs Table -->
            <div class="logs-table-container">
                @if($auditLogs->count() > 0)
                    <table class="logs-table">
                        <thead>
                            <tr>
                                <th>Timestamp</th>
                                <th>Action</th>
                                <th>User</th>
                                <th>Details</th>
                                <th>IP Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($auditLogs as $log)
                                <tr>
                                    <td>
                                        <div style="display: flex; flex-direction: column;">
                                            <span style="font-weight: 600; color: #2c3e50;">{{ $log->created_at->format('M j, Y') }}</span>
                                            <small style="color: #666; font-size: 12px;">{{ $log->created_at->format('g:i:s A') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="action-type action-{{ str_replace('_', '', $log->action) }}">
                                            {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div style="font-weight: 600; color: #2c3e50;">
                                            {{ $log->user_type ?? 'System' }}
                                        </div>
                                        @if($log->user_id)
                                            <small style="color: #666;">ID: {{ $log->user_id }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="color: #5a6c7d; font-size: 14px; line-height: 1.5;">
                                            {{ $log->description ?? 'No description available' }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="log-ip">{{ $log->ip_address ?? 'Unknown' }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="pagination">
                        @if ($auditLogs->onFirstPage())
                            <span class="disabled">« Previous</span>
                        @else
                            <a href="{{ $auditLogs->previousPageUrl() }}">« Previous</a>
                        @endif

                        @foreach(range(1, $auditLogs->lastPage()) as $page)
                            @if($page == $auditLogs->currentPage())
                                <span class="active">{{ $page }}</span>
                            @else
                                <a href="{{ $auditLogs->url($page) }}">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if ($auditLogs->hasMorePages())
                            <a href="{{ $auditLogs->nextPageUrl() }}">Next »</a>
                        @else
                            <span class="disabled">Next »</span>
                        @endif
                    </div>
                @else
                    <div class="no-data">
                        <h3>No audit logs found</h3>
                        <p>System activity will be recorded here for compliance tracking.</p>
                    </div>
                @endif
            </div>

            <!-- Recent Activity Summary -->
            @if($auditLogs->count() > 0)
            <div class="logs-grid">
                <div class="log-card">
                    <h3 style="color: #2c3e50; font-size: 20px; font-weight: 700; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px solid transparent; border-image: linear-gradient(90deg, #4285F4, #FF8C00) 1;">Recent Login Activity</h3>
                    @php
                        $recentLogins = \App\Models\AuditLog::where('action', 'login')->latest()->take(5)->get();
                    @endphp

                    @foreach($recentLogins as $login)
                        <div class="log-item">
                            <div class="log-header">
                                <div class="log-action">Login Event</div>
                                <div class="log-timestamp">{{ $login->created_at->format('M j, g:i A') }}</div>
                            </div>
                            <div class="log-details">
                                {{ $login->description ?? 'User logged in' }}
                                @if($login->ip_address)
                                    <div class="log-ip">IP: {{ $login->ip_address }}</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="log-card">
                    <h3 style="color: #2c3e50; font-size: 20px; font-weight: 700; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px solid transparent; border-image: linear-gradient(90deg, #FF8C00, #FFD700) 1;">Recent Survey Submissions</h3>
                    @php
                        $recentSubmissions = \App\Models\AuditLog::where('action', 'submit_survey_response')->latest()->take(5)->get();
                    @endphp

                    @foreach($recentSubmissions as $submission)
                        <div class="log-item">
                            <div class="log-header">
                                <div class="log-action">Survey Submission</div>
                                <div class="log-timestamp">{{ $submission->created_at->format('M j, g:i A') }}</div>
                            </div>
                            <div class="log-details">
                                {{ $submission->description ?? 'Survey response submitted' }}
                                @if($submission->ip_address)
                                    <div class="log-ip">IP: {{ $submission->ip_address }}</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </main>

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

    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        // Set current year
        document.getElementById('currentYear').textContent = new Date().getFullYear();

        // Add smooth animations on page load
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.stat-item, .log-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';

                setTimeout(() => {
                    card.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // Animate log items
            const logItems = document.querySelectorAll('.log-item');
            logItems.forEach((item, index) => {
                item.style.opacity = '0';
                item.style.transform = 'translateX(-20px)';

                setTimeout(() => {
                    item.style.transition = 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
                    item.style.opacity = '1';
                    item.style.transform = 'translateX(0)';
                }, index * 50);
            });
        });

        console.log('Enhanced Audit Logs page loaded');
    </script>
</body>
</html>
