@extends('layouts.app')

@section('title', 'My Dashboard - Boothcare')
@section('page-title', 'My Dashboard')

@push('styles')
<style>
/* E-commerce Style Problem Cards */
.problem-item {
    transition: all 0.3s ease;
    cursor: pointer;
}

.problem-item:hover {
    background-color: #f8f9fa !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.problem-item:last-child {
    border-bottom: none !important;
}

/* Icon Circles */
.icon-circle {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.bg-primary-light { background-color: rgba(13, 110, 253, 0.1); }
.bg-warning-light { background-color: rgba(255, 193, 7, 0.1); }
.bg-info-light { background-color: rgba(13, 202, 240, 0.1); }
.bg-success-light { background-color: rgba(25, 135, 84, 0.1); }
.bg-secondary-light { background-color: rgba(108, 117, 125, 0.1); }

/* Priority Badges */
.badge.priority-urgent {
    background: linear-gradient(45deg, #dc3545, #e74c3c);
    color: white;
}

.badge.priority-high {
    background: linear-gradient(45deg, #fd7e14, #f39c12);
    color: white;
}

.badge.priority-medium {
    background: linear-gradient(45deg, #ffc107, #f1c40f);
    color: #212529;
}

.badge.priority-low {
    background: linear-gradient(45deg, #6c757d, #95a5a6);
    color: white;
}

/* Status Timeline */
.status-timeline {
    position: relative;
    padding-left: 0;
}

.status-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 12px;
    position: relative;
}

.status-item:last-child {
    margin-bottom: 0;
}

.status-dot {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    font-size: 10px;
    flex-shrink: 0;
    border: 2px solid #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.status-content {
    flex-grow: 1;
    min-width: 0;
}

.status-title {
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 2px;
    line-height: 1.2;
}

.status-time {
    font-size: 0.75rem;
    color: #6c757d;
    line-height: 1.2;
}

.status-item.active .status-title {
    color: #0d6efd;
}

.status-item.completed .status-title {
    color: #198754;
}

.status-item.active .status-dot {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.4); }
    70% { box-shadow: 0 0 0 10px rgba(13, 110, 253, 0); }
    100% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0); }
}

/* Status Badge Styles */
.badge.bg-success-light {
    background-color: rgba(25, 135, 84, 0.1) !important;
    color: #198754 !important;
    border: 1px solid rgba(25, 135, 84, 0.2);
}

.badge.bg-primary-light {
    background-color: rgba(13, 110, 253, 0.1) !important;
    color: #0d6efd !important;
    border: 1px solid rgba(13, 110, 253, 0.2);
}

/* Hover Effects */
.hover-bg-light:hover {
    background-color: #f8f9fa !important;
}

/* Empty State */
.empty-state {
    padding: 2rem;
}

.empty-icon {
    margin-bottom: 1rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .problem-item .row {
        flex-direction: column;
    }
    
    .problem-item .col-md-6,
    .problem-item .col-md-4,
    .problem-item .col-md-2 {
        margin-bottom: 1rem;
    }
    
    .status-timeline {
        margin-top: 1rem;
    }
    
    .icon-circle {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
}

/* Card Enhancements */
.card.border-0.shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
    border: 1px solid rgba(0,0,0,0.05) !important;
}

.card-header.bg-white {
    background-color: #fff !important;
    border-bottom: 1px solid #e9ecef !important;
}

/* Button Enhancements */
.btn-outline-primary.btn-sm {
    font-size: 0.8rem;
    padding: 0.375rem 0.75rem;
    border-radius: 0.375rem;
}

/* View All Link */
.btn-link.text-decoration-none {
    color: #0d6efd;
    font-weight: 500;
}

.btn-link.text-decoration-none:hover {
    color: #0b5ed7;
    text-decoration: underline !important;
}
</style>
@endpush

@section('content')
<!-- Enhanced Welcome Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-gradient-success text-white overflow-hidden position-relative">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center mb-3">
                            <div class="user-avatar me-3">
                                @if($familyMember->profile_photo)
                                    <img src="{{ asset('storage/' . $familyMember->profile_photo) }}" 
                                         class="rounded-circle border border-white border-3" width="70" height="70" alt="Profile">
                                @else
                                    <div class="avatar-circle bg-white bg-opacity-20 border border-white border-3" style="width: 70px; height: 70px; font-size: 1.8rem;">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h3 class="mb-1">Welcome back, {{ Auth::user()->name }}!</h3>
                                @if($familyMember->is_family_head)
                                    <span class="badge bg-warning bg-opacity-90 text-dark mb-2">
                                        <i class="fas fa-crown me-1"></i>Family Head
                                    </span>
                                @endif
                                <p class="mb-0 opacity-75">
                                    <i class="fas fa-map-marker-alt me-2"></i>
                                    {{ $familyMember->house->booth->area->area_name }} → 
                                    Booth {{ $familyMember->house->booth->booth_number }} → 
                                    House {{ $familyMember->house->house_number }}
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar-day me-2"></i>
                                    <div>
                                        <div class="fw-bold">{{ now()->format('M d, Y') }}</div>
                                        <small class="opacity-75">{{ now()->format('l') }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-clock me-2"></i>
                                    <div>
                                        <div class="fw-bold" id="userCurrentTime">{{ now()->format('g:i A') }}</div>
                                        <small class="opacity-75">Current Time</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-users me-2"></i>
                                    <div>
                                        <div class="fw-bold">{{ $stats['house_members'] }}</div>
                                        <small class="opacity-75">House Members</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="user-dashboard-illustration">
                            <i class="fas fa-home fa-4x opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="position-absolute top-0 end-0 p-3">
                <div class="dropdown">
                    <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-cog me-1"></i> My Account
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-edit me-2"></i>Edit Profile</a></li>
                        <li><a class="dropdown-item" href="{{ route('profile.documents') }}"><i class="fas fa-id-card me-2"></i>My Documents</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('members.house-members', $familyMember->house) }}"><i class="fas fa-users me-2"></i>House Members</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Quick Stats -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stat-card bg-gradient-primary text-white border-0 shadow-sm">
            <div class="card-body text-center position-relative">
                <div class="stat-icon">
                    <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                </div>
                <h3 class="counter" data-target="{{ $stats['my_problems'] }}">0</h3>
                <p class="mb-0">My Problems</p>
                <div class="stat-trend">
                    <small class="opacity-75">
                        <i class="fas fa-list me-1"></i>
                        Total reported
                    </small>
                </div>
                <div class="position-absolute top-0 end-0 p-2">
                    <a href="{{ route('problems.index') }}" class="text-white text-decoration-none">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card bg-gradient-warning text-white border-0 shadow-sm">
            <div class="card-body text-center position-relative">
                <div class="stat-icon">
                    <i class="fas fa-clock fa-2x mb-2"></i>
                </div>
                <h3 class="counter" data-target="{{ $stats['active_problems'] }}">0</h3>
                <p class="mb-0">Active</p>
                <div class="stat-trend">
                    <small class="opacity-75">
                        <i class="fas fa-hourglass-half me-1"></i>
                        In progress
                    </small>
                </div>
                <div class="position-absolute top-0 end-0 p-2">
                    <a href="{{ route('problems.index') }}?status=active" class="text-white text-decoration-none">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card bg-gradient-success text-white border-0 shadow-sm">
            <div class="card-body text-center position-relative">
                <div class="stat-icon">
                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                </div>
                <h3 class="counter" data-target="{{ $stats['resolved_problems'] }}">0</h3>
                <p class="mb-0">Resolved</p>
                <div class="stat-trend">
                    <small class="opacity-75">
                        <i class="fas fa-check me-1"></i>
                        Completed
                    </small>
                </div>
                <div class="position-absolute top-0 end-0 p-2">
                    <a href="{{ route('problems.index') }}?status=resolved" class="text-white text-decoration-none">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card bg-gradient-info text-white border-0 shadow-sm">
            <div class="card-body text-center position-relative">
                <div class="stat-icon">
                    <i class="fas fa-users fa-2x mb-2"></i>
                </div>
                <h3 class="counter" data-target="{{ $stats['house_members'] }}">0</h3>
                <p class="mb-0">House Members</p>
                <div class="stat-trend">
                    <small class="opacity-75">
                        <i class="fas fa-home me-1"></i>
                        Family size
                    </small>
                </div>
                <div class="position-absolute top-0 end-0 p-2">
                    <a href="{{ route('members.house-members', $familyMember->house) }}" class="text-white text-decoration-none">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="row">
    <!-- My Problems - E-commerce Style -->
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                <div>
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-clipboard-list me-2 text-primary"></i>
                        My Problems
                    </h5>
                    <small class="text-muted">Track your reported issues and their resolution status</small>
                </div>
                <a href="{{ route('problems.create') }}" class="btn btn-primary btn-sm px-3">
                    <i class="fas fa-plus me-1"></i>
                    Report New
                </a>
            </div>
            <div class="card-body p-0">
                @if($my_problems->count() > 0)
                    @foreach($my_problems as $problem)
                    <div class="problem-item border-bottom p-4 hover-bg-light">
                        <div class="row align-items-center">
                            <!-- Problem Info -->
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <div class="problem-icon me-3">
                                        @switch($problem->category)
                                            @case('water')
                                                <div class="icon-circle bg-primary-light">
                                                    <i class="fas fa-tint text-primary"></i>
                                                </div>
                                                @break
                                            @case('electricity')
                                                <div class="icon-circle bg-warning-light">
                                                    <i class="fas fa-bolt text-warning"></i>
                                                </div>
                                                @break
                                            @case('road')
                                                <div class="icon-circle bg-info-light">
                                                    <i class="fas fa-road text-info"></i>
                                                </div>
                                                @break
                                            @case('sanitation')
                                                <div class="icon-circle bg-success-light">
                                                    <i class="fas fa-broom text-success"></i>
                                                </div>
                                                @break
                                            @default
                                                <div class="icon-circle bg-secondary-light">
                                                    <i class="fas fa-exclamation-triangle text-secondary"></i>
                                                </div>
                                        @endswitch
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-semibold">{{ $problem->title }}</h6>
                                        <p class="text-muted mb-2 small">{{ Str::limit($problem->description, 80) }}</p>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-light text-dark border">
                                                <i class="fas fa-tag me-1"></i>{{ ucfirst($problem->category) }}
                                            </span>
                                            <span class="badge priority-{{ $problem->priority }}">
                                                @switch($problem->priority)
                                                    @case('urgent')
                                                        <i class="fas fa-exclamation-circle me-1"></i>Urgent
                                                        @break
                                                    @case('high')
                                                        <i class="fas fa-arrow-up me-1"></i>High
                                                        @break
                                                    @case('medium')
                                                        <i class="fas fa-minus me-1"></i>Medium
                                                        @break
                                                    @default
                                                        <i class="fas fa-arrow-down me-1"></i>Low
                                                @endswitch
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Status & Progress -->
                            <div class="col-md-4">
                                <div class="status-timeline">
                                    @switch($problem->status)
                                        @case('reported')
                                            <div class="status-item active">
                                                <div class="status-dot bg-primary"></div>
                                                <div class="status-content">
                                                    <div class="status-title">Problem Reported</div>
                                                    <div class="status-time">{{ $problem->created_at->format('M d, Y') }}</div>
                                                </div>
                                            </div>
                                            <div class="status-item">
                                                <div class="status-dot bg-light"></div>
                                                <div class="status-content">
                                                    <div class="status-title text-muted">Under Review</div>
                                                    <div class="status-time text-muted">Pending</div>
                                                </div>
                                            </div>
                                            @break
                                        @case('in_progress')
                                            <div class="status-item completed">
                                                <div class="status-dot bg-success">
                                                    <i class="fas fa-check"></i>
                                                </div>
                                                <div class="status-content">
                                                    <div class="status-title">Problem Reported</div>
                                                    <div class="status-time">{{ $problem->created_at->format('M d, Y') }}</div>
                                                </div>
                                            </div>
                                            <div class="status-item active">
                                                <div class="status-dot bg-warning"></div>
                                                <div class="status-content">
                                                    <div class="status-title">Work in Progress</div>
                                                    <div class="status-time">{{ $problem->updated_at->format('M d, Y') }}</div>
                                                </div>
                                            </div>
                                            @break
                                        @case('resolved')
                                            <div class="status-item completed">
                                                <div class="status-dot bg-success">
                                                    <i class="fas fa-check"></i>
                                                </div>
                                                <div class="status-content">
                                                    <div class="status-title">Problem Resolved</div>
                                                    <div class="status-time">{{ $problem->updated_at->format('M d, Y') }}</div>
                                                </div>
                                            </div>
                                            @break
                                        @default
                                            <div class="status-item active">
                                                <div class="status-dot bg-secondary"></div>
                                                <div class="status-content">
                                                    <div class="status-title">{{ ucfirst(str_replace('_', ' ', $problem->status)) }}</div>
                                                    <div class="status-time">{{ $problem->updated_at->format('M d, Y') }}</div>
                                                </div>
                                            </div>
                                    @endswitch
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="col-md-2 text-end">
                                <div class="d-flex flex-column gap-2">
                                    <a href="{{ route('problems.show', $problem) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>
                                        View Details
                                    </a>
                                    @if($problem->status === 'resolved')
                                        <span class="badge bg-success-light text-success">
                                            <i class="fas fa-check-circle me-1"></i>Completed
                                        </span>
                                    @else
                                        <span class="badge bg-primary-light text-primary">
                                            <i class="fas fa-clock me-1"></i>In Progress
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    
                    <!-- View All Link -->
                    @if($my_problems->count() >= 5)
                    <div class="p-3 text-center border-top bg-light">
                        <a href="{{ route('problems.index') }}" class="btn btn-link text-decoration-none">
                            <i class="fas fa-arrow-right me-1"></i>
                            View All My Problems ({{ $stats['my_problems'] }})
                        </a>
                    </div>
                    @endif
                @else
                <div class="text-center py-5">
                    <div class="empty-state">
                        <div class="empty-icon mb-3">
                            <i class="fas fa-clipboard-list fa-4x text-muted opacity-50"></i>
                        </div>
                        <h5 class="text-muted mb-2">No problems reported yet</h5>
                        <p class="text-muted mb-4">When you face any issues in your area, report them here for quick resolution.</p>
                        <a href="{{ route('problems.create') }}" class="btn btn-primary px-4">
                            <i class="fas fa-plus me-2"></i>
                            Report Your First Problem
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- My Profile Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user me-2"></i>
                    My Profile
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    @if($familyMember->profile_photo)
                    <img src="{{ asset('storage/' . $familyMember->profile_photo) }}"
                        class="rounded-circle" width="80" height="80" alt="Profile">
                    @else
                    <div class="avatar-circle bg-primary text-white mx-auto" style="width: 80px; height: 80px; font-size: 2rem;">
                        {{ strtoupper(substr($familyMember->name, 0, 1)) }}
                    </div>
                    @endif
                    <h6 class="mt-2 mb-1">{{ $familyMember->name }}</h6>
                    <small class="text-muted">{{ $familyMember->relationship_to_head ?? $familyMember->relation_to_head }}</small>
                    @if($familyMember->is_family_head)
                    <br><span class="badge bg-warning mt-1">Family Head</span>
                    @endif
                </div>

                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <div class="h6 text-primary">{{ $familyMember->age ?? 'N/A' }}</div>
                            <small class="text-muted">Age</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="h6 text-success">{{ ucfirst($familyMember->gender ?? 'N/A') }}</div>
                        <small class="text-muted">Gender</small>
                    </div>
                </div>

                <hr>

                <div class="d-grid gap-2">
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-edit me-1"></i>
                        Edit Profile
                    </a>
                    <a href="{{ route('profile.documents') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-id-card me-1"></i>
                        Manage Documents
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('problems.create') }}" class="btn btn-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Report Problem
                    </a>
                    <a href="{{ route('problems.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-list me-2"></i>
                        View All My Problems
                    </a>
                    <a href="{{ route('house.members') }}" class="btn btn-outline-info">
                        <i class="fas fa-users me-2"></i>
                        House Members
                    </a>

                </div>
            </div>
        </div>

        <!-- House Problems -->
        @if($house_problems->count() > 0)
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-home me-2"></i>
                    House Problems
                </h5>
            </div>
            <div class="card-body">
                @foreach($house_problems as $problem)
                <div class="d-flex align-items-start mb-3">
                    <div class="avatar-circle bg-{{ $problem->priority_color }} text-white me-3" style="width: 30px; height: 30px; font-size: 0.8rem;">
                        <i class="fas fa-exclamation"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-bold">{{ Str::limit($problem->title, 25) }}</div>
                        <small class="text-muted">
                            By: {{ $problem->familyMember->name }}<br>
                            {{ $problem->reported_date->diffForHumans() }}
                        </small>
                        <br>
                        <span class="badge bg-{{ $problem->status_color }} mt-1" style="font-size: 0.7em;">
                            {{ ucfirst(str_replace('_', ' ', $problem->status)) }}
                        </span>
                    </div>
                </div>
                @if(!$loop->last)
                <hr class="my-2">
                @endif
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Location Info -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-map-marker-alt me-2"></i>
                    My Location Details
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center p-3 border rounded">
                            <i class="fas fa-map-marked-alt fa-2x text-info mb-2"></i>
                            <h6>Area</h6>
                            <p class="mb-0">{{ $familyMember->house->booth->area->area_name }}</p>
                            <small class="text-muted">{{ $familyMember->house->booth->area->district }}</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 border rounded">
                            <i class="fas fa-building fa-2x text-primary mb-2"></i>
                            <h6>Booth</h6>
                            <p class="mb-0">{{ $familyMember->house->booth->booth_number }}</p>
                            <small class="text-muted">{{ $familyMember->house->booth->booth_name }}</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 border rounded">
                            <i class="fas fa-home fa-2x text-success mb-2"></i>
                            <h6>House</h6>
                            <p class="mb-0">{{ $familyMember->house->house_number }}</p>
                            <small class="text-muted">{{ $familyMember->house->area ?? 'N/A' }}</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 border rounded">
                            <i class="fas fa-users fa-2x text-warning mb-2"></i>
                            <h6>Family Members</h6>
                            <p class="mb-0">{{ $stats['house_members'] }}</p>
                            <small class="text-muted">Total in house</small>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <h6><i class="fas fa-map-pin me-2"></i>Full Address</h6>
                        <p class="text-muted mb-0">{{ $familyMember->house->address }}</p>
                        @if($familyMember->house->pincode)
                        <small class="text-muted">PIN: {{ $familyMember->house->pincode }}</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Enhanced Gradients */
.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    position: relative;
    box-shadow: 0 10px 30px rgba(40, 167, 69, 0.3);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff 0%, #6610f2 100%);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
}

/* Enhanced Stat Cards */
.stat-card {
    transition: all 0.3s ease;
    border-radius: 15px;
    overflow: hidden;
    position: relative;
}

.stat-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
    pointer-events: none;
}

.stat-icon {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

/* User Avatar */
.user-avatar {
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-5px); }
    100% { transform: translateY(0px); }
}

/* Dashboard Illustration */
.user-dashboard-illustration {
    animation: float 4s ease-in-out infinite;
}

/* Counter Animation */
.counter {
    font-weight: 700;
    font-size: 2rem;
}

/* Avatar Circle */
.avatar-circle {
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    transition: all 0.3s ease;
}

.avatar-circle:hover {
    transform: scale(1.1);
}

/* Card Enhancements */
.card {
    border-radius: 12px;
    border: none;
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.card-header {
    border-radius: 12px 12px 0 0 !important;
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

/* Profile Card Enhancements */
.profile-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

/* Quick Actions */
.quick-action-btn {
    transition: all 0.3s ease;
    border-radius: 10px;
}

.quick-action-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

/* Location Cards */
.location-card {
    transition: all 0.3s ease;
    border-radius: 12px;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
}

.location-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

/* Progress Bars */
.progress {
    border-radius: 10px;
    overflow: hidden;
}

.progress-bar {
    transition: width 1s ease-in-out;
}

/* Badges */
.badge {
    border-radius: 8px;
    font-weight: 500;
}

/* Buttons */
.btn {
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
}

/* Table Enhancements */
.table-hover tbody tr:hover {
    background-color: rgba(0,0,0,0.02);
    transform: scale(1.01);
    transition: all 0.2s ease;
}

/* Problem Status Colors */
.problem-reported { border-left: 4px solid #ffc107; }
.problem-in-progress { border-left: 4px solid #17a2b8; }
.problem-resolved { border-left: 4px solid #28a745; }

/* Responsive Enhancements - Improved */
@media (max-width: 768px) {
    body {
        overflow-x: hidden;
    }

    .container-fluid {
        padding-left: 10px !important;
        padding-right: 10px !important;
        max-width: 100% !important;
    }

    .row {
        margin-left: 0 !important;
        margin-right: 0 !important;
    }

    .col-md-3, .col-md-6, .col-md-8, .col-md-4, .col-lg-8, .col-lg-4 {
        padding-left: 5px !important;
        padding-right: 5px !important;
    }

    /* Welcome header responsive */
    .bg-gradient-success .card-body {
        padding: 1rem;
    }

    .header-left {
        flex-direction: column;
        text-align: center;
        gap: 8px;
    }

    .user-avatar {
        margin-bottom: 0.5rem;
        align-self: center;
    }

    .user-avatar img,
    .user-avatar .avatar-circle {
        width: 60px !important;
        height: 60px !important;
        font-size: 1.5rem !important;
    }

    .bg-gradient-success h3 {
        font-size: 1.3rem;
        margin-bottom: 0.5rem;
    }

    .bg-gradient-success p {
        font-size: 0.85rem;
        line-height: 1.3;
    }

    .user-dashboard-illustration {
        display: none;
    }

    /* Stats cards responsive */
    .stat-card {
        margin-bottom: 0.75rem;
    }

    .stat-card .card-body {
        padding: 1rem 0.75rem;
    }
    
    .counter {
        font-size: 1.4rem;
    }

    .stat-card h3 {
        margin-bottom: 0.25rem;
    }

    .stat-card p {
        font-size: 0.85rem;
        margin-bottom: 0.25rem;
    }

    .stat-trend small {
        font-size: 0.7rem;
    }

    /* Update request cards */
    .col-md-6 .card {
        margin-bottom: 0.75rem;
    }

    .col-md-6 .card .card-body {
        padding: 1rem 0.75rem;
    }

    /* Table responsive */
    .table-responsive {
        font-size: 0.85rem;
    }

    .table td, .table th {
        padding: 0.5rem 0.25rem;
    }

    /* Profile card responsive */
    .col-lg-4 .card {
        margin-bottom: 1rem;
    }

    .col-lg-4 .card .card-body {
        padding: 1rem 0.75rem;
    }

    /* Location details responsive */
    .location-card {
        margin-bottom: 0.75rem;
    }

    .location-card .col-md-3 {
        margin-bottom: 0.75rem;
    }

    .location-card .text-center {
        padding: 1rem 0.5rem;
    }

    .location-card .fa-2x {
        font-size: 1.5rem !important;
    }

    .location-card h6 {
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }

    .location-card p {
        font-size: 0.85rem;
        margin-bottom: 0.25rem;
    }

    .location-card small {
        font-size: 0.7rem;
    }

    /* Quick actions responsive */
    .d-grid .btn {
        padding: 0.6rem 1rem;
        font-size: 0.85rem;
    }

    /* Dropdown responsive */
    .dropdown-menu {
        font-size: 0.85rem;
    }

    .dropdown-item {
        padding: 0.5rem 1rem;
    }
}

@media (max-width: 576px) {
    .container-fluid {
        padding-left: 5px !important;
        padding-right: 5px !important;
    }

    .col-md-3, .col-md-6, .col-md-8, .col-md-4, .col-lg-8, .col-lg-4 {
        padding-left: 2.5px !important;
        padding-right: 2.5px !important;
    }

    /* Welcome header extra small */
    .bg-gradient-success .card-body {
        padding: 0.75rem;
    }

    .bg-gradient-success h3 {
        font-size: 1.1rem;
    }

    .bg-gradient-success p {
        font-size: 0.8rem;
    }

    .user-avatar img,
    .user-avatar .avatar-circle {
        width: 50px !important;
        height: 50px !important;
        font-size: 1.2rem !important;
    }

    /* Stats cards extra small */
    .stat-card .card-body {
        padding: 0.75rem 0.5rem;
    }
    
    .counter {
        font-size: 1.2rem;
    }

    .stat-card .fa-2x {
        font-size: 1.3rem !important;
    }

    .stat-card p {
        font-size: 0.8rem;
    }

    .stat-trend small {
        font-size: 0.65rem;
    }

    /* Table extra responsive */
    .table-responsive {
        font-size: 0.8rem;
    }

    .table td, .table th {
        padding: 0.4rem 0.2rem;
    }

    .btn-sm {
        padding: 0.2rem 0.4rem;
        font-size: 0.7rem;
    }

    /* Location cards stack */
    .location-card .col-md-3 {
        margin-bottom: 0.5rem;
    }

    .location-card .text-center {
        padding: 0.75rem 0.25rem;
    }

    .location-card .fa-2x {
        font-size: 1.2rem !important;
    }

    .location-card h6 {
        font-size: 0.8rem;
    }

    .location-card p {
        font-size: 0.8rem;
    }

    .location-card small {
        font-size: 0.65rem;
    }

    /* Profile sidebar responsive */
    .avatar-circle {
        width: 60px !important;
        height: 60px !important;
        font-size: 1.5rem !important;
    }

    .card-header h5 {
        font-size: 1rem;
    }

    .card-body h6 {
        font-size: 0.9rem;
    }

    /* Quick actions extra small */
    .d-grid .btn {
        padding: 0.5rem 0.75rem;
        font-size: 0.8rem;
    }

    /* House problems responsive */
    .house-problem-card {
        padding: 0.75rem;
    }

    .house-problem-card .fw-bold {
        font-size: 0.85rem;
    }

    .house-problem-card small {
        font-size: 0.7rem;
    }

    .house-problem-card .badge {
        font-size: 0.65rem;
    }
}

/* Landscape mobile optimization */
@media (max-width: 768px) and (orientation: landscape) {
    .bg-gradient-success .row .col-md-8 {
        text-align: left;
    }

    .user-dashboard-illustration {
        display: block;
        opacity: 0.3;
    }

    .stats-container {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.5rem;
    }

    .stat-card .card-body {
        padding: 0.75rem 0.5rem;
    }

    .counter {
        font-size: 1.1rem;
    }
}

/* Loading Animation */
.loading {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Stat Trend */
.stat-trend {
    margin-top: 8px;
    font-size: 0.75rem;
}

/* House Problem Cards */
.house-problem-card {
    transition: all 0.3s ease;
    border-radius: 8px;
    border-left: 4px solid transparent;
}

.house-problem-card:hover {
    background-color: rgba(0,0,0,0.02);
    transform: translateX(5px);
}

/* Update Request Cards */
.update-request-card {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    border-radius: 12px;
    transition: all 0.3s ease;
}

.update-request-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(33, 150, 243, 0.2);
}
</style>
@endpush

@push('scripts')
<script>
// Counter Animation
function animateCounters() {
    const counters = document.querySelectorAll('.counter');
    
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'));
        const increment = target / 100;
        let current = 0;
        
        const updateCounter = () => {
            if (current < target) {
                current += increment;
                counter.textContent = Math.ceil(current);
                setTimeout(updateCounter, 20);
            } else {
                counter.textContent = target;
            }
        };
        
        updateCounter();
    });
}

// Real-time Clock
function updateUserClock() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('en-US', {
        hour: 'numeric',
        minute: '2-digit',
        hour12: true
    });
    
    const clockElement = document.getElementById('userCurrentTime');
    if (clockElement) {
        clockElement.textContent = timeString;
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Animate counters with delay
    setTimeout(animateCounters, 500);
    
    // Update clock every minute
    updateUserClock();
    setInterval(updateUserClock, 60000);
    
    // Add loading states to buttons
    document.querySelectorAll('.btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (!this.classList.contains('dropdown-toggle')) {
                const originalContent = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner loading me-2"></i>Loading...';
                
                // Reset after navigation
                setTimeout(() => {
                    this.innerHTML = originalContent;
                }, 1000);
            }
        });
    });
    
    // Add problem status classes to table rows
    document.querySelectorAll('.table tbody tr').forEach(row => {
        const statusBadge = row.querySelector('.badge');
        if (statusBadge) {
            const status = statusBadge.textContent.toLowerCase().trim();
            if (status.includes('reported')) {
                row.classList.add('problem-reported');
            } else if (status.includes('progress')) {
                row.classList.add('problem-in-progress');
            } else if (status.includes('resolved')) {
                row.classList.add('problem-resolved');
            }
        }
    });
    
    // Add refresh functionality
    const refreshBtn = document.createElement('button');
    refreshBtn.className = 'btn btn-sm btn-outline-light position-absolute';
    refreshBtn.style.cssText = 'top: 10px; right: 10px; z-index: 1000;';
    refreshBtn.innerHTML = '<i class="fas fa-sync-alt"></i>';
    refreshBtn.title = 'Refresh Dashboard';
    refreshBtn.onclick = () => window.location.reload();
    
    const headerCard = document.querySelector('.bg-gradient-success');
    if (headerCard) {
        headerCard.style.position = 'relative';
        headerCard.appendChild(refreshBtn);
    }
});

// Greeting based on time
function updateGreeting() {
    const hour = new Date().getHours();
    let greeting = 'Welcome back';
    
    if (hour < 12) {
        greeting = 'Good morning';
    } else if (hour < 17) {
        greeting = 'Good afternoon';
    } else {
        greeting = 'Good evening';
    }
    
    const greetingElement = document.querySelector('h3');
    if (greetingElement && greetingElement.textContent.includes('Welcome')) {
        greetingElement.textContent = greetingElement.textContent.replace('Welcome back', greeting);
    }
}

// Update greeting on load
document.addEventListener('DOMContentLoaded', updateGreeting);
</script>
@endpush