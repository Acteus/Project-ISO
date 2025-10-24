<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Logs - ISO Quality Education</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .page-header {
            background: linear-gradient(135deg, #ffc107, #ff9800);
            color: #333;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
        }

        .page-header h1 {
            margin: 0 0 15px 0;
            font-size: 28px;
            font-weight: 700;
            line-height: 1.2;
        }

        .page-header p {
            margin: 0;
            opacity: 0.9;
            font-size: 16px;
            line-height: 1.5;
        }

        .filters-section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .filters-section h3 {
            margin-top: 0;
            color: #333;
            border-bottom: 2px solid #ffc107;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
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
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .filter-group select,
        .filter-group input[type="date"] {
            padding: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .filter-group select:focus,
        .filter-group input[type="date"]:focus {
            border-color: #ffc107;
            outline: none;
        }

        .filter-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .audit-logs-section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .audit-logs-section h3 {
            margin-top: 0;
            color: #333;
            border-bottom: 2px solid #ffc107;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .audit-log-item {
            padding: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
            background: #fafafa;
        }

        .audit-log-item:hover {
            background: #f5f5f5;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transform: translateX(5px);
        }

        .audit-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .audit-action {
            font-weight: 700;
            font-size: 16px;
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .audit-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-login {
            background: #28a745;
            color: white;
        }

        .badge-logout {
            background: #dc3545;
            color: white;
        }

        .badge-registration {
            background: #17a2b8;
            color: white;
        }

        .badge-submit {
            background: #007bff;
            color: white;
        }

        .badge-view {
            background: #6c757d;
            color: white;
        }

        .badge-default {
            background: #ffc107;
            color: #333;
        }

        .audit-timestamp {
            color: #666;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .audit-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #e0e0e0;
        }

        .audit-detail-item {
            display: flex;
            flex-direction: column;
        }

        .audit-detail-label {
            font-weight: 600;
            color: #666;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .audit-detail-value {
            color: #333;
            font-size: 14px;
        }

        .audit-description {
            margin-top: 10px;
            color: #555;
            font-size: 14px;
            line-height: 1.5;
        }

        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }

        .pagination {
            display: flex;
            gap: 5px;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .pagination li {
            display: inline-block;
        }

        .pagination a,
        .pagination span {
            display: block;
            padding: 8px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
        }

        .pagination a:hover {
            background: #ffc107;
            border-color: #ffc107;
            color: #333;
        }

        .pagination .active span {
            background: #ffc107;
            border-color: #ffc107;
            color: #333;
            font-weight: 700;
        }

        .pagination .disabled span {
            color: #ccc;
            cursor: not-allowed;
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-primary {
            background: linear-gradient(90deg, #ffc107, #ff9800);
            color: #333;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 193, 7, 0.4);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.4);
        }

        .no-data {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 40px 20px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            border-left: 4px solid #ffc107;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #ffc107;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 14px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .icon {
            width: 16px;
            height: 16px;
            display: inline-block;
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
                    <a href="{{ route('welcome') }}" class="nav-link">Home</a>
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">Dashboard</a>
                    <a href="{{ route('admin.audit.logs') }}" class="nav-link active">Audit Logs</a>
                    <span class="nav-link" style="color: rgba(255,255,255,0.8); cursor: default;">{{ $admin->name }}</span>
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
        <div class="dashboard-container">
            <!-- Page Header -->
            <div class="page-header">
                <h1>📋 Audit Logs</h1>
                <p>Review system activity and ensure compliance with ISO 21001 traceability requirements</p>
            </div>

            <!-- Statistics -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value">{{ $auditLogs->total() }}</div>
                    <div class="stat-label">Total Events</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ \App\Models\AuditLog::whereDate('created_at', today())->count() }}</div>
                    <div class="stat-label">Today's Events</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ \App\Models\AuditLog::where('action', 'LIKE', '%login%')->count() }}</div>
                    <div class="stat-label">Login Events</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ \App\Models\AuditLog::where('action', 'submit_survey_response')->count() }}</div>
                    <div class="stat-label">Survey Submissions</div>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="filters-section">
                <h3>🔍 Filter Audit Logs</h3>
                <form method="GET" action="{{ route('admin.audit.logs') }}">
                    <div class="filter-grid">
                        <div class="filter-group">
                            <label for="action">Action Type</label>
                            <select name="action" id="action">
                                <option value="">All Actions</option>
                                @foreach($actions as $actionOption)
                                    <option value="{{ $actionOption }}" {{ $action == $actionOption ? 'selected' : '' }}>
                                        {{ ucwords(str_replace('_', ' ', $actionOption)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="date_from">Date From</label>
                            <input type="date" name="date_from" id="date_from" value="{{ $dateFrom }}">
                        </div>
                        <div class="filter-group">
                            <label for="date_to">Date To</label>
                            <input type="date" name="date_to" id="date_to" value="{{ $dateTo }}">
                        </div>
                    </div>
                    <div class="filter-actions">
                        <a href="{{ route('admin.audit.logs') }}" class="btn btn-secondary">Clear Filters</a>
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                    </div>
                </form>
            </div>

            <!-- Audit Logs List -->
            <div class="audit-logs-section">
                <h3>📜 Activity Log</h3>
                @if($auditLogs->count() > 0)
                    @foreach($auditLogs as $log)
                        <div class="audit-log-item">
                            <div class="audit-header">
                                <div class="audit-action">
                                    @php
                                        $badgeClass = 'badge-default';
                                        if (str_contains($log->action, 'login')) {
                                            $badgeClass = 'badge-login';
                                        } elseif (str_contains($log->action, 'logout')) {
                                            $badgeClass = 'badge-logout';
                                        } elseif (str_contains($log->action, 'registration')) {
                                            $badgeClass = 'badge-registration';
                                        } elseif (str_contains($log->action, 'submit')) {
                                            $badgeClass = 'badge-submit';
                                        } elseif (str_contains($log->action, 'view')) {
                                            $badgeClass = 'badge-view';
                                        }
                                    @endphp
                                    <span class="audit-badge {{ $badgeClass }}">{{ ucwords(str_replace('_', ' ', $log->action)) }}</span>
                                </div>
                                <div class="audit-timestamp">
                                    <span class="icon">🕐</span>
                                    {{ $log->created_at->format('M j, Y g:i A') }}
                                </div>
                            </div>

                            <div class="audit-description">
                                {{ $log->description }}
                            </div>

                            <div class="audit-details">
                                <div class="audit-detail-item">
                                    <span class="audit-detail-label">User</span>
                                    <span class="audit-detail-value">
                                        @if($log->user)
                                            {{ $log->user->name }} ({{ $log->user->student_id }})
                                        @else
                                            System / Admin
                                        @endif
                                    </span>
                                </div>
                                <div class="audit-detail-item">
                                    <span class="audit-detail-label">IP Address</span>
                                    <span class="audit-detail-value">{{ $log->ip_address ?? 'N/A' }}</span>
                                </div>
                                <div class="audit-detail-item">
                                    <span class="audit-detail-label">Event ID</span>
                                    <span class="audit-detail-value">#{{ $log->id }}</span>
                                </div>
                                @if($log->new_values)
                                    <div class="audit-detail-item">
                                        <span class="audit-detail-label">Additional Data</span>
                                        <span class="audit-detail-value">
                                            @if(is_array($log->new_values))
                                                {{ implode(', ', array_map(fn($k, $v) => "$k: $v", array_keys($log->new_values), $log->new_values)) }}
                                            @else
                                                {{ $log->new_values }}
                                            @endif
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    <!-- Pagination -->
                    <div class="pagination-wrapper">
                        <ul class="pagination">
                            {{-- Previous Page Link --}}
                            @if ($auditLogs->onFirstPage())
                                <li class="disabled"><span>&laquo; Previous</span></li>
                            @else
                                <li><a href="{{ $auditLogs->appends(request()->query())->previousPageUrl() }}">&laquo; Previous</a></li>
                            @endif

                            {{-- Pagination Elements --}}
                            @php
                                $currentPage = $auditLogs->currentPage();
                                $lastPage = $auditLogs->lastPage();
                                $start = max(1, $currentPage - 2);
                                $end = min($lastPage, $currentPage + 2);
                            @endphp

                            {{-- First Page --}}
                            @if($start > 1)
                                <li><a href="{{ $auditLogs->appends(request()->query())->url(1) }}">1</a></li>
                                @if($start > 2)
                                    <li class="disabled"><span>...</span></li>
                                @endif
                            @endif

                            {{-- Page Numbers --}}
                            @for ($page = $start; $page <= $end; $page++)
                                @if ($page == $currentPage)
                                    <li class="active"><span>{{ $page }}</span></li>
                                @else
                                    <li><a href="{{ $auditLogs->appends(request()->query())->url($page) }}">{{ $page }}</a></li>
                                @endif
                            @endfor

                            {{-- Last Page --}}
                            @if($end < $lastPage)
                                @if($end < $lastPage - 1)
                                    <li class="disabled"><span>...</span></li>
                                @endif
                                <li><a href="{{ $auditLogs->appends(request()->query())->url($lastPage) }}">{{ $lastPage }}</a></li>
                            @endif

                            {{-- Next Page Link --}}
                            @if ($auditLogs->hasMorePages())
                                <li><a href="{{ $auditLogs->appends(request()->query())->nextPageUrl() }}">Next &raquo;</a></li>
                            @else
                                <li class="disabled"><span>Next &raquo;</span></li>
                            @endif
                        </ul>
                    </div>
                @else
                    <div class="no-data">
                        <p>No audit logs found matching your criteria.</p>
                        <p>Try adjusting your filters or check back later.</p>
                    </div>
                @endif
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
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

    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        // Set current year
        document.getElementById('currentYear').textContent = new Date().getFullYear();

        console.log('Audit logs page loaded');
    </script>
</body>
</html>

