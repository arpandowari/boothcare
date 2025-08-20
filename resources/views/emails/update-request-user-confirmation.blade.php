<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update Request Submitted - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8fafc;
        }
        .container {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e2e8f0;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #4285f4;
            margin-bottom: 10px;
        }
        .title {
            font-size: 20px;
            font-weight: 600;
            color: #1a202c;
            margin: 0;
        }
        .success-alert {
            background: #f0fdf4;
            border: 1px solid #10b981;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .success-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .success-title {
            font-size: 18px;
            font-weight: 600;
            color: #059669;
            margin-bottom: 10px;
        }
        .success-message {
            color: #374151;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 20px 0;
        }
        .info-item {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #4285f4;
        }
        .info-label {
            font-weight: 600;
            color: #4a5568;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        .info-value {
            font-size: 16px;
            color: #1a202c;
            font-weight: 500;
        }
        .timeline {
            background: #fef7ff;
            border: 1px solid #e879f9;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .timeline-title {
            font-weight: 600;
            color: #a21caf;
            margin-bottom: 15px;
            font-size: 16px;
        }
        .timeline-step {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            padding: 10px 0;
        }
        .timeline-step:last-child {
            margin-bottom: 0;
        }
        .step-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 12px;
            font-weight: bold;
        }
        .step-current {
            background: #10b981;
            color: white;
        }
        .step-pending {
            background: #e5e7eb;
            color: #6b7280;
        }
        .step-text {
            flex: 1;
        }
        .step-current-text {
            font-weight: 600;
            color: #059669;
        }
        .step-pending-text {
            color: #6b7280;
        }
        .next-steps {
            background: #fffbeb;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .next-steps-title {
            font-weight: 600;
            color: #d97706;
            margin-bottom: 15px;
            font-size: 16px;
        }
        .next-steps-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .next-steps-list li {
            padding: 8px 0;
            color: #374151;
            position: relative;
            padding-left: 25px;
        }
        .next-steps-list li:before {
            content: "📌";
            position: absolute;
            left: 0;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #4285f4, #34a853);
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 10px 5px;
            text-align: center;
            transition: all 0.3s ease;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(66, 133, 244, 0.3);
        }
        .actions {
            text-align: center;
            margin: 30px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            color: #6b7280;
            font-size: 14px;
        }
        @media (max-width: 600px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🏠 {{ config('app.name') }}</div>
            <h1 class="title">Update Request Submitted</h1>
        </div>

        <div class="success-alert">
            <div class="success-icon">✅</div>
            <div class="success-title">Request Successfully Submitted!</div>
            <div class="success-message">Your {{ ucfirst(str_replace('_', ' ', $updateRequest->request_type)) }} update request has been received and is now under review.</div>
        </div>

        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Request ID</div>
                <div class="info-value">#{{ $updateRequest->id }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Request Type</div>
                <div class="info-value">{{ ucfirst(str_replace('_', ' ', $updateRequest->request_type)) }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Submitted On</div>
                <div class="info-value">{{ $updateRequest->created_at->format('M d, Y H:i A') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Current Status</div>
                <div class="info-value">⏳ Pending Review</div>
            </div>
        </div>

        <div class="timeline">
            <div class="timeline-title">📋 Request Process Timeline</div>
            <div class="timeline-step">
                <div class="step-icon step-current">✓</div>
                <div class="step-text step-current-text">Request Submitted</div>
            </div>
            <div class="timeline-step">
                <div class="step-icon step-pending">2</div>
                <div class="step-text step-pending-text">Admin Review</div>
            </div>
            <div class="timeline-step">
                <div class="step-icon step-pending">3</div>
                <div class="step-text step-pending-text">Decision & Notification</div>
            </div>
            <div class="timeline-step">
                <div class="step-icon step-pending">4</div>
                <div class="step-text step-pending-text">Changes Applied (if approved)</div>
            </div>
        </div>

        <div class="next-steps">
            <div class="next-steps-title">📝 What Happens Next?</div>
            <ul class="next-steps-list">
                <li>Our admin team will review your request within 24-48 hours</li>
                <li>You'll receive an email notification once a decision is made</li>
                <li>If approved, the changes will be applied to your profile automatically</li>
                <li>You can track the status of your request in your dashboard</li>
            </ul>
        </div>

        <div class="actions">
            <a href="{{ route('update-requests.index') }}" class="btn">📋 View My Requests</a>
        </div>

        <div class="footer">
            <p><strong>Need Help?</strong><br>
            If you have any questions about your request, please contact our support team.<br>
            <strong>Request ID:</strong> #{{ $updateRequest->id }}</p>
        </div>
    </div>
</body>
</html>