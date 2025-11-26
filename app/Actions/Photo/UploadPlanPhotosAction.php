<?php

namespace App\Actions\Photo;

use App\Models\Plan;
use App\Models\Photo;
use App\Services\ImageProcessingService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UploadPlanPhotosAction
{
    private const MAX_PHOTOS_PER_PLAN = 20;
    private const MAX_FILE_SIZE = 10240; // 10MB in KB
    private const ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/webp',
    ];

    public function __construct(
        private ImageProcessingService $imageService
    ) {}

    /**
     * Upload multiple photos for a plan
     * 
     * @param Plan $plan
     * @param array<UploadedFile> $files
     * @return array<Photo>
     */
    public function execute(Plan $plan, array $files): array
    {
        // Validate plan belongs to user's couple
        if ($plan->couple_id !== auth()->user()->couple_id) {
            throw ValidationException::withMessages([
                'plan' => __('No tienes permiso para subir fotos a este plan.'),
            ]);
        }

        // Validate number of files
        $currentPhotoCount = $plan->photos()->count();
        if ($currentPhotoCount + count($files) > self::MAX_PHOTOS_PER_PLAN) {
            throw ValidationException::withMessages([
                'photos' => __('El número máximo de fotos por plan es :max. Ya tienes :current fotos.', [
                    'max' => self::MAX_PHOTOS_PER_PLAN,
                    'current' => $currentPhotoCount,
                ]),
            ]);
        }

        // Validate each file
        foreach ($files as $file) {
            $this->validateFile($file);
        }

        return DB::transaction(function () use ($plan, $files) {
            $uploadedPhotos = [];
            
            foreach ($files as $file) {
                // Process and store image
                $path = $this->imageService->processAndStore(
                    $file,
                    $plan->couple_id,
                    $plan->id
                );
                
                // Create photo record
                $photo = Photo::create([
                    'plan_id' => $plan->id,
                    'path' => $path,
                ]);
                
                $uploadedPhotos[] = $photo;
            }
            
            return $uploadedPhotos;
        });
    }

    /**
     * Validate uploaded file
     */
    private function validateFile(UploadedFile $file): void
    {
        // Validate MIME type
        if (!in_array($file->getMimeType(), self::ALLOWED_MIME_TYPES)) {
            throw ValidationException::withMessages([
                'photos' => __('El archivo :name no es una imagen válida (JPG, PNG o WebP).', [
                    'name' => $file->getClientOriginalName(),
                ]),
            ]);
        }
        
        // Validate file size (in KB)
        $fileSizeKB = $file->getSize() / 1024;
        if ($fileSizeKB > self::MAX_FILE_SIZE) {
            throw ValidationException::withMessages([
                'photos' => __('El archivo :name excede el tamaño máximo de :max MB.', [
                    'name' => $file->getClientOriginalName(),
                    'max' => self::MAX_FILE_SIZE / 1024,
                ]),
            ]);
        }
    }
}

