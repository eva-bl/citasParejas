<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Cena', 'icon' => '🍽️', 'color' => '#FF6B6B'],
            ['name' => 'Cine', 'icon' => '🎬', 'color' => '#4ECDC4'],
            ['name' => 'Aventura', 'icon' => '🏔️', 'color' => '#45B7D1'],
            ['name' => 'Cultura', 'icon' => '🎭', 'color' => '#FFA07A'],
            ['name' => 'Deporte', 'icon' => '⚽', 'color' => '#98D8C8'],
            ['name' => 'Relax', 'icon' => '🧘', 'color' => '#F7DC6F'],
            ['name' => 'Viaje', 'icon' => '✈️', 'color' => '#BB8FCE'],
            ['name' => 'Romántico', 'icon' => '💕', 'color' => '#F1948A'],
            ['name' => 'Música', 'icon' => '🎵', 'color' => '#85C1E2'],
            ['name' => 'Naturaleza', 'icon' => '🌳', 'color' => '#52BE80'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}


