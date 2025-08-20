<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Update Request - {{ config('app.name') }}</title>
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
        .alert {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        .alert-icon {
            font-size: 20px;
            margin-right: 8px;
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
        .changes-section {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .changes-title {
            font-weight: 600;
            color: #0369a1;
            margin-bottom: 15px;
            font-size: 16px;
        }
        .change-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #e0f2fe;
        }
        .change-item:last-child {
            border-bottom: none;
        }
        .change-field {
            font-weight: 500;
            color: #374151;
        }
        .change-values {
            text-align: right;
        }
        .old-value {
            color: #dc2626;
            text-decoration: line-through;
            font-size: 14px;
        }
        .new-value {
            color: #059669;
            font-weight: 500;
        }
        .reason-section {
            background: #fef7ff;
            border: 1px solid #e879f9;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .reason-title {
            font-weight: 600;
            color: #a21caf;
            margin-bottom: 10px;
        }
        .reason-text {
            color: #374151;
            font-style: italic;
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
        .btn-approve {
            background: linear-gradient(135deg, #059669, #10b981);
        }
        .btn-reject {
            background: linear-gradient(135deg, #dc2626, #ef4444);
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
                margin-top: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🏠 {{ config('app.name') }}</div>
            <h1 class="title">New Update Request</h1>
        </div>

        <div class="alert">
            <span class="alert-icon">📝</span>
            <strong>Action Required:</strong> A new {{ ucfirst(str_replace('_', ' ', $updateRequest->request_type)) }} update request needs your review.
        </div>

        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Requested By</div>
                <div class="info-value">{{ $user->name }}</div>
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
                <div class="info-label">Request ID</div>
                <div class="info-value">#{{ $updateRequest->id }}</div>
            </div>
        </div>

        @if($updateRequest->requested_data && $updateRequest->current_data)
        <div class="changes-section">
            <div class="changes-title">📋 Requested Changes</div>
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

        @if($updateRequest->reason)
        <div class="reason-section">
            <div class="reason-title">💬 Reason for Request</div>
            <div class="reason-text">{{ $updateRequest->reason }}</div>
        </div>
        @endif

        <div class="actions">
            <a href="{{ route('update-requests.show', $updateRequest) }}" class="btn">📋 Review Request</a>
            <a href="{{ route('update-requests.approve', $updateRequest) }}" class="btn btn-approve">✅ Approve</a>
            <a href="{{ route('update-requests.reject', $updateRequest) }}" class="btn btn-reject">❌ Reject</a>
        </div>

        <div class="footer">
            <p>This is an automated notification from {{ config('app.name') }}.<br>
            Please review and process this request at your earliest convenience.</p>
        </div>
    </div>
</body>
</html>