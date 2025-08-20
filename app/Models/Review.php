<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'booth_id',
        'user_id',
        'family_member_id',
        'reviewer_name',
        'reviewer_phone',
        'rating',
        'comment',
        'is_approved',
        'approved_by',
        'approved_at'
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
        'rating' => 'integer'
    ];

    public function booth(): BelongsTo
    {
        return $this->belongsTo(Booth::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function familyMember(): BelongsTo
    {
        return $this->belongsTo(FamilyMember::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getReviewerNameAttribute(): string
    {
        if ($this->family_member_id) {
            return $this->familyMember->name ?? 'Anonymous';
        }
        return $this->user->name ?? 'Anonymous';
    }
}