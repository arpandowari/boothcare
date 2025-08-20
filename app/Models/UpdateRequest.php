<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UpdateRequest extends Model
{
    protected $fillable = [
        'user_id',
        'request_type',
        'target_id',
        'target_type',
        'current_data',
        'requested_data',
        'reason',
        'status',
        'reviewed_by',
        'review_notes',
        'reviewed_at'
    ];

    protected $casts = [
        'current_data' => 'array',
        'requested_data' => 'array',
        'reviewed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function target()
    {
        return $this->morphTo();
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => '<span class="badge bg-warning">Pending</span>',
            'approved' => '<span class="badge bg-success">Approved</span>',
            'rejected' => '<span class="badge bg-danger">Rejected</span>',
            default => '<span class="badge bg-secondary">Unknown</span>'
        };
    }

    public function getRequestTypeDisplayAttribute(): string
    {
        return match($this->request_type) {
            'profile' => 'Profile Update',
            'family_member' => 'Family Member Update',
            'documents' => 'Document Update',
            default => ucfirst(str_replace('_', ' ', $this->request_type))
        };
    }
}