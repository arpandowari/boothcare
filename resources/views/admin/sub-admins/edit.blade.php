@extends('layouts.app')

@section('title', 'Edit Sub-Admin - Admin')
@section('page-title', 'Edit Sub-Admin')

@push('styles')
<style>
    .form-section {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .section-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .permission-group {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .permission-group-title {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .permission-item {
        display: flex;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .permission-item:last-child {
        border-bottom: none;
    }
    
    .permission-checkbox {
        margin-right: 0.75rem;
    }
    
    .permission-label {
        flex-grow: 1;
        font-weight: 500;
        color: #374151;
    }
    
    .select-all-btn {
        background: #f3f4f6;
        border: 1px solid #d1d5db;
        color: #374151;
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .select-all-btn:hover {
        background: #e5e7eb;
    }
    
    .form-help {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .form-help i {
        color: #0284c7;
    }
    
    .current-info {
        background: #fef3c7;
        border: 1px solid #fbbf24;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .current-info i {
        color: #d97706;
    }
    
    .password-strength {
        margin-top: 0.5rem;
    }
    
    .strength-bar {
        height: 4px;
        border-radius: 2px;
        background: #e5e7eb;
        overflow: hidden;
    }
    
    .strength-fill {
        height: 100%;
        transition: all 0.3s ease;
        border-radius: 2px;
    }
    
    .strength-weak { background: #ef4444; width: 25%; }
    .strength-fair { background: #f59e0b; width: 50%; }
    .strength-good { background: #10b981; width: 75%; }
    .strength-strong { background: #059669; width: 100%; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Edit Sub-Admin</h1>
            <p class="text-muted">Update sub-administrator information and permissions</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.sub-admins.show', $subAdmin) }}" class="btn btn-outline-info">
                <i class="fas fa-eye me-2"></i>View Details
            </a>
            <a href="{{ route('admin.sub-admins.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Sub-Admins
            </a>
        </div>
    </div>

    <form action="{{ route('admin.sub-admins.update', $subAdmin) }}" method="POST">
        @csrf
        @method('PUT')
        
        <!-- Current Information -->
        <div class="current-info">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Editing:</strong> {{ $subAdmin->name }} ({{ $subAdmin->email }}) - 
            Status: {{ $subAdmin->is_active ? 'Active' : 'Inactive' }} - 
            Permissions: {{ count($subAdmin->permissions ?? []) }}
        </div>
        
        <!-- Basic Information -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="fas fa-user"></i>
                Basic Information
            </h3>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-user me-2"></i>Full Name
                        </label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $subAdmin->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-envelope me-2"></i>Email Address
                        </label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email', $subAdmin->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-phone me-2"></i>Phone Number
                        </label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                               value="{{ old('phone', $subAdmin->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-calendar me-2"></i>Date of Birth
                        </label>
                        <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" 
                               value="{{ old('date_of_birth', $subAdmin->date_of_birth?->format('Y-m-d')) }}">
                        @error('date_of_birth')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-venus-mars me-2"></i>Gender
                        </label>
                        <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender', $subAdmin->gender) == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $subAdmin->gender) == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', $subAdmin->gender) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <div class="form-check mt-4">
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" 
                                   {{ old('is_active', $subAdmin->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="is_active">
                                <i class="fas fa-toggle-on me-2"></i>Account Active
                            </label>
                            <div class="form-text">Uncheck to deactivate the account</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Password Change -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="fas fa-lock"></i>
                Change Password
            </h3>
            
            <div class="form-help">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Optional:</strong> Leave password fields empty to keep the current password unchanged.
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-key me-2"></i>New Password
                        </label>
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                        <div class="password-strength">
                            <div class="strength-bar">
                                <div class="strength-fill" id="strengthFill"></div>
                            </div>
                            <small class="text-muted" id="strengthText">Enter a password to see strength</small>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-check-double me-2"></i>Confirm New Password
                        </label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <!-- Permissions -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="fas fa-shield-alt"></i>
                Permissions
            </h3>
            
            <div class="form-help">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Important:</strong> Select the permissions this Sub-Admin should have. They will only be able to access features for which they have permissions.
            </div>
            
            @error('permissions')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            
            @foreach($permissionGroups as $groupName => $permissions)
                <div class="permission-group">
                    <div class="permission-group-title">
                        <i class="fas fa-folder-open me-2"></i>
                        {{ $groupName }}
                        <button type="button" class="select-all-btn ms-auto" onclick="toggleGroupPermissions('{{ $groupName }}')">
                            Select All
                        </button>
                    </div>
                    
                    @foreach($permissions as $permission)
                        @if(isset($availablePermissions[$permission]))
                            <div class="permission-item">
                                <input type="checkbox" name="permissions[]" value="{{ $permission }}" 
                                       class="form-check-input permission-checkbox group-{{ str_replace(' ', '-', strtolower($groupName)) }}"
                                       id="permission_{{ $permission }}"
                                       {{ in_array($permission, old('permissions', $subAdmin->permissions ?? [])) ? 'checked' : '' }}>
                                <label class="permission-label" for="permission_{{ $permission }}">
                                    {{ $availablePermissions[$permission] }}
                                </label>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endforeach
        </div>

        <!-- Submit Buttons -->
        <div class="form-section">
            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Update Sub-Admin
                </button>
                <a href="{{ route('admin.sub-admins.show', $subAdmin) }}" class="btn btn-info">
                    <i class="fas fa-eye me-2"></i>View Details
                </a>
                <a href="{{ route('admin.sub-admins.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>Cancel
                </a>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
// Password strength checker
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strengthFill = document.getElementById('strengthFill');
    const strengthText = document.getElementById('strengthText');
    
    if (password === '') {
        strengthFill.className = 'strength-fill';
        strengthText.textContent = 'Enter a password to see strength';
        return;
    }
    
    let strength = 0;
    let text = '';
    let className = '';
    
    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;
    
    switch (strength) {
        case 0:
        case 1:
            text = 'Very Weak';
            className = 'strength-weak';
            break;
        case 2:
            text = 'Weak';
            className = 'strength-weak';
            break;
        case 3:
            text = 'Fair';
            className = 'strength-fair';
            break;
        case 4:
            text = 'Good';
            className = 'strength-good';
            break;
        case 5:
            text = 'Strong';
            className = 'strength-strong';
            break;
    }
    
    strengthFill.className = 'strength-fill ' + className;
    strengthText.textContent = text;
});

// Toggle group permissions
function toggleGroupPermissions(groupName) {
    const groupClass = 'group-' + groupName.replace(/\s+/g, '-').toLowerCase();
    const checkboxes = document.querySelectorAll('.' + groupClass);
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = !allChecked;
    });
    
    // Update button text
    const button = event.target;
    button.textContent = allChecked ? 'Select All' : 'Deselect All';
}

// Update select all buttons when individual checkboxes change
document.addEventListener('change', function(e) {
    if (e.target.type === 'checkbox' && e.target.name === 'permissions[]') {
        updateSelectAllButtons();
    }
});

function updateSelectAllButtons() {
    @foreach($permissionGroups as $groupName => $permissions)
        const group{{ str_replace(' ', '', $groupName) }}Checkboxes = document.querySelectorAll('.group-{{ str_replace(' ', '-', strtolower($groupName)) }}');
        const group{{ str_replace(' ', '', $groupName) }}Button = document.querySelector('[onclick="toggleGroupPermissions(\'{{ $groupName }}\')"]');
        const allChecked{{ str_replace(' ', '', $groupName) }} = Array.from(group{{ str_replace(' ', '', $groupName) }}Checkboxes).every(cb => cb.checked);
        
        if (group{{ str_replace(' ', '', $groupName) }}Button) {
            group{{ str_replace(' ', '', $groupName) }}Button.textContent = allChecked{{ str_replace(' ', '', $groupName) }} ? 'Deselect All' : 'Select All';
        }
    @endforeach
}

// Initialize select all button states
document.addEventListener('DOMContentLoaded', function() {
    updateSelectAllButtons();
});
</script>
@endpush