<?php

namespace App\Actions\Couple;

use App\Models\Couple;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class JoinCoupleAction
{
    /**
     * Join a user to an existing couple using join code
     */
    public function execute(User $user, string $joinCode): Couple
    {
        // Check if user already has a couple
        if ($user->hasCouple()) {
            throw new \Exception('User already belongs to a couple');
        }

        // Find couple by join code
        $couple = Couple::where('join_code', strtoupper($joinCode))->first();

        if (!$couple) {
            throw new \Exception('Invalid join code');
        }

        // Check if couple already has 2 members
        $memberCount = $couple->users()->count();
        if ($memberCount >= 2) {
            throw new \Exception('This couple is already complete');
        }

        return DB::transaction(function () use ($user, $couple) {
            // Assign couple to user
            $user->update([
                'couple_id' => $couple->id,
            ]);

            return $couple->fresh();
        });
    }
}


