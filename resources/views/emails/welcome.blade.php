<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to {{ $appName }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .email-container {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #0d6efd;
            margin-bottom: 10px;
        }
        .welcome-title {
            color: #198754;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #0d6efd;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .credentials {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #0d6efd;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        .contact-info {
            background: #e7f3ff;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">🏛️ {{ $appName }}</div>
            <p>Digital Constituency Management System</p>
        </div>

        <h2 class="welcome-title">Welcome, {{ $user->name }}! 🎉</h2>

        <p>We're excited to have you join the {{ $appName }} community. Your account has been successfully created and is ready to use.</p>

        <div class="info-box">
            <h3>🔐 Your Account Details:</h3>
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
            <p><strong>Account Status:</strong> <span style="color: #198754;">Active</span></p>
        </div>

        @if($password)
        <div class="credentials">
            <h3>🔑 Login Credentials:</h3>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Temporary Password:</strong> <code style="background: #f8f9fa; padding: 2px 6px; border-radius: 3px;">{{ $password }}</code></p>
            <p><small>⚠️ Please change your password after first login for security.</small></p>
        </div>
        @endif

        <div style="text-align: center;">
            <a href="{{ $loginUrl }}" class="btn">🚀 Login to Your Account</a>
        </div>

        <div class="info-box">
            <h3>🌟 What you can do:</h3>
            <ul>
                <li>📊 View constituency dashboard and statistics</li>
                <li>👥 Manage family member information</li>
                <li>🏠 Track house and booth details</li>
                <li>📝 Report and track problems</li>
                <li>📈 Generate reports and analytics</li>
                <li>⚙️ Update your profile and settings</li>
            </ul>
        </div>

        <div class="contact-info">
            <h3>📞 Need Help?</h3>
            <p>If you have any questions or need assistance, please don't hesitate to contact us:</p>
            <p><strong>Email:</strong> {{ $supportEmail }}</p>
            <p><strong>System:</strong> {{ $appName }} Support Team</p>
        </div>

        <div class="footer">
            <p>Thank you for choosing {{ $appName }}!</p>
            <p>This email was sent automatically. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} {{ $appName }}. All rights reserved. | Made by <a href="https://arpandowari.github.io/ArpanDowari/" target="_blank" style="color: #4285f4; text-decoration: none;">@Arpan</a></p>
        </div>
    </div>
</body>
</html>