@extends('layouts.app')

@section('title', 'User Details - Boothcare')
@section('page-title', 'User: ' . $user->name)

@push('styles')
<style>
    body {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    .professional-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 2rem;
        color: white;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        margin-bottom: 2rem;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: 700;
        color: white;
        border: 4px solid rgba(255, 255, 255, 0.3);
    }

    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    .header-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
        color: white;
    }

    .header-subtitle {
        font-size: 1.2rem;
        opacity: 0.9;
        color: white;
    }

    .card {
        border: 1px solid #e5e7eb;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    .card-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 1px solid #e5e7eb;
    }

    .info-item {
        padding: 1rem;
        background: #f8fafc;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        margin-bottom: 1rem;
    }

    .info-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
    }

    .info-value {
        font-size: 1rem;
        font-weight: 500;
        color: #1f2937;
    }

    .role-admin { background: #ef4444; color: white; }
    .role-sub_admin { background: #f59e0b; color: white; }
    .role-user { background: #10b981; color: white; }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="professional-header">
        <div class="d-flex align-items-center mb-3">
            <a href="{{ route('users.index') }}" class="btn btn-outline-light btn-sm me-3">
                <i class="fas fa-arrow-left me-1"></i> Back to Users
            </a>
            <span class="badge role-{{ $user->role }} me-2">
                {{ ucfirst(str_replace('_', ' ', $user->role)) }}
            </span>
            @if($user->id === auth()->id())
                <span class="badge bg-info">Current User</span>
            @endif
        </div>
        
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center">
                    <div class="profile-avatar me-4">
                        @if($user->profile_photo)
                            <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile">
                        @else
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        @endif
                    </div>
                    <div>
                        <h1 class="header-title">{{ $user->name }}</h1>
                        <p class="header-subtitle mb-0">
                            {{ ucfirst(str_replace('_', ' ', $user->role)) }} Account
                            @if($user->date_of_birth) • {{ $user->date_of_birth->age }} years old @endif
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('users.edit', $user) }}" class="btn btn-light btn-lg">
                    <i class="fas fa-edit me-1"></i> Edit User
                </a>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="row">
        <div class="col-lg-8">
            <!-- Account Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user-cog text-primary me-2"></i>Account Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">Email Address</div>
                                <div class="info-value">
                                    <i class="fas fa-envelope text-info me-2"></i>
                                    <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">Phone Number</div>
                                <div class="info-value">
                                    @if($user->phone)
                                        <i class="fas fa-phone text-success me-2"></i>
                                        <a href="tel:{{ $user->phone }}">{{ $user->phone }}</a>
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
                                    @if($user->date_of_birth)
                                        <i class="fas fa-calendar text-primary me-2"></i>
                                        {{ $user->date_of_birth->format('d M Y') }}
                                    @else
                                        <span class="text-muted">Not provided</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">Account Status</div>
                                <div class="info-value">
                                    @if($user->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Family Member Info -->
            @if($user->familyMember)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-home text-primary me-2"></i>Family Member Profile</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if($user->familyMember->house && $user->familyMember->house->booth && $user->familyMember->house->booth->area)
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">Area</div>
                                        <div class="info-value">{{ $user->familyMember->house->booth->area->area_name }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">Booth</div>
                                        <div class="info-value">{{ $user->familyMember->house->booth->booth_number }} - {{ $user->familyMember->house->booth->booth_name }}</div>
                                    </div>
                                </div>
                            @endif
                            @if($user->familyMember->house)
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">House Number</div>
                                        <div class="info-value">{{ $user->familyMember->house->house_number }}</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('members.show', $user->familyMember) }}" class="btn btn-outline-primary">
                                <i class="fas fa-user me-2"></i>View Full Family Profile
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            This user hasn't completed their family member profile yet.
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <!-- Activity Stats -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-bar text-primary me-2"></i>Activity Statistics</h5>
                </div>
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col-6">
                            <h3 class="text-danger">{{ $user->reportedProblems->count() }}</h3>
                            <small>Problems Reported</small>
                        </div>
                        <div class="col-6">
                            <h3 class="text-info">{{ $user->assignedProblems->count() }}</h3>
                            <small>Problems Assigned</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-bolt text-primary me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-outline-primary">
                            <i class="fas fa-edit me-2"></i>Edit User Account
                        </a>
                        @if($user->familyMember)
                            <a href="{{ route('members.show', $user->familyMember) }}" class="btn btn-outline-info">
                                <i class="fas fa-user me-2"></i>View Family Profile
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection