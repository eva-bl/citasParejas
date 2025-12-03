<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badges = [
            [
                'name' => 'Primera Cita',
                'description' => 'Completa tu primer plan juntos',
                'icon' => '💑',
                'criteria' => [
                    'type' => 'total_completed_plans',
                    'value' => 1,
                    'timeframe' => 'all_time',
                ],
            ],
            [
                'name' => 'Exploradores',
                'description' => 'Completa 10 planes juntos',
                'icon' => '🗺️',
                'criteria' => [
                    'type' => 'total_completed_plans',
                    'value' => 10,
                    'timeframe' => 'all_time',
                ],
            ],
            [
                'name' => 'Gourmets',
                'description' => 'Completa 5 planes de categoría "Cena"',
                'icon' => '🍷',
                'criteria' => [
                    'type' => 'completed_plans_by_category',
                    'category' => 'Cena',
                    'value' => 5,
                ],
            ],
            [
                'name' => 'Alta Valoración',
                'description' => 'Obtén una valoración de 4.5 o más en un plan',
                'icon' => '⭐',
                'criteria' => [
                    'type' => 'high_rating_plan',
                    'value' => 4.5,
                ],
            ],
            [
                'name' => 'Consistencia',
                'description' => 'Completa al menos 1 plan por mes durante 6 meses',
                'icon' => '📅',
                'criteria' => [
                    'type' => 'monthly_consistency',
                    'value' => 6,
                ],
            ],
            [
                'name' => 'Aventureros',
                'description' => 'Completa 3 planes de categoría "Aventura"',
                'icon' => '🏔️',
                'criteria' => [
                    'type' => 'completed_plans_by_category',
                    'category' => 'Aventura',
                    'value' => 3,
                ],
            ],
        ];

        foreach ($badges as $badge) {
            Badge::firstOrCreate(
                ['name' => $badge['name']],
                $badge
            );
        }
    }
}




