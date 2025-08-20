<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Boothcare</title>
    
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
        
        .reset-container {
            width: 100%;
            max-width: 450px;
            position: relative;
        }
        
        .reset-card {
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
        
        .reset-header {
            text-align: center;
            padding: 40px 30px 30px;
            background: #ffffff;
            border-bottom: 1px solid #f1f5f9;
        }

        .logo-container {
            margin-bottom: 20px;
        }

        .logo-container img {
            width: 120px;
            height: 60px;
            object-fit: contain;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.1));
        }
        
        .reset-header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 8px;
            color: #1a202c;
            letter-spacing: -0.5px;
        }

        .reset-header p {
            font-size: 1rem;
            color: #718096;
            font-weight: 500;
        }
        
        .reset-body {
            padding: 30px;
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
        
        .btn-reset {
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

        .btn-reset::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.6s;
        }

        .btn-reset:hover::before {
            left: 100%;
        }
        
        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-reset:active {
            transform: translateY(0);
        }

        .btn-reset:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }
        
        .back-to-login {
            text-align: center;
            margin-top: 25px;
        }
        
        .back-to-login a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .back-to-login a:hover {
            color: #764ba2;
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
        
        .info-box {
            background: #f0f9ff;
            border: 1px solid #0ea5e9;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 20px;
        }
        
        .info-box h6 {
            color: #0369a1;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }
        
        .info-box p {
            margin: 0;
            color: #075985;
            font-size: 0.85rem;
            line-height: 1.5;
        }

        /* Mobile Responsiveness */
        @media (max-width: 480px) {
            .reset-container {
                max-width: 100%;
                padding: 0 10px;
            }

            .reset-header {
                padding: 30px 20px 20px;
            }

            .reset-header h1 {
                font-size: 1.5rem;
            }

            .reset-body {
                padding: 20px;
            }

            .form-control {
                padding: 12px 14px 12px 44px;
            }

            .input-icon {
                left: 14px;
            }

            .logo-container img {
                width: 100px;
                height: 50px;
            }
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <div class="reset-card">
            <div class="reset-header">
                <div class="logo-container">
                    <img src="{{ asset('images/logo_for_login&favicon.png') }}" alt="Boothcare Logo">
                </div>
                <h1>Forgot Password</h1>
                <p>Reset your admin account password</p>
            </div>
            
            <div class="reset-body">
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

                <div class="info-box">
                    <h6><i class="fas fa-info-circle me-2"></i>Password Reset Process:</h6>
                    <p>Enter your admin email address and we'll send you a password reset link to securely reset your password.</p>
                </div>

                <form method="POST" action="{{ route('password.send-otp') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email" class="form-label">Admin Email Address</label>
                        <div class="input-wrapper">
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   placeholder="Enter your admin email address"
                                   autocomplete="email"
                                   required>
                            <i class="fas fa-envelope input-icon"></i>
                        </div>
                        @error('email')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn-reset">
                        <i class="fas fa-paper-plane me-2"></i>Send Reset Link
                    </button>

                    <div class="back-to-login">
                        <a href="{{ route('login') }}">
                            <i class="fas fa-arrow-left"></i>
                            Back to Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');
            const form = document.querySelector('form');
            const submitButton = document.querySelector('.btn-reset');

            // Clear errors on input
            emailInput.addEventListener('input', function() {
                this.classList.remove('is-invalid');
                const errorMsg = this.parentNode.parentNode.querySelector('.error-message');
                if (errorMsg) {
                    errorMsg.remove();
                }
            });

            // Form submission with loading state
            form.addEventListener('submit', function(e) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
            });

            // Email validation
            emailInput.addEventListener('blur', function() {
                const email = this.value.trim();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                
                if (email && !emailRegex.test(email)) {
                    this.classList.add('is-invalid');
                    if (!this.parentNode.parentNode.querySelector('.error-message')) {
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'error-message';
                        errorDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Please enter a valid email address';
                        this.parentNode.parentNode.appendChild(errorDiv);
                    }
                }
            });
        });
    </script>
</body>
</html>