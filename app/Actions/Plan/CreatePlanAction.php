<?php

namespace App\Actions\Plan;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreatePlanAction
{
    /**
     * Create a new plan
     */
    public function execute(User $user, array $data): Plan
    {
        // Validate user has couple
        if (!$user->hasCouple()) {
            throw new \Exception('User must belong to a couple to create plans');
        }

        return DB::transaction(function () use ($user, $data) {
            $plan = Plan::create([
                'couple_id' => $user->couple_id,
                'title' => $data['title'],
                'date' => $data['date'],
                'category_id' => $data['category_id'],
                'location' => $data['location'] ?? null,
                'cost' => $data['cost'] ?? null,
                'description' => $data['description'] ?? null,
                'created_by' => $user->id,
                'status' => $data['status'] ?? 'pending',
            ]);

            // Log activity
            $plan->activityLog()->create([
                'user_id' => $user->id,
                'action' => 'created',
                'new_values' => $plan->toArray(),
            ]);

            return $plan->fresh(['category', 'createdBy']);
        });
    }
}



