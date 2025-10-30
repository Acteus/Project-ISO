<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Management - ISO Quality Education</title>
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
        }

        .survey-main {
            background-image: none !important;
        }

        .qr-codes-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .qr-codes-header {
            background: linear-gradient(135deg, rgba(66, 133, 244, 1), rgba(255, 215, 0, 1));
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .qr-codes-header h1 {
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

        .filters-section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            align-items: end;
        }

        .filter-group label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .filter-group select {
            width: 100%;
            padding: 8px 12px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 14px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
        }

        .btn-primary {
            background: #4285F4;
            color: white;
        }

        .btn-primary:hover {
            background: #357ae8;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(66, 133, 244, 0.4);
            color: white;
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
            color: white;
        }

        .btn-warning {
            background: #ffc107;
            color: #333;
        }

        .btn-warning:hover {
            background: #e0a800;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 193, 7, 0.4);
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-value {
            font-size: 36px;
            font-weight: 700;
            color: rgba(66, 133, 244, 1);
            margin-bottom: 8px;
        }

        .stat-label {
            font-size: 14px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .qr-codes-table-container {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow-x: auto;
        }

        .qr-codes-table {
            width: 100%;
            border-collapse: collapse;
        }

        .qr-codes-table thead {
            background: #f8f9fa;
        }

        .qr-codes-table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid rgba(66, 133, 244, 1);
        }

        .qr-codes-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }

        .qr-codes-table tbody tr {
            transition: background-color 0.2s ease;
        }

        .qr-codes-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .qr-preview {
            width: 50px;
            height: 50px;
            border: 2px solid #ddd;
            border-radius: 4px;
            object-fit: cover;
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .status-expired {
            background: #fff3cd;
            color: #856404;
        }

        .actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .action-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            transform: translateY(-1px);
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

        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
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
                    <a href="{{ route('admin.qr-codes.index') }}" class="nav-link active">QR Codes</a>
                    <a href="{{ route('admin.ai.insights') }}" class="nav-link">AI Insights</a>
                    <a href="{{ route('admin.reports') }}" class="nav-link">Reports</a>
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
        <div class="qr-codes-container">
            <!-- Header -->
            <div class="qr-codes-header">
                <div>
                    <h1>QR Code Management</h1>
                    <p style="margin: 10px 0 0 0; opacity: 0.9;">Generate and manage QR codes for easy survey access</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="back-btn">← Back to Dashboard</a>
            </div>

            <!-- Alert Messages -->
            <div id="alert-container"></div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value">{{ $stats['total_qr_codes'] }}</div>
                    <div class="stat-label">Total QR Codes</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ $stats['active_qr_codes'] }}</div>
                    <div class="stat-label">Active QR Codes</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ $stats['total_scans'] }}</div>
                    <div class="stat-label">Total Scans</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ $stats['average_scans_per_qr'] }}</div>
                    <div class="stat-label">Avg Scans per QR</div>
                </div>
            </div>

            <!-- Filters and Actions -->
            <div class="filters-section">
                <form id="filters-form" method="GET">
                    <div class="filters-grid">
                        <div class="filter-group">
                            <label for="track">Track</label>
                            <select name="track" id="track">
                                <option value="">All Tracks</option>
                                @foreach($tracks as $trackOption)
                                    <option value="{{ $trackOption }}" {{ $track == $trackOption ? 'selected' : '' }}>
                                        {{ $trackOption }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="grade_level">Grade Level</label>
                            <select name="grade_level" id="grade_level">
                                <option value="">All Grades</option>
                                @foreach($gradeLevels as $gradeLevelOption)
                                    <option value="{{ $gradeLevelOption }}" {{ $gradeLevel == $gradeLevelOption ? 'selected' : '' }}>
                                        Grade {{ $gradeLevelOption }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="section">Section</label>
                            <select name="section" id="section">
                                <option value="">All Sections</option>
                                @foreach($sections as $sectionOption)
                                    <option value="{{ $sectionOption }}" {{ $section == $sectionOption ? 'selected' : '' }}>
                                        Section {{ $sectionOption }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="is_active">Status</label>
                            <select name="is_active" id="is_active">
                                <option value="">All Status</option>
                                <option value="1" {{ $isActive === '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $isActive === '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <button type="submit" class="btn btn-primary">Apply Filters</button>
                        </div>
                        <div class="filter-group">
                            <a href="{{ route('admin.qr-codes.index') }}" class="btn btn-warning">Clear Filters</a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Actions Bar -->
            <div style="margin-bottom: 30px; display: flex; gap: 15px; align-items: center;">
                <a href="{{ route('admin.qr-codes.create') }}" class="btn btn-success">+ Create New QR Code</a>
                <a href="#" onclick="showBatchGenerate()" class="btn btn-primary">📱 Batch Generate</a>
                <a href="#" onclick="exportData()" class="btn btn-warning">📊 Export Data</a>
            </div>

            <!-- QR Codes Table -->
            <div class="qr-codes-table-container">
                @if($qrCodes->count() > 0)
                    <table class="qr-codes-table">
                        <thead>
                            <tr>
                                <th>Preview</th>
                                <th>Name</th>
                                <th>Track</th>
                                <th>Grade</th>
                                <th>Section</th>
                                <th>Format</th>
                                <th>Scans</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($qrCodes as $qrCode)
                                <tr>
                                    <td>
                                        @if($qrCode->file_path)
                                            <img src="{{ $qrCode->file_url }}" alt="QR Code" class="qr-preview">
                                        @else
                                            <div style="width: 50px; height: 50px; background: #f0f0f0; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #666;">No Image</div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $qrCode->name }}</strong>
                                        @if($qrCode->description)
                                            <br><small style="color: #666;">{{ Str::limit($qrCode->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $qrCode->track }}</td>
                                    <td>{{ $qrCode->grade_level ?: '-' }}</td>
                                    <td>{{ $qrCode->section ?: '-' }}</td>
                                    <td><span style="text-transform: uppercase;">{{ $qrCode->format }}</span></td>
                                    <td>{{ $qrCode->scan_count }}</td>
                                    <td>
                                        @if($qrCode->is_expired)
                                            <span class="status-badge status-expired">Expired</span>
                                        @elseif($qrCode->is_active)
                                            <span class="status-badge status-active">Active</span>
                                        @else
                                            <span class="status-badge status-inactive">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $qrCode->created_at->format('M j, Y') }}</td>
                                    <td>
                                        <div class="actions">
                                            <a href="{{ route('admin.qr-codes.show', $qrCode->id) }}" class="action-btn btn-primary">View</a>
                                            <a href="{{ route('admin.qr-codes.edit', $qrCode->id) }}" class="action-btn btn-warning">Edit</a>
                                            <a href="{{ route('admin.qr-codes.download', $qrCode->id) }}" class="action-btn btn-success">Download</a>
                                            <button onclick="deleteQrCode({{ $qrCode->id }})" class="action-btn btn-danger">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="no-data">
                        <p>No QR codes found.</p>
                        <p>Create your first QR code to get started!</p>
                        <a href="{{ route('admin.qr-codes.create') }}" class="btn btn-success">Create QR Code</a>
                    </div>
                @endif

                <!-- Pagination -->
                @if($qrCodes->hasPages())
                    <div class="pagination">
                        {{ $qrCodes->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </main>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loading-overlay">
        <div style="text-align: center; color: white;">
            <div class="loading-spinner"></div>
            <p style="margin-top: 20px; font-size: 16px;">Processing...</p>
        </div>
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
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        function showAlert(type, message) {
            const container = document.getElementById('alert-container');
            const alertClass = type === 'success' ? 'alert-success' : 'alert-error';
            container.innerHTML = `<div class="alert ${alertClass}">${message}</div>`;

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

        function deleteQrCode(id) {
            if (confirm('Are you sure you want to delete this QR code?')) {
                showLoading();

                fetch(`/admin/qr-codes/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        showAlert('success', data.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        showAlert('error', data.message);
                    }
                })
                .catch(error => {
                    hideLoading();
                    showAlert('error', 'An error occurred while deleting the QR code.');
                });
            }
        }

        function showBatchGenerate() {
            window.location.href = '{{ route('admin.qr-codes.create') }}#batch';
        }

        function exportData() {
            showLoading();

            const params = new URLSearchParams(window.location.search);
            fetch(`/admin/qr-codes/export?${params}`, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    // Create and download JSON file
                    const blob = new Blob([JSON.stringify(data.data, null, 2)], { type: 'application/json' });
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `qr-codes-export-${new Date().toISOString().split('T')[0]}.json`;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    window.URL.revokeObjectURL(url);

                    showAlert('success', `Exported ${data.count} QR codes successfully!`);
                } else {
                    showAlert('error', 'Export failed.');
                }
            })
            .catch(error => {
                hideLoading();
                showAlert('error', 'Export failed. Please try again.');
            });
        }

        // Section filtering based on grade level
        const allSections = {
            '11': ['C11a', 'C11b', 'C11c'],
            '12': ['C12a', 'C12b', 'C12c']
        };

        function updateSectionOptions() {
            const gradeLevel = document.getElementById('grade_level').value;
            const sectionSelect = document.getElementById('section');
            const currentValue = sectionSelect.value;

            // Clear current options except the first one
            sectionSelect.innerHTML = '<option value="">All Sections</option>';

            if (gradeLevel && allSections[gradeLevel]) {
                // Add sections for selected grade level
                allSections[gradeLevel].forEach(section => {
                    const option = document.createElement('option');
                    option.value = section;
                    option.textContent = 'Section ' + section;
                    if (section === currentValue) {
                        option.selected = true;
                    }
                    sectionSelect.appendChild(option);
                });
            } else {
                // Add all sections if no grade level selected
                Object.values(allSections).flat().forEach(section => {
                    const option = document.createElement('option');
                    option.value = section;
                    option.textContent = 'Section ' + section;
                    if (section === currentValue) {
                        option.selected = true;
                    }
                    sectionSelect.appendChild(option);
                });
            }
        }

        // Update sections when grade level changes
        document.getElementById('grade_level').addEventListener('change', function() {
            updateSectionOptions();
            submitFilters();
        });

        // Auto-submit form when other filters change
        document.getElementById('track').addEventListener('change', submitFilters);
        document.getElementById('section').addEventListener('change', submitFilters);
        document.getElementById('is_active').addEventListener('change', submitFilters);

        function submitFilters() {
            document.getElementById('filters-form').submit();
        }

        // Initialize section options on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateSectionOptions();
        });

        console.log('QR Codes management page loaded');
    </script>
</body>
</html>
