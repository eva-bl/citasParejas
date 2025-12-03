<?php

namespace App\Actions\Statistics;

use App\Models\Couple;
use App\Services\StatisticsService;

class GetCoupleStatisticsAction
{
    protected StatisticsService $statisticsService;
    
    public function __construct(StatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }
    
    /**
     * Ejecutar la acción para obtener estadísticas de pareja
     * 
     * @param Couple $couple
     * @return array
     */
    public function execute(Couple $couple): array
    {
        return $this->statisticsService->getCoupleStatistics($couple);
    }
}




