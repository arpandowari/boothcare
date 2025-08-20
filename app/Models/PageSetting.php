<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'section',
        'description',
        'is_active',
        'updated_by'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Helper methods
    public function getValueAttribute($value)
    {
        if ($this->type === 'json') {
            return json_decode($value, true);
        }
        
        if ($this->type === 'boolean') {
            return (bool) $value;
        }
        
        return $value;
    }

    public function setValueAttribute($value)
    {
        if ($this->type === 'json' && is_array($value)) {
            $this->attributes['value'] = json_encode($value);
        } else {
            $this->attributes['value'] = $value;
        }
    }

    // Static helper methods
    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->where('is_active', true)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set($key, $value, $type = 'text', $section = 'general', $description = null)
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'section' => $section,
                'description' => $description,
                'updated_by' => auth()->id() ?? 1,
                'is_active' => true
            ]
        );
    }

    public static function getBySection($section)
    {
        return static::where('section', $section)
                    ->where('is_active', true)
                    ->pluck('value', 'key')
                    ->toArray();
    }
}
