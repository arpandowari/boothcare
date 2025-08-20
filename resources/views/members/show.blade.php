@extends('layouts.app')

@section('title', 'Member Details - Boothcare')
@section('page-title', 'Member: ' . $member->name)

@php
use Illuminate\Support\Facades\Storage;
@endphp

@push('styles')
<style>
    /* Base Styles */
    body {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        min-height: 100vh;
    }

    .container-fluid {
        max-width: 1400px;
        margin: 0 auto;
    }

    /* Enhanced Animations */
    .fade-in {
        animation: fadeIn 0.6s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .slide-up {
        animation: slideUp 0.5s ease-out;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Professional Header Styles */
    .professional-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 2rem;
        color: white;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        margin-bottom: 0;
        position: relative;
        overflow: hidden;
    }

    .professional-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><defs><radialGradient id="star" cx="50%" cy="50%" r="50%"><stop offset="0%" stop-color="white" stop-opacity="0.3"/><stop offset="100%" stop-color="white" stop-opacity="0"/></radialGradient></defs><circle cx="30" cy="40" r="1.5" fill="url(%23star)"/><circle cx="170" cy="60" r="1" fill="url(%23star)"/><circle cx="80" cy="90" r="0.8" fill="url(%23star)"/><circle cx="140" cy="130" r="1.2" fill="url(%23star)"/></svg>');
        opacity: 0.6;
    }

    .header-content {
        position: relative;
        z-index: 2;
    }

    .header-badges .badge {
        font-size: 0.85rem;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
    }

    .profile-avatar-large {
        position: relative;
        width: 120px;
        height: 120px;
        flex-shrink: 0;
    }

    .profile-avatar-large img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border: 4px solid rgba(255, 255, 255, 0.3);
    }

    .avatar-placeholder {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border: 4px solid rgba(255, 255, 255, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: 700;
        color: white;
    }

    .crown-badge {
        position: absolute;
        top: -8px;
        right: -8px;
        width: 32px;
        height: 32px;
        background: #fbbf24;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        color: #92400e;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        border: 3px solid white;
    }

    .header-title {
        font-size: 2.5rem;
        font-weight: 700;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin: 0;
        color: white;
    }

    .header-subtitle {
        font-size: 1.2rem;
        opacity: 0.9;
        font-weight: 400;
        color: white;
    }

    .header-location {
        font-size: 1rem;
        opacity: 0.8;
        color: white;
    }

    .action-buttons .btn {
        padding: 0.875rem 2rem;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 12px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .action-buttons .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .action-buttons .btn-light {
        background: rgba(255, 255, 255, 0.9);
        color: #1f2937;
        border: none;
    }

    .action-buttons .btn-light:hover {
        background: white;
        color: #1f2937;
    }

    .action-buttons .btn-outline-light {
        background: transparent;
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .action-buttons .btn-outline-light:hover {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border-color: rgba(255, 255, 255, 0.5);
    }

    /* Enhanced Cards */
    .info-card, .card {
        transition: all 0.2s ease;
        border: 1px solid #e5e7eb;
    }

    .info-card:hover, .card:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        border-color: #cbd5e1;
    }

    .card-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 1px solid #e5e7eb;
    }

    .card-title i {
        color: #667eea;
    }

    /* Info Items */
    .info-item {
        padding: 1rem;
        background: #f8fafc;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
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

    .info-value.empty {
        color: #9ca3af;
        font-style: italic;
    }

    /* Enhanced ID Cards */
    .id-card {
        transition: all 0.3s ease;
        cursor: default;
    }

    .id-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    /* Responsive Design */
    @media (max-width: 992px) {
        .professional-header {
            padding: 1.5rem;
        }

        .header-title {
            font-size: 2rem;
        }

        .profile-avatar-large,
        .profile-avatar-large img,
        .avatar-placeholder {
            width: 100px;
            height: 100px;
        }

        .avatar-placeholder {
            font-size: 2rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Professional Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="professional-header">
                <div class="header-content">
                    <div class="d-flex align-items-center mb-3">
                        <a href="{{ route('members.index') }}" class="btn btn-outline-light btn-sm me-3">
                            <i class="fas fa-arrow-left me-1"></i>
                            Back to Members
                        </a>
                        <div class="header-badges">
                            @if($member->is_family_head)
                                <span class="badge bg-warning text-dark me-2">
                                    <i class="fas fa-crown me-1"></i>Family Head
                                </span>
                            @endif
                            @if($member->hasUserAccount())
                                <span class="badge bg-success me-2">
                                    <i class="fas fa-user-check me-1"></i>Has Account
                                </span>
                            @else
                                <span class="badge bg-secondary me-2">
                                    <i class="fas fa-user-times me-1"></i>No Account
                                </span>
                            @endif
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-hashtag me-1"></i>{{ $member->id }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center mb-3">
                                <div class="profile-avatar-large me-4">
                                    @if($member->profile_photo_url)
                                        <img src="{{ $member->profile_photo_url }}" alt="Profile" class="rounded-circle">
                                    @else
                                        <div class="avatar-placeholder">
                                            {{ strtoupper(substr($member->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    @if($member->is_family_head)
                                        <div class="crown-badge">
                                            <i class="fas fa-crown"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h1 class="header-title mb-2">{{ $member->name }}</h1>
                                    <p class="header-subtitle mb-1">
                                        {{ $member->relationship_to_head ?? $member->relation_to_head ?? 'Family Member' }}
                                        @if($member->age)
                                            • {{ $member->age }} years old
                                        @endif
                                        @if($member->gender)
                                            • {{ ucfirst($member->gender) }}
                                        @endif
                                    </p>
                                    @if($member->house)
                                        <p class="header-location mb-0">
                                            <i class="fas fa-home me-1"></i>
                                            House {{ $member->house->house_number }}
                                            @if($member->house->booth && $member->house->booth->area)
                                                , {{ $member->house->booth->area->area_name }}
                                            @endif
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="action-buttons">
                                <a href="{{ route('members.edit', $member) }}" class="btn btn-light btn-lg me-2">
                                    <i class="fas fa-edit me-1"></i>
                                    Edit Member
                                </a>
                                @if($member->user)
                                    <a href="{{ route('users.show', $member->user) }}" class="btn btn-outline-light btn-lg">
                                        <i class="fas fa-user me-1"></i>
                                        User Account
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Layout -->
    <div class="row">
        <!-- Left Column - Main Content -->
        <div class="col-lg-8">
            <!-- Personal Information Card -->
            <div class="card border-0 shadow-sm mb-4 fade-in">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user text-primary me-2"></i>
                        Personal Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">Phone Number</div>
                                <div class="info-value">
                                    @if($member->phone)
                                        <i class="fas fa-phone text-success me-2"></i>
                                        <a href="tel:{{ $member->phone }}" class="text-decoration-none">{{ $member->phone }}</a>
                                    @else
                                        <span class="text-muted">Not provided</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">Email Address</div>
                                <div class="info-value">
                                    @if($member->email)
                                        <i class="fas fa-envelope text-info me-2"></i>
                                        <a href="mailto:{{ $member->email }}" class="text-decoration-none">{{ $member->email }}</a>
                                    @else
                                        <span class="text-muted">Not provided</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">Date of Birth</div>
                                <div class="info-value">
                                    @if($member->date_of_birth)
                                        <i class="fas fa-calendar text-primary me-2"></i>
                                        {{ $member->date_of_birth->format('d M Y') }}
                                    @else
                                        <span class="text-muted">Not provided</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">Age</div>
                                <div class="info-value">
                                    @if($member->age)
                                        <i class="fas fa-birthday-cake text-warning me-2"></i>
                                        {{ $member->age }} years old
                                    @else
                                        <span class="text-muted">Not specified</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">Education</div>
                                <div class="info-value">
                                    @if($member->education)
                                        <i class="fas fa-graduation-cap text-success me-2"></i>
                                        {{ $member->education }}
                                    @else
                                        <span class="text-muted">Not provided</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">Occupation</div>
                                <div class="info-value">
                                    @if($member->occupation)
                                        <i class="fas fa-briefcase text-info me-2"></i>
                                        {{ $member->occupation }}
                                    @else
                                        <span class="text-muted">Not provided</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">Marital Status</div>
                                <div class="info-value">
                                    @if($member->marital_status)
                                        <i class="fas fa-heart text-danger me-2"></i>
                                        {{ ucfirst($member->marital_status) }}
                                    @else
                                        <span class="text-muted">Not provided</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        @if($member->monthly_income)
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">Monthly Income</div>
                                    <div class="info-value">
                                        <i class="fas fa-rupee-sign text-success me-2"></i>
                                        ₹{{ number_format($member->monthly_income) }}
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($member->ration_card_number)
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">Ration Card Number</div>
                                    <div class="info-value">
                                        <i class="fas fa-utensils text-info me-2"></i>
                                        {{ $member->ration_card_number }}
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($member->medical_conditions)
                            <div class="col-12">
                                <div class="info-item">
                                    <div class="info-label">Medical Conditions</div>
                                    <div class="info-value">
                                        <i class="fas fa-stethoscope text-danger me-2"></i>
                                        {{ $member->medical_conditions }}
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($member->notes)
                            <div class="col-12">
                                <div class="info-item">
                                    <div class="info-label">Additional Notes</div>
                                    <div class="info-value">
                                        <i class="fas fa-sticky-note text-warning me-2"></i>
                                        {{ $member->notes }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Location Details Card -->
            <div class="card border-0 shadow-sm mb-4 fade-in">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-map-marker-alt text-primary me-2"></i>
                        Location Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @if($member->house && $member->house->booth && $member->house->booth->area)
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">Area</div>
                                    <div class="info-value">
                                        <i class="fas fa-map text-primary me-2"></i>
                                        {{ $member->house->booth->area->area_name }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">Booth</div>
                                    <div class="info-value">
                                        <i class="fas fa-vote-yea text-info me-2"></i>
                                        {{ $member->house->booth->booth_number }} - {{ $member->house->booth->booth_name }}
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        @if($member->house)
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-label">House Number</div>
                                    <div class="info-value">
                                        <i class="fas fa-home text-success me-2"></i>
                                        {{ $member->house->house_number }}
                                    </div>
                                </div>
                            </div>
                            
                            @if($member->house->address)
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">Full Address</div>
                                        <div class="info-value">
                                            <i class="fas fa-location-dot text-danger me-2"></i>
                                            {{ $member->house->address }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="col-12">
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    No house information available for this member.
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Government IDs & Documents Card -->
            @if($member->aadhar_number || $member->pan_number || $member->voter_id || $member->ration_card_number)
                <div class="card border-0 shadow-sm mb-4 slide-up">
                    <div class="card-header bg-light border-0">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-id-card text-primary me-2"></i>
                            Government IDs & Documents
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @if($member->aadhar_number)
                                <div class="col-md-6 col-lg-3">
                                    <div class="card border-0 bg-light h-100 id-card">
                                        <div class="card-body text-center p-3">
                                            <div class="mb-3">
                                                <i class="fas fa-id-card text-primary" style="font-size: 2.5rem;"></i>
                                            </div>
                                            <h6 class="card-title fw-bold">Aadhar Card</h6>
                                            <p class="card-text font-monospace fw-bold text-primary mb-2">{{ $member->aadhar_number }}</p>
                                            @if($member->aadhar_photo)
                                                <a href="{{ Storage::url($member->aadhar_photo) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye me-1"></i>View Document
                                                </a>
                                            @else
                                                <span class="text-muted small">No document uploaded</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($member->pan_number)
                                <div class="col-md-6 col-lg-3">
                                    <div class="card border-0 bg-light h-100 id-card">
                                        <div class="card-body text-center p-3">
                                            <div class="mb-3">
                                                <i class="fas fa-credit-card text-success" style="font-size: 2.5rem;"></i>
                                            </div>
                                            <h6 class="card-title fw-bold">PAN Card</h6>
                                            <p class="card-text font-monospace fw-bold text-success mb-2">{{ $member->pan_number }}</p>
                                            @if($member->pan_photo)
                                                <a href="{{ Storage::url($member->pan_photo) }}" target="_blank" class="btn btn-outline-success btn-sm">
                                                    <i class="fas fa-eye me-1"></i>View Document
                                                </a>
                                            @else
                                                <span class="text-muted small">No document uploaded</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($member->voter_id)
                                <div class="col-md-6 col-lg-3">
                                    <div class="card border-0 bg-light h-100 id-card">
                                        <div class="card-body text-center p-3">
                                            <div class="mb-3">
                                                <i class="fas fa-vote-yea text-warning" style="font-size: 2.5rem;"></i>
                                            </div>
                                            <h6 class="card-title fw-bold">Voter ID</h6>
                                            <p class="card-text font-monospace fw-bold text-warning mb-2">{{ $member->voter_id }}</p>
                                            @if($member->voter_id_photo)
                                                <a href="{{ Storage::url($member->voter_id_photo) }}" target="_blank" class="btn btn-outline-warning btn-sm">
                                                    <i class="fas fa-eye me-1"></i>View Document
                                                </a>
                                            @else
                                                <span class="text-muted small">No document uploaded</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($member->ration_card_number)
                                <div class="col-md-6 col-lg-3">
                                    <div class="card border-0 bg-light h-100 id-card">
                                        <div class="card-body text-center p-3">
                                            <div class="mb-3">
                                                <i class="fas fa-utensils text-info" style="font-size: 2.5rem;"></i>
                                            </div>
                                            <h6 class="card-title fw-bold">Ration Card</h6>
                                            <p class="card-text font-monospace fw-bold text-info mb-2">{{ $member->ration_card_number }}</p>
                                            @if($member->ration_card_photo)
                                                <a href="{{ Storage::url($member->ration_card_photo) }}" target="_blank" class="btn btn-outline-info btn-sm">
                                                    <i class="fas fa-eye me-1"></i>View Document
                                                </a>
                                            @else
                                                <span class="text-muted small">No document uploaded</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column - Sidebar -->
        <div class="col-lg-4">
            <!-- Account Status Card -->
            <div class="card border-0 shadow-sm mb-4 sticky-top fade-in" style="top: 2rem;">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-cog text-primary me-2"></i>
                        Account Status
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        @if($member->hasUserAccount())
                            <div class="avatar-status mb-3">
                                <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                    <i class="fas fa-user-check text-success" style="font-size: 2.5rem;"></i>
                                </div>
                            </div>
                            <h6 class="text-success mb-2 fw-bold">Has User Account</h6>
                            <p class="text-muted small mb-3">This member has a user account and can log in to the system.</p>
                            <div class="d-grid gap-2">
                                <a href="{{ route('users.show', $member->user) }}" class="btn btn-success">
                                    <i class="fas fa-user me-2"></i>
                                    View User Account
                                </a>
                            </div>
                        @else
                            <div class="avatar-status mb-3">
                                <div class="rounded-circle bg-secondary bg-opacity-10 d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                    <i class="fas fa-user-times text-secondary" style="font-size: 2.5rem;"></i>
                                </div>
                            </div>
                            <h6 class="text-secondary mb-2 fw-bold">No User Account</h6>
                            <p class="text-muted small mb-3">This member doesn't have a user account yet.</p>
                            <div class="d-grid gap-2">
                                <button class="btn btn-outline-primary" disabled>
                                    <i class="fas fa-user-plus me-2"></i>
                                    Create Account
                                </button>
                            </div>
                        @endif
                    </div>

                    <hr>

                    <!-- Additional Info -->
                    <div class="row text-center g-3">
                        <div class="col-6">
                            <div class="info-item">
                                <div class="info-label small">LOGIN METHOD</div>
                                <div class="info-value small">Aadhar + DOB</div>
                            </div>
                        </div>
                        @if($member->user_account_created_at)
                            <div class="col-6">
                                <div class="info-item">
                                    <div class="info-label small">ACCOUNT CREATED</div>
                                    <div class="info-value small">{{ $member->user_account_created_at->format('M d, Y') }}</div>
                                </div>
                            </div>
                        @endif
                        <div class="col-6">
                            <div class="info-item">
                                <div class="info-label small">STATUS</div>
                                <div class="info-value">
                                    @if($member->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-item">
                                <div class="info-label small">MEMBER ID</div>
                                <div class="info-value small font-monospace">#{{ $member->id }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats Card -->
            <div class="card border-0 shadow-sm mb-4 slide-up">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar text-primary me-2"></i>
                        Quick Stats
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <div class="stat-item">
                                <div class="stat-icon bg-primary bg-opacity-10 text-primary mb-2 mx-auto" style="width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="stat-number text-primary">0</div>
                                <div class="stat-label">Total Problems</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-item">
                                <div class="stat-icon bg-warning bg-opacity-10 text-warning mb-2 mx-auto" style="width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="stat-number text-warning">0</div>
                                <div class="stat-label">Active Issues</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-item">
                                <div class="stat-icon bg-success bg-opacity-10 text-success mb-2 mx-auto" style="width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="stat-number text-success">0</div>
                                <div class="stat-label">Resolved</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-item">
                                <div class="stat-icon bg-info bg-opacity-10 text-info mb-2 mx-auto" style="width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-home"></i>
                                </div>
                                <div class="stat-number text-info">{{ $member->house_id ?? 0 }}</div>
                                <div class="stat-label">House ID</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card border-0 shadow-sm fade-in">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt text-primary me-2"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('members.edit', $member) }}" class="btn btn-outline-primary">
                            <i class="fas fa-edit me-2"></i>
                            Edit Member Details
                        </a>
                        
                        @if($member->user)
                            <a href="{{ route('users.show', $member->user) }}" class="btn btn-outline-info">
                                <i class="fas fa-user me-2"></i>
                                View User Account
                            </a>
                        @endif
                        
                        @if($member->house)
                            <a href="{{ route('houses.show', $member->house) }}" class="btn btn-outline-success">
                                <i class="fas fa-home me-2"></i>
                                View House Details
                            </a>
                        @endif
                        
                        <button class="btn btn-outline-warning" disabled>
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Report Problem
                        </button>
                        
                        <hr class="my-2">
                        
                        <div class="text-center">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Last updated: {{ $member->updated_at->format('M d, Y H:i A') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add some JavaScript for enhanced interactions -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    // Add loading states for buttons
    document.querySelectorAll('.btn').forEach(button => {
        button.addEventListener('click', function() {
            if (!this.disabled && this.href) {
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';
            }
        });
    });

    // Add tooltips to truncated text
    document.querySelectorAll('.text-truncate').forEach(element => {
        if (element.scrollWidth > element.clientWidth) {
            element.setAttribute('title', element.textContent);
        }
    });

    // Enhanced document viewing with error handling
    document.querySelectorAll('a[href*="storage"]').forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (!href || href === '#') {
                e.preventDefault();
                alert('Document not available');
                return false;
            }
        });
    });
});
</script>
@endsection