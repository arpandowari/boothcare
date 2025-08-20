<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Request Notification</title>
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
        .request-details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #0d6efd;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
            color: white;
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
        .changes-box {
            background: white;
            border: 1px solid #dee2e6;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">🏛️ {{ $appName }}</div>
            <p>Update Request Notification</p>
        </div>

        <h2>Hello {{ $member->name }},</h2>

        @if($type === 'created')
        <p>Your update request has been successfully submitted and is now under review by our team.</p>
        @elseif($updateRequest->status === 'approved')
        <p>Great news! Your update request has been approved and the changes have been applied to your profile.</p>
        @elseif($updateRequest->status === 'rejected')
        <p>We've reviewed your update request, but unfortunately it could not be approved at this time.</p>
        @else
        <p>There's an update regarding your profile change request.</p>
        @endif

        <div class="request-details">
            <h3>📝 Request Details</h3>
            <p><strong>Request ID:</strong> #{{ $updateRequest->id }}</p>
            <p><strong>Field to Update:</strong> {{ ucfirst(str_replace('_', ' ', $updateRequest->field_name)) }}</p>
            <p><strong>Submitted on:</strong> {{ $updateRequest->created_at->format('M d, Y \a\t H:i A') }}</p>
            <p><strong>Current Status:</strong> 
                <span class="status-badge" style="background-color: 
                    @if($updateRequest->status === 'pending') #fbbf24
                    @elseif($updateRequest->status === 'approved') #10b981
                    @elseif($updateRequest->status === 'rejected') #ef4444
                    @else #6b7280 @endif
                ">{{ ucfirst($updateRequest->status) }}</span>
            </p>
        </div>

        <div class="changes-box">
            <h3>🔄 Requested Changes</h3>
            <div style="display: flex; justify-content: space-between; align-items: center; margin: 10px 0;">
                <div style="flex: 1;">
                    <strong>Current Value:</strong><br>
                    <span style="background: #fee2e2; padding: 5px 10px; border-radius: 3px; color: #dc2626;">
                        {{ $updateRequest->old_value ?: 'Not set' }}
                    </span>
                </div>
                <div style="padding: 0 20px; font-size: 20px;">→</div>
                <div style="flex: 1;">
                    <strong>Requested Value:</strong><br>
                    <span style="background: #dcfce7; padding: 5px 10px; border-radius: 3px; color: #16a34a;">
                        {{ $updateRequest->new_value }}
                    </span>
                </div>
            </div>
            @if($updateRequest->reason)
            <div style="margin-top: 15px;">
                <strong>Reason for Change:</strong>
                <p style="background: #f8f9fa; padding: 10px; border-radius: 3px; margin: 5px 0;">{{ $updateRequest->reason }}</p>
            </div>
            @endif
        </div>

        @if($updateRequest->status === 'approved')
        <div style="background: #d1edff; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <h3 style="color: #0d6efd;">✅ Changes Applied</h3>
            <p>Your profile has been updated with the new information. The changes are now active in your account.</p>
        </div>
        @elseif($updateRequest->status === 'rejected')
        <div style="background: #fee2e2; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <h3 style="color: #dc2626;">❌ Request Not Approved</h3>
            <p>Your update request could not be approved. This may be due to:</p>
            <ul>
                <li>Insufficient documentation provided</li>
                <li>Information doesn't match our records</li>
                <li>Additional verification required</li>
            </ul>
            @if($updateRequest->admin_notes)
            <p><strong>Admin Notes:</strong> {{ $updateRequest->admin_notes }}</p>
            @endif
            <p>You can submit a new request with additional information if needed.</p>
        </div>
        @elseif($updateRequest->status === 'pending')
        <div style="background: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <h3 style="color: #856404;">⏳ Under Review</h3>
            <p>Your request is currently being reviewed by our team. We'll notify you once a decision has been made.</p>
            <p><strong>Expected processing time:</strong> 2-3 business days</p>
        </div>
        @endif

        <div style="text-align: center;">
            <a href="{{ $viewUrl }}" class="btn">📱 View Request Details</a>
        </div>

        @if($updateRequest->documents)
        <div style="background: #e7f3ff; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <h3>📎 Supporting Documents</h3>
            <p>You have submitted {{ count(json_decode($updateRequest->documents, true) ?: []) }} supporting document(s) with this request.</p>
        </div>
        @endif

        <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <h3>📞 Need Help?</h3>
            <p>If you have questions about this update request:</p>
            <p><strong>Email:</strong> {{ $supportEmail }}</p>
            <p><strong>Reference ID:</strong> #{{ $updateRequest->id }}</p>
        </div>

        <div class="footer">
            <p>Thank you for keeping your information up to date!</p>
            <p>This is an automated notification. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} {{ $appName }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>