<?php

namespace App\Actions\Photo;

use App\Models\Photo;
use App\Services\ImageProcessingService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DeletePlanPhotoAction
{
    public function __construct(
        private ImageProcessingService $imageService
    ) {}

    /**
     * Delete a photo (soft delete)
     */
    public function execute(Photo $photo): void
    {
        // Validate that photo belongs to user's couple plan
        if ($photo->plan->couple_id !== auth()->user()->couple_id) {
            throw ValidationException::withMessages([
                'photo' => __('No tienes permiso para eliminar esta foto.'),
            ]);
        }

        DB::transaction(function () use ($photo) {
            // Delete files from storage
            $this->imageService->deletePhoto($photo->path);
            
            // Soft delete photo record (Observer will update photos_count)
            $photo->delete();
        });
    }
}

