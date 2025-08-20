<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PublicReport extends Model
{
    protected $fillable = [
        'booth_id',
        'family_member_id',
        'reporter_name',
        'reporter_phone',
        'reporter_email',
        'problem_title',
        'problem_description',
        'category',
        'priority',
        'status',
        'is_verified',
        'verified_by',
        'verified_at',
        'admin_response',
        'photos'
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
        'photos' => 'array'
    ];

    public function booth(): BelongsTo
    {
        return $this->belongsTo(Booth::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function familyMember(): BelongsTo
    {
        return $this->belongsTo(FamilyMember::class, 'family_member_id');
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'verified' => 'info',
            'in_progress' => 'primary',
            'resolved' => 'success',
            'rejected' => 'danger',
            default => 'secondary'
        };
    }

    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'high' => 'danger',
            'medium' => 'warning',
            'low' => 'info',
            default => 'secondary'
        };
    }
}