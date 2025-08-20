<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP - Boothcare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8fafc;
            min-height: 100vh;
        }
        
        .verify-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .verify-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
            overflow: hidden;
            max-width: 450px;
            width: 100%;
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
        
        .verify-header {
            text-align: center;
            padding: 40px 30px 30px;
            background: #ffffff;
            border-bottom: 1px solid #f1f5f9;
        }

        .logo-container {
            margin-bottom: 20px;
        }

        .logo-container img {
            width: 100px;
            height: 50px;
            object-fit: contain;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.1));
        }
        
        .verify-header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 8px;
            color: #1a202c;
            letter-spacing: -0.5px;
        }

        .verify-header p {
            font-size: 1rem;
            color: #718096;
            font-weight: 500;
        }
        
        .verify-body {
            padding: 40px;
        }
        
        .otp-input-group {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 30px 0;
        }
        
        .otp-input {
            width: 50px;
            height: 60px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            background: #f9fafb;
            transition: all 0.3s ease;
        }
        
        .otp-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            background: white;
            outline: none;
        }
        
        .form-control {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 15px 20px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f9fafb;
        }
        
        .btn-verify {
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

        .btn-verify::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.6s;
        }

        .btn-verify:hover::before {
            left: 100%;
        }
        
        .btn-verify:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-verify:active {
            transform: translateY(0);
        }

        .btn-verify:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }
        
        .resend-otp {
            text-align: center;
            margin-top: 20px;
        }
        
        .resend-otp a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        
        .resend-otp a:hover {
            color: #764ba2;
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 20px;
        }
        
        .info-box {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            text-align: center;
        }
        
        .timer {
            font-size: 18px;
            font-weight: bold;
            color: #f59e0b;
        }
    </style>
</head>
<body>
    <div class="verify-container">
        <div class="verify-card">
            <div class="verify-header">
                <div class="logo-container">
                    <img src="{{ asset('images/logo_for_login&favicon.png') }}" alt="Boothcare Logo">
                </div>
                <h1>Verify OTP</h1>
                <p>Enter the code sent to your email</p>
            </div>
            
            <div class="verify-body">
                <!-- Toast notifications will be handled by the layout -->

                <div class="info-box">
                    <p><i class="fas fa-envelope me-2"></i>We've sent a 6-digit OTP to your registered email address.</p>
                    <div class="timer" id="timer">Valid for: <span id="countdown">10:00</span></div>
                </div>

                <form method="POST" action="{{ route('password.verify-otp.post') }}" id="otpForm">
                    @csrf

                    <div class="text-center mb-4">
                        <label class="form-label fw-bold">Enter 6-Digit OTP</label>
                        <div class="otp-input-group">
                            <input type="text" class="otp-input" maxlength="1" oninput="moveToNext(this, 0)">
                            <input type="text" class="otp-input" maxlength="1" oninput="moveToNext(this, 1)">
                            <input type="text" class="otp-input" maxlength="1" oninput="moveToNext(this, 2)">
                            <input type="text" class="otp-input" maxlength="1" oninput="moveToNext(this, 3)">
                            <input type="text" class="otp-input" maxlength="1" oninput="moveToNext(this, 4)">
                            <input type="text" class="otp-input" maxlength="1" oninput="moveToNext(this, 5)">
                        </div>
                        <input type="hidden" name="otp" id="otpValue">
                        @error('otp')
                            <div class="text-danger mt-2">
                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn-verify">
                        <i class="fas fa-check me-2"></i>Verify OTP
                    </button>

                    <div class="resend-otp">
                        <form method="POST" action="{{ route('password.resend-otp') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link p-0" style="color: #667eea; text-decoration: none; font-weight: 500;">
                                <i class="fas fa-redo me-1"></i>Resend OTP
                            </button>
                        </form>
                        <span class="mx-2">|</span>
                        <a href="{{ route('login') }}">
                            <i class="fas fa-arrow-left me-1"></i>Back to Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // OTP input handling
        function moveToNext(current, index) {
            // Only allow numbers
            current.value = current.value.replace(/[^0-9]/g, '');
            
            if (current.value.length === 1 && index < 5) {
                document.querySelectorAll('.otp-input')[index + 1].focus();
            }
            
            updateOtpValue();
        }

        function updateOtpValue() {
            const inputs = document.querySelectorAll('.otp-input');
            let otp = '';
            inputs.forEach(input => {
                otp += input.value;
            });
            document.getElementById('otpValue').value = otp;
        }

        // Handle backspace
        document.querySelectorAll('.otp-input').forEach((input, index) => {
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && this.value === '' && index > 0) {
                    document.querySelectorAll('.otp-input')[index - 1].focus();
                }
            });
        });

        // Countdown timer using server-side expiration time
        const expiresAt = new Date('{{ $expiresAt->toISOString() }}');
        const countdownElement = document.getElementById('countdown');

        function updateCountdown() {
            const now = new Date();
            const timeLeft = Math.max(0, Math.floor((expiresAt - now) / 1000));
            
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            countdownElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            
            if (timeLeft <= 0) {
                countdownElement.textContent = 'Expired';
                countdownElement.style.color = '#ef4444';
                document.getElementById('otpForm').style.opacity = '0.5';
                document.querySelector('.btn-verify').disabled = true;
                
                // Show expired message
                const expiredDiv = document.createElement('div');
                expiredDiv.className = 'alert alert-danger mt-3';
                expiredDiv.innerHTML = '<i class="fas fa-clock me-2"></i>OTP has expired. Please request a new one.';
                
                const form = document.getElementById('otpForm');
                if (!form.querySelector('.alert-danger')) {
                    form.insertBefore(expiredDiv, form.firstChild);
                }
            }
        }

        // Update countdown every second
        setInterval(updateCountdown, 1000);
        updateCountdown(); // Initial call

        // Auto-submit when all 6 digits are entered
        document.querySelectorAll('.otp-input').forEach(input => {
            input.addEventListener('input', function() {
                updateOtpValue();
                const otp = document.getElementById('otpValue').value;
                if (otp.length === 6) {
                    setTimeout(() => {
                        document.getElementById('otpForm').submit();
                    }, 500);
                }
            });
        });

        // Focus first input on load
        document.querySelectorAll('.otp-input')[0].focus();
    </script>
</body>
</html>