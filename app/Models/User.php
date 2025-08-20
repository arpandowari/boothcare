<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Helpers\FileUploadHelper;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Boot the model and add model events
     */
    protected static function boot()
    {
        parent::boot();

        // Delete associated files when user is deleted
        static::deleting(function ($user) {
            // Delete all photo files
            $photoFields = [
                'profile_photo',
                'aadhar_photo', 
                'pan_photo',
                'voter_id_photo',
                'ration_card_photo'
            ];

            foreach ($photoFields as $field) {
                if ($user->$field) {
                    FileUploadHelper::deleteFile($user->$field);
                }
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'permissions',
        'phone',
        'date_of_birth',
        'gender',
        'nid_number',
        'aadhar_number',
        'profile_photo',
        'aadhar_photo',
        'pan_photo',
        'voter_id_photo',
        'ration_card_photo',
        'is_active',
        'preferences',
        'profile_visibility',
        'show_contact',
        'show_activity'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'is_active' => 'boolean',
            'preferences' => 'array',
            'show_contact' => 'boolean',
            'show_activity' => 'boolean',
        ];
    }

    // Role-based methods
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSubAdmin(): bool
    {
        return $this->role === 'sub_admin';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function isAdminOrSubAdmin(): bool
    {
        return in_array($this->role, ['admin', 'sub_admin']);
    }

    public function canDirectlyEdit(): bool
    {
        // Both Admin and Sub-Admin can directly edit
        return $this->isAdminOrSubAdmin();
    }

    public function canOnlyRequest(): bool
    {
        // No one needs to request anymore - Admin and Sub-Admin can directly edit
        return false;
    }

    // Permission methods
    public function hasPermission(string $permission): bool
    {
        // Admin has all permissions
        if ($this->isAdmin()) {
            return true;
        }
        
        // Sub-Admin permissions are checked against their assigned permissions
        if ($this->isSubAdmin()) {
            $userPermissions = $this->permissions ?? [];
            return in_array($permission, $userPermissions);
        }

        return false;
    }

    public function grantPermission(string $permission): void
    {
        if ($this->isSubAdmin()) {
            $permissions = is_string($this->permissions) ? json_decode($this->permissions, true) : ($this->permissions ?? []);
            if (!in_array($permission, $permissions)) {
                $permissions[] = $permission;
                $this->permissions = $permissions;
                $this->save();
            }
        }
    }

    public function revokePermission(string $permission): void
    {
        if ($this->isSubAdmin()) {
            $permissions = is_string($this->permissions) ? json_decode($this->permissions, true) : ($this->permissions ?? []);
            $permissions = array_filter($permissions, fn($p) => $p !== $permission);
            $this->permissions = array_values($permissions);
            $this->save();
        }
    }

    public function getPermissionsAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    public function setPermissionsAttribute($value)
    {
        $this->attributes['permissions'] = is_array($value) ? json_encode($value) : $value;
    }

    // Relationships
    public function familyMember()
    {
        return $this->hasOne(FamilyMember::class);
    }

    public function reportedProblems()
    {
        return $this->hasMany(Problem::class, 'reported_by');
    }

    public function assignedProblems()
    {
        return $this->hasMany(Problem::class, 'assigned_to');
    }

    public function updateRequests()
    {
        return $this->hasMany(UpdateRequest::class);
    }

    public function reviewedRequests()
    {
        return $this->hasMany(UpdateRequest::class, 'reviewed_by');
    }

    // Helper methods
    public function getAgeAttribute()
    {
        return $this->date_of_birth ? $this->date_of_birth->age : null;
    }



    /**
     * Find user by aadhar number for authentication
     */
    public static function findByAadhar($aadharNumber)
    {
        return static::where('aadhar_number', $aadharNumber)
            ->where('role', 'user')
            ->where('is_active', true)
            ->first();
    }

    public function getFullProfileAttribute()
    {
        return $this->familyMember ? $this->familyMember : null;
    }

    /**
     * Get profile photo URL
     */
    public function getProfilePhotoUrlAttribute(): ?string
    {
        return \App\Helpers\FileUploadHelper::getFileUrl($this->profile_photo);
    }
}
