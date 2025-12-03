<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
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
        ];

        $category = $this->faker->randomElement($categories);

        return [
            'name' => $category['name'],
            'icon' => $category['icon'],
            'color' => $category['color'],
        ];
    }
}




