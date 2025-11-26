<?php

namespace App\Actions\Couple;

use App\Models\Couple;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateCoupleAction
{
    /**
     * Create a new couple and assign it to the user
     */
    public function execute(User $user): Couple
    {
        // Check if user already has a couple
        if ($user->hasCouple()) {
            throw new \Exception('User already belongs to a couple');
        }

        return DB::transaction(function () use ($user) {
            // Create couple with unique join code
            $couple = Couple::create([
                'join_code' => Couple::generateJoinCode(),
            ]);

            // Assign couple to user
            $user->update([
                'couple_id' => $couple->id,
            ]);

            return $couple->fresh();
        });
    }
}


