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
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            /* Textured Background with JRU Colors (Blue, Gold, White) */
            background-color: #e8f4f8;
            background-image:
                /* Diagonal stripes texture */
                repeating-linear-gradient(
                    45deg,
                    transparent,
                    transparent 10px,
                    rgba(66, 133, 244, 0.03) 10px,
                    rgba(66, 133, 244, 0.03) 20px
                ),
                repeating-linear-gradient(
                    -45deg,
                    transparent,
                    transparent 10px,
                    rgba(255, 193, 7, 0.02) 10px,
                    rgba(255, 193, 7, 0.02) 20px
                ),
                /* Dot pattern texture */
                radial-gradient(circle at 25% 25%, rgba(66, 133, 244, 0.04) 2px, transparent 2px),
                radial-gradient(circle at 75% 75%, rgba(255, 193, 7, 0.04) 2px, transparent 2px),
                /* Subtle gradient overlay */
                linear-gradient(135deg,
                    rgba(179, 217, 255, 0.4) 0%,
                    rgba(255, 233, 179, 0.3) 50%,
                    rgba(179, 229, 252, 0.4) 100%
                );
            background-size:
                100% 100%,
                100% 100%,
                20px 20px,
                20px 20px,
                100% 100%;
            background-position:
                0 0,
                0 0,
                0 0,
                10px 10px,
                0 0;
            background-attachment: fixed;
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

        .action-authentication { background: linear-gradient(135deg, #28a745, #20c997); color: white; }
        .action-dataaccess { background: linear-gradient(135deg, #ffc107, #ff9800); color: #333; }
        .action-datamodification { background: linear-gradient(135deg, #17a2b8, #138496); color: white; }
        .action-compliance { background: linear-gradient(135deg, #6f42c1, #5a32a3); color: white; }
        .action-login { background: linear-gradient(135deg, #28a745, #20c997); color: white; }
        .action-logout { background: linear-gradient(135deg, #6c757d, #5a6268); color: white; }
        .action-submit { background: linear-gradient(135deg, #17a2b8, #138496); color: white; }
        .action-access { background: linear-gradient(135deg, #ffc107, #ff9800); color: #333; }
        .action-error { background: linear-gradient(135deg, #dc3545, #e74c3c); color: white; }
        .action-export { background: linear-gradient(135deg, #6f42c1, #5a32a3); color: white; }
        .action-consentgiven { background: linear-gradient(135deg, #28a745, #20c997); color: white; }
        .action-consentdenied { background: linear-gradient(135deg, #dc3545, #e74c3c); color: white; }
        .action-consentrevoked { background: linear-gradient(135deg, #ff9800, #f57c00); color: white; }

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
    <header class="header admin-header">
        <div class="container">
            <div class="nav-wrapper">
                <div class="logo">
                    <a href="{{ route('welcome') }}">ISO Quality Education</a>
                </div>

                <!-- Desktop navigation -->
                <nav class="desktop-nav">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">Dashboard</a>
                    <a href="{{ route('admin.audit.logs') }}" class="nav-link active">Audit Logs</a>
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
        <div class="logs-container">
            <a href="{{ route('admin.dashboard') }}" class="back-btn">← Back to Dashboard</a>

            <div class="logs-header">
                <h1>System Audit Logs</h1>
                <p>Comprehensive audit trail for ISO 21001 compliance and system security monitoring</p>
                        @if(request()->has('action') || request()->has('user_type') || request()->has('resource_type') || request()->has('date_from') || request()->has('date_to') || request()->has('search'))
                    <div style="margin-top: 20px; padding: 15px; background: linear-gradient(135deg, rgba(66, 133, 244, 0.1), rgba(255, 140, 0, 0.1)); border-radius: 12px; border-left: 4px solid #4285F4;">
                        <strong style="color: #4285F4;">
                            <svg style="width: 18px; height: 18px; vertical-align: middle; margin-right: 6px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                            Filters Active:
                        </strong>
                        <span style="color: #5a6c7d; font-weight: 500;">
                            @if(request()->has('action') && request('action') !== 'all')
                                Action: <em>{{ ucfirst(str_replace('_', ' ', request('action'))) }}</em>
                            @endif
                            @if(request()->has('user_type') && request('user_type') !== 'all')
                                {{ (request()->has('action') && request('action') !== 'all') ? ' | ' : '' }}
                                User Type: <em>{{ ucfirst(request('user_type')) }}</em>
                            @endif
                            @if(request()->has('resource_type') && request('resource_type') !== 'all')
                                {{ (request()->has('action') && request('action') !== 'all') || (request()->has('user_type') && request('user_type') !== 'all') ? ' | ' : '' }}
                                Resource: <em>{{ ucfirst(str_replace('_', ' ', request('resource_type'))) }}</em>
                            @endif
                            @if(request()->has('date_from'))
                                {{ (request()->has('action') && request('action') !== 'all') || (request()->has('user_type') && request('user_type') !== 'all') ? ' | ' : '' }}
                                From: <em>{{ request('date_from') }}</em>
                            @endif
                            @if(request()->has('date_to'))
                                | To: <em>{{ request('date_to') }}</em>
                            @endif
                            @if(request()->has('search'))
                                | Search: <em>"{{ request('search') }}"</em>
                            @endif
                        </span>
                    </div>
                @endif
            </div>

            <!-- Filter Section -->
            <div class="logs-table-container" style="margin-bottom: 30px;">
                <h3 style="color: #2c3e50; font-size: 20px; font-weight: 700; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px solid transparent; border-image: linear-gradient(90deg, #4285F4, #FF8C00) 1;">
                    <svg style="width: 24px; height: 24px; vertical-align: middle; margin-right: 8px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M10 18h4v-2h-4v2zM3 6v2h18V6H3zm3 7h12v-2H6v2z"/>
                    </svg>
                    Filter Audit Logs
                </h3>
                <form method="GET" action="{{ route('admin.audit.logs') }}" id="filterForm">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 20px;">
                        <!-- Action Filter -->
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">Action Type</label>
                            <select name="action" class="filter-select" style="width: 100%; padding: 12px; border: 2px solid rgba(66, 133, 244, 0.2); border-radius: 10px; font-size: 14px; background: white; transition: all 0.3s ease;">
                                <option value="all">All Actions</option>
                                @foreach($actions as $actionOption)
                                    <option value="{{ $actionOption }}" {{ request('action') == $actionOption ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $actionOption)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- User Type Filter -->
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">User Type</label>
                            <select name="user_type" class="filter-select" style="width: 100%; padding: 12px; border: 2px solid rgba(66, 133, 244, 0.2); border-radius: 10px; font-size: 14px; background: white; transition: all 0.3s ease;">
                                <option value="all">All Users</option>
                                @foreach($userTypes as $type)
                                    <option value="{{ $type }}" {{ request('user_type') == $type ? 'selected' : '' }}>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Resource Type Filter -->
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">Resource Type</label>
                            <select name="resource_type" class="filter-select" style="width: 100%; padding: 12px; border: 2px solid rgba(66, 133, 244, 0.2); border-radius: 10px; font-size: 14px; background: white; transition: all 0.3s ease;">
                                <option value="all">All Resources</option>
                                @foreach($resourceTypes ?? [] as $resourceType)
                                    <option value="{{ $resourceType }}" {{ request('resource_type') == $resourceType ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $resourceType)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date From -->
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">Date From</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}" class="filter-input" style="width: 100%; padding: 12px; border: 2px solid rgba(66, 133, 244, 0.2); border-radius: 10px; font-size: 14px; transition: all 0.3s ease;">
                        </div>

                        <!-- Date To -->
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">Date To</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}" class="filter-input" style="width: 100%; padding: 12px; border: 2px solid rgba(66, 133, 244, 0.2); border-radius: 10px; font-size: 14px; transition: all 0.3s ease;">
                        </div>

                        <!-- Search -->
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">Search</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Description, IP, User ID..." class="filter-input" style="width: 100%; padding: 12px; border: 2px solid rgba(66, 133, 244, 0.2); border-radius: 10px; font-size: 14px; transition: all 0.3s ease;">
                        </div>

                        <!-- Per Page -->
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">Results Per Page</label>
                            <select name="per_page" class="filter-select" style="width: 100%; padding: 12px; border: 2px solid rgba(66, 133, 244, 0.2); border-radius: 10px; font-size: 14px; background: white; transition: all 0.3s ease;">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </div>
                    </div>

                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                        <button type="submit" style="background: linear-gradient(135deg, #4285F4, #1e88e5); color: white; padding: 12px 24px; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; transition: all 0.3s ease; text-transform: uppercase; letter-spacing: 1px;">
                            <svg style="width: 16px; height: 16px; vertical-align: middle; margin-right: 6px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M10 18h4v-2h-4v2zM3 6v2h18V6H3zm3 7h12v-2H6v2z"/>
                            </svg>
                            Apply Filters
                        </button>
                        <a href="{{ route('admin.audit.logs') }}" style="background: rgba(108, 117, 125, 0.1); color: #6c757d; padding: 12px 24px; border: 2px solid #6c757d; border-radius: 10px; font-weight: 700; cursor: pointer; transition: all 0.3s ease; text-transform: uppercase; letter-spacing: 1px; text-decoration: none; display: inline-block;">
                            <svg style="width: 16px; height: 16px; vertical-align: middle; margin-right: 6px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                            </svg>
                            Clear Filters
                        </a>
                        <button type="button" onclick="exportLogs()" style="background: linear-gradient(135deg, #28a745, #20c997); color: white; padding: 12px 24px; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; transition: all 0.3s ease; text-transform: uppercase; letter-spacing: 1px; margin-left: auto;">
                            <svg style="width: 16px; height: 16px; vertical-align: middle; margin-right: 6px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M19 12v7H5v-7H3v7c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2v-7h-2zm-6 .67l2.59-2.58L17 11.5l-5 5-5-5 1.41-1.41L11 12.67V3h2z"/>
                            </svg>
                            Export CSV
                        </button>
                    </div>
                </form>
            </div>

            <!-- Stats Bar -->
            <div class="stats-bar">
                <div class="stat-item">
                    <div class="stat-value">{{ $auditLogs->total() }}</div>
                    <div class="stat-label">Filtered Results</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $stats['loginCount'] ?? 0 }}</div>
                    <div class="stat-label">Login Events</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $stats['logoutCount'] ?? 0 }}</div>
                    <div class="stat-label">Logout Events</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $stats['submissionCount'] ?? 0 }}</div>
                    <div class="stat-label">Survey Submissions</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $stats['consentCount'] ?? 0 }}</div>
                    <div class="stat-label">Consent Events</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $stats['dataAccessCount'] ?? 0 }}</div>
                    <div class="stat-label">Data Access</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $stats['dataModificationCount'] ?? 0 }}</div>
                    <div class="stat-label">Data Modifications</div>
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
                                <th>Resource</th>
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
                                        @php
                                            // Infer resource type from action/description if not explicitly set
                                            $resourceType = $log->resource_type;
                                            $resourceId = $log->resource_id;
                                            
                                            // If resource_type is null, try to infer it from the action
                                            if (!$resourceType) {
                                                $action = strtolower($log->action ?? '');
                                                $description = strtolower($log->description ?? '');
                                                
                                                // Map actions to resource types
                                                if (in_array($action, ['authentication', 'admin_login', 'student_login', 'admin_logout', 'student_logout']) || 
                                                    strpos($description, 'login') !== false || 
                                                    strpos($description, 'logout') !== false) {
                                                    $resourceType = 'session';
                                                    // Try to get resource_id from user_id if available
                                                    $resourceId = $resourceId ?: $log->user_id;
                                                } elseif (strpos($description, 'survey') !== false || 
                                                          strpos($action, 'survey') !== false || 
                                                          strpos($action, 'submission') !== false) {
                                                    $resourceType = 'survey_response';
                                                } elseif (strpos($description, 'consent') !== false || 
                                                          strpos($action, 'consent') !== false) {
                                                    $resourceType = 'consent_record';
                                                } elseif (strpos($action, 'ai_analysis') !== false || 
                                                          strpos($action, 'view_ai') !== false) {
                                                    $resourceType = 'ai_analysis';
                                                } elseif (strpos($action, 'export') !== false || 
                                                          strpos($description, 'export') !== false) {
                                                    $resourceType = 'export';
                                                } elseif (strpos($action, 'view') !== false || 
                                                          strpos($action, 'access') !== false) {
                                                    $resourceType = 'data_access';
                                                }
                                            }
                                        @endphp
                                        
                                        @if($resourceType)
                                            <div style="font-weight: 600; color: #4285F4;">
                                                {{ ucfirst(str_replace('_', ' ', $resourceType)) }}
                                            </div>
                                            @if($resourceId)
                                                <small style="color: #666;">ID: {{ $resourceId }}</small>
                                            @elseif($log->user_id && $resourceType === 'session')
                                                <small style="color: #666;">User ID: {{ $log->user_id }}</small>
                                            @endif
                                        @else
                                            <span style="color: #999; font-style: italic;" title="This action is not associated with a specific resource (system-level event)">
                                                System Event
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="font-weight: 600; color: #2c3e50;">
                                            @php
                                                $userType = 'System';
                                                if ($log->user_id) {
                                                    // Check metadata for user_type
                                                    if ($log->metadata && isset($log->metadata['user_type'])) {
                                                        $userType = ucfirst($log->metadata['user_type']);
                                                    } else {
                                                        // Check new_values for user_type (backward compatibility)
                                                        if ($log->new_values && isset($log->new_values['user_type'])) {
                                                            $userType = ucfirst($log->new_values['user_type']);
                                                        } else {
                                                            $userType = 'Student';
                                                        }
                                                    }
                                                }
                                            @endphp
                                            {{ $userType }}
                                        </div>
                                        @if($log->user_id)
                                            <small style="color: #666;">User ID: {{ $log->user_id }}</small>
                                        @endif
                                        @if($log->user)
                                            <small style="color: #666; display: block;">{{ $log->user->name ?? 'N/A' }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="color: #5a6c7d; font-size: 14px; line-height: 1.5;">
                                            {{ $log->description ?? 'No description available' }}
                                            @if($log->new_values && is_array($log->new_values))
                                                @php
                                                    // Redact sensitive data for privacy (GDPR & ISO 27001)
                                                    $redactedValues = $log->new_values;
                                                    if (isset($redactedValues['student_id']) && $redactedValues['student_id'] !== '***REDACTED***') {
                                                        $redactedValues['student_id'] = '***REDACTED***';
                                                    }
                                                @endphp
                                                @if(!empty($redactedValues))
                                                    <div style="margin-top: 8px; padding: 8px; background: rgba(66, 133, 244, 0.05); border-radius: 6px; font-size: 12px;">
                                                        <strong style="color: #4285F4;">New Values:</strong>
                                                        <pre style="margin: 4px 0 0 0; font-size: 11px; color: #666; white-space: pre-wrap; word-break: break-word;">{{ json_encode($redactedValues, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                                    </div>
                                                @endif
                                            @endif
                                            @if($log->old_values && is_array($log->old_values))
                                                @php
                                                    // Redact sensitive data
                                                    $redactedOldValues = $log->old_values;
                                                    if (isset($redactedOldValues['student_id']) && $redactedOldValues['student_id'] !== '***REDACTED***') {
                                                        $redactedOldValues['student_id'] = '***REDACTED***';
                                                    }
                                                @endphp
                                                @if(!empty($redactedOldValues))
                                                    <div style="margin-top: 8px; padding: 8px; background: rgba(255, 140, 0, 0.05); border-radius: 6px; font-size: 12px;">
                                                        <strong style="color: #FF8C00;">Old Values:</strong>
                                                        <pre style="margin: 4px 0 0 0; font-size: 11px; color: #666; white-space: pre-wrap; word-break: break-word;">{{ json_encode($redactedOldValues, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                                    </div>
                                                @endif
                                            @endif
                                            @if($log->metadata && is_array($log->metadata))
                                                <div style="margin-top: 8px; padding: 8px; background: rgba(108, 117, 125, 0.05); border-radius: 6px; font-size: 12px;">
                                                    <strong style="color: #6c757d;">Metadata:</strong>
                                                    <pre style="margin: 4px 0 0 0; font-size: 11px; color: #666; white-space: pre-wrap; word-break: break-word;">{{ json_encode($log->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                                </div>
                                            @endif
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
                        $recentLogins = \App\Models\AuditLog::where(function($q) {
                            $q->where('action', 'authentication')
                              ->where('description', 'LIKE', '%login%')
                              ->orWhereIn('action', ['student_login', 'admin_login']); // Backward compatibility
                        })
                        ->latest()
                        ->take(5)
                        ->get();
                    @endphp

                    @if($recentLogins->count() > 0)
                        @foreach($recentLogins as $login)
                            <div class="log-item">
                                <div class="log-header">
                                    <div class="log-action">{{ ucfirst(str_replace('_', ' ', $login->action)) }}</div>
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
                    @else
                        <div style="text-align: center; padding: 20px; color: #6c757d; font-style: italic;">
                            No recent login activity
                        </div>
                    @endif
                </div>

                <div class="log-card">
                    <h3 style="color: #2c3e50; font-size: 20px; font-weight: 700; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px solid transparent; border-image: linear-gradient(90deg, #FF8C00, #FFD700) 1;">Recent Survey Submissions</h3>
                    @php
                        $recentSubmissions = \App\Models\AuditLog::where(function($q) {
                            $q->where(function($q2) {
                                $q2->where('action', 'data_modification')
                                  ->where('resource_type', 'survey_response')
                                  ->where('description', 'LIKE', '%survey%');
                            })
                            ->orWhere('action', 'submit_survey_response'); // Backward compatibility
                        })
                        ->latest()
                        ->take(5)
                        ->get();
                    @endphp

                    @if($recentSubmissions->count() > 0)
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
                    @else
                        <div style="text-align: center; padding: 20px; color: #6c757d; font-style: italic;">
                            No recent survey submissions
                        </div>
                    @endif
                </div>

                <div class="log-card">
                    <h3 style="color: #2c3e50; font-size: 20px; font-weight: 700; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px solid transparent; border-image: linear-gradient(90deg, #28a745, #20c997) 1;">
                        <svg style="width: 20px; height: 20px; vertical-align: middle; margin-right: 8px; fill: #28a745;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/>
                        </svg>
                        Recent Consent Activity (GDPR & ISO 27001)
                    </h3>
                    @php
                        $recentConsent = \App\Models\AuditLog::where(function($q) {
                            $q->where('action', 'compliance')
                              ->where(function($q2) {
                                  $q2->where('description', 'LIKE', '%consent%')
                                    ->orWhere('description', 'LIKE', '%Consent%');
                              })
                              ->orWhereIn('action', ['consent_given', 'consent_denied', 'consent_revoked']); // Backward compatibility
                        })
                        ->latest()
                        ->take(5)
                        ->get();
                    @endphp

                    @if($recentConsent->count() > 0)
                        @foreach($recentConsent as $consent)
                            <div class="log-item">
                                <div class="log-header">
                                    <div class="log-action" style="display: flex; align-items: center; gap: 6px;">
                                        @if($consent->action === 'consent_given')
                                            <svg style="width: 16px; height: 16px; fill: #28a745;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                            </svg>
                                            Consent Given
                                        @elseif($consent->action === 'consent_denied')
                                            <svg style="width: 16px; height: 16px; fill: #dc3545;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                                            </svg>
                                            Consent Denied
                                        @else
                                            <svg style="width: 16px; height: 16px; fill: #ff9800;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                                            </svg>
                                            Consent Revoked
                                        @endif
                                    </div>
                                    <div class="log-timestamp">{{ $consent->created_at->format('M j, g:i A') }}</div>
                                </div>
                                <div class="log-details">
                                    {{ $consent->description ?? 'Consent action recorded' }}
                                    @if($consent->ip_address)
                                        <div class="log-ip">IP: {{ $consent->ip_address }}</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div style="text-align: center; padding: 20px; color: #6c757d; font-style: italic;">
                            No recent consent activity
                        </div>
                    @endif
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

            // Add focus effects to filter inputs
            const filterInputs = document.querySelectorAll('.filter-input, .filter-select');
            filterInputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.style.borderColor = '#4285F4';
                    this.style.boxShadow = '0 0 0 3px rgba(66, 133, 244, 0.1)';
                });

                input.addEventListener('blur', function() {
                    this.style.borderColor = 'rgba(66, 133, 244, 0.2)';
                    this.style.boxShadow = 'none';
                });
            });
        });

        // Export logs functionality
        function exportLogs() {
            const form = document.getElementById('filterForm');
            const params = new URLSearchParams(new FormData(form));

            // Show loading state
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '<svg style="width: 16px; height: 16px; vertical-align: middle; margin-right: 6px; animation: spin 1s linear infinite;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6 0-1.01.25-1.97.7-2.8L5.24 7.74C4.46 8.97 4 10.43 4 12c0 4.42 3.58 8 8 8v3l4-4-4-4v3z"/></svg> Exporting...';
            button.disabled = true;

            // Create a downloadable CSV
            const url = new URL(window.location.href);
            url.searchParams.set('export', 'csv');
            params.forEach((value, key) => {
                if (value) url.searchParams.set(key, value);
            });

            // For now, just show an alert (you would implement actual export in Laravel)
            setTimeout(() => {
                alert('Export functionality would be implemented here. This would generate a CSV file with all filtered audit logs.');
                button.innerHTML = originalText;
                button.disabled = false;
            }, 1000);
        }

        // Auto-submit on filter change (optional - commented out for now)
        // document.querySelectorAll('.filter-select').forEach(select => {
        //     select.addEventListener('change', function() {
        //         document.getElementById('filterForm').submit();
        //     });
        // });

        console.log('Enhanced Audit Logs page with filters loaded');
    </script>

    <style>
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .filter-input:hover,
        .filter-select:hover {
            border-color: #4285F4 !important;
        }

        button[type="submit"]:hover,
        button[type="button"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(66, 133, 244, 0.3);
        }

        a[href*="audit.logs"]:hover {
            background: rgba(108, 117, 125, 0.2) !important;
            transform: translateY(-2px);
        }
    </style>

    @include('partials.admin-logout-modal')
</body>
</html>
