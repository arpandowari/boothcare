@extends('layouts.app')

@section('title', 'Family Members - Boothcare')
@section('page-title', 'Family Members')

@push('styles')
<style>
    /* Modern Attractive Design */
    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        min-height: 100vh;
    }

    .container-fluid {
        max-width: 1400px;
        padding: 20px;
        margin: 0 auto;
    }

    /* Beautiful Header with Gradient */
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 40px 30px;
        margin: -20px -20px 40px -20px;
        border-radius: 0 0 30px 30px;
        box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><defs><radialGradient id="star" cx="50%" cy="50%" r="50%"><stop offset="0%" stop-color="white" stop-opacity="0.4"/><stop offset="100%" stop-color="white" stop-opacity="0"/></radialGradient></defs><circle cx="30" cy="40" r="1.5" fill="url(%23star)" opacity="0.8"><animate attributeName="opacity" values="0.3;0.8;0.3" dur="3s" repeatCount="indefinite"/></circle><circle cx="170" cy="60" r="1" fill="url(%23star)" opacity="0.6"><animate attributeName="opacity" values="0.2;0.7;0.2" dur="4s" repeatCount="indefinite"/></circle><circle cx="80" cy="90" r="0.8" fill="url(%23star)" opacity="0.7"><animate attributeName="opacity" values="0.4;0.9;0.4" dur="2.5s" repeatCount="indefinite"/></circle></svg>');
        opacity: 0.8;
    }

    .header-content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-text h1 {
        font-size: 2.5rem;
        font-weight: 800;
        margin: 0 0 10px 0;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .header-text p {
        opacity: 0.9;
        margin: 0;
        font-size: 1.1rem;
        font-weight: 400;
    }

    .header-actions {
        display: flex;
        gap: 15px;
    }

    .btn-add {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.3);
        padding: 12px 24px;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .btn-add:hover {
        background: rgba(255, 255, 255, 0.3);
        border-color: rgba(255, 255, 255, 0.5);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    }

    /* Beautiful Stats Cards */
    .stats-container {
        margin-bottom: 40px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
    }

    .stat-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        transition: all 0.4s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--card-gradient);
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 50px rgba(0,0,0,0.15);
    }

    .stat-card.total { --card-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .stat-card.male { --card-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
    .stat-card.female { --card-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
    .stat-card.families { --card-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }

    .stat-content {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .stat-icon {
        width: 70px;
        height: 70px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: white;
        background: var(--card-gradient);
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    }

    .stat-info h3 {
        font-size: 2.5rem;
        font-weight: 800;
        margin: 0;
        background: var(--card-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-info p {
        margin: 5px 0 0 0;
        color: #64748b;
        font-weight: 600;
        font-size: 1rem;
    }

    /* Modern Search Section */
    .search-section {
        background: white;
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 40px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        border: 1px solid rgba(255,255,255,0.2);
    }

    .search-form {
        display: flex;
        gap: 20px;
        align-items: center;
        flex-wrap: wrap;
    }

    .search-input-group {
        flex: 2;
        min-width: 300px;
        position: relative;
    }

    .search-input-group .form-control {
        padding-left: 50px;
        height: 50px;
        border-radius: 25px;
        border: 2px solid #e5e7eb;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: #f8fafc;
    }

    .search-input-group .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        outline: none;
        background: white;
    }

    .search-icon {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 1.1rem;
        z-index: 2;
    }

    .filter-group {
        flex: 1;
        min-width: 200px;
    }

    .form-group {
        margin-bottom: 0;
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        display: block;
        font-size: 0.9rem;
    }

    .form-select {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: white;
        color: #1f2937;
        height: 50px;
    }

    .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        outline: none;
        transform: translateY(-2px);
    }

    .btn {
        border: none;
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        white-space: nowrap;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        color: white;
    }

    /* Beautiful Members Grid */
    .members-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        overflow: hidden;
    }

    .members-header {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        padding: 25px 30px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .members-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .members-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 25px;
        padding: 30px;
    }

    /* Stunning Member Cards */
    .member-card {
        background: white;
        border: 2px solid #f1f5f9;
        border-radius: 20px;
        padding: 25px;
        transition: all 0.4s ease;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    .member-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .member-card:hover::before {
        transform: scaleX(1);
    }

    .member-card:hover {
        border-color: #667eea;
        transform: translateY(-8px);
        box-shadow: 0 15px 50px rgba(102, 126, 234, 0.15);
    }

    .member-card.family-head {
        border-color: #fbbf24;
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
    }

    .member-card.family-head::before {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    }

    .member-header {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 20px;
    }

    .member-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 2rem;
        color: white;
        background: linear-gradient(135deg, #667eea, #764ba2);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        position: relative;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }

    .member-card:hover .member-avatar {
        transform: scale(1.1);
        box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
    }

    .member-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    .family-head-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        width: 25px;
        height: 25px;
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        color: white;
        box-shadow: 0 4px 12px rgba(251, 191, 36, 0.4);
        border: 2px solid white;
    }

    .member-info {
        flex: 1;
        min-width: 0;
    }

    .member-name {
        font-size: 1.3rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .member-relation {
        color: #667eea;
        font-weight: 600;
        font-size: 0.9rem;
        background: rgba(102, 126, 234, 0.1);
        padding: 4px 12px;
        border-radius: 20px;
        display: inline-block;
        margin-bottom: 10px;
    }

    .member-details {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-bottom: 20px;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
        color: #64748b;
    }

    .detail-icon {
        width: 16px;
        height: 16px;
        color: #667eea;
    }

    .member-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }

    .action-btn {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .action-btn-view {
        background: rgba(102, 126, 234, 0.1);
        color: #667eea;
        border: 1px solid rgba(102, 126, 234, 0.2);
    }

    .action-btn-view:hover {
        background: #667eea;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .action-btn-edit {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .action-btn-edit:hover {
        background: #10b981;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 30px;
        color: #6b7280;
    }

    .empty-icon {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 25px;
        font-size: 3rem;
        color: #9ca3af;
    }

    .empty-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 10px;
    }

    .empty-text {
        margin-bottom: 25px;
        font-size: 1.1rem;
    }

    /* Ensure FontAwesome icons display properly */
    .fas, .far, .fab, .fal, .fad, .fa {
        font-family: "Font Awesome 6 Free", "Font Awesome 6 Pro", "Font Awesome 6 Brands" !important;
        font-weight: 900;
        -webkit-font-smoothing: antialiased;
        display: inline-block;
        font-style: normal;
        font-variant: normal;
        text-rendering: auto;
        line-height: 1;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 15px;
        }

        .page-header {
            padding: 25px 20px;
            margin: -15px -15px 25px -15px;
        }

        .header-content {
            flex-direction: column;
            gap: 20px;
            text-align: center;
        }

        .header-text h1 {
            font-size: 2rem;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .search-form {
            flex-direction: column;
            align-items: stretch;
        }

        .search-input-group {
            min-width: auto;
            flex: none;
        }

        .filter-group {
            min-width: auto;
            flex: none;
        }

        .btn-primary {
            align-self: center;
            min-width: 120px;
        }

        .members-grid {
            grid-template-columns: 1fr;
            gap: 20px;
            padding: 20px;
        }

        .member-details {
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .member-header {
            gap: 15px;
        }

        .member-avatar {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }
    }

    /* Animations */
    .fade-in {
        animation: fadeInUp 0.6s ease;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
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
    <!-- Beautiful Header -->
    <div class="page-header fade-in">
        <div class="header-content">
            <div class="header-text">
                <h1><i class="fas fa-users"></i> Family Members</h1>
                <p>Manage and view all family members in your community</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('members.create') }}" class="btn-add">
                    <i class="fas fa-plus"></i>
                    Add New Member
                </a>
            </div>
        </div>
    </div>

    <!-- Beautiful Stats Cards -->
    <div class="stats-container fade-in">
        <div class="stats-grid">
            <div class="stat-card total">
                <div class="stat-content">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $totalMembers ?? 0 }}</h3>
                        <p>Total Members</p>
                    </div>
                </div>
            </div>
            <div class="stat-card male">
                <div class="stat-content">
                    <div class="stat-icon">
                        <i class="fas fa-male"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $maleMembers ?? 0 }}</h3>
                        <p>Male Members</p>
                    </div>
                </div>
            </div>
            <div class="stat-card female">
                <div class="stat-content">
                    <div class="stat-icon">
                        <i class="fas fa-female"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $femaleMembers ?? 0 }}</h3>
                        <p>Female Members</p>
                    </div>
                </div>
            </div>
            <div class="stat-card families">
                <div class="stat-content">
                    <div class="stat-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $totalFamilies ?? 0 }}</h3>
                        <p>Total Families</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modern Search Section -->
    <div class="search-section fade-in">
        <form method="GET" action="{{ route('members.index') }}" class="search-form">
            <div class="search-input-group">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="form-control" name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Search by name, phone, or email...">
            </div>
            <div class="filter-group">
                <label class="form-label">Filter by Relation</label>
                <select class="form-select" name="relation">
                    <option value="">All Relations</option>
                    <option value="head" {{ request('relation') == 'head' ? 'selected' : '' }}>Head of Family</option>
                    <option value="spouse" {{ request('relation') == 'spouse' ? 'selected' : '' }}>Spouse</option>
                    <option value="son" {{ request('relation') == 'son' ? 'selected' : '' }}>Son</option>
                    <option value="daughter" {{ request('relation') == 'daughter' ? 'selected' : '' }}>Daughter</option>
                    <option value="father" {{ request('relation') == 'father' ? 'selected' : '' }}>Father</option>
                    <option value="mother" {{ request('relation') == 'mother' ? 'selected' : '' }}>Mother</option>
                    <option value="other" {{ request('relation') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i>
                Search
            </button>
        </form>
    </div>

    <!-- Beautiful Members Grid -->
    <div class="members-container fade-in">
        <div class="members-header">
            <h2 class="members-title">
                <i class="fas fa-address-card"></i>
                Family Members ({{ $members->total() ?? count($members ?? []) }})
            </h2>
        </div>

        @if(isset($members) && $members->count() > 0)
            <div class="members-grid">
                @foreach($members as $member)
                    <div class="member-card {{ $member->relation_to_head == 'head' ? 'family-head' : '' }}">
                        <div class="member-header">
                            <div class="member-avatar">
                                @if($member->profile_photo)
                                    <img src="{{ asset('storage/' . $member->profile_photo) }}" alt="{{ $member->name }}">
                                @else
                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                @endif
                                @if($member->relation_to_head == 'head')
                                    <div class="family-head-badge">
                                        <i class="fas fa-crown"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="member-info">
                                <h3 class="member-name">
                                    {{ $member->name }}
                                    @if($member->gender == 'male')
                                        <i class="fas fa-male" style="color: #3b82f6;"></i>
                                    @elseif($member->gender == 'female')
                                        <i class="fas fa-female" style="color: #ec4899;"></i>
                                    @endif
                                </h3>
                                <div class="member-relation">
                                    {{ ucfirst(str_replace('_', ' ', $member->relation_to_head)) }}
                                </div>
                            </div>
                        </div>

                        <div class="member-details">
                            @if($member->age)
                                <div class="detail-item">
                                    <i class="fas fa-birthday-cake detail-icon"></i>
                                    <span>{{ $member->age }} years old</span>
                                </div>
                            @endif
                            @if($member->phone)
                                <div class="detail-item">
                                    <i class="fas fa-phone detail-icon"></i>
                                    <span>{{ $member->phone }}</span>
                                </div>
                            @endif
                            @if($member->occupation)
                                <div class="detail-item">
                                    <i class="fas fa-briefcase detail-icon"></i>
                                    <span>{{ $member->occupation }}</span>
                                </div>
                            @endif
                            @if($member->education)
                                <div class="detail-item">
                                    <i class="fas fa-graduation-cap detail-icon"></i>
                                    <span>{{ $member->education }}</span>
                                </div>
                            @endif
                            @if($member->house)
                                <div class="detail-item">
                                    <i class="fas fa-home detail-icon"></i>
                                    <span>House {{ $member->house->house_number }}</span>
                                </div>
                            @endif
                            @if($member->house && $member->house->booth)
                                <div class="detail-item">
                                    <i class="fas fa-building detail-icon"></i>
                                    <span>Booth {{ $member->house->booth->booth_number }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="member-actions">
                            <a href="{{ route('members.show', $member) }}" class="action-btn action-btn-view">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="{{ route('members.edit', $member) }}" class="action-btn action-btn-edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="empty-title">No Family Members Found</h3>
                <p class="empty-text">No family members match your current search criteria. Try adjusting your filters or add a new member.</p>
                <a href="{{ route('members.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Add First Member
                </a>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if(isset($members) && $members->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $members->links() }}
        </div>
    @endif
</div>
@endsection