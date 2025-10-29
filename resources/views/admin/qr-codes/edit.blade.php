<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit QR Code - ISO Quality Education</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, rgba(66, 133, 244, 1), rgba(255, 215, 0, 1));
            min-height: 100vh;
        }

        .survey-main {
            background-image: none !important;
        }

        .qr-edit-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }

        .qr-edit-header {
            background: linear-gradient(135deg, rgba(66, 133, 244, 1), rgba(255, 215, 0, 1));
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
        }

        .qr-edit-header h1 {
            margin: 0 0 15px 0;
            font-size: 28px;
            font-weight: 700;
        }

        .qr-edit-header p {
            margin: 0;
            font-size: 16px;
            opacity: 0.9;
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
            margin-bottom: 20px;
            display: inline-block;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.9);
            color: black;
        }

        .edit-form-container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: rgba(66,133,244,1);
            outline: none;
        }

        .form-group small {
            color: #666;
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }

        .color-inputs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .color-input-group {
            text-align: center;
        }

        .color-input-group input[type="color"] {
            width: 60px;
            height: 40px;
            border: 2px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
        }

        .color-input-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 12px;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
            margin-right: 15px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: rgba(66,133,244,1);
            color: white;
        }

        .btn-primary:hover {
            background: #357ae8;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(66,133,244,0.4);
            color: white;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108,117,125,0.4);
            color: white;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220,53,69,0.4);
            color: white;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .qr-preview {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .qr-preview h4 {
            margin: 0 0 15px 0;
            color: #333;
        }

        .qr-image {
            width: 150px;
            height: 150px;
            border: 2px solid #ddd;
            border-radius: 8px;
            margin: 0 auto;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
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
            .form-grid {
                grid-template-columns: 1fr;
            }

            .color-inputs {
                grid-template-columns: 1fr;
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
        <div class="qr-edit-container">
            <!-- Header -->
            <a href="{{ route('admin.qr-codes.show', $qrCode->id) }}" class="back-btn">← Back to Details</a>

            <div class="qr-edit-header">
                <h1>Edit QR Code</h1>
                <p>Update QR Code: {{ $qrCode->name }}</p>
            </div>

            <!-- Alert Messages -->
            <div id="alert-container"></div>

            <!-- Current QR Preview -->
            <div class="qr-preview">
                <h4>Current QR Code</h4>
                @if($qrCode->file_path)
                    <img src="{{ $qrCode->file_url }}" alt="Current QR Code" class="qr-image">
                @else
                    <div class="qr-image" style="background: #f0f0f0; color: #666;">
                        <div>No QR Code File</div>
                    </div>
                @endif
            </div>

            <!-- Edit Form -->
            <div class="edit-form-container">
                <form id="edit-qr-form">
                    @csrf
                    @method('PUT')

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name">QR Code Name *</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $qrCode->name) }}" required placeholder="e.g., CSS Grade 11 Section A - Survey Access">
                            <small>Descriptive name for easy identification</small>
                        </div>

                        <div class="form-group">
                            <label for="track">Academic Track</label>
                            <select id="track" name="track">
                                <option value="CSS" {{ $qrCode->track === 'CSS' ? 'selected' : '' }}>CSS</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="grade_level">Grade Level</label>
                            <select id="grade_level" name="grade_level">
                                <option value="" {{ !$qrCode->grade_level ? 'selected' : '' }}>Select Grade Level</option>
                                <option value="11" {{ $qrCode->grade_level == '11' ? 'selected' : '' }}>Grade 11</option>
                                <option value="12" {{ $qrCode->grade_level == '12' ? 'selected' : '' }}>Grade 12</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="section">Section</label>
                            <select id="section" name="section">
                                <option value="" {{ !$qrCode->section ? 'selected' : '' }}>Select Section</option>
                                <option value="A" {{ $qrCode->section === 'A' ? 'selected' : '' }}>Section A</option>
                                <option value="B" {{ $qrCode->section === 'B' ? 'selected' : '' }}>Section B</option>
                                <option value="C" {{ $qrCode->section === 'C' ? 'selected' : '' }}>Section C</option>
                                <option value="D" {{ $qrCode->section === 'D' ? 'selected' : '' }}>Section D</option>
                                <option value="E" {{ $qrCode->section === 'E' ? 'selected' : '' }}>Section E</option>
                                <option value="F" {{ $qrCode->section === 'F' ? 'selected' : '' }}>Section F</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="academic_year">Academic Year</label>
                            <input type="text" id="academic_year" name="academic_year" value="{{ old('academic_year', $qrCode->academic_year) }}" required>
                            <small>Current academic year</small>
                        </div>

                        <div class="form-group">
                            <label for="semester">Semester</label>
                            <select id="semester" name="semester">
                                <option value="" {{ !$qrCode->semester ? 'selected' : '' }}>Select Semester</option>
                                <option value="1st" {{ $qrCode->semester === '1st' ? 'selected' : '' }}>1st Semester</option>
                                <option value="2nd" {{ $qrCode->semester === '2nd' ? 'selected' : '' }}>2nd Semester</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description (Optional)</label>
                        <textarea id="description" name="description" rows="3" placeholder="Additional details about this QR code">{{ old('description', $qrCode->description) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="target_url">Target URL</label>
                        <input type="url" id="target_url" name="target_url" value="{{ old('target_url', $qrCode->target_url) }}" required>
                        <small>URL that the QR code should link to</small>
                    </div>

                    <h3 style="margin: 30px 0 20px 0; color: #333; font-size: 20px;">Status & Settings</h3>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="is_active">Status</label>
                            <select id="is_active" name="is_active">
                                <option value="1" {{ $qrCode->is_active ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ !$qrCode->is_active ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="version">Version</label>
                            <input type="text" id="version" name="version" value="{{ old('version', $qrCode->version) }}" required>
                            <small>Version identifier for tracking</small>
                        </div>

                        <div class="form-group">
                            <label for="expires_at">Expiration Date (Optional)</label>
                            <input type="datetime-local" id="expires_at" name="expires_at" value="{{ $qrCode->expires_at ? $qrCode->expires_at->format('Y-m-d\TH:i') : '' }}">
                            <small>Leave empty for no expiration</small>
                        </div>

                        <div class="form-group">
                            <label for="scan_count">Scan Count</label>
                            <input type="number" id="scan_count" name="scan_count" value="{{ old('scan_count', $qrCode->scan_count) }}" min="0" readonly>
                            <small>Read-only: Total number of times scanned</small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Color Scheme</label>
                        <div class="color-inputs">
                            <div class="color-input-group">
                                <label for="foreground_color">Foreground (QR)</label>
                                <input type="color" id="foreground_color" name="foreground_color" value="{{ old('foreground_color', $qrCode->foreground_color) }}">
                            </div>
                            <div class="color-input-group">
                                <label for="background_color">Background</label>
                                <input type="color" id="background_color" name="background_color" value="{{ old('background_color', $qrCode->background_color) }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">🚀 Update QR Code</button>
                        <a href="{{ route('admin.qr-codes.show', $qrCode->id) }}" class="btn btn-secondary">Cancel</a>
                        <button type="button" onclick="deleteQRCode()" class="btn btn-danger">🗑️ Delete</button>
                    </div>
                </form>
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

        // Form submission
        document.getElementById('edit-qr-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            try {
                const response = await fetch(`/admin/qr-codes/{{ $qrCode->id }}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    showAlert('success', result.message);
                    setTimeout(() => {
                        window.location.href = result.redirect || '{{ route("admin.qr-codes.show", $qrCode->id) }}';
                    }, 2000);
                } else {
                    showAlert('error', result.message);
                }
            } catch (error) {
                showAlert('error', 'An error occurred while updating the QR code.');
            }
        });

        function deleteQRCode() {
            if (confirm('Are you sure you want to delete this QR code? This action cannot be undone.')) {
                fetch(`/admin/qr-codes/{{ $qrCode->id }}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('success', data.message);
                        setTimeout(() => {
                            window.location.href = '{{ route("admin.qr-codes.index") }}';
                        }, 2000);
                    } else {
                        showAlert('error', data.message);
                    }
                })
                .catch(error => {
                    showAlert('error', 'An error occurred while deleting the QR code.');
                });
            }
        }

        console.log('QR Code edit page loaded');
    </script>
</body>
</html>
