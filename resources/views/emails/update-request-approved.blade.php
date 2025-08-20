<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update Request Approved - {{ config('app.name') }}</title>
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
            background: linear-gradient(135deg, #f0fdf4, #ecfdf5);
            border: 2px solid #10b981;
            border-radius: 12px;
            padding: 25px;
            margin: 20px 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .success-alert::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #10b981, #059669);
        }
        .success-icon {
            font-size: 64px;
            margin-bottom: 15px;
            animation: bounce 2s infinite;
        }
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }
        .success-title {
            font-size: 24px;
            font-weight: 700;
            color: #059669;
            margin-bottom: 10px;
        }
        .success-message {
            color: #374151;
            font-size: 16px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 20px 0;
        }
        .info-item {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #10b981;
            transition: transform 0.2s ease;
        }
        .info-item:hover {
            transform: translateY(-2px);
        }
        .info-label {
            font-weight: 600;
            color: #4a5568;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        .info-value {
            font-size: 16px;
            color: #1a202c;
            font-weight: 600;
        }
        .changes-applied {
            background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
            border: 2px solid #0ea5e9;
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
        }
        .changes-title {
            font-weight: 700;
            color: #0369a1;
            margin-bottom: 20px;
            font-size: 18px;
            display: flex;
            align-items: center;
        }
        .changes-title::before {
            content: "🔄";
            margin-right: 10px;
            font-size: 20px;
        }
        .change-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            margin-bottom: 10px;
            background: white;
            border-radius: 8px;
            border-left: 4px solid #0ea5e9;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .change-item:last-child {
            margin-bottom: 0;
        }
        .change-field {
            font-weight: 600;
            color: #374151;
            font-size: 14px;
        }
        .change-values {
            text-align: right;
        }
        .old-value {
            color: #dc2626;
            text-decoration: line-through;
            font-size: 13px;
            opacity: 0.7;
        }
        .new-value {
            color: #059669;
            font-weight: 600;
            font-size: 15px;
        }
        .review-info {
            background: linear-gradient(135deg, #fef7ff, #fdf4ff);
            border: 1px solid #e879f9;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }
        .review-title {
            font-weight: 600;
            color: #a21caf;
            margin-bottom: 15px;
            font-size: 16px;
        }
        .review-item {
            margin-bottom: 10px;
        }
        .review-label {
            font-weight: 500;
            color: #6b7280;
            font-size: 14px;
        }
        .review-value {
            color: #374151;
            margin-left: 10px;
        }
        .celebration {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            background: linear-gradient(135deg, #fffbeb, #fef3c7);
            border-radius: 12px;
            border: 2px solid #f59e0b;
        }
        .celebration-text {
            font-size: 18px;
            color: #d97706;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .celebration-subtext {
            color: #92400e;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 14px 28px;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            margin: 10px 5px;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
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
            .change-item {
                flex-direction: column;
                align-items: flex-start;
            }
            .change-values {
                text-align: left;
                margin-top: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🏠 {{ config('app.name') }}</div>
            <h1 class="title">Update Request Approved</h1>
        </div>

        <div class="success-alert">
            <div class="success-icon">🎉</div>
            <div class="success-title">Great News!</div>
            <div class="success-message">Your {{ ucfirst(str_replace('_', ' ', $updateRequest->request_type)) }} update request has been approved and the changes have been applied to your profile.</div>
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
                <div class="info-label">Approved On</div>
                <div class="info-value">{{ $updateRequest->reviewed_at->format('M d, Y H:i A') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Status</div>
                <div class="info-value">✅ Approved</div>
            </div>
        </div>

        @if($updateRequest->requested_data && $updateRequest->current_data)
        <div class="changes-applied">
            <div class="changes-title">Changes Applied to Your Profile</div>
            @foreach($updateRequest->requested_data as $field => $newValue)
                @if(isset($updateRequest->current_data[$field]) && $updateRequest->current_data[$field] != $newValue)
                <div class="change-item">
                    <div class="change-field">{{ ucfirst(str_replace('_', ' ', $field)) }}</div>
                    <div class="change-values">
                        <div class="old-value">{{ $updateRequest->current_data[$field] ?: 'Not set' }}</div>
                        <div class="new-value">{{ $newValue ?: 'Not set' }}</div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
        @endif

        @if($updateRequest->reviewer)
        <div class="review-info">
            <div class="review-title">📋 Review Information</div>
            <div class="review-item">
                <span class="review-label">Reviewed by:</span>
                <span class="review-value">{{ $updateRequest->reviewer->name }}</span>
            </div>
            <div class="review-item">
                <span class="review-label">Review date:</span>
                <span class="review-value">{{ $updateRequest->reviewed_at->format('M d, Y H:i A') }}</span>
            </div>
            @if($updateRequest->review_notes)
            <div class="review-item">
                <span class="review-label">Admin notes:</span>
                <span class="review-value">{{ $updateRequest->review_notes }}</span>
            </div>
            @endif
        </div>
        @endif

        <div class="celebration">
            <div class="celebration-text">🌟 Your profile has been updated successfully!</div>
            <div class="celebration-subtext">All changes are now active and visible in your account.</div>
        </div>

        <div class="actions">
            <a href="{{ route('dashboard') }}" class="btn">🏠 Go to Dashboard</a>
            <a href="{{ route('update-requests.index') }}" class="btn">📋 View All Requests</a>
        </div>

        <div class="footer">
            <p><strong>Thank you for keeping your information up to date!</strong><br>
            If you have any questions, please don't hesitate to contact our support team.<br>
            <strong>Request ID:</strong> #{{ $updateRequest->id }}</p>
        </div>
    </div>
</body>
</html>