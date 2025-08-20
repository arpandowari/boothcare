@extends('layouts.public')

@section('title', 'BoothCare - Professional Community Management System')

@push('styles')
<style>
    /* Modern Professional Design */
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

    /* Notice Board */
    .notice-board {
        background: var(--primary-blue);
        color: white;
        padding: 1rem 0;
        position: relative;
        overflow: hidden;
    }

    .notice-content {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        gap: 2rem;
        padding: 0 2rem;
    }

    .notice-icon {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    .notice-text {
        flex: 1;
        font-size: 1.1rem;
        font-weight: 500;
    }

    .notice-marquee {
        white-space: nowrap;
        overflow: hidden;
        animation: marquee 30s linear infinite;
    }

    @keyframes marquee {
        0% { transform: translateX(100%); }
        100% { transform: translateX(-100%); }
    }

    /* Hero Section with Slider */
    .hero-section {
        position: relative;
        height: 100vh;
        min-height: 600px;
        overflow: hidden;
    }

    .image-slider {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
    }

    .slider-container {
        position: relative;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }

    .slide {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        transition: opacity 1s ease-in-out;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    .slide.active {
        opacity: 1;
    }

    .slide::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(30, 64, 175, 0.8) 0%, rgba(59, 130, 246, 0.6) 100%);
        z-index: 1;
    }

    .slide-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        color: white;
        z-index: 2;
        max-width: 800px;
        padding: 0 2rem;
    }

    .slide-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .slide-title {
        font-size: 3.5rem;
        font-weight: 900;
        margin-bottom: 1.5rem;
        line-height: 1.1;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        letter-spacing: -0.02em;
    }

    .slide-description {
        font-size: 1.2rem;
        font-weight: 400;
        margin-bottom: 2rem;
        opacity: 0.95;
        line-height: 1.6;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    .cta-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .cta-button {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        background: var(--white);
        color: var(--primary-blue);
        padding: 1rem 2rem;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 700;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        border: 2px solid transparent;
    }

    .cta-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        color: var(--primary-blue-dark);
    }

    .cta-button.secondary {
        background: transparent;
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.8);
    }

    .cta-button.secondary:hover {
        background: white;
        color: var(--primary-blue);
    }

    /* Slider Navigation */
    .slider-nav {
        position: absolute;
        bottom: 40px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 16px;
        z-index: 3;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(20px);
        padding: 1rem 2rem;
        border-radius: 50px;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .nav-dot {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.5);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .nav-dot.active {
        background: var(--white);
        transform: scale(1.2);
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.8);
    }

    .nav-dot:hover {
        background: rgba(255, 255, 255, 0.8);
        transform: scale(1.1);
    }

    /* Slider Arrows */
    .slider-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(20px);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: white;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 3;
        font-size: 20px;
    }

    .slider-arrow:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-50%) scale(1.1);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .slider-arrow.prev {
        left: 40px;
    }

    .slider-arrow.next {
        right: 40px;
    }

    /* Notice Cards */
    .notice-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
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
        margin-bottom: 1rem;
    }

    .notice-type {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
    }

    .notice-card.urgent .notice-type {
        background: rgba(220, 38, 38, 0.1);
        color: var(--danger);
    }

    .notice-card.important .notice-type {
        background: rgba(217, 119, 6, 0.1);
        color: var(--warning);
    }

    .notice-card.general .notice-type {
        background: rgba(30, 64, 175, 0.1);
        color: var(--primary-blue);
    }

    .notice-date {
        font-size: 0.8rem;
        color: var(--text-light);
        font-weight: 500;
    }

    .notice-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.75rem;
        line-height: 1.3;
    }

    .notice-content-text {
        color: var(--text-gray);
        line-height: 1.6;
        margin-bottom: 1rem;
        font-size: 0.95rem;
    }

    .notice-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid var(--gray-100);
    }

    .notice-author {
        font-size: 0.85rem;
        color: var(--text-light);
        font-weight: 500;
    }

    /* Booth Cards */
    .booth-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0,0,0,0.08);
        border: 1px solid rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        position: relative;
    }

    .booth-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 48px rgba(0,0,0,0.15);
    }

    .booth-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--primary-blue);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .booth-card:hover::before {
        opacity: 1;
    }

    .booth-image {
        height: 220px;
        overflow: hidden;
        position: relative;
        background: var(--gray-100);
    }

    .booth-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .booth-card:hover .booth-image img {
        transform: scale(1.05);
    }

    .booth-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        background: var(--gray-100);
    }

    .booth-placeholder i {
        font-size: 4rem;
        color: #94a3b8;
    }

    /* Service Cards */
    .service-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border: 1px solid rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        height: 100%;
    }

    .service-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 32px rgba(0,0,0,0.12);
    }

    .service-icon {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 2rem;
        color: white;
        background: var(--primary-blue);
        box-shadow: 0 8px 24px rgba(30, 64, 175, 0.3);
    }

    .info-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border: 1px solid rgba(0,0,0,0.05);
        height: 100%;
    }

    .contact-card {
        background: var(--primary-blue);
        color: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 8px 32px rgba(30, 64, 175, 0.3);
    }

    .contact-item {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        padding: 0.5rem 0;
    }

    .contact-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 1.2rem;
    }

    .btn-modern {
        background: var(--primary-blue);
        border: none;
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 600;
        color: white;
        transition: all 0.3s ease;
        box-shadow: 0 4px 16px rgba(30, 64, 175, 0.3);
    }

    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(30, 64, 175, 0.4);
        color: white;
    }

    .section-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: #2d3748;
        margin-bottom: 1rem;
        position: relative;
    }

    .section-subtitle {
        font-size: 1.1rem;
        color: #718096;
        margin-bottom: 3rem;
    }

    .filter-bar {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border: 1px solid rgba(0,0,0,0.05);
        margin-bottom: 2rem;
    }

    .badge-modern {
        background: #10b981;
        color: white;
        border-radius: 8px;
        padding: 6px 12px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .rating-stars {
        color: #fbbf24;
        font-size: 1rem;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .hero-section {
            min-height: 500px;
        }
        
        .slide-title {
            font-size: 2.5rem;
        }
        
        .slide-description {
            font-size: 1rem;
        }
        
        .cta-buttons {
            flex-direction: column;
            align-items: center;
        }
        
        .cta-button {
            width: 100%;
            max-width: 280px;
            justify-content: center;
        }
        
        .slider-arrow {
            width: 50px;
            height: 50px;
            font-size: 16px;
        }
        
        .slider-arrow.prev {
            left: 20px;
        }
        
        .slider-arrow.next {
            right: 20px;
        }
        
        .slider-nav {
            padding: 0.75rem 1.5rem;
            gap: 12px;
        }
        
        .notice-content {
            padding: 0 1rem;
            gap: 1rem;
        }
        
        .notice-icon {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }
        
        .notice-text {
            font-size: 1rem;
        }
        
        .section-title {
            font-size: 2rem;
        }
        
        .section-subtitle {
            font-size: 1rem;
        }
    }

    @media (max-width: 480px) {
        .slide-title {
            font-size: 2rem;
        }
        
        .slide-description {
            font-size: 0.9rem;
        }
        
        .slider-nav {
            padding: 0.5rem 1rem;
            gap: 8px;
        }
        
        .nav-dot {
            width: 10px;
            height: 10px;
        }
        
        .notice-marquee {
            font-size: 0.9rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Notice Board -->
<section class="notice-board">
    <div class="notice-content">
        <div class="notice-icon">
            <i class="fas fa-bullhorn"></i>
        </div>
        <div class="notice-text">
            <div class="notice-marquee">
                🔔 Important Notice: New member registration is now open for all areas. 
                📋 Document verification process has been simplified. 
                🎯 Community feedback system is now live - share your experience! 
                📞 24/7 support available for urgent issues.
            </div>
        </div>
    </div>
</section>

<!-- Enhanced Hero Section -->
<section class="hero-section" id="home">
    <div class="image-slider">
        <div class="slider-container">
            <!-- Slide 1 -->
            <div class="slide active" style="background-image: url('https://images.unsplash.com/photo-1560472354-b33ff0c44a43?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');">
                <div class="slide-content">
                    <div class="slide-badge">🏛️ Government Approved Platform</div>
                    <h1 class="slide-title">BoothCare Management</h1>
                    <p class="slide-description">
                        India's most trusted community management platform with government-grade security, 
                        Aadhar verification, and comprehensive booth administration services.
                    </p>
                    <div class="cta-buttons">
                        <a href="#booths" class="cta-button">
                            <i class="fas fa-search"></i>
                            Find Your Booth
                        </a>
                        <a href="{{ route('login') }}" class="cta-button secondary">
                            <i class="fas fa-user"></i>
                            Member Login
                        </a>
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');">
                <div class="slide-content">
                    <div class="slide-badge">🚀 Advanced Technology</div>
                    <h1 class="slide-title">Smart Community Solutions</h1>
                    <p class="slide-description">
                        Streamline your community operations with AI-powered analytics, 
                        real-time reporting, and automated member management systems.
                    </p>
                    <div class="cta-buttons">
                        <a href="#features" class="cta-button">
                            <i class="fas fa-cogs"></i>
                            Explore Features
                        </a>
                        <a href="#contact" class="cta-button secondary">
                            <i class="fas fa-phone"></i>
                            Get Support
                        </a>
                    </div>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1521737604893-d14cc237f11d?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');">
                <div class="slide-content">
                    <div class="slide-badge">🔒 Bank-Level Security</div>
                    <h1 class="slide-title">Secure & Compliant</h1>
                    <p class="slide-description">
                        Your data is protected with military-grade encryption, Aadhar verification, 
                        and compliance with all government data protection regulations.
                    </p>
                    <div class="cta-buttons">
                        <a href="#features" class="cta-button">
                            <i class="fas fa-shield-alt"></i>
                            Security Details
                        </a>
                        <a href="#booths" class="cta-button secondary">
                            <i class="fas fa-building"></i>
                            Browse Booths
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Navigation Arrows -->
        <div class="slider-arrow prev" onclick="previousSlide()">
            <i class="fas fa-chevron-left"></i>
        </div>
        <div class="slider-arrow next" onclick="nextSlide()">
            <i class="fas fa-chevron-right"></i>
        </div>

        <!-- Enhanced Navigation Dots -->
        <div class="slider-nav">
            <div class="nav-dot active" onclick="goToSlide(0)"></div>
            <div class="nav-dot" onclick="goToSlide(1)"></div>
            <div class="nav-dot" onclick="goToSlide(2)"></div>
        </div>
    </div>
</section>

<!-- Community Notices Section -->
<section class="py-5 bg-white" id="notices">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Community Notices</h2>
            <p class="section-subtitle">Stay updated with the latest announcements and important information</p>
        </div>
        
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="notice-card urgent">
                    <div class="notice-header">
                        <div class="notice-type">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>Urgent</span>
                        </div>
                        <div class="notice-date">Today</div>
                    </div>
                    <h5 class="notice-title">System Maintenance Notice</h5>
                    <p class="notice-content-text">
                        Scheduled maintenance on Sunday, 2:00 AM - 4:00 AM. 
                        Services may be temporarily unavailable during this period.
                    </p>
                    <div class="notice-footer">
                        <span class="notice-author">Admin Team</span>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="notice-card important">
                    <div class="notice-header">
                        <div class="notice-type">
                            <i class="fas fa-info-circle"></i>
                            <span>Important</span>
                        </div>
                        <div class="notice-date">2 days ago</div>
                    </div>
                    <h5 class="notice-title">New Document Verification Process</h5>
                    <p class="notice-content-text">
                        We've simplified the document verification process. 
                        Now you can upload documents directly through the portal.
                    </p>
                    <div class="notice-footer">
                        <span class="notice-author">Verification Team</span>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="notice-card general">
                    <div class="notice-header">
                        <div class="notice-type">
                            <i class="fas fa-bullhorn"></i>
                            <span>General</span>
                        </div>
                        <div class="notice-date">1 week ago</div>
                    </div>
                    <h5 class="notice-title">Community Feedback System Live</h5>
                    <p class="notice-content-text">
                        Share your experience and help us improve our services. 
                        Your feedback is valuable to us and the community.
                    </p>
                    <div class="notice-footer">
                        <span class="notice-author">Community Manager</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('login') }}" class="btn-modern">
                <i class="fas fa-bell me-2"></i>
                View All Notices
            </a>
        </div>
    </div>
</section>

<!-- Available Booths -->
<section class="py-5 bg-light" id="booths">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Available Booths</h2>
            <p class="section-subtitle">Access community booth information and services</p>
        </div>

        <div class="filter-bar">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h5 class="fw-bold mb-0">Browse Booths</h5>
                    <small class="text-muted">Select an area to filter booths or view all available options</small>
                </div>
                <div class="col-lg-6">
                    <div class="d-flex gap-3 justify-content-lg-end">
                        <select class="form-select" style="max-width: 200px;">
                            <option>All Areas</option>
                            @foreach(\App\Models\Area::all() as $area)
                                <option value="{{ $area->id }}">{{ $area->area_name }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-outline-primary">
                            <i class="fas fa-filter me-1"></i>Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            @forelse($booths as $booth)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="booth-card h-100">
                        @if($booth->images->count() > 0)
                            <div class="booth-image">
                                <img src="{{ $booth->images->first()->image_url }}" alt="{{ $booth->booth_name }}">
                            </div>
                        @else
                            <div class="booth-image">
                                <div class="booth-placeholder">
                                    <i class="fas fa-building"></i>
                                </div>
                            </div>
                        @endif

                        <div class="p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="fw-bold mb-0">{{ $booth->booth_name }}</h5>
                                <span class="badge-modern">Active</span>
                            </div>
                            
                            <p class="text-muted mb-3">
                                <i class="fas fa-map-marker-alt me-2"></i>{{ $booth->area->area_name ?? 'N/A' }}
                                @if($booth->booth_number)
                                    <br><small>Booth #{{ $booth->booth_number }}</small>
                                @endif
                            </p>

                            <div class="row text-center mb-4">
                                <div class="col-4">
                                    <div class="border-end">
                                        <div class="fw-bold text-primary fs-5">{{ $booth->total_members ?? 0 }}</div>
                                        <small class="text-muted">Members</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border-end">
                                        <div class="fw-bold text-success fs-5">{{ $booth->total_houses ?? 0 }}</div>
                                        <small class="text-muted">Houses</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="fw-bold text-warning fs-5">{{ $booth->total_reviews ?? 0 }}</div>
                                    <small class="text-muted">Reviews</small>
                                </div>
                            </div>

                            @if($booth->total_reviews > 0)
                                <div class="d-flex align-items-center justify-content-center mb-4">
                                    <div class="rating-stars me-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star{{ $booth->average_rating >= $i ? '' : '-o' }}"></i>
                                        @endfor
                                    </div>
                                    <small class="text-muted">{{ number_format($booth->average_rating ?? 0, 1) }} ({{ $booth->total_reviews }} reviews)</small>
                                </div>
                            @endif

                            <div class="d-grid">
                                <a href="{{ route('public.booth.show', $booth) }}" class="btn-modern">
                                    <i class="fas fa-eye me-2"></i>Access Booth Services
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <div class="booth-placeholder d-inline-flex align-items-center justify-content-center mb-4" style="width: 120px; height: 120px; border-radius: 20px;">
                            <i class="fas fa-building" style="font-size: 4rem;"></i>
                        </div>
                        <h4 class="fw-bold text-muted">No Booths Available</h4>
                        <p class="text-muted">Please contact the administrator to set up booth information.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
// Enhanced Image Slider Functionality
let currentSlide = 0;
const slides = document.querySelectorAll('.slide');
const dots = document.querySelectorAll('.nav-dot');
const totalSlides = slides.length;
let slideInterval;

function showSlide(index) {
    slides.forEach(slide => slide.classList.remove('active'));
    dots.forEach(dot => dot.classList.remove('active'));
    
    slides[index].classList.add('active');
    dots[index].classList.add('active');
}

function nextSlide() {
    currentSlide = (currentSlide + 1) % totalSlides;
    showSlide(currentSlide);
}

function previousSlide() {
    currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
    showSlide(currentSlide);
}

function goToSlide(index) {
    currentSlide = index;
    showSlide(currentSlide);
    resetSlideInterval();
}

function startSlideInterval() {
    slideInterval = setInterval(nextSlide, 5000);
}

function resetSlideInterval() {
    clearInterval(slideInterval);
    startSlideInterval();
}

// Start auto-advance
startSlideInterval();

// Pause on hover
const sliderContainer = document.querySelector('.slider-container');
if (sliderContainer) {
    sliderContainer.addEventListener('mouseenter', () => {
        clearInterval(slideInterval);
    });

    sliderContainer.addEventListener('mouseleave', () => {
        startSlideInterval();
    });
}

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    if (e.key === 'ArrowLeft') {
        previousSlide();
        resetSlideInterval();
    } else if (e.key === 'ArrowRight') {
        nextSlide();
        resetSlideInterval();
    }
});

// Touch/swipe support
let startX = 0;
let endX = 0;

if (sliderContainer) {
    sliderContainer.addEventListener('touchstart', function(e) {
        startX = e.touches[0].clientX;
    });

    sliderContainer.addEventListener('touchend', function(e) {
        endX = e.changedTouches[0].clientX;
        handleSwipe();
    });
}

function handleSwipe() {
    const swipeThreshold = 50;
    const diff = startX - endX;
    
    if (Math.abs(diff) > swipeThreshold) {
        if (diff > 0) {
            nextSlide();
        } else {
            previousSlide();
        }
        resetSlideInterval();
    }
}

// Smooth scrolling for CTA buttons
document.querySelectorAll('.cta-button').forEach(button => {
    button.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if (href && href.startsWith('#')) {
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                const offsetTop = target.offsetTop - 100;
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        }
    });
});

// Page initialization
document.addEventListener('DOMContentLoaded', function() {
    console.log('BoothCare page loaded successfully');
});
</script>
@endpush