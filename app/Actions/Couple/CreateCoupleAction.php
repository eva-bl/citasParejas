<?php

namespace App\Actions\Couple;

use App\Models\Couple;
use App\Models\User;
use App\Services\ImageProcessingService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class CreateCoupleAction
{
    public function __construct(
        private ImageProcessingService $imageService
    ) {}

    /**
     * Create a new couple and assign it to the user
     * 
     * @param User $user
     * @param string|null $name
     * @param int $memberCount
     * @param UploadedFile|null $photo
     * @return Couple
     */
    public function execute(User $user, ?string $name = null, int $memberCount = 2, ?UploadedFile $photo = null): Couple
    {
        // Check if user already has a couple
        if ($user->hasCouple()) {
            throw new \Exception('User already belongs to a couple');
        }

        return DB::transaction(function () use ($user, $name, $memberCount, $photo) {
            // Process photo if provided
            $photoPath = null;
            if ($photo) {
                // We need to create the couple first to get the ID, but we'll do it in a way that works
                // For now, we'll create a temporary couple, process the photo, then update
                $tempCouple = Couple::create([
                    'join_code' => Couple::generateJoinCode(),
                    'name' => $name,
                    'member_count' => $memberCount,
                ]);
                
                $photoPath = $this->imageService->processAndStoreCouplePhoto($photo, $tempCouple->id);
                
                $tempCouple->update(['photo_path' => $photoPath]);
                $couple = $tempCouple;
            } else {
                // Create couple with unique join code
                $couple = Couple::create([
                    'join_code' => Couple::generateJoinCode(),
                    'name' => $name,
                    'member_count' => $memberCount,
                ]);
            }

            // Assign couple to user
            $user->update([
                'couple_id' => $couple->id,
            ]);

            return $couple->fresh();
        });
    }
}




