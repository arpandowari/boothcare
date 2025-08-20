@extends('layouts.app')

@section('title', 'Edit Profile - Boothcare')
@section('page-title', 'Edit Profile')

@push('styles')
<style>
/* Clean Professional Edit Design */
body {
    background: #f5f7fa;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.edit-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
}

/* Edit Header */
.edit-header {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    margin-bottom: 24px;
    padding: 24px 32px;
    display: flex;
    align-items: center;
    gap: 16px;
}

.back-btn {
    background: #e2e8f0;
    color: #4a5568;
    border: none;
    border-radius: 8px;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-size: 1.1rem;
    transition: all 0.2s;
}

.back-btn:hover {
    background: #cbd5e0;
    color: #2d3748;
    transform: translateX(-2px);
}

.edit-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2d3748;
    margin: 0;
}

/* Form Layout */
.form-layout {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 24px;
}

.form-main {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.form-sidebar {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

/* Form Cards */
.form-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    overflow: hidden;
}

.card-header {
    padding: 20px 24px;
    border-bottom: 1px solid #e2e8f0;
    background: #f8f9fa;
}

.card-header h3 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: #2d3748;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.card-body {
    padding: 24px;
}

/* Photo Upload */
.photo-upload {
    text-align: center;
    padding: 32px 24px;
    border: 2px dashed #cbd5e0;
    border-radius: 8px;
    background: #f7fafc;
    margin-bottom: 24px;
    transition: all 0.2s;
}

.photo-upload:hover {
    border-color: #4299e1;
    background: #ebf8ff;
}

.current-photo {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 16px;
    border: 4px solid white;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.photo-placeholder {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    color: white;
    font-size: 2rem;
    border: 4px solid white;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

/* Form Fields */
.form-group {
    margin-bottom: 20px;
}

.form-label {
    font-weight: 600;
    color: #4a5568;
    margin-bottom: 6px;
    font-size: 0.9rem;
    display: block;
}

.form-control, .form-select {
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    padding: 12px 16px;
    font-size: 1rem;
    transition: all 0.2s;
    background: white;
    width: 100%;
    color: #2d3748;
}

.form-control:focus, .form-select:focus {
    border-color: #4299e1;
    box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
    background: white;
    outline: none;
}

.form-control[readonly] {
    background: #f7fafc;
    color: #718096;
    border-color: #e2e8f0;
}

.form-text {
    font-size: 0.85rem;
    color: #718096;
    margin-top: 4px;
}

/* Buttons */
.btn {
    border: none;
    border-radius: 8px;
    padding: 12px 24px;
    font-weight: 600;
    font-size: 0.95rem;
    transition: all 0.2s;
    text-decoration: none;
    display: inline-block;
    text-align: center;
    cursor: pointer;
}

.btn-primary {
    background: #4299e1;
    color: white;
}

.btn-primary:hover {
    background: #3182ce;
    color: white;
    transform: translateY(-1px);
}

.btn-secondary {
    background: #718096;
    color: white;
}

.btn-secondary:hover {
    background: #4a5568;
    color: white;
    transform: translateY(-1px);
}

.btn-warning {
    background: #ed8936;
    color: white;
}

.btn-warning:hover {
    background: #dd6b20;
    color: white;
    transform: translateY(-1px);
}

/* Alert */
.alert-info {
    background: #ebf8ff;
    border: 1px solid #bee3f8;
    border-radius: 8px;
    color: #2b6cb0;
    padding: 16px 20px;
    margin-bottom: 20px;
    font-weight: 500;
}

/* Account Info */
.account-info {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.info-item {
    background: #f7fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.info-label {
    font-weight: 600;
    color: #4a5568;
    font-size: 0.9rem;
}

.info-value {
    font-weight: 600;
    color: #2d3748;
}

.badge {
    padding: 4px 12px;
    border-radius: 16px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.badge-admin {
    background: #fed7d7;
    color: #c53030;
}

.badge-active {
    background: #c6f6d5;
    color: #22543d;
}

.badge-inactive {
    background: #e2e8f0;
    color: #4a5568;
}

/* Responsive */
@media (max-width: 768px) {
    .edit-container {
        padding: 16px;
    }
    
    .form-layout {
        grid-template-columns: 1fr;
    }
    
    .edit-header {
        padding: 20px;
    }
    
    .card-body {
        padding: 20px;
    }
}

/* Additional Utilities */
.d-grid {
    display: grid;
}

.gap-2 {
    gap: 12px;
}

.w-100 {
    width: 100%;
}

.me-2 {
    margin-right: 8px;
}

.text-danger {
    color: #e53e3e;
}

.invalid-feedback {
    color: #e53e3e;
    font-size: 0.85rem;
    margin-top: 4px;
}

/* Bootstrap Grid */
.row {
    display: flex;
    flex-wrap: wrap;
    margin: 0 -12px;
}

.col-md-6 {
    flex: 0 0 50%;
    max-width: 50%;
    padding: 0 12px;
}

.g-3 > * {
    margin-bottom: 16px;
}

@media (max-width: 768px) {
    .col-md-6 {
        flex: 0 0 100%;
        max-width: 100%;
    }
}

/* Additional Flexbox Utilities */
.d-flex {
    display: flex;
}

.justify-content-between {
    justify-content: space-between;
}

.align-items-center {
    align-items: center;
}

.me-1 {
    margin-right: 4px;
}

.mt-2 {
    margin-top: 8px;
}

.mt-3 {
    margin-top: 12px;
}
</style>
@endpush

@section('content')
<div class="edit-container">
    <!-- Edit Header -->
    <div class="edit-header">
        <a href="{{ route('profile.show') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="edit-title">Edit Profile</h1>
    </div>

    <!-- Form Layout -->
    <div class="form-layout">
        <div class="form-main">
            <!-- Profile Form -->
            <div class="form-card">
                <div class="card-header">
                    <h3>Profile Information</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                                
                        @if(Auth::user()->isAdminOrSubAdmin())
                            {{-- Admin/Sub-Admin Profile Form --}}
                            
                            <!-- Photo Upload -->
                            <div class="photo-upload">
                                @if(Auth::user()->profile_photo)
                                    <img src="{{ Auth::user()->profile_photo_url }}" alt="Current Profile Photo" class="current-photo" id="currentPhoto">
                                @else
                                    <div class="photo-placeholder" id="currentPhoto">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                                <div style="margin-bottom: 12px;">
                                    <small style="color: #718096; font-weight: 500;">Current Photo</small>
                                </div>
                                <input type="file" class="form-control @error('profile_photo') is-invalid @enderror" 
                                       id="profile_photo" name="profile_photo" accept="image/*" onchange="previewPhoto(this)">
                                @error('profile_photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Upload a new profile photo (JPEG, PNG, JPG - Max: 2MB)</div>
                                
                                <!-- Photo Preview -->
                                <div id="photoPreview" class="mt-3" style="display: none;">
                                    <img id="previewImage" src="" alt="Preview" style="max-width: 100px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-secondary" onclick="removePreview()" style="padding: 6px 12px; font-size: 0.85rem;">
                                            <i class="fas fa-times me-1"></i>Remove
                                        </button>
                                    </div>
                                </div>
                            </div>

                        <!-- Basic Information -->
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', Auth::user()->phone) }}" 
                                           placeholder="+91 XXXXX XXXXX">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                           id="date_of_birth" name="date_of_birth" 
                                           value="{{ old('date_of_birth', Auth::user()->date_of_birth ? Auth::user()->date_of_birth->format('Y-m-d') : '') }}">
                                    @error('date_of_birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender', Auth::user()->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender', Auth::user()->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender', Auth::user()->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="aadhar_number" class="form-label">Aadhar Number</label>
                                    <input type="text" class="form-control @error('aadhar_number') is-invalid @enderror" 
                                           id="aadhar_number" name="aadhar_number" 
                                           value="{{ old('aadhar_number', Auth::user()->aadhar_number) }}" 
                                           placeholder="XXXX-XXXX-XXXX" maxlength="12"
                                           oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                    @error('aadhar_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        @else
                            {{-- Regular User Profile Form --}}
                            <div class="alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                To edit your detailed profile information, please contact an administrator.
                            </div>
                            
                            <!-- Basic Information Only -->
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" value="{{ Auth::user()->email }}" readonly>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center" style="margin-top: 32px; gap: 16px;">
                            <a href="{{ route('profile.show') }}" class="btn btn-secondary" style="flex: 1;">
                                <i class="fas fa-arrow-left me-1"></i>
                                Cancel
                            </a>
                            
                            @if(Auth::user()->isAdminOrSubAdmin())
                                <button type="submit" class="btn btn-primary" style="flex: 1;">
                                    <i class="fas fa-save me-1"></i>
                                    Update Profile
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="form-sidebar">
        
        <!-- Change Password -->
        @if(Auth::user()->isAdminOrSubAdmin())
        <div class="form-card">
            <div class="card-header">
                <h3>
                    <i class="fas fa-lock me-2"></i>
                    Change Password
                </h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.password.update') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                               id="current_password" name="current_password" required>
                        @error('current_password')
                            <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required>
                        @error('password')
                            <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Password must be at least 8 characters long</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" 
                               id="password_confirmation" name="password_confirmation" required>
                    </div>
                    
                    <button type="submit" class="btn btn-warning w-100">
                        <i class="fas fa-key me-2"></i>
                        Update Password
                    </button>
                </form>
            </div>
        </div>
        @endif
        
        <!-- Account Information -->
        <div class="form-card">
            <div class="card-header">
                <h3>
                    <i class="fas fa-info-circle me-2"></i>
                    Account Information
                </h3>
            </div>
            <div class="card-body">
                <div class="account-info">
                    <div class="info-item">
                        <span class="info-label">Role</span>
                        <span class="badge badge-admin">
                            {{ Auth::user()->isAdmin() ? 'Administrator' : (Auth::user()->isSubAdmin() ? 'Sub Administrator' : 'User') }}
                        </span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">Account Status</span>
                        <span class="badge {{ Auth::user()->is_active ? 'badge-active' : 'badge-inactive' }}">
                            {{ Auth::user()->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">Member Since</span>
                        <span class="info-value">{{ Auth::user()->created_at->format('d M Y') }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">Last Updated</span>
                        <span class="info-value">{{ Auth::user()->updated_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="form-card">
            <div class="card-header">
                <h3>
                    <i class="fas fa-bolt me-2"></i>
                    Quick Actions
                </h3>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                        <i class="fas fa-eye me-2"></i>
                        View Profile
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function previewPhoto(input) {
    const preview = document.getElementById('photoPreview');
    const previewImage = document.getElementById('previewImage');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            preview.style.display = 'block';
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

function removePreview() {
    document.getElementById('profile_photo').value = '';
    document.getElementById('photoPreview').style.display = 'none';
}
</script>
@endpush
@endsection