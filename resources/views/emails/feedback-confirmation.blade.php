<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Thank You for Your Feedback</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #28a745; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f8f9fa; }
        .feedback-summary { background: white; padding: 15px; border-radius: 5px; margin: 10px 0; border-left: 4px solid #28a745; }
        .rating { font-size: 24px; color: #ffc107; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🙏 Thank You for Your Feedback</h1>
        </div>
        
        <div class="content">
            <p>Dear {{ $user->name }},</p>
            
            <p>Thank you for taking the time to provide feedback on your recent problem resolution. Your input is valuable and helps us improve our services.</p>
            
            <div class="feedback-summary">
                <h3>{{ $problem->title }}</h3>
                <p><strong>Problem Status:</strong> Resolved</p>
                <p><strong>Your Rating:</strong> 
                    <span class="rating">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $problem->user_rating)
                                ⭐
                            @else
                                ☆
                            @endif
                        @endfor
                    </span>
                    ({{ $problem->user_rating }}/5)
                </p>
                @if($problem->user_feedback)
                <p><strong>Your Feedback:</strong> {{ $problem->user_feedback }}</p>
                @endif
                <p><strong>Feedback Date:</strong> {{ $problem->feedback_date->format('d M Y H:i') }}</p>
            </div>
            
            <p>Your feedback has been recorded and will be used to improve our services. If you have any additional concerns or questions, please don't hesitate to contact us.</p>
            
            <a href="{{ url('/problems') }}" class="btn">View All Problems</a>
            
            <p>We appreciate your trust in the Booth Care system and look forward to serving you better in the future.</p>
            
            <p>Best regards,<br>Booth Care Team</p>
        </div>
    </div>
</body>
</html>