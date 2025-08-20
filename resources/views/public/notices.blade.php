@extends('layouts.public')

@section('title', 'Community Notices - BoothCare')

@push('styles')
<style>
    :root {
        --primary-blue: #1e40af;
        --primary-blue-dark: #1e3a8a;
        --primary-blue-light: #3b82f6;
        --text-dark: #0f172a;
        --text-gray: #64748b;
        --text-light: #94a3b8;
        --white: #ffffff;
        --gray-50: #f8fafc;
        --gray-100: #f1f5f9;
        --success: #059669;
        --warning: #d97706;
        --danger: #dc2626;
    }

    .page-header {
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-blue-dark) 100%);
        color: white;
        padding: 4rem 0 2rem;
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="20" cy="80" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
    }

    .page-header-content {
        position: relative;
        z-index: 2;
    }

    .page-title {
        font-size: 3rem;
        font-weight: 900;
        margin-bottom: 1rem;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }

    .page-subtitle {
        font-size: 1.2rem;
        opacity: 0.9;
        font-weight: 400;
    }

    .notice-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--gray-100);
        transition: all 0.3s ease;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .notice-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        transition: all 0.3s ease;
    }

    .notice-card.urgent::before {
        background: var(--danger);
    }

    .notice-card.important::before {
        background: var(--warning);
    }

    .notice-card.general::before {
        background: var(--primary-blue);
    }

    .notice-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .notice-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .notice-type {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .notice-card.urgent .notice-type {
        background: rgba(220, 38, 38, 0.1);
        color: var(--danger);
        border: 1px solid rgba(220, 38, 38, 0.2);
    }

    .notice-card.important .notice-type {
        background: rgba(217, 119, 6, 0.1);
        color: var(--warning);
        border: 1px solid rgba(217, 119, 6, 0.2);
    }

    .notice-card.general .notice-type {
        background: rgba(30, 64, 175, 0.1);
        color: var(--primary-blue);
        border: 1px solid rgba(30, 64, 175, 0.2);
    }

    .notice-date {
        font-size: 0.8rem;
        color: var(--text-light);
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .notice-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 1rem;
        line-height: 1.3;
    }

    .notice-content-text {
        color: var(--text-gray);
        line-height: 1.7;
        margin-bottom: 1.5rem;
        font-size: 1rem;
    }

    .notice-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1.5rem;
        border-top: 1px solid var(--gray-100);
    }

    .notice-author {
        font-size: 0.9rem;
        color: var(--text-light);
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .notice-priority {
        background: var(--gray-100);
        color: var(--text-gray);
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .filter-section {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--gray-100);
        margin-bottom: 2rem;
    }

    .filter-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 1rem;
    }

    .filter-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .filter-btn {
        padding: 0.75rem 1.5rem;
        border: 2px solid var(--gray-100);
        background: white;
        color: var(--text-gray);
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
    }

    .filter-btn:hover,
    .filter-btn.active {
        border-color: var(--primary-blue);
        background: var(--primary-blue);
        color: white;
        transform: translateY(-2px);
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .back-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
        transform: translateY(-2px);
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-light);
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1.5rem;
        opacity: 0.5;
    }

    .empty-state h3 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: var(--text-gray);
    }

    @media (max-width: 768px) {
        .page-title {
            font-size: 2rem;
        }
        
        .page-subtitle {
            font-size: 1rem;
        }
        
        .filter-buttons {
            justify-content: center;
        }
        
        .notice-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .notice-footer {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="page-header-content">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="page-title">Community Notices</h1>
                    <p class="page-subtitle">Stay informed with the latest announcements and important community updates</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('home') }}" class="back-btn">
                        <i class="fas fa-arrow-left"></i>
                        Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Filter Section -->
<section class="py-4">
    <div class="container">
        <div class="filter-section">
            <h3 class="filter-title">Filter by Type</h3>
            <div class="filter-buttons">
                <a href="{{ route('public.notices') }}" class="filter-btn {{ !request('type') ? 'active' : '' }}">
                    <i class="fas fa-list me-2"></i>All Notices
                </a>
                <a href="{{ route('public.notices', ['type' => 'urgent']) }}" class="filter-btn {{ request('type') === 'urgent' ? 'active' : '' }}">
                    <i class="fas fa-exclamation-triangle me-2"></i>Urgent
                </a>
                <a href="{{ route('public.notices', ['type' => 'important']) }}" class="filter-btn {{ request('type') === 'important' ? 'active' : '' }}">
                    <i class="fas fa-info-circle me-2"></i>Important
                </a>
                <a href="{{ route('public.notices', ['type' => 'general']) }}" class="filter-btn {{ request('type') === 'general' ? 'active' : '' }}">
                    <i class="fas fa-bullhorn me-2"></i>General
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Notices Grid -->
<section class="py-4 pb-5">
    <div class="container">
        @if($notices->count() > 0)
            <div class="row">
                @foreach($notices as $notice)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="notice-card {{ $notice->type }}">
                            <div class="notice-header">
                                <div class="notice-type">
                                    @if($notice->type === 'urgent')
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <span>Urgent</span>
                                    @elseif($notice->type === 'important')
                                        <i class="fas fa-info-circle"></i>
                                        <span>Important</span>
                                    @else
                                        <i class="fas fa-bullhorn"></i>
                                        <span>General</span>
                                    @endif
                                </div>
                                <div class="notice-date">
                                    <i class="fas fa-calendar-alt"></i>
                                    {{ $notice->created_at->format('M d, Y') }}
                                </div>
                            </div>
                            
                            <h4 class="notice-title">{{ $notice->title }}</h4>
                            <div class="notice-content-text">{{ $notice->content }}</div>
                            
                            <div class="notice-footer">
                                <div class="notice-author">
                                    <i class="fas fa-user"></i>
                                    {{ $notice->author ?? $notice->creator->name ?? 'Admin' }}
                                </div>
                                <div class="notice-priority">
                                    Priority: {{ $notice->priority }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($notices->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $notices->appends(request()->query())->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <i class="fas fa-bell-slash"></i>
                <h3>No Notices Found</h3>
                <p>There are currently no {{ request('type') ? request('type') : '' }} notices to display.</p>
                @if(request('type'))
                    <a href="{{ route('public.notices') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-list me-2"></i>View All Notices
                    </a>
                @endif
            </div>
        @endif
    </div>
</section>
@endsection