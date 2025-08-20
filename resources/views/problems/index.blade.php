@extends('layouts.app')

@section('title', 'Problem Management - Boothcare')
@section('page-title', 'Problem Management')

@push('styles')
<style>
    /* Clean Professional Design */
    .page-header {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white;
        padding: 2rem;
        border-radius: 16px;
        margin-bottom: 2rem;
        box-shadow: 0 10px 25px rgba(79, 70, 229, 0.2);
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .header-info h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .header-info p {
        opacity: 0.9;
        margin: 0;
        font-size: 1rem;
    }

    .btn-new-problem {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-new-problem:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
        transform: translateY(-2px);
    }

    /* Stats Cards */
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
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        cursor: pointer;
        border-left: 4px solid;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }

    .stat-card.total { border-left-color: #4f46e5; }
    .stat-card.reported { border-left-color: #f59e0b; }
    .stat-card.in-progress { border-left-color: #3b82f6; }
    .stat-card.resolved { border-left-color: #10b981; }
    .stat-card.urgent { border-left-color: #ef4444; }

    .stat-content {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: white;
    }

    .stat-icon.total { background: #4f46e5; }
    .stat-icon.reported { background: #f59e0b; }
    .stat-icon.in-progress { background: #3b82f6; }
    .stat-icon.resolved { background: #10b981; }
    .stat-icon.urgent { background: #ef4444; }

    .stat-info h3 {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }

    .stat-info p {
        color: #6b7280;
        margin: 0;
        font-weight: 500;
    }

    /* Filter Section */
    .filter-section {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .filter-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .filter-tab {
        padding: 0.6rem 1.2rem;
        border: 2px solid #e5e7eb;
        border-radius: 25px;
        background: white;
        color: #6b7280;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
    }

    .filter-tab:hover {
        border-color: #4f46e5;
        color: #4f46e5;
    }

    .filter-tab.active {
        background: #4f46e5;
        border-color: #4f46e5;
        color: white;
    }

    .search-controls {
        display: flex;
        gap: 1rem;
        align-items: center;
        flex-wrap: wrap;
    }

    .search-input {
        flex: 1;
        min-width: 250px;
        padding: 0.75rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .btn-search, .btn-refresh {
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-search {
        background: #4f46e5;
        color: white;
    }

    .btn-search:hover {
        background: #4338ca;
        transform: translateY(-2px);
    }

    .btn-refresh {
        background: #f3f4f6;
        color: #6b7280;
        border: 1px solid #e5e7eb;
    }

    .btn-refresh:hover {
        background: #e5e7eb;
        color: #374151;
    }

    /* Problem Cards */
    .problems-container {
        display: grid;
        gap: 1.5rem;
    }

    .problem-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        overflow: hidden;
        border: 1px solid #f3f4f6;
    }

    .problem-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }

    .problem-header {
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
    }

    .problem-info {
        flex: 1;
    }

    .problem-id {
        font-size: 0.85rem;
        color: #6b7280;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .problem-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.75rem;
        line-height: 1.3;
    }

    .problem-description {
        color: #6b7280;
        margin-bottom: 1rem;
        line-height: 1.5;
    }

    .problem-badges {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .badge {
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .badge-status-reported {
        background: #fef3c7;
        color: #d97706;
    }

    .badge-status-in_progress {
        background: #dbeafe;
        color: #2563eb;
    }

    .badge-status-resolved {
        background: #d1fae5;
        color: #059669;
    }

    .badge-category {
        background: #f3f4f6;
        color: #6b7280;
    }

    .badge-priority-urgent {
        background: #fee2e2;
        color: #dc2626;
    }

    .badge-priority-high {
        background: #fef3c7;
        color: #d97706;
    }

    .badge-priority-medium {
        background: #dbeafe;
        color: #2563eb;
    }

    .badge-priority-low {
        background: #d1fae5;
        color: #059669;
    }

    .problem-meta {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 0.5rem;
    }

    .problem-date {
        font-size: 0.85rem;
        color: #6b7280;
    }

    .category-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }

    .category-icon.water { background: #0ea5e9; }
    .category-icon.electricity { background: #f59e0b; }
    .category-icon.road { background: #6366f1; }
    .category-icon.sanitation { background: #10b981; }
    .category-icon.other { background: #ef4444; }

    .problem-details {
        padding: 1rem 1.5rem;
        background: #f9fafb;
        border-top: 1px solid #f3f4f6;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: #6b7280;
    }

    .problem-actions {
        padding: 1rem 1.5rem;
        background: #f9fafb;
        border-top: 1px solid #f3f4f6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        font-size: 0.9rem;
    }

    .action-btn.view {
        background: #dbeafe;
        color: #2563eb;
    }

    .action-btn.edit {
        background: #fef3c7;
        color: #d97706;
    }

    .action-btn.status {
        background: #e0e7ff;
        color: #4f46e5;
    }

    .action-btn.delete {
        background: #fee2e2;
        color: #dc2626;
    }

    .action-btn:hover {
        transform: scale(1.1);
    }

    .cost-info {
        font-size: 0.9rem;
        color: #059669;
        font-weight: 600;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .empty-icon {
        width: 100px;
        height: 100px;
        margin: 0 auto 2rem;
        background: #f3f4f6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: #9ca3af;
    }

    .empty-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 1rem;
    }

    .empty-description {
        color: #6b7280;
        margin-bottom: 2rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            align-items: stretch;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .search-controls {
            flex-direction: column;
            align-items: stretch;
        }

        .search-input {
            min-width: auto;
        }

        .problem-header {
            flex-direction: column;
            gap: 1rem;
        }

        .problem-meta {
            align-items: flex-start;
        }

        .problem-details {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-info">
                <h1>
                    <i class="fas fa-exclamation-triangle"></i>
                    Problem Management
                </h1>
                <p>Manage and track all problems in the system efficiently</p>
            </div>
            <div class="header-action">
                @if(Auth::user()->role !== 'admin' && Auth::user()->role !== 'sub_admin')
                    <a href="{{ route('problems.create') }}" class="btn-new-problem">
                        <i class="fas fa-plus"></i>
                        Report New Problem
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card total">
            <div class="stat-content">
                <div class="stat-icon total">
                    <i class="fas fa-list"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $stats['total'] }}</h3>
                    <p>Total Problems</p>
                </div>
            </div>
        </div>
        <div class="stat-card reported">
            <div class="stat-content">
                <div class="stat-icon reported">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $stats['reported'] }}</h3>
                    <p>Reported</p>
                </div>
            </div>
        </div>
        <div class="stat-card in-progress">
            <div class="stat-content">
                <div class="stat-icon in-progress">
                    <i class="fas fa-tools"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $stats['in_progress'] }}</h3>
                    <p>In Progress</p>
                </div>
            </div>
        </div>
        <div class="stat-card resolved">
            <div class="stat-content">
                <div class="stat-icon resolved">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $stats['resolved'] }}</h3>
                    <p>Resolved</p>
                </div>
            </div>
        </div>
        <div class="stat-card urgent">
            <div class="stat-content">
                <div class="stat-icon urgent">
                    <i class="fas fa-fire"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $stats['urgent'] }}</h3>
                    <p>Urgent</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="filter-tabs">
            <a href="{{ route('problems.index') }}" class="filter-tab {{ !request('status') ? 'active' : '' }}">
                <i class="fas fa-list"></i>
                All Problems
            </a>
            <a href="{{ route('problems.index', ['status' => 'reported']) }}" class="filter-tab {{ request('status') == 'reported' ? 'active' : '' }}">
                <i class="fas fa-exclamation-circle"></i>
                Reported
            </a>
            <a href="{{ route('problems.index', ['status' => 'in_progress']) }}" class="filter-tab {{ request('status') == 'in_progress' ? 'active' : '' }}">
                <i class="fas fa-tools"></i>
                In Progress
            </a>
            <a href="{{ route('problems.index', ['status' => 'resolved']) }}" class="filter-tab {{ request('status') == 'resolved' ? 'active' : '' }}">
                <i class="fas fa-check-circle"></i>
                Resolved
            </a>
            <a href="{{ route('problems.index', ['priority' => 'urgent']) }}" class="filter-tab {{ request('priority') == 'urgent' ? 'active' : '' }}">
                <i class="fas fa-fire"></i>
                Urgent
            </a>
        </div>

        <form method="GET" action="{{ route('problems.index') }}" class="search-controls">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search problems..." class="search-input">
            <button type="submit" class="btn-search">
                <i class="fas fa-search"></i>
                Search
            </button>
            <a href="{{ route('problems.index') }}" class="btn-refresh">
                <i class="fas fa-sync-alt"></i>
                Refresh
            </a>
        </form>
    </div>

    <!-- Problems List -->
    <div class="problems-container">
        @forelse($problems as $problem)
            <div class="problem-card">
                <div class="problem-header">
                    <div class="problem-info">
                        <div class="problem-id">Problem #{{ str_pad($problem->id, 6, '0', STR_PAD_LEFT) }}</div>
                        <h3 class="problem-title">{{ $problem->title }}</h3>
                        <p class="problem-description">{{ Str::limit($problem->description, 120) }}</p>
                        <div class="problem-badges">
                            <span class="badge badge-status-{{ $problem->status }}">
                                @switch($problem->status)
                                    @case('reported')
                                        <i class="fas fa-exclamation-circle"></i>
                                        Reported
                                        @break
                                    @case('in_progress')
                                        <i class="fas fa-tools"></i>
                                        In Progress
                                        @break
                                    @case('resolved')
                                        <i class="fas fa-check-circle"></i>
                                        Resolved
                                        @break
                                @endswitch
                            </span>
                            <span class="badge badge-category">
                                <i class="fas fa-tag"></i>
                                {{ ucfirst($problem->category) }}
                            </span>
                            <span class="badge badge-priority-{{ $problem->priority }}">
                                @switch($problem->priority)
                                    @case('urgent')
                                        <i class="fas fa-fire"></i>
                                        Urgent
                                        @break
                                    @case('high')
                                        <i class="fas fa-arrow-up"></i>
                                        High
                                        @break
                                    @case('medium')
                                        <i class="fas fa-minus"></i>
                                        Medium
                                        @break
                                    @default
                                        <i class="fas fa-arrow-down"></i>
                                        Low
                                @endswitch
                            </span>
                        </div>
                    </div>
                    <div class="problem-meta">
                        <div class="problem-date">{{ $problem->created_at->format('M d, Y') }}</div>
                        <div class="category-icon {{ $problem->category }}">
                            @switch($problem->category)
                                @case('water')
                                    <i class="fas fa-tint"></i>
                                    @break
                                @case('electricity')
                                    <i class="fas fa-bolt"></i>
                                    @break
                                @case('road')
                                    <i class="fas fa-road"></i>
                                    @break
                                @case('sanitation')
                                    <i class="fas fa-trash"></i>
                                    @break
                                @default
                                    <i class="fas fa-exclamation"></i>
                            @endswitch
                        </div>
                    </div>
                </div>

                <div class="problem-details">
                    <div class="detail-item">
                        <i class="fas fa-user"></i>
                        <span>{{ $problem->familyMember->name ?? 'Unknown' }}</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>
                            @if($problem->familyMember && $problem->familyMember->house)
                                House {{ $problem->familyMember->house->house_number }}
                            @else
                                Address N/A
                            @endif
                        </span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-clock"></i>
                        <span>{{ $problem->created_at->diffForHumans() }}</span>
                    </div>
                    @if($problem->estimated_cost || $problem->actual_cost)
                        <div class="detail-item">
                            <i class="fas fa-dollar-sign"></i>
                            <span>৳{{ number_format($problem->actual_cost ?? $problem->estimated_cost) }}</span>
                        </div>
                    @endif
                </div>

                <div class="problem-actions">
                    <div class="action-buttons">
                        <a href="{{ route('problems.show', $problem) }}" class="action-btn view" title="View Details">
                            <i class="fas fa-eye"></i>
                        </a>
                        
                        @if(Auth::user()->isAdminOrSubAdmin())
                            <!-- Admin users get Status Update button -->
                            <a href="{{ route('problems.status-update', $problem) }}" class="action-btn status" title="Update Status">
                                <i class="fas fa-tasks"></i>
                            </a>
                        @else
                            <!-- Regular users only get View button -->
                        @endif
                    </div>
                    @if($problem->actual_cost || $problem->estimated_cost)
                        <div class="cost-info">
                            Cost: ৳{{ number_format($problem->actual_cost ?? $problem->estimated_cost) }}
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <h3 class="empty-title">No Problems Found</h3>
                <p class="empty-description">
                    @if(request('search') || request('status') || request('priority'))
                        No problems match your current filters. Try adjusting your search criteria.
                    @else
                        No problems have been reported yet. 
                        @if(Auth::user()->role !== 'admin' && Auth::user()->role !== 'sub_admin')
                            Click the "Report New Problem" button to get started.
                        @endif
                    @endif
                </p>
                @if(request('search') || request('status') || request('priority'))
                    <a href="{{ route('problems.index') }}" class="btn-new-problem" style="background: #4f46e5; border-color: #4f46e5;">
                        <i class="fas fa-list"></i>
                        View All Problems
                    </a>
                @endif
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($problems->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $problems->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection