@extends('layouts.public')

@section('title', 'BoothCare - Community Management System')

@section('content')
<div style="padding: 2rem 0; background: #f8fafc;">
    <div class="container">
        <div class="text-center mb-5">
            <h1 style="color: #1e40af; font-size: 3rem; font-weight: 800;">BoothCare</h1>
            <p style="font-size: 1.2rem; color: #64748b;">Professional Community Management Platform</p>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="card" style="border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
                    <div class="card-body text-center" style="padding: 3rem;">
                        <h2 style="color: #1e40af; margin-bottom: 1rem;">Welcome to BoothCare</h2>
                        <p style="color: #64748b; margin-bottom: 2rem;">
                            Your trusted platform for community management, member services, and booth administration.
                        </p>
                        <div>
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg me-3">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </a>
                            <a href="#booths" class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-building me-2"></i>View Booths
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="booths" style="padding: 3rem 0;">
    <div class="container">
        <h2 class="text-center mb-4" style="color: #1e40af;">Available Booths</h2>
        
        <div class="row">
            @forelse($booths as $booth)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100" style="border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                        <div class="card-body">
                            <h5 class="card-title" style="color: #1e40af;">{{ $booth->booth_name }}</h5>
                            <p class="card-text text-muted">
                                <i class="fas fa-map-marker-alt me-2"></i>{{ $booth->area->area_name ?? 'N/A' }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <small class="text-muted">{{ $booth->total_members ?? 0 }} Members</small>
                                <small class="text-muted">{{ $booth->total_houses ?? 0 }} Houses</small>
                            </div>
                            <a href="{{ route('public.booth.show', $booth) }}" class="btn btn-primary">
                                <i class="fas fa-eye me-2"></i>View Details
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <div style="font-size: 4rem; color: #e2e8f0; margin-bottom: 1rem;">
                            <i class="fas fa-building"></i>
                        </div>
                        <h4 style="color: #64748b;">No Booths Available</h4>
                        <p class="text-muted">Please contact the administrator to set up booth information.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<div style="background: #1e40af; color: white; padding: 2rem 0;">
    <div class="container">
        <div class="row">
            <div class="col-md-4 text-center mb-3">
                <h3>{{ $booths->count() }}</h3>
                <p>Active Booths</p>
            </div>
            <div class="col-md-4 text-center mb-3">
                <h3>{{ $booths->sum('total_members') }}</h3>
                <p>Community Members</p>
            </div>
            <div class="col-md-4 text-center mb-3">
                <h3>24/7</h3>
                <p>Support Available</p>
            </div>
        </div>
    </div>
</div>
@endsection