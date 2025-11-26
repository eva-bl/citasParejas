<?php

namespace App\Policies;

use App\Models\Photo;
use App\Models\Plan;
use App\Models\User;

class PhotoPolicy
{
    /**
     * Determine if the user can upload photos to a plan
     */
    public function create(User $user, Plan $plan): bool
    {
        return $user->hasCouple() && $user->couple_id === $plan->couple_id;
    }

    /**
     * Determine if the user can delete the photo
     */
    public function delete(User $user, Photo $photo): bool
    {
        return $photo->plan->couple_id === $user->couple_id;
    }

    /**
     * Determine if the user can view any photos
     */
    public function viewAny(User $user): bool
    {
        // En el contexto de admin, todos los admins pueden ver todas las fotos
        return $user->isAdmin();
    }
}

