<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Configuration Test</title>
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
        .success-box {
            background: #d1edff;
            border-left: 4px solid #0d6efd;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">🏛️ {{ $appName }}</div>
            <p>Email Configuration Test</p>
        </div>

        <div class="success-box">
            <h2 style="color: #0d6efd; margin-top: 0;">✅ Email System Working!</h2>
            <p>Congratulations! Your email configuration is working correctly.</p>
        </div>

        <h3>📧 Test Details:</h3>
        <ul>
            <li><strong>Test Time:</strong> {{ $testTime }}</li>
            <li><strong>Application:</strong> {{ $appName }}</li>
            <li><strong>Email System:</strong> Gmail SMTP</li>
            <li><strong>Status:</strong> <span style="color: #10b981;">✅ Successfully Delivered</span></li>
        </ul>

        <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <h3>🔧 Configuration Verified:</h3>
            <ul>
                <li>✅ SMTP Connection Established</li>
                <li>✅ Authentication Successful</li>
                <li>✅ Email Templates Loading</li>
                <li>✅ Mail Queue Processing</li>
            </ul>
        </div>

        <div style="background: #d1edff; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <h3>🚀 Ready to Use:</h3>
            <p>Your email system is now ready to send:</p>
            <ul>
                <li>📧 Welcome emails for new users</li>
                <li>🔐 Password reset OTPs</li>
                <li>📊 Problem status updates</li>
                <li>📝 Update request notifications</li>
                <li>👥 Member account creation emails</li>
                <li>🚨 Admin notifications</li>
            </ul>
        </div>

        <div class="footer">
            <p>This is a test email from {{ $appName }}.</p>
            <p>If you received this email, your configuration is working perfectly!</p>
            <p>&copy; {{ date('Y') }} {{ $appName }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>