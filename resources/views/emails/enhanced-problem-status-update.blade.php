<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Problem Status Update</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, {{ $statusColor }} 0%, {{ $statusColor }}dd 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .header .icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .content {
            padding: 30px 20px;
        }
        .status-change {
            background: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid {{ $statusColor }};
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-old {
            background: #e5e7eb;
            color: #6b7280;
        }
        .status-new {
            background: {{ $statusColor }};
            color: white;
        }
        .problem-details {
            background: #f9fafb;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        .detail-label {
            font-weight: 600;
            color: #374151;
        }
        .detail-value {
            color: #6b7280;
        }
        .notes-section {
            background: #fef3c7;
            border: 1px solid #fbbf24;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .notes-section h3 {
            margin-top: 0;
            color: #92400e;
        }
        .resolution-section {
            background: #d1fae5;
            border: 1px solid #10b981;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .resolution-section h3 {
            margin-top: 0;
            color: #065f46;
        }
        .image-section {
            text-align: center;
            margin: 20px 0;
        }
        .update-image {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .footer {
            background: #f3f4f6;
            padding: 20px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            background: {{ $statusColor }};
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 10px 0;
        }
        .cost-info {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
            text-align: center;
        }
        .cost-amount {
            font-size: 24px;
            font-weight: 700;
            color: #92400e;
        }
        @media (max-width: 600px) {
            .container {
                margin: 10px;
                border-radius: 8px;
            }
            .content {
                padding: 20px 15px;
            }
            .detail-row {
                flex-direction: column;
            }
            .detail-label {
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="icon">{{ $statusIcon }}</div>
            <h1>Problem Status Updated</h1>
            <p>{{ $statusMessage }}</p>
        </div>

        <!-- Content -->
        <div class="content">
            <p>Hello {{ $user->name }},</p>
            
            <p>We have an update on your problem report. Here are the details:</p>

            <!-- Status Change -->
            <div class="status-change">
                <h3>Status Change</h3>
                <p>
                    <span class="status-badge status-old">{{ ucfirst(str_replace('_', ' ', $oldStatus)) }}</span>
                    →
                    <span class="status-badge status-new">{{ ucfirst(str_replace('_', ' ', $newStatus)) }}</span>
                </p>
                <p><strong>Updated on:</strong> {{ $updateDate }}</p>
                @if($updatedBy)
                    <p><strong>Updated by:</strong> {{ $updatedBy->name }}</p>
                @endif
            </div>

            <!-- Problem Details -->
            <div class="problem-details">
                <h3>Problem Information</h3>
                <div class="detail-row">
                    <span class="detail-label">Title:</span>
                    <span class="detail-value">{{ $problem->title }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Category:</span>
                    <span class="detail-value">{{ ucfirst($problem->category) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Priority:</span>
                    <span class="detail-value">{{ ucfirst($problem->priority) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Reported Date:</span>
                    <span class="detail-value">{{ $problem->created_at->format('M d, Y') }}</span>
                </div>
                @if($problem->familyMember && $problem->familyMember->house)
                    <div class="detail-row">
                        <span class="detail-label">Location:</span>
                        <span class="detail-value">
                            {{ $problem->familyMember->house->booth->area->area_name ?? 'N/A' }} - 
                            House {{ $problem->familyMember->house->house_number ?? 'N/A' }}
                        </span>
                    </div>
                @endif
            </div>

            <!-- Resolution Notes (if resolved) -->
            @if($newStatus === 'resolved' && $resolutionNotes)
                <div class="resolution-section">
                    <h3>✅ Resolution Details</h3>
                    <p>{{ $resolutionNotes }}</p>
                    
                    @if($actualCost)
                        <div class="cost-info">
                            <p><strong>Total Cost Incurred:</strong></p>
                            <div class="cost-amount">৳{{ number_format($actualCost, 2) }}</div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Admin Notes (if provided) -->
            @if($adminNotes)
                <div class="notes-section">
                    <h3>📝 Additional Information</h3>
                    <p>{{ $adminNotes }}</p>
                </div>
            @endif

            <!-- Status Update Image -->
            @if($statusUpdateImage)
                <div class="image-section">
                    <h3>📸 Progress Update</h3>
                    <img src="{{ asset('storage/' . $statusUpdateImage) }}" alt="Status Update Image" class="update-image">
                    <p><small>Current progress/status of your problem</small></p>
                </div>
            @endif

            <!-- Action Buttons -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('problems.show', $problem) }}" class="button">
                    View Problem Details
                </a>
                
                @if($newStatus === 'resolved')
                    <br>
                    <a href="{{ route('problems.feedback', $problem) }}" class="button" style="background: #10b981;">
                        Provide Feedback
                    </a>
                @endif
            </div>

            <!-- Next Steps -->
            <div style="background: #f0f9ff; border: 1px solid #0ea5e9; border-radius: 8px; padding: 20px; margin: 20px 0;">
                <h3 style="color: #0369a1; margin-top: 0;">What's Next?</h3>
                @if($newStatus === 'reported')
                    <p>Your problem is now in our system and will be reviewed by our team. We'll keep you updated on the progress.</p>
                @elseif($newStatus === 'in_progress')
                    <p>Our team is actively working on resolving your problem. We'll notify you once it's completed.</p>
                @elseif($newStatus === 'resolved')
                    <p>Your problem has been resolved! Please review the solution and provide your feedback to help us improve our services.</p>
                @else
                    <p>If you have any questions or concerns, please don't hesitate to contact us.</p>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>This is an automated notification from {{ config('app.name') }}.</p>
            <p>If you have any questions, please contact our support team.</p>
            <p><small>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</small></p>
        </div>
    </div>
</body>
</html>