<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to CodeXpress</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f4f4f5;
            color: #18181b;
            margin: 0;
            padding: 0;
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f4f4f5;
            padding: 40px 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            border: 1px solid #e4e4e7;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            padding: 32px;
            text-align: center;
        }
        .header-logo {
            font-size: 24px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.03em;
            margin: 0;
        }
        .header-subtitle {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.85);
            margin: 6px 0 0 0;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 600;
        }
        .content {
            padding: 40px 32px;
        }
        .salutation {
            font-size: 20px;
            font-weight: 700;
            margin: 0 0 16px 0;
            color: #09090b;
            letter-spacing: -0.01em;
        }
        .body-text {
            font-size: 15px;
            line-height: 1.6;
            color: #52525b;
            margin: 0 0 24px 0;
        }
        .credentials-card {
            background-color: #fafafa;
            border: 1px solid #e4e4e7;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 28px;
        }
        .credentials-title {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #71717a;
            margin: 0 0 16px 0;
            border-bottom: 1px solid #e4e4e7;
            padding-bottom: 8px;
        }
        .credential-row {
            margin-bottom: 12px;
            font-size: 14px;
        }
        .credential-row:last-child {
            margin-bottom: 0;
        }
        .credential-label {
            font-weight: 600;
            color: #27272a;
            display: inline-block;
            width: 120px;
        }
        .credential-value {
            color: #09090b;
            font-family: 'Courier New', Courier, monospace;
            font-weight: bold;
            background-color: #f4f4f5;
            padding: 2px 6px;
            border-radius: 4px;
        }
        .btn-wrapper {
            text-align: center;
            margin-bottom: 28px;
        }
        .btn {
            display: inline-block;
            background-color: #4f46e5;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
            transition: background-color 0.2s;
        }
        .footer {
            background-color: #fafafa;
            border-top: 1px solid #e4e4e7;
            padding: 24px 32px;
            text-align: center;
            font-size: 12px;
            color: #71717a;
        }
        .footer-links {
            margin-bottom: 8px;
        }
        .footer-links a {
            color: #4f46e5;
            text-decoration: none;
            margin: 0 8px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <div class="header-logo">CodeXpress</div>
                <div class="header-subtitle">Student Portal Access</div>
            </div>
            <div class="content">
                <h1 class="salutation">Welcome, {{ $student->first_name }} {{ $student->last_name }}!</h1>
                <p class="body-text">
                    You have been registered as a Student on the CodeXpress portal. Below are your account login credentials to access your student dashboard.
                </p>
                
                <div class="credentials-card">
                    <div class="credentials-title">Account Details</div>
                    <div class="credential-row">
                        <span class="credential-label">Portal URL:</span>
                        <span style="color: #4f46e5; font-weight: 600;">{{ url('/student/login') }}</span>
                    </div>
                    <div class="credential-row">
                        <span class="credential-label">Username (Email):</span>
                        <span class="credential-value">{{ $student->email }}</span>
                    </div>
                    <div class="credential-row">
                        <span class="credential-label">Password:</span>
                        <span class="credential-value">{{ $password }}</span>
                    </div>
                    <div class="credential-row" style="margin-top: 12px; padding-top: 12px; border-top: 1px dashed #e4e4e7;">
                        <span class="credential-label">Grade / Course:</span>
                        <span style="color: #09090b; font-weight: 600;">Grade {{ $student->grade }} — {{ $student->course }}</span>
                    </div>
                </div>

                <p class="body-text" style="font-size: 14px; background-color: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; padding: 12px 16px; border-radius: 8px; margin-bottom: 28px;">
                    <strong>Notice:</strong> For security reasons, we recommend changing your password after your first login via the student profile page.
                </p>

                <div class="btn-wrapper">
                    <a href="{{ url('/student/login') }}" class="btn" target="_blank">Access Student Portal</a>
                </div>

                <p class="body-text" style="margin-bottom: 0; font-size: 14px;">
                    If you have any questions or require support, please contact the school administration.
                </p>
            </div>
            <div class="footer">
                <div class="footer-links">
                    <a href="{{ url('/student/login') }}">Student Login</a>
                    <span style="color: #d4d4d8;">|</span>
                    <a href="{{ url('/') }}">School Portal</a>
                </div>
                <div>© {{ date('Y') }} CodeXpress. All rights reserved.</div>
            </div>
        </div>
    </div>
</body>
</html>
