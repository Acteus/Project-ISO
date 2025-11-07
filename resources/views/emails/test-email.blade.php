<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Email - ISO 21001 System</title>
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
            padding: 30px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 30px;
        }
        .success-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .content-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .config-table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }
        .config-table td {
            padding: 10px;
            border-bottom: 1px solid #e9ecef;
        }
        .config-table td:first-child {
            font-weight: bold;
            color: #495057;
            width: 40%;
        }
        .config-table td:last-child {
            color: #666;
        }
        .info-box {
            background: #e7f3ff;
            border-left: 4px solid rgba(66, 133, 244, 1);
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .footer {
            text-align: center;
            color: #6c757d;
            font-size: 12px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="success-icon">✅</div>
            <h1>Email Configuration Test</h1>
            <p>ISO 21001 Quality Education System</p>
        </div>

        <div class="content-section">
            <h2 style="color: #28a745; margin-top: 0;">Success!</h2>
            <p style="font-size: 16px; margin: 15px 0;">
                If you received this email, your email configuration is working correctly!
            </p>
        </div>

        <div class="info-box">
            <strong style="color: rgba(66, 133, 244, 1); font-size: 16px;">Mail Configuration Details</strong>
        </div>

        <table class="config-table">
            <tr>
                <td>Mail Host</td>
                <td>{{ $mailConfig['host'] }}</td>
            </tr>
            <tr>
                <td>Mail Port</td>
                <td>{{ $mailConfig['port'] }}</td>
            </tr>
            @if(isset($mailConfig['username']))
            <tr>
                <td>Username</td>
                <td>{{ $mailConfig['username'] }}</td>
            </tr>
            @endif
            <tr>
                <td>From Address</td>
                <td>{{ $mailConfig['from_address'] }}</td>
            </tr>
            <tr>
                <td>From Name</td>
                <td>{{ $mailConfig['from_name'] }}</td>
            </tr>
            <tr>
                <td>Sent At</td>
                <td>{{ $timestamp }}</td>
            </tr>
        </table>

        <div class="content-section">
            <h3 style="margin-top: 0; color: #495057;">Next Steps</h3>
            <p>Your email configuration is properly set up. You can now:</p>
            <ul style="color: #666;">
                <li>Send weekly progress reports to administrators</li>
                <li>Send monthly compliance reports</li>
                <li>Receive system notifications</li>
            </ul>
        </div>

        <div class="footer">
            <p>This test email was sent by the ISO 21001 Quality Education Analytics System.</p>
            <p style="margin-top: 10px; font-size: 11px; color: #999;">
                Jose Rizal University | ISO 21001 Compliance System
            </p>
        </div>
    </div>
</body>
</html>
