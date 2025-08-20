<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Boothcare</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_for_login&favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo_for_login&favicon.png') }}">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 1;
        }

        .login-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
            overflow: hidden;
            animation: slideUp 0.8s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            text-align: center;
            padding: 40px 30px 30px;
            background: #ffffff;
            border-bottom: 1px solid #f1f5f9;
        }

        .logo-container {
            margin-bottom: 20px;
        }

        .logo-container img {
            width: 140px;
            height: 70px;
            object-fit: contain;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.1));
        }

        .login-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .login-subtitle {
            font-size: 1rem;
            color: #718096;
            font-weight: 500;
        }

        .login-body {
            padding: 30px;
        }

        .login-tabs {
            display: flex;
            background: #f7fafc;
            border-radius: 12px;
            padding: 4px;
            margin-bottom: 30px;
            position: relative;
        }

        .tab-indicator {
            position: absolute;
            top: 4px;
            left: 4px;
            width: calc(50% - 4px);
            height: calc(100% - 8px);
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        }

        .tab-indicator.admin {
            transform: translateX(100%);
        }

        .login-tab {
            flex: 1;
            padding: 12px 16px;
            text-align: center;
            border: none;
            background: transparent;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            position: relative;
            z-index: 2;
        }

        .login-tab.active {
            color: white;
        }

        .login-tab:not(.active) {
            color: #718096;
        }

        .form-section {
            display: none;
        }

        .form-section.active {
            display: block;
            animation: fadeIn 0.4s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 1rem;
            transition: color 0.3s ease;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1rem;
            background: #ffffff;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-control:focus + .input-icon {
            color: #667eea;
        }

        .form-control.is-invalid {
            border-color: #e53e3e;
            background: #fef5f5;
        }

        .error-message {
            color: #e53e3e;
            font-size: 0.85rem;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .remember-me input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #667eea;
        }

        .remember-me label {
            font-size: 0.9rem;
            color: #4a5568;
            cursor: pointer;
        }

        .forgot-password {
            color: #667eea;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: #764ba2;
        }

        .login-button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }

        .login-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.6s;
        }

        .login-button:hover::before {
            left: 100%;
        }

        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .login-button:active {
            transform: translateY(0);
        }

        .login-button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .loading-spinner {
            display: none;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s linear infinite;
            margin-right: 8px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .alert {
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            border: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-success {
            background: #f0fff4;
            color: #22543d;
            border-left: 4px solid #38a169;
        }

        .alert-danger {
            background: #fef5f5;
            color: #742a2a;
            border-left: 4px solid #e53e3e;
        }

        /* Mobile Responsiveness */
        @media (max-width: 480px) {
            .login-container {
                max-width: 100%;
                padding: 0 10px;
            }

            .login-header {
                padding: 30px 20px 20px;
            }

            .login-title {
                font-size: 1.6rem;
            }

            .login-body {
                padding: 20px;
            }

            .form-control {
                padding: 12px 14px 12px 44px;
            }

            .input-icon {
                left: 14px;
            }

            .logo-container img {
                width: 120px;
                height: 60px;
            }
        }


    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Header -->
            <div class="login-header">
                <div class="logo-container">
                    <img src="{{ asset('images/logo_for_login&favicon.png') }}" alt="Boothcare Logo">
                </div>
                <h1 class="login-title">Welcome Back</h1>
                <p class="login-subtitle">Sign in to your account</p>
            </div>

            <!-- Body -->
            <div class="login-body">
                <!-- Toast notifications will be handled by the layout -->
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Login Tabs -->
                <div class="login-tabs">
                    <div class="tab-indicator" id="tabIndicator"></div>
                    <button type="button" class="login-tab active" data-tab="user">
                        <i class="fas fa-user me-1"></i>User
                    </button>
                    <button type="button" class="login-tab" data-tab="admin">
                        <i class="fas fa-user-shield me-1"></i>Admin
                    </button>
                </div>

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf
                    <input type="hidden" name="login_type" id="loginType" value="user">

                    <!-- User Login Section -->
                    <div id="userSection" class="form-section active">
                        <div class="form-group">
                            <label for="aadhar_number" class="form-label">Aadhar Card Number</label>
                            <div class="input-wrapper">
                                <input type="text" 
                                       class="form-control @error('aadhar_number') is-invalid @enderror" 
                                       id="aadhar_number" 
                                       name="aadhar_number" 
                                       value="{{ old('aadhar_number') }}"
                                       placeholder="Enter 12-digit Aadhar number"
                                       maxlength="12"
                                       autocomplete="off">
                                <i class="fas fa-id-card input-icon"></i>
                            </div>
                            @error('aadhar_number')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <div class="input-wrapper">
                                <input type="date" 
                                       class="form-control @error('date_of_birth') is-invalid @enderror" 
                                       id="date_of_birth" 
                                       name="date_of_birth" 
                                       value="{{ old('date_of_birth') }}">
                                <i class="fas fa-calendar input-icon"></i>
                            </div>
                            @error('date_of_birth')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Admin Login Section -->
                    <div id="adminSection" class="form-section">
                        <div class="form-group">
                            <label for="email" class="form-label">Email Address</label>
                            <div class="input-wrapper">
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}"
                                       placeholder="Enter your email address"
                                       autocomplete="email">
                                <i class="fas fa-envelope input-icon"></i>
                            </div>
                            @error('email')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-wrapper">
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password"
                                       placeholder="Enter your password"
                                       autocomplete="current-password">
                                <i class="fas fa-lock input-icon"></i>
                            </div>
                            @error('password')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Options -->
                    <div class="form-options">
                        <div class="remember-me">
                            <input type="checkbox" id="remember_me" name="remember">
                            <label for="remember_me">Remember me</label>
                        </div>
                        <a href="{{ route('password.request.custom') }}" class="forgot-password" id="forgotLink" style="display: none;">
                            Forgot Password?
                        </a>
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="login-button" id="loginButton">
                        <div class="loading-spinner" id="loadingSpinner"></div>
                        <i class="fas fa-sign-in-alt me-2" id="loginIcon"></i>
                        <span id="loginButtonText">Sign In as User</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.login-tab');
            const indicator = document.getElementById('tabIndicator');
            const userSection = document.getElementById('userSection');
            const adminSection = document.getElementById('adminSection');
            const loginType = document.getElementById('loginType');
            const loginButtonText = document.getElementById('loginButtonText');
            const forgotLink = document.getElementById('forgotLink');
            const aadharInput = document.getElementById('aadhar_number');
            const loginForm = document.getElementById('loginForm');
            const loginButton = document.getElementById('loginButton');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const loginIcon = document.getElementById('loginIcon');

            // Tab switching
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const tabType = this.dataset.tab;
                    
                    // Update active tab
                    tabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Move indicator
                    if (tabType === 'admin') {
                        indicator.classList.add('admin');
                        forgotLink.style.display = 'inline';
                    } else {
                        indicator.classList.remove('admin');
                        forgotLink.style.display = 'none';
                    }
                    
                    // Switch sections
                    if (tabType === 'user') {
                        userSection.classList.add('active');
                        adminSection.classList.remove('active');
                        loginButtonText.textContent = 'Sign In as User';
                    } else {
                        adminSection.classList.add('active');
                        userSection.classList.remove('active');
                        loginButtonText.textContent = 'Sign In as Admin';
                    }
                    
                    loginType.value = tabType;
                    clearErrors();
                });
            });

            // Aadhar number formatting
            if (aadharInput) {
                aadharInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length > 12) {
                        value = value.substring(0, 12);
                    }
                    e.target.value = value;
                });

                aadharInput.addEventListener('keypress', function(e) {
                    const char = String.fromCharCode(e.which);
                    if (!/[0-9]/.test(char)) {
                        e.preventDefault();
                    }
                });
            }

            // Form submission
            loginForm.addEventListener('submit', function(e) {
                const button = loginButton;
                const spinner = loadingSpinner;
                const icon = loginIcon;
                const text = loginButtonText;

                // Show loading state
                button.disabled = true;
                spinner.style.display = 'inline-block';
                icon.style.display = 'none';
                text.textContent = 'Signing in...';
            });

            // Clear errors on input
            document.querySelectorAll('.form-control').forEach(input => {
                input.addEventListener('input', function() {
                    this.classList.remove('is-invalid');
                    const errorMsg = this.parentNode.parentNode.querySelector('.error-message');
                    if (errorMsg) {
                        errorMsg.remove();
                    }
                });
            });

            function clearErrors() {
                document.querySelectorAll('.is-invalid').forEach(input => {
                    input.classList.remove('is-invalid');
                });
                document.querySelectorAll('.error-message').forEach(error => {
                    error.remove();
                });
            }
        });
    </script>
</body>
</html>