<?php

namespace App\Actions\Plan;

use App\Models\Couple;
use App\Models\Plan;
use Carbon\Carbon;

class GetPlanOfTheYearAction
{
    /**
     * Obtener el mejor plan del año para una pareja
     * 
     * @param Couple $couple
     * @param int|null $year Año específico, null para año actual
     * @return Plan|null
     */
    public function execute(Couple $couple, ?int $year = null): ?Plan
    {
        $year = $year ?? now()->year;
        
        $startOfYear = Carbon::create($year, 1, 1)->startOfYear();
        $endOfYear = Carbon::create($year, 12, 31)->endOfYear();
        
        return Plan::where('couple_id', $couple->id)
            ->where('status', 'completed')
            ->whereBetween('date', [$startOfYear, $endOfYear])
            ->whereNotNull('overall_avg')
            ->orderBy('overall_avg', 'desc')
            ->orderBy('ratings_count', 'desc')
            ->orderBy('date', 'desc')
            ->first();
    }
}

