<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly ISO 21001 Compliance Report</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            background-color: #f8f9fa;
        }
        .container {
            background: white;
            margin: 20px;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, rgba(66, 133, 244, 1), rgba(255, 215, 0, 1));
            color: white;
            padding: 30px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 30px;
        }
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        .metric-card {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
        }
        .metric-value {
            font-size: 32px;
            font-weight: bold;
            color: rgba(66, 133, 244, 1);
            margin-bottom: 5px;
        }
        .metric-label {
            font-size: 14px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .targets-section {
            background: #e7f3ff;
            border-left: 4px solid rgba(66, 133, 244, 1);
            padding: 20px;
            margin: 20px 0;
        }
        .target-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .target-status {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-achieved { background: #d4edda; color: #155724; }
        .status-not-achieved { background: #f8d7da; color: #721c24; }
        .weekly-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .weekly-table th,
        .weekly-table td {
            border: 1px solid #e9ecef;
            padding: 12px;
            text-align: left;
        }
        .weekly-table th {
            background: #f8f9fa;
            font-weight: bold;
            color: #495057;
        }
        .weekly-table tr:nth-child(even) {
            background: #f8f9fa;
        }
        .insights-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .insights-section h3 {
            margin-top: 0;
            color: #495057;
        }
        .insights-list {
            list-style: none;
            padding: 0;
        }
        .insights-list li {
            padding: 8px 0;
            padding-left: 20px;
            position: relative;
            color: #666;
        }
        .insights-list li:before {
            content: "•";
            position: absolute;
            left: 0;
        }
        .footer {
            text-align: center;
            color: #6c757d;
            font-size: 12px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }
        .trends-section {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Monthly ISO 21001 Compliance Report</h1>
            <h2>{{ $reportData['month'] }}</h2>
            <p>Comprehensive Quality Education Performance Analysis</p>
        </div>

        <!-- Monthly Overview Metrics -->
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-value">{{ number_format($reportData['monthly_averages']['overall_satisfaction'], 2) }}</div>
                <div class="metric-label">Avg Satisfaction</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">{{ number_format($reportData['monthly_averages']['compliance_score'], 2) }}</div>
                <div class="metric-label">Avg Compliance</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">{{ number_format($reportData['monthly_averages']['safety_index'], 2) }}</div>
                <div class="metric-label">Safety Index</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">{{ number_format($reportData['monthly_averages']['total_responses']) }}</div>
                <div class="metric-label">Total Responses</div>
            </div>
        </div>

        <!-- Target Achievement -->
        <div class="targets-section">
            <h3 style="margin-top: 0; color: rgba(66, 133, 244, 1);">Monthly Target Achievement</h3>
            <div class="target-item">
                <span>Satisfaction Target (4.0+)</span>
                <span class="target-status {{ $reportData['targets_achieved']['satisfaction'] ? 'status-achieved' : 'status-not-achieved' }}">
                    {{ $reportData['targets_achieved']['satisfaction'] ? 'ACHIEVED' : 'NOT ACHIEVED' }}
                </span>
            </div>
            <div class="target-item">
                <span>Compliance Target (80%+)</span>
                <span class="target-status {{ $reportData['targets_achieved']['compliance'] ? 'status-achieved' : 'status-not-achieved' }}">
                    {{ $reportData['targets_achieved']['compliance'] ? 'ACHIEVED' : 'NOT ACHIEVED' }}
                </span>
            </div>
            <div class="target-item">
                <span>Response Target (600+)</span>
                <span class="target-status {{ $reportData['targets_achieved']['responses'] ? 'status-achieved' : 'status-not-achieved' }}">
                    {{ $reportData['targets_achieved']['responses'] ? 'ACHIEVED' : 'NOT ACHIEVED' }}
                </span>
            </div>
        </div>

        <!-- Monthly Trends -->
        @if(isset($reportData['trends']) && $reportData['trends'])
        <div class="trends-section">
            <h3 style="margin-top: 0; color: #856404;">Monthly Trends & Changes</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 15px;">
                <div>
                    <strong>Satisfaction Change:</strong>
                    <span style="color: {{ $reportData['trends']['satisfaction_change'] > 0 ? '#28a745' : '#dc3545' }}">
                        {{ $reportData['trends']['satisfaction_change'] > 0 ? '+' : '' }}{{ number_format($reportData['trends']['satisfaction_change'], 2) }}
                    </span>
                </div>
                <div>
                    <strong>Compliance Change:</strong>
                    <span style="color: {{ $reportData['trends']['compliance_change'] > 0 ? '#28a745' : '#dc3545' }}">
                        {{ $reportData['trends']['compliance_change'] > 0 ? '+' : '' }}{{ number_format($reportData['trends']['compliance_change'], 2) }}
                    </span>
                </div>
                <div>
                    <strong>Total Responses:</strong>
                    <span>{{ number_format($reportData['trends']['response_total']) }}</span>
                </div>
            </div>
        </div>
        @endif

        <!-- Weekly Breakdown -->
        <div class="insights-section">
            <h3>Weekly Performance Breakdown</h3>
            <table class="weekly-table">
                <thead>
                    <tr>
                        <th>Week</th>
                        <th>Satisfaction</th>
                        <th>Compliance</th>
                        <th>Safety</th>
                        <th>Responses</th>
                        <th>Risk Level</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportData['weekly_data'] as $week)
                    <tr>
                        <td>{{ $week->date_range_label }}</td>
                        <td>{{ number_format($week->overall_satisfaction, 2) }}</td>
                        <td>{{ number_format($week->compliance_score, 2) }}</td>
                        <td>{{ number_format($week->safety_index, 2) }}</td>
                        <td>{{ $week->new_responses }}</td>
                        <td>
                            <span style="color:
                                @if($week->risk_level === 'Low') #28a745;
                                @elseif($week->risk_level === 'Medium') #ffc107;
                                @else #dc3545; @endif">
                                {{ $week->risk_level }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Key Insights -->
        <div class="insights-section">
            <h3>Key Insights & Recommendations</h3>
            <ul class="insights-list">
                <li>Monthly average satisfaction: {{ number_format($reportData['monthly_averages']['overall_satisfaction'], 2) }}/5.00</li>
                <li>Compliance performance: {{ number_format($reportData['monthly_averages']['compliance_score'], 2) }}/5.00 ({{ number_format(($reportData['monthly_averages']['compliance_score']/5)*100, 1) }}%)</li>
                <li>Total survey responses collected: {{ number_format($reportData['monthly_averages']['total_responses']) }}</li>
                @if($reportData['targets_achieved']['satisfaction'] && $reportData['targets_achieved']['compliance'])
                    <li style="color: #28a745;">Congratulations! All major targets achieved this month.</li>
                @else
                    <li style="color: #dc3545;">Note: Some targets were not met. Review weekly performance for improvement opportunities.</li>
                @endif
                <li>Continue monitoring safety metrics and address any emerging concerns promptly.</li>
                <li>Regular assessment of learner satisfaction helps maintain quality education standards.</li>
            </ul>
        </div>

        <div class="footer">
            <p>This comprehensive monthly report was generated automatically by the ISO 21001 Quality Education Analytics System.</p>
            <p>For detailed weekly reports and interactive dashboards, please access the admin analytics section.</p>
            <p style="margin-top: 10px; font-size: 11px; color: #999;">
                Report Period: {{ $reportData['month'] }} | Generated: {{ now()->format('M j, Y \a\t g:i A') }}
            </p>
        </div>
    </div>
</body>
</html>
