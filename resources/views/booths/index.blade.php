@extends('layouts.app')

@section('title', 'Booths - Boothcare')
@section('page-title', 'Booths Management')

@push('styles')
<style>
    /* Mobile App-like Design for Booths */
    body {
        background: #f8fafc;
    }

    /* Mobile App Header */
    .mobile-app-header {
        background: linear-gradient(135deg, #6f42c1, #5a32a3);
        color: white;
        padding: 20px 16px;
        margin: -16px -16px 20px -16px;
        border-radius: 0 0 25px 25px;
        box-shadow: 0 4px 20px rgba(111, 66, 193, 0.3);
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

    /* Mobile Search Container */
    .mobile-search-container {
        background: white;
        border-radius: 20px;
        padding: 16px;
        margin-bottom: 20px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        border: 1px solid #f1f3f4;
    }

    .mobile-search-input {
        border: none;
        background: #f8fafc;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 0.95rem;
        width: 100%;
        margin-bottom: 12px;
    }

    .mobile-search-input:focus {
        outline: none;
        background: #fff;
        box-shadow: 0 0 0 2px rgba(111, 66, 193, 0.2);
    }

    .mobile-filter-row {
        display: flex;
        gap: 8px;
    }

    .mobile-filter-select {
        flex: 1;
        border: none;
        background: #f8fafc;
        border-radius: 12px;
        padding: 10px 12px;
        font-size: 0.9rem;
    }

    .mobile-search-btn {
        background: linear-gradient(135deg, #6f42c1, #5a32a3);
        border: none;
        border-radius: 12px;
        padding: 10px 16px;
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
        min-width: 80px;
    }

    /* Mobile Booth Cards */
    .mobile-booth-card {
        background: white;
        border-radius: 16px;
        margin-bottom: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        border: 1px solid #f1f3f4;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .mobile-booth-card:active {
        transform: scale(0.98);
        box-shadow: 0 1px 6px rgba(0,0,0,0.12);
    }

    .mobile-booth-header {
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .mobile-booth-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
        color: white;
        background: linear-gradient(135deg, #6f42c1, #5a32a3);
        box-shadow: 0 2px 8px rgba(111, 66, 193, 0.3);
        position: relative;
        flex-shrink: 0;
    }

    .mobile-booth-info {
        flex: 1;
        min-width: 0;
    }

    .mobile-booth-number {
        font-size: 1rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 2px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .mobile-booth-name {
        font-size: 0.85rem;
        color: #666;
        margin-bottom: 4px;
        font-weight: 600;
    }

    .mobile-booth-location {
        font-size: 0.75rem;
        color: #888;
        display: flex;
        align-items: center;
        gap: 4px;
        margin-bottom: 4px;
    }

    .mobile-booth-constituency {
        font-size: 0.75rem;
        color: #888;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .mobile-booth-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        margin-top: 8px;
    }

    .mobile-badge {
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .mobile-booth-footer {
        padding: 0 16px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .mobile-booth-stats {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .mobile-stat-item {
        font-size: 0.75rem;
        color: #666;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .mobile-action-buttons {
        display: flex;
        gap: 8px;
    }

    .mobile-action-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .mobile-action-btn.primary {
        background: #e3f2fd;
        color: #1976d2;
    }

    .mobile-action-btn.success {
        background: #e8f5e8;
        color: #2e7d32;
    }

    .mobile-action-btn.warning {
        background: #fff3e0;
        color: #f57c00;
    }

    .mobile-action-btn.danger {
        background: #ffebee;
        color: #d32f2f;
    }

    .mobile-action-btn:active {
        transform: scale(0.9);
    }

    /* Status Indicators */
    .status-active {
        position: absolute;
        top: -2px;
        right: -2px;
        width: 18px;
        height: 18px;
        background: #4caf50;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.6rem;
        color: #fff;
        box-shadow: 0 1px 4px rgba(0,0,0,0.2);
    }

    .status-inactive {
        position: absolute;
        top: -2px;
        right: -2px;
        width: 18px;
        height: 18px;
        background: #9e9e9e;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.6rem;
        color: #fff;
        box-shadow: 0 1px 4px rgba(0,0,0,0.2);
    }

    /* Floating Add Button */
    .mobile-fab {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6f42c1, #5a32a3);
        border: none;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 4px 16px rgba(111, 66, 193, 0.4);
        z-index: 1000;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .mobile-fab:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 20px rgba(111, 66, 193, 0.5);
        color: white;
    }

    .mobile-fab:active {
        transform: scale(0.95);
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
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Mobile App View -->
    <div class="mobile-view">
        <!-- Mobile App Header -->
        <div class="mobile-app-header">
            <h1><i class="fas fa-building me-2"></i>Voting Booths</h1>
            <div class="header-subtitle">{{ $booths->total() }} booths in the system</div>
        </div>

        <!-- Mobile Stats Grid -->
        <div class="mobile-stats-grid">
            <div class="mobile-stat-card">
                <div class="mobile-stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="fas fa-building"></i>
                </div>
                <div class="mobile-stat-number">{{ $booths->total() }}</div>
                <div class="mobile-stat-label">Total Booths</div>
            </div>
            <div class="mobile-stat-card">
                <div class="mobile-stat-icon bg-info bg-opacity-10 text-info">
                    <i class="fas fa-home"></i>
                </div>
                <div class="mobile-stat-number">{{ $booths->sum('houses_count') }}</div>
                <div class="mobile-stat-label">Total Houses</div>
            </div>
            <div class="mobile-stat-card">
                <div class="mobile-stat-icon bg-success bg-opacity-10 text-success">
                    <i class="fas fa-users"></i>
                </div>
                <div class="mobile-stat-number">{{ $booths->sum('total_members') }}</div>
                <div class="mobile-stat-label">Total Members</div>
            </div>
            <div class="mobile-stat-card">
                <div class="mobile-stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="mobile-stat-number">{{ $booths->where('is_active', true)->count() }}</div>
                <div class="mobile-stat-label">Active Booths</div>
            </div>
        </div>

        <!-- Mobile Search Container -->
        <div class="mobile-search-container">
            <form method="GET" action="{{ route('booths.index') }}">
                <input type="text" class="mobile-search-input" name="search" 
                       value="{{ request('search') }}" 
                       placeholder="🔍 Search booths by number, name, location...">
                <div class="mobile-filter-row">
                    <select class="mobile-filter-select" name="status_filter">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status_filter') == 'active' ? 'selected' : '' }}>Active Only</option>
                        <option value="inactive" {{ request('status_filter') == 'inactive' ? 'selected' : '' }}>Inactive Only</option>
                    </select>
                    <button type="submit" class="mobile-search-btn">Search</button>
                    @if(request('search') || request('status_filter'))
                        <a href="{{ route('booths.index') }}" class="mobile-search-btn" style="background: #6c757d; text-decoration: none;">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Desktop Header -->
    <div class="desktop-view">
        <div class="row align-items-center mb-4">
            <div class="col-md-8">
                <h4 class="mb-0">
                    <i class="fas fa-building me-2 text-primary"></i>
                    Booths Management ({{ $booths->total() }})
                </h4>
                <p class="text-muted mb-0">Manage all voting booths in the system</p>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('booths.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Add New Booth
                </a>
            </div>
        </div>
    </div>


    <!-- Booths List -->
    @if($booths->count() > 0)
        <!-- Mobile App-like View -->
        <div class="mobile-view">
            @foreach($booths as $booth)
            <div class="mobile-booth-card" onclick="window.location.href='{{ route('booths.show', $booth) }}'">
                <div class="mobile-booth-header">
                    <div class="mobile-booth-icon">
                        <i class="fas fa-building"></i>
                        
                        @if($booth->is_active)
                            <div class="status-active">
                                <i class="fas fa-check"></i>
                            </div>
                        @else
                            <div class="status-inactive">
                                <i class="fas fa-times"></i>
                            </div>
                        @endif
                    </div>
                    
                    <div class="mobile-booth-info">
                        <div class="mobile-booth-number">
                            Booth {{ $booth->booth_number }}
                            @if($booth->houses_count > 0)
                                <span class="mobile-badge bg-info text-white">{{ $booth->houses_count }} Houses</span>
                            @endif
                        </div>
                        <div class="mobile-booth-name">
                            {{ Str::limit($booth->booth_name, 25) }}
                        </div>
                        <div class="mobile-booth-location">
                            <i class="fas fa-map-marker-alt text-danger"></i>
                            {{ Str::limit($booth->location, 30) }}
                        </div>
                        <div class="mobile-booth-constituency">
                            <i class="fas fa-landmark text-primary"></i>
                            {{ Str::limit($booth->constituency, 25) }}
                        </div>
                        
                        <div class="mobile-booth-badges">
                            <span class="mobile-badge bg-{{ $booth->is_active ? 'success' : 'secondary' }} text-white">
                                {{ $booth->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            @if($booth->total_members > 0)
                                <span class="mobile-badge bg-warning text-white">{{ $booth->total_members }} Members</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="mobile-booth-footer">
                    <div class="mobile-booth-stats">
                        <div class="mobile-stat-item">
                            <i class="fas fa-home text-info"></i>
                            {{ $booth->houses_count }} houses registered
                        </div>
                        <div class="mobile-stat-item">
                            <i class="fas fa-users text-success"></i>
                            {{ $booth->total_members }} total members
                        </div>
                        <div class="mobile-stat-item">
                            <i class="fas fa-calendar text-muted"></i>
                            Added {{ $booth->created_at->diffForHumans() }}
                        </div>
                    </div>
                    
                    <div class="mobile-action-buttons">
                        <a href="{{ route('booths.show', $booth) }}" class="mobile-action-btn primary" onclick="event.stopPropagation();">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('booths.edit', $booth) }}" class="mobile-action-btn warning" onclick="event.stopPropagation();">
                            <i class="fas fa-edit"></i>
                        </a>
                        @if(auth()->user()->isAdmin())
                            <form action="{{ route('booths.destroy', $booth) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure? This will also delete all houses and members in this booth.')" onclick="event.stopPropagation();">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="mobile-action-btn danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Desktop View -->
        <div class="desktop-view">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 ps-4">Booth Details</th>
                                    <th class="border-0">Location</th>
                                    <th class="border-0">Constituency</th>
                                    <th class="border-0">Houses</th>
                                    <th class="border-0">Members</th>
                                    <th class="border-0">Status</th>
                                    <th class="border-0 pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($booths as $booth)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: 600;">
                                                <i class="fas fa-building"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">
                                                    <a href="{{ route('booths.show', $booth) }}" class="text-decoration-none text-dark">
                                                        Booth {{ $booth->booth_number }}
                                                    </a>
                                                </h6>
                                                <small class="text-muted">{{ $booth->booth_name }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <div>{{ Str::limit($booth->location, 30) }}</div>
                                    </td>
                                    <td class="py-3">
                                        <div class="fw-semibold">{{ $booth->constituency }}</div>
                                    </td>
                                    <td class="py-3 text-center">
                                        <span class="badge bg-info">{{ $booth->houses_count }}</span>
                                        @if($booth->houses_count > 0)
                                            <br><small class="text-muted">houses</small>
                                        @endif
                                    </td>
                                    <td class="py-3 text-center">
                                        <span class="badge bg-success">{{ $booth->total_members }}</span>
                                        @if($booth->total_members > 0)
                                            <br><small class="text-muted">members</small>
                                        @endif
                                    </td>
                                    <td class="py-3">
                                        <span class="badge bg-{{ $booth->is_active ? 'success' : 'secondary' }}">
                                            {{ $booth->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="py-3 pe-4">
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('booths.show', $booth) }}" class="btn btn-sm btn-outline-primary" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('booths.edit', $booth) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if(auth()->user()->isAdmin())
                                                <form action="{{ route('booths.destroy', $booth) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure? This will also delete all houses and members in this booth.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $booths->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="mobile-view">
            <div class="text-center py-5" style="background: white; border-radius: 16px; margin: 20px 0; box-shadow: 0 2px 12px rgba(0,0,0,0.08);">
                <div style="font-size: 4rem; color: #ddd; margin-bottom: 16px;">
                    <i class="fas fa-building"></i>
                </div>
                <h5 style="color: #666; margin-bottom: 8px;">No booths found</h5>
                <p style="color: #888; font-size: 0.9rem; margin-bottom: 20px;">Start by adding your first booth</p>
                <a href="{{ route('booths.create') }}" style="background: linear-gradient(135deg, #6f42c1, #5a32a3); color: white; padding: 12px 24px; border-radius: 25px; text-decoration: none; font-weight: 600;">
                    <i class="fas fa-plus me-2"></i>Add First Booth
                </a>
            </div>
        </div>
        
        <div class="desktop-view">
            <div class="text-center py-5">
                <i class="fas fa-building fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">No booths found</h5>
                <p class="text-muted">Start by adding your first booth to the system.</p>
                <a href="{{ route('booths.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Add First Booth
                </a>
            </div>
        </div>
    @endif

    <!-- Mobile Floating Action Button -->
    <a href="{{ route('booths.create') }}" class="mobile-fab">
        <i class="fas fa-plus"></i>
    </a>
</div>

<script>
// Add haptic feedback for mobile interactions
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.mobile-booth-card, .mobile-action-btn, .mobile-stat-card').forEach(element => {
        element.addEventListener('click', function() {
            if (navigator.vibrate) {
                navigator.vibrate(10);
            }
        });
    });
});
</script>

@endsection