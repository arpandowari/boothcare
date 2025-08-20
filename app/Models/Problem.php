<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Helpers\FileUploadHelper;

class Problem extends Model
{
    /**
     * Boot the model and add model events
     */
    protected static function boot()
    {
        parent::boot();

        // Delete associated files when problem is deleted
        static::deleting(function ($problem) {
            // Delete problem photo if it exists
            if ($problem->problem_photo) {
                FileUploadHelper::deleteFile($problem->problem_photo);
            }
        });
    }

    protected $fillable = [
        'family_member_id',
        'reported_by',
        'title',
        'description',
        'category',
        'priority',
        'status',
        'problem_photo',
        'reported_date',
        'expected_resolution_date',
        'actual_resolution_date',
        'resolution_notes',
        'assigned_to',
        'estimated_cost',
        'actual_cost',
        'admin_notes',
        'is_public',
        'user_feedback',
        'user_rating',
        'feedback_date',
        'feedback_submitted'
    ];

    protected $casts = [
        'reported_date' => 'date',
        'expected_resolution_date' => 'date',
        'actual_resolution_date' => 'date',
        'estimated_cost' => 'decimal:2',
        'actual_cost' => 'decimal:2',
        'is_public' => 'boolean',
        'feedback_date' => 'datetime',
        'feedback_submitted' => 'boolean',
    ];

    public function familyMember(): BelongsTo
    {
        return $this->belongsTo(FamilyMember::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function reportedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'reported' => 'warning',
            'in_progress' => 'info',
            'resolved' => 'success',
            'closed' => 'secondary',
            default => 'primary'
        };
    }

    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            'low' => 'success',
            'medium' => 'warning',
            'high' => 'danger',
            'urgent' => 'dark',
            default => 'primary'
        };
    }

    public function getDaysOpenAttribute()
    {
        $endDate = $this->actual_resolution_date ?? now();
        return $this->reported_date->diffInDays($endDate);
    }

    /**
     * Get problem photo URL
     */
    public function getProblemPhotoUrlAttribute(): ?string
    {
        return \App\Helpers\FileUploadHelper::getFileUrl($this->problem_photo);
    }
}
