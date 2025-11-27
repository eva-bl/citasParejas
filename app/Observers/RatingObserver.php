<?php

namespace App\Observers;

use App\Actions\Rating\CalculatePlanAveragesAction;
use App\Models\Rating;
use App\Services\StatisticsService;

class RatingObserver
{
    public function __construct(
        private CalculatePlanAveragesAction $calculateAverages,
        private StatisticsService $statisticsService
    ) {}

    /**
     * Handle the Rating "created" event.
     */
    public function created(Rating $rating): void
    {
        $this->calculateAverages->execute($rating->plan);
        $this->invalidateStatisticsCache($rating);
    }

    /**
     * Handle the Rating "updated" event.
     */
    public function updated(Rating $rating): void
    {
        $this->calculateAverages->execute($rating->plan);
        $this->invalidateStatisticsCache($rating);
    }

    /**
     * Handle the Rating "deleted" event.
     */
    public function deleted(Rating $rating): void
    {
        // Get plan before rating is deleted
        $plan = $rating->plan;
        $this->calculateAverages->execute($plan);
        $this->invalidateStatisticsCache($rating);
    }

    /**
     * Invalidate statistics cache when rating changes
     */
    protected function invalidateStatisticsCache(Rating $rating): void
    {
        if ($rating->plan && $rating->plan->couple) {
            $this->statisticsService->invalidateCoupleCache($rating->plan->couple);
        }
        
        if ($rating->user) {
            $this->statisticsService->invalidateUserCache($rating->user);
        }
    }
}

