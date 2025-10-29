<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Details - ISO Quality Education</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, rgba(66, 133, 244, 1), rgba(255, 215, 0, 1));
            min-height: 100vh;
        }

        .survey-main {
            background-image: none !important;
        }

        .qr-detail-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .qr-detail-header {
            background: linear-gradient(135deg, rgba(66, 133, 244, 1), rgba(255, 215, 0, 1));
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .qr-detail-header h1 {
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

        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .qr-preview-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            text-align: center;
        }

        .qr-preview-image {
            width: 300px;
            height: 300px;
            border: 2px solid #ddd;
            border-radius: 12px;
            margin: 0 auto 20px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .qr-preview-image img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .qr-info-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .info-section {
            margin-bottom: 30px;
        }

        .info-section:last-child {
            margin-bottom: 0;
        }

        .info-section h3 {
            margin: 0 0 20px 0;
            color: #333;
            border-bottom: 2px solid rgba(66, 133, 244, 1);
            padding-bottom: 10px;
            font-size: 20px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .info-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid rgba(66, 133, 244, 1);
        }

        .info-label {
            font-weight: 600;
            color: #666;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 16px;
            color: #333;
            font-weight: 500;
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

        .actions-section {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .actions-section h3 {
            margin: 0 0 20px 0;
            color: #333;
            font-size: 20px;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .action-btn {
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            font-size: 14px;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .btn-primary {
            background: #4285F4;
            color: white;
        }

        .btn-primary:hover {
            background: #357ae8;
            color: white;
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
            color: white;
        }

        .btn-warning {
            background: #ffc107;
            color: #333;
        }

        .btn-warning:hover {
            background: #e0a800;
            color: #333;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
            color: white;
        }

        .btn-info {
            background: #17a2b8;
            color: white;
        }

        .btn-info:hover {
            background: #138496;
            color: white;
        }

        .scan-analytics {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .scan-analytics h3 {
            margin: 0 0 20px 0;
            color: #333;
            border-bottom: 2px solid rgba(255, 215, 0, 1);
            padding-bottom: 10px;
            font-size: 20px;
        }

        .analytics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .analytics-stat {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .analytics-stat-value {
            font-size: 32px;
            font-weight: 700;
            color: rgba(66, 133, 244, 1);
            margin-bottom: 5px;
        }

        .analytics-stat-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .recent-scans {
            margin-top: 20px;
        }

        .recent-scans h4 {
            margin: 0 0 15px 0;
            color: #333;
        }

        .scan-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }

        .scan-item:last-child {
            border-bottom: none;
        }

        .scan-timestamp {
            color: #666;
        }

        .scan-ip {
            color: #999;
            font-family: monospace;
        }

        .no-data {
            text-align: center;
            padding: 40px 20px;
            color: #666;
            font-style: italic;
        }

        .qr-url-display {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid rgba(66, 133, 244, 1);
            margin-top: 15px;
            word-break: break-all;
        }

        .qr-url-display small {
            color: #666;
            font-weight: 600;
            display: block;
            margin-bottom: 5px;
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

        @media (max-width: 768px) {
            .detail-grid {
                grid-template-columns: 1fr;
            }

            .qr-preview-image {
                width: 250px;
                height: 250px;
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
        <div class="qr-detail-container">
            <!-- Header -->
            <div class="qr-detail-header">
                <div>
                    <h1>QR Code Details</h1>
                    <p style="margin: 10px 0 0 0; opacity: 0.9;">ID: #{{ $qrCode->id }} | {{ $qrCode->name }}</p>
                </div>
                <a href="{{ route('admin.qr-codes.index') }}" class="back-btn">← Back to QR Codes</a>
            </div>

            <!-- Actions Section -->
            <div class="actions-section">
                <h3>Quick Actions</h3>
                <div class="actions-grid">
                    <a href="{{ route('admin.qr-codes.edit', $qrCode->id) }}" class="action-btn btn-warning">✏️ Edit QR Code</a>
                    <a href="{{ route('admin.qr-codes.download', $qrCode->id) }}" class="action-btn btn-success">📥 Download File</a>
                    <button onclick="copyToClipboard('{{ $qrCode->getPublicUrl() }}')" class="action-btn btn-info">📋 Copy Link</button>
                    <button onclick="printQRCode()" class="action-btn btn-primary">🖨️ Print</button>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="detail-grid">
                <!-- QR Code Preview -->
                <div class="qr-preview-card">
                    <h3>QR Code Preview</h3>
                    @if($qrCode->file_path)
                        <div class="qr-preview-image">
                            <img src="{{ $qrCode->file_url }}" alt="QR Code" id="qr-image">
                        </div>
                    @else
                        <div class="qr-preview-image" style="background: #f0f0f0; color: #666;">
                            <div>No QR Code File</div>
                        </div>
                    @endif

                    <div class="qr-url-display">
                        <small>QR Code URL:</small>
                        {{ $qrCode->getPublicUrl() }}
                    </div>

                    <div style="margin-top: 20px;">
                        <p style="margin: 0; font-size: 14px; color: #666;">
                            <strong>Target URL:</strong><br>
                            <small>{{ $qrCode->target_url }}</small>
                        </p>
                    </div>
                </div>

                <!-- QR Code Information -->
                <div class="qr-info-card">
                    <div class="info-section">
                        <h3>Basic Information</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Name</div>
                                <div class="info-value">{{ $qrCode->name }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Track</div>
                                <div class="info-value">{{ $qrCode->track }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Grade Level</div>
                                <div class="info-value">{{ $qrCode->grade_level ?: '-' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Section</div>
                                <div class="info-value">{{ $qrCode->section ?: '-' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Academic Year</div>
                                <div class="info-value">{{ $qrCode->academic_year }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Semester</div>
                                <div class="info-value">{{ $qrCode->semester ?: '-' }}</div>
                            </div>
                        </div>

                        @if($qrCode->description)
                            <div style="margin-top: 15px; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                                <div class="info-label">Description</div>
                                <div class="info-value">{{ $qrCode->description }}</div>
                            </div>
                        @endif
                    </div>

                    <div class="info-section">
                        <h3>Technical Details</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Format</div>
                                <div class="info-value" style="text-transform: uppercase;">{{ $qrCode->format }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Size</div>
                                <div class="info-value">{{ $qrCode->size }}px</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Version</div>
                                <div class="info-value">{{ $qrCode->version }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Status</div>
                                <div class="info-value">
                                    @if($qrCode->is_expired)
                                        <span class="status-badge status-expired">Expired</span>
                                    @elseif($qrCode->is_active)
                                        <span class="status-badge status-active">Active</span>
                                    @else
                                        <span class="status-badge status-inactive">Inactive</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="info-section">
                        <h3>Timestamps</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Created</div>
                                <div class="info-value">{{ $qrCode->created_at->format('M j, Y g:i A') }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Last Updated</div>
                                <div class="info-value">{{ $qrCode->updated_at->format('M j, Y g:i A') }}</div>
                            </div>
                            @if($qrCode->expires_at)
                                <div class="info-item">
                                    <div class="info-label">Expires</div>
                                    <div class="info-value">{{ $qrCode->expires_at->format('M j, Y g:i A') }}</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="info-section">
                        <h3>Created By</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Admin User</div>
                                <div class="info-value">{{ $qrCode->created_by ?: 'System' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scan Analytics -->
            <div class="scan-analytics">
                <h3>Scan Analytics</h3>

                <div class="analytics-grid">
                    <div class="analytics-stat">
                        <div class="analytics-stat-value">{{ $qrCode->scan_count }}</div>
                        <div class="analytics-stat-label">Total Scans</div>
                    </div>
                    <div class="analytics-stat">
                        <div class="analytics-stat-value">{{ $qrCode->is_active ? 'Yes' : 'No' }}</div>
                        <div class="analytics-stat-label">Currently Active</div>
                    </div>
                    <div class="analytics-stat">
                        <div class="analytics-stat-value">{{ $qrCode->is_expired ? 'Yes' : 'No' }}</div>
                        <div class="analytics-stat-label">Expired</div>
                    </div>
                    <div class="analytics-stat">
                        <div class="analytics-stat-value">
                            @if($qrCode->scan_count > 0)
                                {{ $qrCode->scan_count > 0 ? 'Recent' : 'None' }}
                            @else
                                None
                            @endif
                        </div>
                        <div class="analytics-stat-label">Recent Activity</div>
                    </div>
                </div>

                @if($qrCode->scan_analytics && count($qrCode->scan_analytics) > 0)
                    <div class="recent-scans">
                        <h4>Recent Scans</h4>
                        @foreach(array_slice($qrCode->scan_analytics, -5) as $scan)
                            <div class="scan-item">
                                <div>
                                    <div class="scan-timestamp">{{ \Carbon\Carbon::parse($scan['timestamp'])->format('M j, Y g:i A') }}</div>
                                    @if(isset($scan['user_agent']))
                                        <small style="color: #999;">{{ Str::limit($scan['user_agent'], 50) }}</small>
                                    @endif
                                </div>
                                @if(isset($scan['ip_address']))
                                    <div class="scan-ip">{{ $scan['ip_address'] }}</div>
                                @endif
                            </div>
                        @endforeach

                        @if(count($qrCode->scan_analytics) > 5)
                            <div style="text-align: center; margin-top: 10px;">
                                <small style="color: #666;">Showing last 5 of {{ count($qrCode->scan_analytics) }} total scans</small>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="no-data">
                        <p>No scan data available yet.</p>
                        <p>This QR code hasn't been scanned by users.</p>
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

    <script>
        // Set current year
        document.getElementById('currentYear').textContent = new Date().getFullYear();

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('QR Code URL copied to clipboard!');
            }, function(err) {
                console.error('Could not copy text: ', err);
                // Fallback for older browsers
                const textArea = document.createElement("textarea");
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                alert('QR Code URL copied to clipboard!');
            });
        }

        function printQRCode() {
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Print QR Code</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            text-align: center;
                            padding: 20px;
                        }
                        .qr-container {
                            max-width: 400px;
                            margin: 0 auto;
                            padding: 20px;
                            border: 2px solid #000;
                            border-radius: 10px;
                        }
                        .qr-image {
                            width: 300px;
                            height: 300px;
                            margin: 20px auto;
                            display: block;
                        }
                        .qr-info {
                            margin-top: 20px;
                        }
                        .qr-name {
                            font-size: 24px;
                            font-weight: bold;
                            margin-bottom: 10px;
                        }
                        .qr-details {
                            font-size: 14px;
                            color: #666;
                            margin-bottom: 10px;
                        }
                        .qr-url {
                            font-size: 12px;
                            color: #999;
                            word-break: break-all;
                        }
                    </style>
                </head>
                <body>
                    <div class="qr-container">
                        <h1>ISO Quality Education Survey</h1>
                        <img src="{{ $qrCode->file_url }}" alt="QR Code" class="qr-image" onload="window.print(); window.close();">
                        <div class="qr-info">
                            <div class="qr-name">{{ $qrCode->name }}</div>
                            <div class="qr-details">
                                {{ $qrCode->track }} Track |
                                @if($qrCode->grade_level)Grade {{ $qrCode->grade_level }} | @endif
                                @if($qrCode->section)Section {{ $qrCode->section }} | @endif
                                {{ $qrCode->academic_year }}
                            </div>
                            <div class="qr-url">{{ $qrCode->target_url }}</div>
                        </div>
                    </div>
                </body>
                </html>
            `);
            printWindow.document.close();
        }

        console.log('QR Code details page loaded');
    </script>
</body>
</html>
