<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Account is Ready</title>
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
        .login-info {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 20px;
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
        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #0d6efd;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">🏛️ {{ $appName }}</div>
            <p>Your Digital Account is Ready!</p>
        </div>

        <h2 class="welcome-title">Welcome {{ $member->name }}! 🎉</h2>

        <p>Great news! Your family member profile has been created and a user account has been automatically set up for you. You can now access the {{ $appName }} system digitally.</p>

        <div class="info-box">
            <h3>👤 Your Profile Information:</h3>
            <p><strong>Name:</strong> {{ $member->name }}</p>
            <p><strong>Email:</strong> {{ $member->email }}</p>
            <p><strong>Relation:</strong> {{ $member->relationship_to_head ?? $member->relation_to_head }}</p>
            @if($member->is_family_head)
            <p><strong>Role:</strong> <span style="color: #fbbf24;">👑 Family Head</span></p>
            @endif
            <p><strong>House:</strong> {{ $member->house->house_number ?? 'N/A' }}</p>
            <p><strong>Area:</strong> {{ $member->house->booth->area->area_name ?? 'N/A' }}</p>
        </div>

        <div class="login-info">
            <h3>🔐 How to Login:</h3>
            <p>Your account uses <strong>Aadhaar-based authentication</strong> for security. Here are your login credentials:</p>
            
            @if($loginCredentials['aadhar'])
            <div style="background: white; padding: 15px; border-radius: 5px; margin: 10px 0;">
                <p><strong>Username:</strong> <code style="background: #f8f9fa; padding: 2px 6px; border-radius: 3px;">{{ $loginCredentials['aadhar'] }}</code> (Your Aadhaar Number)</p>
                @if($loginCredentials['dob'])
                <p><strong>Password:</strong> <code style="background: #f8f9fa; padding: 2px 6px; border-radius: 3px;">{{ $loginCredentials['dob'] }}</code> (Your Date of Birth: YYYY-MM-DD format)</p>
                @endif
            </div>
            @endif

            @if($password)
            <div style="background: #fee2e2; padding: 15px; border-radius: 5px; margin: 10px 0;">
                <p><strong>Alternative Login:</strong></p>
                <p><strong>Email:</strong> {{ $member->email }}</p>
                <p><strong>Temporary Password:</strong> <code style="background: #f8f9fa; padding: 2px 6px; border-radius: 3px;">{{ $password }}</code></p>
                <p><small>⚠️ Please change this password after your first login.</small></p>
            </div>
            @endif

            <p><small>💡 <strong>Tip:</strong> We recommend using your Aadhaar number and date of birth for login as it's more secure and easier to remember.</small></p>
        </div>

        <div style="text-align: center;">
            <a href="{{ $loginUrl }}" class="btn">🚀 Login to Your Account</a>
        </div>

        <div class="info-box">
            <h3>🌟 What you can do with your account:</h3>
            <ul>
                <li>📊 View your family and house information</li>
                <li>📝 Report problems and issues in your area</li>
                <li>📈 Track the status of your reported problems</li>
                <li>🔄 Request updates to your profile information</li>
                <li>📱 Access services digitally from anywhere</li>
                <li>📞 Contact support when needed</li>
            </ul>
        </div>

        <div style="background: #d1edff; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <h3>🔒 Security Information</h3>
            <p>Your account is secured with:</p>
            <ul>
                <li>✅ Aadhaar-based authentication</li>
                <li>✅ Encrypted data storage</li>
                <li>✅ Secure login sessions</li>
                <li>✅ Regular security updates</li>
            </ul>
            <p><small>Never share your login credentials with anyone. Our team will never ask for your password via email or phone.</small></p>
        </div>

        <div style="background: #e7f3ff; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <h3>📞 Need Help?</h3>
            <p>If you have any questions or need assistance with your account:</p>
            <p><strong>Email:</strong> {{ $supportEmail }}</p>
            <p><strong>Member ID:</strong> #{{ $member->id }}</p>
            <p>Our support team is here to help you get started!</p>
        </div>

        <div class="footer">
            <p>Welcome to the digital age of constituency management!</p>
            <p>This email was sent automatically. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} Boothcare. All rights reserved.</p>
        </div>
    </div>
</body>
</html>