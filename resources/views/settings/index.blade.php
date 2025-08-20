@extends('layouts.app')

@section('title', 'Settings - Boothcare')
@section('page-title', 'System Settings')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-0">
            <i class="fas fa-cog me-2"></i>
            System Settings
        </h4>
    </div>
</div>



<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2"></i>
                    Application Settings
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('settings.update') }}" method="POST">
                    @csrf
                    
                    <!-- Application Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2">Application Information</h6>
                        </div>
                        
                        <div class="col-md-12 mb-3">
                            <label for="app_description" class="form-label">Application Description</label>
                            <textarea class="form-control @error('app_description') is-invalid @enderror" 
                                      id="app_description" name="app_description" rows="3" 
                                      placeholder="Brief description of the application">{{ old('app_description', $settings['app_description']) }}</textarea>
                            @error('app_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2">Contact Information</h6>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="contact_email" class="form-label">Contact Email</label>
                            <input type="email" class="form-control @error('contact_email') is-invalid @enderror" 
                                   id="contact_email" name="contact_email" 
                                   value="{{ old('contact_email', $settings['contact_email']) }}"
                                   placeholder="admin@example.com">
                            @error('contact_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="contact_phone" class="form-label">Contact Phone</label>
                            <input type="text" class="form-control @error('contact_phone') is-invalid @enderror" 
                                   id="contact_phone" name="contact_phone" 
                                   value="{{ old('contact_phone', $settings['contact_phone']) }}"
                                   placeholder="+91-9876543210">
                            @error('contact_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="office_address" class="form-label">Office Address</label>
                            <textarea class="form-control @error('office_address') is-invalid @enderror" 
                                      id="office_address" name="office_address" rows="2" 
                                      placeholder="Complete office address">{{ old('office_address', $settings['office_address']) }}</textarea>
                            @error('office_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- System Configuration -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2">System Configuration</h6>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="max_file_size" class="form-label">Maximum File Size (KB)</label>
                            <input type="number" class="form-control @error('max_file_size') is-invalid @enderror" 
                                   id="max_file_size" name="max_file_size" 
                                   value="{{ old('max_file_size', $settings['max_file_size']) }}"
                                   min="512" max="10240">
                            @error('max_file_size')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Allowed range: 512KB - 10MB</small>
                        </div>
                    </div>

                    <!-- Feature Toggles -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2">Feature Settings</h6>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="enable_notifications" 
                                       name="enable_notifications" value="1"
                                       {{ old('enable_notifications', $settings['enable_notifications']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="enable_notifications">
                                    Enable Notifications
                                </label>
                            </div>
                            <small class="text-muted">Enable system-wide notifications</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="enable_problem_tracking" 
                                       name="enable_problem_tracking" value="1"
                                       {{ old('enable_problem_tracking', $settings['enable_problem_tracking']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="enable_problem_tracking">
                                    Enable Problem Tracking
                                </label>
                            </div>
                            <small class="text-muted">Allow users to report and track problems</small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Back to Dashboard
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Current Configuration
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Application Name:</strong>
                    <p class="text-muted mb-0">{{ $settings['app_name'] }}</p>
                </div>

                <div class="mb-3">
                    <strong>Current Version:</strong>
                    <p class="text-muted mb-0">1.0.0</p>
                </div>

                <div class="mb-3">
                    <strong>Environment:</strong>
                    <p class="text-muted mb-0">{{ config('app.env') }}</p>
                </div>

                <div class="mb-3">
                    <strong>Debug Mode:</strong>
                    <p class="text-muted mb-0">{{ config('app.debug') ? 'Enabled' : 'Disabled' }}</p>
                </div>

                <div class="mb-3">
                    <strong>Database:</strong>
                    <p class="text-muted mb-0">{{ config('database.default') }}</p>
                </div>
            </div>
        </div>

        <!-- Email Configuration Test -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-envelope me-2"></i>
                    Email Configuration Test
                </h6>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Test your email configuration by sending a test email.</p>
                
                <form action="{{ route('settings.test-email') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="test_email" class="form-label">Test Email Address</label>
                        <input type="email" class="form-control @error('test_email') is-invalid @enderror" 
                               id="test_email" name="test_email" 
                               value="{{ old('test_email', config('mail.from.address')) }}"
                               placeholder="test@example.com" required>
                        @error('test_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-info btn-sm w-100">
                        <i class="fas fa-paper-plane me-2"></i>
                        Send Test Email
                    </button>
                </form>

                <div class="mt-3">
                    <small class="text-muted">
                        <strong>Current Email Settings:</strong><br>
                        <i class="fas fa-server me-1"></i> Host: {{ config('mail.mailers.smtp.host') }}<br>
                        <i class="fas fa-envelope me-1"></i> From: {{ config('mail.from.address') }}
                    </small>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-shield-alt me-2"></i>
                    Security Notes
                </h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled text-muted">
                    <li><i class="fas fa-check text-success me-2"></i>Only administrators can modify settings</li>
                    <li><i class="fas fa-check text-success me-2"></i>All changes are logged for audit</li>
                    <li><i class="fas fa-check text-success me-2"></i>Settings are cached for performance</li>
                    <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>Changes take effect immediately</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection