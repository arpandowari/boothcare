@extends('layouts.app')

@section('title', 'Sub-Admin Management - Admin')
@section('page-title', 'Sub-Admin Management')

@push('styles')
<style>
    .sub-admin-card {
        border-left: 4px solid #f59e0b;
        transition: all 0.3s ease;
        border-radius: 12px;
        overflow: hidden;
    }
    
    .sub-admin-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .sub-admin-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.2rem;
    }
    
    .permission-badge {
        background: #fef3c7;
        color: #92400e;
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        margin: 0.125rem;
        display: inline-block;
    }
    
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.8rem;
    }
    
    .status-active {
        background: #d1fae5;
        color: #065f46;
    }
    
    .status-inactive {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }
    
    .stat-item {
        text-align: center;
        padding: 0.75rem;
        background: #f8fafc;
        border-radius: 8px;
    }
    
    .stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
    }
    
    .stat-label {
        font-size: 0.75rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .btn-action {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #6b7280;
    }
    
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1.5rem;
        opacity: 0.5;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Sub-Admin Management</h1>
            <p class="text-muted">Manage sub-administrators and their permissions</p>
        </div>
        <a href="{{ route('admin.sub-admins.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Create Sub-Admin
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h3 class="mb-0">{{ $subAdmins->total() }}</h3>
                            <p class="mb-0">Total Sub-Admins</p>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-user-shield fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h3 class="mb-0">{{ $subAdmins->where('is_active', true)->count() }}</h3>
                            <p class="mb-0">Active Sub-Admins</p>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-check-circle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h3 class="mb-0">{{ $subAdmins->where('is_active', false)->count() }}</h3>
                            <p class="mb-0">Inactive Sub-Admins</p>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-times-circle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h3 class="mb-0">{{ $subAdmins->where('created_at', '>=', now()->subDays(30))->count() }}</h3>
                            <p class="mb-0">Created This Month</p>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-calendar-plus fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sub-Admins List -->
    @if($subAdmins->count() > 0)
        <div class="row">
            @foreach($subAdmins as $subAdmin)
                <div class="col-lg-6 col-xl-4 mb-4">
                    <div class="card sub-admin-card h-100">
                        <div class="card-body">
                            <!-- Header -->
                            <div class="d-flex align-items-start mb-3">
                                <div class="sub-admin-avatar me-3">
                                    {{ strtoupper(substr($subAdmin->name, 0, 2)) }}
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-1">{{ $subAdmin->name }}</h5>
                                    <p class="text-muted mb-2">{{ $subAdmin->email }}</p>
                                    <span class="status-badge {{ $subAdmin->is_active ? 'status-active' : 'status-inactive' }}">
                                        <i class="fas fa-{{ $subAdmin->is_active ? 'check' : 'times' }} me-1"></i>
                                        {{ $subAdmin->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('admin.sub-admins.show', $subAdmin) }}">
                                            <i class="fas fa-eye me-2"></i>View Details
                                        </a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.sub-admins.edit', $subAdmin) }}">
                                            <i class="fas fa-edit me-2"></i>Edit
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="#" onclick="toggleStatus({{ $subAdmin->id }})">
                                            <i class="fas fa-{{ $subAdmin->is_active ? 'pause' : 'play' }} me-2"></i>
                                            {{ $subAdmin->is_active ? 'Deactivate' : 'Activate' }}
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteSubAdmin({{ $subAdmin->id }})">
                                            <i class="fas fa-trash me-2"></i>Delete
                                        </a></li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Contact Info -->
                            @if($subAdmin->phone)
                                <div class="mb-3">
                                    <small class="text-muted d-block">Phone</small>
                                    <span>{{ $subAdmin->phone }}</span>
                                </div>
                            @endif

                            <!-- Permissions -->
                            <div class="mb-3">
                                <small class="text-muted d-block mb-2">Permissions ({{ count($subAdmin->permissions ?? []) }})</small>
                                <div class="permissions-list" style="max-height: 100px; overflow-y: auto;">
                                    @if($subAdmin->permissions && count($subAdmin->permissions) > 0)
                                        @foreach(array_slice($subAdmin->permissions, 0, 6) as $permission)
                                            <span class="permission-badge">{{ config('permissions.available_permissions')[$permission] ?? $permission }}</span>
                                        @endforeach
                                        @if(count($subAdmin->permissions) > 6)
                                            <span class="permission-badge">+{{ count($subAdmin->permissions) - 6 }} more</span>
                                        @endif
                                    @else
                                        <span class="text-muted">No permissions assigned</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Statistics -->
                            <div class="stats-grid">
                                <div class="stat-item">
                                    <div class="stat-number">{{ $subAdmin->reportedProblems()->count() }}</div>
                                    <div class="stat-label">Problems</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-number">{{ $subAdmin->assignedProblems()->count() }}</div>
                                    <div class="stat-label">Assigned</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-number">{{ $subAdmin->reviewedRequests()->count() }}</div>
                                    <div class="stat-label">Reviews</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer bg-light">
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                Created {{ $subAdmin->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($subAdmins->hasPages())
            <div class="d-flex justify-content-center">
                {{ $subAdmins->links() }}
            </div>
        @endif
    @else
        <div class="empty-state">
            <i class="fas fa-user-shield"></i>
            <h4>No Sub-Admins Found</h4>
            <p>Create your first sub-administrator to delegate responsibilities.</p>
            <a href="{{ route('admin.sub-admins.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Create Sub-Admin
            </a>
        </div>
    @endif
</div>

<!-- Hidden Forms -->
<form id="toggleForm" method="POST" style="display: none;">
    @csrf
</form>

<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
function toggleStatus(subAdminId) {
    if (confirm('Are you sure you want to change the status of this Sub-Admin?')) {
        const form = document.getElementById('toggleForm');
        form.action = `/admin/sub-admins/${subAdminId}/toggle-status`;
        form.submit();
    }
}

function deleteSubAdmin(subAdminId) {
    if (confirm('Are you sure you want to delete this Sub-Admin? This action cannot be undone.')) {
        const form = document.getElementById('deleteForm');
        form.action = `/admin/sub-admins/${subAdminId}`;
        form.submit();
    }
}
</script>
@endpush