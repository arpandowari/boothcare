@extends('layouts.app')

@section('title', 'Edit Problem - Boothcare')
@section('page-title', 'Edit Problem')

@push('styles')
<style>
    /* Professional Problem Edit Page */
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --shadow-soft: 0 4px 20px rgba(0,0,0,0.08);
        --shadow-hover: 0 8px 30px rgba(0,0,0,0.15);
        --border-radius: 16px;
        --border-radius-sm: 12px;
    }

    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
    }

    /* Hero Header */
    .edit-header {
        background: var(--warning-gradient);
        color: white;
        padding: 2rem 0;
        margin: -1rem -1rem 2rem -1rem;
        border-radius: 0 0 var(--border-radius) var(--border-radius);
        box-shadow: var(--shadow-soft);
        position: relative;
        overflow: hidden;
    }

    .edit-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 100%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: heroFloat 8s ease-in-out infinite;
    }

    @keyframes heroFloat {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-15px) rotate(3deg); }
    }

    .edit-title {
        font-size: 2.5rem;
        font-weight: 900;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .edit-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        margin: 0;
    }

    /* Breadcrumb */
    .breadcrumb-nav {
        background: white;
        border-radius: var(--border-radius-sm);
        padding: 1rem 1.5rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-soft);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .breadcrumb {
        margin: 0;
        background: none;
        padding: 0;
    }

    .breadcrumb-item a {
        color: #667eea;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .breadcrumb-item a:hover {
        color: #5a67d8;
        transform: translateX(2px);
    }

    .breadcrumb-item.active {
        color: #6b7280;
        font-weight: 600;
    }

    /* Form Container */
    .form-container {
        max-width: 900px;
        margin: 0 auto;
    }

    .form-card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-soft);
        border: 1px solid rgba(255, 255, 255, 0.2);
        overflow: hidden;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    .form-card:hover {
        box-shadow: var(--shadow-hover);
        transform: translateY(-2px);
    }

    .form-card-header {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .form-card-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: white;
        background: var(--warning-gradient);
    }

    .form-card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #2d3748;
        margin: 0;
    }

    .form-card-body {
        padding: 2rem;
    }

    /* Form Elements */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
        display: block;
    }

    .form-label.required::after {
        content: ' *';
        color: #dc3545;
    }

    .form-control, .form-select {
        border: 2px solid #e5e7eb;
        border-radius: var(--border-radius-sm);
        padding: 0.875rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: white;
        width: 100%;
    }

    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
        background: white;
        transform: translateY(-1px);
    }

    .form-control.is-invalid {
        border-color: #dc3545;
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
    }

    .invalid-feedback {
        display: block;
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        font-weight: 500;
    }

    .form-text {
        color: #6b7280;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        line-height: 1.4;
    }

    /* Alert Styles */
    .alert-modern {
        border: none;
        border-radius: var(--border-radius-sm);
        padding: 1.5rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .alert-info-modern {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        color: #1565c0;
        border-left: 4px solid #2196f3;
    }

    .alert-icon {
        font-size: 1.25rem;
        margin-top: 0.125rem;
    }

    .member-info {
        flex: 1;
    }

    .member-name {
        font-size: 1.1rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 0.25rem;
    }

    .member-details {
        font-size: 0.9rem;
        color: #6b7280;
        line-height: 1.4;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 1rem;
        justify-content: space-between;
        padding-top: 2rem;
        border-top: 2px solid #f1f5f9;
        margin-top: 2rem;
    }

    .btn-modern {
        padding: 0.875rem 2rem;
        border: none;
        border-radius: var(--border-radius-sm);
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        min-height: 48px;
    }

    .btn-primary-modern {
        background: var(--warning-gradient);
        color: white;
        box-shadow: 0 4px 15px rgba(240, 147, 251, 0.4);
    }

    .btn-primary-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(240, 147, 251, 0.6);
        color: white;
    }

    .btn-secondary-modern {
        background: #f8f9fa;
        color: #6c757d;
        border: 2px solid #e9ecef;
    }

    .btn-secondary-modern:hover {
        background: #e9ecef;
        color: #495057;
        transform: translateY(-2px);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .edit-title {
            font-size: 2rem;
        }

        .form-card-body {
            padding: 1.5rem;
        }

        .action-buttons {
            flex-direction: column-reverse;
        }

        .btn-modern {
            justify-content: center;
        }
    }

    /* Animations */
    .fade-in {
        animation: fadeIn 0.6s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Hero Header -->
    <div class="edit-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="edit-title">
                        <i class="fas fa-edit me-3"></i>
                        Edit Problem
                    </h1>
                    <p class="edit-subtitle">
                        Update problem information and status
                    </p>
                </div>
                <div class="col-lg-4 text-end">
                    <a href="{{ route('problems.show', $problem) }}" class="btn btn-light btn-lg">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back to Problem
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('problems.index') }}">Problems</a></li>
            <li class="breadcrumb-item"><a href="{{ route('problems.show', $problem) }}">Problem #{{ $problem->id }}</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>

    <div class="form-container fade-in">
        <!-- Member Information -->
        <div class="alert-modern alert-info-modern">
            <i class="fas fa-info-circle alert-icon"></i>
            <div class="member-info">
                <h6 class="member-name">Problem Reporter</h6>
                <div class="d-flex align-items-center">
                    <i class="fas fa-user me-2"></i>
                    <div class="member-details">
                        @if($problem->familyMember)
                            <strong>{{ $problem->familyMember->name }}</strong>
                            <br>{{ $problem->familyMember->relation_to_head ?? 'Family Member' }} |
                            @if($problem->familyMember->house)
                                House {{ $problem->familyMember->house->house_number ?? 'N/A' }} |
                                @if($problem->familyMember->house->booth)
                                    Booth {{ $problem->familyMember->house->booth->booth_number ?? 'N/A' }}
                                @else
                                    Booth N/A
                                @endif
                            @else
                                House N/A | Booth N/A
                            @endif
                        @else
                            <strong>Unknown Member</strong>
                            <br>Member information not available
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Problem Edit Form -->
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <i class="fas fa-edit"></i>
                </div>
                <h3 class="form-card-title">Edit Problem Information</h3>
            </div>
            <div class="form-card-body">
                <form method="POST" action="{{ route('problems.update', $problem) }}" id="editProblemForm">
                    @csrf
                    @method('PUT')

                    <!-- Problem Details -->
                    <div class="mb-3">
                        <label for="problem_type" class="form-label">Problem Type <span class="text-danger">*</span></label>
                        <select class="form-select @error('problem_type') is-invalid @enderror" id="problem_type" name="problem_type" required>
                            <option value="">Select Problem Type</option>
                            <option value="health" {{ old('problem_type', $problem->problem_type) == 'health' ? 'selected' : '' }}>Health</option>
                            <option value="education" {{ old('problem_type', $problem->problem_type) == 'education' ? 'selected' : '' }}>Education</option>
                            <option value="employment" {{ old('problem_type', $problem->problem_type) == 'employment' ? 'selected' : '' }}>Employment</option>
                            <option value="housing" {{ old('problem_type', $problem->problem_type) == 'housing' ? 'selected' : '' }}>Housing</option>
                            <option value="infrastructure" {{ old('problem_type', $problem->problem_type) == 'infrastructure' ? 'selected' : '' }}>Infrastructure</option>
                            <option value="legal" {{ old('problem_type', $problem->problem_type) == 'legal' ? 'selected' : '' }}>Legal</option>
                            <option value="financial" {{ old('problem_type', $problem->problem_type) == 'financial' ? 'selected' : '' }}>Financial</option>
                            <option value="social" {{ old('problem_type', $problem->problem_type) == 'social' ? 'selected' : '' }}>Social</option>
                            <option value="other" {{ old('problem_type', $problem->problem_type) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('problem_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="problem_description" class="form-label">Problem Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('problem_description') is-invalid @enderror"
                            id="problem_description" name="problem_description" rows="4" required>{{ old('problem_description', $problem->problem_description) }}</textarea>
                        @error('problem_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                                <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                                    <option value="">Select Priority</option>
                                    <option value="low" {{ old('priority', $problem->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ old('priority', $problem->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ old('priority', $problem->priority) == 'high' ? 'selected' : '' }}>High</option>
                                    <option value="urgent" {{ old('priority', $problem->priority) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                </select>
                                @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="pending" {{ old('status', $problem->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_progress" {{ old('status', $problem->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="resolved" {{ old('status', $problem->status) == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    <option value="closed" {{ old('status', $problem->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="reported_date" class="form-label">Reported Date</label>
                                <input type="date" class="form-control @error('reported_date') is-invalid @enderror"
                                    id="reported_date" name="reported_date" value="{{ old('reported_date', $problem->reported_date ? $problem->reported_date->format('Y-m-d') : '') }}">
                                @error('reported_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="expected_resolution_date" class="form-label">Expected Resolution Date</label>
                                <input type="date" class="form-control @error('expected_resolution_date') is-invalid @enderror"
                                    id="expected_resolution_date" name="expected_resolution_date" value="{{ old('expected_resolution_date', $problem->expected_resolution_date ? $problem->expected_resolution_date->format('Y-m-d') : '') }}">
                                @error('expected_resolution_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="assigned_to" class="form-label">Assigned To</label>
                        <input type="text" class="form-control @error('assigned_to') is-invalid @enderror"
                            id="assigned_to" name="assigned_to" value="{{ old('assigned_to', $problem->assigned_to) }}"
                            placeholder="Name of person/department assigned to handle this problem">
                        @error('assigned_to')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="resolution_notes" class="form-label">Resolution Notes</label>
                        <textarea class="form-control @error('resolution_notes') is-invalid @enderror"
                            id="resolution_notes" name="resolution_notes" rows="3"
                            placeholder="Notes about the resolution or progress made">{{ old('resolution_notes', $problem->resolution_notes) }}</textarea>
                        @error('resolution_notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="admin_notes" class="form-label">Admin Notes</label>
                        <textarea class="form-control @error('admin_notes') is-invalid @enderror"
                            id="admin_notes" name="admin_notes" rows="3"
                            placeholder="Internal notes for administrative purposes">{{ old('admin_notes', $problem->admin_notes) }}</textarea>
                        @error('admin_notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="action-buttons">
                        <a href="{{ route('problems.show', $problem) }}" class="btn-modern btn-secondary-modern">
                            <i class="fas fa-arrow-left"></i>
                            Cancel
                        </a>
                        <button type="submit" class="btn-modern btn-primary-modern">
                            <i class="fas fa-save"></i>
                            Update Problem
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-set reported date if not set
    document.addEventListener('DOMContentLoaded', function() {
        const reportedDateInput = document.getElementById('reported_date');
        if (!reportedDateInput.value) {
            const today = new Date().toISOString().split('T')[0];
            reportedDateInput.value = today;
        }
    });

    // Update expected resolution date based on priority
    document.getElementById('priority').addEventListener('change', function() {
        const priority = this.value;
        const expectedDateInput = document.getElementById('expected_resolution_date');

        if (priority && !expectedDateInput.value) {
            const today = new Date();
            let daysToAdd = 30; // default

            switch (priority) {
                case 'urgent':
                    daysToAdd = 3;
                    break;
                case 'high':
                    daysToAdd = 7;
                    break;
                case 'medium':
                    daysToAdd = 15;
                    break;
                case 'low':
                    daysToAdd = 30;
                    break;
            }

            const expectedDate = new Date(today.getTime() + (daysToAdd * 24 * 60 * 60 * 1000));
            expectedDateInput.value = expectedDate.toISOString().split('T')[0];
        }
    });
</script>
@endpush
@endsection