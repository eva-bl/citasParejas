<?php

namespace App\Actions\Plan;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UpdatePlanAction
{
    /**
     * Update an existing plan
     */
    public function execute(User $user, Plan $plan, array $data): Plan
    {
        // Store old values for activity log
        $oldValues = $plan->toArray();

        return DB::transaction(function () use ($user, $plan, $data, $oldValues) {
            $plan->update([
                'title' => $data['title'] ?? $plan->title,
                'date' => $data['date'] ?? $plan->date,
                'category_id' => $data['category_id'] ?? $plan->category_id,
                'location' => $data['location'] ?? $plan->location,
                'cost' => $data['cost'] ?? $plan->cost,
                'description' => $data['description'] ?? $plan->description,
                'status' => $data['status'] ?? $plan->status,
            ]);

            // Log activity
            $plan->activityLog()->create([
                'user_id' => $user->id,
                'action' => 'updated',
                'old_values' => $oldValues,
                'new_values' => $plan->fresh()->toArray(),
            ]);

            return $plan->fresh(['category', 'createdBy']);
        });
    }
}




