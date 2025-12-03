<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\Plan;
use App\Models\Rating;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BadgeEvaluationService
{
    /**
     * Evaluar si un usuario cumple los criterios de una insignia
     * 
     * @param User $user
     * @param Badge $badge
     * @return bool
     */
    public function evaluateBadge(User $user, Badge $badge): bool
    {
        $criteria = $badge->criteria;
        
        if (!isset($criteria['type'])) {
            return false;
        }
        
        return match ($criteria['type']) {
            'total_completed_plans' => $this->evaluateTotalCompletedPlans($user, $criteria),
            'completed_plans_by_category' => $this->evaluateCompletedPlansByCategory($user, $criteria),
            'high_rating_plan' => $this->evaluateHighRatingPlan($user, $criteria),
            'monthly_consistency' => $this->evaluateMonthlyConsistency($user, $criteria),
            default => false,
        };
    }
    
    /**
     * Evaluar criterio: total de planes completados
     * 
     * @param User $user
     * @param array $criteria
     * @return bool
     */
    protected function evaluateTotalCompletedPlans(User $user, array $criteria): bool
    {
        if (!$user->hasCouple()) {
            return false;
        }
        
        $requiredCount = $criteria['value'] ?? 0;
        $timeframe = $criteria['timeframe'] ?? 'all_time';
        
        $query = Plan::where('couple_id', $user->couple_id)
            ->where('status', 'completed');
        
        if ($timeframe === 'last_month') {
            $query->where('date', '>=', Carbon::now()->subMonth()->startOfMonth());
        } elseif ($timeframe === 'last_year') {
            $query->where('date', '>=', Carbon::now()->subYear()->startOfYear());
        }
        
        $count = $query->count();
        
        return $count >= $requiredCount;
    }
    
    /**
     * Evaluar criterio: planes completados por categoría
     * 
     * @param User $user
     * @param array $criteria
     * @return bool
     */
    protected function evaluateCompletedPlansByCategory(User $user, array $criteria): bool
    {
        if (!$user->hasCouple()) {
            return false;
        }
        
        $categoryName = $criteria['category'] ?? null;
        $requiredCount = $criteria['value'] ?? 0;
        
        if (!$categoryName) {
            return false;
        }
        
        $count = Plan::where('couple_id', $user->couple_id)
            ->where('status', 'completed')
            ->whereHas('category', function ($query) use ($categoryName) {
                $query->where('name', $categoryName);
            })
            ->count();
        
        return $count >= $requiredCount;
    }
    
    /**
     * Evaluar criterio: plan con alta valoración
     * 
     * @param User $user
     * @param array $criteria
     * @return bool
     */
    protected function evaluateHighRatingPlan(User $user, array $criteria): bool
    {
        if (!$user->hasCouple()) {
            return false;
        }
        
        $requiredRating = $criteria['value'] ?? 4.5;
        
        $hasHighRating = Plan::where('couple_id', $user->couple_id)
            ->where('overall_avg', '>=', $requiredRating)
            ->whereNotNull('overall_avg')
            ->exists();
        
        return $hasHighRating;
    }
    
    /**
     * Evaluar criterio: consistencia mensual
     * 
     * @param User $user
     * @param array $criteria
     * @return bool
     */
    protected function evaluateMonthlyConsistency(User $user, array $criteria): bool
    {
        if (!$user->hasCouple()) {
            return false;
        }
        
        $requiredMonths = $criteria['value'] ?? 6;
        
        // Obtener los últimos N meses con al menos 1 plan completado
        $monthsWithPlans = Plan::where('couple_id', $user->couple_id)
            ->where('status', 'completed')
            ->select(DB::raw('DATE_FORMAT(date, "%Y-%m") as month'))
            ->distinct()
            ->orderBy('month', 'desc')
            ->limit($requiredMonths)
            ->pluck('month')
            ->toArray();
        
        // Verificar que tenemos al menos N meses consecutivos
        if (count($monthsWithPlans) < $requiredMonths) {
            return false;
        }
        
        // Verificar que son meses consecutivos
        $currentMonth = Carbon::now()->format('Y-m');
        $expectedMonths = [];
        
        for ($i = $requiredMonths - 1; $i >= 0; $i--) {
            $expectedMonths[] = Carbon::now()->subMonths($i)->format('Y-m');
        }
        
        // Verificar que todos los meses esperados están en los meses con planes
        foreach ($expectedMonths as $expectedMonth) {
            if (!in_array($expectedMonth, $monthsWithPlans)) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Obtener todas las insignias disponibles
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllBadges()
    {
        return Badge::all();
    }
    
    /**
     * Obtener insignias de un usuario
     * 
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserBadges(User $user)
    {
        return $user->badges()->orderBy('user_badges.obtained_at', 'desc')->get();
    }
    
    /**
     * Verificar si un usuario tiene una insignia
     * 
     * @param User $user
     * @param Badge $badge
     * @return bool
     */
    public function userHasBadge(User $user, Badge $badge): bool
    {
        return $user->badges()->where('badge_id', $badge->id)->exists();
    }
}




