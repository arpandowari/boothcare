<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Booth Care') }} - @yield('title', 'Public Portal')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #64748b;
            --success-color: #059669;
            --warning-color: #d97706;
            --danger-color: #dc2626;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f8fafc;
        }

        .navbar-brand {
            font-weight: 600;
            color: var(--primary-color) !important;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1e40af 100%);
            color: white;
            padding: 4rem 0;
        }

        .card {
            border: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #1d4ed8;
            border-color: #1d4ed8;
        }

        .rating {
            color: #fbbf24;
        }

        .footer {
            background-color: #1f2937;
            color: white;
            padding: 3rem 0 1rem;
        }

        .footer h4, .footer h5 {
            color: white !important;
        }

        .footer .text-muted {
            color: #d1d5db !important;
        }

        .footer .footer-link {
            color: #d1d5db !important;
            transition: color 0.3s ease;
        }

        .footer .footer-link:hover {
            color: white !important;
        }

        .footer .contact-info strong {
            color: white !important;
        }

        .footer .newsletter-section {
            background-color: rgba(255, 255, 255, 0.1) !important;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .footer .newsletter-section h5 {
            color: white !important;
        }

        .footer .newsletter-section .text-muted {
            color: #d1d5db !important;
        }

        .footer .btn-outline-primary,
        .footer .btn-outline-info,
        .footer .btn-outline-danger,
        .footer .btn-outline-success,
        .footer .btn-outline-secondary {
            border-width: 2px;
            font-weight: 600;
        }

        .footer .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .footer .stats-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1e40af 100%) !important;
        }

        .footer .newsletter-section {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%) !important;
            backdrop-filter: blur(10px);
        }

        .footer .contact-icon {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .footer a[href^="tel:"],
        .footer a[href^="mailto:"] {
            color: #60a5fa !important;
        }

        .footer a[href^="tel:"]:hover,
        .footer a[href^="mailto:"]:hover {
            color: white !important;
            text-decoration: underline !important;
        }

        .image-slider {
            height: 300px;
            overflow: hidden;
        }

        .image-slider img {
            height: 100%;
            object-fit: cover;
        }

        .problem-board {
            max-height: 400px;
            overflow-y: auto;
        }

        .problem-item {
            border-left: 4px solid var(--primary-color);
            background: white;
            margin-bottom: 1rem;
            padding: 1rem;
            border-radius: 0 8px 8px 0;
        }

        .member-card {
            cursor: pointer;
            transition: all 0.2s;
        }

        .member-card:hover {
            background-color: #f1f5f9;
        }

        @media (max-width: 768px) {
            .hero-section {
                padding: 2rem 0;
            }
            
            .image-slider {
                height: 200px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <!-- Brand Logo -->
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('images/logo_for_dashboard.png') }}" alt="{{ config('app.name', 'Booth Care') }}" 
                     style="height: 40px; width: auto; object-fit: contain;">
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Main Navigation -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active fw-bold' : '' }}" href="{{ route('home') }}">
                            <i class="bi bi-house me-1"></i>Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">
                            <i class="bi bi-star me-1"></i>Features
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">
                            <i class="bi bi-telephone me-1"></i>Contact
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#faq">
                            <i class="bi bi-question-circle me-1"></i>FAQ
                        </a>
                    </li>
                </ul>
                
                <!-- Right Side Navigation -->
                <ul class="navbar-nav">
                    @if(session('authenticated_member'))
                        <li class="nav-item">
                            <span class="navbar-text me-3">
                                <i class="bi bi-person-check text-success me-1"></i>
                                <span class="badge bg-success">Authenticated</span>
                            </span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-danger" href="{{ route('public.member.logout') }}">
                                <i class="bi bi-box-arrow-right me-1"></i>Logout
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-person-circle me-1"></i>Login
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container">
            <!-- Main Footer Content -->
            <div class="row">
                <!-- About Section -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="footer-brand mb-3">
                        <h4 class="fw-bold text-primary">
                            <i class="bi bi-house-door me-2"></i>
                            {{ config('app.name', 'Booth Care') }}
                        </h4>
                    </div>
                    <p class="text-muted mb-3">
                        Empowering communities through digital connectivity. Your trusted platform for booth management, 
                        member engagement, and community development.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="#" class="btn btn-outline-primary btn-sm rounded-circle" style="width: 40px; height: 40px;" title="Facebook">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="#" class="btn btn-outline-info btn-sm rounded-circle" style="width: 40px; height: 40px;" title="Twitter">
                            <i class="bi bi-twitter"></i>
                        </a>
                        <a href="#" class="btn btn-outline-danger btn-sm rounded-circle" style="width: 40px; height: 40px;" title="Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="#" class="btn btn-outline-success btn-sm rounded-circle" style="width: 40px; height: 40px;" title="WhatsApp">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                        <a href="#" class="btn btn-outline-secondary btn-sm rounded-circle" style="width: 40px; height: 40px;" title="LinkedIn">
                            <i class="bi bi-linkedin"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="fw-bold mb-3">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="{{ route('home') }}" class="text-muted text-decoration-none footer-link">
                                <i class="bi bi-house me-2"></i>Home
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('public.booth.index') }}" class="text-muted text-decoration-none footer-link">
                                <i class="bi bi-building me-2"></i>All Booths
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#features" class="text-muted text-decoration-none footer-link">
                                <i class="bi bi-star me-2"></i>Features
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#faq" class="text-muted text-decoration-none footer-link">
                                <i class="bi bi-question-circle me-2"></i>FAQ
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('login') }}" class="text-muted text-decoration-none footer-link">
                                <i class="bi bi-shield-lock me-2"></i>Admin Portal
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Services -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="fw-bold mb-3">Services</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="#" class="text-muted text-decoration-none footer-link">
                                <i class="bi bi-people me-2"></i>Member Management
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-muted text-decoration-none footer-link">
                                <i class="bi bi-star me-2"></i>Reviews & Ratings
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-muted text-decoration-none footer-link">
                                <i class="bi bi-flag me-2"></i>Issue Reporting
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-muted text-decoration-none footer-link">
                                <i class="bi bi-graph-up me-2"></i>Community Analytics
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-muted text-decoration-none footer-link">
                                <i class="bi bi-shield-check me-2"></i>Secure Access
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Contact Information -->
                <div class="col-lg-4 col-md-6 mb-4" id="contact">
                    <h5 class="fw-bold mb-3">Get In Touch</h5>
                    <div class="contact-info">
                        <div class="d-flex align-items-center mb-3">
                            <div class="contact-icon bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bi bi-geo-alt"></i>
                            </div>
                            <div>
                                <strong class="d-block">Address</strong>
                                <span class="text-muted">123 Community Street, City Name, State - 123456</span>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center mb-3">
                            <div class="contact-icon bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bi bi-telephone"></i>
                            </div>
                            <div>
                                <strong class="d-block">Phone</strong>
                                <a href="tel:+919876543210" class="text-muted text-decoration-none">+91 98765 43210</a>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center mb-3">
                            <div class="contact-icon bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bi bi-envelope"></i>
                            </div>
                            <div>
                                <strong class="d-block">Email</strong>
                                <a href="mailto:info@boothcare.com" class="text-muted text-decoration-none">info@boothcare.com</a>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center mb-3">
                            <div class="contact-icon bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bi bi-clock"></i>
                            </div>
                            <div>
                                <strong class="d-block">Office Hours</strong>
                                <span class="text-muted">Mon - Fri: 9:00 AM - 6:00 PM</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Newsletter Section -->
            <div class="row">
                <div class="col-12">
                    <div class="newsletter-section bg-light rounded p-4 mb-4">
                        <div class="row align-items-center">
                            <div class="col-lg-6 mb-3 mb-lg-0">
                                <h5 class="fw-bold mb-2">
                                    <i class="bi bi-envelope-heart text-primary me-2"></i>
                                    Stay Connected with Your Community
                                </h5>
                                <p class="text-muted mb-0">
                                    Subscribe to get updates about community activities, resolved issues, and important announcements.
                                </p>
                            </div>
                            <div class="col-lg-6">
                                <form class="d-flex gap-2">
                                    <input type="email" class="form-control" placeholder="Enter your email address" required>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-send me-1"></i>Subscribe
                                    </button>
                                </form>
                                <small class="text-muted">
                                    <i class="bi bi-shield-check me-1"></i>
                                    We respect your privacy. Unsubscribe anytime.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Statistics Section -->
            <div class="row">
                <div class="col-12">
                    <div class="stats-section bg-primary text-white rounded p-4 mb-4">
                        <div class="row text-center">
                            <div class="col-6 col-md-3 mb-3 mb-md-0">
                                <div class="stat-item">
                                    <h3 class="fw-bold mb-1">{{ \App\Models\Booth::count() }}+</h3>
                                    <small class="opacity-75">Active Booths</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-3 mb-3 mb-md-0">
                                <div class="stat-item">
                                    <h3 class="fw-bold mb-1">{{ \App\Models\FamilyMember::count() }}+</h3>
                                    <small class="opacity-75">Community Members</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-3 mb-3 mb-md-0">
                                <div class="stat-item">
                                    <h3 class="fw-bold mb-1">{{ \App\Models\Review::where('is_approved', true)->count() }}+</h3>
                                    <small class="opacity-75">Reviews Shared</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="stat-item">
                                    <h3 class="fw-bold mb-1">{{ \App\Models\PublicReport::where('status', 'resolved')->count() }}+</h3>
                                    <small class="opacity-75">Issues Resolved</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer Bottom -->
            <hr class="my-4">
            
            <div class="row align-items-center">
                <div class="col-lg-6 mb-3 mb-lg-0">
                    <p class="text-muted mb-0">
                        &copy; {{ date('Y') }} {{ config('app.name', 'Booth Care') }}. All rights reserved. 
                        Made with <i class="bi bi-heart-fill text-danger"></i> for stronger communities.
                    </p>
                </div>
                <div class="col-lg-6 text-lg-end">
                    <div class="footer-links">
                        <a href="#" class="text-muted text-decoration-none me-3">Privacy Policy</a>
                        <a href="#" class="text-muted text-decoration-none me-3">Terms of Service</a>
                        <a href="#" class="text-muted text-decoration-none me-3">Cookie Policy</a>
                        <a href="#" class="text-muted text-decoration-none">Sitemap</a>
                    </div>
                </div>
            </div>
            
            <!-- Back to Top Button -->
            <div class="text-center mt-4">
                <button class="btn btn-outline-primary btn-sm" onclick="window.scrollTo({top: 0, behavior: 'smooth'})" title="Back to Top">
                    <i class="bi bi-arrow-up"></i> Back to Top
                </button>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>

    @stack('scripts')
</body>
</html>