<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use App\Helpers\FileUploadHelper;

class FamilyMember extends Authenticatable implements AuthenticatableContract
{
    /**
     * Boot the model and add model events
     */
    protected static function boot()
    {
        parent::boot();

        // Delete associated files when family member is deleted
        static::deleting(function ($familyMember) {
            // Delete all photo files
            $photoFields = [
                'profile_photo',
                'aadhar_photo', 
                'pan_photo',
                'voter_id_photo',
                'ration_card_photo'
            ];

            foreach ($photoFields as $field) {
                if ($familyMember->$field) {
                    FileUploadHelper::deleteFile($familyMember->$field);
                }
            }
        });
    }

    protected $fillable = [
        'house_id',
        'user_id',
        'name',
        'date_of_birth',
        'gender',
        'relation_to_head',
        'relationship_to_head',
        'phone',
        'email',
        'education',
        'occupation',
        'marital_status',
        'monthly_income',
        'aadhar_number',
        'aadhar_photo',
        'pan_number',
        'pan_photo',
        'voter_id',
        'voter_id_photo',
        'ration_card_number',
        'ration_card_photo',
        'profile_photo',
        'medical_conditions',
        'notes',
        'is_head',
        'is_family_head',
        'is_active',
        'can_login',
        'user_account_created_at',
        'remember_token'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'is_head' => 'boolean',
        'is_family_head' => 'boolean',
        'is_active' => 'boolean',
        'can_login' => 'boolean',
        'user_account_created_at' => 'datetime',
        'monthly_income' => 'decimal:2',
    ];

    public function house(): BelongsTo
    {
        return $this->belongsTo(House::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function problems(): HasMany
    {
        return $this->hasMany(Problem::class);
    }

    public function getAgeAttribute()
    {
        return $this->date_of_birth ? $this->date_of_birth->age : null;
    }

    public function getTotalProblemsAttribute()
    {
        return $this->problems()->count();
    }

    public function getActiveProblemsAttribute()
    {
        return $this->problems()->whereIn('status', ['reported', 'in_progress'])->count();
    }

    public function getResolvedProblemsAttribute()
    {
        return $this->problems()->where('status', 'resolved')->count();
    }

    /**
     * Check if this family member has a user account
     */
    public function hasUserAccount(): bool
    {
        return $this->user_id !== null && $this->can_login;
    }



    /**
     * Get the login status display
     */
    public function getLoginStatusAttribute(): string
    {
        if ($this->hasUserAccount()) {
            return 'Can Login';
        } else {
            return 'Family Member Only';
        }
    }

    /**
     * Get the login status color
     */
    public function getLoginStatusColorAttribute(): string
    {
        if ($this->hasUserAccount()) {
            return 'success';
        } else {
            return 'secondary';
        }
    }

    /**
     * Check if the user account was auto-created
     */
    public function isAutoCreatedAccount(): bool
    {
        return $this->hasUserAccount() && 
               $this->user_account_created_at !== null &&
               $this->created_at->diffInMinutes($this->user_account_created_at) < 5; // Created within 5 minutes of member creation
    }

    /**
     * Check if a user account can be created for this family member
     */
    public function canCreateUserAccount(): bool
    {
        return !$this->hasUserAccount() && 
               !empty($this->aadhar_number) && 
               !empty($this->date_of_birth);
    }

    /**
     * Get profile photo URL
     */
    public function getProfilePhotoUrlAttribute(): ?string
    {
        return \App\Helpers\FileUploadHelper::getFileUrl($this->profile_photo);
    }

    /**
     * Get Aadhaar photo URL
     */
    public function getAadharPhotoUrlAttribute(): ?string
    {
        return \App\Helpers\FileUploadHelper::getFileUrl($this->aadhar_photo);
    }

    /**
     * Get PAN photo URL
     */
    public function getPanPhotoUrlAttribute(): ?string
    {
        return \App\Helpers\FileUploadHelper::getFileUrl($this->pan_photo);
    }

    /**
     * Get Voter ID photo URL
     */
    public function getVoterIdPhotoUrlAttribute(): ?string
    {
        return \App\Helpers\FileUploadHelper::getFileUrl($this->voter_id_photo);
    }

    /**
     * Get Ration Card photo URL
     */
    public function getRationCardPhotoUrlAttribute(): ?string
    {
        return \App\Helpers\FileUploadHelper::getFileUrl($this->ration_card_photo);
    }

    // Authentication methods required by Authenticatable interface
    
    /**
     * Get the name of the unique identifier for the user.
     */
    public function getAuthIdentifierName(): string
    {
        return 'id';
    }

    /**
     * Get the unique identifier for the user.
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user (not used for family members).
     */
    public function getAuthPassword(): string
    {
        return ''; // Family members don't use passwords
    }

    /**
     * Get the token value for the "remember me" session.
     */
    public function getRememberToken(): ?string
    {
        return $this->remember_token ?? null;
    }

    /**
     * Set the token value for the "remember me" session.
     */
    public function setRememberToken($value): void
    {
        $this->remember_token = $value;
    }

    /**
     * Get the column name for the "remember me" token.
     */
    public function getRememberTokenName(): string
    {
        return 'remember_token';
    }

    // User role methods for compatibility with existing code
    
    /**
     * Check if the family member is an admin or sub-admin (always false for family members)
     */
    public function isAdminOrSubAdmin(): bool
    {
        return false;
    }

    /**
     * Check if the family member is an admin (always false for family members)
     */
    public function isAdmin(): bool
    {
        return false;
    }

    /**
     * Check if the family member is a sub-admin (always false for family members)
     */
    public function isSubAdmin(): bool
    {
        return false;
    }

    /**
     * Check if the family member is a super admin (always false for family members)
     */
    public function isSuperAdmin(): bool
    {
        return false;
    }

    /**
     * Check if the family member has a specific permission (always false for family members)
     */
    public function hasPermission(string $permission): bool
    {
        return false;
    }

    /**
     * Get the role attribute (always 'user' for family members)
     */
    public function getRoleAttribute(): string
    {
        return 'user';
    }

    /**
     * Get the permissions attribute (empty for family members)
     */
    public function getPermissionsAttribute(): array
    {
        return [];
    }

    /**
     * Get the family member relationship (for compatibility)
     */
    public function familyMember(): BelongsTo
    {
        return $this->belongsTo(FamilyMember::class, 'id', 'id');
    }
}
