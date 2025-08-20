@extends('layouts.app')

@section('title', 'Update Requests Management - Admin')
@section('page-title', 'Update Requests Management')

@push('styles')
<style>
    /* Modern Admin Dashboard Styles */
    body {
        background: #f8fafc;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    .container-fluid {
        max-width: 1400px;
        margin: 0 auto;
    }

    /* Header Section */
    .admin-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem 1.5rem;
        margin: -1rem -1rem 2rem -1rem;
        border-radius: 0 0 20px 20px;
        box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
    }

    .admin-header h1 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .admin-header p {
        opacity: 0.9;
        margin: 0;
        font-size: 1.1rem;
    }

    /* Stats Cards */
    .stats-container {
        margin-bottom: 2rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        text-align: center;
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
        background: var(--stat-color, #667eea);
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.12);
    }

    .stat-card.total { --stat-color: #667eea; }
    .stat-card.pending { --stat-color: #f59e0b; }
    .stat-card.approved { --stat-color: #10b981; }
    .stat-card.rejected { --stat-color: #ef4444; }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.25rem;
        color: white;
        background: var(--stat-color, #667eea);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: var(--stat-color, #667eea);
    }

    .stat-label {
        color: #64748b;
        font-weight: 500;
        font-size: 0.9rem;
    }

    /* Filters Section */
    .filters-section {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        border: 1px solid #e2e8f0;
    }

    .filters-form {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr auto;
        gap: 1rem;
        align-items: end;
    }

    .form-group {
        margin-bottom: 0;
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        display: block;
        font-size: 0.9rem;
    }

    .form-control, .form-select {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        transition: all 0.2s ease;
        background: white;
        color: #1f2937;
    }

    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
    }

    .btn {
        border: none;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        white-space: nowrap;
    }

    .btn-primary {
        background: #667eea;
        color: white;
    }

    .btn-primary:hover {
        background: #5a67d8;
        color: white;
        transform: translateY(-1px);
    }

    .btn-success {
        background: #10b981;
        color: white;
    }

    .btn-success:hover {
        background: #059669;
        color: white;
        transform: translateY(-1px);
    }

    .btn-danger {
        background: #ef4444;
        color: white;
    }

    .btn-danger:hover {
        background: #dc2626;
        color: white;
        transform: translateY(-1px);
    }

    .btn-outline {
        background: transparent;
        color: #6b7280;
        border: 2px solid #e5e7eb;
    }

    .btn-outline:hover {
        background: #f9fafb;
        border-color: #667eea;
        color: #667eea;
    }

    /* Requests Table */
    .requests-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .requests-header {
        background: #f8fafc;
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .requests-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }

    .bulk-actions {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .table {
        margin: 0;
        background: white;
    }

    .table th {
        background: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
        font-weight: 600;
        color: #374151;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        padding: 1rem 0.75rem;
        white-space: nowrap;
    }

    .table td {
        border-bottom: 1px solid #f1f5f9;
        padding: 1rem 0.75rem;
        vertical-align: middle;
        color: #374151;
    }

    .table tbody tr:hover {
        background: #f8fafc;
    }

    /* Status Badges */
    .badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .badge-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-approved {
        background: #dcfce7;
        color: #166534;
    }

    .badge-rejected {
        background: #fecaca;
        color: #991b1b;
    }

    /* Request Type Badges */
    .type-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 500;
        text-transform: capitalize;
    }

    .type-profile {
        background: #dbeafe;
        color: #1e40af;
    }

    .type-family_member {
        background: #f3e8ff;
        color: #7c3aed;
    }

    .type-documents {
        background: #fef3c7;
        color: #92400e;
    }

    .type-problem_creation {
        background: #fecaca;
        color: #991b1b;
    }

    /* Action Buttons */
    .action-btn {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        margin: 0 0.125rem;
    }

    .action-btn-view {
        background: #f0f9ff;
        color: #0369a1;
        border: 1px solid #bae6fd;
    }

    .action-btn-view:hover {
        background: #0369a1;
        color: white;
    }

    .action-btn-approve {
        background: #f0fdf4;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .action-btn-approve:hover {
        background: #166534;
        color: white;
    }

    .action-btn-reject {
        background: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .action-btn-reject:hover {
        background: #991b1b;
        color: white;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 1.5rem;
        color: #6b7280;
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 2rem;
        color: #9ca3af;
    }

    .empty-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .empty-text {
        margin-bottom: 1.5rem;
    }

    /* Tabs Styles */
    .tabs-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .custom-tabs {
        border-bottom: none;
        background: #f8fafc;
        margin: 0;
        padding: 0 1.5rem;
    }

    .custom-tabs .nav-link {
        border: none;
        border-radius: 0;
        padding: 1.25rem 2rem;
        font-weight: 600;
        color: #6b7280;
        background: transparent;
        position: relative;
        display: flex;
        align-items: center;
        transition: all 0.3s ease;
    }

    .custom-tabs .nav-link:hover {
        color: #374151;
        background: rgba(102, 126, 234, 0.05);
    }

    .custom-tabs .nav-link.active {
        color: #667eea;
        background: white;
        border-bottom: 3px solid #667eea;
    }

    .tab-badge {
        background: #e5e7eb;
        color: #374151;
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 700;
        margin-left: 0.5rem;
        min-width: 24px;
        text-align: center;
    }

    .custom-tabs .nav-link.active .tab-badge {
        background: #667eea;
        color: white;
    }

    .problem-badge {
        background: #ef4444 !important;
        color: white !important;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    .tab-content {
        padding: 0;
    }

    .tab-pane {
        padding: 1.5rem;
    }

    /* Problem Header Styling */
    .problem-header {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
        color: white !important;
    }

    .problem-header .requests-title {
        color: white !important;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .admin-header {
            padding: 1.5rem 1rem;
            margin: -1rem -1rem 1.5rem -1rem;
        }

        .admin-header h1 {
            font-size: 1.5rem;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }

        .stat-card {
            padding: 1rem;
        }

        .stat-number {
            font-size: 1.5rem;
        }

        .filters-section {
            padding: 1rem;
        }

        .filters-form {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .requests-header {
            padding: 1rem;
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }

        .bulk-actions {
            justify-content: center;
        }

        .table-responsive {
            font-size: 0.875rem;
        }

        .action-btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.7rem;
        }

        .custom-tabs .nav-link {
            padding: 1rem 1.5rem;
            font-size: 0.9rem;
        }

        .tab-pane {
            padding: 1rem;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .custom-tabs .nav-link {
            padding: 0.75rem 1rem;
            font-size: 0.8rem;
        }

        .tab-badge {
            font-size: 0.7rem;
            padding: 0.2rem 0.4rem;
        }
    }

    /* Modern Toast Notification System */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        max-width: 400px;
        width: 100%;
    }

    .toast {
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        margin-bottom: 12px;
        padding: 0;
        border: none;
        overflow: hidden;
        transform: translateX(400px);
        opacity: 0;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
    }

    .toast.show {
        transform: translateX(0);
        opacity: 1;
    }

    .toast.hide {
        transform: translateX(400px);
        opacity: 0;
    }

    .toast::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--toast-color, #10b981);
    }

    .toast.success::before { background: #10b981; }
    .toast.error::before { background: #ef4444; }
    .toast.warning::before { background: #f59e0b; }
    .toast.info::before { background: #3b82f6; }

    .toast-header {
        background: transparent;
        border: none;
        padding: 16px 20px 8px 24px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .toast-icon {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        color: white;
        flex-shrink: 0;
    }

    .toast.success .toast-icon {
        background: #10b981;
    }

    .toast.error .toast-icon {
        background: #ef4444;
    }

    .toast.warning .toast-icon {
        background: #f59e0b;
    }

    .toast.info .toast-icon {
        background: #3b82f6;
    }

    .toast-title {
        font-weight: 600;
        color: #1f2937;
        margin: 0;
        font-size: 0.95rem;
        flex: 1;
    }

    .toast-close {
        background: none;
        border: none;
        color: #9ca3af;
        font-size: 18px;
        cursor: pointer;
        padding: 0;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        transition: all 0.2s ease;
    }

    .toast-close:hover {
        background: #f3f4f6;
        color: #6b7280;
    }

    .toast-body {
        padding: 0 20px 16px 24px;
        color: #6b7280;
        font-size: 0.9rem;
        line-height: 1.5;
    }

    .toast-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        background: var(--toast-color, #10b981);
        border-radius: 0 0 12px 12px;
        transition: width linear;
        opacity: 0.7;
    }

    .toast.success .toast-progress { background: #10b981; }
    .toast.error .toast-progress { background: #ef4444; }
    .toast.warning .toast-progress { background: #f59e0b; }
    .toast.info .toast-progress { background: #3b82f6; }

    /* Mobile responsive */
    @media (max-width: 768px) {
        .toast-container {
            top: 10px;
            right: 10px;
            left: 10px;
            max-width: none;
        }

        .toast {
            transform: translateY(-100px);
        }

        .toast.show {
            transform: translateY(0);
        }

        .toast.hide {
            transform: translateY(-100px);
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Modern Toast Notifications Container -->
    <div id="toast-container" class="toast-container"></div>

    <!-- Admin Header -->
    <div class="admin-header">
        <h1><i class="fas fa-edit me-2"></i>Update Requests Management</h1>
        <p>Review and manage all user update requests from a centralized admin panel</p>
    </div>

    <!-- Statistics -->
    <div class="stats-container">
        <div class="stats-grid">
            <div class="stat-card total">
                <div class="stat-icon">
                    <i class="fas fa-list"></i>
                </div>
                <div class="stat-number">{{ $stats['total'] }}</div>
                <div class="stat-label">Total Requests</div>
            </div>
            <div class="stat-card pending">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-number">{{ $stats['pending'] }}</div>
                <div class="stat-label">Pending Review</div>
            </div>
            <div class="stat-card approved">
                <div class="stat-icon">
                    <i class="fas fa-check"></i>
                </div>
                <div class="stat-number">{{ $stats['approved'] }}</div>
                <div class="stat-label">Approved</div>
            </div>
            <div class="stat-card rejected">
                <div class="stat-icon">
                    <i class="fas fa-times"></i>
                </div>
                <div class="stat-number">{{ $stats['rejected'] }}</div>
                <div class="stat-label">Rejected</div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-section">
        <form method="GET" action="{{ route('admin.update-requests.index') }}" class="filters-form">
            <div class="form-group">
                <label class="form-label">Search Requests</label>
                <input type="text" class="form-control" name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Search by user name or email...">
            </div>
            <div class="form-group">
                <label class="form-label">Filter by Status</label>
                <select class="form-select" name="status">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Filter by Type</label>
                <select class="form-select" name="type">
                    <option value="">All Types</option>
                    @foreach($requestTypes as $type)
                        <option value="{{ $type['value'] }}" {{ request('type') == $type['value'] ? 'selected' : '' }}>
                            {{ $type['label'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Tabs Navigation -->
    <div class="tabs-container">
        <ul class="nav nav-tabs custom-tabs" id="requestTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-requests" 
                        type="button" role="tab" aria-controls="profile-requests" aria-selected="true">
                    <i class="fas fa-user-edit me-2"></i>
                    Profile & Document Updates
                    <span class="tab-badge">{{ $updateRequests->total() }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="problem-tab" data-bs-toggle="tab" data-bs-target="#problem-requests" 
                        type="button" role="tab" aria-controls="problem-requests" aria-selected="false">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Problem Reports
                    <span class="tab-badge problem-badge">{{ $problemRequests->count() }}</span>
                </button>
            </li>
        </ul>

        <div class="tab-content" id="requestTabsContent">
            <!-- Profile & Document Updates Tab -->
            <div class="tab-pane fade show active" id="profile-requests" role="tabpanel" aria-labelledby="profile-tab">
                <!-- Regular Update Requests Table -->
    <div class="requests-container">
        <div class="requests-header">
            <h2 class="requests-title">
                <i class="fas fa-edit me-2"></i>
                Profile & Document Updates ({{ $updateRequests->total() }})
            </h2>
            <div class="bulk-actions">
                <button type="button" class="btn btn-success btn-sm" onclick="bulkApprove()">
                    <i class="fas fa-check"></i>
                    Bulk Approve
                </button>
                <button type="button" class="btn btn-danger btn-sm" onclick="bulkReject()">
                    <i class="fas fa-times"></i>
                    Bulk Reject
                </button>
            </div>
        </div>

        @if($updateRequests->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                            </th>
                            <th>Request ID</th>
                            <th>User</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th>Reviewed By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($updateRequests as $request)
                            <tr>
                                <td>
                                    <input type="checkbox" class="request-checkbox" value="{{ $request->id }}">
                                </td>
                                <td>
                                    <strong>#{{ $request->id }}</strong>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $request->user->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $request->user->email }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="type-badge type-{{ $request->request_type }}">
                                        {{ ucfirst(str_replace('_', ' ', $request->request_type)) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $request->status }}">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div>
                                        {{ $request->created_at->format('M d, Y') }}
                                        <br>
                                        <small class="text-muted">{{ $request->created_at->format('H:i A') }}</small>
                                    </div>
                                </td>
                                <td>
                                    @if($request->reviewer)
                                        <div>
                                            <strong>{{ $request->reviewer->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $request->reviewed_at->format('M d, Y') }}</small>
                                        </div>
                                    @else
                                        <span class="text-muted">Not reviewed</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap">
                                        <a href="{{ route('admin.update-requests.show', $request) }}" 
                                           class="action-btn action-btn-view">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        
                                        @if($request->status === 'pending')
                                            <button type="button" class="action-btn action-btn-approve" 
                                                    onclick="handleApprove({{ $request->id }})">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                            <button type="button" class="action-btn action-btn-reject" 
                                                    onclick="handleReject({{ $request->id }})">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-edit"></i>
                </div>
                <h3 class="empty-title">No Update Requests Found</h3>
                <p class="empty-text">No update requests match your current filters. Try adjusting your search criteria.</p>
            </div>
        @endif
    </div>

                <!-- Pagination -->
                @if($updateRequests->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $updateRequests->links() }}
                    </div>
                @endif
            </div>

            <!-- Problem Reports Tab -->
            <div class="tab-pane fade" id="problem-requests" role="tabpanel" aria-labelledby="problem-tab">
                <div class="requests-container">
                    <div class="requests-header problem-header">
                        <h2 class="requests-title">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Problem Reports ({{ $problemRequests->count() }})
                        </h2>
                        <div class="bulk-actions">
                            <button type="button" class="btn btn-light btn-sm" onclick="bulkApproveProblem()">
                                <i class="fas fa-check"></i>
                                Bulk Approve
                            </button>
                            <button type="button" class="btn btn-outline-light btn-sm" onclick="bulkRejectProblem()">
                                <i class="fas fa-times"></i>
                                Bulk Reject
                            </button>
                        </div>
                    </div>

                    @if($problemRequests->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="selectAllProblems" onchange="toggleSelectAllProblems()">
                                        </th>
                                        <th>Request ID</th>
                                        <th>Reporter</th>
                                        <th>Problem Title</th>
                                        <th>Category</th>
                                        <th>Priority</th>
                                        <th>Source</th>
                                        <th>Submitted</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($problemRequests as $request)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="problem-checkbox" value="{{ $request->id }}">
                                            </td>
                                            <td>
                                                <strong>#{{ $request->id }}</strong>
                                            </td>
                                            <td>
                                                <div>
                                                    @if($request->user)
                                                        <strong>{{ $request->user->name }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $request->user->email }}</small>
                                                    @else
                                                        <strong>Public User</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $request->requested_data['reporter_name'] ?? 'Anonymous' }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $request->requested_data['title'] ?? 'No Title' }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ Str::limit($request->requested_data['description'] ?? 'No description', 50) }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ ucfirst($request->requested_data['category'] ?? 'general') }}
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $priority = $request->requested_data['priority'] ?? 'medium';
                                                    $priorityColors = [
                                                        'low' => 'success',
                                                        'medium' => 'warning', 
                                                        'high' => 'danger',
                                                        'urgent' => 'dark'
                                                    ];
                                                @endphp
                                                <span class="badge bg-{{ $priorityColors[$priority] ?? 'secondary' }}">
                                                    {{ ucfirst($priority) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($request->request_type === 'public_problem_creation')
                                                    <span class="badge bg-primary">Public Portal</span>
                                                @else
                                                    <span class="badge bg-secondary">User Account</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div>
                                                    {{ $request->created_at->format('M d, Y') }}
                                                    <br>
                                                    <small class="text-muted">{{ $request->created_at->format('H:i A') }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap">
                                                    <a href="{{ route('admin.update-requests.show', $request) }}" 
                                                       class="action-btn action-btn-view">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                    
                                                    @if($request->status === 'pending')
                                                        <button type="button" class="action-btn action-btn-approve" 
                                                                onclick="handleApproveProblem({{ $request->id }})">
                                                            <i class="fas fa-check"></i> Create Problem
                                                        </button>
                                                        <button type="button" class="action-btn action-btn-reject" 
                                                                onclick="handleRejectProblem({{ $request->id }})">
                                                            <i class="fas fa-times"></i> Reject
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <h3 class="empty-title">No Problem Reports Found</h3>
                            <p class="empty-text">No problem creation requests are currently pending review.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approve Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="approveForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Are you sure you want to approve this update request?</p>
                    <div class="form-group">
                        <label class="form-label">Review Notes (Optional)</label>
                        <textarea class="form-control" name="review_notes" rows="3" 
                                  placeholder="Add any notes about this approval..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Approve Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Please provide a reason for rejecting this update request:</p>
                    <div class="form-group">
                        <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="review_notes" rows="4" required
                                  placeholder="Explain why this request is being rejected..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Reject Modal -->
<div class="modal fade" id="bulkRejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Reject Requests</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="bulkRejectForm" method="POST" action="{{ route('admin.update-requests.bulk-reject') }}">
                @csrf
                <div class="modal-body">
                    <p>Please provide a reason for rejecting the selected requests:</p>
                    <div class="form-group">
                        <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="bulk_reject_reason" rows="4" required
                                  placeholder="Explain why these requests are being rejected..."></textarea>
                    </div>
                    <input type="hidden" name="request_ids" id="bulkRejectIds">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Selected</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Select all functionality
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.request-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

// Individual request actions - CLEAN VERSION
function handleApprove(requestId) {
    console.log('✅ Approve request called for ID:', requestId);
    
    const notes = prompt('Add review notes (optional):');
    if (notes !== null) { // User didn't cancel
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/update-requests/${requestId}/approve`;
        form.style.display = 'none';
        
        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken.getAttribute('content');
            form.appendChild(csrfInput);
        } else {
            alert('Security token not found. Please refresh the page.');
            return;
        }
        
        // Add review notes
        const notesInput = document.createElement('input');
        notesInput.type = 'hidden';
        notesInput.name = 'review_notes';
        notesInput.value = notes || '';
        form.appendChild(notesInput);
        
        // Submit form
        document.body.appendChild(form);
        console.log('📤 Submitting approval form...');
        form.submit();
    }
}

function handleReject(requestId) {
    console.log('❌ Reject request called for ID:', requestId);
    
    const reason = prompt('Please provide a reason for rejection (required):');
    if (reason && reason.trim() !== '') {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/update-requests/${requestId}/reject`;
        form.style.display = 'none';
        
        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken.getAttribute('content');
            form.appendChild(csrfInput);
        } else {
            alert('Security token not found. Please refresh the page.');
            return;
        }
        
        // Add rejection reason
        const reasonInput = document.createElement('input');
        reasonInput.type = 'hidden';
        reasonInput.name = 'review_notes';
        reasonInput.value = reason;
        form.appendChild(reasonInput);
        
        // Submit form
        document.body.appendChild(form);
        console.log('📤 Submitting rejection form...');
        form.submit();
    } else if (reason !== null) {
        alert('Rejection reason is required!');
        handleReject(requestId); // Try again
    }
}uerySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken.getAttribute('content');
            form.appendChild(csrfInput);
        } else {
            alert('Security token not found. Please refresh the page.');
            return;
        }
        
        // Add rejection reason
        const reasonInput = document.createElement('input');
        reasonInput.type = 'hidden';
        reasonInput.name = 'review_notes';
        reasonInput.value = reason;
        form.appendChild(reasonInput);
        
        // Submit form
        document.body.appendChild(form);
        form.submit();
    } else if (reason !== null) {
        alert('Rejection reason is required!');
        rejectRequest(requestId); // Try again
    }
}

// Bulk actions
function bulkApprove() {
    const selectedIds = getSelectedIds();
    
    if (selectedIds.length === 0) {
        alert('Please select at least one request to approve.');
        return;
    }
    
    if (confirm(`Are you sure you want to approve ${selectedIds.length} selected request(s)?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.update-requests.bulk-approve") }}';
        
        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken.getAttribute('content');
            form.appendChild(csrfInput);
        }
        
        // Add selected IDs
        selectedIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'request_ids[]';
            input.value = id;
            form.appendChild(input);
        });
        
        document.body.appendChild(form);
        form.submit();
    }
}

function bulkReject() {
    const selectedIds = getSelectedIds();
    
    if (selectedIds.length === 0) {
        alert('Please select at least one request to reject.');
        return;
    }
    
    document.getElementById('bulkRejectIds').value = JSON.stringify(selectedIds);
    
    const modal = new bootstrap.Modal(document.getElementById('bulkRejectModal'));
    modal.show();
}

function getSelectedIds() {
    const checkboxes = document.querySelectorAll('.request-checkbox:checked');
    return Array.from(checkboxes).map(cb => cb.value);
}heckbox:checked');
    return Array.from(checkboxes).map(checkbox => checkbox.value);
}

// Modern Toast Notification System
function showToast(type, title, message, duration = 5000) {
    const toastContainer = document.getElementById('toast-container');
    
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    
    // Toast content
    toast.innerHTML = `
        <div class="toast-header">
            <div class="toast-icon">
                <i class="fas ${getToastIcon(type)}"></i>
            </div>
            <div class="toast-title">${title}</div>
            <button class="toast-close" onclick="hideToast(this.closest('.toast'))">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="toast-body">${message}</div>
        <div class="toast-progress"></div>
    `;
    
    // Add to container
    toastContainer.appendChild(toast);
    
    // Show toast with animation
    setTimeout(() => {
        toast.classList.add('show');
    }, 100);
    
    // Progress bar animation
    const progressBar = toast.querySelector('.toast-progress');
    progressBar.style.width = '100%';
    progressBar.style.transitionDuration = duration + 'ms';
    
    setTimeout(() => {
        progressBar.style.width = '0%';
    }, 200);
    
    // Auto hide
    setTimeout(() => {
        hideToast(toast);
    }, duration);
}

function hideToast(toast) {
    toast.classList.remove('show');
    toast.classList.add('hide');
    
    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 400);
}

function getToastIcon(type) {
    const icons = {
        'success': 'fa-check',
        'error': 'fa-times',
        'warning': 'fa-exclamation-triangle',
        'info': 'fa-info'
    };
    return icons[type] || 'fa-info';
}

// Modern Input Dialog
function showInputDialog(title, placeholder, required = false) {
    return new Promise((resolve) => {
        // Create overlay
        const overlay = document.createElement('div');
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(4px);
        `;
        
        // Create dialog
        const dialog = document.createElement('div');
        dialog.style.cssText = `
            background: white;
            border-radius: 16px;
            padding: 24px;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            transform: scale(0.9);
            transition: transform 0.2s ease;
        `;
        
        dialog.innerHTML = `
            <h3 style="margin: 0 0 16px 0; color: #1f2937; font-size: 1.25rem; font-weight: 600;">${title}</h3>
            <textarea 
                id="dialog-input" 
                placeholder="${placeholder}"
                style="
                    width: 100%;
                    min-height: 80px;
                    border: 2px solid #e5e7eb;
                    border-radius: 8px;
                    padding: 12px;
                    font-size: 14px;
                    resize: vertical;
                    font-family: inherit;
                    margin-bottom: 20px;
                    transition: border-color 0.2s ease;
                "
                ${required ? 'required' : ''}
            ></textarea>
            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button 
                    id="dialog-cancel"
                    style="
                        background: #f3f4f6;
                        color: #6b7280;
                        border: none;
                        padding: 10px 20px;
                        border-radius: 8px;
                        cursor: pointer;
                        font-weight: 500;
                        transition: background 0.2s ease;
                    "
                >Cancel</button>
                <button 
                    id="dialog-confirm"
                    style="
                        background: #667eea;
                        color: white;
                        border: none;
                        padding: 10px 20px;
                        border-radius: 8px;
                        cursor: pointer;
                        font-weight: 500;
                        transition: background 0.2s ease;
                    "
                >Confirm</button>
            </div>
        `;
        
        overlay.appendChild(dialog);
        document.body.appendChild(overlay);
        
        // Show with animation
        setTimeout(() => {
            dialog.style.transform = 'scale(1)';
        }, 10);
        
        // Focus input
        const input = dialog.querySelector('#dialog-input');
        input.focus();
        
        // Input focus styling
        input.addEventListener('focus', () => {
            input.style.borderColor = '#667eea';
            input.style.boxShadow = '0 0 0 3px rgba(102, 126, 234, 0.1)';
        });
        
        input.addEventListener('blur', () => {
            input.style.borderColor = '#e5e7eb';
            input.style.boxShadow = 'none';
        });
        
        // Button hover effects
        const cancelBtn = dialog.querySelector('#dialog-cancel');
        const confirmBtn = dialog.querySelector('#dialog-confirm');
        
        cancelBtn.addEventListener('mouseenter', () => {
            cancelBtn.style.background = '#e5e7eb';
        });
        
        cancelBtn.addEventListener('mouseleave', () => {
            cancelBtn.style.background = '#f3f4f6';
        });
        
        confirmBtn.addEventListener('mouseenter', () => {
            confirmBtn.style.background = '#5a67d8';
        });
        
        confirmBtn.addEventListener('mouseleave', () => {
            confirmBtn.style.background = '#667eea';
        });
        
        // Handle actions
        function closeDialog(result) {
            dialog.style.transform = 'scale(0.9)';
            overlay.style.opacity = '0';
            
            setTimeout(() => {
                document.body.removeChild(overlay);
                resolve(result);
            }, 200);
        }
        
        cancelBtn.addEventListener('click', () => closeDialog(null));
        confirmBtn.addEventListener('click', () => {
            const value = input.value.trim();
            if (required && !value) {
                input.style.borderColor = '#ef4444';
                input.focus();
                return;
            }
            closeDialog(value);
        });
        
        // Enter key to confirm
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && e.ctrlKey) {
                confirmBtn.click();
            }
        });
        
        // Escape to cancel
        overlay.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                cancelBtn.click();
            }
        });
        
        // Click outside to cancel
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                cancelBtn.click();
            }
        });
    });
}

// Individual request approve/reject functions with modern UI
async function approveRequest(requestId) {
    try {
        const notes = await showInputDialog(
            'Approve Request',
            'Add review notes (optional)...',
            false
        );
        
        if (notes !== null) {
            // Show loading toast
            showToast('info', 'Processing...', 'Approving request, please wait...', 2000);
            
            // Create and submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/update-requests/${requestId}/approve`;
            form.style.display = 'none';
            
            // Add CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken.getAttribute('content');
                form.appendChild(csrfInput);
            } else {
                showToast('error', 'Security Error', 'Security token not found. Please refresh the page and try again.');
                return;
            }
            
            // Add review notes
            const notesInput = document.createElement('input');
            notesInput.type = 'hidden';
            notesInput.name = 'review_notes';
            notesInput.value = notes || '';
            form.appendChild(notesInput);
            
            // Submit form
            document.body.appendChild(form);
            form.submit();
        }
    } catch (error) {
        showToast('error', 'Error', 'An unexpected error occurred. Please try again.');
        console.error('Error in approveRequest:', error);
    }
}

async function rejectRequest(requestId) {
    try {
        const notes = await showInputDialog(
            'Reject Request',
            'Please provide a reason for rejection (required)...',
            true
        );
        
        if (notes !== null && notes.trim() !== '') {
            // Show loading toast
            showToast('info', 'Processing...', 'Rejecting request, please wait...', 2000);
            
            // Create and submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/update-requests/${requestId}/reject`;
            form.style.display = 'none';
            
            // Add CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken.getAttribute('content');
                form.appendChild(csrfInput);
            } else {
                showToast('error', 'Security Error', 'Security token not found. Please refresh the page and try again.');
                return;
            }
            
            // Add review notes
            const notesInput = document.createElement('input');
            notesInput.type = 'hidden';
            notesInput.name = 'review_notes';
            notesInput.value = notes;
            form.appendChild(notesInput);
            
            // Submit form
            document.body.appendChild(form);
            form.submit();
        }
    } catch (error) {
        showToast('error', 'Error', 'An unexpected error occurred. Please try again.');
        console.error('Error in rejectRequest:', error);
    }
}

// Show toast notifications from Laravel session messages
document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
        showToast('success', 'Success!', '{{ session('success') }}', 6000);
    @endif
    
    @if(session('error'))
        showToast('error', 'Error!', '{{ session('error') }}', 6000);
    @endif
    
    @if(session('warning'))
        showToast('warning', 'Warning!', '{{ session('warning') }}', 6000);
    @endif
    
    @if(session('info'))
        showToast('info', 'Info', '{{ session('info') }}', 6000);
    @endif
});

// Auto-refresh every 30 seconds for pending requests
if ({{ $stats['pending'] }} > 0) {
    setTimeout(() => {
        window.location.reload();
    }, 30000);
}heckbox:checked');
    return Array.from(checkboxes).map(checkbox => checkbox.value);
}

// Modern Toast Notification System
function showToast(type, title, message, duration = 5000) {
    const toastContainer = document.getElementById('toast-container');
    
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    
    // Toast content
    toast.innerHTML = `
        <div class="toast-header">
            <div class="toast-icon">
                <i class="fas ${getToastIcon(type)}"></i>
            </div>
            <div class="toast-title">${title}</div>
            <button class="toast-close" onclick="hideToast(this.closest('.toast'))">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="toast-body">${message}</div>
        <div class="toast-progress"></div>
    `;
    
    // Add to container
    toastContainer.appendChild(toast);
    
    // Show toast with animation
    setTimeout(() => {
        toast.classList.add('show');
    }, 100);
    
    // Progress bar animation
    const progressBar = toast.querySelector('.toast-progress');
    progressBar.style.width = '100%';
    progressBar.style.transitionDuration = duration + 'ms';
    
    setTimeout(() => {
        progressBar.style.width = '0%';
    }, 200);
    
    // Auto hide
    setTimeout(() => {
        hideToast(toast);
    }, duration);
}

function hideToast(toast) {
    toast.classList.remove('show');
    toast.classList.add('hide');
    
    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 400);
}

function getToastIcon(type) {
    const icons = {
        'success': 'fa-check',
        'error': 'fa-times',
        'warning': 'fa-exclamation-triangle',
        'info': 'fa-info'
    };
    return icons[type] || 'fa-info';
}

// Modern Input Dialog
function showInputDialog(title, placeholder, required = false) {
    return new Promise((resolve) => {
        // Create overlay
        const overlay = document.createElement('div');
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(4px);
        `;
        
        // Create dialog
        const dialog = document.createElement('div');
        dialog.style.cssText = `
            background: white;
            border-radius: 16px;
            padding: 24px;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            transform: scale(0.9);
            transition: transform 0.2s ease;
        `;
        
        dialog.innerHTML = `
            <h3 style="margin: 0 0 16px 0; color: #1f2937; font-size: 1.25rem; font-weight: 600;">${title}</h3>
            <textarea 
                id="dialog-input" 
                placeholder="${placeholder}"
                style="
                    width: 100%;
                    min-height: 80px;
                    border: 2px solid #e5e7eb;
                    border-radius: 8px;
                    padding: 12px;
                    font-size: 14px;
                    resize: vertical;
                    font-family: inherit;
                    margin-bottom: 20px;
                    transition: border-color 0.2s ease;
                "
                ${required ? 'required' : ''}
            ></textarea>
            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button 
                    id="dialog-cancel"
                    style="
                        background: #f3f4f6;
                        color: #6b7280;
                        border: none;
                        padding: 10px 20px;
                        border-radius: 8px;
                        cursor: pointer;
                        font-weight: 500;
                        transition: background 0.2s ease;
                    "
                >Cancel</button>
                <button 
                    id="dialog-confirm"
                    style="
                        background: #667eea;
                        color: white;
                        border: none;
                        padding: 10px 20px;
                        border-radius: 8px;
                        cursor: pointer;
                        font-weight: 500;
                        transition: background 0.2s ease;
                    "
                >Confirm</button>
            </div>
        `;
        
        overlay.appendChild(dialog);
        document.body.appendChild(overlay);
        
        // Show with animation
        setTimeout(() => {
            dialog.style.transform = 'scale(1)';
        }, 10);
        
        // Focus input
        const input = dialog.querySelector('#dialog-input');
        input.focus();
        
        // Input focus styling
        input.addEventListener('focus', () => {
            input.style.borderColor = '#667eea';
            input.style.boxShadow = '0 0 0 3px rgba(102, 126, 234, 0.1)';
        });
        
        input.addEventListener('blur', () => {
            input.style.borderColor = '#e5e7eb';
            input.style.boxShadow = 'none';
        });
        
        // Button hover effects
        const cancelBtn = dialog.querySelector('#dialog-cancel');
        const confirmBtn = dialog.querySelector('#dialog-confirm');
        
        cancelBtn.addEventListener('mouseenter', () => {
            cancelBtn.style.background = '#e5e7eb';
        });
        
        cancelBtn.addEventListener('mouseleave', () => {
            cancelBtn.style.background = '#f3f4f6';
        });
        
        confirmBtn.addEventListener('mouseenter', () => {
            confirmBtn.style.background = '#5a67d8';
        });
        
        confirmBtn.addEventListener('mouseleave', () => {
            confirmBtn.style.background = '#667eea';
        });
        
        // Handle actions
        function closeDialog(result) {
            dialog.style.transform = 'scale(0.9)';
            overlay.style.opacity = '0';
            
            setTimeout(() => {
                document.body.removeChild(overlay);
                resolve(result);
            }, 200);
        }
        
        cancelBtn.addEventListener('click', () => closeDialog(null));
        confirmBtn.addEventListener('click', () => {
            const value = input.value.trim();
            if (required && !value) {
                input.style.borderColor = '#ef4444';
                input.focus();
                return;
            }
            closeDialog(value);
        });
        
        // Enter key to confirm
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && e.ctrlKey) {
                confirmBtn.click();
            }
        });
        
        // Escape to cancel
        overlay.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                cancelBtn.click();
            }
        });
        
        // Click outside to cancel
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                cancelBtn.click();
            }
        });
    });
}

// Individual request approve/reject functions with modern UI
async function approveRequest(requestId) {
    try {
        const notes = await showInputDialog(
            'Approve Request',
            'Add review notes (optional)...',
            false
        );
        
        if (notes !== null) {
            // Show loading toast
            showToast('info', 'Processing...', 'Approving request, please wait...', 2000);
            
            // Create and submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/update-requests/${requestId}/approve`;
            form.style.display = 'none';
            
            // Add CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken.getAttribute('content');
                form.appendChild(csrfInput);
            } else {
                showToast('error', 'Security Error', 'Security token not found. Please refresh the page and try again.');
                return;
            }
            
            // Add review notes
            const notesInput = document.createElement('input');
            notesInput.type = 'hidden';
            notesInput.name = 'review_notes';
            notesInput.value = notes || '';
            form.appendChild(notesInput);
            
            // Submit form
            document.body.appendChild(form);
            form.submit();
        }
    } catch (error) {
        showToast('error', 'Error', 'An unexpected error occurred. Please try again.');
        console.error('Error in approveRequest:', error);
    }
}

async function rejectRequest(requestId) {
    try {
        const notes = await showInputDialog(
            'Reject Request',
            'Please provide a reason for rejection (required)...',
            true
        );
        
        if (notes !== null && notes.trim() !== '') {
            // Show loading toast
            showToast('info', 'Processing...', 'Rejecting request, please wait...', 2000);
            
            // Create and submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/update-requests/${requestId}/reject`;
            form.style.display = 'none';
            
            // Add CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken.getAttribute('content');
                form.appendChild(csrfInput);
            } else {
                showToast('error', 'Security Error', 'Security token not found. Please refresh the page and try again.');
                return;
            }
            
            // Add review notes
            const notesInput = document.createElement('input');
            notesInput.type = 'hidden';
            notesInput.name = 'review_notes';
            notesInput.value = notes;
            form.appendChild(notesInput);
            
            // Submit form
            document.body.appendChild(form);
            form.submit();
        }
    } catch (error) {
        showToast('error', 'Error', 'An unexpected error occurred. Please try again.');
        console.error('Error in rejectRequest:', error);
    }
}

// Show toast notifications from Laravel session messages
document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
        showToast('success', 'Success!', '{{ session('success') }}', 6000);
    @endif
    
    @if(session('error'))
        showToast('error', 'Error!', '{{ session('error') }}', 6000);
    @endif
    
    @if(session('warning'))
        showToast('warning', 'Warning!', '{{ session('warning') }}', 6000);
    @endif
    
    @if(session('info'))
        showToast('info', 'Info', '{{ session('info') }}', 6000);
    @endif
});

// Auto-refresh every 30 seconds for pending requests
if ({{ $stats['pending'] }} > 0) {
    setTimeout(() => {
        window.location.reload();
    }, 30000);
}heckbox:checked');
    return Array.from(checkboxes).map(checkbox => checkbox.value);
}

// Modern Toast Notification System
function showToast(type, title, message, duration = 5000) {
    const toastContainer = document.getElementById('toast-container');
    
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    
    // Toast content
    toast.innerHTML = `
        <div class="toast-header">
            <div class="toast-icon">
                <i class="fas ${getToastIcon(type)}"></i>
            </div>
            <div class="toast-title">${title}</div>
            <button class="toast-close" onclick="hideToast(this.closest('.toast'))">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="toast-body">${message}</div>
        <div class="toast-progress"></div>
    `;
    
    // Add to container
    toastContainer.appendChild(toast);
    
    // Show toast with animation
    setTimeout(() => {
        toast.classList.add('show');
    }, 100);
    
    // Progress bar animation
    const progressBar = toast.querySelector('.toast-progress');
    progressBar.style.width = '100%';
    progressBar.style.transitionDuration = duration + 'ms';
    
    setTimeout(() => {
        progressBar.style.width = '0%';
    }, 200);
    
    // Auto hide
    setTimeout(() => {
        hideToast(toast);
    }, duration);
}

function hideToast(toast) {
    toast.classList.remove('show');
    toast.classList.add('hide');
    
    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 400);
}

function getToastIcon(type) {
    const icons = {
        'success': 'fa-check',
        'error': 'fa-times',
        'warning': 'fa-exclamation-triangle',
        'info': 'fa-info'
    };
    return icons[type] || 'fa-info';
}

// Modern Input Dialog
function showInputDialog(title, placeholder, required = false) {
    return new Promise((resolve) => {
        // Create overlay
        const overlay = document.createElement('div');
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(4px);
        `;
        
        // Create dialog
        const dialog = document.createElement('div');
        dialog.style.cssText = `
            background: white;
            border-radius: 16px;
            padding: 24px;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            transform: scale(0.9);
            transition: transform 0.2s ease;
        `;
        
        dialog.innerHTML = `
            <h3 style="margin: 0 0 16px 0; color: #1f2937; font-size: 1.25rem; font-weight: 600;">${title}</h3>
            <textarea 
                id="dialog-input" 
                placeholder="${placeholder}"
                style="
                    width: 100%;
                    min-height: 80px;
                    border: 2px solid #e5e7eb;
                    border-radius: 8px;
                    padding: 12px;
                    font-size: 14px;
                    resize: vertical;
                    font-family: inherit;
                    margin-bottom: 20px;
                    transition: border-color 0.2s ease;
                "
                ${required ? 'required' : ''}
            ></textarea>
            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button 
                    id="dialog-cancel"
                    style="
                        background: #f3f4f6;
                        color: #6b7280;
                        border: none;
                        padding: 10px 20px;
                        border-radius: 8px;
                        cursor: pointer;
                        font-weight: 500;
                        transition: background 0.2s ease;
                    "
                >Cancel</button>
                <button 
                    id="dialog-confirm"
                    style="
                        background: #667eea;
                        color: white;
                        border: none;
                        padding: 10px 20px;
                        border-radius: 8px;
                        cursor: pointer;
                        font-weight: 500;
                        transition: background 0.2s ease;
                    "
                >Confirm</button>
            </div>
        `;
        
        overlay.appendChild(dialog);
        document.body.appendChild(overlay);
        
        // Show with animation
        setTimeout(() => {
            dialog.style.transform = 'scale(1)';
        }, 10);
        
        // Focus input
        const input = dialog.querySelector('#dialog-input');
        input.focus();
        
        // Input focus styling
        input.addEventListener('focus', () => {
            input.style.borderColor = '#667eea';
            input.style.boxShadow = '0 0 0 3px rgba(102, 126, 234, 0.1)';
        });
        
        input.addEventListener('blur', () => {
            input.style.borderColor = '#e5e7eb';
            input.style.boxShadow = 'none';
        });
        
        // Button hover effects
        const cancelBtn = dialog.querySelector('#dialog-cancel');
        const confirmBtn = dialog.querySelector('#dialog-confirm');
        
        cancelBtn.addEventListener('mouseenter', () => {
            cancelBtn.style.background = '#e5e7eb';
        });
        
        cancelBtn.addEventListener('mouseleave', () => {
            cancelBtn.style.background = '#f3f4f6';
        });
        
        confirmBtn.addEventListener('mouseenter', () => {
            confirmBtn.style.background = '#5a67d8';
        });
        
        confirmBtn.addEventListener('mouseleave', () => {
            confirmBtn.style.background = '#667eea';
        });
        
        // Handle actions
        function closeDialog(result) {
            dialog.style.transform = 'scale(0.9)';
            overlay.style.opacity = '0';
            
            setTimeout(() => {
                document.body.removeChild(overlay);
                resolve(result);
            }, 200);
        }
        
        cancelBtn.addEventListener('click', () => closeDialog(null));
        confirmBtn.addEventListener('click', () => {
            const value = input.value.trim();
            if (required && !value) {
                input.style.borderColor = '#ef4444';
                input.focus();
                return;
            }
            closeDialog(value);
        });
        
        // Enter key to confirm
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && e.ctrlKey) {
                confirmBtn.click();
            }
        });
        
        // Escape to cancel
        overlay.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                cancelBtn.click();
            }
        });
        
        // Click outside to cancel
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                cancelBtn.click();
            }
        });
    });
}

// Individual request approve/reject functions with modern UI
async function approveRequest(requestId) {
    try {
        const notes = await showInputDialog(
            'Approve Request',
            'Add review notes (optional)...',
            false
        );
        
        if (notes !== null) {
            // Show loading toast
            showToast('info', 'Processing...', 'Approving request, please wait...', 2000);
            
            // Create and submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/update-requests/${requestId}/approve`;
            form.style.display = 'none';
            
            // Add CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken.getAttribute('content');
                form.appendChild(csrfInput);
            } else {
                showToast('error', 'Security Error', 'Security token not found. Please refresh the page and try again.');
                return;
            }
            
            // Add review notes
            const notesInput = document.createElement('input');
            notesInput.type = 'hidden';
            notesInput.name = 'review_notes';
            notesInput.value = notes || '';
            form.appendChild(notesInput);
            
            // Submit form
            document.body.appendChild(form);
            form.submit();
        }
    } catch (error) {
        showToast('error', 'Error', 'An unexpected error occurred. Please try again.');
        console.error('Error in approveRequest:', error);
    }
}

async function rejectRequest(requestId) {
    try {
        const notes = await showInputDialog(
            'Reject Request',
            'Please provide a reason for rejection (required)...',
            true
        );
        
        if (notes !== null && notes.trim() !== '') {
            // Show loading toast
            showToast('info', 'Processing...', 'Rejecting request, please wait...', 2000);
            
            // Create and submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/update-requests/${requestId}/reject`;
            form.style.display = 'none';
            
            // Add CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken.getAttribute('content');
                form.appendChild(csrfInput);
            } else {
                showToast('error', 'Security Error', 'Security token not found. Please refresh the page and try again.');
                return;
            }
            
            // Add review notes
            const notesInput = document.createElement('input');
            notesInput.type = 'hidden';
            notesInput.name = 'review_notes';
            notesInput.value = notes;
            form.appendChild(notesInput);
            
            // Submit form
            document.body.appendChild(form);
            form.submit();
        }
    } catch (error) {
        showToast('error', 'Error', 'An unexpected error occurred. Please try again.');
        console.error('Error in rejectRequest:', error);
    }
}

// Auto-refresh every 30 seconds for pending requests
if ({{ $stats['pending'] }} > 0) {
    setTimeout(() => {
        window.location.reload();
    }, 30000);
}
</script>

</script>

<script>
// Clean working approve/reject functions
function handleApprove(requestId) {
    console.log('✅ Approve request called for ID:', requestId);
    
    const notes = prompt('Add review notes (optional):');
    if (notes !== null) { // User didn't cancel
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/update-requests/${requestId}/approve`;
        form.style.display = 'none';
        
        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken.getAttribute('content');
            form.appendChild(csrfInput);
        } else {
            alert('Security token not found. Please refresh the page.');
            return;
        }
        
        // Add review notes
        const notesInput = document.createElement('input');
        notesInput.type = 'hidden';
        notesInput.name = 'review_notes';
        notesInput.value = notes || '';
        form.appendChild(notesInput);
        
        // Submit form
        document.body.appendChild(form);
        console.log('📤 Submitting approval form...');
        form.submit();
    }
}

function handleReject(requestId) {
    console.log('❌ Reject request called for ID:', requestId);
    
    const reason = prompt('Please provide a reason for rejection (required):');
    if (reason && reason.trim() !== '') {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/update-requests/${requestId}/reject`;
        form.style.display = 'none';
        
        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken.getAttribute('content');
            form.appendChild(csrfInput);
        } else {
            alert('Security token not found. Please refresh the page.');
            return;
        }
        
        // Add rejection reason
        const reasonInput = document.createElement('input');
        reasonInput.type = 'hidden';
        reasonInput.name = 'review_notes';
        reasonInput.value = reason;
        form.appendChild(reasonInput);
        
        // Submit form
        document.body.appendChild(form);
        console.log('📤 Submitting rejection form...');
        form.submit();
    } else if (reason !== null) {
        alert('Rejection reason is required!');
        handleReject(requestId); // Try again
    }
}

// Bulk actions
function bulkApprove() {
    const selectedIds = getSelectedIds();
    
    if (selectedIds.length === 0) {
        alert('Please select at least one request to approve.');
        return;
    }
    
    if (confirm(`Are you sure you want to approve ${selectedIds.length} selected request(s)?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.update-requests.bulk-approve") }}';
        
        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken.getAttribute('content');
            form.appendChild(csrfInput);
        }
        
        // Add selected IDs
        selectedIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'request_ids[]';
            input.value = id;
            form.appendChild(input);
        });
        
        document.body.appendChild(form);
        form.submit();
    }
}

function bulkReject() {
    const selectedIds = getSelectedIds();
    
    if (selectedIds.length === 0) {
        alert('Please select at least one request to reject.');
        return;
    }
    
    document.getElementById('bulkRejectIds').value = JSON.stringify(selectedIds);
    
    const modal = new bootstrap.Modal(document.getElementById('bulkRejectModal'));
    modal.show();
}

function getSelectedIds() {
    const checkboxes = document.querySelectorAll('.request-checkbox:checked');
    return Array.from(checkboxes).map(cb => cb.value);
}

// Problem request functions
function handleApproveProblem(requestId) {
    console.log('✅ Approve problem request called for ID:', requestId);
    
    const notes = prompt('Add review notes (optional):');
    if (notes !== null) { // User didn't cancel
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/update-requests/${requestId}/approve`;
        form.style.display = 'none';
        
        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken.getAttribute('content');
            form.appendChild(csrfInput);
        } else {
            alert('Security token not found. Please refresh the page.');
            return;
        }
        
        // Add review notes
        const notesInput = document.createElement('input');
        notesInput.type = 'hidden';
        notesInput.name = 'review_notes';
        notesInput.value = notes || '';
        form.appendChild(notesInput);
        
        // Submit form
        document.body.appendChild(form);
        console.log('📤 Creating problem from request...');
        form.submit();
    }
}

function handleRejectProblem(requestId) {
    console.log('❌ Reject problem request called for ID:', requestId);
    
    const reason = prompt('Please provide a reason for rejection (required):');
    if (reason && reason.trim() !== '') {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/update-requests/${requestId}/reject`;
        form.style.display = 'none';
        
        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken.getAttribute('content');
            form.appendChild(csrfInput);
        } else {
            alert('Security token not found. Please refresh the page.');
            return;
        }
        
        // Add rejection reason
        const reasonInput = document.createElement('input');
        reasonInput.type = 'hidden';
        reasonInput.name = 'review_notes';
        reasonInput.value = reason;
        form.appendChild(reasonInput);
        
        // Submit form
        document.body.appendChild(form);
        console.log('📤 Rejecting problem request...');
        form.submit();
    } else if (reason !== null) {
        alert('Rejection reason is required!');
        handleRejectProblem(requestId); // Try again
    }
}

// Problem request bulk actions
function toggleSelectAllProblems() {
    const selectAll = document.getElementById('selectAllProblems');
    const checkboxes = document.querySelectorAll('.problem-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

function bulkApproveProblem() {
    const selectedIds = getSelectedProblemIds();
    
    if (selectedIds.length === 0) {
        alert('Please select at least one problem request to approve.');
        return;
    }
    
    if (confirm(`Are you sure you want to create ${selectedIds.length} problem(s) from the selected request(s)?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.update-requests.bulk-approve") }}';
        
        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken.getAttribute('content');
            form.appendChild(csrfInput);
        }
        
        // Add selected IDs
        selectedIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'request_ids[]';
            input.value = id;
            form.appendChild(input);
        });
        
        document.body.appendChild(form);
        form.submit();
    }
}

function bulkRejectProblem() {
    const selectedIds = getSelectedProblemIds();
    
    if (selectedIds.length === 0) {
        alert('Please select at least one problem request to reject.');
        return;
    }
    
    const reason = prompt('Please provide a reason for bulk rejection (required):');
    if (reason && reason.trim() !== '') {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.update-requests.bulk-reject") }}';
        
        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken.getAttribute('content');
            form.appendChild(csrfInput);
        }
        
        // Add selected IDs
        selectedIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'request_ids[]';
            input.value = id;
            form.appendChild(input);
        });
        
        // Add bulk reject reason
        const reasonInput = document.createElement('input');
        reasonInput.type = 'hidden';
        reasonInput.name = 'bulk_reject_reason';
        reasonInput.value = reason;
        form.appendChild(reasonInput);
        
        document.body.appendChild(form);
        form.submit();
    } else if (reason !== null) {
        alert('Rejection reason is required!');
        bulkRejectProblem(); // Try again
    }
}

function getSelectedProblemIds() {
    const checkboxes = document.querySelectorAll('.problem-checkbox:checked');
    return Array.from(checkboxes).map(cb => cb.value);
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Admin Update Requests page loaded');
    console.log('✅ Functions available:', {
        handleApprove: typeof handleApprove,
        handleReject: typeof handleReject,
        handleApproveProblem: typeof handleApproveProblem,
        handleRejectProblem: typeof handleRejectProblem,
        bulkApprove: typeof bulkApprove,
        bulkReject: typeof bulkReject,
        bulkApproveProblem: typeof bulkApproveProblem,
        bulkRejectProblem: typeof bulkRejectProblem
    });
});
</script>

@endsection