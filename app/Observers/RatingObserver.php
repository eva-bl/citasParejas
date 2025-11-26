<?php

namespace App\Observers;

use App\Actions\Rating\CalculatePlanAveragesAction;
use App\Models\Rating;

class RatingObserver
{
    public function __construct(
        private CalculatePlanAveragesAction $calculateAverages
    ) {}

    /**
     * Handle the Rating "created" event.
     */
    public function created(Rating $rating): void
    {
        $this->calculateAverages->execute($rating->plan);
    }

    /**
     * Handle the Rating "updated" event.
     */
    public function updated(Rating $rating): void
    {
        $this->calculateAverages->execute($rating->plan);
    }

    /**
     * Handle the Rating "deleted" event.
     */
    public function deleted(Rating $rating): void
    {
        // Get plan before rating is deleted
        $plan = $rating->plan;
        $this->calculateAverages->execute($plan);
    }
}

