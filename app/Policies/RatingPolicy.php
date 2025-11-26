<?php

namespace App\Policies;

use App\Models\Plan;
use App\Models\Rating;
use App\Models\User;

class RatingPolicy
{
    /**
     * Determine if the user can create a rating
     */
    public function create(User $user, ?Plan $plan = null): bool
    {
        // En el contexto de admin, todos los admins pueden crear valoraciones
        if ($user->isAdmin()) {
            return true;
        }
        
        // En el contexto normal, el usuario debe tener pareja y el plan debe pertenecer a esa pareja
        if ($plan === null) {
            return $user->hasCouple();
        }
        
        return $user->hasCouple() && $user->couple_id === $plan->couple_id;
    }

    /**
     * Determine if the user can update the rating
     */
    public function update(User $user, Rating $rating): bool
    {
        // User can only update their own rating
        return $rating->user_id === $user->id;
    }

    /**
     * Determine if the user can delete the rating
     */
    public function delete(User $user, Rating $rating): bool
    {
        // User can only delete their own rating
        return $rating->user_id === $user->id;
    }

    /**
     * Determine if the user can view any ratings
     */
    public function viewAny(User $user): bool
    {
        // En el contexto de admin, todos los admins pueden ver todas las valoraciones
        return $user->isAdmin();
    }
}

