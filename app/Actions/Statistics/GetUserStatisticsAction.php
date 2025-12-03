<?php

namespace App\Actions\Statistics;

use App\Models\User;
use App\Services\StatisticsService;

class GetUserStatisticsAction
{
    protected StatisticsService $statisticsService;
    
    public function __construct(StatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }
    
    /**
     * Ejecutar la acción para obtener estadísticas de usuario
     * 
     * @param User $user
     * @return array
     */
    public function execute(User $user): array
    {
        return $this->statisticsService->getUserStatistics($user);
    }
}



