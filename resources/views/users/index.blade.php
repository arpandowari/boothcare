@extends('layouts.app')

@section('title', 'User Management - Boothcare')
@section('page-title', 'User Management')

@push('styles')
<style>
    /* Mobile App-like Design for Users */
    body {
        background: #f8fafc;
    }

    /* Mobile App Header */
    .mobile-app-header {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 20px 16px;
        margin: -16px -16px 20px -16px;
        border-radius: 0 0 25px 25px;
        box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
    }

    .mobile-app-header h1 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 700;
        text-align: center;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .mobile-app-header .header-subtitle {
        text-align: center;
        opacity: 0.9;
        font-size: 0.9rem;
        margin-top: 4px;
    }

    /* Mobile Stats Grid */
    .mobile-stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        margin-bottom: 20px;
    }

    .mobile-stat-card {
        background: white;
        border-radius: 16px;
        padding: 16px;
        text-align: center;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        border: 1px solid #f1f3f4;
        transition: all 0.3s ease;
    }

    .mobile-stat-card:active {
        transform: scale(0.98);
        box-shadow: 0 1px 6px rgba(0,0,0,0.12);
    }

    .mobile-stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 8px;
        font-size: 1.2rem;
    }

    .mobile-stat-number {
        font-size: 1.4rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 2px;
    }

    .mobile-stat-label {
        font-size: 0.75rem;
        color: #666;
        font-weight: 500;
    }

    /* User Cards */
    .users-container {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .user-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .user-card:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        transform: translateY(-2px);
    }

    .user-card-header {
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .user-card-header:hover {
        background-color: #f8f9fa;
    }

    .user-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        overflow: hidden;
        flex-shrink: 0;
    }

    .user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 1.2rem;
    }

    .user-info {
        flex: 1;
        min-width: 0;
    }

    .user-name {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 4px;
    }

    .user-email, .user-phone {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 2px;
    }

    .user-badges {
        display: flex;
        flex-direction: column;
        gap: 4px;
        align-items: flex-end;
    }

    .role-badge, .status-badge {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
        color: white;
    }

    /* Quick Actions */
    .user-quick-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .quick-delete-btn {
        padding: 4px 8px;
        border: 1px solid #dc3545;
        background: transparent;
        color: #dc3545;
        border-radius: 6px;
        transition: all 0.2s ease;
        font-size: 0.8rem;
    }

    .quick-delete-btn:hover {
        background: #dc3545;
        color: white;
        transform: scale(1.05);
    }

    .quick-delete-btn:active {
        transform: scale(0.95);
    }

    .quick-delete-btn.btn-danger {
        background: #dc3545;
        color: white;
        border: 1px solid #dc3545;
    }

    .quick-delete-btn.btn-danger:hover {
        background: #c82333;
        border-color: #bd2130;
        transform: scale(1.05);
    }

    .expand-icon {
        color: #666;
        transition: transform 0.3s ease;
    }

    .user-card.expanded .expand-icon {
        transform: rotate(180deg);
    }

    .user-card-details {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
        background: #f8f9fa;
    }

    .user-card.expanded .user-card-details {
        max-height: 500px;
    }

    .details-content {
        padding: 20px;
    }

    .detail-item {
        margin-bottom: 8px;
        font-size: 0.9rem;
    }

    .detail-item strong {
        color: #1a1a1a;
        margin-right: 8px;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        padding-top: 16px;
        border-top: 1px solid #e9ecef;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    /* Floating Action Button */
    .fab {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4285f4, #34a853);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(66, 133, 244, 0.3);
        transition: all 0.3s ease;
        z-index: 1000;
    }

    .fab:hover {
        transform: scale(1.1);
        color: white;
        box-shadow: 0 6px 20px rgba(66, 133, 244, 0.4);
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

        .user-card-header {
            padding: 16px;
            gap: 12px;
        }
        
        .user-avatar {
            width: 50px;
            height: 50px;
        }
        
        .avatar-placeholder {
            font-size: 1rem;
        }
        
        .user-name {
            font-size: 1rem;
        }
        
        .user-email, .user-phone {
            font-size: 0.85rem;
        }
        
        .details-content {
            padding: 16px;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .action-buttons .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Mobile App View -->
    <div class="mobile-view">
        <!-- Mobile App Header -->
        <div class="mobile-app-header">
            <h1><i class="fas fa-users me-2"></i>User Management</h1>
            <div class="header-subtitle">{{ $users->total() }} users in the system</div>
        </div>

        <!-- Mobile Stats Grid -->
        <div class="mobile-stats-grid">
            <div class="mobile-stat-card">
                <div class="mobile-stat-icon bg-danger bg-opacity-10 text-danger">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="mobile-stat-number">{{ $users->where('role', 'admin')->count() }}</div>
                <div class="mobile-stat-label">Administrators</div>
            </div>
            <div class="mobile-stat-card">
                <div class="mobile-stat-icon bg-success bg-opacity-10 text-success">
                    <i class="fas fa-users"></i>
                </div>
                <div class="mobile-stat-number">{{ $users->where('role', 'user')->count() }}</div>
                <div class="mobile-stat-label">Regular Users</div>
            </div>
            <div class="mobile-stat-card">
                <div class="mobile-stat-icon bg-info bg-opacity-10 text-info">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="mobile-stat-number">{{ $users->where('is_active', true)->count() }}</div>
                <div class="mobile-stat-label">Active</div>
            </div>
            <div class="mobile-stat-card">
                <div class="mobile-stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="mobile-stat-number">{{ $users->where('created_at', '>=', now()->subDays(30))->count() }}</div>
                <div class="mobile-stat-label">New (30d)</div>
            </div>
        </div>
    </div>

    <!-- Desktop Header -->
    <div class="desktop-view">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">
                <i class="fas fa-users me-2 text-secondary"></i>
                All Users ({{ $users->total() }})
            </h4>
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>
                Add New User
            </a>
        </div>
    </div>

    <!-- Desktop Statistics Cards -->
    <div class="desktop-view">
        <div class="row mb-4">
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="mobile-stat-icon bg-danger bg-opacity-10 text-danger mx-auto">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <h3 class="h4 mb-1">{{ $users->where('role', 'admin')->count() }}</h3>
                        <p class="text-muted mb-0 small">Administrators</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="mobile-stat-icon bg-success bg-opacity-10 text-success mx-auto">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="h4 mb-1">{{ $users->where('role', 'user')->count() }}</h3>
                        <p class="text-muted mb-0 small">Regular Users</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="mobile-stat-icon bg-info bg-opacity-10 text-info mx-auto">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <h3 class="h4 mb-1">{{ $users->where('is_active', true)->count() }}</h3>
                        <p class="text-muted mb-0 small">Active Users</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="mobile-stat-icon bg-warning bg-opacity-10 text-warning mx-auto">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h3 class="h4 mb-1">{{ $users->where('created_at', '>=', now()->subDays(7))->count() }}</h3>
                        <p class="text-muted mb-0 small">New This Week</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users List -->
    @if($users->count() > 0)
        <div class="users-container">
            @foreach($users as $user)
            <div class="user-card" data-user-id="{{ $user->id }}">
                <div class="user-card-header" onclick="toggleUserDetails({{ $user->id }})">
                    <div class="user-avatar">
                        @if($user->profile_photo)
                            <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}">
                        @else
                            <div class="avatar-placeholder bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'sub_admin' ? 'warning' : 'primary') }}">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                        @endif
                    </div>
                    <div class="user-info">
                        <div class="user-name">
                            {{ $user->name }}
                            @if($user->id === auth()->id())
                                <span class="badge bg-info ms-1">You</span>
                            @endif
                        </div>
                        <div class="user-email">
                            <i class="fas fa-envelope me-1"></i>{{ $user->email }}
                        </div>
                        @if($user->phone)
                        <div class="user-phone">
                            <i class="fas fa-phone me-1"></i>{{ $user->phone }}
                        </div>
                        @endif
                    </div>
                    <div class="user-badges">
                        <span class="role-badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'sub_admin' ? 'warning' : 'success') }}">
                            {{ $user->role === 'admin' ? 'Admin' : ($user->role === 'sub_admin' ? 'Sub Admin' : 'User') }}
                        </span>
                        <span class="status-badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="user-quick-actions">
                        @if($user->id !== auth()->id())
                            <button type="button" class="btn btn-sm btn-outline-danger quick-delete-btn" onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}')" title="Delete User">
                                <i class="fas fa-trash"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger quick-delete-btn" onclick="confirmForceDelete({{ $user->id }}, '{{ $user->name }}')" title="Force Delete User">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        @endif
                        <div class="expand-icon">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>
                </div>
                
                <div class="user-card-details" id="details-{{ $user->id }}">
                    <div class="details-content">
                        <div class="row">
                            <div class="col-md-6">
                                <h6><i class="fas fa-info-circle me-2"></i>Profile Information</h6>
                                <div class="detail-item">
                                    <strong>Full Name:</strong> {{ $user->name }}
                                </div>
                                <div class="detail-item">
                                    <strong>Email:</strong> {{ $user->email }}
                                </div>
                                @if($user->phone)
                                <div class="detail-item">
                                    <strong>Phone:</strong> {{ $user->phone }}
                                </div>
                                @endif
                                <div class="detail-item">
                                    <strong>Role:</strong> {{ ucfirst($user->role) }}
                                </div>
                                <div class="detail-item">
                                    <strong>Status:</strong> 
                                    <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                                @if($user->date_of_birth)
                                <div class="detail-item">
                                    <strong>Date of Birth:</strong> {{ $user->date_of_birth->format('M d, Y') }}
                                </div>
                                @endif
                                @if($user->gender)
                                <div class="detail-item">
                                    <strong>Gender:</strong> {{ ucfirst($user->gender) }}
                                </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h6><i class="fas fa-home me-2"></i>Location Details</h6>
                                @if($user->familyMember)
                                    <div class="detail-item">
                                        <strong>Area:</strong> {{ $user->familyMember->house->booth->area->area_name ?? 'N/A' }}
                                    </div>
                                    <div class="detail-item">
                                        <strong>Booth:</strong> {{ $user->familyMember->house->booth->booth_number ?? 'N/A' }}
                                    </div>
                                    <div class="detail-item">
                                        <strong>House:</strong> {{ $user->familyMember->house->house_number ?? 'N/A' }}
                                    </div>
                                    <div class="detail-item">
                                        <strong>Address:</strong> {{ $user->familyMember->house->address ?? 'N/A' }}
                                    </div>
                                @else
                                    <div class="detail-item text-muted">
                                        <i class="fas fa-info-circle me-1"></i>No location profile linked
                                    </div>
                                @endif
                                
                                <h6 class="mt-3"><i class="fas fa-clock me-2"></i>Account Info</h6>
                                <div class="detail-item">
                                    <strong>Registered:</strong> {{ $user->created_at->format('M d, Y') }}
                                </div>
                                <div class="detail-item">
                                    <strong>Last Updated:</strong> {{ $user->updated_at->diffForHumans() }}
                                </div>
                                @if($user->aadhar_number)
                                <div class="detail-item">
                                    <strong>Aadhar:</strong> {{ $user->aadhar_number }}
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="action-buttons mt-3">
                            <a href="{{ route('users.show', $user) }}" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-eye me-1"></i>View Full Profile
                            </a>
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-edit me-1"></i>Edit User
                            </a>
                            @if($user->id !== auth()->id())
                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}')">
                                    <i class="fas fa-trash me-1"></i>Delete
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmForceDelete({{ $user->id }}, '{{ $user->name }}')">
                                    <i class="fas fa-trash-alt me-1"></i>Force Delete
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $users->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="mobile-view">
            <div class="text-center py-5" style="background: white; border-radius: 16px; margin: 20px 0; box-shadow: 0 2px 12px rgba(0,0,0,0.08);">
                <div style="font-size: 4rem; color: #ddd; margin-bottom: 16px;">
                    <i class="fas fa-users"></i>
                </div>
                <h5 style="color: #666; margin-bottom: 8px;">No users found</h5>
                <p style="color: #888; font-size: 0.9rem; margin-bottom: 20px;">Create your first user account</p>
                <a href="{{ route('users.create') }}" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 12px 24px; border-radius: 25px; text-decoration: none; font-weight: 600;">
                    <i class="fas fa-plus me-2"></i>Create First User
                </a>
            </div>
        </div>
        
        <div class="desktop-view">
            <div class="empty-state">
                <i class="fas fa-users fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">No users found</h5>
                <p class="text-muted">Users can register through the registration page or be added by administrators.</p>
                <a href="{{ route('users.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Add First User
                </a>
            </div>
        </div>
    @endif

    <!-- Mobile Add Button -->
    <a href="{{ route('users.create') }}" class="fab d-block d-md-none">
        <i class="fas fa-plus"></i>
    </a>
</div>

<script>
// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Users page JavaScript loaded');
});

function toggleUserDetails(userId) {
    console.log('Toggling details for user:', userId);
    
    const card = document.querySelector(`[data-user-id="${userId}"]`);
    const details = document.getElementById(`details-${userId}`);
    
    if (!card || !details) {
        console.error('Card or details not found for user:', userId);
        return;
    }
    
    card.classList.toggle('expanded');
    
    if (card.classList.contains('expanded')) {
        details.style.maxHeight = details.scrollHeight + 'px';
        console.log('Expanded user details for:', userId);
    } else {
        details.style.maxHeight = '0';
        console.log('Collapsed user details for:', userId);
    }
}

function confirmDelete(userId, userName) {
    console.log('Delete function called for user:', userId, userName);
    
    // Prevent event bubbling
    if (event) {
        event.stopPropagation();
    }
    
    // Simple fallback approach - use browser confirm
    if (confirm(`Are you sure you want to delete user "${userName}"? This action cannot be undone.`)) {
        // Create and submit form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/users/${userId}`;
        
        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken.getAttribute('content');
            form.appendChild(csrfInput);
        }
        
        // Add DELETE method
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);
        
        // Submit form
        document.body.appendChild(form);
        form.submit();
    }
}

function confirmForceDelete(userId, userName) {
    console.log('Force delete function called for user:', userId, userName);
    
    // Prevent event bubbling
    if (event) {
        event.stopPropagation();
    }
    
    // Double confirmation for force delete
    if (confirm(`⚠️ FORCE DELETE WARNING ⚠️\n\nThis will permanently delete user "${userName}" and ALL related data including:\n- Problems reported/assigned\n- Update requests\n- Family member profiles\n- All other relationships\n\nThis action CANNOT be undone!\n\nAre you absolutely sure?`)) {
        if (confirm(`Final confirmation: Delete "${userName}" and all related data permanently?`)) {
            // Create and submit form for force delete
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/users/${userId}/force`;
            
            // Add CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken.getAttribute('content');
                form.appendChild(csrfInput);
            }
            
            // Add DELETE method
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);
            
            // Submit form
            document.body.appendChild(form);
            form.submit();
        }
    }
}

// Close expanded cards when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.user-card')) {
        document.querySelectorAll('.user-card.expanded').forEach(card => {
            card.classList.remove('expanded');
            const userId = card.dataset.userId;
            const details = document.getElementById(`details-${userId}`);
            if (details) {
                details.style.maxHeight = '0';
            }
        });
    }
});

// Prevent card expansion when clicking action buttons
document.addEventListener('click', function(e) {
    if (e.target.closest('.quick-delete-btn') || e.target.closest('.action-buttons')) {
        e.stopPropagation();
    }
});
</script>

@endsection