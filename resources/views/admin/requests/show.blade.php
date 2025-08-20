<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Request #{{ $request->id }} - Boothcare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background-color: #f8f9fa;
            padding: 20px;
        }
        .main-container { 
            max-width: 1200px; 
            margin: 0 auto; 
            background: white; 
            border-radius: 10px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
        }
        .action-card { 
            border: 2px solid; 
            border-radius: 10px; 
            padding: 20px; 
            text-align: center; 
            margin: 10px 0;
        }
        .approve-card { 
            border-color: #28a745; 
            background: #f8fff9; 
        }
        .reject-card { 
            border-color: #dc3545; 
            background: #fff8f8; 
        }
        .btn-action { 
            padding: 15px 40px; 
            font-size: 18px; 
            font-weight: bold; 
            border: none; 
            border-radius: 8px; 
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-approve { 
            background: #28a745; 
            color: white; 
        }
        .btn-approve:hover { 
            background: #218838; 
            transform: translateY(-2px);
        }
        .btn-reject { 
            background: #dc3545; 
            color: white; 
        }
        .btn-reject:hover { 
            background: #c82333; 
            transform: translateY(-2px);
        }
        .debug-info { 
            background: #e9ecef; 
            padding: 15px; 
            border-radius: 5px; 
            margin: 20px 0; 
            font-size: 12px;
        }
        .test-section {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-file-alt text-primary"></i> Update Request #{{ $request->id }}</h1>
            <a href="{{ route('admin.requests.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
        
        <!-- Toast notifications will be handled by the layout -->
        
        <!-- Request Information -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5><i class="fas fa-info-circle"></i> Request Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <strong>Type:</strong><br>
                        <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $request->request_type)) }}</span>
                    </div>
                    <div class="col-md-3">
                        <strong>Status:</strong><br>
                        <span class="badge bg-{{ $request->status === 'approved' ? 'success' : ($request->status === 'rejected' ? 'danger' : 'warning') }}">
                            {{ ucfirst($request->status) }}
                        </span>
                    </div>
                    <div class="col-md-3">
                        <strong>Submitted By:</strong><br>
                        {{ $request->user ? $request->user->name : 'Public User' }}
                    </div>
                    <div class="col-md-3">
                        <strong>Date:</strong><br>
                        {{ $request->created_at->format('M d, Y H:i') }}
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Problem Details -->
        @if(in_array($request->request_type, ['problem_creation', 'public_problem_creation']))
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5><i class="fas fa-exclamation-triangle"></i> Problem Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Family Member</h6>
                        <p><strong>Name:</strong> {{ $request->current_data['family_member_name'] ?? 'N/A' }}</p>
                        <p><strong>House:</strong> {{ $request->current_data['house'] ?? 'N/A' }}</p>
                        <p><strong>Area:</strong> {{ $request->current_data['area'] ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Problem Information</h6>
                        <p><strong>Title:</strong> {{ $request->requested_data['title'] ?? 'N/A' }}</p>
                        <p><strong>Category:</strong> {{ ucfirst($request->requested_data['category'] ?? 'N/A') }}</p>
                        <p><strong>Priority:</strong> {{ ucfirst($request->requested_data['priority_request'] ?? 'medium') }}</p>
                        <p><strong>Description:</strong> {{ Str::limit($request->requested_data['description'] ?? 'N/A', 200) }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        <!-- JavaScript Test Section -->
        <div class="test-section">
            <h6><i class="fas fa-code"></i> JavaScript Test</h6>
            <p>Click this button to test if JavaScript is working:</p>
            <button onclick="testJavaScript()" class="btn btn-warning">
                <i class="fas fa-play"></i> Test JavaScript
            </button>
            <span id="testResult" style="margin-left: 10px;"></span>
        </div>
        
        <!-- Admin Actions -->
        @if($request->status === 'pending' && Auth::user()->isAdminOrSubAdmin())
        <div class="row">
            <div class="col-md-6">
                <div class="action-card approve-card">
                    <h4><i class="fas fa-check-circle text-success"></i> APPROVE REQUEST</h4>
                    <p>This will create a new problem record and mark the request as approved.</p>
                    <button onclick="approveRequest()" class="btn-action btn-approve">
                        <i class="fas fa-thumbs-up"></i> APPROVE NOW
                    </button>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="action-card reject-card">
                    <h4><i class="fas fa-times-circle text-danger"></i> REJECT REQUEST</h4>
                    <p>This will permanently reject the request with a reason.</p>
                    <button onclick="rejectRequest()" class="btn-action btn-reject">
                        <i class="fas fa-thumbs-down"></i> REJECT NOW
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Hidden Forms -->
        <form id="approveForm" method="GET" action="{{ route('admin.requests.approve-problem-get', $request) }}" style="display: none;"></form>
        <form id="rejectForm" method="POST" action="{{ route('admin.requests.reject-problem', $request) }}" style="display: none;">
            @csrf
            <input type="hidden" name="review_notes" id="rejectReason">
        </form>
        @endif
        
        <!-- Debug Information -->
        <div class="debug-info">
            <h6><i class="fas fa-bug"></i> Debug Information</h6>
            <div class="row">
                <div class="col-md-6">
                    <strong>Current User:</strong> {{ Auth::user()->name }}<br>
                    <strong>User Role:</strong> {{ Auth::user()->role }}<br>
                    <strong>Is Admin:</strong> {{ Auth::user()->isAdminOrSubAdmin() ? 'YES' : 'NO' }}
                </div>
                <div class="col-md-6">
                    <strong>Request ID:</strong> {{ $request->id }}<br>
                    <strong>Status:</strong> {{ $request->status }}<br>
                    <strong>Type:</strong> {{ $request->request_type }}
                </div>
            </div>
            <div class="mt-2">
                <strong>URLs:</strong><br>
                <small>
                    Approve: {{ route('admin.requests.approve-problem-get', $request) }}<br>
                    Reject: {{ route('admin.requests.reject-problem', $request) }}
                </small>
            </div>
        </div>
    </div>

    <!-- CLEAN JAVASCRIPT - NO CONFLICTS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        console.log('🚀 Standalone page loaded - no layout conflicts');
        
        // Test function
        function testJavaScript() {
            console.log('✅ JavaScript test function called');
            alert('🎉 JavaScript is working perfectly!');
            document.getElementById('testResult').innerHTML = '<span class="text-success"><i class="fas fa-check"></i> JavaScript Working!</span>';
        }
        
        // Approve function
        function approveRequest() {
            console.log('📝 Approve function called');
            
            const confirmed = confirm(
                '⚠️ CONFIRM APPROVAL\n\n' +
                'This will:\n' +
                '✅ Create a new problem record\n' +
                '✅ Mark request as approved\n' +
                '✅ Send notifications\n\n' +
                'Click OK to APPROVE or Cancel to stop.'
            );
            
            if (confirmed) {
                console.log('✅ User confirmed approval - submitting form');
                document.getElementById('approveForm').submit();
            } else {
                console.log('❌ User cancelled approval');
            }
        }
        
        // Reject function
        function rejectRequest() {
            console.log('📝 Reject function called');
            
            const reason = prompt(
                '📝 REJECTION REASON\n\n' +
                'Please provide a clear reason for rejecting this request:',
                'Request does not meet approval criteria'
            );
            
            if (reason && reason.trim() !== '') {
                console.log('📝 User provided reason:', reason);
                
                const confirmed = confirm(
                    '⚠️ CONFIRM REJECTION\n\n' +
                    'Reason: ' + reason + '\n\n' +
                    'This will:\n' +
                    '❌ Permanently reject the request\n' +
                    '❌ Send rejection notification\n' +
                    '❌ Cannot be undone\n\n' +
                    'Click OK to REJECT or Cancel to stop.'
                );
                
                if (confirmed) {
                    console.log('✅ User confirmed rejection - submitting form');
                    document.getElementById('rejectReason').value = reason;
                    document.getElementById('rejectForm').submit();
                } else {
                    console.log('❌ User cancelled rejection');
                }
            } else {
                console.log('❌ User cancelled or provided empty reason');
                alert('❌ Rejection cancelled. A reason is required.');
            }
        }
        
        // Page ready
        document.addEventListener('DOMContentLoaded', function() {
            console.log('✅ Page fully loaded and ready');
            console.log('🔧 Available functions: testJavaScript, approveRequest, rejectRequest');
        });
    </script>
</body>
</html>