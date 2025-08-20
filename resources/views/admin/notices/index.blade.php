@extends('layouts.app')

@section('title', 'Notice Management - Admin')
@section('page-title', 'Notice Management')

@push('styles')
<style>
    .notice-card {
        border-left: 4px solid;
        transition: all 0.3s ease;
    }
    
    .notice-card.urgent {
        border-left-color: #dc2626;
    }
    
    .notice-card.important {
        border-left-color: #d97706;
    }
    
    .notice-card.general {
        border-left-color: #2563eb;
    }
    
    .notice-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .notice-type-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-weight: 600;
    }
    
    .notice-type-badge.urgent {
        background: #fee2e2;
        color: #dc2626;
    }
    
    .notice-type-badge.important {
        background: #fef3c7;
        color: #d97706;
    }
    
    .notice-type-badge.general {
        background: #dbeafe;
        color: #2563eb;
    }
    
    .display-location-badge {
        font-size: 0.7rem;
        padding: 0.2rem 0.4rem;
        border-radius: 8px;
        font-weight: 500;
    }
    
    .display-location-badge.marquee {
        background: #f3e8ff;
        color: #7c3aed;
    }
    
    .display-location-badge.card {
        background: #ecfdf5;
        color: #059669;
    }
    
    .display-location-badge.both {
        background: #fef3c7;
        color: #d97706;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Notice Management</h1>
            <p class="text-muted">Manage community notices and announcements</p>
        </div>
        <a href="{{ route('admin.notices.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Create Notice
        </a>
    </div>

    <!-- Notices List -->
    <div class="row">
        @forelse($notices as $notice)
            <div class="col-lg-6 col-xl-4 mb-4">
                <div class="card notice-card {{ $notice->type }} h-100">
                    <div class="card-header d-flex justify-content-between align-items-start">
                        <div>
                            <span class="notice-type-badge {{ $notice->type }}">
                                @if($notice->type === 'urgent')
                                    <i class="fas fa-exclamation-triangle me-1"></i>Urgent
                                @elseif($notice->type === 'important')
                                    <i class="fas fa-info-circle me-1"></i>Important
                                @else
                                    <i class="fas fa-bullhorn me-1"></i>General
                                @endif
                            </span>
                            <span class="display-location-badge {{ $notice->display_location }} ms-2">
                                {{ ucfirst($notice->display_location) }}
                            </span>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('admin.notices.edit', $notice) }}">
                                    <i class="fas fa-edit me-2"></i>Edit
                                </a></li>
                                <li><a class="dropdown-item" href="#" onclick="toggleNotice({{ $notice->id }})">
                                    <i class="fas fa-{{ $notice->is_active ? 'eye-slash' : 'eye' }} me-2"></i>
                                    {{ $notice->is_active ? 'Deactivate' : 'Activate' }}
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#" onclick="deleteNotice({{ $notice->id }})">
                                    <i class="fas fa-trash me-2"></i>Delete
                                </a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <h5 class="card-title">{{ $notice->title }}</h5>
                        <p class="card-text">{{ Str::limit($notice->content, 120) }}</p>
                        
                        <div class="row text-center mt-3">
                            <div class="col-4">
                                <small class="text-muted d-block">Priority</small>
                                <strong class="text-primary">{{ $notice->priority }}</strong>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block">Status</small>
                                <span class="badge bg-{{ $notice->is_active ? 'success' : 'secondary' }}">
                                    {{ $notice->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block">Author</small>
                                <strong>{{ $notice->author }}</strong>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer text-muted">
                        <small>
                            <i class="fas fa-calendar me-1"></i>
                            Created {{ $notice->created_at->diffForHumans() }}
                            @if($notice->start_date)
                                <br><i class="fas fa-play me-1"></i>
                                Starts {{ $notice->start_date->format('M d, Y') }}
                            @endif
                            @if($notice->end_date)
                                <br><i class="fas fa-stop me-1"></i>
                                Ends {{ $notice->end_date->format('M d, Y') }}
                            @endif
                        </small>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-bullhorn fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No Notices Found</h4>
                    <p class="text-muted">Create your first notice to get started.</p>
                    <a href="{{ route('admin.notices.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create Notice
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($notices->hasPages())
        <div class="d-flex justify-content-center">
            {{ $notices->links() }}
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
function toggleNotice(noticeId) {
    if (confirm('Are you sure you want to change the status of this notice?')) {
        const form = document.getElementById('toggleForm');
        form.action = `/admin/notices/${noticeId}/toggle`;
        form.submit();
    }
}

function deleteNotice(noticeId) {
    if (confirm('Are you sure you want to delete this notice? This action cannot be undone.')) {
        const form = document.getElementById('deleteForm');
        form.action = `/admin/notices/${noticeId}`;
        form.submit();
    }
}
</script>
@endpush