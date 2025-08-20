@extends('layouts.app')

@section('title', 'My Profile - Boothcare')
@section('page-title', 'My Profile')

@push('styles')
<style>
    /* Modern Profile Show Design */
    .profile-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 24px;
    }

    /* Profile Header */
    .profile-header {
        background: var(--card-bg);
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        margin-bottom: 24px;
        overflow: hidden;
    }

    .header-cover {
        height: 200px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .header-cover::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><defs><radialGradient id="star" cx="50%" cy="50%" r="50%"><stop offset="0%" stop-color="white" stop-opacity="0.8"/><stop offset="100%" stop-color="white" stop-opacity="0"/></radialGradient></defs><circle cx="20" cy="30" r="2" fill="url(%23star)"/><circle cx="180" cy="50" r="1.5" fill="url(%23star)"/><circle cx="60" cy="80" r="1" fill="url(%23star)"/><circle cx="150" cy="120" r="2.5" fill="url(%23star)"/><circle cx="40" cy="160" r="1.5" fill="url(%23star)"/><circle cx="120" cy="180" r="1" fill="url(%23star)"/></svg>');
        animation: twinkle 4s ease-in-out infinite alternate;
    }

    @keyframes twinkle {
        0%, 100% { opacity: 0.3; }
        50% { opacity: 1; }
    }

    .profile-avatar {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        border: 4px solid rgba(255, 255, 255, 0.3);
        object-fit: cover;
        position: relative;
        z-index: 2;
        box-shadow: 0 12px 30px rgba(0,0,0,0.2);
    }

    .avatar-placeholder {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        border: 4px solid rgba(255, 255, 255, 0.3);
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 4rem;
        position: relative;
        z-index: 2;
        box-shadow: 0 12px 30px rgba(0,0,0,0.2);
        backdrop-filter: blur(10px);
    }

    .header-content {
        padding: 32px;
        text-align: center;
        margin-top: -75px;
        position: relative;
        z-index: 3;
    }

    .profile-name {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 20px 0 8px 0;
    }

    .profile-role {
        color: var(--text-secondary);
        font-size: 1.1rem;
        margin: 0 0 20px 0;
        font-weight: 500;
    }

    .profile-actions {
        display: flex;
        gap: 12px;
        justify-content: center;
        flex-wrap: wrap;
    }

    /* Profile Layout */
    .profile-layout {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
    }

    .profile-main {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .profile-sidebar {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    /* Profile Cards */
    .profile-card {
        background: var(--card-bg);
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .profile-card:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }

    .profile-card-header {
        background: var(--bg-tertiary);
        padding: 20px 24px;
        border-bottom: 1px solid var(--border-color);
    }

    .profile-card-title {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .profile-card-title i {
        color: var(--primary-color);
        font-size: 1.2rem;
    }

    .profile-card-body {
        padding: 24px;
    }

    /* Info Items */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .info-label {
        font-weight: 500;
        color: var(--text-secondary);
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 1rem;
    }

    .info-value.empty {
        color: var(--text-muted);
        font-style: italic;
    }

    /* Badges */
    .badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
    }

    .badge-admin {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }

    .badge-user {
        background: linear-gradient(135deg, #4facfe, #00f2fe);
        color: white;
    }

    .badge-active {
        background: #d4edda;
        color: #155724;
    }

    .badge-inactive {
        background: #f8d7da;
        color: #721c24;
    }

    /* Buttons */
    .btn {
        border: none;
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }

    .btn-primary {
        background: var(--primary-color);
        color: white;
    }

    .btn-primary:hover {
        background: var(--primary-dark);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }

    .btn-secondary {
        background: var(--secondary-color);
        color: white;
    }

    .btn-secondary:hover {
        background: #4b5563;
        color: white;
        transform: translateY(-1px);
    }

    .btn-outline {
        background: transparent;
        color: var(--text-secondary);
        border: 2px solid var(--border-color);
    }

    .btn-outline:hover {
        background: var(--bg-tertiary);
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
    }

    .stat-card {
        background: var(--bg-secondary);
        border: 2px solid var(--border-color);
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        border-color: var(--primary-color);
        background: var(--bg-tertiary);
        transform: translateY(-2px);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: var(--primary-color);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
        font-size: 1.2rem;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 4px;
    }

    .stat-label {
        font-size: 0.9rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    /* Activity Timeline */
    .activity-timeline {
        position: relative;
        padding-left: 24px;
    }

    .activity-timeline::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: var(--border-color);
    }

    .activity-item {
        position: relative;
        margin-bottom: 20px;
        padding-bottom: 20px;
    }

    .activity-item::before {
        content: '';
        position: absolute;
        left: -20px;
        top: 6px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--primary-color);
        border: 2px solid var(--card-bg);
    }

    .activity-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .activity-date {
        font-size: 0.8rem;
        color: var(--text-muted);
        font-weight: 500;
    }

    .activity-title {
        font-weight: 600;
        color: var(--text-primary);
        margin: 4px 0;
    }

    .activity-desc {
        font-size: 0.9rem;
        color: var(--text-secondary);
    }

    /* Responsive Design */
    @media (max-width: 992px) {
        .profile-layout {
            grid-template-columns: 1fr;
        }
        
        .profile-sidebar {
            order: -1;
        }
    }

    @media (max-width: 768px) {
        .profile-container {
            padding: 16px;
        }
        
        .header-content {
            padding: 24px 20px;
        }
        
        .profile-name {
            font-size: 1.8rem;
        }
        
        .profile-avatar,
        .avatar-placeholder {
            width: 120px;
            height: 120px;
        }
        
        .avatar-placeholder {
            font-size: 3rem;
        }
        
        .profile-card-body {
            padding: 20px;
        }
        
        .profile-actions {
            flex-direction: column;
        }
        
        .btn {
            width: 100%;
            justify-content: center;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="profile-container">
    <!-- Profile Header -->
    <div class="profile-header">
        <div class="header-cover">
            @if(Auth::user()->isAdminOrSubAdmin() && Auth::user()->profile_photo)
                <img src="{{ Auth::user()->profile_photo_url }}" alt="Profile Photo" class="profile-avatar">
            @else
                <div class="avatar-placeholder">
                    <i class="fas fa-user"></i>
                </div>
            @endif
        </div>
        <div class="header-content">
            <h1 class="profile-name">{{ Auth::user()->name }}</h1>
            <p class="profile-role">
                @if(Auth::user()->isAdmin())
                    <span class="badge badge-admin">Administrator</span>
                @elseif(Auth::user()->isSubAdmin())
                    <span class="badge badge-admin">Sub Administrator</span>
                @else
                    <span class="badge badge-user">User</span>
                @endif
            </p>
            
            <div class="profile-actions">
                <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i>
                    Edit Profile
                </a>
                <a href="{{ route('dashboard') }}" class="btn btn-outline">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="profile-layout">
        <div class="profile-main">
            <!-- Personal Information -->
            <div class="profile-card">
                <div class="profile-card-header">
                    <h3 class="profile-card-title">
                        <i class="fas fa-user"></i>
                        Personal Information
                    </h3>
                </div>
                <div class="profile-card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Full Name</span>
                            <span class="info-value">{{ Auth::user()->name }}</span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">Email Address</span>
                            <span class="info-value">{{ Auth::user()->email }}</span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">Phone Number</span>
                            <span class="info-value {{ Auth::user()->phone ? '' : 'empty' }}">
                                {{ Auth::user()->phone ?: 'Not provided' }}
                            </span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">Date of Birth</span>
                            <span class="info-value {{ Auth::user()->date_of_birth ? '' : 'empty' }}">
                                {{ Auth::user()->date_of_birth ? Auth::user()->date_of_birth->format('d M Y') : 'Not provided' }}
                            </span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">Gender</span>
                            <span class="info-value {{ Auth::user()->gender ? '' : 'empty' }}">
                                {{ Auth::user()->gender ? ucfirst(Auth::user()->gender) : 'Not provided' }}
                            </span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">Member Since</span>
                            <span class="info-value">{{ Auth::user()->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @if(!Auth::user()->isAdminOrSubAdmin() && $familyMember)
            <!-- Family Information -->
            <div class="profile-card">
                <div class="profile-card-header">
                    <h3 class="profile-card-title">
                        <i class="fas fa-home"></i>
                        Family Information
                    </h3>
                </div>
                <div class="profile-card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">House</span>
                            <span class="info-value {{ $familyMember->house ? '' : 'empty' }}">
                                @if($familyMember->house)
                                    House #{{ $familyMember->house->house_number }}
                                    @if($familyMember->house->booth)
                                        - {{ $familyMember->house->booth->booth_name }}
                                    @endif
                                @else
                                    Not assigned
                                @endif
                            </span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">Area</span>
                            <span class="info-value {{ $familyMember->house && $familyMember->house->booth && $familyMember->house->booth->area ? '' : 'empty' }}">
                                @if($familyMember->house && $familyMember->house->booth && $familyMember->house->booth->area)
                                    {{ $familyMember->house->booth->area->area_name }}
                                @else
                                    Not assigned
                                @endif
                            </span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">Booth Number</span>
                            <span class="info-value {{ $familyMember->house && $familyMember->house->booth ? '' : 'empty' }}">
                                @if($familyMember->house && $familyMember->house->booth)
                                    Booth {{ $familyMember->house->booth->booth_number }}
                                @else
                                    Not assigned
                                @endif
                            </span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">Relation to Head</span>
                            <span class="info-value {{ $familyMember->relation_to_head ? '' : 'empty' }}">
                                {{ $familyMember->relation_to_head ? ucfirst($familyMember->relation_to_head) : 'Not provided' }}
                            </span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">Occupation</span>
                            <span class="info-value {{ $familyMember->occupation ? '' : 'empty' }}">
                                {{ $familyMember->occupation ?: 'Not provided' }}
                            </span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">Education</span>
                            <span class="info-value {{ $familyMember->education ? '' : 'empty' }}">
                                {{ $familyMember->education ?: 'Not provided' }}
                            </span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">Marital Status</span>
                            <span class="info-value {{ $familyMember->marital_status ? '' : 'empty' }}">
                                {{ $familyMember->marital_status ? ucfirst($familyMember->marital_status) : 'Not provided' }}
                            </span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">Monthly Income</span>
                            <span class="info-value {{ $familyMember->monthly_income ? '' : 'empty' }}">
                                @if($familyMember->monthly_income)
                                    ₹{{ number_format($familyMember->monthly_income) }}
                                @else
                                    Not provided
                                @endif
                            </span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">Family Head</span>
                            <span class="info-value">
                                {{ $familyMember->is_family_head ? 'Yes' : 'No' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @elseif(!Auth::user()->isAdminOrSubAdmin() && !$familyMember)
            <!-- No Family Information -->
            <div class="profile-card">
                <div class="profile-card-header">
                    <h3 class="profile-card-title">
                        <i class="fas fa-home"></i>
                        Family Information
                    </h3>
                </div>
                <div class="profile-card-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>No family information found.</strong> Please contact an administrator to set up your family member profile.
                    </div>
                </div>
            </div>
            @endif

            <!-- Government IDs -->
            <div class="profile-card">
                <div class="profile-card-header">
                    <h3 class="profile-card-title">
                        <i class="fas fa-id-card"></i>
                        Government ID Numbers
                    </h3>
                </div>
                <div class="profile-card-body">
                    <div class="info-grid">
                        @if($familyMember)
                            <!-- Show family member IDs -->
                            <div class="info-item">
                                <span class="info-label">Aadhar Number</span>
                                <span class="info-value {{ $familyMember->aadhar_number ? '' : 'empty' }}">
                                    @if($familyMember->aadhar_number)
                                        {{ substr($familyMember->aadhar_number, 0, 4) }}****{{ substr($familyMember->aadhar_number, -4) }}
                                    @else
                                        Not provided
                                    @endif
                                </span>
                            </div>
                            
                            <div class="info-item">
                                <span class="info-label">PAN Number</span>
                                <span class="info-value {{ $familyMember->pan_number ? '' : 'empty' }}">
                                    {{ $familyMember->pan_number ?: 'Not provided' }}
                                </span>
                            </div>
                            
                            <div class="info-item">
                                <span class="info-label">Voter ID</span>
                                <span class="info-value {{ $familyMember->voter_id ? '' : 'empty' }}">
                                    {{ $familyMember->voter_id ?: 'Not provided' }}
                                </span>
                            </div>
                            
                            <div class="info-item">
                                <span class="info-label">Ration Card Number</span>
                                <span class="info-value {{ $familyMember->ration_card_number ? '' : 'empty' }}">
                                    {{ $familyMember->ration_card_number ?: 'Not provided' }}
                                </span>
                            </div>
                        @else
                            <!-- Show user IDs for admin users -->
                            <div class="info-item">
                                <span class="info-label">NID Number</span>
                                <span class="info-value {{ Auth::user()->nid_number ? '' : 'empty' }}">
                                    {{ Auth::user()->nid_number ?: 'Not provided' }}
                                </span>
                            </div>
                            
                            <div class="info-item">
                                <span class="info-label">Aadhar Number</span>
                                <span class="info-value {{ Auth::user()->aadhar_number ? '' : 'empty' }}">
                                    @if(Auth::user()->aadhar_number)
                                        {{ substr(Auth::user()->aadhar_number, 0, 4) }}****{{ substr(Auth::user()->aadhar_number, -4) }}
                                    @else
                                        Not provided
                                    @endif
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="profile-sidebar">
            <!-- Account Status -->
            <div class="profile-card">
                <div class="profile-card-header">
                    <h3 class="profile-card-title">
                        <i class="fas fa-info-circle"></i>
                        Account Status
                    </h3>
                </div>
                <div class="profile-card-body">
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div class="stat-value">
                                <span class="badge {{ Auth::user()->is_active ? 'badge-active' : 'badge-inactive' }}">
                                    {{ Auth::user()->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div class="stat-label">Account Status</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="profile-card">
                <div class="profile-card-header">
                    <h3 class="profile-card-title">
                        <i class="fas fa-clock"></i>
                        Recent Activity
                    </h3>
                </div>
                <div class="profile-card-body">
                    <div class="activity-timeline">
                        <div class="activity-item">
                            <div class="activity-date">{{ Auth::user()->updated_at->format('d M Y, g:i A') }}</div>
                            <div class="activity-title">Profile Updated</div>
                            <div class="activity-desc">Last profile information update</div>
                        </div>
                        
                        <div class="activity-item">
                            <div class="activity-date">{{ Auth::user()->created_at->format('d M Y, g:i A') }}</div>
                            <div class="activity-title">Account Created</div>
                            <div class="activity-desc">Joined Boothcare platform</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="profile-card">
                <div class="profile-card-header">
                    <h3 class="profile-card-title">
                        <i class="fas fa-bolt"></i>
                        Quick Actions
                    </h3>
                </div>
                <div class="profile-card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i>
                            Edit Profile
                        </a>
                        @if(!Auth::user()->profile_completed)

                        @endif
                        <a href="{{ route('dashboard') }}" class="btn btn-outline">
                            <i class="fas fa-tachometer-alt"></i>
                            Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .d-grid {
        display: grid;
    }

    .gap-2 {
        gap: 12px;
    }
</style>
@endsection