<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Boothcare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .reset-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .reset-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
        }
        
        .reset-header {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        
        .reset-header h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .reset-body {
            padding: 40px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            display: block;
        }
        
        .form-control {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 15px 20px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f9fafb;
        }
        
        .form-control:focus {
            border-color: #8b5cf6;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
            background: white;
        }
        
        .input-group {
            position: relative;
        }
        
        .input-group-text {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: #6b7280;
            z-index: 10;
        }
        
        .input-group .form-control {
            padding-left: 50px;
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: #6b7280;
            cursor: pointer;
            z-index: 10;
        }
        
        .btn-reset {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            border: none;
            border-radius: 12px;
            padding: 15px 30px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
        }
        
        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(139, 92, 246, 0.4);
        }
        
        .password-strength {
            margin-top: 10px;
        }
        
        .strength-bar {
            height: 4px;
            border-radius: 2px;
            background: #e5e7eb;
            overflow: hidden;
            margin-bottom: 10px;
        }
        
        .strength-fill {
            height: 100%;
            transition: all 0.3s ease;
            width: 0%;
        }
        
        .strength-weak { background: #ef4444; }
        .strength-medium { background: #f59e0b; }
        .strength-strong { background: #10b981; }
        
        .password-requirements {
            font-size: 14px;
            color: #6b7280;
        }
        
        .requirement {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }
        
        .requirement.met {
            color: #10b981;
        }
        
        .requirement.met i {
            color: #10b981;
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <div class="reset-card">
            <div class="reset-header">
                <h1><i class="fas fa-lock me-3"></i>Reset Password</h1>
                <p>Create your new password</p>
            </div>
            
            <div class="reset-body">
                <!-- Toast notifications will be handled by the layout -->

                <form method="POST" action="{{ route('admin.password.reset') }}" id="resetForm">
                    @csrf

                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-2"></i>New Password
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password"
                                   placeholder="Enter new password"
                                   oninput="checkPasswordStrength()"
                                   required>
                            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                <i class="fas fa-eye" id="password-eye"></i>
                            </button>
                        </div>
                        
                        <div class="password-strength">
                            <div class="strength-bar">
                                <div class="strength-fill" id="strengthBar"></div>
                            </div>
                            <div class="password-requirements">
                                <div class="requirement" id="req-length">
                                    <i class="fas fa-times me-2"></i>At least 8 characters
                                </div>
                                <div class="requirement" id="req-uppercase">
                                    <i class="fas fa-times me-2"></i>One uppercase letter
                                </div>
                                <div class="requirement" id="req-lowercase">
                                    <i class="fas fa-times me-2"></i>One lowercase letter
                                </div>
                                <div class="requirement" id="req-number">
                                    <i class="fas fa-times me-2"></i>One number
                                </div>
                            </div>
                        </div>
                        
                        @error('password')
                            <div class="text-danger mt-2">
                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">
                            <i class="fas fa-lock me-2"></i>Confirm New Password
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation"
                                   placeholder="Confirm new password"
                                   oninput="checkPasswordMatch()"
                                   required>
                            <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                                <i class="fas fa-eye" id="password_confirmation-eye"></i>
                            </button>
                        </div>
                        <div id="password-match" class="mt-2" style="display: none;">
                            <small class="text-danger">
                                <i class="fas fa-exclamation-circle me-1"></i>Passwords do not match
                            </small>
                        </div>
                    </div>

                    <button type="submit" class="btn-reset" id="submitBtn" disabled>
                        <i class="fas fa-save me-2"></i>Reset Password
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const eye = document.getElementById(fieldId + '-eye');
            
            if (field.type === 'password') {
                field.type = 'text';
                eye.classList.remove('fa-eye');
                eye.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                eye.classList.remove('fa-eye-slash');
                eye.classList.add('fa-eye');
            }
        }

        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthBar = document.getElementById('strengthBar');
            
            let score = 0;
            let requirements = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /[0-9]/.test(password)
            };

            // Update requirement indicators
            Object.keys(requirements).forEach(req => {
                const element = document.getElementById('req-' + req);
                if (requirements[req]) {
                    element.classList.add('met');
                    element.querySelector('i').classList.remove('fa-times');
                    element.querySelector('i').classList.add('fa-check');
                    score++;
                } else {
                    element.classList.remove('met');
                    element.querySelector('i').classList.remove('fa-check');
                    element.querySelector('i').classList.add('fa-times');
                }
            });

            // Update strength bar
            const percentage = (score / 4) * 100;
            strengthBar.style.width = percentage + '%';
            
            strengthBar.className = 'strength-fill';
            if (score <= 1) {
                strengthBar.classList.add('strength-weak');
            } else if (score <= 3) {
                strengthBar.classList.add('strength-medium');
            } else {
                strengthBar.classList.add('strength-strong');
            }

            checkFormValidity();
        }

        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmation = document.getElementById('password_confirmation').value;
            const matchIndicator = document.getElementById('password-match');

            if (confirmation.length > 0) {
                if (password !== confirmation) {
                    matchIndicator.style.display = 'block';
                } else {
                    matchIndicator.style.display = 'none';
                }
            } else {
                matchIndicator.style.display = 'none';
            }

            checkFormValidity();
        }

        function checkFormValidity() {
            const password = document.getElementById('password').value;
            const confirmation = document.getElementById('password_confirmation').value;
            const submitBtn = document.getElementById('submitBtn');

            const isPasswordStrong = password.length >= 8 && 
                                   /[A-Z]/.test(password) && 
                                   /[a-z]/.test(password) && 
                                   /[0-9]/.test(password);
            
            const passwordsMatch = password === confirmation && confirmation.length > 0;

            if (isPasswordStrong && passwordsMatch) {
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
            } else {
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.6';
            }
        }

        // Initial check
        checkFormValidity();
    </script>
</body>
</html>