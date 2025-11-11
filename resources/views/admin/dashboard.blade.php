@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@push('styles')
<style>
    /* Dashboard-specific styles */
    .dashboard-header {
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

    .dashboard-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, #4285F4, #FF8C00, #FFD700);
    }

    .admin-info-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(15px);
        padding: 30px;
        border-radius: 16px;
        margin-bottom: 40px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 30px rgba(66, 133, 244, 0.15);
    }

    .admin-info-card h3 {
        margin-top: 0;
        color: #2c3e50;
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .student-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .info-item {
        background: linear-gradient(135deg, rgba(66, 133, 244, 0.05), rgba(255, 140, 0, 0.05));
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s ease;
    }

    .info-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }

    .info-label {
        font-weight: 700;
        color: #4285F4;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
    }

    .info-value {
        font-size: 18px;
        color: #2c3e50;
        font-weight: 600;
    }

    .actions-section {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }

    .action-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        padding: 35px 30px;
        border-radius: 20px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.1);
        text-align: center;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .action-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, #4285F4, #FF8C00, #FFD700);
    }

    .action-card:hover {
        transform: translateY(-10px) scale(1.03);
        box-shadow: 0 25px 60px rgba(0,0,0,0.2);
    }

    .action-card-icon {
        width: 90px;
        height: 90px;
        margin: 0 auto 25px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.4s ease;
        position: relative;
    }

    .action-card:hover .action-card-icon {
        transform: scale(1.15) rotate(5deg);
    }

    .action-card.analytics .action-card-icon {
        background: linear-gradient(135deg, #17a2b8, #138496);
        box-shadow: 0 10px 30px rgba(23, 162, 184, 0.4);
    }

    .action-card.export .action-card-icon {
        background: linear-gradient(135deg, #28a745, #20c997);
        box-shadow: 0 10px 30px rgba(40, 167, 69, 0.4);
    }

    .action-card.audit .action-card-icon {
        background: linear-gradient(135deg, #ffc107, #ff9800);
        box-shadow: 0 10px 30px rgba(255, 193, 7, 0.4);
    }

    .action-card.reports .action-card-icon {
        background: linear-gradient(135deg, #6f42c1, #5a32a3);
        box-shadow: 0 10px 30px rgba(111, 66, 193, 0.4);
    }

    .action-card.qr-codes .action-card-icon {
        background: linear-gradient(135deg, #FF5722, #FF9800);
        box-shadow: 0 10px 30px rgba(255, 87, 34, 0.4);
    }

    .action-card.metrics .action-card-icon {
        background: linear-gradient(135deg, #9C27B0, #673AB7);
        box-shadow: 0 10px 30px rgba(156, 39, 176, 0.4);
    }

    .action-card-icon svg {
        width: 45px;
        height: 45px;
        fill: white;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
    }

    .action-card h3 {
        margin: 0 0 15px 0;
        color: #2c3e50;
        font-size: 22px;
        font-weight: 700;
        line-height: 1.3;
    }

    .action-card p {
        color: #5a6c7d;
        margin: 0 0 25px 0;
        font-size: 16px;
        line-height: 1.6;
        font-weight: 500;
    }

    .recent-responses {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(15px);
        padding: 30px;
        border-radius: 18px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.1);
        margin-bottom: 40px;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .recent-responses h3 {
        margin-top: 0;
        color: #2c3e50;
        font-size: 24px;
        font-weight: 700;
        border-bottom: 3px solid transparent;
        border-image: linear-gradient(90deg, #4285F4, #FF8C00) 1;
        padding-bottom: 15px;
        margin-bottom: 25px;
    }

    .response-item {
        padding: 18px 20px;
        border-bottom: 1px solid rgba(0,0,0,0.06);
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.3s ease;
        border-radius: 12px;
        margin-bottom: 8px;
    }

    .response-item:hover {
        background: linear-gradient(135deg, rgba(66, 133, 244, 0.08), rgba(255, 140, 0, 0.08));
        transform: translateX(5px);
        box-shadow: 0 5px 15px rgba(66, 133, 244, 0.2);
    }

    .response-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .response-info {
        flex: 1;
    }

    .response-track {
        font-weight: 700;
        color: #4285F4;
        font-size: 16px;
        margin-bottom: 4px;
    }

    .response-date {
        color: #6c757d;
        font-size: 14px;
        font-weight: 500;
    }

    .track-distribution {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(15px);
        padding: 30px;
        border-radius: 18px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .track-distribution h3 {
        margin-top: 0;
        color: #2c3e50;
        font-size: 24px;
        font-weight: 700;
        border-bottom: 3px solid transparent;
        border-image: linear-gradient(90deg, #FF8C00, #FFD700) 1;
        padding-bottom: 15px;
        margin-bottom: 25px;
    }

    .track-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        border-bottom: 1px solid rgba(0,0,0,0.06);
        transition: all 0.3s ease;
        border-radius: 12px;
        margin-bottom: 8px;
    }

    .track-item:hover {
        background: linear-gradient(135deg, rgba(255, 140, 0, 0.08), rgba(255, 215, 0, 0.08));
        transform: translateX(5px);
        box-shadow: 0 5px 15px rgba(255, 140, 0, 0.2);
    }

    .track-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .track-name {
        font-weight: 700;
        color: #2c3e50;
        font-size: 16px;
    }

    .track-count {
        background: linear-gradient(135deg, #FF8C00, #FFD700);
        color: white;
        padding: 8px 16px;
        border-radius: 25px;
        font-weight: 700;
        font-size: 14px;
        box-shadow: 0 4px 12px rgba(255, 140, 0, 0.3);
    }

    /* Export Modal Styles */
    .export-modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(10px);
        z-index: 9999;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .export-modal-overlay.active {
        display: flex;
        animation: fadeIn 0.3s ease forwards;
    }

    .export-modal {
        background: linear-gradient(135deg, #4285f4, #ffd700);
        border-radius: 24px;
        padding: 3px;
        max-width: 680px;
        width: 90%;
        box-shadow: 0 25px 70px rgba(0, 0, 0, 0.5);
        animation: slideUp 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        position: relative;
    }

    .export-modal-inner {
        background: white;
        border-radius: 22px;
        padding: 45px 40px;
        position: relative;
    }

    .modal-close {
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(255, 255, 255, 0.9);
        border: 2px solid rgba(108, 117, 125, 0.2);
        font-size: 24px;
        color: #6c757d;
        cursor: pointer;
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.3s ease;
        font-weight: 300;
        z-index: 10;
    }

    .modal-close:hover {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
        border-color: transparent;
        transform: rotate(90deg) scale(1.1);
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
    }

    .export-modal-header {
        text-align: center;
        margin-bottom: 35px;
    }

    .export-modal-header h2 {
        font-size: 32px;
        background: linear-gradient(135deg, #4285F4, #FF8C00);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 12px;
        font-weight: 800;
    }

    .export-options {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
        margin-bottom: 35px;
    }

    .export-option {
        background: linear-gradient(135deg, rgba(66, 133, 244, 0.08), rgba(255, 215, 0, 0.08));
        border: 3px solid transparent;
        border-radius: 18px;
        padding: 32px 18px;
        text-align: center;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .export-option:hover {
        transform: translateY(-8px) scale(1.03);
        box-shadow: 0 15px 40px rgba(66, 133, 244, 0.3);
        border-color: rgba(66, 133, 244, 0.5);
    }

    .export-option.selected {
        background: linear-gradient(135deg, rgba(66, 133, 244, 0.2), rgba(255, 215, 0, 0.2));
        border-color: #4285F4;
        box-shadow: 0 15px 45px rgba(66, 133, 244, 0.4);
        transform: translateY(-8px) scale(1.05);
    }

    .export-option-icon {
        font-size: 56px;
        margin-bottom: 16px;
        display: block;
    }

    .export-option-title {
        font-size: 19px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 10px;
    }

    .export-option-desc {
        font-size: 13px;
        color: #6c757d;
        line-height: 1.5;
    }

    .export-modal-actions {
        display: flex;
        gap: 15px;
        justify-content: center;
    }

    .modal-btn {
        padding: 16px 40px;
        border: none;
        border-radius: 14px;
        font-weight: 700;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-transform: uppercase;
        letter-spacing: 1.5px;
    }

    .modal-btn-primary {
        background: linear-gradient(135deg, #4285F4, #2c6cd6);
        color: white;
        box-shadow: 0 8px 25px rgba(66, 133, 244, 0.4);
    }

    .modal-btn-primary:hover:not(:disabled) {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 12px 40px rgba(66, 133, 244, 0.6);
    }

    .modal-btn-secondary {
        background: linear-gradient(135deg, #6c757d, #5a6268);
        color: white;
    }

    @media (max-width: 768px) {
        .export-options {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="admin-container">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <h1>Admin Dashboard</h1>
        <p>Welcome back, {{ $admin->name }}! Here's your comprehensive ISO 21001 Survey analytics overview with real-time insights and management tools.</p>
    </div>

    <!-- Progress Alerts Section -->
    <div class="progress-alerts" id="progress-alerts" style="margin-bottom: 40px;">
        <!-- Dynamic alerts loaded via JavaScript -->
    </div>

    <!-- Admin Information -->
    <div class="admin-info-card">
        <h3>Administrator Information</h3>
        <div class="student-info-grid">
            <div class="info-item">
                <div class="info-label">Username</div>
                <div class="info-value">{{ $admin->username }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Full Name</div>
                <div class="info-value">{{ $admin->name }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Role</div>
                <div class="info-value">System Administrator</div>
            </div>
        </div>
    </div>

    <!-- Metrics Overview -->
    <div class="metrics-grid">
        <div class="metric-card">
            <div class="metric-value">{{ $totalResponses }}</div>
            <div class="metric-label">Total Survey Responses</div>
        </div>
        <div class="metric-card">
            <div class="metric-value">{{ $responsesByTrack->count() }}</div>
            <div class="metric-label">Active Tracks</div>
        </div>
        <div class="metric-card">
            <div class="metric-value">{{ $recentResponses->count() }}</div>
            <div class="metric-label">Recent Responses</div>
        </div>
        <div class="metric-card">
            <div class="metric-value">{{ \App\Models\AuditLog::where('action', 'submit_survey_response')->count() }}</div>
            <div class="metric-label">Audit Events</div>
        </div>
    </div>

    <!-- Action Cards -->
    <div class="actions-section">
        <div class="action-card analytics">
            <div class="action-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
                </svg>
            </div>
            <h3>View Detailed Analytics</h3>
            <p>Access comprehensive survey analytics, trends, and insights from the ISO 21001 quality education system.</p>
            <a href="{{ route('api.survey.analytics') }}" class="btn btn-primary" target="_blank">View Analytics</a>
        </div>

        <div class="action-card qr-codes">
            <div class="action-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M12,2C6.48,2 2,6.48 2,12C2,17.52 6.48,22 12,22C17.52,22 22,17.52 22,12C22,6.48 17.52,2 12,2M8,17C8,15 10,15 10,13C10,11 8,11 8,9C8,7 10,7 10,5C10,3 8,3 6,3H4C2.9,3 2,3.9 2,5V9C2,11.09 3.09,12 4,12H8M13,15C13,17 11,17 11,19C11,21 13,21 13,23C13,25 11,25 9,25H5C3.9,25 3,24.1 3,23V19C3,16.91 4.09,16 5,16H9C10.09,16 11,16.91 11,18V19H13C15.09,19 16,17.09 16,15H13M13,7H9C7.9,7 7,7.9 7,9V11C7,12.09 8.09,13 9,13H11C12.09,13 13,12.09 13,11V9Z"/>
                </svg>
            </div>
            <h3>QR Code Management</h3>
            <p>Generate and manage QR codes for easy survey access via mobile devices. Create individual or batch QR codes for CSS sections.</p>
            <a href="{{ route('admin.qr-codes.index') }}" class="btn btn-primary">Manage QR Codes</a>
        </div>

        <div class="action-card reports">
            <div class="action-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,4A8,8 0 0,1 20,12A8,8 0 0,1 12,20A8,8 0 0,1 4,12A8,8 0 0,1 12,4M12,6A6,6 0 0,0 6,12A6,6 0 0,0 12,18A6,6 0 0,0 18,12A6,6 0 0,0 12,6M12,8A4,4 0 0,1 16,12A4,4 0 0,1 12,16A4,4 0 0,1 8,12A4,4 0 0,1 12,8Z"/>
                </svg>
            </div>
            <h3>AI Insights Dashboard</h3>
            <p>Access advanced AI-powered analytics, compliance predictions, and machine learning insights.</p>
            <a href="{{ route('admin.ai.insights') }}" class="btn btn-primary">AI Insights</a>
        </div>

        <div class="action-card export">
            <div class="action-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/>
                </svg>
            </div>
            <h3>Export Data</h3>
            <p>Export survey responses and analytics reports in Excel, CSV, or PDF format for further analysis.</p>
            <button onclick="showExportModal()" class="btn btn-success">Export Data</button>
        </div>

        <div class="action-card audit">
            <div class="action-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                </svg>
            </div>
            <h3>Audit Logs</h3>
            <p>Review system audit logs to ensure compliance with ISO 21001 traceability requirements.</p>
            <a href="{{ route('admin.audit.logs') }}" class="btn btn-warning">View Logs</a>
        </div>

        <div class="action-card reports">
            <div class="action-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                </svg>
            </div>
            <h3>Send Reports</h3>
            <p>Send weekly progress reports and monthly compliance reports to administrators via email.</p>
            <a href="{{ route('admin.reports') }}" class="btn btn-primary">Manage Reports</a>
        </div>

        <div class="action-card metrics">
            <div class="action-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3M19,5V19H5V5H19M7,10H9V17H7V10M11,7H13V17H11V7M15,13H17V17H15V13Z"/>
                </svg>
            </div>
            <h3>Upload Indirect Metrics</h3>
            <p>Upload student performance metrics (grades, attendance, participation) for ISO 21001 direct vs indirect validation.</p>
            <a href="{{ route('admin.indirect-metrics.index') }}" class="btn btn-primary">Upload Metrics</a>
        </div>
    </div>

    <!-- Recent Responses -->
    <div class="recent-responses">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h3 style="margin: 0;">Recent Survey Responses</h3>
            <a href="{{ route('admin.responses') }}" class="btn btn-success" style="padding: 12px 24px; font-size: 14px; text-decoration: none;">View All Responses →</a>
        </div>
        @if($recentResponses->count() > 0)
            @foreach($recentResponses as $response)
                <div class="response-item">
                    <div class="response-info">
                        <div class="response-track">{{ $response->track }} Track - Response #{{ $response->id }}</div>
                        <div class="response-date">{{ $response->created_at->format('M j, Y g:i A') }}</div>
                    </div>
                    <div style="display: flex; gap: 12px; align-items: center;">
                        <span style="background: linear-gradient(135deg, #28a745, #20c997); color: white; padding: 8px 16px; border-radius: 20px; font-size: 12px; font-weight: 600;">Completed</span>
                        <a href="{{ route('admin.response.view', $response->id) }}" class="btn btn-primary" style="padding: 10px 18px; font-size: 14px; text-decoration: none;">View Details</a>
                    </div>
                </div>
            @endforeach
        @else
            <div class="no-data">
                <p>No survey responses yet.</p>
                <p>Survey responses will appear here once students start submitting their feedback.</p>
            </div>
        @endif
    </div>

    <!-- Track Distribution -->
    <div class="track-distribution">
        <h3>Responses by Academic Track</h3>
        @if($responsesByTrack->count() > 0)
            @foreach($responsesByTrack as $track)
                <div class="track-item">
                    <div class="track-name">{{ $track->track }} Track</div>
                    <div class="track-count">{{ $track->count }}</div>
                </div>
            @endforeach
        @else
            <div class="no-data">
                <p>No track distribution data available yet.</p>
            </div>
        @endif
    </div>
</div>

<!-- Export Format Selection Modal -->
<div class="export-modal-overlay" id="exportModal">
    <div class="export-modal">
        <div class="export-modal-inner">
            <button class="modal-close" onclick="closeExportModal()">&times;</button>

            <div class="export-modal-header">
                <h2>Choose Export Format</h2>
                <p>Select your preferred format for exporting survey data</p>
            </div>

            <div class="export-options">
                <div class="export-option" data-format="excel" onclick="selectFormat('excel')">
                    <span class="export-option-icon">📗</span>
                    <div class="export-option-title">Excel</div>
                    <div class="export-option-desc">Best for data analysis and spreadsheets</div>
                </div>
                <div class="export-option" data-format="csv" onclick="selectFormat('csv')">
                    <span class="export-option-icon">📄</span>
                    <div class="export-option-title">CSV</div>
                    <div class="export-option-desc">Universal format for all systems</div>
                </div>
                <div class="export-option" data-format="pdf" onclick="selectFormat('pdf')">
                    <span class="export-option-icon">📕</span>
                    <div class="export-option-title">PDF</div>
                    <div class="export-option-desc">Professional report format</div>
                </div>
            </div>

            <div class="export-modal-actions">
                <button class="modal-btn modal-btn-secondary" onclick="closeExportModal()">Cancel</button>
                <button class="modal-btn modal-btn-primary" id="exportButton" onclick="performExport()" disabled>
                    Export Data
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Export Modal Functions
    let selectedFormat = null;

    function showExportModal() {
        document.getElementById('exportModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeExportModal() {
        document.getElementById('exportModal').classList.remove('active');
        document.body.style.overflow = '';
        selectedFormat = null;
        document.querySelectorAll('.export-option').forEach(option => {
            option.classList.remove('selected');
        });
        document.getElementById('exportButton').disabled = true;
    }

    function selectFormat(format) {
        selectedFormat = format;
        document.querySelectorAll('.export-option').forEach(option => {
            option.classList.remove('selected');
        });
        document.querySelector(`[data-format="${format}"]`).classList.add('selected');
        document.getElementById('exportButton').disabled = false;
    }

    function performExport() {
        if (!selectedFormat) {
            AdminUtils.showError('Please select a format first');
            return;
        }

        const exportButton = document.getElementById('exportButton');
        const originalText = exportButton.textContent;
        exportButton.textContent = 'Exporting...';
        exportButton.disabled = true;

        let exportUrl = '';
        switch(selectedFormat) {
            case 'excel':
                exportUrl = '{{ route("api.export.excel") }}';
                break;
            case 'csv':
                exportUrl = '{{ route("api.export.csv") }}';
                break;
            case 'pdf':
                exportUrl = '{{ route("api.export.pdf") }}';
                break;
            default:
                AdminUtils.showError('Invalid format selected');
                exportButton.textContent = originalText;
                exportButton.disabled = false;
                return;
        }

        window.open(exportUrl, '_blank');
        AdminUtils.showSuccess('Export started!');
        
        setTimeout(() => {
            exportButton.textContent = originalText;
            closeExportModal();
        }, 1000);
    }

    // Close modal when clicking outside
    document.getElementById('exportModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeExportModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && document.getElementById('exportModal').classList.contains('active')) {
            closeExportModal();
        }
    });

    // Enhanced progress alerts loading
    async function loadProgressAlerts() {
        try {
            const response = await fetch('/api/visualizations/progress-alerts', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': AdminUtils.getCSRFToken()
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();
            const alerts = result.data;
            updateProgressAlerts(alerts);
        } catch (error) {
            console.error('Error loading progress alerts:', error);
        }
    }

    function updateProgressAlerts(alerts) {
        const container = document.getElementById('progress-alerts');
        if (!alerts || alerts.length === 0) {
            container.innerHTML = '';
            return;
        }

        let alertsHtml = '';
        alerts.forEach(alert => {
            const alertClass = alert.type === 'success' ? 'alert-success' :
                             alert.type === 'warning' ? 'alert-warning' :
                             alert.type === 'danger' ? 'alert-danger' : 'alert-info';

            alertsHtml += `
                <div class="alert ${alertClass}">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        ${alert.icon ? `<div style="font-size: 24px;">${alert.icon}</div>` : ''}
                        <div style="flex: 1;">
                            <strong style="color: #2c3e50; font-size: 16px; font-weight: 700;">${alert.title}</strong>
                            <div style="margin-top: 8px; color: #5a6c7d; font-size: 15px; line-height: 1.5;">${alert.message}</div>
                        </div>
                        ${alert.action ? `<a href="${alert.action.url}" class="btn btn-sm btn-primary">${alert.action.text}</a>` : ''}
                    </div>
                </div>
            `;
        });

        container.innerHTML = alertsHtml;
    }

    // Smart Polling for Real-Time Dashboard Updates
    let lastCheckTimestamp = 0; // Start with 0 to get initial data on first poll
    let pollingInterval = null;
    let isPolling = false;
    let pollErrorCount = 0;
    let isFirstPoll = true; // Track if this is the first poll
    const MAX_POLL_ERRORS = 5;
    const POLL_INTERVAL = 15000; // 15 seconds
    const POLL_INTERVAL_ERROR = 30000; // 30 seconds on error

    /**
     * Poll dashboard for updates
     */
    async function pollDashboardUpdates() {
        if (isPolling) return; // Prevent concurrent polls
        isPolling = true;

        try {
            const url = lastCheckTimestamp > 0 
                ? `{{ route('admin.dashboard.updates') }}?last_check=${lastCheckTimestamp}`
                : `{{ route('admin.dashboard.updates') }}`;
            
            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': AdminUtils.getCSRFToken()
                },
                credentials: 'same-origin'
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            pollErrorCount = 0; // Reset error count on success

            if (data.updated) {
                // Update dashboard with new data
                updateDashboardData(data);
                
                // Only show notification if this is NOT the first poll (to avoid false notifications)
                if (!isFirstPoll) {
                    showUpdateNotification('Dashboard updated with new data');
                }
                
                // Mark that we've completed the first poll
                isFirstPoll = false;
                
                // Update timestamp for next poll
                if (data.timestamp) {
                    lastCheckTimestamp = data.timestamp;
                }
            } else {
                // No changes - just update timestamp
                if (data.timestamp) {
                    lastCheckTimestamp = data.timestamp;
                }
                // Mark that we've completed the first poll even if no updates
                isFirstPoll = false;
            }
        } catch (error) {
            console.error('Dashboard polling error:', error);
            pollErrorCount++;
            
            if (pollErrorCount >= MAX_POLL_ERRORS) {
                // Too many errors - stop polling and show notification
                stopPolling();
                AdminUtils.showError('Dashboard auto-update stopped due to connection issues. Please refresh the page.');
            }
        } finally {
            isPolling = false;
        }
    }

    /**
     * Update dashboard UI with new data
     */
    function updateDashboardData(data) {
        // Update total responses
        const totalResponsesEl = document.querySelector('.metric-card .metric-value');
        if (totalResponsesEl && data.totalResponses !== undefined) {
            animateValueChange(totalResponsesEl, data.totalResponses);
        }

        // Update recent responses
        if (data.recentResponses && data.recentResponses.length > 0) {
            updateRecentResponses(data.recentResponses);
        }

        // Update track distribution
        if (data.responsesByTrack && data.responsesByTrack.length > 0) {
            updateTrackDistribution(data.responsesByTrack);
        }

        // Update audit events count
        const auditEventsEl = document.querySelectorAll('.metric-card .metric-value')[3];
        if (auditEventsEl && data.auditEventsCount !== undefined) {
            animateValueChange(auditEventsEl, data.auditEventsCount);
        }

        // Reload progress alerts
        loadProgressAlerts();
    }

    /**
     * Animate value change with subtle highlight
     */
    function animateValueChange(element, newValue) {
        const oldValue = element.textContent.trim();
        if (oldValue === String(newValue)) return; // No change

        // Add highlight animation
        element.style.transition = 'all 0.3s ease';
        element.style.backgroundColor = 'rgba(66, 133, 244, 0.2)';
        element.textContent = newValue;

        setTimeout(() => {
            element.style.backgroundColor = '';
        }, 500);
    }

    /**
     * Update recent responses list
     */
    function updateRecentResponses(responses) {
        const container = document.querySelector('.recent-responses');
        if (!container) return;

        // Find all response items (skip the header)
        const existingItems = container.querySelectorAll('.response-item');
        
        // Clear existing items
        existingItems.forEach(item => item.remove());

        // Add new responses
        if (responses.length === 0) {
            const noDataDiv = document.createElement('div');
            noDataDiv.className = 'no-data';
            noDataDiv.innerHTML = `
                <p>No survey responses yet.</p>
                <p>Survey responses will appear here once students start submitting their feedback.</p>
            `;
            container.appendChild(noDataDiv);
            return;
        }

        responses.forEach(response => {
            const responseItem = document.createElement('div');
            responseItem.className = 'response-item';
            responseItem.innerHTML = `
                <div class="response-info">
                    <div class="response-track">${response.track} Track - Response #${response.id}</div>
                    <div class="response-date">${response.created_at}</div>
                </div>
                <div style="display: flex; gap: 12px; align-items: center;">
                    <span style="background: linear-gradient(135deg, #28a745, #20c997); color: white; padding: 8px 16px; border-radius: 20px; font-size: 12px; font-weight: 600;">Completed</span>
                    <a href="/admin/responses/${response.id}" class="btn btn-primary" style="padding: 10px 18px; font-size: 14px; text-decoration: none;">View Details</a>
                </div>
            `;
            container.appendChild(responseItem);
        });
    }

    /**
     * Update track distribution
     */
    function updateTrackDistribution(tracks) {
        const container = document.querySelector('.track-distribution');
        if (!container) return;

        // Find all track items (skip the header)
        const existingItems = container.querySelectorAll('.track-item');
        
        // Clear existing items
        existingItems.forEach(item => item.remove());

        if (tracks.length === 0) {
            const noDataDiv = document.createElement('div');
            noDataDiv.className = 'no-data';
            noDataDiv.innerHTML = `
                <p>No track distribution data available yet.</p>
            `;
            container.appendChild(noDataDiv);
            return;
        }

        tracks.forEach(track => {
            const trackItem = document.createElement('div');
            trackItem.className = 'track-item';
            trackItem.innerHTML = `
                <div class="track-name">${track.track} Track</div>
                <div class="track-count">${track.count}</div>
            `;
            container.appendChild(trackItem);
        });
    }

    /**
     * Show subtle update notification
     */
    function showUpdateNotification(message) {
        // Create or get notification element
        let notification = document.getElementById('dashboard-update-notification');
        if (!notification) {
            notification = document.createElement('div');
            notification.id = 'dashboard-update-notification';
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: linear-gradient(135deg, #4285F4, #2c6cd6);
                color: white;
                padding: 12px 20px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(66, 133, 244, 0.3);
                z-index: 10000;
                font-size: 14px;
                font-weight: 500;
                opacity: 0;
                transform: translateY(-20px);
                transition: all 0.3s ease;
            `;
            document.body.appendChild(notification);
        }

        notification.textContent = message;
        notification.style.opacity = '1';
        notification.style.transform = 'translateY(0)';

        // Auto-hide after 3 seconds
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateY(-20px)';
        }, 3000);
    }

    /**
     * Start polling
     */
    function startPolling() {
        if (pollingInterval) return; // Already polling

        // Initial poll after 5 seconds
        setTimeout(() => {
            pollDashboardUpdates();
        }, 5000);

        // Then poll at regular intervals
        pollingInterval = setInterval(() => {
            pollDashboardUpdates();
        }, pollErrorCount > 0 ? POLL_INTERVAL_ERROR : POLL_INTERVAL);
    }

    /**
     * Stop polling
     */
    function stopPolling() {
        if (pollingInterval) {
            clearInterval(pollingInterval);
            pollingInterval = null;
        }
    }

    // Load progress alerts on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadProgressAlerts();

        // Start smart polling for real-time updates
        startPolling();

        // Stop polling when page is hidden (save resources)
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                stopPolling();
            } else {
                startPolling();
                // Poll immediately when page becomes visible
                pollDashboardUpdates();
            }
        });

        // Add smooth animations
        const cards = document.querySelectorAll('.metric-card, .action-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';

            setTimeout(() => {
                card.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
</script>
@endpush

