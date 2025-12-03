<?php

namespace App\Actions\Plan;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DeletePlanAction
{
    /**
     * Soft delete a plan
     */
    public function execute(User $user, Plan $plan): bool
    {
        return DB::transaction(function () use ($user, $plan) {
            // Log activity before deletion
            $plan->activityLog()->create([
                'user_id' => $user->id,
                'action' => 'deleted',
                'old_values' => $plan->toArray(),
            ]);

            return $plan->delete();
        });
    }
}



