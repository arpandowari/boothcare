<!DOCTYPE html>
<html lang="{{ Auth::check() && Auth::user()->preferences && isset(Auth::user()->preferences['language']) ? Auth::user()->preferences['language'] : 'en' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#4F46E5">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Boothcare">
    
    <!-- Favicon and Icons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_for_login&favicon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo_for_login&favicon.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/logo_for_login&favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/logo_for_login&favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo_for_login&favicon.png') }}">
    
    <title>@yield('title', 'Boothcare')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Ensure FontAwesome icons load properly */
        .fas, .far, .fab, .fal, .fad, .fa {
            font-family: "Font Awesome 6 Free", "Font Awesome 6 Pro", "Font Awesome 6 Brands" !important;
            font-weight: 900;
            -webkit-font-smoothing: antialiased;
            display: inline-block;
            font-style: normal;
            font-variant: normal;
            text-rendering: auto;
            line-height: 1;
        }

        /* Modern App Design System */
        :root {
            --primary-color: #4F46E5;
            --primary-dark: #3730A3;
            --primary-light: #6366F1;
            --secondary-color: #6B7280;
            --success-color: #10B981;
            --warning-color: #F59E0B;
            --danger-color: #EF4444;
            --info-color: #3B82F6;
            --dark-color: #111827;
            --light-color: #F9FAFB;
            --sidebar-width: 260px;
            --header-height: 64px;
            --border-radius: 16px;
            --border-radius-sm: 8px;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-md: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 25px 50px -12px rgb(0 0 0 / 0.25);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-secondary: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --gradient-success: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --gradient-warning: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }

        /* Light Theme (Default) */
        .theme-light {
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --bg-tertiary: #f1f5f9;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --border-color: #e2e8f0;
            --card-bg: #ffffff;
            --sidebar-bg: #ffffff;
            --nav-bg: rgba(255, 255, 255, 0.95);
        }

        /* Dark Theme */
        .theme-dark {
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --bg-tertiary: #334155;
            --text-primary: #f1f5f9;
            --text-secondary: #cbd5e1;
            --text-muted: #94a3b8;
            --border-color: #334155;
            --card-bg: #1e293b;
            --sidebar-bg: #0f172a;
            --nav-bg: rgba(15, 23, 42, 0.95);
        }

        /* Auto Theme - follows system preference */
        .theme-auto {
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --bg-tertiary: #f1f5f9;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --border-color: #e2e8f0;
            --card-bg: #ffffff;
            --sidebar-bg: #ffffff;
            --nav-bg: rgba(255, 255, 255, 0.95);
        }

        @media (prefers-color-scheme: dark) {
            .theme-auto {
                --bg-primary: #0f172a;
                --bg-secondary: #1e293b;
                --bg-tertiary: #334155;
                --text-primary: #f1f5f9;
                --text-secondary: #cbd5e1;
                --text-muted: #94a3b8;
                --border-color: #334155;
                --card-bg: #1e293b;
                --sidebar-bg: #0f172a;
                --nav-bg: rgba(15, 23, 42, 0.95);
            }
        }

        /* Compact Mode */
        .compact-mode {
            --sidebar-width: 220px;
            --header-height: 56px;
            --border-radius: 12px;
            --border-radius-sm: 6px;
        }

        .compact-mode .card-body {
            padding: 16px !important;
        }

        .compact-mode .profile-card-body {
            padding: 16px !important;
        }

        .compact-mode .sidebar .nav-link {
            padding: 10px 16px !important;
            font-size: 0.85rem !important;
        }

        .compact-mode .mobile-nav {
            height: 56px !important;
        }

        /* Remove all scrollbars */
        * {
            box-sizing: border-box;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        *::-webkit-scrollbar {
            display: none;
        }

        html, body {
            margin: 0;
            padding: 0;
            height: 100vh;
            overflow-x: hidden;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--bg-secondary);
            color: var(--text-primary);
            line-height: 1.6;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Mobile Navigation */
        .mobile-nav {
            background: var(--nav-bg);
            backdrop-filter: blur(20px);
            box-shadow: var(--shadow);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1060;
            height: var(--header-height);
            border-bottom: 1px solid var(--border-color);
        }

        .mobile-nav .navbar-brand {
            color: var(--primary-color) !important;
            font-weight: 800;
            font-size: 1.4rem;
            display: flex;
            align-items: center;
        }

        .mobile-nav .navbar-brand i {
            margin-right: 8px;
            font-size: 1.6rem;
        }

        .mobile-nav .navbar-toggler {
            border: none;
            background: var(--gradient-primary);
            color: white;
            padding: 12px 16px;
            border-radius: 12px;
            transition: var(--transition);
            box-shadow: var(--shadow);
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        .mobile-nav .navbar-toggler:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .mobile-nav .navbar-toggler:focus {
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.3);
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            transform: translateX(-100%);
            transition: var(--transition);
            z-index: 1050;
            overflow-y: auto;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .sidebar.show {
            transform: translateX(0);
        }

        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
        }

        .sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .sidebar-header {
            padding: 24px 20px;
            border-bottom: 1px solid var(--border-color);
            background: var(--sidebar-bg);
            margin-bottom: 20px;
        }

        .sidebar-brand {
            color: #4285f4;
            font-weight: 800;
            font-size: 1.5rem;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .sidebar-brand:hover {
            color: #3367d6;
        }

        .sidebar-brand i {
            margin-right: 12px;
            font-size: 1.8rem;
        }

        .sidebar .nav-link {
            color: var(--text-secondary);
            padding: 14px 20px;
            margin: 4px 12px;
            border-radius: var(--border-radius-sm);
            transition: var(--transition);
            font-size: 0.9rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .sidebar .nav-link:hover {
            color: var(--primary-color);
            background: var(--bg-tertiary);
            transform: translateX(2px);
        }

        .sidebar .nav-link.active {
            color: var(--primary-color);
            background: var(--bg-tertiary);
            font-weight: 600;
            box-shadow: none;
        }

        .sidebar .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: #4285f4;
        }

        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
            margin-right: 12px;
            font-size: 1rem;
        }

        .nav-section {
            margin-top: 32px;
            padding: 0 20px;
        }

        .nav-section-title {
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: #666;
            margin-bottom: 16px;
            padding-left: 20px;
            position: relative;
        }

        .nav-section-title::after {
            display: none;
        }

        /* Main Content */
        .main-content {
            transition: var(--transition);
            min-height: 100vh;
            display: block !important;
            visibility: visible !important;
            padding-top: var(--header-height);
            background: transparent;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            transition: var(--transition);
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            overflow: hidden;
        }

        .card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-4px);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
        }

        .card-header {
            background: var(--bg-tertiary);
            border-bottom: 1px solid var(--border-color);
            border-radius: var(--border-radius) var(--border-radius) 0 0 !important;
            padding: 20px 24px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .card-body {
            padding: 24px;
        }

        /* Form Controls */
        .form-control, .form-select {
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            padding: 12px 16px;
            transition: var(--transition);
            font-size: 0.9rem;
            background: var(--card-bg);
            color: var(--text-primary);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            outline: none;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
            font-size: 0.875rem;
        }

        /* Buttons */
        .btn {
            border-radius: var(--border-radius);
            padding: 10px 20px;
            font-weight: 600;
            transition: var(--transition);
            border: none;
            font-size: 0.875rem;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            color: white;
        }

        .btn-success {
            background: var(--success-color);
            color: white;
        }

        .btn-success:hover {
            background: #047857;
            color: white;
        }

        .btn-warning {
            background: var(--warning-color);
            color: white;
        }

        .btn-warning:hover {
            background: #b45309;
            color: white;
        }

        .btn-danger {
            background: var(--danger-color);
            color: white;
        }

        .btn-danger:hover {
            background: #b91c1c;
            color: white;
        }

        .btn-secondary {
            background: var(--secondary-color);
            color: white;
        }

        .btn-secondary:hover {
            background: #475569;
            color: white;
        }

        .btn-outline-primary {
            background: transparent;
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            color: white;
        }

        /* Tables */
        .table {
            background: var(--card-bg);
            color: var(--text-primary);
        }

        .table th {
            background: var(--bg-tertiary);
            border-bottom: 1px solid var(--border-color);
            font-weight: 600;
            color: var(--text-primary);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            padding: 16px 12px;
        }

        .table td {
            border-bottom: 1px solid var(--border-color);
            padding: 16px 12px;
            vertical-align: middle;
            color: var(--text-primary);
        }

        .table-hover tbody tr:hover {
            background: var(--bg-tertiary);
        }

        /* Alerts */
        .alert {
            border: none;
            border-radius: var(--border-radius);
            padding: 16px 20px;
            margin-bottom: 20px;
            border-left: 4px solid;
        }

        .alert-success {
            background: #f0fdf4;
            color: #166534;
            border-left-color: var(--success-color);
        }

        .alert-danger {
            background: #fef2f2;
            color: #991b1b;
            border-left-color: var(--danger-color);
        }

        .alert-warning {
            background: #fffbeb;
            color: #92400e;
            border-left-color: var(--warning-color);
        }

        .alert-info {
            background: #f0f9ff;
            color: #075985;
            border-left-color: var(--info-color);
        }

        /* Badges */
        .badge {
            border-radius: 6px;
            padding: 4px 8px;
            font-weight: 500;
            font-size: 0.75rem;
        }

        /* Breadcrumb */
        .breadcrumb {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: var(--border-radius);
            padding: 12px 16px;
            margin-bottom: 24px;
        }

        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
        }

        .breadcrumb-item a:hover {
            color: var(--primary-dark);
        }

        /* Modern Stats Cards */
        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: var(--border-radius);
            padding: 24px;
            transition: var(--transition);
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
            background: var(--gradient-primary);
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
        }

        .stat-card h3 {
            font-size: 2.5rem;
            font-weight: 800;
            margin: 0;
            color: white;
        }

        .stat-card p {
            color: rgba(255, 255, 255, 0.8);
            margin: 8px 0 0 0;
            font-weight: 500;
        }

        .stat-card i {
            font-size: 3rem;
            color: rgba(255, 255, 255, 0.3);
            position: absolute;
            top: 20px;
            right: 20px;
        }

        /* Chart Container */
        .chart-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: var(--border-radius);
            padding: 24px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Responsive Design */
        @media (min-width: 1024px) {
            .sidebar {
                transform: translateX(0);
            }

            .main-content {
                margin-left: var(--sidebar-width);
                padding-top: 32px;
            }

            .mobile-nav {
                display: none;
            }

            .sidebar-overlay {
                display: none;
            }
        }

        @media (max-width: 1023px) {
            .main-content {
                padding: var(--header-height) 16px 16px;
                border-radius: 0;
                margin-top: 0;
            }

            .card-body {
                padding: 20px;
            }

            .form-control, .form-select {
                padding: 12px 16px;
                border-radius: var(--border-radius-sm);
                border: 2px solid rgba(255, 255, 255, 0.2);
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(10px);
                color: var(--dark-color);
            }

            .btn {
                padding: 12px 20px;
                font-size: 0.9rem;
                border-radius: var(--border-radius-sm);
                font-weight: 600;
            }

            .stat-card {
                margin-bottom: 16px;
            }

            .stat-card h3 {
                font-size: 2rem;
            }

            /* Mobile App-like Features */
            .mobile-bottom-nav {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(20px);
                border-top: 1px solid rgba(255, 255, 255, 0.2);
                padding: 12px 0;
                z-index: 1000;
                display: flex;
                justify-content: space-around;
                align-items: center;
            }

            .mobile-nav-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                text-decoration: none;
                color: var(--secondary-color);
                transition: var(--transition);
                padding: 8px 12px;
                border-radius: var(--border-radius-sm);
            }

            .mobile-nav-item.active {
                color: var(--primary-color);
                background: rgba(79, 70, 229, 0.1);
            }

            .mobile-nav-item i {
                font-size: 1.2rem;
                margin-bottom: 4px;
            }

            .mobile-nav-item span {
                font-size: 0.7rem;
                font-weight: 500;
            }

            /* Pull to refresh indicator */
            .pull-to-refresh {
                position: absolute;
                top: -60px;
                left: 50%;
                transform: translateX(-50%);
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(10px);
                padding: 12px 20px;
                border-radius: 20px;
                box-shadow: var(--shadow);
                transition: var(--transition);
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: var(--header-height) 8px 80px 8px;
            }

            .card-body {
                padding: 16px;
            }

            .table-responsive {
                font-size: 0.875rem;
            }

            .btn-group .btn {
                padding: 8px 12px;
                font-size: 0.8rem;
            }

            .dashboard-header {
                padding: 20px;
                margin-bottom: 20px;
            }

            .dashboard-title {
                font-size: 1.5rem;
            }

            .chart-card, .mini-chart-card {
                margin-bottom: 16px;
            }

            .mini-chart-card {
                height: auto;
                min-height: 160px;
            }

            /* App-like touch interactions */
            .card, .btn, .nav-link {
                -webkit-tap-highlight-color: transparent;
                user-select: none;
            }

            .card:active, .btn:active {
                transform: scale(0.98);
            }

            /* iOS-style scrolling */
            .main-content {
                -webkit-overflow-scrolling: touch;
            }

            /* Status bar spacing for mobile */
            @supports (padding-top: env(safe-area-inset-top)) {
                .mobile-nav {
                    padding-top: env(safe-area-inset-top);
                    height: calc(var(--header-height) + env(safe-area-inset-top));
                }

                .main-content {
                    padding-top: calc(var(--header-height) + env(safe-area-inset-top) + 12px);
                }

                .mobile-bottom-nav {
                    padding-bottom: env(safe-area-inset-bottom);
                }
            }
        }

        /* Animations */
        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Loading States */
        .btn.loading {
            position: relative;
            color: transparent !important;
            pointer-events: none;
        }

        .btn.loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-left: -8px;
            margin-top: -8px;
            border: 2px solid transparent;
            border-top-color: currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Footer Styles */
        .app-footer {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            padding: 20px 0;
            margin-top: 40px;
            font-size: 0.9rem;
            color: #666;
            position: relative;
            z-index: 1000;
            width: 100%;
            clear: both;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        }

        .footer-link {
            color: #4285f4;
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
        }

        .footer-link:hover {
            color: #3367d6;
            text-decoration: underline;
        }

        /* Ensure footer is always visible */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .main-content {
            flex: 1 !important;
            display: block !important;
            visibility: visible !important;
            min-height: calc(100vh - 200px) !important;
        }

        @media (max-width: 768px) {
            .app-footer {
                text-align: center;
                padding: 12px 0 80px 0; /* Extra padding for mobile bottom nav */
            }

            .app-footer .col-md-6 {
                text-align: center !important;
                margin-bottom: 4px;
            }

            .app-footer .row {
                flex-direction: column;
            }
        }

        /* Additional Modern Styles */
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .stat-card.bg-danger {
            background: linear-gradient(135deg, #ea4335 0%, #d93025 100%);
            color: white;
        }

        .stat-card.bg-success {
            background: linear-gradient(135deg, #34a853 0%, #2d8f47 100%);
            color: white;
        }

        .stat-card.bg-info {
            background: linear-gradient(135deg, #4285f4 0%, #3367d6 100%);
            color: white;
        }

        .stat-card.bg-warning {
            background: linear-gradient(135deg, #fbbc04 0%, #f9ab00 100%);
            color: #1a1a1a;
        }

        .stat-content {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.2);
            font-size: 24px;
        }

        .stat-info h3 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
            line-height: 1;
        }

        .stat-info p {
            margin: 4px 0 0 0;
            font-size: 0.9rem;
            opacity: 0.9;
        }

        /* Enhanced Table Styles */
        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
        }

        /* Enhanced Pagination */
        .pagination {
            border-radius: 12px;
            overflow: hidden;
        }

        .page-link {
            border: none;
            padding: 12px 16px;
            color: #666;
            transition: all 0.2s ease;
        }

        .page-link:hover {
            background: #4285f4;
            color: white;
            transform: translateY(-1px);
        }

        .page-item.active .page-link {
            background: #4285f4;
            border-color: #4285f4;
        }

        /* Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(0,0,0,0.1);
            padding: 8px 0;
            z-index: 1000;
            display: none;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.1);
        }

        .bottom-nav-item {
            flex: 1;
            text-align: center;
            padding: 8px 4px;
            text-decoration: none;
            color: #666;
            transition: all 0.2s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .bottom-nav-item.active {
            color: #4285f4;
        }

        .bottom-nav-item:hover {
            color: #4285f4;
            text-decoration: none;
        }

        .bottom-nav-item i {
            font-size: 1.2rem;
            margin-bottom: 4px;
        }

        .bottom-nav-item span {
            font-size: 0.7rem;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .bottom-nav {
                display: flex;
            }

            .app-footer {
                display: none;
            }
        }

        /* Modal fixes - allow modals to work properly */
        .modal-backdrop {
            z-index: 1040;
        }

        .modal {
            z-index: 1050;
        }

        /* Modern Toast Notification System */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1055;
            max-width: 400px;
            width: 100%;
        }

        .toast {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            margin-bottom: 12px;
            padding: 0;
            border: none;
            overflow: hidden;
            transform: translateX(400px);
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
        }

        .toast.show {
            transform: translateX(0);
            opacity: 1;
        }

        .toast.hide {
            transform: translateX(400px);
            opacity: 0;
        }

        .toast::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--toast-color, #10b981);
        }

        .toast.success::before { background: #10b981; }
        .toast.error::before { background: #ef4444; }
        .toast.warning::before { background: #f59e0b; }
        .toast.info::before { background: #3b82f6; }

        .toast-header {
            background: transparent;
            border: none;
            padding: 16px 20px 8px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .toast-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: white;
            flex-shrink: 0;
        }

        .toast.success .toast-icon { background: #10b981; }
        .toast.error .toast-icon { background: #ef4444; }
        .toast.warning .toast-icon { background: #f59e0b; }
        .toast.info .toast-icon { background: #3b82f6; }

        .toast-title {
            font-weight: 600;
            color: #1f2937;
            margin: 0;
            font-size: 0.95rem;
            flex: 1;
        }

        .toast-close {
            background: none;
            border: none;
            color: #9ca3af;
            font-size: 18px;
            cursor: pointer;
            padding: 0;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .toast-close:hover {
            background: #f3f4f6;
            color: #6b7280;
        }

        .toast-body {
            padding: 0 20px 16px 24px;
            color: #6b7280;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background: var(--toast-color, #10b981);
            border-radius: 0 0 12px 12px;
            transition: width linear;
            opacity: 0.7;
        }

        .toast.success .toast-progress { background: #10b981; }
        .toast.error .toast-progress { background: #ef4444; }
        .toast.warning .toast-progress { background: #f59e0b; }
        .toast.info .toast-progress { background: #3b82f6; }

        /* Mobile responsive for toasts */
        @media (max-width: 768px) {
            .toast-container {
                top: 10px;
                right: 10px;
                left: 10px;
                max-width: none;
            }

            .toast {
                transform: translateY(-100px);
            }

            .toast.show {
                transform: translateY(0);
            }

            .toast.hide {
                transform: translateY(-100px);
            }
        }
        }

        /* Modern Toast Notification System */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1055;
            max-width: 400px;
            width: 100%;
        }

        .toast {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            margin-bottom: 12px;
            padding: 0;
            border: none;
            overflow: hidden;
            transform: translateX(400px);
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
        }

        .toast.show {
            transform: translateX(0);
            opacity: 1;
        }

        .toast.hide {
            transform: translateX(400px);
            opacity: 0;
        }

        .toast::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--toast-color, #10b981);
        }

        .toast.success::before { background: #10b981; }
        .toast.error::before { background: #ef4444; }
        .toast.warning::before { background: #f59e0b; }
        .toast.info::before { background: #3b82f6; }

        .toast-header {
            background: transparent;
            border: none;
            padding: 16px 20px 8px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .toast-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: white;
            flex-shrink: 0;
        }

        .toast.success .toast-icon { background: #10b981; }
        .toast.error .toast-icon { background: #ef4444; }
        .toast.warning .toast-icon { background: #f59e0b; }
        .toast.info .toast-icon { background: #3b82f6; }

        .toast-title {
            font-weight: 600;
            color: #1f2937;
            margin: 0;
            font-size: 0.95rem;
            flex: 1;
        }

        .toast-close {
            background: none;
            border: none;
            color: #9ca3af;
            font-size: 18px;
            cursor: pointer;
            padding: 0;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .toast-close:hover {
            background: #f3f4f6;
            color: #6b7280;
        }

        .toast-body {
            padding: 0 20px 16px 24px;
            color: #6b7280;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background: var(--toast-color, #10b981);
            border-radius: 0 0 12px 12px;
            transition: width linear;
            opacity: 0.7;
        }

        .toast.success .toast-progress { background: #10b981; }
        .toast.error .toast-progress { background: #ef4444; }
        .toast.warning .toast-progress { background: #f59e0b; }
        .toast.info .toast-progress { background: #3b82f6; }

        /* Mobile responsive for toasts */
        @media (max-width: 768px) {
            .toast-container {
                top: 10px;
                right: 10px;
                left: 10px;
                max-width: none;
            }

            .toast {
                transform: translateY(-100px);
            }

            .toast.show {
                transform: translateY(0);
            }

            .toast.hide {
                transform: translateY(-100px);
            }
        }
        }

        /* Modern Toast Notification System */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1055;
            max-width: 400px;
            width: 100%;
        }

        .toast {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            margin-bottom: 12px;
            padding: 0;
            border: none;
            overflow: hidden;
            transform: translateX(400px);
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
        }

        .toast.show {
            transform: translateX(0);
            opacity: 1;
        }

        .toast.hide {
            transform: translateX(400px);
            opacity: 0;
        }

        .toast::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--toast-color, #10b981);
        }

        .toast.success::before { background: #10b981; }
        .toast.error::before { background: #ef4444; }
        .toast.warning::before { background: #f59e0b; }
        .toast.info::before { background: #3b82f6; }

        .toast-header {
            background: transparent;
            border: none;
            padding: 16px 20px 8px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .toast-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: white;
            flex-shrink: 0;
        }

        .toast.success .toast-icon { background: #10b981; }
        .toast.error .toast-icon { background: #ef4444; }
        .toast.warning .toast-icon { background: #f59e0b; }
        .toast.info .toast-icon { background: #3b82f6; }

        .toast-title {
            font-weight: 600;
            color: #1f2937;
            margin: 0;
            font-size: 0.95rem;
            flex: 1;
        }

        .toast-close {
            background: none;
            border: none;
            color: #9ca3af;
            font-size: 18px;
            cursor: pointer;
            padding: 0;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .toast-close:hover {
            background: #f3f4f6;
            color: #6b7280;
        }

        .toast-body {
            padding: 0 20px 16px 24px;
            color: #6b7280;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background: var(--toast-color, #10b981);
            border-radius: 0 0 12px 12px;
            transition: width linear;
            opacity: 0.7;
        }

        .toast.success .toast-progress { background: #10b981; }
        .toast.error .toast-progress { background: #ef4444; }
        .toast.warning .toast-progress { background: #f59e0b; }
        .toast.info .toast-progress { background: #3b82f6; }

        /* Mobile responsive */
        @media (max-width: 768px) {
            .toast-container {
                top: 10px;
                right: 10px;
                left: 10px;
                max-width: none;
            }

            .toast {
                transform: translateY(-100px);
            }

            .toast.show {
                transform: translateY(0);
            }

            .toast.hide {
                transform: translateY(-100px);
            }
        }
        }

        /* Allow modal-open class to work properly */
        body.modal-open {
            overflow: hidden;
        }

        /* Ensure all buttons are always clickable */
        .btn,
        button,
        input[type="submit"],
        input[type="button"] {
            z-index: 10 !important;
            position: relative !important;
            pointer-events: auto !important;
            cursor: pointer !important;
        }

        /* Print Styles */
        @media print {
            .sidebar, .mobile-nav, .btn, .pagination, .alert, .app-footer, .bottom-nav {
                display: none !important;
            }

            .main-content {
                margin-left: 0 !important;
                padding: 0 !important;
            }

            .card, .chart-card {
                border: 1px solid #ddd !important;
                box-shadow: none !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="@if(Auth::check() && Auth::user()->preferences){{ isset(Auth::user()->preferences['theme']) ? 'theme-' . Auth::user()->preferences['theme'] : 'theme-light' }}@else theme-light @endif @if(Auth::check() && Auth::user()->preferences && isset(Auth::user()->preferences['compactMode']) && Auth::user()->preferences['compactMode']) compact-mode @endif">
    <!-- Mobile Navigation -->
    <nav class="navbar navbar-expand-lg mobile-nav d-lg-none">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <img src="{{ asset('images/logo_for_dashboard.png') }}" alt="Boothcare" style="width: 200px; height: 40px; object-fit: contain;">
            </a>
            <button class="navbar-toggler" type="button" onclick="toggleSidebar()" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" onclick="closeSidebar()"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header d-none d-lg-block">
            <a href="{{ route('dashboard') }}" class="sidebar-brand">
                <img src="{{ asset('images/logo_for_dashboard.png') }}" alt="Boothcare" style="width: 320px; height: 50px; object-fit: contain;">
            </a>
        </div>

        <!-- Mobile Header -->
        <div class="sidebar-header d-lg-none">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('dashboard') }}" class="sidebar-brand">
                    <img src="{{ asset('images/logo_for_dashboard.png') }}" alt="Boothcare" style="width: 260px; height: 50px; object-fit: contain;">
                </a>
                <button class="btn btn-sm text-white" onclick="closeSidebar()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <nav class="nav flex-column">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            
            @auth
                @if(Auth::user()->isAdminOrSubAdmin())
                    <!-- Admin/Sub-Admin Navigation -->
                    <div class="nav-section">
                        <div class="nav-section-title">Management</div>
                        
                        @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('areas.view'))
                        <a class="nav-link {{ request()->routeIs('areas.*') ? 'active' : '' }}" href="{{ route('areas.index') }}">
                            <i class="fas fa-map-marked-alt"></i>
                            <span>Areas</span>
                        </a>
                        @endif
                        
                        @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('booths.view'))
                        <a class="nav-link {{ request()->routeIs('booths.*') ? 'active' : '' }}" href="{{ route('booths.index') }}">
                            <i class="fas fa-building"></i>
                            <span>Booths</span>
                        </a>
                        @endif
                        
                        @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('houses.view'))
                        <a class="nav-link {{ request()->routeIs('houses.*') ? 'active' : '' }}" href="{{ route('houses.index') }}">
                            <i class="fas fa-home"></i>
                            <span>Houses</span>
                        </a>
                        @endif
                        
                        @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('members.view'))
                        <a class="nav-link {{ request()->routeIs('members.*') ? 'active' : '' }}" href="{{ route('members.index') }}">
                            <i class="fas fa-users"></i>
                            <span>Members</span>
                        </a>
                        @endif
                        
                        <a class="nav-link {{ request()->routeIs('problems.*') ? 'active' : '' }}" href="{{ route('problems.index') }}">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>{{ Auth::user()->isAdminOrSubAdmin() ? 'All Problems' : 'My Problems' }}</span>
                        </a>
                        

                    </div>
                    
                    <!-- Admin Tools -->
                    <div class="nav-section">
                        <div class="nav-section-title">Tools</div>
                        
                        @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('users.view'))
                        <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                            <i class="fas fa-user-cog"></i>
                            <span>Users</span>
                        </a>
                        @endif
                        
                        @if(Auth::user()->isAdmin())
                        <a class="nav-link {{ request()->routeIs('admin.sub-admins.*') ? 'active' : '' }}" href="{{ route('admin.sub-admins.index') }}">
                            <i class="fas fa-user-shield"></i>
                            <span>Sub-Admin Management</span>
                        </a>
                        @endif
                        
                        @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('reports.view'))
                        <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                            <i class="fas fa-chart-bar"></i>
                            <span>Reports</span>
                        </a>
                        @endif
                        
                        @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('update_requests.view'))
                        <a class="nav-link {{ request()->routeIs('admin.update-requests.*') ? 'active' : '' }}" href="{{ route('admin.update-requests.index') }}">
                            <i class="fas fa-edit"></i>
                            <span>Update Requests</span>
                        </a>
                        @endif
                        
                        @if(Auth::user()->isAdmin())
                        <a class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}" href="{{ route('settings.index') }}">
                            <i class="fas fa-cog"></i>
                            <span>Settings</span>
                        </a>
                        @endif
                    </div>
                    
                    <!-- Webpage Management Section for Admin -->
                    @if(Auth::user()->isAdmin())
                    <div class="nav-section">
                        <div class="nav-section-title">Webpage</div>
                        
                        @if(Auth::user()->isAdminOrSubAdmin())
                        <a class="nav-link {{ request()->routeIs('admin.notices.*') ? 'active' : '' }}" href="{{ route('admin.notices.index') }}">
                            <i class="fas fa-bullhorn"></i>
                            <span>Notice Management</span>
                        </a>
                        @endif
                        
                        <a class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}" href="{{ route('admin.reviews.index') }}">
                            <i class="fas fa-star"></i>
                            <span>Reviews</span>
                        </a>
                        
                        <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">
                            <i class="fas fa-flag"></i>
                            <span>Public Reports</span>
                        </a>
                        
                        <a class="nav-link {{ request()->routeIs('admin.booth-images.*') ? 'active' : '' }}" href="{{ route('admin.booth-images.index') }}">
                            <i class="fas fa-images"></i>
                            <span>Manage Booth Images</span>
                        </a>
                    </div>
                    @endif
                    
                    <!-- Profile Section for Admin/Sub-Admin -->
                    <div class="nav-section">
                        <div class="nav-section-title">My Account</div>
                        
                        <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.show') }}">
                            <i class="fas fa-user"></i>
                            <span>Profile</span>
                        </a>
                    </div>
                @else
                    <!-- User Navigation -->
                    <div class="nav-section">
                        <div class="nav-section-title">My Account</div>
                        
                        <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.show') }}">
                            <i class="fas fa-user"></i>
                            <span>Profile</span>
                        </a>
                        
                        <a class="nav-link {{ request()->routeIs('problems.*') ? 'active' : '' }}" href="{{ route('problems.index') }}">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>My Problems</span>
                        </a>
                        
                        <a class="nav-link {{ request()->routeIs('house.*') ? 'active' : '' }}" href="{{ route('house.members') }}">
                            <i class="fas fa-home"></i>
                            <span>House Members</span>
                        </a>
                        
                        <a class="nav-link {{ request()->routeIs('documents.*') ? 'active' : '' }}" href="{{ route('profile.documents') }}">
                            <i class="fas fa-id-card"></i>
                            <span>Documents</span>
                        </a>
                        
                        @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('update_requests.view'))
                        <a class="nav-link {{ request()->routeIs('admin.update-requests.*') ? 'active' : '' }}" href="{{ route('admin.update-requests.index') }}">
                            <i class="fas fa-edit"></i>
                            <span>Update Requests</span>
                        </a>
                        @endif
                    </div>
                    
                    <!-- User Actions -->
                    <div class="nav-section">
                        <div class="nav-section-title">Actions</div>
                        @if(Auth::user()->isAdminOrSubAdmin())
                            <a class="nav-link" href="{{ route('problems.create') }}">
                                <i class="fas fa-plus"></i>
                                <span>Create Problem</span>
                            </a>
                        @else
                            <a class="nav-link" href="{{ route('problem-requests.create') }}">
                                <i class="fas fa-paper-plane"></i>
                                <span>Report Problem</span>
                            </a>
                        @endif
                        <a class="nav-link" href="{{ route('profile.edit') }}">
                            <i class="fas fa-edit"></i>
                            <span>Edit Profile</span>
                        </a>
                        <a class="nav-link" href="{{ route('user.update-requests.create') }}">
                            <i class="fas fa-paper-plane"></i>
                            <span>Profile Update Request</span>
                        </a>
                    </div>
                @endif
            @else
                <!-- Guest Navigation -->
                <div class="nav-section">
                    <div class="nav-section-title">Get Started</div>
                    <a class="nav-link" href="{{ route('login') }}">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Login</span>
                    </a>
                    <a class="nav-link" href="{{ route('register') }}">
                        <i class="fas fa-user-plus"></i>
                        <span>Register</span>
                    </a>
                </div>
            @endauth

            <!-- Logout -->
            @auth
            <div class="nav-section" style="margin-top: auto; padding-top: 20px; border-top: 1px solid #e2e8f0;">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link btn btn-link text-start w-100 border-0" style="color: var(--secondary-color); padding: 12px 20px; margin: 2px 12px; border-radius: var(--border-radius); background: none;">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
            @endauth
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid" style="display: block !important; visibility: visible !important; background: #f0f0f0 !important; min-height: 500px !important; padding: 20px !important;">
            <!-- Page Header -->
            @if(View::hasSection('page-title'))
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="h3 mb-0 fade-in">@yield('page-title')</h1>
                        @auth
                        <div class="d-flex align-items-center">
                            <span class="text-muted me-3 d-none d-md-inline">Welcome, {{ Auth::user()->name }}</span>
                            <div class="dropdown">
                                <button class="btn btn-outline-primary dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                                    @if(Auth::user()->profile_photo)
                                        <img src="{{ Auth::user()->profile_photo_url }}" 
                                             class="rounded-circle me-2" width="24" height="24" alt="Profile">
                                    @else
                                        <i class="fas fa-user me-1"></i>
                                    @endif
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('profile.show') }}">
                                        <i class="fas fa-user me-2"></i>Profile
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        @endauth
                    </div>
                </div>
            </div>
            @endif

            <!-- Modern Toast Notifications Container -->
            <div id="toast-container" class="toast-container"></div>

            <!-- Main Content Area -->
            <div class="fade-in" style="display: block !important; visibility: visible !important; opacity: 1 !important;">
                @yield('content')
            </div>

            <!-- Footer -->
            <footer class="app-footer">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <p class="mb-0">&copy; {{ date('Y') }} Boothcare. All rights reserved.</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <p class="mb-0">Made by <a href="https://arpandowari.github.io/ArpanDowari/" target="_blank" class="footer-link">@Arpan</a></p>
                        </div>
                    </div>
                </div>
            </footer>

            <!-- Mobile Bottom Navigation -->
            <nav class="bottom-nav">
                <a href="{{ route('dashboard') }}" class="bottom-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('members.index') }}" class="bottom-nav-item {{ request()->routeIs('members.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Members</span>
                </a>
                <a href="{{ route('problems.index') }}" class="bottom-nav-item {{ request()->routeIs('problems.*') ? 'active' : '' }}">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>{{ Auth::user()->isAdminOrSubAdmin() ? 'All Problems' : 'My Problems' }}</span>
                </a>
                <a href="{{ route('profile.show') }}" class="bottom-nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <i class="fas fa-user"></i>
                    <span>Profile</span>
                </a>
            </nav>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Ensure footer is always visible
        document.addEventListener('DOMContentLoaded', function() {
            const footer = document.querySelector('.app-footer');
            if (footer) {
                footer.style.display = 'block';
                footer.style.visibility = 'visible';
            }
        });
    </script>
    
    <script>
        // Sidebar Toggle Functions
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
            
            if (sidebar.classList.contains('show')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }

        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
            document.body.style.overflow = '';
        }

        // Close sidebar when clicking on nav links (mobile)
        document.querySelectorAll('.sidebar .nav-link').forEach(link => {
            link.addEventListener('click', (e) => {
                // Add haptic feedback on mobile
                if ('vibrate' in navigator) {
                    navigator.vibrate(10);
                }
                
                if (window.innerWidth < 1024) {
                    setTimeout(() => closeSidebar(), 150);
                }
            });
        });

        // Add haptic feedback to buttons
        document.querySelectorAll('.btn, .mobile-nav-item').forEach(element => {
            element.addEventListener('click', () => {
                if ('vibrate' in navigator) {
                    navigator.vibrate(10);
                }
            });
        });

        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                closeSidebar();
                document.body.style.overflow = '';
            }
        });

        // Close sidebar on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeSidebar();
            }
        });

        // Add loading states to form submit buttons
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function() {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn && !submitBtn.classList.contains('no-loading')) {
                        submitBtn.classList.add('loading');
                        submitBtn.disabled = true;
                        
                        setTimeout(() => {
                            submitBtn.classList.remove('loading');
                            submitBtn.disabled = false;
                        }, 10000);
                    }
                });
            });

            // Auto-dismiss alerts after 5 seconds
            document.querySelectorAll('.alert').forEach(alert => {
                if (!alert.classList.contains('alert-permanent')) {
                    setTimeout(() => {
                        const closeBtn = alert.querySelector('.btn-close');
                        if (closeBtn) {
                            closeBtn.click();
                        }
                    }, 5000);
                }
            });
        });

        // Touch gestures for mobile sidebar
        let touchStartX = 0;
        let touchEndX = 0;

        document.addEventListener('touchstart', e => {
            touchStartX = e.changedTouches[0].screenX;
        });

        document.addEventListener('touchend', e => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });

        function handleSwipe() {
            const swipeThreshold = 50;
            const sidebar = document.getElementById('sidebar');
            
            if (touchEndX < touchStartX - swipeThreshold && sidebar.classList.contains('show')) {
                closeSidebar();
            }
            
            if (touchEndX > touchStartX + swipeThreshold && !sidebar.classList.contains('show') && touchStartX < 20) {
                toggleSidebar();
            }
        }
    </script>

    <script>
        // Theme and Preferences Management
        document.addEventListener('DOMContentLoaded', function() {
            // Apply theme on page load
            applyCurrentTheme();
            
            // Listen for system theme changes
            if (window.matchMedia) {
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
                    if (document.body.classList.contains('theme-auto')) {
                        applyAutoTheme();
                    }
                });
            }
        });

        function applyCurrentTheme() {
            const body = document.body;
            if (body.classList.contains('theme-auto')) {
                applyAutoTheme();
            }
        }

        function applyAutoTheme() {
            const body = document.body;
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                body.classList.add('auto-dark');
            } else {
                body.classList.remove('auto-dark');
            }
        }

        // Global theme switching function
        window.switchTheme = function(theme) {
            const body = document.body;
            body.classList.remove('theme-light', 'theme-dark', 'theme-auto', 'auto-dark');
            body.classList.add('theme-' + theme);
            
            if (theme === 'auto') {
                applyAutoTheme();
            }
            
            // Update theme-color meta tag
            const themeColorMeta = document.querySelector('meta[name="theme-color"]');
            if (themeColorMeta) {
                if (theme === 'dark' || (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    themeColorMeta.setAttribute('content', '#0f172a');
                } else {
                    themeColorMeta.setAttribute('content', '#4F46E5');
                }
            }
        };

        // Global compact mode toggle
        window.toggleCompactMode = function(enabled) {
            const body = document.body;
            if (enabled) {
                body.classList.add('compact-mode');
            } else {
                body.classList.remove('compact-mode');
            }
        };

        // Language switching function
        window.switchLanguage = function(language) {
            document.documentElement.lang = language;
            // You can add more language-specific changes here
        };
    </script>
    
    <!-- Modal Support Enabled -->
    <script>
        // Allow modals to work properly - no interference
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Modals enabled - Bootstrap version:', bootstrap.Modal.VERSION || 'Unknown');
        });

        // Modern Toast Notification System
        function showToast(type, title, message, duration = 5000) {
            const toastContainer = document.getElementById('toast-container');
            if (!toastContainer) return;
            
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            
            toast.innerHTML = `
                <div class="toast-header">
                    <div class="toast-icon">
                        <i class="fas ${getToastIcon(type)}"></i>
                    </div>
                    <div class="toast-title">${title}</div>
                    <button class="toast-close" onclick="hideToast(this.closest('.toast'))">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="toast-body">${message}</div>
                <div class="toast-progress"></div>
            `;
            
            toastContainer.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.add('show');
            }, 100);
            
            const progressBar = toast.querySelector('.toast-progress');
            progressBar.style.width = '100%';
            progressBar.style.transitionDuration = duration + 'ms';
            
            setTimeout(() => {
                progressBar.style.width = '0%';
            }, 200);
            
            setTimeout(() => {
                hideToast(toast);
            }, duration);
        }

        function hideToast(toast) {
            toast.classList.remove('show');
            toast.classList.add('hide');
            
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 400);
        }

        function getToastIcon(type) {
            const icons = {
                'success': 'fa-check',
                'error': 'fa-times',
                'warning': 'fa-exclamation-triangle',
                'info': 'fa-info'
            };
            return icons[type] || 'fa-info';
        }

        // Show toast notifications from Laravel session messages
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                showToast('success', 'Success!', '{{ session('success') }}', 6000);
            @endif
            
            @if(session('error'))
                showToast('error', 'Error!', '{{ session('error') }}', 6000);
            @endif
            
            @if(session('warning'))
                showToast('warning', 'Warning!', '{{ session('warning') }}', 6000);
            @endif
            
            @if(session('info'))
                showToast('info', 'Info', '{{ session('info') }}', 6000);
            @endif
        });
    </script>
    
    @stack('scripts')
</body>
</html>