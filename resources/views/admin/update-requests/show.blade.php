@extends('layouts.app')

@section('title', 'Update Request Details - Admin')
@section('page-title', 'Update Request Details')

@push('styles')
<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        min-height: 100vh;
    }

    .container-fluid {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }

    .page-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .header-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .back-button {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .back-button:hover {
        color: white;
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }

    .header-badges {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .badge-id, .badge-type, .badge-status {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.9rem;
    }

    .badge-id {
        background: linear-gradient(135deg, #374151 0%, #1f2937 100%);
        color: white;
    }

    .badge-type.type-documents {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }

    .badge-type.type-profile {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
    }

    .badge-type.type-family_member {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
    }

    .badge-status.status-pending {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }

    .badge-status.status-approved {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .badge-status.status-rejected {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        border-top: 1px solid rgba(0, 0, 0, 0.1);
        padding-top: 1.5rem;
    }

    .user-avatar {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .user-details h1 {
        font-size: 2rem;
        font-weight: 800;
        color: #1f2937;
        margin: 0 0 0.25rem 0;
    }

    .user-details p {
        color: #6b7280;
        margin: 0;
        font-weight: 500;
    }

    .card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    .card-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 16px 16px 0 0;
        padding: 1.5rem;
    }

    .card-title {
        color: #1f2937;
        font-weight: 700;
        margin: 0;
        font-size: 1.1rem;
    }

    .card-title i {
        color: #667eea;
    }

    .card-body {
        padding: 1.5rem;
    }

    .info-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1rem;
        transition: all 0.2s ease;
    }

    .info-item:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
        transform: translateY(-1px);
    }

    .info-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
    }

    .info-value {
        font-size: 1rem;
        font-weight: 500;
        color: #1f2937;
    }

    .btn-action {
        padding: 0.875rem 2rem;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 12px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        width: 100%;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    .changes-column {
        padding: 1rem;
        border-radius: 8px;
        border: 2px solid;
        margin-bottom: 1rem;
    }

    .current-data {
        background: #fef2f2;
        border-color: #fecaca;
    }

    .requested-data {
        background: #f0fdf4;
        border-color: #bbf7d0;
    }

    .changes-title {
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .change-item {
        margin-bottom: 0.75rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid rgba(0,0,0,0.1);
    }

    .change-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .change-field {
        font-size: 0.8rem;
        font-weight: 600;
        color: #6b7280;
        margin-bottom: 0.25rem;
    }

    .change-value {
        font-size: 0.9rem;
        color: #1f2937;
        word-break: break-word;
    }

    @media (max-width: 768px) {
        .container-fluid {
            padding: 1rem;
        }
        
        .header-top {
            flex-direction: column;
            align-items: stretch;
        }
        
        .header-badges {
            justify-content: center;
        }
        
        .user-info {
            flex-direction: column;
            text-align: center;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Clean Header Section -->
    <div class="page-header">
        <div class="header-top">
            <a href="{{ route('admin.update-requests.index') }}" class="back-button">
                <i class="fas fa-arrow-left me-2"></i>
                Back to Requests
            </a>
            <div class="header-badges">
                <span class="badge-id">#{{ $updateRequest->id }}</span>
                <span class="badge-type type-{{ $updateRequest->request_type }}">
                    {{ ucfirst(str_replace('_', ' ', $updateRequest->request_type)) }}
                </span>
                <span class="badge-status status-{{ $updateRequest->status }}">
                    @if($updateRequest->status === 'pending')
                        <i class="fas fa-clock me-1"></i>Pending
                    @elseif($updateRequest->status === 'approved')
                        <i class="fas fa-check-circle me-1"></i>Approved
                    @else
                        <i class="fas fa-times-circle me-1"></i>Rejected
                    @endif
                </span>
            </div>
        </div>
        
        <div class="user-info">
            <div class="user-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="user-details">
                <h1>{{ $updateRequest->user->name }}</h1>
                <p>Submitted {{ $updateRequest->created_at->format('M d, Y') }} at {{ $updateRequest->created_at->format('H:i A') }}</p>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row g-4">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- User Information Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-user me-2"></i>
                        User Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">Full Name</div>
                                <div class="info-value">{{ $updateRequest->user->name }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">Email Address</div>
                                <div class="info-value">{{ $updateRequest->user->email }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">Phone Number</div>
                                <div class="info-value">{{ $updateRequest->user->phone ?? 'Not provided' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">User Role</div>
                                <div class="info-value">
                                    <span class="badge bg-secondary">{{ ucfirst($updateRequest->user->role) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Request Details Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-clipboard-list me-2"></i>
                        Request Details
                    </h5>
                </div>
                <div class="card-body">
                    @if($updateRequest->reason)
                        <div class="alert alert-info border-0 mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Reason:</strong> {{ $updateRequest->reason }}
                        </div>
                    @endif

                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="info-item">
                                <div class="info-label">Request ID</div>
                                <div class="info-value">#{{ $updateRequest->id }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-item">
                                <div class="info-label">Request Type</div>
                                <div class="info-value">{{ ucfirst(str_replace('_', ' ', $updateRequest->request_type)) }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-item">
                                <div class="info-label">Submitted Date</div>
                                <div class="info-value">{{ $updateRequest->created_at->format('M d, Y') }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-item">
                                <div class="info-label">Submitted Time</div>
                                <div class="info-value">{{ $updateRequest->created_at->format('H:i A') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Changes Comparison -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-exchange-alt me-2"></i>
                        Requested Changes
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Current Data -->
                        <div class="col-md-6">
                            <div class="changes-column current-data">
                                <div class="changes-title">
                                    <i class="fas fa-database text-danger me-2"></i>
                                    Current Data
                                </div>
                                @if($updateRequest->current_data)
                                    @foreach($updateRequest->current_data as $field => $value)
                                        @if($value && $value !== 'Not set' && $value !== '' && $value !== null)
                                            <div class="change-item">
                                                <div class="change-field">{{ ucfirst(str_replace('_', ' ', $field)) }}</div>
                                                <div class="change-value">{{ $value }}</div>
                                            </div>
                                        @endif
                                    @endforeach
                                @else
                                    <p class="text-muted">No current data available</p>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Requested Data -->
                        <div class="col-md-6">
                            <div class="changes-column requested-data">
                                <div class="changes-title">
                                    <i class="fas fa-edit text-success me-2"></i>
                                    Requested Data
                                </div>
                                @if($updateRequest->requested_data)
                                    @foreach($updateRequest->requested_data as $field => $value)
                                        @if($field !== 'uploaded_documents' && $field !== 'uploaded_files')
                                            @php
                                                $shouldShow = false;
                                                if (is_array($value)) {
                                                    $shouldShow = !empty($value);
                                                } elseif (in_array($field, ['problem_photo', 'profile_photo', 'aadhar_photo', 'pan_photo', 'voter_id_photo', 'ration_card_photo'])) {
                                                    $shouldShow = !empty($value);
                                                } else {
                                                    $shouldShow = $value && $value !== 'Not set' && $value !== '' && $value !== null;
                                                }
                                            @endphp
                                            
                                            @if($shouldShow)
                                                <div class="change-item">
                                                    <div class="change-field">{{ ucfirst(str_replace('_', ' ', $field)) }}</div>
                                                    <div class="change-value">
                                                        @if(is_array($value))
                                                            {{ json_encode($value, JSON_PRETTY_PRINT) }}
                                                        @elseif(in_array($field, ['problem_photo', 'profile_photo', 'aadhar_photo', 'pan_photo', 'voter_id_photo', 'ration_card_photo']) && $value)
                                                            <div class="mt-2">
                                                                <img src="{{ asset('storage/' . $value) }}" 
                                                                     alt="{{ ucfirst(str_replace('_', ' ', $field)) }}" 
                                                                     class="img-fluid rounded shadow-sm" 
                                                                     style="max-height: 200px; cursor: pointer;"
                                                                     onclick="showImageModal('{{ asset('storage/' . $value) }}')">
                                                                <br>
                                                                <small class="text-muted">{{ $value }}</small>
                                                            </div>
                                                        @else
                                                            {{ $value }}
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    @endforeach
                                    
                                    @php
                                        $hasVisibleData = false;
                                        foreach($updateRequest->requested_data as $field => $value) {
                                            if ($field !== 'uploaded_documents' && $field !== 'uploaded_files') {
                                                if (is_array($value)) {
                                                    if (!empty($value)) { $hasVisibleData = true; break; }
                                                } elseif (in_array($field, ['problem_photo', 'profile_photo', 'aadhar_photo', 'pan_photo', 'voter_id_photo', 'ration_card_photo'])) {
                                                    if (!empty($value)) { $hasVisibleData = true; break; }
                                                } else {
                                                    if ($value && $value !== 'Not set' && $value !== '' && $value !== null) { $hasVisibleData = true; break; }
                                                }
                                            }
                                        }
                                    @endphp
                                    
                                    @if(!$hasVisibleData)
                                        <p class="text-muted">No requested data to display</p>
                                    @endif
                                @else
                                    <p class="text-muted">No requested data available</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Actions -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-cogs me-2"></i>
                        Actions & Status
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <span class="badge-status status-{{ $updateRequest->status }}" style="font-size: 1rem; padding: 0.75rem 1.5rem;">
                            @if($updateRequest->status === 'pending')
                                <i class="fas fa-clock me-1"></i>PENDING
                            @elseif($updateRequest->status === 'approved')
                                <i class="fas fa-check-circle me-1"></i>APPROVED
                            @else
                                <i class="fas fa-times-circle me-1"></i>REJECTED
                            @endif
                        </span>
                    </div>
                    <p class="text-muted text-center mb-4">Current Status</p>
                    
                    @if($updateRequest->status === 'pending')
                        <button class="btn-action btn-success" onclick="approveRequest()">
                            <i class="fas fa-check me-2"></i>
                            Approve Request
                        </button>
                        <button class="btn-action btn-danger" onclick="rejectRequest()">
                            <i class="fas fa-times me-2"></i>
                            Reject Request
                        </button>
                    @else
                        <div class="alert alert-info border-0 text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            This request has been {{ $updateRequest->status }}
                        </div>
                    @endif
                    
                    <div class="mt-4 pt-3 border-top">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            {{ $updateRequest->user->name }} submitted this {{ $updateRequest->request_type }} update request.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Forms for Approval/Rejection -->
<form id="approveForm" method="POST" action="{{ route('admin.update-requests.approve', $updateRequest) }}" style="display: none;">
    @csrf
    <input type="hidden" name="review_notes" id="approveNotes">
</form>

<form id="rejectForm" method="POST" action="{{ route('admin.update-requests.reject', $updateRequest) }}" style="display: none;">
    @csrf
    <input type="hidden" name="review_notes" id="rejectNotes">
</form>

<script>
function approveRequest() {
    const notes = prompt('Add review notes (optional):');
    if (notes !== null) { // User didn't cancel
        document.getElementById('approveNotes').value = notes || '';
        document.getElementById('approveForm').submit();
    }
}

function rejectRequest() {
    const notes = prompt('Please provide a reason for rejection (required):');
    if (notes && notes.trim() !== '') {
        document.getElementById('rejectNotes').value = notes;
        document.getElementById('rejectForm').submit();
    } else if (notes !== null) {
        alert('Rejection reason is required!');
        rejectRequest(); // Try again
    }
}

function showImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    new bootstrap.Modal(document.getElementById('imageModal')).show();
}
</script>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Problem Photo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Problem Photo" class="img-fluid">
            </div>
        </div>
    </div>
</div>

@endsection