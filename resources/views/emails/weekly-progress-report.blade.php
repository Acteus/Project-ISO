<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISO 21001 Weekly Progress Report</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
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
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 30px;
        }
        .metric-card {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .metric-header {
            font-size: 16px;
            font-weight: bold;
            color: #495057;
            margin-bottom: 10px;
        }
        .metric-value {
            font-size: 24px;
            font-weight: bold;
            color: rgba(66, 133, 244, 1);
        }
        .trend {
            font-size: 14px;
            margin-top: 5px;
        }
        .trend-up { color: #28a745; }
        .trend-down { color: #dc3545; }
        .trend-stable { color: #ffc107; }
        .targets-section {
            background: #e7f3ff;
            border-left: 4px solid rgba(66, 133, 244, 1);
            padding: 20px;
            margin: 20px 0;
        }
        .insights-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .recommendations-section {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            color: #6c757d;
            font-size: 12px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }
        .status-indicator {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-met { background: #d4edda; color: #155724; }
        .status-not-met { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>ISO 21001 Weekly Progress Report</h1>
            <h2>{{ $weeklyMetric->date_range_label }}</h2>
            <p>Quality Education Metrics & Compliance Tracking</p>
        </div>

        <!-- Key Metrics -->
        <div class="metric-card">
            <div class="metric-header">Overall Satisfaction</div>
            <div class="metric-value">{{ number_format($weeklyMetric->overall_satisfaction, 2) }}/5.00</div>
            @if($comparison)
                <div class="trend trend-{{ $comparison['satisfaction_trend'] }}">
                    {{ $comparison['satisfaction_change'] > 0 ? '+' : '' }}{{ number_format($comparison['satisfaction_change'], 2) }} from last week
                </div>
            @endif
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="metric-card">
                <div class="metric-header">Compliance Score</div>
                <div class="metric-value">{{ number_format($weeklyMetric->compliance_score, 2) }}/5.00</div>
                <div class="metric-value" style="font-size: 16px; color: #666;">{{ number_format($weeklyMetric->compliance_percentage, 1) }}%</div>
                @if($comparison)
                    <div class="trend trend-{{ $comparison['compliance_trend'] }}">
                        {{ $comparison['compliance_change'] > 0 ? '+' : '' }}{{ number_format($comparison['compliance_change'], 2) }} from last week
                    </div>
                @endif
            </div>

            <div class="metric-card">
                <div class="metric-header">New Responses</div>
                <div class="metric-value">{{ number_format($weeklyMetric->new_responses) }}</div>
                @if($comparison)
                    <div class="trend trend-{{ $comparison['response_trend'] }}">
                        {{ $comparison['response_change'] > 0 ? '+' : '' }}{{ $comparison['response_change'] }} from last week
                    </div>
                @endif
            </div>
        </div>

        <!-- Risk Level -->
        <div class="metric-card">
            <div class="metric-header">Compliance Risk Level</div>
            <div class="metric-value" style="color:
                @if($weeklyMetric->risk_level === 'Low') #28a745;
                @elseif($weeklyMetric->risk_level === 'Medium') #ffc107;
                @else #dc3545; @endif">
                {{ $weeklyMetric->risk_level }} Risk
            </div>
        </div>

        <!-- Target Achievement -->
        <div class="targets-section">
            <h3 style="margin-top: 0; color: rgba(66, 133, 244, 1);">Target Achievement</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; margin-top: 15px;">
                <div style="text-align: center;">
                    <div style="font-weight: bold; margin-bottom: 5px;">Satisfaction (4.0+)</div>
                    <span class="status-indicator {{ $weeklyMetric->satisfaction_target_met ? 'status-met' : 'status-not-met' }}">
                        {{ $weeklyMetric->satisfaction_target_met ? 'MET' : 'NOT MET' }}
                    </span>
                </div>
                <div style="text-align: center;">
                    <div style="font-weight: bold; margin-bottom: 5px;">Compliance (80%+)</div>
                    <span class="status-indicator {{ $weeklyMetric->compliance_target_met ? 'status-met' : 'status-not-met' }}">
                        {{ $weeklyMetric->compliance_target_met ? 'MET' : 'NOT MET' }}
                    </span>
                </div>
                <div style="text-align: center;">
                    <div style="font-weight: bold; margin-bottom: 5px;">Responses (50+)</div>
                    <span class="status-indicator {{ $weeklyMetric->response_target_met ? 'status-met' : 'status-not-met' }}">
                        {{ $weeklyMetric->response_target_met ? 'MET' : 'NOT MET' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Key Insights -->
        @if($weeklyMetric->key_insights && count($weeklyMetric->key_insights) > 0)
        <div class="insights-section">
            <h3 style="margin-top: 0; color: #495057;">Key Insights</h3>
            <ul style="margin: 10px 0; padding-left: 20px;">
                @foreach($weeklyMetric->key_insights as $insight)
                <li style="margin-bottom: 5px;">{{ $insight }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Recommendations -->
        @if($weeklyMetric->recommendations && count($weeklyMetric->recommendations) > 0)
        <div class="recommendations-section">
            <h3 style="margin-top: 0; color: #856404;">Action Recommendations</h3>
            <ul style="margin: 10px 0; padding-left: 20px;">
                @foreach($weeklyMetric->recommendations as $recommendation)
                <li style="margin-bottom: 5px;">{{ $recommendation }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- ISO 21001 Indices Breakdown -->
        <div class="metric-card">
            <h3 style="margin-top: 0; color: #495057;">ISO 21001 Quality Indices</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin-top: 15px;">
                <div>
                    <div style="font-weight: bold; color: #666; font-size: 12px;">Learner Needs</div>
                    <div style="font-size: 18px; font-weight: bold;">{{ number_format($weeklyMetric->learner_needs_index, 2) }}/5.00</div>
                </div>
                <div>
                    <div style="font-weight: bold; color: #666; font-size: 12px;">Satisfaction</div>
                    <div style="font-size: 18px; font-weight: bold;">{{ number_format($weeklyMetric->satisfaction_score, 2) }}/5.00</div>
                </div>
                <div>
                    <div style="font-weight: bold; color: #666; font-size: 12px;">Success</div>
                    <div style="font-size: 18px; font-weight: bold;">{{ number_format($weeklyMetric->success_index, 2) }}/5.00</div>
                </div>
                <div>
                    <div style="font-weight: bold; color: #666; font-size: 12px;">Safety</div>
                    <div style="font-size: 18px; font-weight: bold;">{{ number_format($weeklyMetric->safety_index, 2) }}/5.00</div>
                </div>
                <div>
                    <div style="font-weight: bold; color: #666; font-size: 12px;">Wellbeing</div>
                    <div style="font-size: 18px; font-weight: bold;">{{ number_format($weeklyMetric->wellbeing_index, 2) }}/5.00</div>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>This report was generated automatically by the ISO 21001 Quality Education Analytics System.</p>
            <p>For more detailed analytics, please visit the admin dashboard.</p>
            <p style="margin-top: 10px; font-size: 11px; color: #999;">
                Total Responses to Date: {{ number_format($weeklyMetric->total_responses) }}
            </p>
        </div>
    </div>
</body>
</html>
