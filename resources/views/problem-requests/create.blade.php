@extends('layouts.app')

@section('title', 'Report Problem')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                        Report a Problem
                    </h5>
                    <p class="text-muted mb-0 mt-2">
                        Submit a problem request. An administrator will review and create the problem record.
                    </p>
                </div>
                <div class="card-body">
                    <!-- Auto-selected Information -->
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i>Reporting For</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Name:</strong><br>
                                {{ $familyMember->name }}
                            </div>
                            <div class="col-md-3">
                                <strong>House:</strong><br>
                                {{ $familyMember->house->house_number ?? 'Not set' }}
                            </div>
                            <div class="col-md-3">
                                <strong>Area:</strong><br>
                                {{ $familyMember->house->booth->area->area_name ?? 'Not set' }}
                            </div>
                            <div class="col-md-3">
                                <strong>Booth:</strong><br>
                                {{ $familyMember->house->booth ? 'Booth ' . $familyMember->house->booth->booth_number : 'Not set' }}
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('problem-requests.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Problem Details -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Problem Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" required 
                                   placeholder="Brief title for the problem">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="category" class="form-label">Problem Category <span class="text-danger">*</span></label>
                            <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                <option value="">Select Category</option>
                                <option value="health" {{ old('category') == 'health' ? 'selected' : '' }}>🏥 Health</option>
                                <option value="education" {{ old('category') == 'education' ? 'selected' : '' }}>🎓 Education</option>
                                <option value="employment" {{ old('category') == 'employment' ? 'selected' : '' }}>💼 Employment</option>
                                <option value="housing" {{ old('category') == 'housing' ? 'selected' : '' }}>🏠 Housing</option>
                                <option value="infrastructure" {{ old('category') == 'infrastructure' ? 'selected' : '' }}>🚧 Infrastructure</option>
                                <option value="legal" {{ old('category') == 'legal' ? 'selected' : '' }}>⚖️ Legal</option>
                                <option value="financial" {{ old('category') == 'financial' ? 'selected' : '' }}>💰 Financial</option>
                                <option value="social" {{ old('category') == 'social' ? 'selected' : '' }}>👥 Social</option>
                                <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>📝 Other</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Category-specific fields (same as in problems/create.blade.php) -->
                        <div id="category-specific-fields">
                            <!-- Health Category Fields -->
                            <div id="health-fields" class="category-fields" style="display: none;">
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-heartbeat me-2"></i>Health Problem Details</h6>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="health_type" class="form-label">Health Issue Type</label>
                                            <select class="form-select" id="health_type" name="health_type">
                                                <option value="">Select Type</option>
                                                <option value="medical_treatment">Medical Treatment</option>
                                                <option value="medicine_access">Medicine Access</option>
                                                <option value="hospital_referral">Hospital Referral</option>
                                                <option value="emergency">Emergency</option>
                                                <option value="chronic_condition">Chronic Condition</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="urgency_level" class="form-label">Urgency Level</label>
                                            <select class="form-select" id="urgency_level" name="urgency_level">
                                                <option value="">Select Urgency</option>
                                                <option value="immediate">Immediate</option>
                                                <option value="within_week">Within a Week</option>
                                                <option value="routine">Routine</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Education Category Fields -->
                            <div id="education-fields" class="category-fields" style="display: none;">
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-graduation-cap me-2"></i>Education Problem Details</h6>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="education_level" class="form-label">Education Level</label>
                                            <select class="form-select" id="education_level" name="education_level">
                                                <option value="">Select Level</option>
                                                <option value="primary">Primary School</option>
                                                <option value="secondary">Secondary School</option>
                                                <option value="higher_secondary">Higher Secondary</option>
                                                <option value="college">College</option>
                                                <option value="university">University</option>
                                                <option value="vocational">Vocational Training</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="education_issue" class="form-label">Issue Type</label>
                                            <select class="form-select" id="education_issue" name="education_issue">
                                                <option value="">Select Issue</option>
                                                <option value="admission">Admission Problem</option>
                                                <option value="fees">Fee Payment</option>
                                                <option value="scholarship">Scholarship</option>
                                                <option value="documents">Document Issues</option>
                                                <option value="facilities">School Facilities</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Employment Category Fields -->
                            <div id="employment-fields" class="category-fields" style="display: none;">
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-briefcase me-2"></i>Employment Problem Details</h6>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="employment_type" class="form-label">Employment Issue</label>
                                            <select class="form-select" id="employment_type" name="employment_type">
                                                <option value="">Select Issue</option>
                                                <option value="job_search">Job Search</option>
                                                <option value="skill_training">Skill Training</option>
                                                <option value="workplace_issue">Workplace Issue</option>
                                                <option value="salary_dispute">Salary Dispute</option>
                                                <option value="business_support">Business Support</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="experience_level" class="form-label">Experience Level</label>
                                            <select class="form-select" id="experience_level" name="experience_level">
                                                <option value="">Select Experience</option>
                                                <option value="fresher">Fresher</option>
                                                <option value="1-3_years">1-3 Years</option>
                                                <option value="3-5_years">3-5 Years</option>
                                                <option value="5+_years">5+ Years</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Housing Category Fields -->
                            <div id="housing-fields" class="category-fields" style="display: none;">
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-home me-2"></i>Housing Problem Details</h6>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="housing_issue" class="form-label">Housing Issue</label>
                                            <select class="form-select" id="housing_issue" name="housing_issue">
                                                <option value="">Select Issue</option>
                                                <option value="repair">House Repair</option>
                                                <option value="construction">New Construction</option>
                                                <option value="rent_dispute">Rent Dispute</option>
                                                <option value="utilities">Utilities (Water/Electric)</option>
                                                <option value="documentation">Property Documents</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="housing_urgency" class="form-label">Urgency</label>
                                            <select class="form-select" id="housing_urgency" name="housing_urgency">
                                                <option value="">Select Urgency</option>
                                                <option value="emergency">Emergency</option>
                                                <option value="urgent">Urgent</option>
                                                <option value="planned">Planned</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Financial Category Fields -->
                            <div id="financial-fields" class="category-fields" style="display: none;">
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-money-bill me-2"></i>Financial Problem Details</h6>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="financial_issue" class="form-label">Financial Issue</label>
                                            <select class="form-select" id="financial_issue" name="financial_issue">
                                                <option value="">Select Issue</option>
                                                <option value="loan">Loan Application</option>
                                                <option value="subsidy">Government Subsidy</option>
                                                <option value="pension">Pension Issues</option>
                                                <option value="insurance">Insurance Claims</option>
                                                <option value="banking">Banking Problems</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="amount_involved" class="form-label">Amount Involved</label>
                                            <input type="number" class="form-control" id="amount_involved" name="amount_involved" 
                                                   placeholder="Enter amount" min="0" step="0.01">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Problem Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" required 
                                      placeholder="Please describe the problem in detail...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="problem_photo" class="form-label">Problem Photo (Optional)</label>
                            <input type="file" class="form-control @error('problem_photo') is-invalid @enderror" 
                                   id="problem_photo" name="problem_photo" accept="image/*">
                            <div class="form-text">Upload a photo to help explain the problem (JPG, PNG only, max 2MB)</div>
                            @error('problem_photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="priority_request" class="form-label">Priority Request (Optional)</label>
                            <select class="form-select @error('priority_request') is-invalid @enderror" id="priority_request" name="priority_request">
                                <option value="">Let administrator decide</option>
                                <option value="low" {{ old('priority_request') == 'low' ? 'selected' : '' }}>🟢 Low</option>
                                <option value="medium" {{ old('priority_request') == 'medium' ? 'selected' : '' }}>🟡 Medium</option>
                                <option value="high" {{ old('priority_request') == 'high' ? 'selected' : '' }}>🟠 High</option>
                                <option value="urgent" {{ old('priority_request') == 'urgent' ? 'selected' : '' }}>🔴 Urgent</option>
                            </select>
                            <div class="form-text">Suggest a priority level - final priority will be set by administrator</div>
                            @error('priority_request')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Note:</strong> This is a problem request. An administrator will review your submission and create the official problem record if approved.
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-paper-plane me-2"></i>Submit Problem Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.category-fields {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    transition: all 0.3s ease;
}

.category-fields .alert {
    margin-bottom: 15px;
    border: none;
    font-weight: 500;
}

.category-fields .form-label {
    font-weight: 600;
    color: #495057;
}

#category-specific-fields {
    margin: 20px 0;
}

/* Category-specific styling */
#health-fields { border-left: 4px solid #dc3545; }
#education-fields { border-left: 4px solid #007bff; }
#employment-fields { border-left: 4px solid #28a745; }
#housing-fields { border-left: 4px solid #ffc107; }
#financial-fields { border-left: 4px solid #6f42c1; }
#infrastructure-fields { border-left: 4px solid #fd7e14; }
#legal-fields { border-left: 4px solid #20c997; }
#social-fields { border-left: 4px solid #e83e8c; }
</style>
@endpush

@push('scripts')
<script>
// Category-specific fields handling
document.getElementById('category').addEventListener('change', function() {
    const category = this.value;
    
    // Hide all category-specific fields
    const categoryFields = document.querySelectorAll('.category-fields');
    categoryFields.forEach(field => {
        field.style.display = 'none';
    });
    
    // Show relevant category fields
    if (category) {
        const targetFields = document.getElementById(category + '-fields');
        if (targetFields) {
            targetFields.style.display = 'block';
        }
    }
});

// Trigger category change on page load if category is already selected
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category');
    if (categorySelect.value) {
        categorySelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush
@endsection