<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageProcessingService
{
    private ImageManager $imageManager;
    private const MAX_WIDTH = 2000;
    private const MAX_HEIGHT = 2000;
    private const THUMBNAIL_SIZES = [
        'small' => [150, 150],
        'medium' => [300, 300],
        'large' => [800, 800],
    ];

    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver());
    }

    /**
     * Process and store an uploaded image
     * 
     * @param UploadedFile $file
     * @param int $coupleId
     * @param int $planId
     * @return string Relative path from storage/app/public
     */
    public function processAndStore(UploadedFile $file, int $coupleId, int $planId): string
    {
        // Generate unique filename
        $uniqueId = Str::uuid()->toString();
        
        // Create directory structure
        $basePath = "couples/{$coupleId}/plans/{$planId}";
        
        // Ensure directories exist
        Storage::disk('public')->makeDirectory($basePath);
        Storage::disk('public')->makeDirectory("{$basePath}/thumbnails");
        
        // Read and process image
        $image = $this->imageManager->read($file->getRealPath());
        
        // Resize if too large (maintain aspect ratio)
        $image = $this->resizeIfNeeded($image);
        
        // Save original (as WebP for better compression)
        $webpPath = "{$basePath}/{$uniqueId}.webp";
        $image->toWebp(85)->save(Storage::disk('public')->path($webpPath));
        
        // Generate thumbnails
        $this->generateThumbnails($image, $basePath, $uniqueId);
        
        return $webpPath;
    }

    /**
     * Resize image if it exceeds maximum dimensions
     */
    private function resizeIfNeeded($image)
    {
        $width = $image->width();
        $height = $image->height();
        
        if ($width > self::MAX_WIDTH || $height > self::MAX_HEIGHT) {
            $image->scaleDown(self::MAX_WIDTH, self::MAX_HEIGHT);
        }
        
        return $image;
    }

    /**
     * Generate thumbnails in different sizes
     */
    private function generateThumbnails($image, string $basePath, string $uniqueId): void
    {
        $thumbnailsPath = "{$basePath}/thumbnails";
        
        foreach (self::THUMBNAIL_SIZES as $size => [$width, $height]) {
            $thumbnail = clone $image;
            $thumbnail->cover($width, $height);
            
            $thumbnailPath = "{$thumbnailsPath}/{$uniqueId}_{$width}x{$height}.webp";
            $thumbnail->toWebp(85)->save(Storage::disk('public')->path($thumbnailPath));
        }
    }

    /**
     * Delete photo and all its thumbnails
     */
    public function deletePhoto(string $path): void
    {
        // Delete original
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
        
        // Delete thumbnails
        $pathInfo = pathinfo($path);
        $thumbnailsDir = $pathInfo['dirname'] . '/thumbnails';
        $filename = $pathInfo['filename'];
        
        foreach (self::THUMBNAIL_SIZES as $size => [$width, $height]) {
            $thumbnailPath = "{$thumbnailsDir}/{$filename}_{$width}x{$height}.webp";
            if (Storage::disk('public')->exists($thumbnailPath)) {
                Storage::disk('public')->delete($thumbnailPath);
            }
        }
    }

    /**
     * Get thumbnail URL for a given size
     */
    public function getThumbnailUrl(string $path, string $size = 'medium'): string
    {
        if (!isset(self::THUMBNAIL_SIZES[$size])) {
            $size = 'medium';
        }
        
        [$width, $height] = self::THUMBNAIL_SIZES[$size];
        $pathInfo = pathinfo($path);
        $thumbnailsDir = $pathInfo['dirname'] . '/thumbnails';
        $filename = $pathInfo['filename'];
        
        $thumbnailPath = "{$thumbnailsDir}/{$filename}_{$width}x{$height}.webp";
        
        return Storage::disk('public')->url($thumbnailPath);
    }

    /**
     * Process and store a couple photo
     * 
     * @param UploadedFile $file
     * @param int $coupleId
     * @return string Relative path from storage/app/public
     */
    public function processAndStoreCouplePhoto(UploadedFile $file, int $coupleId): string
    {
        // Generate unique filename
        $uniqueId = Str::uuid()->toString();
        
        // Create directory structure
        $basePath = "couples/{$coupleId}";
        
        // Ensure directories exist
        Storage::disk('public')->makeDirectory($basePath);
        Storage::disk('public')->makeDirectory("{$basePath}/thumbnails");
        
        // Read and process image
        $image = $this->imageManager->read($file->getRealPath());
        
        // Resize if too large (maintain aspect ratio)
        $image = $this->resizeIfNeeded($image);
        
        // Save original (as WebP for better compression)
        $webpPath = "{$basePath}/photo_{$uniqueId}.webp";
        $image->toWebp(85)->save(Storage::disk('public')->path($webpPath));
        
        // Generate thumbnails
        $this->generateThumbnails($image, $basePath, "photo_{$uniqueId}");
        
        return $webpPath;
    }
}

