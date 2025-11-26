<?php

namespace App\Actions\Rating;

use App\Models\Plan;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreateOrUpdateRatingAction
{
    public function __construct(
        private CalculatePlanAveragesAction $calculateAverages
    ) {}

    /**
     * Create or update a rating for a plan
     */
    public function execute(
        Plan $plan,
        User $user,
        array $data
    ): Rating {
        // Validate that plan belongs to user's couple
        if ($plan->couple_id !== $user->couple_id) {
            throw ValidationException::withMessages([
                'plan' => __('No tienes permiso para valorar este plan.'),
            ]);
        }

        // Validate rating values (1-5)
        $validated = validator($data, [
            'fun' => 'required|integer|min:1|max:5',
            'emotional_connection' => 'required|integer|min:1|max:5',
            'organization' => 'required|integer|min:1|max:5',
            'value_for_money' => 'required|integer|min:1|max:5',
            'overall' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ])->validate();

        return DB::transaction(function () use ($plan, $user, $validated) {
            // Find existing rating or create new one
            $rating = Rating::updateOrCreate(
                [
                    'plan_id' => $plan->id,
                    'user_id' => $user->id,
                ],
                $validated
            );

            // Recalculate plan averages
            $this->calculateAverages->execute($plan->fresh());

            return $rating;
        });
    }
}

