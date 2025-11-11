@extends('layouts.admin')

@section('title', 'Performance Monitoring')

@push('styles')
<style>
    .performance-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        color: #333;
        padding: 40px 30px;
        border-radius: 20px;
        margin-bottom: 40px;
        box-shadow: 0 20px 60px rgba(66, 133, 244, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        position: relative;
        overflow: hidden;
    }

    .performance-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, #4285F4, #FF8C00, #FFD700);
    }

    .metric-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(15px);
        padding: 25px;
        border-radius: 16px;
        margin-bottom: 25px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 30px rgba(66, 133, 244, 0.15);
        transition: all 0.3s ease;
    }

    .metric-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(66, 133, 244, 0.2);
    }

    .metric-card h3 {
        margin-top: 0;
        color: #2c3e50;
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .metric-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 15px;
    }

    .metric-item {
        background: linear-gradient(135deg, rgba(66, 133, 244, 0.05), rgba(255, 140, 0, 0.05));
        padding: 15px;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .metric-label {
        font-weight: 700;
        color: #4285F4;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
    }

    .metric-value {
        font-size: 24px;
        color: #2c3e50;
        font-weight: 700;
    }

    .health-badge {
        display: inline-block;
        padding: 8px 20px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .health-badge.healthy {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }

    .health-badge.degraded {
        background: linear-gradient(135deg, #ffc107, #ff9800);
        color: white;
    }

    .health-badge.critical {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
    }

    .bottleneck-item {
        background: rgba(255, 255, 255, 0.9);
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 15px;
        border-left: 4px solid;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }

    .bottleneck-item.critical {
        border-left-color: #dc3545;
    }

    .bottleneck-item.high {
        border-left-color: #ffc107;
    }

    .bottleneck-item.medium {
        border-left-color: #17a2b8;
    }

    .severity-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        margin-right: 10px;
    }

    .severity-badge.critical {
        background: #dc3545;
        color: white;
    }

    .severity-badge.high {
        background: #ffc107;
        color: #333;
    }

    .severity-badge.medium {
        background: #17a2b8;
        color: white;
    }

    .time-selector {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .time-btn {
        padding: 10px 20px;
        border: 2px solid #4285F4;
        background: white;
        color: #4285F4;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .time-btn:hover, .time-btn.active {
        background: #4285F4;
        color: white;
    }

    .export-btn {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
    }

    .export-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(40, 167, 69, 0.4);
    }

    .no-data {
        text-align: center;
        padding: 40px;
        color: #6c757d;
    }

    .trend-chart {
        height: 300px;
        margin-top: 20px;
    }
</style>
@endpush

@section('content')
<div class="container" style="max-width: 1400px; margin: 0 auto; padding: 40px 20px;">
    <div class="performance-header">
        <h1 style="margin: 0 0 10px 0; font-size: 32px; font-weight: 800; background: linear-gradient(135deg, #4285F4, #FF8C00); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
            Performance Monitoring Dashboard
        </h1>
        <p style="margin: 0; color: #6c757d; font-size: 16px;">
            Real-time performance metrics for AI services and analytics queries
        </p>
    </div>

    @if(isset($error))
        <div class="metric-card">
            <div class="no-data">
                <h3>Error Loading Performance Data</h3>
                <p>{{ $error }}</p>
            </div>
        </div>
    @else
        <!-- Time Period Selector -->
        <div class="metric-card">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
                <div>
                    <h3 style="margin: 0 0 10px 0;">Time Period</h3>
                    <div class="time-selector">
                        <a href="?hours=24" class="time-btn {{ $hours == 24 ? 'active' : '' }}">Last 24 Hours</a>
                        <a href="?hours=48" class="time-btn {{ $hours == 48 ? 'active' : '' }}">Last 48 Hours</a>
                        <a href="?hours=168" class="time-btn {{ $hours == 168 ? 'active' : '' }}">Last 7 Days</a>
                        <a href="?hours=720" class="time-btn {{ $hours == 720 ? 'active' : '' }}">Last 30 Days</a>
                    </div>
                </div>
                <div>
                    <button onclick="exportReport()" class="export-btn">
                        📊 Export Report
                    </button>
                </div>
            </div>
        </div>

        <!-- Overall Health Status -->
        @if(isset($bottleneckAnalysis))
        <div class="metric-card">
            <h3>
                <span>Overall System Health</span>
                <span class="health-badge {{ $bottleneckAnalysis['overall_health'] }}">
                    {{ ucfirst($bottleneckAnalysis['overall_health']) }}
                </span>
            </h3>
            <p style="color: #6c757d; margin: 0;">
                Based on analysis of the last {{ $hours }} hours
            </p>
        </div>
        @endif

        <!-- AI Service Performance -->
        @if(isset($aiSummary) && $aiSummary['total_calls'] > 0)
        <div class="metric-card">
            <h3>🤖 AI Service Performance</h3>
            <div class="metric-grid">
                <div class="metric-item">
                    <div class="metric-label">Total Calls</div>
                    <div class="metric-value">{{ number_format($aiSummary['total_calls']) }}</div>
                </div>
                <div class="metric-item">
                    <div class="metric-label">Success Rate</div>
                    <div class="metric-value">{{ number_format($aiSummary['success_rate'], 2) }}%</div>
                </div>
                <div class="metric-item">
                    <div class="metric-label">Avg Duration</div>
                    <div class="metric-value">{{ number_format($aiSummary['average_duration_ms'], 0) }}ms</div>
                </div>
                <div class="metric-item">
                    <div class="metric-label">P95 Duration</div>
                    <div class="metric-value">{{ number_format($aiSummary['p95_duration_ms'], 0) }}ms</div>
                </div>
                <div class="metric-item">
                    <div class="metric-label">P99 Duration</div>
                    <div class="metric-value">{{ number_format($aiSummary['p99_duration_ms'], 0) }}ms</div>
                </div>
                <div class="metric-item">
                    <div class="metric-label">Error Count</div>
                    <div class="metric-value" style="color: {{ $aiSummary['error_count'] > 0 ? '#dc3545' : '#28a745' }};">
                        {{ $aiSummary['error_count'] }}
                    </div>
                </div>
                <div class="metric-item">
                    <div class="metric-label">Slow Calls (>2s)</div>
                    <div class="metric-value" style="color: {{ $aiSummary['slow_call_rate'] > 10 ? '#ffc107' : '#28a745' }};">
                        {{ $aiSummary['slow_calls'] }} ({{ number_format($aiSummary['slow_call_rate'], 1) }}%)
                    </div>
                </div>
            </div>

            @if(!empty($aiSummary['by_endpoint']))
            <div style="margin-top: 25px;">
                <h4 style="color: #2c3e50; margin-bottom: 15px;">Top Endpoints</h4>
                <div style="display: grid; gap: 10px;">
                    @foreach(array_slice($aiSummary['by_endpoint'], 0, 5, true) as $endpoint => $stats)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: rgba(66, 133, 244, 0.05); border-radius: 8px;">
                        <div>
                            <strong>{{ $endpoint }}</strong>
                            <div style="font-size: 12px; color: #6c757d;">
                                {{ $stats['count'] }} calls • {{ number_format($stats['average_duration_ms'], 0) }}ms avg • {{ number_format($stats['success_rate'], 1) }}% success
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @else
        <div class="metric-card">
            <div class="no-data">
                <h3>No AI Service Data</h3>
                <p>No AI service calls recorded in the selected time period.</p>
            </div>
        </div>
        @endif

        <!-- Analytics Query Performance -->
        @if(isset($analyticsSummary) && $analyticsSummary['total_queries'] > 0)
        <div class="metric-card">
            <h3>📊 Analytics Query Performance</h3>
            <div class="metric-grid">
                <div class="metric-item">
                    <div class="metric-label">Total Queries</div>
                    <div class="metric-value">{{ number_format($analyticsSummary['total_queries']) }}</div>
                </div>
                <div class="metric-item">
                    <div class="metric-label">Avg Duration</div>
                    <div class="metric-value">{{ number_format($analyticsSummary['average_duration_ms'], 0) }}ms</div>
                </div>
                <div class="metric-item">
                    <div class="metric-label">P95 Duration</div>
                    <div class="metric-value">{{ number_format($analyticsSummary['p95_duration_ms'], 0) }}ms</div>
                </div>
                <div class="metric-item">
                    <div class="metric-label">P99 Duration</div>
                    <div class="metric-value">{{ number_format($analyticsSummary['p99_duration_ms'], 0) }}ms</div>
                </div>
                <div class="metric-item">
                    <div class="metric-label">Slow Queries (>1s)</div>
                    <div class="metric-value" style="color: {{ $analyticsSummary['slow_query_rate'] > 20 ? '#ffc107' : '#28a745' }};">
                        {{ $analyticsSummary['slow_queries'] }} ({{ number_format($analyticsSummary['slow_query_rate'], 1) }}%)
                    </div>
                </div>
                <div class="metric-item">
                    <div class="metric-label">Total Rows</div>
                    <div class="metric-value">{{ number_format($analyticsSummary['total_rows_processed']) }}</div>
                </div>
                <div class="metric-item">
                    <div class="metric-label">Avg Rows/Query</div>
                    <div class="metric-value">{{ number_format($analyticsSummary['average_rows_per_query'], 0) }}</div>
                </div>
            </div>

            @if(!empty($analyticsSummary['by_query_type']))
            <div style="margin-top: 25px;">
                <h4 style="color: #2c3e50; margin-bottom: 15px;">Query Types</h4>
                <div style="display: grid; gap: 10px;">
                    @foreach(array_slice($analyticsSummary['by_query_type'], 0, 5, true) as $type => $stats)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: rgba(66, 133, 244, 0.05); border-radius: 8px;">
                        <div>
                            <strong>{{ $type }}</strong>
                            <div style="font-size: 12px; color: #6c757d;">
                                {{ $stats['count'] }} queries • {{ number_format($stats['average_duration_ms'], 0) }}ms avg • {{ number_format($stats['total_rows']) }} rows
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @else
        <div class="metric-card">
            <div class="no-data">
                <h3>No Analytics Query Data</h3>
                <p>No analytics queries recorded in the selected time period.</p>
            </div>
        </div>
        @endif

        <!-- Bottleneck Analysis -->
        @if(isset($bottleneckAnalysis) && !empty($bottleneckAnalysis['bottlenecks']))
        <div class="metric-card">
            <h3>⚠️ Identified Bottlenecks</h3>
            @foreach($bottleneckAnalysis['bottlenecks'] as $bottleneck)
            <div class="bottleneck-item {{ $bottleneck['severity'] }}">
                <div style="display: flex; align-items: center; margin-bottom: 10px;">
                    <span class="severity-badge {{ $bottleneck['severity'] }}">{{ $bottleneck['severity'] }}</span>
                    <strong style="color: #2c3e50;">{{ $bottleneck['issue'] }}</strong>
                </div>
                <p style="margin: 0; color: #6c757d; font-size: 14px;">
                    <strong>Recommendation:</strong> {{ $bottleneck['recommendation'] }}
                </p>
            </div>
            @endforeach
        </div>
        @elseif(isset($bottleneckAnalysis))
        <div class="metric-card">
            <div style="text-align: center; padding: 20px;">
                <h3 style="color: #28a745; margin: 0;">✅ No Bottlenecks Identified</h3>
                <p style="color: #6c757d; margin: 10px 0 0 0;">System is performing well!</p>
            </div>
        </div>
        @endif

        <!-- Performance Trends -->
        @if(isset($aiTrends) && !empty($aiTrends))
        <div class="metric-card">
            <h3>📈 AI Service Trends (Last 7 Days)</h3>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: rgba(66, 133, 244, 0.1);">
                            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #4285F4;">Date</th>
                            <th style="padding: 12px; text-align: right; border-bottom: 2px solid #4285F4;">Calls</th>
                            <th style="padding: 12px; text-align: right; border-bottom: 2px solid #4285F4;">Avg Duration</th>
                            <th style="padding: 12px; text-align: right; border-bottom: 2px solid #4285F4;">P95</th>
                            <th style="padding: 12px; text-align: right; border-bottom: 2px solid #4285F4;">Success Rate</th>
                            <th style="padding: 12px; text-align: right; border-bottom: 2px solid #4285F4;">Errors</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($aiTrends as $trend)
                        <tr style="border-bottom: 1px solid rgba(0,0,0,0.1);">
                            <td style="padding: 10px;">{{ $trend['date'] }}</td>
                            <td style="padding: 10px; text-align: right;">{{ $trend['count'] }}</td>
                            <td style="padding: 10px; text-align: right;">{{ number_format($trend['average_duration_ms'], 0) }}ms</td>
                            <td style="padding: 10px; text-align: right;">{{ number_format($trend['p95_duration_ms'], 0) }}ms</td>
                            <td style="padding: 10px; text-align: right; color: {{ $trend['success_rate'] < 95 ? '#ffc107' : '#28a745' }};">
                                {{ number_format($trend['success_rate'], 1) }}%
                            </td>
                            <td style="padding: 10px; text-align: right; color: {{ $trend['error_count'] > 0 ? '#dc3545' : '#28a745' }};">
                                {{ $trend['error_count'] }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        @if(isset($analyticsTrends) && !empty($analyticsTrends))
        <div class="metric-card">
            <h3>📈 Analytics Query Trends (Last 7 Days)</h3>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: rgba(66, 133, 244, 0.1);">
                            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #4285F4;">Date</th>
                            <th style="padding: 12px; text-align: right; border-bottom: 2px solid #4285F4;">Queries</th>
                            <th style="padding: 12px; text-align: right; border-bottom: 2px solid #4285F4;">Avg Duration</th>
                            <th style="padding: 12px; text-align: right; border-bottom: 2px solid #4285F4;">P95</th>
                            <th style="padding: 12px; text-align: right; border-bottom: 2px solid #4285F4;">P99</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($analyticsTrends as $trend)
                        <tr style="border-bottom: 1px solid rgba(0,0,0,0.1);">
                            <td style="padding: 10px;">{{ $trend['date'] }}</td>
                            <td style="padding: 10px; text-align: right;">{{ $trend['count'] }}</td>
                            <td style="padding: 10px; text-align: right;">{{ number_format($trend['average_duration_ms'], 0) }}ms</td>
                            <td style="padding: 10px; text-align: right;">{{ number_format($trend['p95_duration_ms'], 0) }}ms</td>
                            <td style="padding: 10px; text-align: right;">{{ number_format($trend['p99_duration_ms'], 0) }}ms</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    @endif
</div>

<script>
function exportReport() {
    // Get current time period
    const hours = new URLSearchParams(window.location.search).get('hours') || 24;
    
    // Create a simple text report
    const report = generateTextReport();
    
    // Create blob and download
    const blob = new Blob([report], { type: 'text/plain' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `performance-report-${hours}h-${new Date().toISOString().split('T')[0]}.txt`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
}

function generateTextReport() {
    const hours = new URLSearchParams(window.location.search).get('hours') || 24;
    let report = `Performance Monitoring Report\n`;
    report += `Generated: ${new Date().toLocaleString()}\n`;
    report += `Time Period: Last ${hours} hours\n`;
    report += `\n${'='.repeat(60)}\n\n`;
    
    // This is a simple text export - you could enhance this to include actual data
    report += `Report data is available via:\n`;
    report += `- Web Dashboard: /admin/performance?hours=${hours}\n`;
    report += `- API Endpoint: /api/performance/dashboard?hours=${hours}\n`;
    report += `- Command Line: php artisan performance:report --hours=${hours}\n`;
    
    return report;
}
</script>
@endsection

