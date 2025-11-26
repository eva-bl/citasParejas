<?php

namespace App\Policies;

use App\Models\Plan;
use App\Models\User;

class PlanPolicy
{
    /**
     * Determine if the user can view any plans
     */
    public function viewAny(User $user): bool
    {
        return $user->hasCouple();
    }

    /**
     * Determine if the user can view the plan
     */
    public function view(User $user, Plan $plan): bool
    {
        return $user->couple_id === $plan->couple_id;
    }

    /**
     * Determine if the user can create plans
     */
    public function create(User $user): bool
    {
        return $user->hasCouple();
    }

    /**
     * Determine if the user can update the plan
     */
    public function update(User $user, Plan $plan): bool
    {
        return $user->couple_id === $plan->couple_id;
    }

    /**
     * Determine if the user can delete the plan
     */
    public function delete(User $user, Plan $plan): bool
    {
        return $user->couple_id === $plan->couple_id;
    }
}

