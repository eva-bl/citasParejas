<?php

namespace App\Observers;

use App\Jobs\EvaluateUserBadgesJob;
use App\Models\Plan;
use App\Notifications\PlanCreatedNotification;
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
        
        // Notificar a la pareja (excepto al creador)
        if ($plan->couple && $plan->createdBy) {
            $partner = $plan->couple->users()->where('id', '!=', $plan->created_by)->first();
            if ($partner) {
                $partner->notify(new PlanCreatedNotification($plan));
            }
        }
    }

    /**
     * Handle the Plan "updated" event.
     */
    public function updated(Plan $plan): void
    {
        $this->invalidateCache($plan);
        
        // Si el plan cambió a "completed", evaluar badges
        if ($plan->wasChanged('status') && $plan->status === 'completed') {
            $this->evaluateBadgesForCouple($plan);
        }
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
    
    /**
     * Evaluate badges for all users in the couple
     */
    protected function evaluateBadgesForCouple(Plan $plan): void
    {
        if ($plan->couple) {
            foreach ($plan->couple->users as $user) {
                EvaluateUserBadgesJob::dispatch($user);
            }
        }
    }
}

