<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Photo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'plan_id',
        'path',
    ];

    /**
     * Get the plan that owns the photo
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get the full URL for the photo
     */
    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->path);
    }

    /**
     * Get thumbnail URL for a given size
     */
    public function getThumbnailUrl(string $size = 'medium'): string
    {
        $pathInfo = pathinfo($this->path);
        $sizes = [
            'small' => '150x150',
            'medium' => '300x300',
            'large' => '800x800',
        ];
        
        if (!isset($sizes[$size])) {
            $size = 'medium';
        }
        
        $thumbnailPath = $pathInfo['dirname'] . '/thumbnails/' . $pathInfo['filename'] . '_' . $sizes[$size] . '.webp';
        
        return Storage::disk('public')->url($thumbnailPath);
    }

    /**
     * Get full image URL
     */
    public function getFullUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->path);
    }
}
