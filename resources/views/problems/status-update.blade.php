@extends('layouts.app')

@section('title', 'Update Problem Status - Boothcare')
@section('page-title', 'Update Problem Status')

@push('styles')
<style>
    /* Remove top gaps */
    .main-content {
        padding-top: 0 !important;
        margin-top: 0 !important;
    }

    /* Modern Status Update Design */
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --danger-gradient: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
        --shadow-soft: 0 4px 20px rgba(0,0,0,0.08);
        --border-radius: 16px;
        --border-radius-sm: 12px;
    }

    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
    }

    .status-update-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    .status-card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-soft);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .status-header {
        background: var(--primary-gradient);
        color: white;
        padding: 2rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .status-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 100%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-10px) rotate(2deg); }
    }

    .status-header h1 {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 2;
    }

    .status-header p {
        opacity: 0.9;
        margin: 0;
        position: relative;
        z-index: 2;
    }

    .problem-info {
        padding: 2rem;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }

    .problem-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 1rem;
    }

    .problem-meta {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        color: #4a5568;
    }

    .meta-icon {
        color: #667eea;
        width: 16px;
    }

    .current-status {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .status-reported { background: #fed7d7; color: #c53030; }
    .status-in_progress { background: #bee3f8; color: #2b6cb0; }
    .status-resolved { background: #c6f6d5; color: #2f855a; }
    .status-closed { background: #e9d8fd; color: #6b46c1; }

    .form-section {
        padding: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .form-control {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: var(--border-radius-sm);
        font-size: 1rem;
        transition: all 0.3s ease;
        background: #f9fafb;
    }

    .form-control:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        background: white;
    }

    .status-select {
        position: relative;
    }

    .status-option {
        padding: 0.75rem 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
        transition: all 0.3s ease;
        border-radius: var(--border-radius-sm);
        margin-bottom: 0.5rem;
        border: 2px solid transparent;
    }

    .status-option:hover {
        background: #f3f4f6;
        transform: translateX(5px);
    }

    .status-option.selected {
        border-color: #667eea;
        background: #f0f4ff;
    }

    .status-icon {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.875rem;
    }

    .status-icon.reported { background: var(--warning-gradient); }
    .status-icon.in_progress { background: var(--primary-gradient); }
    .status-icon.resolved { background: var(--success-gradient); }
    .status-icon.closed { background: var(--danger-gradient); }

    .image-upload-area {
        border: 2px dashed #d1d5db;
        border-radius: var(--border-radius-sm);
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        background: #f9fafb;
    }

    .image-upload-area:hover {
        border-color: #667eea;
        background: #f0f4ff;
    }

    .image-upload-area.dragover {
        border-color: #667eea;
        background: #f0f4ff;
        transform: scale(1.02);
    }

    .upload-icon {
        width: 48px;
        height: 48px;
        margin: 0 auto 1rem;
        background: var(--primary-gradient);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
    }

    .image-preview {
        max-width: 200px;
        max-height: 200px;
        border-radius: var(--border-radius-sm);
        margin-top: 1rem;
        box-shadow: var(--shadow-soft);
    }

    .notification-section {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: var(--border-radius-sm);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .notification-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
        font-weight: 600;
        color: #0369a1;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .checkbox-custom {
        width: 20px;
        height: 20px;
        accent-color: #667eea;
    }

    .btn-update {
        background: var(--success-gradient);
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: var(--border-radius-sm);
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(79, 172, 254, 0.4);
        width: 100%;
    }

    .btn-update:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(79, 172, 254, 0.6);
    }

    .btn-cancel {
        background: #6b7280;
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: var(--border-radius-sm);
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        margin-right: 1rem;
    }

    .btn-cancel:hover {
        background: #4b5563;
        color: white;
        transform: translateY(-1px);
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #e5e7eb;
    }

    @media (max-width: 768px) {
        .status-update-container {
            padding: 1rem 0.5rem;
        }

        .status-header {
            padding: 1.5rem 1rem;
        }

        .status-header h1 {
            font-size: 1.5rem;
        }

        .problem-meta {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-cancel {
            margin-right: 0;
        }
    }
</style>
@endpush

@section('content')
<div class="status-update-container">
    <!-- Status Update Card -->
    <div class="status-card">
        <!-- Header -->
        <div class="status-header">
            <h1><i class="fas fa-edit me-2"></i>Update Problem Status</h1>
            <p>Change status, add notes, and notify the member</p>
        </div>

        <!-- Problem Information -->
        <div class="problem-info">
            <h2 class="problem-title">{{ $problem->title }}</h2>
            
            <div class="problem-meta">
                <div class="meta-item">
                    <i class="fas fa-user meta-icon"></i>
                    <span>{{ $problem->familyMember->name ?? 'Unknown Member' }}</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-map-marker-alt meta-icon"></i>
                    <span>
                        @if($problem->familyMember && $problem->familyMember->house)
                            {{ $problem->familyMember->house->booth->area->area_name ?? 'N/A' }} - 
                            House {{ $problem->familyMember->house->house_number ?? 'N/A' }}
                        @else
                            Location not available
                        @endif
                    </span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-calendar meta-icon"></i>
                    <span>{{ $problem->created_at->format('M d, Y') }}</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-flag meta-icon"></i>
                    <span>{{ ucfirst($problem->priority) }} Priority</span>
                </div>
            </div>

            <div class="meta-item">
                <i class="fas fa-info-circle meta-icon"></i>
                <span>Current Status: </span>
                <span class="current-status status-{{ $problem->status }}">
                    @if($problem->status === 'reported')
                        <i class="fas fa-exclamation-triangle"></i>
                    @elseif($problem->status === 'in_progress')
                        <i class="fas fa-cog fa-spin"></i>
                    @elseif($problem->status === 'resolved')
                        <i class="fas fa-check-circle"></i>
                    @else
                        <i class="fas fa-times-circle"></i>
                    @endif
                    {{ ucfirst(str_replace('_', ' ', $problem->status)) }}
                </span>
            </div>
        </div>

        <!-- Status Update Form -->
        <form method="POST" action="{{ route('problems.update-status', $problem) }}" enctype="multipart/form-data" class="form-section">
            @csrf

            <!-- Status Selection -->
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-tasks me-2"></i>New Status
                </label>
                <div class="status-select">
                    <div class="status-option {{ $problem->status === 'reported' ? 'selected' : '' }}" onclick="selectStatus('reported')">
                        <div class="status-icon reported">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div>
                            <strong>Reported</strong>
                            <div class="text-muted small">Problem has been reported and is awaiting review</div>
                        </div>
                    </div>
                    
                    <div class="status-option {{ $problem->status === 'in_progress' ? 'selected' : '' }}" onclick="selectStatus('in_progress')">
                        <div class="status-icon in_progress">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div>
                            <strong>In Progress</strong>
                            <div class="text-muted small">Work is currently being done to resolve the problem</div>
                        </div>
                    </div>
                    
                    <div class="status-option {{ $problem->status === 'resolved' ? 'selected' : '' }}" onclick="selectStatus('resolved')">
                        <div class="status-icon resolved">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <strong>Resolved</strong>
                            <div class="text-muted small">Problem has been successfully resolved</div>
                        </div>
                    </div>
                    
                    <div class="status-option {{ $problem->status === 'closed' ? 'selected' : '' }}" onclick="selectStatus('closed')">
                        <div class="status-icon closed">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div>
                            <strong>Closed</strong>
                            <div class="text-muted small">Problem is closed (may be unresolved)</div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="status" id="selectedStatus" value="{{ $problem->status }}" required>
            </div>

            <!-- Admin Notes -->
            <div class="form-group">
                <label for="admin_notes" class="form-label">
                    <i class="fas fa-sticky-note me-2"></i>Admin Notes
                </label>
                <textarea name="admin_notes" id="admin_notes" class="form-control" rows="4" 
                          placeholder="Add internal notes about this status update (visible to admins only)..."></textarea>
            </div>

            <!-- Resolution Notes (shown when resolved) -->
            <div class="form-group" id="resolutionNotesGroup" style="display: none;">
                <label for="resolution_notes" class="form-label">
                    <i class="fas fa-check-circle me-2"></i>Resolution Notes
                </label>
                <textarea name="resolution_notes" id="resolution_notes" class="form-control" rows="4" 
                          placeholder="Describe how the problem was resolved (visible to member)..."></textarea>
            </div>

            <!-- Actual Cost (shown when resolved) -->
            <div class="form-group" id="actualCostGroup" style="display: none;">
                <label for="actual_cost" class="form-label">
                    <i class="fas fa-dollar-sign me-2"></i>Actual Cost (৳)
                </label>
                <input type="number" name="actual_cost" id="actual_cost" class="form-control" 
                       min="0" step="0.01" placeholder="Enter actual cost incurred">
            </div>

            <!-- Status Update Image -->
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-camera me-2"></i>Update Image (Optional)
                </label>
                <div class="image-upload-area" onclick="document.getElementById('status_update_image').click()">
                    <div class="upload-icon">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <h4>Upload Progress Image</h4>
                    <p class="text-muted">Click to select or drag and drop an image showing the current progress</p>
                    <small class="text-muted">Supported formats: JPG, PNG (Max: 2MB)</small>
                </div>
                <input type="file" name="status_update_image" id="status_update_image" 
                       accept="image/jpeg,image/png,image/jpg" style="display: none;" onchange="previewImage(this)">
                <img id="imagePreview" class="image-preview" style="display: none;">
            </div>

            <!-- Email Notification -->
            <div class="notification-section">
                <div class="notification-header">
                    <i class="fas fa-envelope"></i>
                    <span>Email Notification</span>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" name="notify_member" id="notify_member" class="checkbox-custom" checked>
                    <label for="notify_member">
                        Send email notification to the member about this status update
                    </label>
                </div>
                <small class="text-muted mt-2 d-block">
                    The member will receive an email with the status change, your notes, and any uploaded image.
                </small>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('problems.show', $problem) }}" class="btn-cancel">
                    <i class="fas fa-times me-2"></i>Cancel
                </a>
                <button type="submit" class="btn-update">
                    <i class="fas fa-save me-2"></i>Update Status
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function selectStatus(status) {
    // Remove selected class from all options
    document.querySelectorAll('.status-option').forEach(option => {
        option.classList.remove('selected');
    });
    
    // Add selected class to clicked option
    event.currentTarget.classList.add('selected');
    
    // Update hidden input
    document.getElementById('selectedStatus').value = status;
    
    // Show/hide resolution fields
    const resolutionNotesGroup = document.getElementById('resolutionNotesGroup');
    const actualCostGroup = document.getElementById('actualCostGroup');
    
    if (status === 'resolved') {
        resolutionNotesGroup.style.display = 'block';
        actualCostGroup.style.display = 'block';
        document.getElementById('resolution_notes').required = true;
    } else {
        resolutionNotesGroup.style.display = 'none';
        actualCostGroup.style.display = 'none';
        document.getElementById('resolution_notes').required = false;
    }
}

function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const uploadArea = document.querySelector('.image-upload-area');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            
            // Update upload area
            uploadArea.innerHTML = `
                <div class="upload-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h4>Image Selected</h4>
                <p class="text-muted">${input.files[0].name}</p>
                <small class="text-muted">Click to change image</small>
            `;
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Drag and drop functionality
const uploadArea = document.querySelector('.image-upload-area');

uploadArea.addEventListener('dragover', function(e) {
    e.preventDefault();
    this.classList.add('dragover');
});

uploadArea.addEventListener('dragleave', function(e) {
    e.preventDefault();
    this.classList.remove('dragover');
});

uploadArea.addEventListener('drop', function(e) {
    e.preventDefault();
    this.classList.remove('dragover');
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        document.getElementById('status_update_image').files = files;
        previewImage(document.getElementById('status_update_image'));
    }
});

// Initialize form based on current status
document.addEventListener('DOMContentLoaded', function() {
    const currentStatus = '{{ $problem->status }}';
    selectStatus(currentStatus);
});

console.log('✅ Status update page loaded successfully');
</script>
@endpush
@endsection