@extends('layouts.app')

@section('title', 'Sub-Admin Details - Admin')
@section('page-title', 'Sub-Admin Details')

@push('styles')
<style>
    .profile-header {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
    }
    
    .profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }
    
    .info-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .info-card-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-weight: 600;
        color: #64748b;
    }
    
    .info-value {
        color: #1e293b;
        font-weight: 500;
    }
    
    .permission-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1rem;
    }
    
    .permission-group-card {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1rem;
    }
    
    .permission-group-title {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .permission-item {
        display: flex;
        align-items: center;
        padding: 0.25rem 0;
        font-size: 0.9rem;
    }
    
    .permission-check {
        color: #10b981;
        margin-right: 0.5rem;
    }
    
    .permission-cross {
        color: #ef4444;
        margin-right: 0.5rem;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.5rem;
        color: white;
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        color: #64748b;
        font-weight: 500;
    }
    
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .status-active {
        background: #d1fae5;
        color: #065f46;
    }
    
    .status-inactive {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .action-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Sub-Admin Details</h1>
            <p class="text-muted">View sub-administrator information and activity</p>
        </div>
        <a href="{{ route('admin.sub-admins.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Sub-Admins
        </a>
    </div>

    <!-- Profile Header -->
    <div class="profile-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="profile-avatar">
                    {{ strtoupper(substr($subAdmin->name, 0, 2)) }}
                </div>
                <h2 class="mb-2">{{ $subAdmin->name }}</h2>
                <p class="mb-3 opacity-75">{{ $subAdmin->email }}</p>
                <span class="status-badge {{ $subAdmin->is_active ? 'status-active' : 'status-inactive' }}">
                    <i class="fas fa-{{ $subAdmin->is_active ? 'check' : 'times' }} me-1"></i>
                    {{ $subAdmin->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="action-buttons">
                    <a href="{{ route('admin.sub-admins.edit', $subAdmin) }}" class="btn btn-light">
                        <i class="fas fa-edit me-2"></i>Edit
                    </a>
                    <button class="btn btn-light" onclick="toggleStatus({{ $subAdmin->id }})">
                        <i class="fas fa-{{ $subAdmin->is_active ? 'pause' : 'play' }} me-2"></i>
                        {{ $subAdmin->is_active ? 'Deactivate' : 'Activate' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: #3b82f6;">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-number">{{ $stats['problems_reported'] }}</div>
            <div class="stat-label">Problems Reported</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: #f59e0b;">
                <i class="fas fa-tasks"></i>
            </div>
            <div class="stat-number">{{ $stats['problems_assigned'] }}</div>
            <div class="stat-label">Problems Assigned</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: #10b981;">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-number">{{ $stats['problems_resolved'] }}</div>
            <div class="stat-label">Problems Resolved</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: #8b5cf6;">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <div class="stat-number">{{ $stats['update_requests_reviewed'] }}</div>
            <div class="stat-label">Requests Reviewed</div>
        </div>
    </div>

    <div class="row">
        <!-- Personal Information -->
        <div class="col-lg-6">
            <div class="info-card">
                <h3 class="info-card-title">
                    <i class="fas fa-user"></i>
                    Personal Information
                </h3>
                
                <div class="info-item">
                    <span class="info-label">Full Name</span>
                    <span class="info-value">{{ $subAdmin->name }}</span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Email</span>
                    <span class="info-value">{{ $subAdmin->email }}</span>
                </div>
                
                @if($subAdmin->phone)
                <div class="info-item">
                    <span class="info-label">Phone</span>
                    <span class="info-value">{{ $subAdmin->phone }}</span>
                </div>
                @endif
                
                @if($subAdmin->date_of_birth)
                <div class="info-item">
                    <span class="info-label">Date of Birth</span>
                    <span class="info-value">{{ $subAdmin->date_of_birth->format('M d, Y') }}</span>
                </div>
                @endif
                
                @if($subAdmin->gender)
                <div class="info-item">
                    <span class="info-label">Gender</span>
                    <span class="info-value">{{ ucfirst($subAdmin->gender) }}</span>
                </div>
                @endif
                
                <div class="info-item">
                    <span class="info-label">Account Created</span>
                    <span class="info-value">{{ $subAdmin->created_at->format('M d, Y') }}</span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Last Updated</span>
                    <span class="info-value">{{ $subAdmin->updated_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>

        <!-- Account Status -->
        <div class="col-lg-6">
            <div class="info-card">
                <h3 class="info-card-title">
                    <i class="fas fa-cog"></i>
                    Account Status
                </h3>
                
                <div class="info-item">
                    <span class="info-label">Role</span>
                    <span class="info-value">
                        <span class="badge bg-warning">Sub Administrator</span>
                    </span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Status</span>
                    <span class="info-value">
                        <span class="status-badge {{ $subAdmin->is_active ? 'status-active' : 'status-inactive' }}">
                            {{ $subAdmin->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Total Permissions</span>
                    <span class="info-value">{{ count($subAdmin->permissions ?? []) }}</span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Login Access</span>
                    <span class="info-value">
                        <i class="fas fa-{{ $subAdmin->is_active ? 'check text-success' : 'times text-danger' }}"></i>
                        {{ $subAdmin->is_active ? 'Allowed' : 'Blocked' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Permissions -->
    <div class="info-card">
        <h3 class="info-card-title">
            <i class="fas fa-shield-alt"></i>
            Permissions
        </h3>
        
        @if($subAdmin->permissions && count($subAdmin->permissions) > 0)
            <div class="permission-grid">
                @foreach(config('permissions.permission_groups') as $groupName => $permissions)
                    <div class="permission-group-card">
                        <div class="permission-group-title">
                            <i class="fas fa-folder-open"></i>
                            {{ $groupName }}
                        </div>
                        
                        @foreach($permissions as $permission)
                            @if(isset(config('permissions.available_permissions')[$permission]))
                                <div class="permission-item">
                                    @if(in_array($permission, $subAdmin->permissions))
                                        <i class="fas fa-check permission-check"></i>
                                    @else
                                        <i class="fas fa-times permission-cross"></i>
                                    @endif
                                    <span class="{{ in_array($permission, $subAdmin->permissions) ? 'text-success' : 'text-muted' }}">
                                        {{ config('permissions.available_permissions')[$permission] }}
                                    </span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-4">
                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                <h5 class="text-muted">No Permissions Assigned</h5>
                <p class="text-muted">This sub-admin has no permissions assigned and cannot access any features.</p>
                <a href="{{ route('admin.sub-admins.edit', $subAdmin) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>Assign Permissions
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Hidden Forms -->
<form id="toggleForm" method="POST" style="display: none;">
    @csrf
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
</script>
@endpush