@extends('layouts.app')

@section('title', 'Create New User - Boothcare')
@section('page-title', 'Create New User')

@section('content')
<div class="container-fluid px-2">
    <!-- Mobile Header -->
    <div class="mobile-header d-block d-md-none" style="background: rgba(255,255,255,0.95); padding: 16px; margin-bottom: 16px; border-radius: 0 0 20px 20px;">
        <h1 style="text-align: center; margin: 0; font-size: 1.4rem; color: #1a1a1a;"><i class="fas fa-user-plus me-2"></i>Create User</h1>
    </div>

    <!-- Desktop Header -->
    <div class="dashboard-header d-none d-md-block">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="dashboard-title">
                    <i class="fas fa-user-plus me-2"></i>
                    Create New User
                </h1>
                <p class="dashboard-subtitle">Add a new user to the system</p>
            </div>
            <div class="col-md-4 text-end text-center-mobile">
                <a href="{{ route('users.index') }}" class="export-btn" style="background: #6c757d;">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Users
                </a>
            </div>
        </div>
    </div>

    <!-- Mobile Back Button -->
    <div class="d-block d-md-none mb-3">
        <a href="{{ route('users.index') }}" style="background: #f0f0f0; border: none; border-radius: 25px; padding: 12px 20px; font-size: 0.9rem; font-weight: 600; color: #666; text-decoration: none; display: block; width: 100%; text-align: center;">
            <i class="fas fa-arrow-left me-2"></i>
            Back to Users
        </a>
    </div>

@push('styles')
<style>
    .form-section {
        border: 1px solid #e2e8f0;
        border-radius: var(--border-radius);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        background: white;
    }

    .form-section h6 {
        color: var(--primary-color);
        border-bottom: 2px solid #f1f5f9;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
    }

    .photo-upload-area {
        border: 2px dashed #d1d5db;
        border-radius: var(--border-radius);
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        background: #f8fafc;
    }

    .photo-upload-area:hover {
        border-color: var(--primary-color);
        background: #f0f9ff;
    }

    .photo-preview {
        margin-top: 1rem;
        display: none;
    }

    .photo-preview img {
        max-width: 150px;
        max-height: 150px;
        border-radius: 8px;
        border: 2px solid #e2e8f0;
    }

    @media (max-width: 768px) {
        .form-section {
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .photo-upload-area {
            padding: 1.5rem;
        }

        .btn {
            width: 100%;
            margin-bottom: 0.5rem;
        }

        .d-flex.justify-content-between {
            flex-direction: column;
        }

        .d-flex.justify-content-between .btn:last-child {
            margin-bottom: 0;
        }
    }
</style>
@endpush

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
        <li class="breadcrumb-item active">Create New</li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-plus me-2"></i>
                    Create New User Account
                </h5>
            </div>
            <div class="card-body p-0">
                <!-- Photo Validation Alert -->
                <div class="alert alert-info m-3 mb-0">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-info-circle fa-lg me-3 mt-1"></i>
                        <div>
                            <h6 class="alert-heading">User Account Creation</h6>
                            <p class="mb-2">Create user accounts for family members who need system access.</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-success">
                                        <i class="fas fa-check-circle me-1"></i> <strong>With Family Link:</strong> Connected to member record
                                    </small>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-info">
                                        <i class="fas fa-info-circle me-1"></i> <strong>Without Link:</strong> Standalone user account
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data" id="userForm">
                    @csrf
                    
                    <!-- Basic Information Section -->
                    <div class="form-section">
                        <h6><i class="fas fa-user me-2"></i>Basic Information</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required
                                       placeholder="Enter full name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required
                                       placeholder="user@example.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                                <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required onchange="togglePermissions()">
                                    <option value="">Select Role</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="sub_admin" {{ old('role') == 'sub_admin' ? 'selected' : '' }}>Sub Admin</option>
                                    <option value="user" {{ old('role', 'user') == 'user' ? 'selected' : '' }}>User</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}"
                                       placeholder="+91 XXXXX XXXXX">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Permissions Section (for Sub Admin) -->
                    <div class="form-section" id="permissionsSection" style="display: none;">
                        <h6><i class="fas fa-shield-alt me-2"></i>Permissions</h6>
                        <p class="text-muted mb-3">Select the permissions for this sub-admin user.</p>
                        
                        @if(isset($permissionGroups) && isset($availablePermissions))
                            @foreach($permissionGroups as $groupName => $groupPermissions)
                                <div class="permission-group mb-4">
                                    <div class="d-flex align-items-center mb-2">
                                        <h6 class="mb-0">{{ $groupName }}</h6>
                                        <button type="button" class="btn btn-sm btn-outline-primary ms-2" onclick="toggleGroupPermissions('{{ $groupName }}')">
                                            <i class="fas fa-check-double me-1"></i>Select All
                                        </button>
                                    </div>
                                    <div class="row">
                                        @foreach($groupPermissions as $permission)
                                            @if(isset($availablePermissions[$permission]))
                                                <div class="col-md-6 col-lg-4 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input permission-checkbox" 
                                                               type="checkbox" 
                                                               name="permissions[]" 
                                                               value="{{ $permission }}" 
                                                               id="perm_{{ str_replace('.', '_', $permission) }}"
                                                               data-group="{{ $groupName }}"
                                                               {{ in_array($permission, old('permissions', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="perm_{{ str_replace('.', '_', $permission) }}">
                                                            {{ $availablePermissions[$permission] }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    
                    <!-- Profile Photo Section -->
                    <div class="form-section">
                        <h6><i class="fas fa-camera me-2"></i>Profile Photo</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="photo-upload-area" onclick="document.getElementById('profile_photo').click()">
                                    <i class="fas fa-camera fa-2x text-primary mb-2"></i>
                                    <p class="mb-1"><strong>Click to upload profile photo</strong></p>
                                    <small class="text-muted">JPG, PNG (Max: 2MB)</small>
                                    <input type="file" id="profile_photo" name="profile_photo" accept="image/*" style="display: none;">
                                </div>
                                @error('profile_photo')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <div class="photo-preview" id="photoPreview">
                                    <img src="" alt="Profile Preview" class="img-fluid">
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removePhoto()">
                                            <i class="fas fa-trash me-1"></i>Remove Photo
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Password Section -->
                    <div class="form-section">
                        <h6><i class="fas fa-lock me-2"></i>Password & Security</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" required placeholder="Enter password">
                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password')">
                                        <i class="fas fa-eye" id="passwordToggle"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Minimum 8 characters with letters and numbers</div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" required
                                           placeholder="Confirm password">
                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password_confirmation')">
                                        <i class="fas fa-eye" id="passwordConfirmToggle"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Family Member Association -->
                    <div class="form-section">
                        <h6><i class="fas fa-users me-2"></i>Family Member Association (Optional)</h6>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Link this user account to an existing family member record for better integration.
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="area_id" class="form-label">Area</label>
                                <select class="form-select @error('area_id') is-invalid @enderror" id="area_id" name="area_id">
                                    <option value="">Select Area</option>
                                    @foreach($areas as $area)
                                        <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>
                                            {{ $area->area_name }} ({{ $area->district }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('area_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4">
                                <label for="booth_id" class="form-label">Booth</label>
                                <select class="form-select @error('booth_id') is-invalid @enderror" id="booth_id" name="booth_id">
                                    <option value="">Select Booth</option>
                                    @foreach($booths as $booth)
                                        <option value="{{ $booth->id }}" {{ old('booth_id') == $booth->id ? 'selected' : '' }}>
                                            Booth {{ $booth->booth_number }} - {{ $booth->booth_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('booth_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4">
                                <label for="house_id" class="form-label">House</label>
                                <select class="form-select @error('house_id') is-invalid @enderror" id="house_id" name="house_id">
                                    <option value="">Select House</option>
                                    @foreach($houses as $house)
                                        <option value="{{ $house->id }}" {{ old('house_id') == $house->id ? 'selected' : '' }}>
                                            {{ $house->house_number }} - {{ Str::limit($house->address, 30) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('house_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row g-3 mt-2">
                            <div class="col-12">
                                <label for="family_member_id" class="form-label">Family Member</label>
                                <select class="form-select @error('family_member_id') is-invalid @enderror" id="family_member_id" name="family_member_id">
                                    <option value="">Select Family Member</option>
                                    @foreach($members as $member)
                                        <option value="{{ $member->id }}" {{ old('family_member_id') == $member->id ? 'selected' : '' }}>
                                            {{ $member->name }} ({{ $member->relation_to_head }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('family_member_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Link this user account to a family member record</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Account Settings -->
                    <div class="form-section">
                        <h6><i class="fas fa-cog me-2"></i>Account Settings</h6>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        <strong>Active Account</strong>
                                        <div class="form-text">User can login and access the system</div>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="send_welcome_email" name="send_welcome_email" value="1" 
                                           {{ old('send_welcome_email', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="send_welcome_email">
                                        <strong>Send Welcome Email</strong>
                                        <div class="form-text">Send login credentials via email</div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="form-section">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Cancel
                            </a>
                            <div>
                                <button type="button" class="btn btn-outline-primary me-2" onclick="validateForm()">
                                    <i class="fas fa-check me-1"></i> Validate
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Create User
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Photo upload handling
document.getElementById('profile_photo').addEventListener('change', function() {
    const file = this.files[0];
    const preview = document.getElementById('photoPreview');
    const img = preview.querySelector('img');
    
    if (file) {
        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!allowedTypes.includes(file.type)) {
            alert('Please select a valid image file (JPEG, PNG, JPG)');
            this.value = '';
            preview.style.display = 'none';
            return;
        }
        
        // Validate file size (2MB max)
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB');
            this.value = '';
            preview.style.display = 'none';
            return;
        }
        
        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
});

function removePhoto() {
    document.getElementById('profile_photo').value = '';
    document.getElementById('photoPreview').style.display = 'none';
}

// Password visibility toggle
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const toggle = document.getElementById(fieldId + 'Toggle');
    
    if (field.type === 'password') {
        field.type = 'text';
        toggle.classList.remove('fa-eye');
        toggle.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        toggle.classList.remove('fa-eye-slash');
        toggle.classList.add('fa-eye');
    }
}

// Form validation
function validateForm() {
    let isValid = true;
    const errors = [];
    
    // Clear previous errors
    document.querySelectorAll('.is-invalid').forEach(el => {
        el.classList.remove('is-invalid');
    });
    document.querySelectorAll('.invalid-feedback').forEach(el => {
        el.remove();
    });
    
    // Validate required fields
    const requiredFields = [
        { id: 'name', message: 'Name is required' },
        { id: 'email', message: 'Email is required' },
        { id: 'role', message: 'Role is required' },
        { id: 'password', message: 'Password is required' },
        { id: 'password_confirmation', message: 'Password confirmation is required' }
    ];
    
    requiredFields.forEach(field => {
        const element = document.getElementById(field.id);
        if (!element.value.trim()) {
            showFieldError(element, field.message);
            errors.push(field.message);
            isValid = false;
        }
    });
    
    // Validate email format
    const email = document.getElementById('email');
    if (email.value && !isValidEmail(email.value)) {
        showFieldError(email, 'Please enter a valid email address');
        errors.push('Invalid email format');
        isValid = false;
    }
    
    // Validate password strength
    const password = document.getElementById('password');
    if (password.value && !isValidPassword(password.value)) {
        showFieldError(password, 'Password must be at least 8 characters with letters and numbers');
        errors.push('Password too weak');
        isValid = false;
    }
    
    // Validate password confirmation
    const passwordConfirm = document.getElementById('password_confirmation');
    if (password.value !== passwordConfirm.value) {
        showFieldError(passwordConfirm, 'Passwords do not match');
        errors.push('Passwords do not match');
        isValid = false;
    }
    
    // Validate phone format if provided
    const phone = document.getElementById('phone');
    if (phone.value && !isValidPhone(phone.value)) {
        showFieldError(phone, 'Please enter a valid phone number');
        errors.push('Invalid phone format');
        isValid = false;
    }
    
    // Show validation results
    if (isValid) {
        alert('✅ Form validation passed! Ready to submit.');
    } else {
        alert('❌ Form validation failed:\n\n' + errors.join('\n'));
        // Scroll to first error
        const firstError = document.querySelector('.is-invalid');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstError.focus();
        }
    }
    
    return isValid;
}

function showFieldError(field, message) {
    field.classList.add('is-invalid');
    const errorDiv = document.createElement('div');
    errorDiv.className = 'invalid-feedback';
    errorDiv.textContent = message;
    field.parentElement.appendChild(errorDiv);
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function isValidPassword(password) {
    // At least 8 characters, contains letters and numbers
    const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*#?&]{8,}$/;
    return passwordRegex.test(password);
}

function isValidPhone(phone) {
    const phoneRegex = /^[\+]?[0-9\s\-\(\)]{10,15}$/;
    return phoneRegex.test(phone);
}

// Dynamic dropdown loading
document.getElementById('area_id').addEventListener('change', function() {
    const areaId = this.value;
    const boothSelect = document.getElementById('booth_id');
    const houseSelect = document.getElementById('house_id');
    const memberSelect = document.getElementById('family_member_id');
    
    // Clear dependent dropdowns
    boothSelect.innerHTML = '<option value="">Select Booth</option>';
    houseSelect.innerHTML = '<option value="">Select House</option>';
    memberSelect.innerHTML = '<option value="">Select Family Member</option>';
    
    if (areaId) {
        fetch(`/api/areas/${areaId}/booths`)
            .then(response => response.json())
            .then(booths => {
                booths.forEach(booth => {
                    const option = document.createElement('option');
                    option.value = booth.id;
                    option.textContent = `Booth ${booth.booth_number} - ${booth.booth_name}`;
                    boothSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching booths:', error));
    }
});

document.getElementById('booth_id').addEventListener('change', function() {
    const boothId = this.value;
    const houseSelect = document.getElementById('house_id');
    const memberSelect = document.getElementById('family_member_id');
    
    // Clear dependent dropdowns
    houseSelect.innerHTML = '<option value="">Select House</option>';
    memberSelect.innerHTML = '<option value="">Select Family Member</option>';
    
    if (boothId) {
        fetch(`/api/booths/${boothId}/houses`)
            .then(response => response.json())
            .then(houses => {
                houses.forEach(house => {
                    const option = document.createElement('option');
                    option.value = house.id;
                    option.textContent = `${house.house_number} - ${house.address.substring(0, 30)}...`;
                    houseSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching houses:', error));
    }
});

document.getElementById('house_id').addEventListener('change', function() {
    const houseId = this.value;
    const memberSelect = document.getElementById('family_member_id');
    
    // Clear member dropdown
    memberSelect.innerHTML = '<option value="">Select Family Member</option>';
    
    if (houseId) {
        fetch(`/api/houses/${houseId}/members`)
            .then(response => response.json())
            .then(members => {
                members.forEach(member => {
                    const option = document.createElement('option');
                    option.value = member.id;
                    option.textContent = `${member.name} (${member.relation_to_head})`;
                    memberSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching members:', error));
    }
});

// Auto-generate email from name
document.getElementById('name').addEventListener('blur', function() {
    const name = this.value.toLowerCase().replace(/\s+/g, '.');
    const emailField = document.getElementById('email');
    
    if (name && !emailField.value) {
        emailField.value = name + '@boothcare.local';
    }
});

// Real-time password validation
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strengthIndicator = document.getElementById('passwordStrength');
    
    if (!strengthIndicator) {
        const indicator = document.createElement('div');
        indicator.id = 'passwordStrength';
        indicator.className = 'form-text';
        this.parentElement.appendChild(indicator);
    }
    
    const strength = getPasswordStrength(password);
    const indicator = document.getElementById('passwordStrength');
    
    indicator.innerHTML = `
        <div class="d-flex align-items-center">
            <div class="progress flex-grow-1 me-2" style="height: 4px;">
                <div class="progress-bar bg-${strength.color}" style="width: ${strength.percentage}%"></div>
            </div>
            <small class="text-${strength.color}">${strength.text}</small>
        </div>
    `;
});

function getPasswordStrength(password) {
    if (password.length === 0) {
        return { percentage: 0, color: 'secondary', text: '' };
    } else if (password.length < 6) {
        return { percentage: 25, color: 'danger', text: 'Weak' };
    } else if (password.length < 8 || !/(?=.*[A-Za-z])(?=.*\d)/.test(password)) {
        return { percentage: 50, color: 'warning', text: 'Fair' };
    } else if (!/(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])/.test(password)) {
        return { percentage: 75, color: 'info', text: 'Good' };
    } else {
        return { percentage: 100, color: 'success', text: 'Strong' };
    }
}

// Permission management functions
function togglePermissions() {
    const roleSelect = document.getElementById('role');
    const permissionsSection = document.getElementById('permissionsSection');
    
    if (roleSelect.value === 'sub_admin') {
        permissionsSection.style.display = 'block';
    } else {
        permissionsSection.style.display = 'none';
        // Uncheck all permissions when hiding
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });
    }
}

function toggleGroupPermissions(groupName) {
    const groupCheckboxes = document.querySelectorAll(`input[data-group="${groupName}"]`);
    const allChecked = Array.from(groupCheckboxes).every(cb => cb.checked);
    
    groupCheckboxes.forEach(checkbox => {
        checkbox.checked = !allChecked;
    });
}

// Form submission validation
document.getElementById('userForm').addEventListener('submit', function(e) {
    if (!validateForm()) {
        e.preventDefault();
        return false;
    }
});

// Initialize permissions visibility on page load
document.addEventListener('DOMContentLoaded', function() {
    togglePermissions();
});
</script>
@endpush
@endsection