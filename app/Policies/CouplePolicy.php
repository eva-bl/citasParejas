<?php

namespace App\Policies;

use App\Models\Couple;
use App\Models\User;

class CouplePolicy
{
    /**
     * Determine if the user can view the couple
     */
    public function view(User $user, Couple $couple): bool
    {
        return $user->couple_id === $couple->id;
    }

    /**
     * Determine if the user can update the couple
     */
    public function update(User $user, Couple $couple): bool
    {
        return $user->couple_id === $couple->id;
    }

    /**
     * Determine if the user can create a couple
     */
    public function create(User $user): bool
    {
        return !$user->hasCouple();
    }
}


