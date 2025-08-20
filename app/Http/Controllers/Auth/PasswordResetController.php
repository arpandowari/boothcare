<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PasswordResetOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use App\Services\EmailService;

class PasswordResetController extends Controller
{
    /**
     * Show the password reset request form
     */
    public function showRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send OTP to user's email
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::where('email', $request->email)
            ->where('role', 'admin')
            ->where('is_active', true)
            ->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'No admin account found with this email address.'
            ]);
        }

        // Generate and send OTP
        $otp = PasswordResetOtp::generateOtp($request->email, $user->email);

        // Send OTP using EmailService
        $emailService = new EmailService();
        $emailSent = $emailService->sendPasswordResetOTP($user->email, $otp, $user->name);

        if ($emailSent) {
            // Store email in session for the entire password reset process
            session(['user_email' => $request->email]);
            
            return redirect()->route('password.verify-otp')
                ->with('success', 'Password reset OTP has been sent to your email address.');
        } else {
            return back()->withErrors([
                'email' => 'Failed to send password reset email. Please try again later.'
            ]);
        }
    }

    /**
     * Show OTP verification form
     */
    public function showVerifyOtpForm()
    {
        if (!session('user_email')) {
            return redirect()->route('password.request.custom');
        }

        // Get the OTP record to pass expiration time to view
        $userEmail = session('user_email');
        $otpRecord = PasswordResetOtp::where('email', $userEmail)
            ->where('is_used', false)
            ->orderBy('created_at', 'desc')
            ->first();

        $expiresAt = $otpRecord ? $otpRecord->expires_at : now()->addMinutes(10);

        return view('auth.verify-otp', compact('expiresAt'));
    }

    /**
     * Verify OTP and show password reset form
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $userEmail = session('user_email');
        if (!$userEmail) {
            return redirect()->route('password.request.custom')
                ->withErrors(['otp' => 'Session expired. Please start again.']);
        }

        // Debug: Check OTP record before verification
        $otpRecord = PasswordResetOtp::where('email', $userEmail)
            ->where('otp', $request->otp)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$otpRecord) {
            return back()->withErrors([
                'otp' => 'Invalid OTP. Please check the code and try again.'
            ]);
        }

        if ($otpRecord->is_used) {
            return back()->withErrors([
                'otp' => 'This OTP has already been used. Please request a new OTP.'
            ]);
        }

        if ($otpRecord->isExpired()) {
            return back()->withErrors([
                'otp' => 'OTP has expired. Please request a new OTP.'
            ]);
        }

        if (!PasswordResetOtp::verifyOtp($userEmail, $request->otp)) {
            return back()->withErrors([
                'otp' => 'Invalid or expired OTP. Please try again.'
            ]);
        }

        // Store OTP verification status in session
        session(['otp_verified' => true]);

        return redirect()->route('password.reset.custom')
            ->with('success', 'OTP verified successfully. You can now reset your password.');
    }

    /**
     * Show password reset form
     */
    public function showResetForm()
    {
        if (!session('otp_verified') || !session('user_email')) {
            return redirect()->route('password.request.custom');
        }

        return view('auth.reset-password');
    }

    /**
     * Reset the password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $userEmail = session('user_email');
        if (!session('otp_verified') || !$userEmail) {
            return redirect()->route('password.request.custom')
                ->withErrors(['password' => 'Session expired. Please start again.']);
        }

        $user = User::where('email', $userEmail)
            ->where('role', 'admin')
            ->where('is_active', true)
            ->first();

        if (!$user) {
            return redirect()->route('password.request.custom')
                ->withErrors(['password' => 'Admin user not found.']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Clear session
        $request->session()->forget(['user_email', 'otp_verified']);

        return redirect()->route('login')
            ->with('status', 'Admin password has been reset successfully. You can now login with your new password.');
    }

    /**
     * Resend OTP to user's email
     */
    public function resendOtp(Request $request)
    {
        $userEmail = session('user_email');
        if (!$userEmail) {
            return redirect()->route('password.request.custom')
                ->withErrors(['email' => 'Session expired. Please start again.']);
        }

        // Find the user
        $user = User::where('email', $userEmail)->first();
        if (!$user) {
            return redirect()->route('password.request.custom')
                ->withErrors(['email' => 'User not found. Please start again.']);
        }

        // Generate and send new OTP
        $otp = PasswordResetOtp::generateOtp($userEmail, $user->email);

        // Send OTP using EmailService
        $emailService = new EmailService();
        $emailSent = $emailService->sendPasswordResetOTP($user->email, $otp, $user->name);

        if ($emailSent) {
            return redirect()->route('password.verify-otp')
                ->with('success', 'A new OTP has been sent to your email address.');
        } else {
            return back()->withErrors([
                'otp' => 'Failed to send OTP. Please try again or contact support.'
            ]);
        }
    }
}