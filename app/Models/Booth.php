<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booth extends Model
{
    protected $fillable = [
        'area_id',
        'booth_number',
        'booth_name',
        'description',
        'location',
        'constituency',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function houses(): HasMany
    {
        return $this->hasMany(House::class);
    }

    public function getTotalHousesAttribute()
    {
        return $this->houses()->count();
    }

    public function getTotalMembersAttribute()
    {
        return FamilyMember::whereHas('house', function ($query) {
            $query->where('booth_id', $this->id);
        })->count();
    }

    public function getTotalProblemsAttribute()
    {
        return Problem::whereHas('familyMember.house', function ($query) {
            $query->where('booth_id', $this->id);
        })->count();
    }

    public function images(): HasMany
    {
        return $this->hasMany(BoothImage::class)->where('is_active', true)->orderBy('sort_order');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('is_approved', true)->latest();
    }

    public function publicReports(): HasMany
    {
        return $this->hasMany(PublicReport::class)->where('is_verified', true)->latest();
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function getTotalReviewsAttribute()
    {
        return $this->reviews()->count();
    }
}
