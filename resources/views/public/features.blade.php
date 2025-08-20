@extends('layouts.app')

@section('title', 'Features - Boothcare')
@section('page-title', 'Our Features')

@push('styles')
<style>
    /* Professional Blue Theme Features Page */
    body {
        background: #f8fafc;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    .features-wrapper {
        min-height: 100vh;
        background: #f8fafc;
    }

    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        color: white;
        padding: 4rem 0;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 80%, rgba(59, 130, 246, 0.3) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
        pointer-events: none;
    }

    .hero-content {
        position: relative;
        z-index: 2;
        max-width: 800px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .hero-title {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 1.5rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .hero-subtitle {
        font-size: 1.25rem;
        opacity: 0.9;
        margin-bottom: 2rem;
        line-height: 1.6;
    }

    /* Image Slider Section */
    .slider-section {
        padding: 4rem 0;
        background: white;
    }

    .slider-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .slider-header {
        text-align: center;
        margin-bottom: 3rem;
    }

    .slider-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #1e40af;
        margin-bottom: 1rem;
    }

    .slider-description {
        font-size: 1.1rem;
        color: #64748b;
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* Image Slider */
    .image-slider {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(30, 64, 175, 0.15);
        background: white;
    }

    .slider-wrapper {
        position: relative;
        width: 100%;
        height: 500px;
        overflow: hidden;
    }

    .slide {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        transition: opacity 0.8s ease-in-out;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .slide.active {
        opacity: 1;
    }

    .slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .slide-content {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(transparent, rgba(30, 64, 175, 0.9));
        color: white;
        padding: 3rem 2rem 2rem;
        text-align: center;
    }

    .slide-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .slide-description {
        font-size: 1rem;
        opacity: 0.9;
        line-height: 1.5;
    }

    /* Slider Controls */
    .slider-controls {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 12px;
        z-index: 10;
    }

    .slider-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.5);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .slider-dot.active {
        background: white;
        transform: scale(1.2);
    }

    .slider-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.9);
        border: none;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: #1e40af;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 10;
    }

    .slider-nav:hover {
        background: white;
        transform: translateY(-50%) scale(1.1);
        box-shadow: 0 4px 20px rgba(30, 64, 175, 0.2);
    }

    .slider-prev {
        left: 20px;
    }

    .slider-next {
        right: 20px;
    }

    /* Features Grid */
    .features-section {
        padding: 4rem 0;
        background: #f8fafc;
    }

    .features-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-top: 3rem;
    }

    .feature-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(30, 64, 175, 0.08);
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        text-align: center;
    }

    .feature-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(30, 64, 175, 0.15);
        border-color: #3b82f6;
    }

    .feature-icon {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 1.5rem;
        color: white;
    }

    .feature-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e40af;
        margin-bottom: 1rem;
    }

    .feature-description {
        color: #64748b;
        line-height: 1.6;
        font-size: 0.95rem;
    }

    /* CTA Section */
    .cta-section {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        color: white;
        padding: 4rem 0;
        text-align: center;
    }

    .cta-content {
        max-width: 600px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .cta-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .cta-description {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 2rem;
        line-height: 1.6;
    }

    .cta-button {
        background: white;
        color: #1e40af;
        padding: 1rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1.1rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .cta-button:hover {
        color: #1e40af;
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2rem;
        }

        .hero-subtitle {
            font-size: 1.1rem;
        }

        .slider-title {
            font-size: 2rem;
        }

        .slider-wrapper {
            height: 300px;
        }

        .slide-content {
            padding: 2rem 1rem 1rem;
        }

        .slide-title {
            font-size: 1.2rem;
        }

        .slide-description {
            font-size: 0.9rem;
        }

        .slider-nav {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }

        .slider-prev {
            left: 10px;
        }

        .slider-next {
            right: 10px;
        }

        .features-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .feature-card {
            padding: 1.5rem;
        }

        .cta-title {
            font-size: 2rem;
        }

        .cta-description {
            font-size: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="features-wrapper">
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title">Powerful Features</h1>
            <p class="hero-subtitle">
                Discover the comprehensive tools and capabilities that make Boothcare 
                the perfect solution for community management and problem resolution.
            </p>
        </div>
    </section>

    <!-- Image Slider Section -->
    <section class="slider-section">
        <div class="slider-container">
            <div class="slider-header">
                <h2 class="slider-title">See Boothcare in Action</h2>
                <p class="slider-description">
                    Explore our intuitive interface and powerful features through these interactive demonstrations.
                </p>
            </div>

            <div class="image-slider">
                <div class="slider-wrapper">
                    <!-- Slide 1 -->
                    <div class="slide active">
                        <img src="https://images.unsplash.com/photo-1551434678-e076c223a692?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Dashboard Overview">
                        <div class="slide-content">
                            <h3 class="slide-title">Comprehensive Dashboard</h3>
                            <p class="slide-description">Get a complete overview of your community with real-time statistics, charts, and activity tracking.</p>
                        </div>
                    </div>

                    <!-- Slide 2 -->
                    <div class="slide">
                        <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2015&q=80" alt="Problem Management">
                        <div class="slide-content">
                            <h3 class="slide-title">Smart Problem Management</h3>
                            <p class="slide-description">Efficiently track, categorize, and resolve community issues with our intelligent problem management system.</p>
                        </div>
                    </div>

                    <!-- Slide 3 -->
                    <div class="slide">
                        <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="User Management">
                        <div class="slide-content">
                            <h3 class="slide-title">Advanced User Management</h3>
                            <p class="slide-description">Manage family members, update requests, and user profiles with ease and security.</p>
                        </div>
                    </div>

                    <!-- Slide 4 -->
                    <div class="slide">
                        <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Analytics">
                        <div class="slide-content">
                            <h3 class="slide-title">Detailed Analytics</h3>
                            <p class="slide-description">Make data-driven decisions with comprehensive analytics and reporting tools.</p>
                        </div>
                    </div>

                    <!-- Slide 5 -->
                    <div class="slide">
                        <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Mobile Experience">
                        <div class="slide-content">
                            <h3 class="slide-title">Mobile-First Design</h3>
                            <p class="slide-description">Access all features seamlessly on any device with our responsive, mobile-optimized interface.</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <button class="slider-nav slider-prev" onclick="previousSlide()">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="slider-nav slider-next" onclick="nextSlide()">
                    <i class="fas fa-chevron-right"></i>
                </button>

                <!-- Dots -->
                <div class="slider-controls">
                    <span class="slider-dot active" onclick="currentSlide(1)"></span>
                    <span class="slider-dot" onclick="currentSlide(2)"></span>
                    <span class="slider-dot" onclick="currentSlide(3)"></span>
                    <span class="slider-dot" onclick="currentSlide(4)"></span>
                    <span class="slider-dot" onclick="currentSlide(5)"></span>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Grid -->
    <section class="features-section">
        <div class="features-container">
            <div class="slider-header">
                <h2 class="slider-title">Core Features</h2>
                <p class="slider-description">
                    Everything you need to manage your community effectively and efficiently.
                </p>
            </div>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <h3 class="feature-title">Real-time Dashboard</h3>
                    <p class="feature-description">
                        Monitor community activity with live statistics, interactive charts, and instant notifications for critical updates.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h3 class="feature-title">Problem Tracking</h3>
                    <p class="feature-description">
                        Report, track, and resolve community issues with priority-based categorization and status updates.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="feature-title">Family Management</h3>
                    <p class="feature-description">
                        Manage family members, update profiles, and handle document uploads with secure approval workflows.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="feature-title">Secure Authentication</h3>
                    <p class="feature-description">
                        Multi-factor authentication with OTP verification ensures secure access to sensitive community data.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3 class="feature-title">Mobile Responsive</h3>
                    <p class="feature-description">
                        Access all features seamlessly across devices with our mobile-first, app-like user interface.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="feature-title">Advanced Analytics</h3>
                    <p class="feature-description">
                        Gain insights with comprehensive reporting, trend analysis, and data visualization tools.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3 class="feature-title">Email Notifications</h3>
                    <p class="feature-description">
                        Automated email notifications keep users informed about status updates and important announcements.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-cog"></i>
                    </div>
                    <h3 class="feature-title">Admin Controls</h3>
                    <p class="feature-description">
                        Comprehensive admin panel with bulk operations, user management, and system configuration options.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h3 class="feature-title">Document Management</h3>
                    <p class="feature-description">
                        Secure document upload, storage, and management with approval workflows for sensitive information.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="cta-content">
            <h2 class="cta-title">Ready to Get Started?</h2>
            <p class="cta-description">
                Join thousands of communities already using Boothcare to streamline their management processes and improve resident satisfaction.
            </p>
            <a href="{{ route('register') }}" class="cta-button">
                <i class="fas fa-rocket"></i>
                Start Your Journey
            </a>
        </div>
    </section>
</div>

<script>
let currentSlideIndex = 0;
const slides = document.querySelectorAll('.slide');
const dots = document.querySelectorAll('.slider-dot');
const totalSlides = slides.length;

function showSlide(index) {
    // Hide all slides
    slides.forEach(slide => slide.classList.remove('active'));
    dots.forEach(dot => dot.classList.remove('active'));
    
    // Show current slide
    slides[index].classList.add('active');
    dots[index].classList.add('active');
}

function nextSlide() {
    currentSlideIndex = (currentSlideIndex + 1) % totalSlides;
    showSlide(currentSlideIndex);
}

function previousSlide() {
    currentSlideIndex = (currentSlideIndex - 1 + totalSlides) % totalSlides;
    showSlide(currentSlideIndex);
}

function currentSlide(index) {
    currentSlideIndex = index - 1;
    showSlide(currentSlideIndex);
}

// Auto-advance slides every 5 seconds
setInterval(nextSlide, 5000);

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    if (e.key === 'ArrowLeft') {
        previousSlide();
    } else if (e.key === 'ArrowRight') {
        nextSlide();
    }
});

// Touch/swipe support for mobile
let startX = 0;
let endX = 0;

document.querySelector('.slider-wrapper').addEventListener('touchstart', function(e) {
    startX = e.touches[0].clientX;
});

document.querySelector('.slider-wrapper').addEventListener('touchend', function(e) {
    endX = e.changedTouches[0].clientX;
    handleSwipe();
});

function handleSwipe() {
    const swipeThreshold = 50;
    const diff = startX - endX;
    
    if (Math.abs(diff) > swipeThreshold) {
        if (diff > 0) {
            nextSlide(); // Swipe left - next slide
        } else {
            previousSlide(); // Swipe right - previous slide
        }
    }
}
</script>
@endsection