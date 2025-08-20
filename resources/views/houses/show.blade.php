@extends('layouts.app')

@section('title', 'House Details - Boothcare')
@section('page-title', 'House: ' . $house->house_number)

@push('styles')
<style>
    /* Modern House Details Page */
    :root {
        --primary-gradient: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        --success-gradient: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        --warning-gradient: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
        --danger-gradient: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        --card-shadow: 0 4px 20px rgba(0,0,0,0.08);
        --card-shadow-hover: 0 8px 30px rgba(0,0,0,0.12);
    }

    body {
        background: linear-gradient(135deg, #f8fafc 0%, #e3f2fd 100%);
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        min-height: 100vh;
    }

    /* Beautiful Header */
    .house-header {
        background: var(--primary-gradient);
        color: white;
        padding: 32px 20px;
        margin: -16px -16px 32px -16px;
        border-radius: 0 0 30px 30px;
        box-shadow: 0 8px 30px rgba(23, 162, 184, 0.3);
        position: relative;
        overflow: hidden;
    }

    .house-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><defs><radialGradient id="star" cx="50%" cy="50%" r="50%"><stop offset="0%" stop-color="white" stop-opacity="0.4"/><stop offset="100%" stop-color="white" stop-opacity="0"/></radialGradient></defs><circle cx="40" cy="30" r="1.5" fill="url(%23star)" opacity="0.8"><animate attributeName="opacity" values="0.3;0.8;0.3" dur="3s" repeatCount="indefinite"/></circle><circle cx="160" cy="50" r="1" fill="url(%23star)" opacity="0.6"><animate attributeName="opacity" values="0.2;0.7;0.2" dur="4s" repeatCount="indefinite"/></circle><circle cx="90" cy="80" r="0.8" fill="url(%23star)" opacity="0.7"><animate attributeName="opacity" values="0.4;0.9;0.4" dur="2.5s" repeatCount="indefinite"/></circle><circle cx="150" cy="120" r="1.2" fill="url(%23star)" opacity="0.5"><animate attributeName="opacity" values="0.3;0.8;0.3" dur="3.5s" repeatCount="indefinite"/></circle></svg>');
        opacity: 0.8;
        animation: twinkle 6s ease-in-out infinite;
    }

    @keyframes twinkle {
        0%, 100% { opacity: 0.8; }
        50% { opacity: 1; }
    }

    .house-header-content {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .house-back-btn {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        backdrop-filter: blur(10px);
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .house-back-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.05);
        color: white;
    }

    .house-header-text {
        flex: 1;
    }

    .house-title {
        font-size: 1.6rem;
        font-weight: 700;
        margin: 0 0 6px 0;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .house-subtitle {
        opacity: 0.9;
        font-size: 0.95rem;
        margin: 0 0 12px 0;
        font-weight: 400;
    }

    .house-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 12px;
    }

    .house-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Modern Cards */
    .modern-card {
        background: white;
        border-radius: 20px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: var(--card-shadow);
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .modern-card:hover {
        box-shadow: var(--card-shadow-hover);
        transform: translateY(-2px);
    }

    .card-header-modern {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 2px solid #f1f3f4;
    }

    .card-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: white;
    }

    .card-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0;
    }

    .card-subtitle {
        font-size: 0.9rem;
        color: #6b7280;
        margin: 4px 0 0 0;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        text-align: center;
        box-shadow: var(--card-shadow);
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--card-shadow-hover);
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
        font-size: 1.1rem;
        color: white;
    }

    .stat-number {
        font-size: 1.8rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 4px;
    }

    .stat-label {
        font-size: 0.8rem;
        color: #6b7280;
        font-weight: 600;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 20px;
    }

    .action-btn {
        border: none;
        border-radius: 12px;
        padding: 12px 20px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        min-height: 44px;
    }

    .action-btn-primary {
        background: var(--primary-gradient);
        color: white;
        box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);
    }

    .action-btn-warning {
        background: var(--warning-gradient);
        color: white;
        box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        color: white;
    }

    /* Member Cards */
    .members-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }

    .member-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--card-shadow);
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .member-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--card-shadow-hover);
    }

    .member-header {
        padding: 20px;
        background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
        color: white;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .member-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        font-weight: 700;
        backdrop-filter: blur(10px);
    }

    .member-info {
        flex: 1;
    }

    .member-name {
        font-size: 1.1rem;
        font-weight: 700;
        margin: 0 0 4px 0;
        text-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }

    .member-relation {
        font-size: 0.85rem;
        opacity: 0.9;
        margin: 0;
    }

    .member-body {
        padding: 20px;
    }

    .member-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 16px;
    }

    .member-detail {
        font-size: 0.85rem;
    }

    .member-detail-label {
        color: #6b7280;
        font-weight: 600;
        margin-bottom: 2px;
    }

    .member-detail-value {
        color: #1f2937;
        font-weight: 600;
    }

    .member-actions {
        display: flex;
        gap: 8px;
    }

    .member-btn {
        flex: 1;
        border: none;
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 0.8rem;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .member-btn-primary {
        background: #e3f2fd;
        color: #1976d2;
    }

    .member-btn-danger {
        background: #ffebee;
        color: #d32f2f;
    }

    .member-btn:hover {
        transform: translateY(-1px);
        color: inherit;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 20px;
        box-shadow: var(--card-shadow);
    }

    .empty-icon {
        font-size: 4rem;
        color: #d1d5db;
        margin-bottom: 20px;
    }

    .empty-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #6b7280;
        margin-bottom: 8px;
    }

    .empty-subtitle {
        color: #9ca3af;
        margin-bottom: 24px;
        line-height: 1.5;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 16px;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .members-grid {
            grid-template-columns: 1fr;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .action-btn {
            width: 100%;
        }
        
        .member-details {
            grid-template-columns: 1fr;
        }
    }

    /* Problem Badge */
    .problem-badge {
        background: var(--danger-gradient);
        color: white;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    /* Head Badge */
    .head-badge {
        background: #1a1a1a;
        color: white;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Beautiful Header -->
    <div class="house-header">
        <div class="house-header-content">
            <a href="{{ route('houses.index') }}" class="house-back-btn">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="house-header-text">
                <h1 class="house-title">House {{ $house->house_number }}</h1>
                <div class="house-subtitle">
                    <i class="fas fa-map-marker-alt"></i>
                    {{ Str::limit($house->address, 60) }}
                </div>
                <div class="house-badges">
                    <span class="house-badge">
                        <i class="fas fa-building"></i>
                        Booth {{ $house->booth->booth_number ?? 'N/A' }}
                    </span>
                    <span class="house-badge">
                        <i class="fas fa-map-marked-alt"></i>
                        {{ $house->booth->area->area_name ?? 'N/A' }}
                    </span>
                    <span class="house-badge">
                        <i class="fas fa-{{ $house->is_active ? 'check-circle' : 'times-circle' }}"></i>
                        {{ $house->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- House Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--warning-gradient);">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-number">{{ $house->members->count() }}</div>
            <div class="stat-label">Total Members</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--danger-gradient);">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-number">{{ $house->members->sum(function($member) { return $member->problems->count(); }) }}</div>
            <div class="stat-label">Problems</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--primary-gradient);">
                <i class="fas fa-mars"></i>
            </div>
            <div class="stat-number">{{ $house->members->where('gender', 'male')->count() }}</div>
            <div class="stat-label">Male</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #e91e63 0%, #ad1457 100%);">
                <i class="fas fa-venus"></i>
            </div>
            <div class="stat-number">{{ $house->members->where('gender', 'female')->count() }}</div>
            <div class="stat-label">Female</div>
        </div>
    </div>

    <!-- House Information -->
    <div class="modern-card">
        <div class="card-header-modern">
            <div class="card-icon" style="background: var(--primary-gradient);">
                <i class="fas fa-home"></i>
            </div>
            <div>
                <h2 class="card-title">House Information</h2>
                <div class="card-subtitle">Detailed information about this house</div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            @if($house->owner_name)
            <div>
                <div style="font-size: 0.85rem; color: #6b7280; font-weight: 600; margin-bottom: 4px;">Owner</div>
                <div style="font-weight: 600; color: #1f2937;">{{ $house->owner_name }}</div>
                @if($house->contact_number)
                    <div style="font-size: 0.8rem; color: #6b7280; margin-top: 2px;">{{ $house->contact_number }}</div>
                @endif
            </div>
            @endif
            
            @if($house->house_type)
            <div>
                <div style="font-size: 0.85rem; color: #6b7280; font-weight: 600; margin-bottom: 4px;">House Type</div>
                <span style="background: #f3f4f6; color: #374151; padding: 4px 12px; border-radius: 12px; font-size: 0.8rem; font-weight: 600;">
                    {{ ucfirst($house->house_type) }}
                </span>
            </div>
            @endif
            
            @if($house->area)
            <div>
                <div style="font-size: 0.85rem; color: #6b7280; font-weight: 600; margin-bottom: 4px;">Area/Locality</div>
                <div style="font-weight: 600; color: #1f2937;">{{ $house->area }}</div>
            </div>
            @endif
            
            @if($house->pincode)
            <div>
                <div style="font-size: 0.85rem; color: #6b7280; font-weight: 600; margin-bottom: 4px;">PIN Code</div>
                <div style="font-weight: 600; color: #1f2937;">{{ $house->pincode }}</div>
            </div>
            @endif
        </div>
        
        @if($house->notes)
        <div style="margin-top: 20px; padding-top: 20px; border-top: 2px solid #f1f3f4;">
            <div style="font-size: 0.85rem; color: #6b7280; font-weight: 600; margin-bottom: 8px;">Notes</div>
            <div style="color: #1f2937; line-height: 1.5;">{{ $house->notes }}</div>
        </div>
        @endif
        
        <div class="action-buttons">
            <a href="{{ route('houses.edit', $house) }}" class="action-btn action-btn-warning">
                <i class="fas fa-edit"></i> Edit House
            </a>
            <a href="{{ route('members.create') }}?house_id={{ $house->id }}" class="action-btn action-btn-primary">
                <i class="fas fa-plus"></i> Add Member
            </a>
        </div>
    </div>

    <!-- Family Members -->
    <div class="modern-card">
        <div class="card-header-modern">
            <div class="card-icon" style="background: var(--warning-gradient);">
                <i class="fas fa-users"></i>
            </div>
            <div style="flex: 1;">
                <h2 class="card-title">Family Members ({{ $house->members->count() }})</h2>
                <div class="card-subtitle">All registered members in this house</div>
            </div>
            <a href="{{ route('members.create') }}?house_id={{ $house->id }}" class="action-btn action-btn-primary" style="margin: 0;">
                <i class="fas fa-plus"></i> Add Member
            </a>
        </div>

        @if($house->members->count() > 0)
            <div class="members-grid">
                @foreach($house->members as $member)
                <div class="member-card">
                    <div class="member-header">
                        <div class="member-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="member-info">
                            <h3 class="member-name">{{ $member->name }}</h3>
                            <div class="member-relation">{{ ucfirst($member->relation_to_head) }}</div>
                        </div>
                        @if($member->relation_to_head === 'head')
                            <span class="head-badge">Head</span>
                        @endif
                    </div>
                    
                    <div class="member-body">
                        <div class="member-details">
                            <div class="member-detail">
                                <div class="member-detail-label">Age</div>
                                <div class="member-detail-value">
                                    {{ $member->age ?? 'N/A' }}
                                    <i class="fas fa-{{ $member->gender === 'male' ? 'mars' : 'venus' }} ms-2" 
                                       style="color: {{ $member->gender === 'male' ? '#17a2b8' : '#e91e63' }};"></i>
                                </div>
                            </div>
                            
                            @if($member->phone)
                            <div class="member-detail">
                                <div class="member-detail-label">Phone</div>
                                <div class="member-detail-value">{{ $member->phone }}</div>
                            </div>
                            @endif
                            
                            @if($member->aadhar_number)
                            <div class="member-detail">
                                <div class="member-detail-label">Aadhaar</div>
                                <div class="member-detail-value">{{ $member->aadhar_number }}</div>
                            </div>
                            @endif
                            
                            @if($member->occupation)
                            <div class="member-detail">
                                <div class="member-detail-label">Occupation</div>
                                <div class="member-detail-value">{{ $member->occupation }}</div>
                            </div>
                            @endif
                        </div>
                        
                        @if($member->problems->count() > 0)
                        <div style="margin-bottom: 16px;">
                            <span class="problem-badge">
                                <i class="fas fa-exclamation-triangle"></i>
                                {{ $member->problems->count() }} Problem{{ $member->problems->count() > 1 ? 's' : '' }}
                            </span>
                        </div>
                        @endif
                        
                        <div class="member-actions">
                            <a href="{{ route('members.show', $member) }}" class="member-btn member-btn-primary">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                            @if($member->problems->count() === 0)
                            <a href="{{ route('problems.create') }}?member_id={{ $member->id }}" class="member-btn member-btn-danger">
                                <i class="fas fa-plus"></i> Report Problem
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="empty-title">No family members registered yet</h3>
                <p class="empty-subtitle">Start by adding the first family member for this house.</p>
                <a href="{{ route('members.create') }}?house_id={{ $house->id }}" class="action-btn action-btn-primary">
                    <i class="fas fa-plus"></i> Add First Member
                </a>
            </div>
        @endif
    </div>

    <!-- Recent Problems -->
    @php
        $recentProblems = $house->members->flatMap->problems->sortByDesc('created_at')->take(5);
    @endphp

    @if($recentProblems->count() > 0)
    <div class="modern-card">
        <div class="card-header-modern">
            <div class="card-icon" style="background: var(--danger-gradient);">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div>
                <h2 class="card-title">Recent Problems</h2>
                <div class="card-subtitle">Latest issues reported from this house</div>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #f1f3f4;">
                        <th style="padding: 12px; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.85rem;">Member</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.85rem;">Problem</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.85rem;">Priority</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.85rem;">Status</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.85rem;">Date</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.85rem;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentProblems as $problem)
                    <tr style="border-bottom: 1px solid #f1f3f4;">
                        <td style="padding: 12px;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--warning-gradient); color: white; display: flex; align-items: center; justify-content: center; font-size: 0.8rem;">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <div style="font-weight: 600; color: #1f2937; font-size: 0.9rem;">{{ $problem->member->name ?? 'Unknown Member' }}</div>
                                    <div style="font-size: 0.75rem; color: #6b7280;">{{ $problem->member->relation_to_head ?? 'Unknown Relation' }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 12px; font-size: 0.9rem; color: #1f2937;">{{ Str::limit($problem->problem_description, 50) }}</td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 600; color: white; background: {{ $problem->priority === 'high' ? 'var(--danger-gradient)' : ($problem->priority === 'medium' ? 'var(--warning-gradient)' : 'var(--primary-gradient)') }};">
                                {{ ucfirst($problem->priority) }}
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 600; color: white; background: {{ $problem->status === 'resolved' ? 'var(--success-gradient)' : ($problem->status === 'in_progress' ? 'var(--warning-gradient)' : '#6b7280') }};">
                                {{ ucfirst(str_replace('_', ' ', $problem->status)) }}
                            </span>
                        </td>
                        <td style="padding: 12px; font-size: 0.9rem; color: #6b7280;">{{ $problem->created_at->format('M d, Y') }}</td>
                        <td style="padding: 12px;">
                            <a href="{{ route('problems.show', $problem) }}" style="width: 32px; height: 32px; border-radius: 50%; background: #e3f2fd; color: #1976d2; display: flex; align-items: center; justify-content: center; text-decoration: none; font-size: 0.8rem; transition: all 0.3s ease;">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection