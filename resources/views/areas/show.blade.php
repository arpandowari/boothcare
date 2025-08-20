@extends('layouts.app')

@section('title', 'Area Details - Boothcare')
@section('page-title', 'Area: ' . $area->area_name)

@push('styles')
<style>
    /* Professional Area Details Styles */
    body {
        background: linear-gradient(135deg, #f8fafc 0%, #e3f2fd 100%);
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    .area-details-header {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 32px 20px;
        margin: -16px -16px 32px -16px;
        border-radius: 0 0 30px 30px;
        box-shadow: 0 8px 30px rgba(102, 126, 234, 0.3);
        position: relative;
        overflow: hidden;
    }

    .area-details-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><circle cx="40" cy="30" r="1.5" fill="white" opacity="0.3"><animate attributeName="opacity" values="0.1;0.3;0.1" dur="3s" repeatCount="indefinite"/></circle><circle cx="160" cy="50" r="1" fill="white" opacity="0.2"><animate attributeName="opacity" values="0.1;0.4;0.1" dur="4s" repeatCount="indefinite"/></circle></svg>');
        opacity: 0.6;
    }

    .breadcrumb-nav {
        background: white;
        border-radius: 16px;
        padding: 16px 20px;
        margin-bottom: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #f1f3f4;
    }

    .breadcrumb {
        margin: 0;
        background: none;
        padding: 0;
    }

    .breadcrumb-item a {
        color: #667eea;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .breadcrumb-item a:hover {
        color: #5a67d8;
        transform: translateX(2px);
    }

    .breadcrumb-item.active {
        color: #6b7280;
        font-weight: 600;
    }

    .area-info-card {
        background: white;
        border-radius: 20px;
        padding: 32px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #f1f3f4;
        margin-bottom: 24px;
        transition: all 0.3s ease;
    }

    .area-info-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .area-icon-section {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 24px;
    }

    .area-main-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: white;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        position: relative;
        flex-shrink: 0;
    }

    .area-title-section h1 {
        font-size: 2rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 8px;
    }

    .area-location {
        color: #6b7280;
        font-size: 1.1rem;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .area-status-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .area-status-badge.active {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .area-status-badge.inactive {
        background: #6c757d;
        color: white;
    }

    .area-description {
        color: #495057;
        font-size: 1rem;
        line-height: 1.6;
        margin: 20px 0;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 12px;
        border-left: 4px solid #667eea;
    }

    .area-actions {
        display: flex;
        gap: 12px;
        margin-top: 24px;
    }

    .action-btn {
        padding: 12px 24px;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }

    .action-btn.primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .action-btn.primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .action-btn.secondary {
        background: #f8f9fa;
        color: #6c757d;
        border: 2px solid #e9ecef;
    }

    .action-btn.secondary:hover {
        background: #e9ecef;
        color: #495057;
        transform: translateY(-2px);
    }

    .stats-card {
        background: white;
        border-radius: 20px;
        padding: 32px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #f1f3f4;
        text-align: center;
        transition: all 0.3s ease;
    }

    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .stats-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 20px;
    }

    .stat-item {
        text-align: center;
        padding: 16px;
        background: #f8f9fa;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .stat-item:hover {
        background: #e9ecef;
        transform: translateY(-2px);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .stat-label {
        font-size: 0.9rem;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .booths-section {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #f1f3f4;
        overflow: hidden;
        margin-top: 24px;
    }

    .booths-header {
        background: linear-gradient(135deg, #f8f9fa, #e3f2fd);
        padding: 24px;
        border-bottom: 1px solid #f1f3f4;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .booths-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .booth-card {
        background: white;
        border: 2px solid #f1f3f4;
        border-radius: 16px;
        padding: 24px;
        transition: all 0.3s ease;
        margin: 16px;
    }

    .booth-card:hover {
        border-color: #667eea;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .booth-header {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .booth-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .booth-info h6 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 700;
    }

    .booth-name {
        font-size: 1rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 8px;
    }

    .booth-description {
        color: #6c757d;
        font-size: 0.9rem;
        line-height: 1.5;
        margin-bottom: 16px;
    }

    .booth-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 20px;
    }

    .booth-stat {
        text-align: center;
        padding: 12px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .booth-stat-number {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .booth-stat-label {
        font-size: 0.8rem;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
    }

    .booth-actions {
        display: flex;
        gap: 8px;
    }

    .booth-action-btn {
        flex: 1;
        padding: 10px 16px;
        border: none;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .booth-action-btn.primary {
        background: #667eea;
        color: white;
    }

    .booth-action-btn.primary:hover {
        background: #5a67d8;
        color: white;
        transform: translateY(-1px);
    }

    .booth-action-btn.success {
        background: #10b981;
        color: white;
    }

    .booth-action-btn.success:hover {
        background: #059669;
        color: white;
        transform: translateY(-1px);
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }

    .empty-icon {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 20px;
    }

    .empty-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 12px;
    }

    .empty-description {
        margin-bottom: 24px;
        line-height: 1.6;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .area-details-header {
            padding: 24px 16px;
            margin: -16px -16px 20px -16px;
        }

        .area-info-card {
            padding: 24px;
        }

        .area-icon-section {
            flex-direction: column;
            text-align: center;
            gap: 16px;
        }

        .area-title-section h1 {
            font-size: 1.5rem;
        }

        .area-actions {
            flex-direction: column;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .booth-stats {
            grid-template-columns: 1fr;
        }

        .booth-actions {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('areas.index') }}">Areas</a></li>
            <li class="breadcrumb-item active">{{ $area->area_name }}</li>
        </ol>
    </nav>

    <!-- Area Information Card -->
    <div class="row">
        <div class="col-md-8">
            <div class="area-info-card">
                <div class="area-icon-section">
                    <div class="area-main-icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <div class="area-title-section">
                        <h1>{{ $area->area_name }}</h1>
                        <div class="area-location">
                            <i class="fas fa-map-marker-alt"></i>
                            {{ $area->district }}, {{ $area->division }}
                        </div>
                        <span class="area-status-badge {{ $area->is_active ? 'active' : 'inactive' }}">
                            <i class="fas fa-{{ $area->is_active ? 'check-circle' : 'times-circle' }}"></i>
                            {{ $area->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
                
                @if($area->description)
                    <div class="area-description">
                        <i class="fas fa-info-circle" style="color: #667eea; margin-right: 8px;"></i>
                        {{ $area->description }}
                    </div>
                @endif
                
                <div class="area-actions">
                    <a href="{{ route('areas.edit', $area) }}" class="action-btn secondary">
                        <i class="fas fa-edit"></i> Edit Area
                    </a>
                    <a href="{{ route('booths.create') }}?area_id={{ $area->id }}" class="action-btn primary">
                        <i class="fas fa-plus"></i> Add Booth
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="stats-card">
                <h2 class="stats-title">
                    <i class="fas fa-chart-bar"></i>
                    Area Statistics
                </h2>
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-number text-primary">{{ $area->booths->count() }}</div>
                        <div class="stat-label">Booths</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number text-success">{{ $area->total_houses ?? 0 }}</div>
                        <div class="stat-label">Houses</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number text-warning">{{ $area->total_members ?? 0 }}</div>
                        <div class="stat-label">Members</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number text-danger">{{ $area->total_problems ?? 0 }}</div>
                        <div class="stat-label">Problems</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Booths Section -->
    <div class="booths-section">
        <div class="booths-header">
            <h2 class="booths-title">
                <i class="fas fa-building"></i>
                Booths in {{ $area->area_name }} ({{ $area->booths->count() }})
            </h2>
            <a href="{{ route('booths.create') }}?area_id={{ $area->id }}" class="action-btn primary">
                <i class="fas fa-plus"></i>
                Add Booth
            </a>
        </div>
        
        @if($area->booths->count() > 0)
            <div class="row">
                @foreach($area->booths as $booth)
                <div class="col-md-6 col-lg-4">
                    <div class="booth-card">
                        <div class="booth-header">
                            <div class="booth-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="booth-info">
                                <h6>Booth {{ $booth->booth_number }}</h6>
                            </div>
                        </div>
                        
                        <div class="booth-name">{{ $booth->booth_name }}</div>
                        
                        @if($booth->description)
                            <div class="booth-description">{{ Str::limit($booth->description, 80) }}</div>
                        @endif
                        
                        <div class="booth-stats">
                            <div class="booth-stat">
                                <div class="booth-stat-number text-success">{{ $booth->houses->count() }}</div>
                                <div class="booth-stat-label">Houses</div>
                            </div>
                            <div class="booth-stat">
                                <div class="booth-stat-number text-warning">{{ $booth->total_members ?? 0 }}</div>
                                <div class="booth-stat-label">Members</div>
                            </div>
                            <div class="booth-stat">
                                <div class="booth-stat-number text-danger">{{ $booth->total_problems ?? 0 }}</div>
                                <div class="booth-stat-label">Problems</div>
                            </div>
                        </div>
                        
                        <div class="booth-actions">
                            <a href="{{ route('booths.show', $booth) }}" class="booth-action-btn primary">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="{{ route('houses.create') }}?booth_id={{ $booth->id }}" class="booth-action-btn success">
                                <i class="fas fa-plus"></i> Add House
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-building"></i>
                </div>
                <h3 class="empty-title">No booths in this area yet</h3>
                <div class="empty-description">Start building the hierarchy by adding the first booth in {{ $area->area_name }}.</div>
                <a href="{{ route('booths.create') }}?area_id={{ $area->id }}" class="action-btn primary">
                    <i class="fas fa-plus"></i>
                    Add First Booth
                </a>
            </div>
        @endif
    </div>
@endsection