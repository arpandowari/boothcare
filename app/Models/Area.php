<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
    protected $fillable = [
        'area_name',
        'district',
        'division',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function booths(): HasMany
    {
        return $this->hasMany(Booth::class);
    }

    public function getTotalBoothsAttribute()
    {
        return $this->booths()->count();
    }

    public function getTotalHousesAttribute()
    {
        return House::whereHas('booth', function ($query) {
            $query->where('area_id', $this->id);
        })->count();
    }

    public function getTotalMembersAttribute()
    {
        return FamilyMember::whereHas('house.booth', function ($query) {
            $query->where('area_id', $this->id);
        })->count();
    }

    public function getTotalProblemsAttribute()
    {
        return Problem::whereHas('familyMember.house.booth', function ($query) {
            $query->where('area_id', $this->id);
        })->count();
    }
}
