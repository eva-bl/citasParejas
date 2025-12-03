<?php

namespace App\Actions\Plan;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TogglePlanFavoriteAction
{
    /**
     * Marcar o desmarcar un plan como favorito
     * 
     * @param User $user
     * @param Plan $plan
     * @return bool True si se marcó como favorito, false si se desmarcó
     * @throws ValidationException
     */
    public function execute(User $user, Plan $plan): bool
    {
        // Verificar que el usuario pertenece a la pareja del plan
        if ($user->couple_id !== $plan->couple_id) {
            throw ValidationException::withMessages([
                'plan' => 'No estás autorizado para marcar este plan como favorito.',
            ]);
        }

        return DB::transaction(function () use ($user, $plan) {
            $isFavorite = $user->favoritePlans()->where('plan_id', $plan->id)->exists();
            
            if ($isFavorite) {
                // Desmarcar como favorito
                $user->favoritePlans()->detach($plan->id);
                return false;
            } else {
                // Marcar como favorito
                $user->favoritePlans()->attach($plan->id);
                return true;
            }
        });
    }
}




