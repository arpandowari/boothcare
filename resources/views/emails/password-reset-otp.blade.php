<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset OTP - {{ $appName }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8fafc;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        
        .logo {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .email-body {
            padding: 40px 30px;
        }
        
        .greeting {
            font-size: 1.2rem;
            margin-bottom: 20px;
            color: #2d3748;
        }
        
        .message {
            font-size: 1rem;
            margin-bottom: 30px;
            color: #4a5568;
            line-height: 1.6;
        }
        
        .otp-container {
            background: #f7fafc;
            border: 2px dashed #667eea;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }
        
        .otp-label {
            font-size: 0.9rem;
            color: #718096;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }
        
        .otp-code {
            font-size: 2.5rem;
            font-weight: bold;
            color: #667eea;
            letter-spacing: 8px;
            font-family: 'Courier New', monospace;
            margin: 10px 0;
        }
        
        .otp-expiry {
            font-size: 0.85rem;
            color: #e53e3e;
            margin-top: 15px;
            font-weight: 500;
        }
        
        .instructions {
            background: #e6fffa;
            border-left: 4px solid #38b2ac;
            padding: 20px;
            margin: 30px 0;
            border-radius: 0 8px 8px 0;
        }
        
        .instructions h3 {
            color: #2c7a7b;
            margin-bottom: 10px;
            font-size: 1.1rem;
        }
        
        .instructions ol {
            color: #2d3748;
            padding-left: 20px;
        }
        
        .instructions li {
            margin-bottom: 8px;
        }
        
        .security-notice {
            background: #fef5e7;
            border: 1px solid #f6ad55;
            border-radius: 8px;
            padding: 20px;
            margin: 30px 0;
        }
        
        .security-notice h3 {
            color: #c05621;
            margin-bottom: 10px;
            font-size: 1rem;
        }
        
        .security-notice p {
            color: #744210;
            font-size: 0.9rem;
            margin-bottom: 8px;
        }
        
        .email-footer {
            background: #f7fafc;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        
        .footer-text {
            color: #718096;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }
        
        .support-info {
            color: #4a5568;
            font-size: 0.85rem;
        }
        
        .support-email {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }
        
        .support-email:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 600px) {
            .email-container {
                margin: 10px;
                border-radius: 8px;
            }
            
            .email-header {
                padding: 30px 20px;
            }
            
            .email-body {
                padding: 30px 20px;
            }
            
            .otp-code {
                font-size: 2rem;
                letter-spacing: 4px;
            }
            
            .email-footer {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="logo">🔐 {{ $appName }}</div>
            <h1>Password Reset Request</h1>
        </div>
        
        <!-- Body -->
        <div class="email-body">
            <div class="greeting">
                Hello {{ $userName }},
            </div>
            
            <div class="message">
                We received a request to reset your password for your {{ $appName }} admin account. 
                Use the OTP code below to proceed with resetting your password.
            </div>
            
            <!-- OTP Container -->
            <div class="otp-container">
                <div class="otp-label">Your OTP Code</div>
                <div class="otp-code">{{ $otp }}</div>
                <div class="otp-expiry">
                    ⏰ This code expires in {{ $expiryMinutes }} minutes
                </div>
            </div>
            
            <!-- Instructions -->
            <div class="instructions">
                <h3>📋 How to use this OTP:</h3>
                <ol>
                    <li>Go back to the password reset page</li>
                    <li>Enter the 6-digit OTP code above</li>
                    <li>Click "Verify OTP" to proceed</li>
                    <li>Create your new secure password</li>
                </ol>
            </div>
            
            <!-- Security Notice -->
            <div class="security-notice">
                <h3>🛡️ Security Notice</h3>
                <p><strong>Keep this code secure:</strong> Never share this OTP with anyone.</p>
                <p><strong>Didn't request this?</strong> If you didn't request a password reset, please ignore this email and contact our support team immediately.</p>
                <p><strong>One-time use:</strong> This code can only be used once and will expire automatically.</p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="email-footer">
            <div class="footer-text">
                This is an automated message from {{ $appName }}. Please do not reply to this email.
            </div>
            
            <div class="support-info">
                Need help? Contact us at 
                <a href="mailto:{{ $supportEmail }}" class="support-email">{{ $supportEmail }}</a>
            </div>
        </div>
    </div>
</body>
</html>