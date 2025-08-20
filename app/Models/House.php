<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class House extends Model
{
    protected $fillable = [
        'booth_id',
        'house_number',
        'address',
        'area',
        'pincode',
        'latitude',
        'longitude',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function booth(): BelongsTo
    {
        return $this->belongsTo(Booth::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(FamilyMember::class);
    }

    public function familyHead()
    {
        return $this->hasOne(FamilyMember::class)->where('is_family_head', true);
    }

    public function getTotalMembersAttribute()
    {
        return $this->members()->count();
    }

    public function getTotalProblemsAttribute()
    {
        return Problem::whereHas('familyMember', function ($query) {
            $query->where('house_id', $this->id);
        })->count();
    }
}
