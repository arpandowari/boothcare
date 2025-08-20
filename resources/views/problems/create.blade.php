@extends('layouts.app')

@section('title', 'Report New Problem - Boothcare')
@section('page-title', 'Report New Problem')

@push('styles')
<style>
    /* Professional Problem Create Page */
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --danger-gradient: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
        --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
    .create-header {
        background: var(--danger-gradient);
        color: white;
        padding: 2rem 0;
        margin: -1rem -1rem 2rem -1rem;
        border-radius: 0 0 var(--border-radius) var(--border-radius);
        box-shadow: var(--shadow-soft);
        position: relative;
        overflow: hidden;
    }

    .create-header::before {
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

    .create-title {
        font-size: 2.5rem;
        font-weight: 900;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .create-subtitle {
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
        background: var(--danger-gradient);
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
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
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

    /* Category Fields */
    .category-fields {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border: 2px solid #e5e7eb;
        border-radius: var(--border-radius-sm);
        padding: 1.5rem;
        margin: 1.5rem 0;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .category-fields::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
    }

    .category-fields.health::before { background: var(--danger-gradient); }
    .category-fields.education::before { background: var(--primary-gradient); }
    .category-fields.employment::before { background: var(--success-gradient); }
    .category-fields.housing::before { background: var(--warning-gradient); }
    .category-fields.financial::before { background: linear-gradient(135deg, #6f42c1 0%, #9f7aea 100%); }

    .category-fields:hover {
        border-color: #667eea;
        background: linear-gradient(135deg, #f0f4ff 0%, #e0e7ff 100%);
        transform: translateY(-2px);
        box-shadow: var(--shadow-soft);
    }

    .category-alert {
        background: rgba(255, 255, 255, 0.8);
        border: none;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .category-alert h6 {
        margin: 0;
        font-weight: 700;
        color: #2d3748;
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
        background: var(--danger-gradient);
        color: white;
        box-shadow: 0 4px 15px rgba(255, 107, 107, 0.4);
    }

    .btn-primary-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 107, 107, 0.6);
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
        .create-title {
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
    <div class="create-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="create-title">
                        <i class="fas fa-exclamation-triangle me-3"></i>
                        Report New Problem
                    </h1>
                    <p class="create-subtitle">
                        Help us understand and resolve issues in your community
                    </p>
                </div>
                <div class="col-lg-4 text-end">
                    <a href="{{ route('problems.index') }}" class="btn btn-light btn-lg">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back to Problems
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
            <li class="breadcrumb-item active">Report New</li>
        </ol>
    </nav>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-plus me-2"></i>
                    Report New Problem
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('problems.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Member Selection - Only for Admins -->
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i>Problem Reporting</h6>
                        <p class="mb-0">As an administrator, you can report problems on behalf of any family member in the system.</p>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="area_id" class="form-label">Area <span class="text-danger">*</span></label>
                                <select class="form-select @error('area_id') is-invalid @enderror" id="area_id" name="area_id" required>
                                    <option value="">Select Area</option>
                                    @foreach($areas as $area)
                                        <option value="{{ $area->id }}" {{ old('area_id', request('area_id')) == $area->id ? 'selected' : '' }}>
                                            {{ $area->area_name }} ({{ $area->district }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('area_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="booth_id" class="form-label">Booth <span class="text-danger">*</span></label>
                                <select class="form-select @error('booth_id') is-invalid @enderror" id="booth_id" name="booth_id" required>
                                    <option value="">Select Booth</option>
                                    @foreach($booths as $booth)
                                        <option value="{{ $booth->id }}" {{ old('booth_id', request('booth_id')) == $booth->id ? 'selected' : '' }}>
                                            Booth {{ $booth->booth_number }} - {{ $booth->booth_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('booth_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="house_id" class="form-label">House <span class="text-danger">*</span></label>
                                <select class="form-select @error('house_id') is-invalid @enderror" id="house_id" name="house_id" required>
                                    <option value="">Select House</option>
                                    @foreach($houses as $house)
                                        <option value="{{ $house->id }}" {{ old('house_id', request('house_id')) == $house->id ? 'selected' : '' }}>
                                            {{ $house->house_number }} - {{ Str::limit($house->address, 30) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('house_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="family_member_id" class="form-label">Family Member <span class="text-danger">*</span></label>
                        <select class="form-select @error('family_member_id') is-invalid @enderror" id="family_member_id" name="family_member_id" required>
                            <option value="">Select Family Member</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}" {{ old('family_member_id', request('family_member_id')) == $member->id ? 'selected' : '' }}>
                                    {{ $member->name }} ({{ $member->relation_to_head }}) - {{ $member->house->house_number ?? 'No House' }}
                                </option>
                            @endforeach
                        </select>
                        @error('family_member_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Select the family member who has this problem</div>
                    </div>
                    
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
                    
                    <!-- Category-specific fields -->
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
                    
                    <!-- Admin can set priority and estimated cost -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                                <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                                    <option value="">Select Priority</option>
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>🟢 Low</option>
                                    <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>🟡 Medium</option>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>🟠 High</option>
                                    <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>🔴 Urgent</option>
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="estimated_cost" class="form-label">Estimated Cost (Optional)</label>
                                <input type="number" class="form-control @error('estimated_cost') is-invalid @enderror" 
                                       id="estimated_cost" name="estimated_cost" value="{{ old('estimated_cost') }}" 
                                       min="0" step="0.01" placeholder="0.00">
                                @error('estimated_cost')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="expected_resolution_date" class="form-label">Expected Resolution Date</label>
                        <input type="date" class="form-control @error('expected_resolution_date') is-invalid @enderror" 
                               id="expected_resolution_date" name="expected_resolution_date" value="{{ old('expected_resolution_date') }}">
                        @error('expected_resolution_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">This will be automatically calculated based on priority if left empty.</div>
                    </div>
                    
                    @if(auth()->user()->isAdmin())
                    <div class="mb-3">
                        <label for="assigned_to" class="form-label">Assign To</label>
                        <input type="text" class="form-control @error('assigned_to') is-invalid @enderror" 
                               id="assigned_to" name="assigned_to" value="{{ old('assigned_to') }}" 
                               placeholder="Name of person/department to handle this problem">
                        @error('assigned_to')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="admin_notes" class="form-label">Admin Notes</label>
                        <textarea class="form-control @error('admin_notes') is-invalid @enderror" 
                                  id="admin_notes" name="admin_notes" rows="3" 
                                  placeholder="Internal notes for administrative purposes">{{ old('admin_notes') }}</textarea>
                        @error('admin_notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @endif
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('problems.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Report Problem
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.avatar-circle {
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.2rem;
}

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

// Dynamic dropdown loading for admin
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

// Update expected resolution date based on priority
document.getElementById('priority').addEventListener('change', function() {
    const priority = this.value;
    const expectedDateInput = document.getElementById('expected_resolution_date');
    
    if (priority && !expectedDateInput.value) {
        const today = new Date();
        let daysToAdd = 30; // default
        
        switch(priority) {
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