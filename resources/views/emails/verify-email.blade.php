<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - ISO 21001 System</title>
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
        .welcome-box {
            background: #d4edda;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .verify-button {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, #28a745, #218838);
            color: white !important;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .verify-button:hover {
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
        .features-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .features-section h3 {
            margin-top: 0;
            color: #495057;
        }
        .features-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 15px;
        }
        .feature-item {
            background: white;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #e9ecef;
        }
        .feature-item .emoji {
            font-size: 24px;
            margin-bottom: 5px;
        }
        .feature-item .title {
            font-weight: bold;
            color: #495057;
            margin-bottom: 5px;
        }
        .feature-item .description {
            font-size: 12px;
            color: #666;
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
            <div class="icon">📧</div>
            <h1>Verify Your Email Address</h1>
            <p>ISO 21001 Quality Education System</p>
        </div>

        <div class="welcome-box">
            <strong style="color: #155724;">🎉 Welcome{{ isset($userName) ? ', ' . $userName : '' }}!</strong>
            <p style="margin: 10px 0 0 0; color: #155724;">
                Thank you for registering with the ISO 21001 Survey System.
            </p>
        </div>

        <div class="content-section">
            <h2 style="color: #495057; margin-top: 0;">One More Step...</h2>
            <p style="font-size: 16px; margin: 15px 0;">
                To complete your registration and access the survey system, please verify your email address by clicking the button below.
            </p>
        </div>

        <div class="button-container">
            <a href="{{ $verificationUrl }}" class="verify-button">Verify Email Address</a>
        </div>

        <div class="warning-box">
            <strong style="color: #856404;">⏰ Time Sensitive</strong>
            <p style="margin: 10px 0 0 0; color: #856404;">
                This verification link will expire in <strong>60 minutes</strong>. Please verify your email as soon as possible.
            </p>
        </div>

        <div class="features-section">
            <h3>What You Can Do After Verification</h3>
            <div class="features-grid">
                <div class="feature-item">
                    <div class="emoji">📝</div>
                    <div class="title">Complete Surveys</div>
                    <div class="description">Participate in ISO 21001 quality surveys</div>
                </div>
                <div class="feature-item">
                    <div class="emoji">📊</div>
                    <div class="title">Track Progress</div>
                    <div class="description">View your survey responses</div>
                </div>
                <div class="feature-item">
                    <div class="emoji">🔒</div>
                    <div class="title">Secure Access</div>
                    <div class="description">Protected account information</div>
                </div>
                <div class="feature-item">
                    <div class="emoji">📱</div>
                    <div class="title">QR Code Access</div>
                    <div class="description">Easy survey access via QR codes</div>
                </div>
            </div>
        </div>

        <div class="info-box">
            <strong style="color: rgba(66, 133, 244, 1);">ℹ️ Didn't Create an Account?</strong>
            <p style="margin: 10px 0 0 0; color: #495057;">
                If you did not create an account, no further action is required. You can safely ignore this email.
            </p>
        </div>

        <p style="color: #666; font-size: 14px; margin-top: 20px;">
            If you're having trouble clicking the "Verify Email Address" button, copy and paste the URL below into your web browser:
        </p>
        <div class="link-text">
            {{ $verificationUrl }}
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
