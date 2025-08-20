@extends('layouts.public')

@section('title', 'BoothCare - Community Management System')

@push('styles')
<style>
    /* Simple, clean styles */
    .hero-section {
        background: linear-gradient(135deg, rgba(0, 0, 0, 0.4) 0%, rgba(0, 0, 0, 0.2) 100%), 
                    url('https://images.unsplash.com/photo-1560472354-b33ff0c44a43?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');
        background-size: cover;
        background-position: center;
        height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: white;
    }
    
    .hero-content {
        max-width: 800px;
        padding: 0 2rem;
    }
    
    .hero-title {
        font-size: 3.5rem;
        font-weight: 900;
        margin-bottom: 1.5rem;
        text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }
    
    .hero-description {
        font-size: 1.2rem;
        margin-bottom: 2rem;
        opacity: 0.95;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }
    
    .cta-button {
        display: inline-block;
        background: white;
        color: #1e40af;
        padding: 1rem 2rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 700;
        margin: 0 0.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }
    
    .cta-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        color: #1e3a8a;
    }
    
    .notice-bar {
        background: #1e40af;
        color: white;
        padding: 1rem;
        text-align: center;
    }
    
    .booth-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .booth-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    
    .booth-image {
        height: 200px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: #94a3b8;
    }
    
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }
        
        .hero-description {
            font-size: 1rem;
        }
        
        .cta-button {
            display: block;
            margin: 0.5rem auto;
            max-width: 250px;
        }
    }
</style>
@endpush

@section('content')
<!-- Notice Bar -->
@if(isset($marqueeNotices) && $marqueeNotices->count() > 0)
<div class="notice-bar">
    <i class="fas fa-bullhorn me-2"></i>
    @foreach($marqueeNotices as $notice)
        {{ $notice->title }}: {{ $notice->content }}
        @if(!$loop->last) | @endif
    @endforeach
</div>
@else
<div class="notice-bar">
    <i class="fas fa-bullhorn me-2"></i>
    Welcome to BoothCare - Your trusted community management platform
</div>
@endif

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-content">
        <h1 class="hero-title">BoothCare Management</h1>
        <p class="hero-description">
            India's most trusted community management platform with government-grade security, 
            Aadhar verification, and comprehensive booth administration services.
        </p>
        <div>
            <a href="#booths" class="cta-button">
                <i class="fas fa-search me-2"></i>Find Your Booth
            </a>
            <a href="{{ route('login') }}" class="cta-button">
                <i class="fas fa-user me-2"></i>Member Login
            </a>
        </div>
    </div>
</section>

<!-- Booths Section -->
<section class="py-5" id="booths">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold text-primary">Available Booths</h2>
            <p class="lead text-muted">Access community booth information and services</p>
        </div>
        
        <div class="row">
            @forelse($booths as $booth)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card booth-card h-100">
                        <div class="booth-image">
                            @if($booth->images && $booth->images->count() > 0)
                                <img src="{{ $booth->images->first()->image_url }}" alt="{{ $booth->booth_name }}" 
                                     style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <i class="fas fa-building"></i>
                            @endif
                        </div>
                        
                        <div class="card-body">
                            <h5 class="card-title text-primary">{{ $booth->booth_name }}</h5>
                            <p class="card-text text-muted">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                {{ $booth->area->area_name ?? 'N/A' }}
                            </p>
                            
                            <div class="row text-center mb-3">
                                <div class="col-4">
                                    <strong class="text-primary">{{ $booth->total_members ?? 0 }}</strong>
                                    <br><small class="text-muted">Members</small>
                                </div>
                                <div class="col-4">
                                    <strong class="text-success">{{ $booth->total_houses ?? 0 }}</strong>
                                    <br><small class="text-muted">Houses</small>
                                </div>
                                <div class="col-4">
                                    <strong class="text-warning">{{ $booth->total_reviews ?? 0 }}</strong>
                                    <br><small class="text-muted">Reviews</small>
                                </div>
                            </div>
                            
                            <a href="{{ route('public.booth.show', $booth) }}" class="btn btn-primary w-100">
                                <i class="fas fa-eye me-2"></i>View Details
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-building fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">No Booths Available</h4>
                        <p class="text-muted">Please contact the administrator to set up booth information.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Notices Section -->
@if(isset($cardNotices) && $cardNotices->count() > 0)
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold text-primary">Community Notices</h2>
            <p class="lead text-muted">Stay updated with the latest announcements</p>
        </div>
        
        <div class="row">
            @foreach($cardNotices as $notice)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span class="badge bg-{{ $notice->type === 'urgent' ? 'danger' : ($notice->type === 'important' ? 'warning' : 'primary') }}">
                                {{ ucfirst($notice->type) }}
                            </span>
                            <small class="text-muted">{{ $notice->created_at->diffForHumans() }}</small>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $notice->title }}</h5>
                            <p class="card-text">{{ $notice->content }}</p>
                        </div>
                        <div class="card-footer text-muted">
                            <small>By {{ $notice->author ?? $notice->creator->name }}</small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection