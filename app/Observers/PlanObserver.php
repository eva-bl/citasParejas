<?php

namespace App\Observers;

use App\Models\Plan;
use Illuminate\Support\Facades\Cache;

class PlanObserver
{
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
        if ($plan->couple_id) {
            Cache::tags(['couple_stats', "couple_{$plan->couple_id}"])->flush();
        }
    }
}

