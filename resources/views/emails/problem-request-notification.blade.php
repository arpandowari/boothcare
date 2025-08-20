<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Problem Request</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #dc3545; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f8f9fa; }
        .request-details { background: white; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .priority-high { border-left: 4px solid #dc3545; }
        .priority-medium { border-left: 4px solid #ffc107; }
        .priority-low { border-left: 4px solid #28a745; }
        .priority-urgent { border-left: 4px solid #6f42c1; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; }
        .category-data { background: #e9ecef; padding: 10px; border-radius: 5px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚨 New Problem Request</h1>
        </div>
        
        <div class="content">
            <p>Dear {{ $admin->name }},</p>
            
            <p>A new problem request has been submitted and requires your review.</p>
            
            @php
                $requestData = $updateRequest->requested_data;
                $currentData = $updateRequest->current_data;
                $priority = $requestData['priority_request'] ?? 'medium';
            @endphp
            
            <div class="request-details priority-{{ $priority }}">
                <h3>{{ $requestData['title'] ?? 'Problem Request' }}</h3>
                <p><strong>Description:</strong> {{ $requestData['description'] ?? 'No description provided' }}</p>
                <p><strong>Category:</strong> {{ ucfirst($requestData['category'] ?? 'Other') }}</p>
                <p><strong>Requested Priority:</strong> {{ ucfirst($priority) }}</p>
                <p><strong>Family Member:</strong> {{ $currentData['family_member_name'] ?? 'Unknown' }}</p>
                <p><strong>House:</strong> {{ $currentData['house'] ?? 'Unknown' }}</p>
                <p><strong>Area:</strong> {{ $currentData['area'] ?? 'Unknown' }}</p>
                <p><strong>Request Type:</strong> {{ $updateRequest->request_type === 'public_problem_creation' ? 'Public Portal' : 'User Account' }}</p>
                <p><strong>Submitted:</strong> {{ $updateRequest->created_at->format('d M Y H:i') }}</p>
                
                @if(isset($currentData['contact_phone']))
                <p><strong>Contact Phone:</strong> {{ $currentData['contact_phone'] }}</p>
                @endif
            </div>
            
            @if($requestData['category'] && isset($requestData['health_type']) || isset($requestData['education_level']) || isset($requestData['employment_type']) || isset($requestData['housing_issue']) || isset($requestData['financial_issue']))
            <div class="category-data">
                <h4>Category-Specific Details:</h4>
                @if($requestData['category'] === 'health')
                    @if(isset($requestData['health_type']))<p><strong>Health Type:</strong> {{ $requestData['health_type'] }}</p>@endif
                    @if(isset($requestData['urgency_level']))<p><strong>Urgency:</strong> {{ $requestData['urgency_level'] }}</p>@endif
                @elseif($requestData['category'] === 'education')
                    @if(isset($requestData['education_level']))<p><strong>Education Level:</strong> {{ $requestData['education_level'] }}</p>@endif
                    @if(isset($requestData['education_issue']))<p><strong>Issue Type:</strong> {{ $requestData['education_issue'] }}</p>@endif
                @elseif($requestData['category'] === 'employment')
                    @if(isset($requestData['employment_type']))<p><strong>Employment Issue:</strong> {{ $requestData['employment_type'] }}</p>@endif
                    @if(isset($requestData['experience_level']))<p><strong>Experience:</strong> {{ $requestData['experience_level'] }}</p>@endif
                @elseif($requestData['category'] === 'housing')
                    @if(isset($requestData['housing_issue']))<p><strong>Housing Issue:</strong> {{ $requestData['housing_issue'] }}</p>@endif
                    @if(isset($requestData['housing_urgency']))<p><strong>Urgency:</strong> {{ $requestData['housing_urgency'] }}</p>@endif
                @elseif($requestData['category'] === 'financial')
                    @if(isset($requestData['financial_issue']))<p><strong>Financial Issue:</strong> {{ $requestData['financial_issue'] }}</p>@endif
                    @if(isset($requestData['amount_involved']))<p><strong>Amount:</strong> ₹{{ number_format($requestData['amount_involved']) }}</p>@endif
                @endif
            </div>
            @endif
            
            <p>Please review this request and create the problem record if appropriate.</p>
            
            <a href="{{ url('/update-requests/' . $updateRequest->id) }}" class="btn">Review Request</a>
            
            <p>Best regards,<br>Booth Care System</p>
        </div>
    </div>
</body>
</html>