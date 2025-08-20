<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\FamilyMember;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $loginType = $this->input('login_type', 'user');
        
        if ($loginType === 'admin') {
            return [
                'email' => ['required', 'string', 'email'],
                'password' => ['required', 'string'],
                'login_type' => ['required', 'in:admin,user'],
            ];
        }
        
        return [
            'aadhar_number' => ['nullable', 'string', 'size:12'],
            'date_of_birth' => ['nullable', 'date'],
            'login_type' => ['required', 'in:admin,user'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $loginType = $this->input('login_type', 'user');
        
        if ($loginType === 'admin') {
            $this->authenticateAdmin();
        } else {
            $this->authenticateUser();
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Authenticate admin using email and password
     */
    private function authenticateAdmin(): void
    {
        $email = $this->input('email');
        $password = $this->input('password');

        // Log authentication attempt for debugging
        \Log::info('Admin authentication attempt', [
            'email' => $email,
            'ip' => $this->ip()
        ]);

        // Find admin user by email and check if they have admin privileges
        $user = User::where('email', $email)
            ->whereIn('role', ['admin', 'sub_admin', 'super_admin'])
            ->where('is_active', true)
            ->first();

        if (!$user) {
            \Log::warning('Admin user not found during authentication', [
                'email' => $email
            ]);

            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => 'Invalid email or password, or account is not active.',
            ]);
        }

        // Check password
        if (!Hash::check($password, $user->password)) {
            \Log::warning('Admin password mismatch', [
                'user_id' => $user->id,
                'email' => $email
            ]);

            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => 'Invalid email or password, or account is not active.',
            ]);
        }

        \Log::info('Admin authentication successful', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_role' => $user->role
        ]);

        // Manually log in the admin user
        Auth::login($user, $this->boolean('remember'));
    }

    /**
     * Authenticate user using Aadhar number and date of birth from family_members table
     */
    private function authenticateUser(): void
    {
        $aadharNumber = trim($this->input('aadhar_number'));
        $dateOfBirth = $this->input('date_of_birth');

        // Log authentication attempt for debugging
        \Log::info('Family member authentication attempt', [
            'aadhar_number' => $aadharNumber,
            'date_of_birth' => $dateOfBirth,
            'ip' => $this->ip()
        ]);

        // Clean Aadhaar number - remove any spaces, dashes, or special characters
        $cleanAadhar = preg_replace('/[^0-9]/', '', $aadharNumber);

        // Find the family member with matching Aadhar number
        $familyMember = FamilyMember::where('aadhar_number', $cleanAadhar)
            ->where('is_active', true)
            ->first();

        // Also try with original format if clean version doesn't work
        if (!$familyMember) {
            $familyMember = FamilyMember::where('aadhar_number', $aadharNumber)
                ->where('is_active', true)
                ->first();
        }

        if (!$familyMember) {
            \Log::warning('Family member not found during authentication', [
                'aadhar_number' => $aadharNumber,
                'clean_aadhar' => $cleanAadhar
            ]);

            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'aadhar_number' => 'Invalid Aadhar number or member not found.',
            ]);
        }

        // Check if date of birth matches
        if (!$familyMember->date_of_birth) {
            \Log::warning('Family member has no date of birth set', [
                'family_member_id' => $familyMember->id,
                'aadhar_number' => $aadharNumber
            ]);

            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'date_of_birth' => 'Date of birth not set for this member. Please contact administrator.',
            ]);
        }

        $memberDob = $familyMember->date_of_birth->format('Y-m-d');
        if ($memberDob !== $dateOfBirth) {
            \Log::warning('Date of birth mismatch', [
                'family_member_id' => $familyMember->id,
                'input_dob' => $dateOfBirth,
                'member_dob' => $memberDob
            ]);

            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'date_of_birth' => 'Invalid date of birth.',
            ]);
        }

        // Try to get existing linked user account first
        $user = null;
        if ($familyMember->user_id) {
            $user = User::where('id', $familyMember->user_id)
                ->where('is_active', true)
                ->first();
        }

        // If no linked user account exists, create one automatically
        if (!$user) {
            $user = User::create([
                'name' => $familyMember->name,
                'email' => $familyMember->email ?: $familyMember->aadhar_number . '@member.local',
                'aadhar_number' => $familyMember->aadhar_number,
                'date_of_birth' => $familyMember->date_of_birth,
                'phone' => $familyMember->phone,
                'role' => 'user',
                'is_active' => true,
                'password' => Hash::make('default_password_' . $familyMember->aadhar_number), // Temporary password
                'email_verified_at' => now(),
            ]);

            // Link the family member to the new user account
            $familyMember->update([
                'user_id' => $user->id,
                'can_login' => true,
                'user_account_created_at' => now()
            ]);

            \Log::info('Auto-created user account for family member', [
                'family_member_id' => $familyMember->id,
                'user_id' => $user->id,
                'member_name' => $familyMember->name
            ]);
        }

        \Log::info('Family member authentication successful', [
            'user_id' => $user->id,
            'family_member_id' => $familyMember->id,
            'user_name' => $user->name,
            'member_name' => $familyMember->name
        ]);

        // Authenticate the user account
        Auth::login($user, $this->boolean('remember'));
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        $loginType = $this->input('login_type', 'user');
        
        if ($loginType === 'admin') {
            return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
        }
        
        return Str::transliterate(Str::lower($this->string('aadhar_number')).'|'.$this->ip());
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'aadhar_number.size' => 'Aadhar number must be exactly 12 digits.',
            'date_of_birth.date' => 'Please enter a valid date of birth.',
            'email.required' => 'Email address is required for admin login.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'Password is required for admin login.',
        ];
    }
}
