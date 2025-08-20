@extends('layouts.app')

@section('title', 'Admin Requests - Boothcare')
@section('page-title', 'Admin Request System')

@push('styles')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        --danger-gradient: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
        --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --shadow-soft: 0 4px 20px rgba(0,0,0,0.08);
        --shadow-hover: 0 8px 30px rgba(0,0,0,0.15);
        --border-radius: 16px;
    }

    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
    }

    /* Modern Header */
    .requests-header {
        background: var(--primary-gradient);
        color: white;
        padding: 2rem 0;
        margin: -1rem -1rem 2rem -1rem;
        border-radius: 0 0 var(--border-radius) var(--border-radius);
        box-shadow: var(--shadow-soft);
        position: relative;
        overflow: hidden;
    }

    .requests-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 100%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-10px) rotate(2deg); }
    }

    .header-content {
        position: relative;
        z-index: 2;
    }

    .header-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .header-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 1.5rem;
    }

    /* Modern Cards */
    .modern-card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-soft);
        border: 1px solid rgba(255,255,255,0.2);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .modern-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-hover);
    }

    .card-header-modern {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .card-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: white;
        flex-shrink: 0;
    }

    .card-icon.primary { background: var(--primary-gradient); }
    .card-icon.success { background: var(--success-gradient); }
    .card-icon.danger { background: var(--danger-gradient); }
    .card-icon.warning { background: var(--warning-gradient); }
    .card-icon.info { background: var(--info-gradient); }

    .card-title-modern {
        font-size: 1.25rem;
        font-weight: 700;
        color: #2d3748;
        margin: 0;
    }

    .card-body-modern {
        padding: 1.5rem;
    }

    /* New Request Button */
    .new-request-btn {
        background: var(--success-gradient);
        border: none;
        color: white;
        padding: 12px 24px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(17, 153, 142, 0.3);
    }

    .new-request-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(17, 153, 142, 0.4);
        color: white;
    }

    .dropdown-menu-modern {
        border: none;
        border-radius: 12px;
        box-shadow: var(--shadow-soft);
        padding: 0.5rem;
        margin-top: 0.5rem;
    }

    .dropdown-item-modern {
        border-radius: 8px;
        padding: 0.75rem 1rem;
        margin-bottom: 0.25rem;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .dropdown-item-modern:hover {
        background: var(--primary-gradient);
        color: white;
        transform: translateX(4px);
    }

    /* Role Badge */
    .role-badge {
        background: var(--info-gradient);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: var(--border-radius);
        border: none;
        box-shadow: var(--shadow-soft);
        margin-bottom: 2rem;
    }

    .role-badge i {
        font-size: 1.2rem;
        margin-right: 0.5rem;
    }

    /* Request Items */
    .request-item {
        background: white;
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        border: 1px solid #f1f3f4;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .request-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        transition: all 0.3s ease;
    }

    .request-item.pending::before { background: var(--warning-gradient); }
    .request-item.approved::before { background: var(--success-gradient); }
    .request-item.rejected::before { background: var(--danger-gradient); }
    .request-item.problem::before { background: var(--danger-gradient); }

    .request-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .request-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .request-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.25rem;
    }

    .request-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 0.75rem;
    }

    .request-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    /* Modern Buttons */
    .btn-modern {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
        border: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-modern.success {
        background: var(--success-gradient);
        color: white;
    }

    .btn-modern.danger {
        background: var(--danger-gradient);
        color: white;
    }

    .btn-modern.primary {
        background: var(--primary-gradient);
        color: white;
    }

    .btn-modern.outline {
        background: transparent;
        border: 2px solid #e2e8f0;
        color: #6b7280;
    }

    .btn-modern:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    /* Status Badges */
    .status-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-badge.pending {
        background: rgba(251, 191, 36, 0.1);
        color: #f59e0b;
        border: 1px solid rgba(251, 191, 36, 0.2);
    }

    .status-badge.approved {
        background: rgba(34, 197, 94, 0.1);
        color: #22c55e;
        border: 1px solid rgba(34, 197, 94, 0.2);
    }

    .status-badge.rejected {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .status-badge.public {
        background: rgba(168, 85, 247, 0.1);
        color: #a855f7;
        border: 1px solid rgba(168, 85, 247, 0.2);
    }

    .status-badge.user {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
        border: 1px solid rgba(59, 130, 246, 0.2);
    }

    /* Statistics Cards */
    .stat-card {
        background: white;
        border-radius: var(--border-radius);
        padding: 1.5rem;
        text-align: center;
        box-shadow: var(--shadow-soft);
        transition: all 0.3s ease;
        border: 1px solid rgba(255,255,255,0.2);
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
        transition: all 0.3s ease;
    }

    .stat-card.total::before { background: var(--primary-gradient); }
    .stat-card.pending::before { background: var(--warning-gradient); }
    .stat-card.approved::before { background: var(--success-gradient); }
    .stat-card.rejected::before { background: var(--danger-gradient); }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-hover);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        margin: 0 auto 1rem;
    }

    .stat-icon.total { background: var(--primary-gradient); }
    .stat-icon.pending { background: var(--warning-gradient); }
    .stat-icon.approved { background: var(--success-gradient); }
    .stat-icon.rejected { background: var(--danger-gradient); }

    .stat-number {
        font-size: 2rem;
        font-weight: 800;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Empty States */
    .empty-state {
        text-align: center;
        padding: 3rem 1.5rem;
        color: #6b7280;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-state h6 {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .header-title {
            font-size: 2rem;
        }
        
        .requests-header {
            padding: 1.5rem 0;
        }
        
        .request-header {
            flex-direction: column;
            gap: 1rem;
        }
        
        .request-actions {
            justify-content: flex-start;
        }
        
        .stat-card {
            margin-bottom: 1rem;
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

    /* Smooth Transitions */
    * {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Modern Header -->
    <div class="requests-header">
        <div class="container">
            <div class="header-content">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="header-title">
                            <i class="fas fa-paper-plane me-3"></i>
                            Request Management
                        </h1>
                        <p class="header-subtitle">
                            Streamlined system for data updates and administrative approvals
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="dropdown">
                            <button class="new-request-btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-plus me-2"></i>New Request
                            </button>
                            <ul class="dropdown-menu dropdown-menu-modern">
                                <li>
                                    <a class="dropdown-item dropdown-item-modern" href="{{ route('admin.requests.family-member-update') }}">
                                        <i class="fas fa-user"></i>
                                        <div>
                                            <div class="fw-bold">Family Member</div>
                                            <small class="text-muted">Update member information</small>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item dropdown-item-modern" href="{{ route('admin.requests.location-update') }}">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <div>
                                            <div class="fw-bold">Location Data</div>
                                            <small class="text-muted">Update area, booth, house</small>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Role Info -->
    <div class="role-badge">
        <i class="fas fa-user-shield"></i>
        <strong>Your Role:</strong> {{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }} - 
        Full administrative privileges for data management and request approvals
    </div>

    <div class="row">
        <!-- My Requests -->
        <div class="col-lg-8">
            <div class="modern-card">
                <div class="card-header-modern">
                    <div class="card-icon primary">
                        <i class="fas fa-list"></i>
                    </div>
                    <h3 class="card-title-modern">My Recent Requests</h3>
                </div>
                <div class="card-body-modern">
                    @if($myRequests->count() > 0)
                        @foreach($myRequests as $request)
                            <div class="request-item {{ $request->status }}">
                                <div class="request-header">
                                    <div>
                                        <div class="request-title">
                                            {{ ucfirst(str_replace('_', ' ', $request->request_type)) }} Update
                                        </div>
                                        <div class="request-meta">
                                            <span>
                                                <i class="fas fa-tag me-1"></i>
                                                @if($request->target)
                                                    {{ $request->target->name ?? $request->target->area_name ?? $request->target->title ?? 'N/A' }}
                                                @else
                                                    <span class="text-muted">Target Deleted</span>
                                                @endif
                                            </span>
                                            <span>
                                                <i class="fas fa-edit me-1"></i>
                                                {{ ucfirst(str_replace('_', ' ', $request->current_data['field'] ?? 'N/A')) }}
                                            </span>
                                            <span>
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $request->created_at->format('M d, Y') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="status-badge {{ $request->status }}">
                                        {{ ucfirst($request->status) }}
                                    </div>
                                </div>
                                <div class="request-actions">
                                    <a href="{{ route('admin.requests.show', $request) }}" class="btn-modern primary">
                                        <i class="fas fa-eye"></i>
                                        View Details
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <h6>No requests submitted yet</h6>
                            <p>Start by creating your first data update request using the "New Request" button above.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            @if(Auth::user()->isAdminOrSubAdmin())
                <!-- Problem Creation Requests -->
                <div class="modern-card">
                    <div class="card-header-modern">
                        <div class="card-icon danger">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div>
                            <h3 class="card-title-modern">Problem Requests</h3>
                            @if($problemRequests->count() > 0)
                                <span class="status-badge public">{{ $problemRequests->count() }} Pending</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body-modern">
                        @if($problemRequests->count() > 0)
                            @foreach($problemRequests as $request)
                                <div class="request-item problem">
                                    <div class="request-header">
                                        <div>
                                            <div class="request-title">{{ $request->requested_data['title'] ?? 'Problem Request' }}</div>
                                            <div class="request-meta">
                                                <span>
                                                    <i class="fas fa-user me-1"></i>
                                                    {{ $request->user->name ?? 'Public User' }}
                                                </span>
                                                <span>
                                                    <i class="fas fa-tag me-1"></i>
                                                    {{ ucfirst($request->requested_data['category'] ?? 'N/A') }}
                                                </span>
                                                <span>
                                                    <i class="fas fa-clock me-1"></i>
                                                    {{ $request->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="status-badge {{ $request->request_type === 'public_problem_creation' ? 'public' : 'user' }}">
                                            {{ $request->request_type === 'public_problem_creation' ? 'Public' : 'User' }}
                                        </div>
                                    </div>
                                    <div class="request-actions">
                                        <button type="button" class="btn-modern success" onclick="approveProblem({{ $request->id }})">
                                            <i class="fas fa-check"></i>
                                            Approve
                                        </button>
                                        <a href="{{ route('admin.requests.show', $request) }}" class="btn-modern primary">
                                            <i class="fas fa-eye"></i>
                                            Details
                                        </a>
                                        <button type="button" class="btn-modern danger" onclick="rejectProblem({{ $request->id }})">
                                            <i class="fas fa-times"></i>
                                            Reject
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="empty-state">
                                <i class="fas fa-check-circle"></i>
                                <h6>All Clear!</h6>
                                <p>No problem requests pending approval.</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Other Pending Requests -->
                <div class="modern-card">
                    <div class="card-header-modern">
                        <div class="card-icon warning">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3 class="card-title-modern">Other Requests</h3>
                    </div>
                    <div class="card-body-modern">
                        @if($pendingRequests->count() > 0)
                            @foreach($pendingRequests as $request)
                                <div class="request-item pending">
                                    <div class="request-header">
                                        <div>
                                            <div class="request-title">{{ ucfirst($request->request_type) }} Update</div>
                                            <div class="request-meta">
                                                <span>
                                                    <i class="fas fa-user me-1"></i>
                                                    {{ $request->user->name ?? 'Unknown' }}
                                                </span>
                                                <span>
                                                    <i class="fas fa-clock me-1"></i>
                                                    {{ $request->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="status-badge pending">Pending</div>
                                    </div>
                                    <div class="request-actions">
                                        <a href="{{ route('admin.requests.show', $request) }}" class="btn-modern primary">
                                            <i class="fas fa-eye"></i>
                                            Review
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="empty-state">
                                <i class="fas fa-check-circle"></i>
                                <h6>All Caught Up!</h6>
                                <p>No pending requests to review.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <!-- Quick Actions for Regular Admins -->
                <div class="modern-card">
                    <div class="card-header-modern">
                        <div class="card-icon success">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <h3 class="card-title-modern">Quick Actions</h3>
                    </div>
                    <div class="card-body-modern">
                        <div class="d-grid gap-3">
                            <a href="{{ route('admin.requests.family-member-update') }}" class="btn-modern primary" style="padding: 1rem; text-decoration: none;">
                                <i class="fas fa-user"></i>
                                <div>
                                    <div class="fw-bold">Update Family Member</div>
                                    <small>Request changes to member data</small>
                                </div>
                            </a>
                            <a href="{{ route('admin.requests.location-update') }}" class="btn-modern info" style="padding: 1rem; text-decoration: none;">
                                <i class="fas fa-map-marker-alt"></i>
                                <div>
                                    <div class="fw-bold">Update Location</div>
                                    <small>Request area, booth, house changes</small>
                                </div>
                            </a>
                        </div>
                        
                        <div class="text-center mt-3 p-3 bg-light rounded">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                All requests require Super Administrator approval
                            </small>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modern Statistics -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="stat-card total">
                <div class="stat-icon total">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <div class="stat-number">{{ $myRequests->count() }}</div>
                <div class="stat-label">Total Requests</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card pending">
                <div class="stat-icon pending">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-number">{{ $myRequests->where('status', 'pending')->count() }}</div>
                <div class="stat-label">Pending</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card approved">
                <div class="stat-icon approved">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-number">{{ $myRequests->where('status', 'approved')->count() }}</div>
                <div class="stat-label">Approved</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card rejected">
                <div class="stat-icon rejected">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-number">{{ $myRequests->where('status', 'rejected')->count() }}</div>
                <div class="stat-label">Rejected</div>
            </div>
        </div>
    </div>
</div>

<!-- Modern Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.2);">
            <div class="modal-header" style="border: none; padding: 2rem 2rem 1rem;">
                <div class="d-flex align-items-center gap-3">
                    <div id="modalIcon" class="d-flex align-items-center justify-content-center" 
                         style="width: 60px; height: 60px; border-radius: 50%; font-size: 1.5rem; color: white;">
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-1" id="modalTitle"></h5>
                        <p class="text-muted mb-0" id="modalSubtitle"></p>
                    </div>
                </div>
            </div>
            <div class="modal-body" style="padding: 1rem 2rem;">
                <p id="modalMessage" class="mb-0"></p>
                <div id="rejectReasonContainer" style="display: none;" class="mt-3">
                    <label for="rejectReason" class="form-label fw-bold">Reason for rejection:</label>
                    <textarea id="rejectReason" class="form-control" rows="3" 
                              placeholder="Please provide a brief reason for rejecting this request..."
                              style="border-radius: 8px; border: 2px solid #e2e8f0;"></textarea>
                </div>
            </div>
            <div class="modal-footer" style="border: none; padding: 1rem 2rem 2rem; gap: 1rem;">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" 
                        style="border-radius: 8px; padding: 0.75rem 1.5rem; font-weight: 500;">
                    Cancel
                </button>
                <button type="button" id="confirmButton" class="btn" 
                        style="border-radius: 8px; padding: 0.75rem 1.5rem; font-weight: 500; color: white;">
                    Confirm
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
     background: rgba(0,0,0,0.5); z-index: 9999; backdrop-filter: blur(4px);">
    <div class="d-flex align-items-center justify-content-center h-100">
        <div class="text-center text-white">
            <div class="spinner-border mb-3" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <h5>Processing Request...</h5>
            <p class="mb-0">Please wait while we process your request.</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Modern JavaScript for request management
class RequestManager {
    constructor() {
        this.modal = new bootstrap.Modal(document.getElementById('confirmationModal'));
        this.loadingOverlay = document.getElementById('loadingOverlay');
        this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    }

    showLoading() {
        this.loadingOverlay.style.display = 'block';
    }

    hideLoading() {
        this.loadingOverlay.style.display = 'none';
    }

    showConfirmation(config) {
        // Set modal content
        document.getElementById('modalIcon').innerHTML = config.icon;
        document.getElementById('modalIcon').style.background = config.iconBg;
        document.getElementById('modalTitle').textContent = config.title;
        document.getElementById('modalSubtitle').textContent = config.subtitle;
        document.getElementById('modalMessage').textContent = config.message;
        
        const confirmBtn = document.getElementById('confirmButton');
        confirmBtn.textContent = config.confirmText;
        confirmBtn.style.background = config.confirmBg;
        confirmBtn.onclick = config.onConfirm;

        // Show/hide reject reason if needed
        const rejectContainer = document.getElementById('rejectReasonContainer');
        if (config.showRejectReason) {
            rejectContainer.style.display = 'block';
            document.getElementById('rejectReason').focus();
        } else {
            rejectContainer.style.display = 'none';
        }

        this.modal.show();
    }

    async makeRequest(url, method = 'POST', data = {}) {
        try {
            this.showLoading();
            
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                },
                body: method !== 'GET' ? JSON.stringify(data) : undefined
            });

            const result = await response.json();
            
            if (response.ok) {
                this.showSuccess(result.message || 'Operation completed successfully!');
                setTimeout(() => window.location.reload(), 1500);
            } else {
                this.showError(result.message || 'An error occurred. Please try again.');
            }
        } catch (error) {
            console.error('Request failed:', error);
            this.showError('Network error. Please check your connection and try again.');
        } finally {
            this.hideLoading();
            this.modal.hide();
        }
    }

    showSuccess(message) {
        this.showNotification(message, 'success');
    }

    showError(message) {
        this.showNotification(message, 'error');
    }

    showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed`;
        notification.style.cssText = `
            top: 20px; right: 20px; z-index: 10000; min-width: 300px;
            border-radius: 12px; box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            border: none; font-weight: 500;
        `;
        notification.innerHTML = `
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
        }, 4000);
    }
}

// Initialize request manager
const requestManager = new RequestManager();

// Problem approval function
function approveProblem(requestId) {
    requestManager.showConfirmation({
        icon: '<i class="fas fa-check"></i>',
        iconBg: 'linear-gradient(135deg, #11998e 0%, #38ef7d 100%)',
        title: 'Approve Problem Request',
        subtitle: 'Create new problem record',
        message: 'This will create a new problem record in the system and send notifications to relevant parties. Are you sure you want to approve this request?',
        confirmText: 'Approve Request',
        confirmBg: 'linear-gradient(135deg, #11998e 0%, #38ef7d 100%)',
        showRejectReason: false,
        onConfirm: () => {
            // Use form submission for problem approval (existing route expects form data)
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin-requests/${requestId}/approve-problem`;
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = requestManager.csrfToken;
            form.appendChild(csrfInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Problem rejection function
function rejectProblem(requestId) {
    requestManager.showConfirmation({
        icon: '<i class="fas fa-times"></i>',
        iconBg: 'linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%)',
        title: 'Reject Problem Request',
        subtitle: 'Provide rejection reason',
        message: 'Please provide a clear reason for rejecting this problem request. This will help the requester understand the decision.',
        confirmText: 'Reject Request',
        confirmBg: 'linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%)',
        showRejectReason: true,
        onConfirm: () => {
            const reason = document.getElementById('rejectReason').value.trim();
            if (!reason) {
                document.getElementById('rejectReason').style.borderColor = '#ef4444';
                document.getElementById('rejectReason').focus();
                return;
            }

            // Use form submission for problem rejection (existing route expects form data)
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin-requests/${requestId}/reject-problem`;
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = requestManager.csrfToken;
            form.appendChild(csrfInput);
            
            const reasonInput = document.createElement('input');
            reasonInput.type = 'hidden';
            reasonInput.name = 'review_notes';
            reasonInput.value = reason;
            form.appendChild(reasonInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Add smooth animations to cards
document.addEventListener('DOMContentLoaded', function() {
    // Animate cards on load
    const cards = document.querySelectorAll('.modern-card, .stat-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Add hover effects to request items
    const requestItems = document.querySelectorAll('.request-item');
    requestItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px) scale(1.02)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    // Add click animation to buttons
    const buttons = document.querySelectorAll('.btn-modern, .new-request-btn');
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.cssText = `
                position: absolute; border-radius: 50%; background: rgba(255,255,255,0.3);
                transform: scale(0); animation: ripple 0.6s linear;
                left: ${x}px; top: ${y}px; width: ${size}px; height: ${size}px;
            `;
            
            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);
            
            setTimeout(() => ripple.remove(), 600);
        });
    });
});

// Add CSS for ripple animation
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script>
@endpush

@endsection