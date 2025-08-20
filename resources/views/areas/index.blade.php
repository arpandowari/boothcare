@extends('layouts.app')

@section('title', 'Areas Management - Boothcare')
@section('page-title', 'Areas Management')

@push('styles')
<style>
    /* Areas Management Styles */
    .areas-header {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 30px 20px;
        margin: -16px -16px 24px -16px;
        border-radius: 0 0 25px 25px;
        box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
    }

    .areas-header h1 {
        margin: 0 0 8px 0;
        font-size: 2rem;
        font-weight: 700;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .areas-header .subtitle {
        opacity: 0.9;
        font-size: 1.1rem;
        margin: 0;
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
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
        background: linear-gradient(90deg, #667eea, #764ba2);
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .stat-content {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        background: linear-gradient(135deg, #667eea, #764ba2);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }

    .stat-info h3 {
        font-size: 2rem;
        font-weight: 800;
        margin: 0;
        color: #1a1a1a;
        line-height: 1;
    }

    .stat-info p {
        margin: 4px 0 0 0;
        color: #6c757d;
        font-weight: 500;
        font-size: 0.9rem;
    }

    /* Areas Grid */
    .areas-grid {
        display: grid;
        gap: 20px;
    }

    .area-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid #f1f3f4;
    }

    .area-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        border-color: #667eea;
    }

    .area-card-header {
        padding: 24px;
        border-bottom: 1px solid #f8f9fa;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .area-info h3 {
        margin: 0 0 8px 0;
        font-size: 1.3rem;
        font-weight: 700;
        color: #1a1a1a;
    }

    .area-location {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .area-description {
        color: #495057;
        font-size: 0.9rem;
        line-height: 1.5;
        margin: 0;
    }

    .area-status {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 8px;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-badge.active {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .status-badge.inactive {
        background: #6c757d;
        color: white;
    }

    .area-stats {
        display: flex;
        gap: 16px;
        font-size: 0.85rem;
        color: #6c757d;
    }

    .area-stat {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .area-card-body {
        padding: 0 24px 24px;
    }

    .area-metrics {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 20px;
    }

    .metric-item {
        text-align: center;
        padding: 16px;
        background: #f8f9fa;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .metric-item:hover {
        background: #e9ecef;
        transform: translateY(-2px);
    }

    .metric-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: #667eea;
        margin: 0;
    }

    .metric-label {
        font-size: 0.8rem;
        color: #6c757d;
        margin: 4px 0 0 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .area-actions {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
    }

    .action-btn {
        padding: 8px 16px;
        border: none;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .action-btn.primary {
        background: #667eea;
        color: white;
    }

    .action-btn.primary:hover {
        background: #5a67d8;
        color: white;
        transform: translateY(-1px);
    }

    .action-btn.secondary {
        background: #f8f9fa;
        color: #6c757d;
        border: 1px solid #e9ecef;
    }

    .action-btn.secondary:hover {
        background: #e9ecef;
        color: #495057;
        transform: translateY(-1px);
    }

    .action-btn.danger {
        background: #dc3545;
        color: white;
    }

    .action-btn.danger:hover {
        background: #c82333;
        color: white;
        transform: translateY(-1px);
    }

    /* Add Button */
    .add-area-btn {
        position: fixed;
        bottom: 24px;
        right: 24px;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border: none;
        font-size: 1.5rem;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        transition: all 0.3s ease;
        z-index: 1000;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .add-area-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 12px 35px rgba(102, 126, 234, 0.5);
        color: white;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .empty-state i {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 20px;
    }

    .empty-state h3 {
        font-size: 1.5rem;
        color: #6c757d;
        margin-bottom: 12px;
        font-weight: 600;
    }

    .empty-state p {
        color: #6c757d;
        margin-bottom: 24px;
        line-height: 1.6;
    }

    /* Search and Filter */
    .controls-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .search-box {
        flex: 1;
        max-width: 400px;
        position: relative;
    }

    .search-input {
        width: 100%;
        padding: 12px 16px 12px 44px;
        border: 2px solid #e9ecef;
        border-radius: 25px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        background: white;
    }

    .search-input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
    }

    .search-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }

    .filter-controls {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .filter-select {
        padding: 10px 16px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 0.9rem;
        background: white;
        transition: all 0.3s ease;
    }

    .filter-select:focus {
        border-color: #667eea;
        outline: none;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .areas-header {
            padding: 24px 16px;
            margin: -16px -16px 20px -16px;
        }

        .areas-header h1 {
            font-size: 1.5rem;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .stat-card {
            padding: 20px;
        }

        .stat-content {
            gap: 12px;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            font-size: 1.2rem;
        }

        .stat-info h3 {
            font-size: 1.5rem;
        }

        .area-card-header {
            padding: 20px;
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .area-status {
            align-items: flex-start;
            flex-direction: row;
        }

        .area-metrics {
            grid-template-columns: repeat(2, 1fr);
        }

        .controls-bar {
            flex-direction: column;
            align-items: stretch;
        }

        .search-box {
            max-width: none;
        }

        .filter-controls {
            justify-content: space-between;
        }

        .add-area-btn {
            bottom: 20px;
            right: 20px;
            width: 56px;
            height: 56px;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Areas Header -->
    <div class="areas-header">
        <h1><i class="fas fa-map-marked-alt me-2"></i>Areas Management</h1>
        <p class="subtitle">Manage geographical areas and their administrative divisions</p>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-icon">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $areas->total() }}</h3>
                    <p>Total Areas</p>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $areas->where('is_active', true)->count() }}</h3>
                    <p>Active Areas</p>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $areas->sum('booths_count') }}</h3>
                    <p>Total Booths</p>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ number_format($areas->sum('booths_count') / max($areas->count(), 1), 1) }}</h3>
                    <p>Avg Booths/Area</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Controls Bar -->
    <div class="controls-bar">
        <div class="search-box">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="search-input" placeholder="Search areas..." id="searchInput">
        </div>
        <div class="filter-controls">
            <select class="filter-select" id="statusFilter">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            <select class="filter-select" id="districtFilter">
                <option value="">All Districts</option>
                @foreach($areas->pluck('district')->unique() as $district)
                    <option value="{{ $district }}">{{ $district }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Areas Grid -->
    @if($areas->count() > 0)
        <div class="areas-grid" id="areasGrid">
            @foreach($areas as $area)
            <div class="area-card" data-area-name="{{ strtolower($area->area_name) }}" data-district="{{ strtolower($area->district) }}" data-status="{{ $area->is_active ? 'active' : 'inactive' }}">
                <div class="area-card-header">
                    <div class="area-info">
                        <h3>{{ $area->area_name }}</h3>
                        <div class="area-location">
                            <i class="fas fa-map-marker-alt"></i>
                            {{ $area->district }}, {{ $area->division }}
                        </div>
                        @if($area->description)
                            <p class="area-description">{{ Str::limit($area->description, 100) }}</p>
                        @endif
                    </div>
                    <div class="area-status">
                        <span class="status-badge {{ $area->is_active ? 'active' : 'inactive' }}">
                            {{ $area->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        <div class="area-stats">
                            <div class="area-stat">
                                <i class="fas fa-building"></i>
                                {{ $area->booths_count }} Booths
                            </div>
                        </div>
                    </div>
                </div>
                <div class="area-card-body">
                    <div class="area-metrics">
                        <div class="metric-item">
                            <div class="metric-number">{{ $area->booths_count }}</div>
                            <div class="metric-label">Booths</div>
                        </div>
                        <div class="metric-item">
                            <div class="metric-number">{{ $area->booths->sum('houses_count') ?? 0 }}</div>
                            <div class="metric-label">Houses</div>
                        </div>
                        <div class="metric-item">
                            <div class="metric-number">{{ $area->booths->sum('members_count') ?? 0 }}</div>
                            <div class="metric-label">Members</div>
                        </div>
                    </div>
                    <div class="area-actions">
                        <a href="{{ route('areas.show', $area) }}" class="action-btn primary">
                            <i class="fas fa-eye"></i>
                            View
                        </a>
                        @if(auth()->user()->isAdmin() || auth()->user()->hasPermission('areas.edit'))
                            <a href="{{ route('areas.edit', $area) }}" class="action-btn secondary">
                                <i class="fas fa-edit"></i>
                                Edit
                            </a>
                        @endif
                        @if(auth()->user()->isAdmin() || auth()->user()->hasPermission('areas.delete'))
                            <button class="action-btn danger" onclick="confirmDelete({{ $area->id }}, '{{ $area->area_name }}')">
                                <i class="fas fa-trash"></i>
                                Delete
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $areas->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="empty-state">
            <i class="fas fa-map-marked-alt"></i>
            <h3>No Areas Found</h3>
            <p>Start by creating your first geographical area to organize booths and manage constituencies effectively.</p>
            @if(auth()->user()->isAdmin() || auth()->user()->hasPermission('areas.create'))
                <a href="{{ route('areas.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create First Area
                </a>
            @endif
        </div>
    @endif

    <!-- Add Area Button -->
    @if(auth()->user()->isAdmin() || auth()->user()->hasPermission('areas.create'))
        <a href="{{ route('areas.create') }}" class="add-area-btn" title="Add New Area">
            <i class="fas fa-plus"></i>
        </a>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the area <strong id="deleteAreaName"></strong>?</p>
                <p class="text-muted">This action cannot be undone and will also delete all associated booths and houses.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Area</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    filterAreas();
});

// Filter functionality
document.getElementById('statusFilter').addEventListener('change', filterAreas);
document.getElementById('districtFilter').addEventListener('change', filterAreas);

function filterAreas() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const districtFilter = document.getElementById('districtFilter').value.toLowerCase();
    
    const areaCards = document.querySelectorAll('.area-card');
    
    areaCards.forEach(card => {
        const areaName = card.dataset.areaName;
        const district = card.dataset.district;
        const status = card.dataset.status;
        
        const matchesSearch = areaName.includes(searchTerm) || district.includes(searchTerm);
        const matchesStatus = !statusFilter || status === statusFilter;
        const matchesDistrict = !districtFilter || district.includes(districtFilter);
        
        if (matchesSearch && matchesStatus && matchesDistrict) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

// Delete confirmation
function confirmDelete(areaId, areaName) {
    document.getElementById('deleteAreaName').textContent = areaName;
    document.getElementById('deleteForm').action = `/areas/${areaId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Auto-hide alerts
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});
</script>
@endsection