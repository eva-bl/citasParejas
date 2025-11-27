<?php

namespace App\Actions\Badge;

use App\Models\Badge;
use App\Models\User;
use App\Notifications\BadgeEarnedNotification;
use App\Services\BadgeEvaluationService;
use Illuminate\Support\Facades\DB;

class CheckAndAssignBadgesAction
{
    protected BadgeEvaluationService $evaluationService;
    
    public function __construct(BadgeEvaluationService $evaluationService)
    {
        $this->evaluationService = $evaluationService;
    }
    
    /**
     * Evaluar y asignar insignias a un usuario
     * 
     * @param User $user
     * @return array Array de insignias recién asignadas
     */
    public function execute(User $user): array
    {
        $newlyAssignedBadges = [];
        
        // Obtener todas las insignias disponibles
        $allBadges = Badge::all();
        
        foreach ($allBadges as $badge) {
            // Verificar si el usuario ya tiene esta insignia
            if ($this->evaluationService->userHasBadge($user, $badge)) {
                continue;
            }
            
            // Evaluar si cumple los criterios
            if ($this->evaluationService->evaluateBadge($user, $badge)) {
                // Asignar la insignia
                DB::transaction(function () use ($user, $badge, &$newlyAssignedBadges) {
                    $user->badges()->attach($badge->id, [
                        'obtained_at' => now(),
                    ]);
                    
                    $newlyAssignedBadges[] = $badge;
                    
                    // Notificar al usuario
                    $user->notify(new BadgeEarnedNotification($badge));
                });
            }
        }
        
        return $newlyAssignedBadges;
    }
}

