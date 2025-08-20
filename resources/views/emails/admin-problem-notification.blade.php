<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Problem Reported</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #007bff; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f8f9fa; }
        .problem-details { background: white; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .priority-high { border-left: 4px solid #dc3545; }
        .priority-medium { border-left: 4px solid #ffc107; }
        .priority-low { border-left: 4px solid #28a745; }
        .priority-urgent { border-left: 4px solid #6f42c1; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚨 New Problem Reported</h1>
        </div>
        
        <div class="content">
            <p>Dear {{ $admin->name }},</p>
            
            <p>A new problem has been reported in the Booth Care system and requires your attention.</p>
            
            <div class="problem-details priority-{{ $problem->priority }}">
                <h3>{{ $problem->title }}</h3>
                <p><strong>Description:</strong> {{ $problem->description }}</p>
                <p><strong>Category:</strong> {{ ucfirst($problem->category) }}</p>
                <p><strong>Priority:</strong> {{ ucfirst($problem->priority) }}</p>
                <p><strong>Reported by:</strong> {{ $problem->familyMember->name }}</p>
                <p><strong>House:</strong> {{ $problem->familyMember->house->house_number }}</p>
                <p><strong>Booth:</strong> {{ $problem->familyMember->house->booth->booth_name }}</p>
                <p><strong>Area:</strong> {{ $problem->familyMember->house->booth->area->area_name }}</p>
                <p><strong>Reported Date:</strong> {{ $problem->reported_date->format('d M Y') }}</p>
                @if($problem->expected_resolution_date)
                <p><strong>Expected Resolution:</strong> {{ $problem->expected_resolution_date->format('d M Y') }}</p>
                @endif
            </div>
            
            <p>Please review this problem and take appropriate action.</p>
            
            <a href="{{ url('/problems/' . $problem->id) }}" class="btn">View Problem Details</a>
            
            <p>Best regards,<br>Booth Care System</p>
        </div>
    </div>
</body>
</html>