<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - ISO 21001 System</title>
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
        .icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .content-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .reset-button {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, rgba(66, 133, 244, 1), rgba(66, 133, 244, 0.8));
            color: white !important;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .reset-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0,0,0,0.15);
        }
        .warning-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-box {
            background: #e7f3ff;
            border-left: 4px solid rgba(66, 133, 244, 1);
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .security-tips {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .security-tips h3 {
            margin-top: 0;
            color: #495057;
        }
        .security-tips ul {
            margin: 10px 0;
            padding-left: 20px;
            color: #666;
        }
        .security-tips li {
            margin-bottom: 8px;
        }
        .footer {
            text-align: center;
            color: #6c757d;
            font-size: 12px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }
        .link-text {
            word-break: break-all;
            color: #666;
            font-size: 12px;
            background: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="icon">🔐</div>
            <h1>Password Reset Request</h1>
            <p>ISO 21001 Quality Education System</p>
        </div>

        <div class="content-section">
            <h2 style="color: #495057; margin-top: 0;">Hello!</h2>
            <p style="font-size: 16px; margin: 15px 0;">
                You are receiving this email because we received a password reset request for your account.
            </p>
        </div>

        <div class="button-container">
            <a href="{{ $resetUrl }}" class="reset-button">Reset Password</a>
        </div>

        <div class="warning-box">
            <strong style="color: #856404;">⏰ Time Sensitive</strong>
            <p style="margin: 10px 0 0 0; color: #856404;">
                This password reset link will expire in <strong>{{ $expirationMinutes }} minutes</strong>.
            </p>
        </div>

        <div class="info-box">
            <strong style="color: rgba(66, 133, 244, 1);">ℹ️ Didn't Request This?</strong>
            <p style="margin: 10px 0 0 0; color: #495057;">
                If you did not request a password reset, no further action is required. Your password will remain unchanged.
            </p>
        </div>

        <div class="security-tips">
            <h3>Security Tips</h3>
            <ul>
                <li>Never share your password with anyone</li>
                <li>Use a strong, unique password for your account</li>
                <li>If you suspect unauthorized access, contact support immediately</li>
                <li>Always verify the sender's email address before clicking links</li>
            </ul>
        </div>

        <p style="color: #666; font-size: 14px; margin-top: 20px;">
            If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:
        </p>
        <div class="link-text">
            {{ $resetUrl }}
        </div>

        <div class="footer">
            <p>This email was sent by the ISO 21001 Quality Education Analytics System.</p>
            <p style="margin-top: 10px; font-size: 11px; color: #999;">
                Jose Rizal University | ISO 21001 Compliance System
            </p>
            <p style="margin-top: 10px; font-size: 11px; color: #999;">
                © {{ date('Y') }} All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
