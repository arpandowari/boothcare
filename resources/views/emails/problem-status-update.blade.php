<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Problem Status Update</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #28a745; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f8f9fa; }
        .status-update { background: white; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .status-reported { border-left: 4px solid #ffc107; }
        .status-in_progress { border-left: 4px solid #007bff; }
        .status-resolved { border-left: 4px solid #28a745; }
        .status-closed { border-left: 4px solid #6c757d; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 5px; }
        .btn-feedback { background: #28a745; }
        .highlight { background: #fff3cd; padding: 10px; border-radius: 5px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📋 Problem Status Update</h1>
        </div>
        
        <div class="content">
            <p>Dear {{ $user->name }},</p>
            
            <div class="highlight">
                <p><strong>{{ $statusMessage }}</strong></p>
            </div>
            
            <div class="status-update status-{{ $newStatus }}">
                <h3>{{ $problem->title }}</h3>
                <p><strong>Previous Status:</strong> {{ ucfirst(str_replace('_', ' ', $oldStatus)) }}</p>
                <p><strong>Current Status:</strong> {{ ucfirst(str_replace('_', ' ', $newStatus)) }}</p>
                <p><strong>Category:</strong> {{ ucfirst($problem->category) }}</p>
                <p><strong>Priority:</strong> {{ ucfirst($problem->priority) }}</p>
                <p><strong>Updated Date:</strong> {{ now()->format('d M Y H:i') }}</p>
                
                @if($problem->resolution_notes)
                <p><strong>Resolution Notes:</strong> {{ $problem->resolution_notes }}</p>
                @endif
                
                @if($problem->admin_notes)
                <p><strong>Admin Notes:</strong> {{ $problem->admin_notes }}</p>
                @endif
            </div>
            
            @if($newStatus === 'in_progress')
            <div class="highlight">
                <p>🎉 <strong>Great news!</strong> Your problem has been accepted and our team is now working on it. You will receive another notification once the work is completed.</p>
            </div>
            @endif
            
            @if($newStatus === 'resolved')
            <div class="highlight">
                <p>✅ <strong>Work Completed!</strong> Your problem has been resolved. We would appreciate your feedback on the service provided.</p>
            </div>
            <a href="{{ url('/problems/' . $problem->id . '/feedback') }}" class="btn btn-feedback">Provide Feedback</a>
            @endif
            
            <a href="{{ url('/problems/' . $problem->id) }}" class="btn">View Problem Details</a>
            
            <p>Thank you for using Booth Care system.</p>
            
            <p>Best regards,<br>Booth Care Team</p>
        </div>
    </div>
</body>
</html>