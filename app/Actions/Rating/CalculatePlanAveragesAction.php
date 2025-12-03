<?php

namespace App\Actions\Rating;

use App\Models\Plan;
use Illuminate\Support\Facades\DB;

class CalculatePlanAveragesAction
{
    /**
     * Calculate and update cached averages for a plan
     */
    public function execute(Plan $plan): void
    {
        $ratings = $plan->ratings;

        if ($ratings->isEmpty()) {
            // No ratings, set all to null
            $plan->update([
                'overall_avg' => null,
                'fun_avg' => null,
                'emotional_connection_avg' => null,
                'organization_avg' => null,
                'value_for_money_avg' => null,
                'ratings_count' => 0,
                'last_rated_at' => null,
            ]);
            return;
        }

        // Calculate averages
        $averages = [
            'overall_avg' => round($ratings->avg('overall'), 2),
            'fun_avg' => round($ratings->avg('fun'), 2),
            'emotional_connection_avg' => round($ratings->avg('emotional_connection'), 2),
            'organization_avg' => round($ratings->avg('organization'), 2),
            'value_for_money_avg' => round($ratings->avg('value_for_money'), 2),
            'ratings_count' => $ratings->count(),
            'last_rated_at' => $ratings->max('created_at'),
        ];

        $plan->update($averages);
    }
}




