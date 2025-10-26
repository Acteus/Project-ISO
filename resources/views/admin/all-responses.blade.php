<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Survey Responses - ISO Quality Education</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, rgba(66, 133, 244, 1), rgba(255, 215, 0, 1));
            min-height: 100vh;
        }

        .survey-main {
            background-image: none !important;
        }

        .responses-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .responses-header {
            background: linear-gradient(135deg, rgba(66, 133, 244, 1), rgba(255, 215, 0, 1));
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .responses-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
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
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.9);
            color: black;
        }

        .responses-table-container {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow-x: auto;
        }

        .responses-table {
            width: 100%;
            border-collapse: collapse;
        }

        .responses-table thead {
            background: #f8f9fa;
        }

        .responses-table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid rgba(66, 133, 244, 1);
        }

        .responses-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }

        .responses-table tbody tr {
            transition: background-color 0.2s ease;
        }

        .responses-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-info {
            background: #17a2b8;
            color: white;
        }

        .badge-success {
            background: #28a745;
            color: white;
        }

        .btn {
            display: inline-block;
            padding: 8px 15px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-primary {
            background: #4285F4;
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(66, 133, 244, 0.4);
            color: white;
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 20px;
        }

        .pagination a,
        .pagination span {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
        }

        .pagination a:hover {
            background: rgba(66, 133, 244, 1);
            color: white;
            border-color: rgba(66, 133, 244, 1);
        }

        .pagination .active {
            background: rgba(66, 133, 244, 1);
            color: white;
            border-color: rgba(66, 133, 244, 1);
        }

        .pagination .disabled {
            opacity: 0.5;
            pointer-events: none;
        }

        .stats-bar {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat-item {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: rgba(66, 133, 244, 1);
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 14px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }

        .rating-stars {
            color: #ffc107;
            font-size: 14px;
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
                    <a href="{{ route('admin.responses') }}" class="nav-link active">All Responses</a>
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
        <div class="responses-container">
            <!-- Header -->
            <div class="responses-header">
                <div>
                    <h1>All Survey Responses</h1>
                    <p style="margin: 10px 0 0 0; opacity: 0.9;">Complete list of ISO 21001 survey submissions</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="back-btn">← Back to Dashboard</a>
            </div>

            <!-- Stats Bar -->
            <div class="stats-bar">
                <div class="stat-item">
                    <div class="stat-value">{{ $responses->total() }}</div>
                    <div class="stat-label">Total Responses</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $responses->currentPage() }}</div>
                    <div class="stat-label">Current Page</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $responses->lastPage() }}</div>
                    <div class="stat-label">Total Pages</div>
                </div>
            </div>

            <!-- Responses Table -->
            <div class="responses-table-container">
                @if($responses->count() > 0)
                    <table class="responses-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Track</th>
                                <th>Grade</th>
                                <th>Academic Year</th>
                                <th>Semester</th>
                                <th>Overall Rating</th>
                                <th>Submitted</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($responses as $response)
                                <tr>
                                    <td><strong>#{{ $response->id }}</strong></td>
                                    <td>
                                        <span class="badge badge-info">{{ $response->track }}</span>
                                    </td>
                                    <td>Grade {{ $response->grade_level }}</td>
                                    <td>{{ $response->academic_year }}</td>
                                    <td>{{ $response->semester }}</td>
                                    <td>
                                        <span class="rating-stars">
                                            @for ($i = 1; $i <= 5; $i++)
                                                {{ $i <= $response->overall_satisfaction ? '★' : '☆' }}
                                            @endfor
                                        </span>
                                        <span style="color: #666; font-size: 12px;">({{ $response->overall_satisfaction }}/5)</span>
                                    </td>
                                    <td>
                                        {{ $response->created_at->format('M j, Y') }}<br>
                                        <small style="color: #666;">{{ $response->created_at->format('g:i A') }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.response.view', $response->id) }}" class="btn btn-primary">
                                            View Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="pagination">
                        @if ($responses->onFirstPage())
                            <span class="disabled">« Previous</span>
                        @else
                            <a href="{{ $responses->previousPageUrl() }}">« Previous</a>
                        @endif

                        @foreach(range(1, $responses->lastPage()) as $page)
                            @if($page == $responses->currentPage())
                                <span class="active">{{ $page }}</span>
                            @else
                                <a href="{{ $responses->url($page) }}">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if ($responses->hasMorePages())
                            <a href="{{ $responses->nextPageUrl() }}">Next »</a>
                        @else
                            <span class="disabled">Next »</span>
                        @endif
                    </div>
                @else
                    <div class="no-data">
                        <h3>No survey responses found</h3>
                        <p>Survey responses will appear here once students start submitting their feedback.</p>
                    </div>
                @endif
            </div>
        </div>
    </main>

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

    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        // Set current year
        document.getElementById('currentYear').textContent = new Date().getFullYear();
    </script>
</body>
</html>

