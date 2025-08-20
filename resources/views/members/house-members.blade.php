@extends('layouts.app')

@section('title', 'House Members - Boothcare')
@section('page-title', 'House: ' . $house->house_number . ' Members')

@push('styles')
<style>
    /* Mobile-First Responsive Design */
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --card-shadow: 0 4px 20px rgba(0,0,0,0.08);
        --card-shadow-hover: 0 8px 30px rgba(0,0,0,0.12);
    }

    body {
        background: #f8fafc;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    /* Mobile Header */
    .mobile-header {
        background: var(--primary-gradient);
        color: white;
        padding: 24px 20px;
        margin: -16px -16px 24px -16px;
        border-radius: 0 0 30px 30px;
        box-shadow: 0 8px 30px rgba(102, 126, 234, 0.3);
        position: relative;
        overflow: hidden;
    }

    .mobile-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><defs><radialGradient id="star" cx="50%" cy="50%" r="50%"><stop offset="0%" stop-color="white" stop-opacity="0.3"/><stop offset="100%" stop-color="white" stop-opacity="0"/></radialGradient></defs><circle cx="30" cy="40" r="1.5" fill="url(%23star)"/><circle cx="170" cy="60" r="1" fill="url(%23star)"/><circle cx="80" cy="90" r="0.8" fill="url(%23star)"/><circle cx="140" cy="130" r="1.2" fill="url(%23star)"/></svg>');
        opacity: 0.6;
    }

    .mobile-header-content {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .mobile-back-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        backdrop-filter: blur(10px);
        text-decoration: none;
    }

    .mobile-header-text {
        flex: 1;
        text-align: center;
    }

    .mobile-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0 0 4px 0;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .mobile-subtitle {
        opacity: 0.9;
        font-size: 0.9rem;
        margin: 0;
    }

    /* Mobile House Info Card */
    .mobile-house-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: var(--card-shadow);
    }

    .mobile-house-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .mobile-house-info {
        display: grid;
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .mobile-house-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
    }

    .mobile-house-label {
        font-weight: 500;
        color: #666;
        font-size: 0.9rem;
    }

    .mobile-house-value {
        font-weight: 600;
        color: #1a1a1a;
        font-size: 0.9rem;
        text-align: right;
    }

    /* Mobile Member Cards */
    .mobile-member-card {
        background: white;
        border-radius: 16px;
        margin-bottom: 12px;
        box-shadow: var(--card-shadow);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .mobile-member-card.head {
        border: 2px solid #ffc107;
        box-shadow: 0 4px 20px rgba(255, 193, 7, 0.2);
    }

    .mobile-member-card:active {
        transform: scale(0.98);
    }

    .mobile-member-header {
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .mobile-member-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
        color: white;
        background: var(--primary-gradient);
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        position: relative;
        flex-shrink: 0;
    }

    .mobile-member-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    .mobile-member-info {
        flex: 1;
        min-width: 0;
    }

    .mobile-member-name {
        font-size: 1rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 2px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .mobile-member-relation {
        font-size: 0.8rem;
        color: #666;
        margin-bottom: 4px;
    }

    .mobile-member-details {
        font-size: 0.75rem;
        color: #888;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .mobile-member-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        margin-top: 8px;
    }

    .mobile-badge {
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .mobile-badge.head {
        background: #ffc107;
        color: #000;
    }

    .mobile-badge.gender {
        background: #e3f2fd;
        color: #1976d2;
    }

    .mobile-badge.age {
        background: #f3e5f5;
        color: #7b1fa2;
    }

    .mobile-member-actions {
        padding: 0 16px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .mobile-member-stats {
        display: flex;
        gap: 16px;
    }

    .mobile-stat {
        text-align: center;
    }

    .mobile-stat-number {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 2px;
    }

    .mobile-stat-label {
        font-size: 0.7rem;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .mobile-action-buttons {
        display: flex;
        gap: 8px;
    }

    .mobile-action-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .mobile-action-btn.primary {
        background: #e3f2fd;
        color: #1976d2;
    }

    .mobile-action-btn.warning {
        background: #fff3e0;
        color: #f57c00;
    }

    .mobile-action-btn.danger {
        background: #ffebee;
        color: #d32f2f;
    }

    .mobile-action-btn:active {
        transform: scale(0.9);
    }

    /* Floating Add Button */
    .mobile-fab {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: var(--primary-gradient);
        border: none;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 4px 16px rgba(102, 126, 234, 0.4);
        z-index: 1000;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .mobile-fab:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        color: white;
    }

    .mobile-fab:active {
        transform: scale(0.95);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        background: white;
        border-radius: 16px;
        box-shadow: var(--card-shadow);
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        font-size: 2rem;
        color: #9ca3af;
    }

    .empty-state-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }

    .empty-state-text {
        color: #6b7280;
        margin-bottom: 20px;
    }

    .empty-state-btn {
        background: var(--primary-gradient);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    /* Desktop Styles */
    @media (min-width: 769px) {
        .mobile-view {
            display: none !important;
        }
        .desktop-view {
            display: block !important;
        }
        .mobile-fab {
            display: none;
        }
    }

    @media (max-width: 768px) {
        .desktop-view {
            display: none !important;
        }
        .mobile-view {
            display: block !important;
        }
        
        .container-fluid {
            padding: 16px;
        }
    }

    /* Desktop fallback styles */
    .desktop-view .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .desktop-view .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.12);
    }

    .desktop-view .member-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .desktop-view .member-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .desktop-view .avatar-circle {
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Mobile View -->
    <div class="mobile-view">
        <!-- Mobile Header -->
        <div class="mobile-header">
            <div class="mobile-header-content">
                <a href="{{ route('members.index') }}" class="mobile-back-btn">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="mobile-header-text">
                    <h1 class="mobile-title">House {{ $house->house_number }}</h1>
                    <div class="mobile-subtitle">{{ $members->count() }} family members</div>
                </div>
            </div>
        </div>

        <!-- Mobile House Info -->
        <div class="mobile-house-card">
            <h3 class="mobile-house-title">
                <i class="fas fa-home text-primary"></i>
                House Information
            </h3>
            
            <div class="mobile-house-info">
                <div class="mobile-house-item">
                    <span class="mobile-house-label">House Number</span>
                    <span class="mobile-house-value">{{ $house->house_number }}</span>
                </div>
                
                <div class="mobile-house-item">
                    <span class="mobile-house-label">Address</span>
                    <span class="mobile-house-value">{{ $house->address }}</span>
                </div>
                
                <div class="mobile-house-item">
                    <span class="mobile-house-label">Area</span>
                    <span class="mobile-house-value">{{ $house->booth->area->area_name }}</span>
                </div>
                
                <div class="mobile-house-item">
                    <span class="mobile-house-label">Booth</span>
                    <span class="mobile-house-value">{{ $house->booth->booth_number }} - {{ $house->booth->booth_name }}</span>
                </div>
                
                <div class="mobile-house-item">
                    <span class="mobile-house-label">Total Members</span>
                    <span class="mobile-house-value">{{ $members->count() }}</span>
                </div>
                
                @if($house->familyHead)
                <div class="mobile-house-item">
                    <span class="mobile-house-label">House Head</span>
                    <span class="mobile-house-value">
                        <a href="{{ route('members.show', $house->familyHead) }}" style="color: #667eea; text-decoration: none; font-weight: 600;">
                            {{ $house->familyHead->name }}
                        </a>
                    </span>
                </div>
                @endif
            </div>
        </div>

        <!-- Mobile Members List -->
        @if($members->count() > 0)
            @foreach($members as $member)
            <div class="mobile-member-card {{ $member->is_family_head ? 'head' : '' }}" onclick="window.location.href='{{ route('members.show', $member) }}'">
                <div class="mobile-member-header">
                    <div class="mobile-member-avatar">
                        @if($member->profile_photo)
                            <img src="{{ asset('storage/' . $member->profile_photo) }}" alt="Profile">
                        @else
                            {{ strtoupper(substr($member->name, 0, 1)) }}
                        @endif
                    </div>
                    
                    <div class="mobile-member-info">
                        <div class="mobile-member-name">
                            {{ Str::limit($member->name, 18) }}
                        </div>
                        <div class="mobile-member-relation">
                            {{ $member->relation_to_head }}
                        </div>
                        <div class="mobile-member-details">
                            @if($member->age)
                                <span><i class="fas fa-birthday-cake"></i> {{ $member->age }}y</span>
                            @endif
                            @if($member->phone)
                                <span><i class="fas fa-phone"></i> {{ Str::limit($member->phone, 12) }}</span>
                            @endif
                            @if($member->occupation)
                                <span><i class="fas fa-briefcase"></i> {{ Str::limit($member->occupation, 10) }}</span>
                            @endif
                        </div>
                        
                        <div class="mobile-member-badges">
                            @if($member->is_family_head)
                                <span class="mobile-badge head">House Head</span>
                            @endif
                            @if($member->gender)
                                <span class="mobile-badge gender">{{ ucfirst($member->gender) }}</span>
                            @endif
                            @if($member->age)
                                <span class="mobile-badge age">{{ $member->age }} years</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="mobile-member-actions">
                    <div class="mobile-member-stats">
                        <div class="mobile-stat">
                            <div class="mobile-stat-number text-danger">{{ $member->problems->count() }}</div>
                            <div class="mobile-stat-label">Problems</div>
                        </div>
                        <div class="mobile-stat">
                            <div class="mobile-stat-number text-warning">{{ $member->active_problems ?? 0 }}</div>
                            <div class="mobile-stat-label">Active</div>
                        </div>
                    </div>
                    
                    <div class="mobile-action-buttons">
                        <a href="{{ route('members.show', $member) }}" class="mobile-action-btn primary" onclick="event.stopPropagation();">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('members.edit', $member) }}" class="mobile-action-btn warning" onclick="event.stopPropagation();">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="{{ route('problems.create') }}?member_id={{ $member->id }}" class="mobile-action-btn danger" onclick="event.stopPropagation();">
                            <i class="fas fa-plus"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="empty-state-title">No Members Found</h3>
                <p class="empty-state-text">This house doesn't have any registered members yet.</p>
                <a href="{{ route('members.create') }}?house_id={{ $house->id }}" class="empty-state-btn">
                    <i class="fas fa-plus"></i>
                    Add First Member
                </a>
            </div>
        @endif

        <!-- Floating Add Button -->
        <a href="{{ route('members.create') }}?house_id={{ $house->id }}" class="mobile-fab">
            <i class="fas fa-plus"></i>
        </a>
    </div>

    <!-- Desktop View -->
    <div class="desktop-view">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('members.index') }}">Members</a></li>
                <li class="breadcrumb-item active">House {{ $house->house_number }} Members</li>
            </ol>
        </nav>

        <!-- House Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-home me-2"></i>
                    House Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>House Number:</strong> {{ $house->house_number }}</p>
                        <p><strong>Address:</strong> {{ $house->address }}</p>
                        <p><strong>Area:</strong> {{ $house->booth->area->area_name }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Booth:</strong> {{ $house->booth->booth_number }} - {{ $house->booth->booth_name }}</p>
                        <p><strong>Total Members:</strong> {{ $members->count() }}</p>
                        @if($house->familyHead)
                            <p><strong>House Head:</strong> 
                                <a href="{{ route('members.show', $house->familyHead) }}" class="text-decoration-none">
                                    {{ $house->familyHead->name }}
                                </a>
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- House Members -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i>
                    House Members ({{ $members->count() }})
                </h5>
                <a href="{{ route('members.create') }}?house_id={{ $house->id }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>
                    Add Member
                </a>
            </div>
            <div class="card-body">
                @if($members->count() > 0)
                    <div class="row">
                        @foreach($members as $member)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card member-card h-100 {{ $member->is_family_head ? 'border-warning' : 'border-light' }}">
                                    <div class="card-body text-center">
                                        <!-- Profile Photo -->
                                        @if($member->profile_photo)
                                            <img src="{{ asset('storage/' . $member->profile_photo) }}" 
                                                 class="rounded-circle mb-3" width="80" height="80" alt="Profile">
                                        @else
                                            <div class="avatar-circle bg-primary text-white mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                                                {{ strtoupper(substr($member->name, 0, 1)) }}
                                            </div>
                                        @endif

                                        <!-- Member Info -->
                                        <h5 class="mb-2">{{ $member->name }}</h5>
                                        
                                        @if($member->is_family_head)
                                            <span class="badge bg-warning mb-2">House Head</span>
                                        @endif
                                        
                                        <p class="text-muted mb-2">{{ $member->relation_to_head }}</p>
                                        
                                        <!-- Details -->
                                        <div class="small text-muted mb-3">
                                            @if($member->age)
                                                <div><i class="fas fa-birthday-cake me-1"></i>{{ $member->age }} years old</div>
                                            @endif
                                            @if($member->phone)
                                                <div><i class="fas fa-phone me-1"></i>{{ $member->phone }}</div>
                                            @endif
                                            @if($member->occupation)
                                                <div><i class="fas fa-briefcase me-1"></i>{{ $member->occupation }}</div>
                                            @endif
                                        </div>

                                        <!-- Stats -->
                                        <div class="row text-center mb-3">
                                            <div class="col-6">
                                                <small class="text-muted">Problems</small>
                                                <div class="fw-bold text-danger">{{ $member->problems->count() }}</div>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">Active</small>
                                                <div class="fw-bold text-warning">{{ $member->active_problems ?? 0 }}</div>
                                            </div>
                                        </div>

                                        <!-- Actions -->
                                        <div class="d-grid gap-2">
                                            <a href="{{ route('members.show', $member) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye me-1"></i>View Details
                                            </a>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('members.edit', $member) }}" class="btn btn-outline-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('problems.create') }}?member_id={{ $member->id }}" class="btn btn-outline-danger btn-sm">
                                                    <i class="fas fa-plus"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No Members Found</h5>
                        <p class="text-muted">This house doesn't have any registered members yet.</p>
                        <a href="{{ route('members.create') }}?house_id={{ $house->id }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            Add First Member
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection