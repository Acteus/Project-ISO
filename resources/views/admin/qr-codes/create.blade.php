<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create QR Code - ISO Quality Education</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, rgba(66, 133, 244, 1), rgba(255, 215, 0, 1));
            min-height: 100vh;
        }

        .survey-main {
            background-image: none !important;
        }

        .qr-create-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .qr-create-header {
            background: linear-gradient(135deg, rgba(66, 133, 244, 1), rgba(255, 215, 0, 1));
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
        }

        .qr-create-header h1 {
            margin: 0 0 15px 0;
            font-size: 28px;
            font-weight: 700;
        }

        .qr-create-header p {
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

        .creation-methods {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .method-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            border: 3px solid transparent;
        }

        .method-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        }

        .method-card.active {
            border-color: rgba(66, 133, 244, 1);
            background: linear-gradient(135deg, rgba(66, 133, 244, 0.05), rgba(255, 215, 0, 0.05));
        }

        .method-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #4285F4, #34A853);
            color: white;
            font-size: 32px;
        }

        .method-card.batch .method-icon {
            background: linear-gradient(135deg, #FF9800, #F44336);
        }

        .method-card h3 {
            margin: 0 0 15px 0;
            color: #333;
            font-size: 24px;
        }

        .method-card p {
            color: #666;
            margin: 0;
            line-height: 1.5;
        }

        .form-section {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            display: none;
        }

        .form-section.active {
            display: block;
        }

        .form-section h2 {
            margin: 0 0 25px 0;
            color: #333;
            border-bottom: 3px solid rgba(66, 133, 244, 1);
            padding-bottom: 10px;
            font-size: 24px;
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

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .checkbox-group input[type="checkbox"] {
            width: auto;
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

        .btn-warning {
            background: #ffc107;
            color: #333;
        }

        .btn-warning:hover {
            background: #e0a800;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255,193,7,0.4);
        }

        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .batch-sections {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }

        .section-option {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .section-option:hover {
            border-color: rgba(66,133,244,1);
        }

        .section-option input[type="checkbox"] {
            width: auto;
        }

        .preview-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            text-align: center;
        }

        .preview-section h4 {
            margin: 0 0 15px 0;
            color: #333;
        }

        .qr-preview {
            width: 200px;
            height: 200px;
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

        @media (max-width: 768px) {
            .creation-methods {
                grid-template-columns: 1fr;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .color-inputs {
                grid-template-columns: 1fr;
            }
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
        <div class="qr-create-container">
            <!-- Header -->
            <a href="{{ route('admin.qr-codes.index') }}" class="back-btn">← Back to QR Codes</a>

            <div class="qr-create-header">
                <h1>Create QR Code</h1>
                <p>Generate QR codes for easy survey access via mobile devices</p>
            </div>

            <!-- Alert Messages -->
            <div id="alert-container"></div>

            <!-- Creation Method Selection -->
            <div class="creation-methods">
                <div class="method-card active" id="single-method" onclick="selectMethod('single')">
                    <div class="method-icon">📱</div>
                    <h3>Single QR Code</h3>
                    <p>Create a custom QR code for specific targets with full customization options</p>
                </div>

                <div class="method-card batch" id="batch-method" onclick="selectMethod('batch')">
                    <div class="method-icon">📊</div>
                    <h3>Batch Generation</h3>
                    <p>Generate multiple QR codes for different CSS sections at once</p>
                </div>
            </div>

            <!-- Single QR Code Form -->
            <div class="form-section active" id="single-form">
                <h2>Create Single QR Code</h2>

                <form id="single-qr-form">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name">QR Code Name *</label>
                            <input type="text" id="name" name="name" required placeholder="e.g., CSS Grade 11 Section A - Survey Access">
                            <small>Descriptive name for easy identification</small>
                        </div>

                        <div class="form-group">
                            <label for="track">Academic Track</label>
                            <select id="track" name="track">
                                <option value="CSS" selected>CSS</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="grade_level">Grade Level</label>
                            <select id="grade_level" name="grade_level">
                                <option value="">Select Grade Level</option>
                                <option value="11">Grade 11</option>
                                <option value="12">Grade 12</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="section">Section</label>
                            <select id="section" name="section">
                                <option value="">Select Section</option>
                                @foreach($cssSections as $section)
                                    <option value="{{ $section }}">Section {{ $section }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="academic_year">Academic Year</label>
                            <input type="text" id="academic_year" name="academic_year" value="{{ $currentAcademicYear }}" required>
                            <small>Current academic year</small>
                        </div>

                        <div class="form-group">
                            <label for="semester">Semester</label>
                            <select id="semester" name="semester">
                                <option value="">Select Semester</option>
                                <option value="1st">1st Semester</option>
                                <option value="2nd">2nd Semester</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description (Optional)</label>
                        <textarea id="description" name="description" rows="3" placeholder="Additional details about this QR code"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="target_url">Target URL</label>
                        <input type="url" id="target_url" name="target_url" value="{{ route('welcome') }}" required>
                        <small>URL that the QR code should link to</small>
                    </div>

                    <h3 style="margin: 30px 0 20px 0; color: #333; font-size: 20px;">Customization Options</h3>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="format">File Format</label>
                            <select id="format" name="format">
                                <option value="png" selected>PNG (Recommended)</option>
                                <option value="svg">SVG (Vector)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="size">Size (pixels)</label>
                            <select id="size" name="size">
                                <option value="200">Small (200px)</option>
                                <option value="300" selected>Medium (300px)</option>
                                <option value="500">Large (500px)</option>
                                <option value="700">Extra Large (700px)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="version">Version</label>
                            <input type="text" id="version" name="version" value="1.0" required>
                            <small>Version identifier for tracking</small>
                        </div>

                        <div class="form-group">
                            <label for="expires_at">Expiration Date (Optional)</label>
                            <input type="datetime-local" id="expires_at" name="expires_at">
                            <small>Leave empty for no expiration</small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Color Scheme</label>
                        <div class="color-inputs">
                            <div class="color-input-group">
                                <label for="foreground_color">Foreground (QR)</label>
                                <input type="color" id="foreground_color" name="foreground_color" value="#000000">
                            </div>
                            <div class="color-input-group">
                                <label for="background_color">Background</label>
                                <input type="color" id="background_color" name="background_color" value="#FFFFFF">
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" onclick="previewSingle()" class="btn btn-warning">👁️ Preview</button>
                        <button type="submit" class="btn btn-primary">🚀 Generate QR Code</button>
                    </div>
                </form>

                <!-- Preview Section -->
                <div class="preview-section" id="single-preview" style="display: none;">
                    <h4>QR Code Preview</h4>
                    <div class="qr-preview" id="single-qr-preview">
                        Preview will appear here
                    </div>
                </div>
            </div>

            <!-- Batch QR Code Form -->
            <div class="form-section" id="batch-form">
                <h2>Batch QR Code Generation</h2>

                <form id="batch-qr-form">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="batch_target_url">Target URL</label>
                            <input type="url" id="batch_target_url" name="target_url" value="{{ route('welcome') }}" required>
                            <small>All QR codes will link to this URL</small>
                        </div>

                        <div class="form-group">
                            <label for="batch_format">File Format</label>
                            <select id="batch_format" name="format">
                                <option value="png" selected>PNG (Recommended)</option>
                                <option value="svg">SVG (Vector)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="batch_size">Size (pixels)</label>
                            <select id="batch_size" name="size">
                                <option value="200">Small (200px)</option>
                                <option value="300" selected>Medium (300px)</option>
                                <option value="500">Large (500px)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="batch_academic_year">Academic Year</label>
                            <input type="text" id="batch_academic_year" name="academic_year" value="{{ $currentAcademicYear }}" required>
                        </div>

                        <div class="form-group">
                            <label for="batch_semester">Semester</label>
                            <select id="batch_semester" name="semester">
                                <option value="">Select Semester</option>
                                <option value="1st">1st Semester</option>
                                <option value="2nd">2nd Semester</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="batch_version">Version</label>
                            <input type="text" id="batch_version" name="version" value="1.0" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Grade Levels to Generate</label>
                        <div class="batch-sections">
                            <div class="section-option">
                                <input type="checkbox" id="grade_11" name="grade_levels[]" value="11" checked>
                                <label for="grade_11">Grade 11</label>
                            </div>
                            <div class="section-option">
                                <input type="checkbox" id="grade_12" name="grade_levels[]" value="12">
                                <label for="grade_12">Grade 12</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Sections to Generate</label>
                        <div class="batch-sections">
                            @foreach($cssSections as $section)
                                <div class="section-option">
                                    <input type="checkbox" id="section_{{ $section }}" name="sections[]" value="{{ $section }}" checked>
                                    <label for="section_{{ $section }}">Section {{ $section }}</label>
                                </div>
                            @endforeach
                        </div>
                        <small>Select specific sections for batch generation</small>
                    </div>

                    <div class="form-group">
                        <label>Color Scheme</label>
                        <div class="color-inputs">
                            <div class="color-input-group">
                                <label for="batch_foreground_color">Foreground (QR)</label>
                                <input type="color" id="batch_foreground_color" name="foreground_color" value="#000000">
                            </div>
                            <div class="color-input-group">
                                <label for="batch_background_color">Background</label>
                                <input type="color" id="batch_background_color" name="background_color" value="#FFFFFF">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="batch_expires_at">Expiration Date (Optional)</label>
                        <input type="datetime-local" id="batch_expires_at" name="expires_at">
                    </div>

                    <div class="form-actions">
                        <button type="button" onclick="previewBatch()" class="btn btn-warning">👁️ Preview</button>
                        <button type="submit" class="btn btn-success">🚀 Generate All QR Codes</button>
                    </div>
                </form>

                <!-- Preview Section -->
                <div class="preview-section" id="batch-preview" style="display: none;">
                    <h4>Batch Generation Summary</h4>
                    <div id="batch-summary">
                        Generation summary will appear here
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loading-overlay">
        <div style="text-align: center; color: white;">
            <div class="loading-spinner"></div>
            <p style="margin-top: 20px; font-size: 16px;">Generating QR codes...</p>
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

        let currentMethod = 'single';

        function selectMethod(method) {
            currentMethod = method;

            // Update method cards
            document.querySelectorAll('.method-card').forEach(card => {
                card.classList.remove('active');
            });
            document.getElementById(method + '-method').classList.add('active');

            // Update form sections
            document.querySelectorAll('.form-section').forEach(section => {
                section.classList.remove('active');
            });
            document.getElementById(method + '-form').classList.add('active');
        }

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

        function previewSingle() {
            const form = document.getElementById('single-qr-form');
            const formData = new FormData(form);

            const previewData = {
                name: formData.get('name') || 'Sample QR Code',
                target_url: formData.get('target_url'),
                format: formData.get('format'),
                size: formData.get('size'),
                foreground_color: formData.get('foreground_color'),
                background_color: formData.get('background_color'),
                track: formData.get('track'),
                grade_level: formData.get('grade_level') || 'N/A',
                section: formData.get('section') || 'N/A'
            };

            const previewHtml = `
                <div style="text-align: left; max-width: 300px; margin: 0 auto;">
                    <p><strong>Name:</strong> ${previewData.name}</p>
                    <p><strong>Track:</strong> ${previewData.track} | <strong>Grade:</strong> ${previewData.grade_level} | <strong>Section:</strong> ${previewData.section}</p>
                    <p><strong>Format:</strong> ${previewData.format.toUpperCase()} | <strong>Size:</strong> ${previewData.size}px</p>
                    <p><strong>Target URL:</strong> <small>${previewData.target_url}</small></p>
                    <div style="margin-top: 15px;">
                        <div style="width: 20px; height: 20px; background: ${previewData.foreground_color}; display: inline-block; border: 1px solid #ddd;"></div>
                        <span style="margin: 0 10px;">QR Color</span>
                        <div style="width: 20px; height: 20px; background: ${previewData.background_color}; display: inline-block; border: 1px solid #ddd;"></div>
                        <span style="margin-left: 10px;">Background</span>
                    </div>
                </div>
            `;

            document.getElementById('single-qr-preview').innerHTML = previewHtml;
            document.getElementById('single-preview').style.display = 'block';
        }

        function previewBatch() {
            const form = document.getElementById('batch-qr-form');
            const formData = new FormData(form);

            const gradeLevels = formData.getAll('grade_levels[]');
            const sections = formData.getAll('sections[]');
            const totalCodes = gradeLevels.length * sections.length;

            const previewHtml = `
                <div style="text-align: left; max-width: 400px; margin: 0 auto;">
                    <p><strong>Total QR Codes:</strong> ${totalCodes}</p>
                    <p><strong>Grade Levels:</strong> ${gradeLevels.map(g => `Grade ${g}`).join(', ') || 'None'}</p>
                    <p><strong>Sections:</strong> ${sections.map(s => `Section ${s}`).join(', ') || 'None'}</p>
                    <p><strong>Format:</strong> ${formData.get('format').toUpperCase()} | <strong>Size:</strong> ${formData.get('size')}px</p>
                    <p><strong>Academic Year:</strong> ${formData.get('academic_year')}</p>
                    <p><strong>Target URL:</strong> <small>${formData.get('target_url')}</small></p>
                </div>
            `;

            document.getElementById('batch-summary').innerHTML = previewHtml;
            document.getElementById('batch-preview').style.display = 'block';
        }

        // Single QR Code Form Submission
        document.getElementById('single-qr-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            showLoading();

            try {
                const response = await fetch('{{ route("admin.qr-codes.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const result = await response.json();

                hideLoading();

                if (result.success) {
                    showAlert('success', result.message);
                    setTimeout(() => {
                        window.location.href = result.redirect;
                    }, 2000);
                } else {
                    showAlert('error', result.message);
                }
            } catch (error) {
                hideLoading();
                showAlert('error', 'An error occurred while generating the QR code.');
            }
        });

        // Batch QR Code Form Submission
        document.getElementById('batch-qr-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            // Validate selections
            const gradeLevels = formData.getAll('grade_levels[]');
            const sections = formData.getAll('sections[]');

            if (gradeLevels.length === 0 || sections.length === 0) {
                showAlert('error', 'Please select at least one grade level and one section.');
                return;
            }

            showLoading();

            try {
                const response = await fetch('{{ route("admin.qr-codes.batch-generate") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const result = await response.json();

                hideLoading();

                if (result.success) {
                    showAlert('success', result.message);
                    setTimeout(() => {
                        window.location.href = '{{ route("admin.qr-codes.index") }}';
                    }, 2000);
                } else {
                    showAlert('error', result.message);
                }
            } catch (error) {
                hideLoading();
                showAlert('error', 'An error occurred while generating QR codes.');
            }
        });

        // Check for hash parameter to switch to batch mode
        if (window.location.hash === '#batch') {
            selectMethod('batch');
        }

        console.log('QR Code creation page loaded');
    </script>
</body>
</html>
