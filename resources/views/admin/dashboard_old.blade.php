@extends('layouts.app')

@section('title', 'Admin Dashboard - Boothcare')
@section('page-title', 'Admin Dashboard')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .dashboard-header {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(20px);
        border-radius: var(--border-radius);
        padding: 24px;
        margin-bottom: 24px;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .dashboard-title {
        color: white;
        font-size: 2rem;
        font-weight: 800;
        margin: 0;
    }

    .dashboard-subtitle {
        color: rgba(255, 255, 255, 0.8);
        margin: 8px 0 0 0;
    }

    .chart-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: var(--border-radius);
        padding: 24px;
        box-shadow: var(--shadow);
        border: 1px solid rgba(255, 255, 255, 0.2);
        height: 400px;
    }

    .mini-chart-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: var(--border-radius);
        padding: 20px;
        box-shadow: var(--shadow);
        border: 1px solid rgba(255, 255, 255, 0.2);
        height: 200px;
    }

    .chart-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 16px;
    }

    .chart-value {
        font-size: 2rem;
        font-weight: 800;
        color: var(--primary-color);
        margin-bottom: 8px;
    }

    .chart-change {
        font-size: 0.9rem;
        font-weight: 600;
    }

    .chart-change.positive {
        color: var(--success-color);
    }

    .chart-change.negative {
        color: var(--danger-color);
    }

    .export-btn {
        background: var(--gradient-primary);
        border: none;
        color: white;
        padding: 10px 20px;
        border-radius: var(--border-radius-sm);
        font-weight: 600;
        transition: var(--transition);
    }

    .export-btn:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        color: white;
    }
</style>
@endpush

@section('content')
<!-- Modern Dashboard Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-gradient-primary text-white overflow-hidden position-relative">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center mb-2">
                            <div class="user-avatar me-3">
                                @if(Auth::user()->profile_photo)
                                    <img src="{{ Auth::user()->profile_photo_url }}" 
                                         class="rounded-circle border border-white border-3" width="60" height="60" alt="Profile">
                                @else
                                    <div class="avatar-circle bg-white bg-opacity-20 border border-white border-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h3 class="mb-1">Welcome back, {{ Auth::user()->name }}!</h3>
                                <p class="mb-0 opacity-75">
                                    <i class="fas fa-shield-alt me-2"></i>
                                    {{ Auth::user()->isAdmin() ? 'Administrator' : 'Sub Administrator' }} Dashboard - Managing Boothcare System
                                </p>
                            </div>
                        </div>
                        <div class="row mt-3">
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
                                        <div class="fw-bold" id="currentTime">{{ now()->format('g:i A') }}</div>
                                        <small class="opacity-75">Current Time</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-users me-2"></i>
                                    <div>
                                        <div class="fw-bold">{{ ($stats['total_users'] ?? 0) + 1 }}</div>
                                        <small class="opacity-75">Total Users</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="dashboard-illustration">
                            <i class="fas fa-chart-line fa-4x opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="position-absolute top-0 end-0 p-3">
                <div class="dropdown">
                    <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-cog me-1"></i> Quick Settings
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('settings.index') }}"><i class="fas fa-cog me-2"></i>System Settings</a></li>
                        <li><a class="dropdown-item" href="{{ route('users.index') }}"><i class="fas fa-users me-2"></i>Manage Users</a></li>
                        <li><a class="dropdown-item" href="{{ route('reports.index') }}"><i class="fas fa-chart-bar me-2"></i>Reports</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="fas fa-user me-2"></i>My Profile</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Statistics Cards -->
<div class="row mb-4">
    @if(isset($stats['total_areas']))
    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="card stat-card bg-gradient-info text-white border-0 shadow-sm">
            <div class="card-body text-center position-relative">
                <div class="stat-icon">
                    <i class="fas fa-map-marked-alt fa-2x mb-2"></i>
                </div>
                <h3 class="counter" data-target="{{ $stats['total_areas'] }}">0</h3>
                <p class="mb-0">Areas</p>
                <div class="stat-trend">
                    <small class="opacity-75">
                        <i class="fas fa-arrow-up me-1"></i>
                        Active regions
                    </small>
                </div>
                @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('areas.view'))
                <div class="position-absolute top-0 end-0 p-2">
                    <a href="{{ route('areas.index') }}" class="text-white text-decoration-none">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
    
    @if(isset($stats['total_booths']))
    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="card stat-card bg-gradient-primary text-white border-0 shadow-sm">
            <div class="card-body text-center position-relative">
                <div class="stat-icon">
                    <i class="fas fa-building fa-2x mb-2"></i>
                </div>
                <h3 class="counter" data-target="{{ $stats['total_booths'] }}">0</h3>
                <p class="mb-0">Booths</p>
                <div class="stat-trend">
                    <small class="opacity-75">
                        <i class="fas fa-chart-line me-1"></i>
                        Voting centers
                    </small>
                </div>
                @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('booths.view'))
                <div class="position-absolute top-0 end-0 p-2">
                    <a href="{{ route('booths.index') }}" class="text-white text-decoration-none">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
    @if(isset($stats['total_houses']))
    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="card stat-card bg-gradient-success text-white border-0 shadow-sm">
            <div class="card-body text-center position-relative">
                <div class="stat-icon">
                    <i class="fas fa-home fa-2x mb-2"></i>
                </div>
                <h3 class="counter" data-target="{{ $stats['total_houses'] }}">0</h3>
                <p class="mb-0">Houses</p>
                <div class="stat-trend">
                    <small class="opacity-75">
                        <i class="fas fa-home me-1"></i>
                        Registered homes
                    </small>
                </div>
                @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('houses.view'))
                <div class="position-absolute top-0 end-0 p-2">
                    <a href="{{ route('houses.index') }}" class="text-white text-decoration-none">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
    
    @if(isset($stats['total_members']))
    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="card stat-card bg-gradient-warning text-white border-0 shadow-sm">
            <div class="card-body text-center position-relative">
                <div class="stat-icon">
                    <i class="fas fa-users fa-2x mb-2"></i>
                </div>
                <h3 class="counter" data-target="{{ $stats['total_members'] }}">0</h3>
                <p class="mb-0">Members</p>
                <div class="stat-trend">
                    <small class="opacity-75">
                        <i class="fas fa-users me-1"></i>
                        Family members
                    </small>
                </div>
                @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('members.view'))
                <div class="position-absolute top-0 end-0 p-2">
                    <a href="{{ route('members.index') }}" class="text-white text-decoration-none">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
    
    @if(isset($stats['total_users']))
    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="card stat-card bg-gradient-secondary text-white border-0 shadow-sm">
            <div class="card-body text-center position-relative">
                <div class="stat-icon">
                    <i class="fas fa-user-check fa-2x mb-2"></i>
                </div>
                <h3 class="counter" data-target="{{ $stats['total_users'] }}">0</h3>
                <p class="mb-0">Users</p>
                <div class="stat-trend">
                    <small class="opacity-75">
                        <i class="fas fa-user-plus me-1"></i>
                        Active accounts
                    </small>
                </div>
                @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('users.view'))
                <div class="position-absolute top-0 end-0 p-2">
                    <a href="{{ route('users.index') }}" class="text-white text-decoration-none">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
    @if(isset($stats['total_problems']))
    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="card stat-card bg-gradient-danger text-white border-0 shadow-sm">
            <div class="card-body text-center position-relative">
                <div class="stat-icon">
                    <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                </div>
                <h3 class="counter" data-target="{{ $stats['total_problems'] }}">0</h3>
                <p class="mb-0">Problems</p>
                <div class="stat-trend">
                    <small class="opacity-75">
                        <i class="fas fa-exclamation-circle me-1"></i>
                        Total reported
                    </small>
                </div>
                @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('problems.view'))
                <div class="position-absolute top-0 end-0 p-2">
                    <a href="{{ route('problems.index') }}" class="text-white text-decoration-none">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Analytics Dashboard -->
@if(Auth::user()->isAdmin() || Auth::user()->hasPermission('problems.view'))
<div class="row mb-4">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2 text-primary"></i>
                        Problem Analytics
                    </h5>
                    <div class="btn-group btn-group-sm" role="group">
                        <input type="radio" class="btn-check" name="chartType" id="chart-bar" checked>
                        <label class="btn btn-outline-primary" for="chart-bar">
                            <i class="fas fa-chart-bar"></i>
                        </label>
                        <input type="radio" class="btn-check" name="chartType" id="chart-pie">
                        <label class="btn btn-outline-primary" for="chart-pie">
                            <i class="fas fa-chart-pie"></i>
                        </label>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="analytics-card bg-warning bg-opacity-10 border border-warning rounded p-3">
                            <div class="d-flex align-items-center">
                                <div class="analytics-icon bg-warning text-white rounded-circle me-3">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <h4 class="text-warning mb-0 counter" data-target="{{ $stats['active_problems'] ?? 0 }}">0</h4>
                                    <small class="text-muted">Active Problems</small>
                                </div>
                            </div>
                            <div class="progress mt-2" style="height: 4px;">
                                <div class="progress-bar bg-warning" style="width: {{ ($stats['total_problems'] ?? 0) > 0 ? (($stats['active_problems'] ?? 0) / ($stats['total_problems'] ?? 1)) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="analytics-card bg-success bg-opacity-10 border border-success rounded p-3">
                            <div class="d-flex align-items-center">
                                <div class="analytics-icon bg-success text-white rounded-circle me-3">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div>
                                    <h4 class="text-success mb-0 counter" data-target="{{ $stats['resolved_problems'] ?? 0 }}">0</h4>
                                    <small class="text-muted">Resolved</small>
                                </div>
                            </div>
                            <div class="progress mt-2" style="height: 4px;">
                                <div class="progress-bar bg-success" style="width: {{ ($stats['total_problems'] ?? 0) > 0 ? (($stats['resolved_problems'] ?? 0) / ($stats['total_problems'] ?? 1)) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="analytics-card bg-danger bg-opacity-10 border border-danger rounded p-3">
                            <div class="d-flex align-items-center">
                                <div class="analytics-icon bg-danger text-white rounded-circle me-3">
                                    <i class="fas fa-fire"></i>
                                </div>
                                <div>
                                    <h4 class="text-danger mb-0 counter" data-target="{{ $stats['urgent_problems'] ?? 0 }}">0</h4>
                                    <small class="text-muted">Urgent</small>
                                </div>
                            </div>
                            <div class="progress mt-2" style="height: 4px;">
                                <div class="progress-bar bg-danger" style="width: {{ ($stats['total_problems'] ?? 0) > 0 ? (($stats['urgent_problems'] ?? 0) / ($stats['total_problems'] ?? 1)) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="analytics-card bg-info bg-opacity-10 border border-info rounded p-3">
                            <div class="d-flex align-items-center">
                                <div class="analytics-icon bg-info text-white rounded-circle me-3">
                                    <i class="fas fa-percentage"></i>
                                </div>
                                <div>
                                    <h4 class="text-info mb-0">
                                        <span class="counter" data-target="{{ ($stats['total_problems'] ?? 0) > 0 ? round((($stats['resolved_problems'] ?? 0) / ($stats['total_problems'] ?? 1)) * 100) : 0 }}">0</span>%
                                    </h4>
                                    <small class="text-muted">Success Rate</small>
                                </div>
                            </div>
                            <div class="progress mt-2" style="height: 4px;">
                                <div class="progress-bar bg-info" style="width: {{ ($stats['total_problems'] ?? 0) > 0 ? round((($stats['resolved_problems'] ?? 0) / ($stats['total_problems'] ?? 1)) * 100) : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-tachometer-alt me-2 text-success"></i>
                    System Health
                </h5>
            </div>
            <div class="card-body">
                <div class="system-health-item mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small">Database Status</span>
                        <span class="badge bg-success">Online</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: 100%"></div>
                    </div>
                </div>
                <div class="system-health-item mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small">Active Users</span>
                        <span class="badge bg-primary">{{ $stats['total_users'] ?? 0 }}</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-primary" style="width: 85%"></div>
                    </div>
                </div>
                <div class="system-health-item mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small">Problem Resolution</span>
                        <span class="badge bg-info">{{ ($stats['total_problems'] ?? 0) > 0 ? round((($stats['resolved_problems'] ?? 0) / ($stats['total_problems'] ?? 1)) * 100) : 0 }}%</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-info" style="width: {{ ($stats['total_problems'] ?? 0) > 0 ? round((($stats['resolved_problems'] ?? 0) / ($stats['total_problems'] ?? 1)) * 100) : 0 }}%"></div>
                    </div>
                </div>
                <div class="system-health-item">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small">Update Requests</span>
                        <span class="badge bg-warning">{{ $stats['pending_update_requests'] ?? 0 }}</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-warning" style="width: {{ ($stats['pending_update_requests'] ?? 0) > 0 ? 75 : 25 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Update Request Statistics -->
@if(isset($stats['pending_update_requests']) || isset($stats['total_update_requests']))
<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card border-primary">
            <div class="card-body text-center">
                <i class="fas fa-edit fa-2x text-primary mb-2"></i>
                <h3 class="text-primary">{{ $stats['pending_update_requests'] ?? 0 }}</h3>
                <p class="mb-0">Pending Update Requests</p>
                <small class="text-muted">Awaiting review</small>
                <div class="mt-2">
                    <a href="{{ route('update-requests.index') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-eye me-1"></i> Review
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card border-secondary">
            <div class="card-body text-center">
                <i class="fas fa-list fa-2x text-secondary mb-2"></i>
                <h3 class="text-secondary">{{ $stats['total_update_requests'] ?? 0 }}</h3>
                <p class="mb-0">Total Update Requests</p>
                <small class="text-muted">All time requests</small>
                <div class="mt-2">
                    <a href="{{ route('update-requests.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-list me-1"></i> View All
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<div class="row">
    <!-- Recent Problems -->
    @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('problems.view'))
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-list-alt me-2"></i>
                    Recent Problems
                </h5>
                <a href="{{ route('problems.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                @if($recent_problems->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Problem</th>
                                    <th>Member</th>
                                    <th>Location</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_problems as $problem)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ Str::limit($problem->title, 30) }}</strong>
                                            <br>
                                            <small class="text-muted">{{ Str::limit($problem->description, 40) }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            {{ $problem->familyMember->name ?? 'N/A' }}
                                            <br>
                                            <small class="text-muted">{{ $problem->familyMember->relation_to_head ?? '' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <small>
                                            <strong>{{ $problem->familyMember->house->booth->area->area_name ?? 'N/A' }}</strong><br>
                                            Booth: {{ $problem->familyMember->house->booth->booth_number ?? 'N/A' }}<br>
                                            House: {{ $problem->familyMember->house->house_number ?? 'N/A' }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $problem->priority_color }}">
                                            {{ ucfirst($problem->priority) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $problem->status_color }}">
                                            {{ ucfirst(str_replace('_', ' ', $problem->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ $problem->reported_date->format('M d') }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('problems.show', $problem) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No problems reported yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Quick Actions & Recent Users -->
    <div class="col-lg-4">
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
                    @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('areas.create'))
                    <a href="{{ route('areas.create') }}" class="btn btn-info">
                        <i class="fas fa-map-marked-alt me-2"></i>
                        Add New Area
                    </a>
                    @endif
                    
                    @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('booths.create'))
                    <a href="{{ route('booths.create') }}" class="btn btn-primary">
                        <i class="fas fa-building me-2"></i>
                        Add New Booth
                    </a>
                    @endif
                    
                    @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('houses.create'))
                    <a href="{{ route('houses.create') }}" class="btn btn-success">
                        <i class="fas fa-home me-2"></i>
                        Add New House
                    </a>
                    @endif
                    
                    @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('problems.view'))
                    <a href="{{ route('problems.index') }}?status=reported" class="btn btn-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Review New Problems
                    </a>
                    @endif
                    
                    @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('users.view'))
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-users me-2"></i>
                        Manage Users
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent User Registrations -->
        @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('users.view'))
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-plus me-2"></i>
                    Recent Registrations
                </h5>
            </div>
            <div class="card-body">
                @if($recent_registrations->count() > 0)
                    @foreach($recent_registrations as $user)
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-circle bg-primary text-white me-3">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold">{{ $user->name }}</div>
                            <small class="text-muted">{{ $user->email }}</small>
                            <br>
                            <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                        </div>
                        <div>
                            <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    @if(!$loop->last)
                        <hr class="my-2">
                    @endif
                    @endforeach
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-user-plus fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No recent registrations</p>
                    </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Area Overview -->
@if(Auth::user()->isAdmin() || Auth::user()->hasPermission('areas.view'))
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-sitemap me-2"></i>
                    Area Overview - Hierarchical Structure
                </h5>
            </div>
            <div class="card-body">
                @if($areas->count() > 0)
                    <div class="row">
                        @foreach($areas as $area)
                        <div class="col-md-6 mb-4">
                            <div class="card border-info">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-map-marked-alt me-2"></i>
                                        {{ $area->area_name }}
                                    </h6>
                                    <small>{{ $area->district }}, {{ $area->division }}</small>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center mb-3">
                                        <div class="col-3">
                                            <div class="h5 text-primary">{{ $area->booths->count() }}</div>
                                            <small class="text-muted">Booths</small>
                                        </div>
                                        <div class="col-3">
                                            <div class="h5 text-success">{{ $area->total_houses }}</div>
                                            <small class="text-muted">Houses</small>
                                        </div>
                                        <div class="col-3">
                                            <div class="h5 text-warning">{{ $area->total_members }}</div>
                                            <small class="text-muted">Members</small>
                                        </div>
                                        <div class="col-3">
                                            <div class="h5 text-danger">{{ $area->total_problems }}</div>
                                            <small class="text-muted">Problems</small>
                                        </div>
                                    </div>
                                    
                                    <!-- Booth List -->
                                    @foreach($area->booths->take(3) as $booth)
                                    <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                                        <div>
                                            <strong class="text-primary">Booth {{ $booth->booth_number }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $booth->booth_name }}</small>
                                        </div>
                                        <div class="text-end">
                                            <small class="text-muted">
                                                {{ $booth->houses->count() }} houses<br>
                                                {{ $booth->total_members }} members
                                            </small>
                                        </div>
                                    </div>
                                    @endforeach
                                    
                                    @if($area->booths->count() > 3)
                                    <div class="text-center">
                                        <small class="text-muted">... and {{ $area->booths->count() - 3 }} more booths</small>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-map-marked-alt fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">No areas configured yet</h5>
                        <p class="text-muted">Start by adding your first area to organize the booth structure.</p>
                        <a href="{{ route('areas.create') }}" class="btn btn-info">
                            <i class="fas fa-plus me-2"></i>
                            Add First Area
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('styles')
<style>
/* Enhanced Gradients */
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
}

.bg-gradient-danger {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
}

.bg-gradient-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
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

/* Counter Animation */
.counter {
    font-weight: 700;
    font-size: 2rem;
}

/* Analytics Cards */
.analytics-card {
    transition: all 0.3s ease;
    border-radius: 12px;
}

.analytics-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.analytics-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

/* System Health */
.system-health-item {
    transition: all 0.3s ease;
    padding: 8px;
    border-radius: 8px;
}

.system-health-item:hover {
    background-color: rgba(0,0,0,0.02);
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

/* Dashboard Illustration */
.dashboard-illustration {
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
    100% { transform: translateY(0px); }
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

/* Responsive Enhancements */
@media (max-width: 768px) {
    .stat-card {
        margin-bottom: 1rem;
    }
    
    .counter {
        font-size: 1.5rem;
    }
    
    .analytics-card {
        margin-bottom: 1rem;
    }
    
    .dashboard-illustration {
        display: none;
    }
    
    .card-body .row .col-md-8 {
        text-align: center;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
}

@media (max-width: 576px) {
    .stat-card .card-body {
        padding: 1rem 0.5rem;
    }
    
    .counter {
        font-size: 1.25rem;
    }
    
    .analytics-icon {
        width: 30px;
        height: 30px;
        font-size: 1rem;
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
function updateClock() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('en-US', {
        hour: 'numeric',
        minute: '2-digit',
        hour12: true
    });
    
    const clockElement = document.getElementById('currentTime');
    if (clockElement) {
        clockElement.textContent = timeString;
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Animate counters with delay
    setTimeout(animateCounters, 500);
    
    // Update clock every minute
    updateClock();
    setInterval(updateClock, 60000);
    
    // Add loading states to buttons
    document.querySelectorAll('.btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (!this.classList.contains('dropdown-toggle')) {
                const originalContent = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner loading me-2"></i>Loading...';
                
                // Reset after navigation (for demo purposes)
                setTimeout(() => {
                    this.innerHTML = originalContent;
                }, 1000);
            }
        });
    });
    
    // Add refresh functionality
    const refreshBtn = document.createElement('button');
    refreshBtn.className = 'btn btn-sm btn-outline-light position-absolute';
    refreshBtn.style.cssText = 'top: 10px; right: 10px; z-index: 1000;';
    refreshBtn.innerHTML = '<i class="fas fa-sync-alt"></i>';
    refreshBtn.title = 'Refresh Dashboard';
    refreshBtn.onclick = () => window.location.reload();
    
    const headerCard = document.querySelector('.bg-gradient-primary');
    if (headerCard) {
        headerCard.style.position = 'relative';
        headerCard.appendChild(refreshBtn);
    }
});

// Chart type toggle (placeholder for future chart implementation)
document.addEventListener('change', function(e) {
    if (e.target.name === 'chartType') {
        console.log('Chart type changed to:', e.target.id);
        // Future: Implement chart switching logic
    }
}); 
</script>
@endpush