<?php

namespace App\Services;

use App\Models\Couple;
use App\Models\Plan;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class StatisticsService
{
    /**
     * Obtener estadísticas de una pareja
     * 
     * @param Couple $couple
     * @return array
     */
    public function getCoupleStatistics(Couple $couple): array
    {
        $cacheKey = "couple_stats_{$couple->id}";
        
        return Cache::remember($cacheKey, 3600, function () use ($couple) {
            $plans = Plan::where('couple_id', $couple->id)->get();
            
            // Estadísticas básicas
            $totalPlans = $plans->count();
            $completedPlans = $plans->where('status', 'completed')->count();
            $pendingPlans = $plans->where('status', 'pending')->count();
            $cancelledPlans = $plans->where('status', 'cancelled')->count();
            
            // Valoraciones
            $ratings = Rating::whereHas('plan', function ($query) use ($couple) {
                $query->where('couple_id', $couple->id);
            })->get();
            
            $totalRatings = $ratings->count();
            $overallAvg = $ratings->avg('overall') ?? 0;
            $funAvg = $ratings->avg('fun') ?? 0;
            $emotionalAvg = $ratings->avg('emotional_connection') ?? 0;
            $organizationAvg = $ratings->avg('organization') ?? 0;
            $valueAvg = $ratings->avg('value_for_money') ?? 0;
            
            // Categorías mejor/peor valoradas
            $categoryStats = $this->getCategoryStatistics($couple);
            
            // Distribución por meses
            $monthlyDistribution = $this->getMonthlyDistribution($couple);
            
            // Fotos
            $totalPhotos = $plans->sum('photos_count');
            
            return [
                'total_plans' => $totalPlans,
                'completed_plans' => $completedPlans,
                'pending_plans' => $pendingPlans,
                'cancelled_plans' => $cancelledPlans,
                'total_ratings' => $totalRatings,
                'overall_avg' => round($overallAvg, 2),
                'fun_avg' => round($funAvg, 2),
                'emotional_connection_avg' => round($emotionalAvg, 2),
                'organization_avg' => round($organizationAvg, 2),
                'value_for_money_avg' => round($valueAvg, 2),
                'total_photos' => $totalPhotos,
                'category_stats' => $categoryStats,
                'monthly_distribution' => $monthlyDistribution,
            ];
        });
    }
    
    /**
     * Obtener estadísticas de un usuario individual
     * 
     * @param User $user
     * @return array
     */
    public function getUserStatistics(User $user): array
    {
        $cacheKey = "user_stats_{$user->id}";
        
        return Cache::remember($cacheKey, 3600, function () use ($user) {
            // Planes creados
            $plansCreated = Plan::where('created_by', $user->id)->count();
            
            // Valoraciones del usuario
            $userRatings = Rating::where('user_id', $user->id)->get();
            $ratingsCount = $userRatings->count();
            $userOverallAvg = $userRatings->avg('overall') ?? 0;
            $userFunAvg = $userRatings->avg('fun') ?? 0;
            $userEmotionalAvg = $userRatings->avg('emotional_connection') ?? 0;
            $userOrganizationAvg = $userRatings->avg('organization') ?? 0;
            $userValueAvg = $userRatings->avg('value_for_money') ?? 0;
            
            // Planes valorados
            $plansRated = $userRatings->pluck('plan_id')->unique()->count();
            
            // Fotos subidas (a través de planes)
            $photosUploaded = Plan::where('created_by', $user->id)
                ->sum('photos_count');
            
            return [
                'plans_created' => $plansCreated,
                'ratings_count' => $ratingsCount,
                'plans_rated' => $plansRated,
                'photos_uploaded' => $photosUploaded,
                'overall_avg' => round($userOverallAvg, 2),
                'fun_avg' => round($userFunAvg, 2),
                'emotional_connection_avg' => round($userEmotionalAvg, 2),
                'organization_avg' => round($userOrganizationAvg, 2),
                'value_for_money_avg' => round($userValueAvg, 2),
            ];
        });
    }
    
    /**
     * Obtener estadísticas por categoría
     * 
     * @param Couple $couple
     * @return array
     */
    protected function getCategoryStatistics(Couple $couple): array
    {
        $categoryStats = DB::table('plans')
            ->join('ratings', 'plans.id', '=', 'ratings.plan_id')
            ->join('categories', 'plans.category_id', '=', 'categories.id')
            ->where('plans.couple_id', $couple->id)
            ->select(
                'categories.id',
                'categories.name',
                'categories.icon',
                'categories.color',
                DB::raw('AVG(ratings.overall) as avg_rating'),
                DB::raw('COUNT(ratings.id) as ratings_count')
            )
            ->groupBy('categories.id', 'categories.name', 'categories.icon', 'categories.color')
            ->orderBy('avg_rating', 'desc')
            ->get();
        
        $bestCategory = $categoryStats->first();
        $worstCategory = $categoryStats->last();
        
        return [
            'all' => $categoryStats->map(function ($stat) {
                return [
                    'id' => $stat->id,
                    'name' => $stat->name,
                    'icon' => $stat->icon,
                    'color' => $stat->color,
                    'avg_rating' => round($stat->avg_rating, 2),
                    'ratings_count' => $stat->ratings_count,
                ];
            })->toArray(),
            'best' => $bestCategory ? [
                'id' => $bestCategory->id,
                'name' => $bestCategory->name,
                'icon' => $bestCategory->icon,
                'color' => $bestCategory->color,
                'avg_rating' => round($bestCategory->avg_rating, 2),
            ] : null,
            'worst' => $worstCategory ? [
                'id' => $worstCategory->id,
                'name' => $worstCategory->name,
                'icon' => $worstCategory->icon,
                'color' => $worstCategory->color,
                'avg_rating' => round($worstCategory->avg_rating, 2),
            ] : null,
        ];
    }
    
    /**
     * Obtener distribución mensual de planes
     * 
     * @param Couple $couple
     * @return array
     */
    protected function getMonthlyDistribution(Couple $couple): array
    {
        $distribution = DB::table('plans')
            ->where('couple_id', $couple->id)
            ->select(
                DB::raw('DATE_FORMAT(date, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();
        
        return $distribution->map(function ($item) {
            return [
                'month' => $item->month,
                'count' => $item->count,
            ];
        })->toArray();
    }
    
    /**
     * Invalidar caché de estadísticas de una pareja
     * 
     * @param Couple $couple
     * @return void
     */
    public function invalidateCoupleCache(Couple $couple): void
    {
        Cache::forget("couple_stats_{$couple->id}");
        
        // También invalidar estadísticas de los usuarios de la pareja
        foreach ($couple->users as $user) {
            Cache::forget("user_stats_{$user->id}");
        }
    }
    
    /**
     * Invalidar caché de estadísticas de un usuario
     * 
     * @param User $user
     * @return void
     */
    public function invalidateUserCache(User $user): void
    {
        Cache::forget("user_stats_{$user->id}");
        
        // Si tiene pareja, invalidar también las estadísticas de la pareja
        if ($user->couple) {
            Cache::forget("couple_stats_{$user->couple->id}");
        }
    }
}

