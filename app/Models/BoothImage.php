<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BoothImage extends Model
{
    protected $fillable = [
        'booth_id',
        'image_path',
        'title',
        'description',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    public function booth(): BelongsTo
    {
        return $this->belongsTo(Booth::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        return \App\Helpers\FileUploadHelper::getFileUrl($this->image_path);
    }
}