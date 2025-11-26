<?php

namespace App\Filament\Widgets;

use App\Models\Badge;
use App\Models\Couple;
use App\Models\Photo;
use App\Models\Plan;
use App\Models\Rating;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Usuarios', User::count())
                ->description('Usuarios registrados')
                ->descriptionIcon('heroicon-o-users')
                ->color('primary'),
            
            Stat::make('Total Parejas', Couple::count())
                ->description('Parejas activas')
                ->descriptionIcon('heroicon-o-heart')
                ->color('success'),
            
            Stat::make('Total Planes', Plan::count())
                ->description('Planes creados')
                ->descriptionIcon('heroicon-o-calendar')
                ->color('warning'),
            
            Stat::make('Total Valoraciones', Rating::count())
                ->description('Valoraciones realizadas')
                ->descriptionIcon('heroicon-o-star')
                ->color('info'),
            
            Stat::make('Total Fotos', Photo::count())
                ->description('Fotos subidas')
                ->descriptionIcon('heroicon-o-photo')
                ->color('danger'),
            
            Stat::make('Total Insignias', Badge::count())
                ->description('Insignias disponibles')
                ->descriptionIcon('heroicon-o-trophy')
                ->color('gray'),
        ];
    }
}
