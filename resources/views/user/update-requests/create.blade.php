@extends('layouts.app')

@section('title', 'Submit Update Request')
@section('page-title', 'Submit Update Request')

@push('styles')
<style>
    /* Modern Form Styles */
    body {
        background: #f8fafc;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    .container-fluid {
        max-width: 800px;
        margin: 0 auto;
    }

    /* Header Section */
    .form-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem 1.5rem;
        margin: -1rem -1rem 2rem -1rem;
        border-radius: 0 0 20px 20px;
        box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
    }

    .form-header h1 {
        font-size: 1.8rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .form-header p {
        opacity: 0.9;
        margin: 0;
        font-size: 1rem;
    }

    /* Form Card */
    .form-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .form-card-header {
        background: #f8fafc;
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .form-card-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }

    .form-card-body {
        padding: 2rem;
    }

    /* Form Controls */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        display: block;
        font-size: 0.9rem;
    }

    .form-control, .form-select, .form-textarea {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        transition: all 0.2s ease;
        background: white;
        color: #1f2937;
        width: 100%;
    }

    .form-control:focus, .form-select:focus, .form-textarea:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
    }

    .form-textarea {
        resize: vertical;
        min-height: 100px;
    }

    /* Request Type Selection */
    .request-type-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .request-type-card {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
    }

    .request-type-card:hover {
        border-color: #667eea;
        background: #f8fafc;
    }

    .request-type-card.selected {
        border-color: #667eea;
        background: #f0f4ff;
    }

    .request-type-icon {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        color: #667eea;
    }

    .request-type-title {
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.25rem;
    }

    .request-type-description {
        font-size: 0.8rem;
        color: #6b7280;
    }

    /* Dynamic Fields */
    .dynamic-fields {
        display: none !important;
        margin-top: 1.5rem;
        padding: 1.5rem;
        background: #f8fafc;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }

    .dynamic-fields.active {
        display: block !important;
    }

    .field-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }

    /* Buttons */
    .btn {
        border: none;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        white-space: nowrap;
    }

    .btn-primary {
        background: #667eea;
        color: white;
    }

    .btn-primary:hover {
        background: #5a67d8;
        color: white;
        transform: translateY(-1px);
    }

    .btn-secondary {
        background: #6b7280;
        color: white;
    }

    .btn-secondary:hover {
        background: #4b5563;
        color: white;
        transform: translateY(-1px);
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e2e8f0;
    }

    /* File Upload Styles */
    .file-upload-group {
        margin-top: 1rem;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }

    .file-upload-title {
        font-weight: 600;
        color: #374151;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .file-input-wrapper {
        position: relative;
        display: inline-block;
        width: 100%;
        margin-bottom: 1rem;
    }

    .file-input {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }

    .file-input-display {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        border: 2px dashed #d1d5db;
        border-radius: 8px;
        background: white;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .file-input-display:hover {
        border-color: #667eea;
        background: #f8fafc;
    }

    .file-input-display.has-file {
        border-color: #10b981;
        background: #f0fdf4;
        border-style: solid;
    }

    .file-input-icon {
        font-size: 1.5rem;
        color: #6b7280;
    }

    .file-input-display.has-file .file-input-icon {
        color: #10b981;
    }

    .file-input-text {
        flex: 1;
    }

    .file-input-label {
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.25rem;
    }

    .file-input-description {
        font-size: 0.8rem;
        color: #6b7280;
    }

    .file-input-display.has-file .file-input-description {
        color: #059669;
    }

    .file-remove-btn {
        background: #ef4444;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 0.25rem 0.5rem;
        font-size: 0.7rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .file-remove-btn:hover {
        background: #dc2626;
    }

    .current-file-info {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 6px;
        padding: 0.75rem;
        margin-top: 0.5rem;
        font-size: 0.85rem;
    }

    .current-file-link {
        color: #0369a1;
        text-decoration: none;
        font-weight: 500;
    }

    .current-file-link:hover {
        text-decoration: underline;
    }

    /* Document Upload Grid */
    .document-upload-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .form-header {
            padding: 1.5rem 1rem;
            margin: -1rem -1rem 1.5rem -1rem;
        }

        .form-header h1 {
            font-size: 1.5rem;
        }

        .form-card-body {
            padding: 1.5rem;
        }

        .request-type-grid {
            grid-template-columns: 1fr;
        }

        .field-grid {
            grid-template-columns: 1fr;
        }

        .document-upload-grid {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Form Header -->
    <div class="form-header">
        <h1><i class="fas fa-paper-plane me-2"></i>Submit Update Request</h1>
        <p>Request changes to your profile, family member information, or documents</p>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <div class="form-card-header">
            <h3 class="form-card-title">Update Request Form</h3>
        </div>
        <div class="form-card-body">
            <form method="POST" action="{{ route('user.update-requests.store') }}" id="updateRequestForm" enctype="multipart/form-data">
                @csrf

                <!-- Request Type Selection -->
                <div class="form-group">
                    <label class="form-label">What would you like to update?</label>
                    <div class="request-type-grid">
                        <div class="request-type-card" data-type="profile">
                            <div class="request-type-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="request-type-title">Profile Information</div>
                            <div class="request-type-description">Update your basic profile details</div>
                        </div>
                        <div class="request-type-card" data-type="family_member">
                            <div class="request-type-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="request-type-title">Family Member Info</div>
                            <div class="request-type-description">Update family member details</div>
                        </div>
                        <div class="request-type-card" data-type="documents">
                            <div class="request-type-icon">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <div class="request-type-title">Document Information</div>
                            <div class="request-type-description">Update document numbers</div>
                        </div>
                    </div>
                    <input type="hidden" name="request_type" id="requestType" required>
                    @error('request_type')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Profile Fields -->
                <div class="dynamic-fields" id="profileFields">
                    <h4>Profile Information</h4>
                    <p class="text-muted mb-3">Update your basic profile information</p>
                    <div class="field-grid">
                        <div class="form-group">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="requested_data[name]" 
                                   value="{{ old('requested_data.name', $user->name ?? '') }}" 
                                   placeholder="Enter your full name" required>
                            <small class="text-muted">Current: {{ $user->name ?? 'Not set' }}</small>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="requested_data[email]" 
                                   value="{{ old('requested_data.email', $user->email ?? '') }}" 
                                   placeholder="Enter your email" required>
                            <small class="text-muted">Current: {{ $user->email ?? 'Not set' }}</small>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="requested_data[phone]" 
                                   value="{{ old('requested_data.phone', $user->phone ?? '') }}" 
                                   placeholder="Enter your phone number">
                            <small class="text-muted">Current: {{ $user->phone ?? 'Not set' }}</small>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" name="requested_data[date_of_birth]" 
                                   value="{{ old('requested_data.date_of_birth', $user->date_of_birth?->format('Y-m-d') ?? '') }}">
                            <small class="text-muted">Current: {{ $user->date_of_birth?->format('M d, Y') ?? 'Not set' }}</small>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Gender</label>
                            <select class="form-select" name="requested_data[gender]">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('requested_data.gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('requested_data.gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('requested_data.gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            <small class="text-muted">Current: {{ ucfirst($user->gender ?? 'Not set') }}</small>
                        </div>
                    </div>

                    <!-- Profile Document Uploads -->
                    <div class="file-upload-group">
                        <div class="file-upload-title">
                            <i class="fas fa-upload"></i>
                            Upload Profile Documents (Optional)
                        </div>
                        <div class="document-upload-grid">
                            <div class="file-input-wrapper">
                                <input type="file" class="file-input" name="profile_photo" accept="image/*" onchange="handleFileSelect(this, 'profile-photo-display')">
                                <div class="file-input-display" id="profile-photo-display">
                                    <div class="file-input-icon">
                                        <i class="fas fa-camera"></i>
                                    </div>
                                    <div class="file-input-text">
                                        <div class="file-input-label">Profile Photo</div>
                                        <div class="file-input-description">Click to upload profile photo (JPG, PNG)</div>
                                    </div>
                                </div>
                                @if($user->profile_photo ?? false)
                                    <div class="current-file-info">
                                        <i class="fas fa-file-image me-1"></i>
                                        Current: <a href="{{ asset('storage/' . $user->profile_photo) }}" target="_blank" class="current-file-link">View Current Photo</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Family Member Fields -->
                <div class="dynamic-fields" id="familyMemberFields">
                    <h4>Family Member Information</h4>
                    @if($familyMember)
                        <p class="text-muted mb-3">Update your family member profile information</p>
                        <div class="field-grid">
                            <div class="form-group">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="requested_data[name]" 
                                       value="{{ old('requested_data.name', $familyMember->name ?? '') }}" 
                                       placeholder="Enter name" required>
                                <small class="text-muted">Current: {{ $familyMember->name ?? 'Not set' }}</small>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="requested_data[email]" 
                                       value="{{ old('requested_data.email', $familyMember->email ?? '') }}" 
                                       placeholder="Enter email">
                                <small class="text-muted">Current: {{ $familyMember->email ?? 'Not set' }}</small>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" name="requested_data[phone]" 
                                       value="{{ old('requested_data.phone', $familyMember->phone ?? '') }}" 
                                       placeholder="Enter phone">
                                <small class="text-muted">Current: {{ $familyMember->phone ?? 'Not set' }}</small>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Education</label>
                                <input type="text" class="form-control" name="requested_data[education]" 
                                       value="{{ old('requested_data.education', $familyMember->education ?? '') }}" 
                                       placeholder="Enter education">
                                <small class="text-muted">Current: {{ $familyMember->education ?? 'Not set' }}</small>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Occupation</label>
                                <input type="text" class="form-control" name="requested_data[occupation]" 
                                       value="{{ old('requested_data.occupation', $familyMember->occupation ?? '') }}" 
                                       placeholder="Enter occupation">
                                <small class="text-muted">Current: {{ $familyMember->occupation ?? 'Not set' }}</small>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Monthly Income</label>
                                <input type="number" class="form-control" name="requested_data[monthly_income]" 
                                       value="{{ old('requested_data.monthly_income', $familyMember->monthly_income ?? '') }}" 
                                       placeholder="Enter monthly income" step="0.01">
                                <small class="text-muted">Current: ₹{{ number_format($familyMember->monthly_income ?? 0, 2) }}</small>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Relation to Head</label>
                                <input type="text" class="form-control" name="requested_data[relation_to_head]" 
                                       value="{{ old('requested_data.relation_to_head', $familyMember->relation_to_head ?? '') }}" 
                                       placeholder="Enter relation to head">
                                <small class="text-muted">Current: {{ $familyMember->relation_to_head ?? 'Not set' }}</small>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Marital Status</label>
                                <select class="form-select" name="requested_data[marital_status]">
                                    <option value="">Select Status</option>
                                    <option value="single" {{ old('requested_data.marital_status', $familyMember->marital_status) == 'single' ? 'selected' : '' }}>Single</option>
                                    <option value="married" {{ old('requested_data.marital_status', $familyMember->marital_status) == 'married' ? 'selected' : '' }}>Married</option>
                                    <option value="divorced" {{ old('requested_data.marital_status', $familyMember->marital_status) == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                    <option value="widowed" {{ old('requested_data.marital_status', $familyMember->marital_status) == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                </select>
                                <small class="text-muted">Current: {{ ucfirst($familyMember->marital_status ?? 'Not set') }}</small>
                            </div>
                        </div>

                        <!-- Family Member Document Uploads -->
                        <div class="file-upload-group">
                            <div class="file-upload-title">
                                <i class="fas fa-upload"></i>
                                Upload Family Member Documents (Optional)
                            </div>
                            <div class="document-upload-grid">
                                <div class="file-input-wrapper">
                                    <input type="file" class="file-input" name="family_profile_photo" accept="image/*" onchange="handleFileSelect(this, 'family-profile-photo-display')">
                                    <div class="file-input-display" id="family-profile-photo-display">
                                        <div class="file-input-icon">
                                            <i class="fas fa-camera"></i>
                                        </div>
                                        <div class="file-input-text">
                                            <div class="file-input-label">Profile Photo</div>
                                            <div class="file-input-description">Click to upload profile photo (JPG, PNG)</div>
                                        </div>
                                    </div>
                                    @if($familyMember?->profile_photo)
                                        <div class="current-file-info">
                                            <i class="fas fa-file-image me-1"></i>
                                            Current: <a href="{{ asset('storage/' . $familyMember->profile_photo) }}" target="_blank" class="current-file-link">View Current Photo</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>No Family Member Profile Found</strong><br>
                            You don't have a family member profile linked to your account. Please contact an administrator to set up your family member profile first.
                        </div>
                    @endif
                </div>

                <!-- Document Fields -->
                <div class="dynamic-fields" id="documentsFields">
                    <h4>Document Information</h4>
                    <p class="text-muted mb-3">Update your document numbers and identification details</p>
                    <div class="field-grid">
                        <div class="form-group">
                            <label class="form-label">Aadhar Number</label>
                            <input type="text" class="form-control" name="requested_data[aadhar_number]" 
                                   value="{{ old('requested_data.aadhar_number', $user->aadhar_number ?? '') }}" 
                                   placeholder="Enter Aadhar number" maxlength="12">
                            <small class="text-muted">Current: {{ $user->aadhar_number ?? 'Not set' }}</small>
                        </div>
                        <div class="form-group">
                            <label class="form-label">PAN Number</label>
                            <input type="text" class="form-control" name="requested_data[pan_number]" 
                                   value="{{ old('requested_data.pan_number', $user->pan_number ?? $familyMember?->pan_number ?? '') }}" 
                                   placeholder="Enter PAN number" maxlength="10" style="text-transform: uppercase;">
                            <small class="text-muted">Current: {{ $user->pan_number ?? $familyMember?->pan_number ?? 'Not set' }}</small>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Voter ID</label>
                            <input type="text" class="form-control" name="requested_data[voter_id]" 
                                   value="{{ old('requested_data.voter_id', $user->voter_id ?? $familyMember?->voter_id ?? '') }}" 
                                   placeholder="Enter Voter ID">
                            <small class="text-muted">Current: {{ $user->voter_id ?? $familyMember?->voter_id ?? 'Not set' }}</small>
                        </div>
                    </div>

                    <!-- Document Upload Section -->
                    <div class="file-upload-group">
                        <div class="file-upload-title">
                            <i class="fas fa-upload"></i>
                            Upload Document Files (Optional)
                        </div>
                        <div class="document-upload-grid">
                            <!-- Aadhar Card Upload -->
                            <div class="file-input-wrapper">
                                <input type="file" class="file-input" name="aadhar_photo" accept="image/*,.pdf" onchange="handleFileSelect(this, 'aadhar-photo-display')">
                                <div class="file-input-display" id="aadhar-photo-display">
                                    <div class="file-input-icon">
                                        <i class="fas fa-id-card"></i>
                                    </div>
                                    <div class="file-input-text">
                                        <div class="file-input-label">Aadhar Card</div>
                                        <div class="file-input-description">Upload Aadhar card (JPG, PNG, PDF)</div>
                                    </div>
                                </div>
                                @if($user->aadhar_photo ?? $familyMember?->aadhar_photo ?? false)
                                    <div class="current-file-info">
                                        <i class="fas fa-file-alt me-1"></i>
                                        Current: <a href="{{ asset('storage/' . ($user->aadhar_photo ?? $familyMember->aadhar_photo)) }}" target="_blank" class="current-file-link">View Current Aadhar</a>
                                    </div>
                                @endif
                            </div>

                            <!-- PAN Card Upload -->
                            <div class="file-input-wrapper">
                                <input type="file" class="file-input" name="pan_photo" accept="image/*,.pdf" onchange="handleFileSelect(this, 'pan-photo-display')">
                                <div class="file-input-display" id="pan-photo-display">
                                    <div class="file-input-icon">
                                        <i class="fas fa-credit-card"></i>
                                    </div>
                                    <div class="file-input-text">
                                        <div class="file-input-label">PAN Card</div>
                                        <div class="file-input-description">Upload PAN card (JPG, PNG, PDF)</div>
                                    </div>
                                </div>
                                @if($user->pan_photo ?? $familyMember?->pan_photo ?? false)
                                    <div class="current-file-info">
                                        <i class="fas fa-file-alt me-1"></i>
                                        Current: <a href="{{ asset('storage/' . ($user->pan_photo ?? $familyMember->pan_photo)) }}" target="_blank" class="current-file-link">View Current PAN</a>
                                    </div>
                                @endif
                            </div>

                            <!-- Voter ID Upload -->
                            <div class="file-input-wrapper">
                                <input type="file" class="file-input" name="voter_id_photo" accept="image/*,.pdf" onchange="handleFileSelect(this, 'voter-id-photo-display')">
                                <div class="file-input-display" id="voter-id-photo-display">
                                    <div class="file-input-icon">
                                        <i class="fas fa-vote-yea"></i>
                                    </div>
                                    <div class="file-input-text">
                                        <div class="file-input-label">Voter ID Card</div>
                                        <div class="file-input-description">Upload Voter ID (JPG, PNG, PDF)</div>
                                    </div>
                                </div>
                                @if($user->voter_id_photo ?? $familyMember?->voter_id_photo ?? false)
                                    <div class="current-file-info">
                                        <i class="fas fa-file-alt me-1"></i>
                                        Current: <a href="{{ asset('storage/' . ($user->voter_id_photo ?? $familyMember->voter_id_photo)) }}" target="_blank" class="current-file-link">View Current Voter ID</a>
                                    </div>
                                @endif
                            </div>

                            <!-- Ration Card Upload -->
                            <div class="file-input-wrapper">
                                <input type="file" class="file-input" name="ration_card_photo" accept="image/*,.pdf" onchange="handleFileSelect(this, 'ration-card-photo-display')">
                                <div class="file-input-display" id="ration-card-photo-display">
                                    <div class="file-input-icon">
                                        <i class="fas fa-receipt"></i>
                                    </div>
                                    <div class="file-input-text">
                                        <div class="file-input-label">Ration Card</div>
                                        <div class="file-input-description">Upload Ration card (JPG, PNG, PDF)</div>
                                    </div>
                                </div>
                                @if($user->ration_card_photo ?? $familyMember?->ration_card_photo ?? false)
                                    <div class="current-file-info">
                                        <i class="fas fa-file-alt me-1"></i>
                                        Current: <a href="{{ asset('storage/' . ($user->ration_card_photo ?? $familyMember->ration_card_photo)) }}" target="_blank" class="current-file-link">View Current Ration Card</a>
                                    </div>
                                @endif
                            </div>

                            <!-- Additional Documents Upload -->
                            <div class="file-input-wrapper">
                                <input type="file" class="file-input" name="additional_documents[]" accept="image/*,.pdf" multiple onchange="handleMultipleFileSelect(this, 'additional-docs-display')">
                                <div class="file-input-display" id="additional-docs-display">
                                    <div class="file-input-icon">
                                        <i class="fas fa-folder-plus"></i>
                                    </div>
                                    <div class="file-input-text">
                                        <div class="file-input-label">Additional Documents</div>
                                        <div class="file-input-description">Upload other documents (Multiple files allowed)</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- File Upload Instructions -->
                        <div class="mt-3 p-3 bg-light border rounded">
                            <h6 class="mb-2"><i class="fas fa-info-circle me-1"></i>Upload Guidelines:</h6>
                            <ul class="mb-0 small text-muted">
                                <li>Supported formats: JPG, PNG, PDF</li>
                                <li>Maximum file size: 2MB per file</li>
                                <li>Ensure documents are clear and readable</li>
                                <li>You can upload multiple additional documents</li>
                                <li>All uploads are optional but help speed up verification</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Reason -->
                <div class="form-group">
                    <label class="form-label">Reason for Update <span class="text-danger">*</span></label>
                    <textarea class="form-textarea" name="reason" required 
                              placeholder="Please explain why you need to update this information...">{{ old('reason') }}</textarea>
                    @error('reason')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i>
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Update Request Form JavaScript loaded');
    
    const requestTypeCards = document.querySelectorAll('.request-type-card');
    const requestTypeInput = document.getElementById('requestType');
    const dynamicFields = document.querySelectorAll('.dynamic-fields');

    console.log('Found elements:', {
        cards: requestTypeCards.length,
        input: requestTypeInput ? 'found' : 'missing',
        fields: dynamicFields.length
    });

    requestTypeCards.forEach(card => {
        card.addEventListener('click', function() {
            const type = this.dataset.type;
            console.log('Card clicked:', type);
            
            // Update selection
            requestTypeCards.forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
            
            // Update hidden input
            requestTypeInput.value = type;
            console.log('Hidden input value set to:', type);
            
            // Show/hide fields
            dynamicFields.forEach(field => {
                field.classList.remove('active');
                console.log('Removed active from:', field.id);
            });
            
            const targetField = document.getElementById(type + 'Fields');
            if (targetField) {
                targetField.classList.add('active');
                console.log('Added active to:', targetField.id);
                
                // Force display for debugging
                targetField.style.display = 'block';
            } else {
                console.error('Target field not found:', type + 'Fields');
            }
        });
    });

    // Auto-select first option for testing
    if (requestTypeCards.length > 0) {
        console.log('Auto-selecting first card for testing');
        requestTypeCards[0].click();
    }

    // Fix for duplicate field names - ensure only active section fields are submitted
    document.getElementById('updateRequestForm').addEventListener('submit', function(e) {
        console.log('Form being submitted...');
        
        // Disable all fields in inactive sections to prevent conflicts
        const allFields = document.querySelectorAll('.dynamic-fields input, .dynamic-fields select, .dynamic-fields textarea');
        allFields.forEach(field => {
            const parentSection = field.closest('.dynamic-fields');
            if (parentSection && !parentSection.classList.contains('active')) {
                field.disabled = true;
                console.log('Disabled field in inactive section:', field.name);
            }
        });
        
        // Log all active fields being submitted
        const formData = new FormData(this);
        console.log('Active form data being submitted:');
        for (let [key, value] of formData.entries()) {
            console.log(key + ':', value);
        }
    });

    // Form validation
    document.getElementById('updateRequestForm').addEventListener('submit', function(e) {
        console.log('Form submitted with request type:', requestTypeInput.value);
        
        if (!requestTypeInput.value) {
            e.preventDefault();
            alert('Please select what you would like to update.');
            return false;
        }

        // Log form data for debugging
        const formData = new FormData(this);
        console.log('Form data being submitted:');
        for (let [key, value] of formData.entries()) {
            console.log(key + ':', value);
        }
    });

    // Debug: Show all dynamic fields initially for testing
    console.log('Available dynamic fields:');
    dynamicFields.forEach(field => {
        console.log('Field ID:', field.id, 'Display:', window.getComputedStyle(field).display);
    });
});

// File upload handling functions
function handleFileSelect(input, displayId) {
    const display = document.getElementById(displayId);
    const file = input.files[0];
    
    if (file) {
        console.log('File selected:', file.name, 'Size:', file.size, 'Type:', file.type);
        
        // Validate file size (2MB limit)
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB. Please choose a smaller file.');
            input.value = '';
            return;
        }
        
        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
        if (!allowedTypes.includes(file.type)) {
            alert('Please upload only JPG, PNG, or PDF files.');
            input.value = '';
            return;
        }
        
        // Update display
        display.classList.add('has-file');
        const label = display.querySelector('.file-input-label');
        const description = display.querySelector('.file-input-description');
        
        if (label) label.textContent = file.name;
        if (description) description.textContent = `File selected: ${(file.size / 1024).toFixed(1)} KB`;
        
        // Add remove button if not exists
        let removeBtn = display.querySelector('.file-remove-btn');
        if (!removeBtn) {
            removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'file-remove-btn';
            removeBtn.innerHTML = '<i class="fas fa-times"></i>';
            removeBtn.onclick = () => removeFile(input, displayId);
            display.appendChild(removeBtn);
        }
    }
}

function handleMultipleFileSelect(input, displayId) {
    const display = document.getElementById(displayId);
    const files = input.files;
    
    if (files.length > 0) {
        console.log('Multiple files selected:', files.length);
        
        let totalSize = 0;
        let validFiles = 0;
        
        // Validate all files
        for (let file of files) {
            totalSize += file.size;
            
            // Check individual file size
            if (file.size > 2 * 1024 * 1024) {
                alert(`File "${file.name}" is too large. Maximum size is 2MB per file.`);
                input.value = '';
                return;
            }
            
            // Check file type
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
            if (allowedTypes.includes(file.type)) {
                validFiles++;
            }
        }
        
        if (validFiles !== files.length) {
            alert('Some files have invalid formats. Please upload only JPG, PNG, or PDF files.');
            input.value = '';
            return;
        }
        
        // Update display
        display.classList.add('has-file');
        const label = display.querySelector('.file-input-label');
        const description = display.querySelector('.file-input-description');
        
        if (label) label.textContent = `${files.length} files selected`;
        if (description) description.textContent = `Total size: ${(totalSize / 1024).toFixed(1)} KB`;
        
        // Add remove button if not exists
        let removeBtn = display.querySelector('.file-remove-btn');
        if (!removeBtn) {
            removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'file-remove-btn';
            removeBtn.innerHTML = '<i class="fas fa-times"></i>';
            removeBtn.onclick = () => removeFile(input, displayId);
            display.appendChild(removeBtn);
        }
    }
}

function removeFile(input, displayId) {
    const display = document.getElementById(displayId);
    
    // Clear the input
    input.value = '';
    
    // Reset display
    display.classList.remove('has-file');
    
    // Reset text based on input type
    const label = display.querySelector('.file-input-label');
    const description = display.querySelector('.file-input-description');
    const removeBtn = display.querySelector('.file-remove-btn');
    
    // Get original text from data attributes or default values
    const originalLabel = input.getAttribute('data-original-label') || 'Select File';
    const originalDescription = input.getAttribute('data-original-description') || 'Click to upload file';
    
    if (label) label.textContent = originalLabel;
    if (description) description.textContent = originalDescription;
    if (removeBtn) removeBtn.remove();
    
    console.log('File removed from:', displayId);
}

// Initialize file upload displays with original text
document.addEventListener('DOMContentLoaded', function() {
    // Store original text for file inputs
    document.querySelectorAll('.file-input').forEach(input => {
        const display = input.nextElementSibling;
        if (display && display.classList.contains('file-input-display')) {
            const label = display.querySelector('.file-input-label');
            const description = display.querySelector('.file-input-description');
            
            if (label) input.setAttribute('data-original-label', label.textContent);
            if (description) input.setAttribute('data-original-description', description.textContent);
        }
    });
});
</script>

@endsection