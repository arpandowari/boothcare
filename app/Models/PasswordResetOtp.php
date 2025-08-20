<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PasswordResetOtp extends Model
{
    protected $fillable = [
        'aadhar_number',
        'email',
        'otp',
        'expires_at',
        'is_used'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_used' => 'boolean',
    ];

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isValid(): bool
    {
        return !$this->is_used && !$this->isExpired();
    }

    public static function generateOtp(string $identifier, string $email): string
    {
        // Delete any existing OTPs for this email
        self::where('email', $email)->delete();

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // For email-based reset, store email in both fields for compatibility
        self::create([
            'aadhar_number' => null, // Set to null for email-based reset
            'email' => $email,
            'otp' => $otp,
            'expires_at' => Carbon::now()->addMinutes(10), // OTP expires in 10 minutes
        ]);

        return $otp;
    }

    public static function verifyOtp(string $identifier, string $otp): bool
    {
        // Try to find by email first (for admin reset), then by aadhar (for user reset)
        $otpRecord = self::where('email', $identifier)
            ->where('otp', $otp)
            ->where('is_used', false)
            ->first();

        // If not found by email, try by aadhar_number (for backward compatibility)
        if (!$otpRecord) {
            $otpRecord = self::where('aadhar_number', $identifier)
                ->where('otp', $otp)
                ->where('is_used', false)
                ->first();
        }

        if (!$otpRecord || $otpRecord->isExpired()) {
            return false;
        }

        $otpRecord->update(['is_used' => true]);
        return true;
    }
}