<?php

namespace App\Observers;

use App\Models\Plan;
use App\Services\StatisticsService;

class PlanObserver
{
    public function __construct(
        private StatisticsService $statisticsService
    ) {}

    /**
     * Handle the Plan "created" event.
     */
    public function created(Plan $plan): void
    {
        $this->invalidateCache($plan);
    }

    /**
     * Handle the Plan "updated" event.
     */
    public function updated(Plan $plan): void
    {
        $this->invalidateCache($plan);
    }

    /**
     * Handle the Plan "deleted" event.
     */
    public function deleted(Plan $plan): void
    {
        $this->invalidateCache($plan);
    }

    /**
     * Handle the Plan "restored" event.
     */
    public function restored(Plan $plan): void
    {
        $this->invalidateCache($plan);
    }

    /**
     * Invalidate cache for couple statistics
     */
    protected function invalidateCache(Plan $plan): void
    {
        if ($plan->couple) {
            $this->statisticsService->invalidateCoupleCache($plan->couple);
        }
        
        if ($plan->createdBy) {
            $this->statisticsService->invalidateUserCache($plan->createdBy);
        }
    }
}

