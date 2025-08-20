@extends('layouts.app')

@section('title', 'Houses - Boothcare')
@section('page-title', 'House Management')

@push('styles')
<style>
    /* Modern Houses Index Page */
    :root {
        --primary-gradient: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        --success-gradient: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        --warning-gradient: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
        --danger-gradient: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        --info-gradient: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
        --card-shadow: 0 4px 20px rgba(0,0,0,0.08);
        --card-shadow-hover: 0 8px 30px rgba(0,0,0,0.12);
    }

    body {
        background: linear-gradient(135deg, #f8fafc 0%, #e3f2fd 100%);
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        min-height: 100vh;
    }

    /* Beautiful Header with Animated Stars */
    .houses-header {
        background: var(--primary-gradient);
        color: white;
        padding: 32px 20px;
        margin: -16px -16px 32px -16px;
        border-radius: 0 0 30px 30px;
        box-shadow: 0 8px 30px rgba(23, 162, 184, 0.3);
        position: relative;
        overflow: hidden;
    }

    .houses-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><defs><radialGradient id="star" cx="50%" cy="50%" r="50%"><stop offset="0%" stop-color="white" stop-opacity="0.4"/><stop offset="100%" stop-color="white" stop-opacity="0"/></radialGradient></defs><circle cx="40" cy="30" r="1.5" fill="url(%23star)" opacity="0.8"><animate attributeName="opacity" values="0.3;0.8;0.3" dur="3s" repeatCount="indefinite"/></circle><circle cx="160" cy="50" r="1" fill="url(%23star)" opacity="0.6"><animate attributeName="opacity" values="0.2;0.7;0.2" dur="4s" repeatCount="indefinite"/></circle><circle cx="90" cy="80" r="0.8" fill="url(%23star)" opacity="0.7"><animate attributeName="opacity" values="0.4;0.9;0.4" dur="2.5s" repeatCount="indefinite"/></circle><circle cx="150" cy="120" r="1.2" fill="url(%23star)" opacity="0.5"><animate attributeName="opacity" values="0.3;0.8;0.3" dur="3.5s" repeatCount="indefinite"/></circle></svg>');
        opacity: 0.8;
        animation: twinkle 6s ease-in-out infinite;
    }

    @keyframes twinkle {
        0%, 100% { opacity: 0.8; }
        50% { opacity: 1; }
    }

    .houses-header-content {
        position: relative;
        z-index: 2;
        text-align: center;
    }

    .houses-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin: 0 0 8px 0;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
    }

    .houses-subtitle {
        opacity: 0.9;
        font-size: 1rem;
        margin: 0;
        font-weight: 400;
    }

    /* Modern Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 16px;
        margin-bottom: 32px;
    }

    .stat-card {
        background: white;
        border-radius: 20px;
        padding: 24px;
        text-align: center;
        box-shadow: var(--card-shadow);
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.2);
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
        background: var(--primary-gradient);
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--card-shadow-hover);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        font-size: 1.3rem;
        color: white;
        position: relative;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 6px;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.85rem;
        color: #6b7280;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Modern Search Container */
    .search-container {
        background: white;
        border-radius: 24px;
        padding: 24px;
        margin-bottom: 32px;
        box-shadow: var(--card-shadow);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .search-container:hover {
        box-shadow: var(--card-shadow-hover);
    }

    .search-input {
        border: 2px solid #e5e7eb;
        background: #f8fafc;
        border-radius: 16px;
        padding: 16px 20px;
        font-size: 1rem;
        width: 100%;
        margin-bottom: 16px;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        outline: none;
        background: #fff;
        border-color: #17a2b8;
        box-shadow: 0 0 0 4px rgba(23, 162, 184, 0.1);
        transform: translateY(-1px);
    }

    .search-input::placeholder {
        color: #9ca3af;
        font-weight: 500;
    }

    .filter-row {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .filter-select {
        flex: 1;
        border: 2px solid #e5e7eb;
        background: #f8fafc;
        border-radius: 16px;
        padding: 14px 16px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .filter-select:focus {
        outline: none;
        background: #fff;
        border-color: #17a2b8;
        box-shadow: 0 0 0 4px rgba(23, 162, 184, 0.1);
    }

    .search-btn {
        background: var(--primary-gradient);
        border: none;
        border-radius: 16px;
        padding: 14px 24px;
        color: white;
        font-weight: 600;
        font-size: 0.95rem;
        min-width: 100px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);
    }

    .search-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(23, 162, 184, 0.4);
    }

    /* Modern House Cards */
    .houses-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 24px;
        margin-bottom: 32px;
    }

    .house-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: var(--card-shadow);
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.2);
        cursor: pointer;
    }

    .house-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--card-shadow-hover);
    }

    .house-header {
        padding: 24px;
        display: flex;
        align-items: center;
        gap: 16px;
        background: linear-gradient(135deg, #f8fafc 0%, #e3f2fd 100%);
        border-bottom: 1px solid #f1f3f4;
    }

    .house-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.4rem;
        color: white;
        background: var(--primary-gradient);
        box-shadow: 0 4px 12px rgba(23, 162, 184, 0.3);
        position: relative;
        flex-shrink: 0;
    }

    .house-info {
        flex: 1;
        min-width: 0;
    }

    .house-number {
        font-size: 1.2rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .house-booth {
        font-size: 0.9rem;
        color: #6b7280;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .house-address {
        font-size: 0.85rem;
        color: #9ca3af;
        display: flex;
        align-items: center;
        gap: 6px;
        line-height: 1.4;
    }

    .house-body {
        padding: 24px;
    }

    .house-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 20px;
    }

    .house-badge {
        padding: 6px 12px;
        border-radius: 16px;
        font-size: 0.75rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .house-stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
        margin-bottom: 20px;
    }

    .house-stat {
        text-align: center;
        padding: 12px;
        background: #f8fafc;
        border-radius: 12px;
    }

    .house-stat-number {
        font-size: 1.4rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 4px;
    }

    .house-stat-label {
        font-size: 0.75rem;
        color: #6b7280;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .house-actions {
        display: flex;
        gap: 8px;
    }

    .house-action-btn {
        flex: 1;
        border: none;
        border-radius: 12px;
        padding: 10px 16px;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        cursor: pointer;
    }

    .house-action-btn.primary {
        background: #e3f2fd;
        color: #1976d2;
    }

    .house-action-btn.warning {
        background: #fff3e0;
        color: #f57c00;
    }

    .house-action-btn.danger {
        background: #ffebee;
        color: #d32f2f;
    }

    .house-action-btn:hover {
        transform: translateY(-1px);
        color: inherit;
    }

    /* Status Indicators */
    .status-indicator {
        position: absolute;
        top: -3px;
        right: -3px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        color: #fff;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        border: 2px solid white;
    }

    .status-active {
        background: var(--success-gradient);
    }

    .status-inactive {
        background: #9e9e9e;
    }

    /* Floating Add Button */
    .fab {
        position: fixed;
        bottom: 24px;
        right: 24px;
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: var(--primary-gradient);
        border: none;
        color: white;
        font-size: 1.6rem;
        box-shadow: 0 6px 20px rgba(23, 162, 184, 0.4);
        z-index: 1000;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .fab:hover {
        transform: scale(1.1);
        box-shadow: 0 8px 25px rgba(23, 162, 184, 0.5);
        color: white;
    }

    .fab:active {
        transform: scale(0.95);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: 24px;
        box-shadow: var(--card-shadow);
        margin: 40px 0;
    }

    .empty-icon {
        font-size: 5rem;
        color: #d1d5db;
        margin-bottom: 24px;
    }

    .empty-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #6b7280;
        margin-bottom: 12px;
    }

    .empty-subtitle {
        color: #9ca3af;
        margin-bottom: 32px;
        line-height: 1.6;
        font-size: 1.1rem;
    }

    .empty-action {
        background: var(--primary-gradient);
        color: white;
        padding: 16px 32px;
        border-radius: 24px;
        text-decoration: none;
        font-weight: 600;
        font-size: 1.1rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);
    }

    .empty-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(23, 162, 184, 0.4);
        color: white;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 16px;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
        
        .houses-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }
        
        .filter-row {
            flex-direction: column;
            gap: 12px;
        }
        
        .search-btn {
            width: 100%;
        }
        
        .house-stats {
            grid-template-columns: 1fr;
        }
        
        .house-actions {
            flex-direction: column;
        }
    }

    @media (min-width: 1200px) {
        .houses-grid {
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Beautiful Header -->
    <div class="houses-header">
        <div class="houses-header-content">
            <h1 class="houses-title">
                <i class="fas fa-home"></i>
                Houses Management
            </h1>
            <div class="houses-subtitle">{{ $houses->total() }} houses across all booths</div>
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--primary-gradient);">
                <i class="fas fa-home"></i>
            </div>
            <div class="stat-number">{{ $houses->total() }}</div>
            <div class="stat-label">Total Houses</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--success-gradient);">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-number">{{ $houses->sum('members_count') }}</div>
            <div class="stat-label">Total Members</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--info-gradient);">
                <i class="fas fa-building"></i>
            </div>
            <div class="stat-number">{{ $houses->unique('booth_id')->count() }}</div>
            <div class="stat-label">Booths Covered</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--warning-gradient);">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-number">{{ $houses->where('is_active', true)->count() }}</div>
            <div class="stat-label">Active Houses</div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="search-container">
        <form method="GET" action="{{ route('houses.index') }}">
            <input type="text" class="search-input" name="search" 
                   value="{{ request('search') }}" 
                   placeholder="🔍 Search houses by number, address, or area...">
            <div class="filter-row">
                <select class="filter-select" name="booth_filter">
                    <option value="">All Booths</option>
                    @foreach($houses->unique('booth_id') as $house)
                        <option value="{{ $house->booth_id }}" {{ request('booth_filter') == $house->booth_id ? 'selected' : '' }}>
                            Booth {{ $house->booth->booth_number }} - {{ $house->booth->booth_name ?? 'N/A' }}
                        </option>
                    @endforeach
                </select>
                <select class="filter-select" name="status_filter">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status_filter') == 'active' ? 'selected' : '' }}>Active Only</option>
                    <option value="inactive" {{ request('status_filter') == 'inactive' ? 'selected' : '' }}>Inactive Only</option>
                </select>
                <button type="submit" class="search-btn">
                    <i class="fas fa-search"></i> Search
                </button>
                @if(request('search') || request('booth_filter') || request('status_filter'))
                    <a href="{{ route('houses.index') }}" class="search-btn" style="background: #6c757d; text-decoration: none;">
                        <i class="fas fa-times"></i> Clear
                    </a>
                @endif
            </div>
        </form>
    </div>


    <!-- Houses Grid -->
    @if($houses->count() > 0)
        <div class="houses-grid">
            @foreach($houses as $house)
            <div class="house-card" onclick="window.location.href='{{ route('houses.show', $house) }}'">
                <div class="house-header">
                    <div class="house-icon">
                        <i class="fas fa-home"></i>
                        <div class="status-indicator {{ $house->is_active ? 'status-active' : 'status-inactive' }}">
                            <i class="fas fa-{{ $house->is_active ? 'check' : 'times' }}"></i>
                        </div>
                    </div>
                    
                    <div class="house-info">
                        <div class="house-number">
                            House {{ $house->house_number }}
                            @if($house->area)
                                <span class="house-badge" style="background: #f3f4f6; color: #374151;">
                                    {{ Str::limit($house->area, 12) }}
                                </span>
                            @endif
                        </div>
                        <div class="house-booth">
                            <i class="fas fa-building" style="color: #17a2b8;"></i>
                            Booth {{ $house->booth->booth_number }} - {{ Str::limit($house->booth->booth_name ?? 'N/A', 20) }}
                        </div>
                        <div class="house-address">
                            <i class="fas fa-map-marker-alt" style="color: #dc3545;"></i>
                            {{ Str::limit($house->address, 45) }}
                            @if($house->pincode)
                                - {{ $house->pincode }}
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="house-body">
                    <div class="house-badges">
                        <span class="house-badge" style="background: {{ $house->is_active ? 'var(--success-gradient)' : '#6b7280' }}; color: white;">
                            <i class="fas fa-{{ $house->is_active ? 'check-circle' : 'times-circle' }}"></i>
                            {{ $house->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        @if($house->members_count > 0)
                            <span class="house-badge" style="background: var(--warning-gradient); color: white;">
                                <i class="fas fa-users"></i>
                                {{ $house->members_count }} Members
                            </span>
                        @endif
                        @if($house->latitude && $house->longitude)
                            <span class="house-badge" style="background: var(--info-gradient); color: white;">
                                <i class="fas fa-map-pin"></i>
                                GPS Available
                            </span>
                        @endif
                    </div>
                    
                    <div class="house-stats">
                        <div class="house-stat">
                            <div class="house-stat-number">{{ $house->members_count }}</div>
                            <div class="house-stat-label">Members</div>
                        </div>
                        <div class="house-stat">
                            <div class="house-stat-number">{{ $house->created_at->diffForHumans(null, true) }}</div>
                            <div class="house-stat-label">Added</div>
                        </div>
                    </div>
                    
                    <div class="house-actions">
                        <a href="{{ route('houses.show', $house) }}" class="house-action-btn primary" onclick="event.stopPropagation();">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ route('houses.edit', $house) }}" class="house-action-btn warning" onclick="event.stopPropagation();">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        @if(auth()->user()->isAdmin())
                            <form action="{{ route('houses.destroy', $house) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure? This will also delete all members in this house.')" onclick="event.stopPropagation();" style="flex: 1;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="house-action-btn danger" style="width: 100%;">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div style="display: flex; justify-content: center; margin-top: 32px;">
            {{ $houses->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-home"></i>
            </div>
            <h2 class="empty-title">No houses found</h2>
            <p class="empty-subtitle">
                @if(request('search') || request('booth_filter'))
                    No houses match your search criteria. Try adjusting your filters or search terms.
                @else
                    Start by creating your first house in the system. Houses are organized under booths for better management.
                @endif
            </p>
            @if(!request('search') && !request('booth_filter'))
                <a href="{{ route('houses.create') }}" class="empty-action">
                    <i class="fas fa-plus"></i> Add First House
                </a>
            @else
                <a href="{{ route('houses.index') }}" class="empty-action">
                    <i class="fas fa-list"></i> View All Houses
                </a>
            @endif
        </div>
    @endif

    <!-- Floating Action Button -->
    <a href="{{ route('houses.create') }}" class="fab">
        <i class="fas fa-plus"></i>
    </a>
</div>

@push('scripts')
<script>
// Enhanced interactions and animations
document.addEventListener('DOMContentLoaded', function() {
    // Add haptic feedback for mobile interactions
    document.querySelectorAll('.house-card, .house-action-btn, .stat-card, .fab').forEach(element => {
        element.addEventListener('click', function() {
            if (navigator.vibrate) {
                navigator.vibrate(10);
            }
        });
    });

    // Smooth scroll to top when clicking stats
    document.querySelectorAll('.stat-card').forEach(card => {
        card.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    });

    // Auto-submit search form on filter change
    const filterSelect = document.querySelector('.filter-select');
    if (filterSelect) {
        filterSelect.addEventListener('change', function() {
            this.closest('form').submit();
        });
    }

    // Add loading state to search button
    const searchForm = document.querySelector('.search-container form');
    const searchBtn = document.querySelector('.search-btn');
    if (searchForm && searchBtn) {
        searchForm.addEventListener('submit', function() {
            searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Searching...';
            searchBtn.disabled = true;
        });
    }

    // Keyboard navigation for house cards
    document.querySelectorAll('.house-card').forEach((card, index) => {
        card.setAttribute('tabindex', '0');
        card.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                card.click();
            }
        });
    });
});
</script>
@endpush

@endsection