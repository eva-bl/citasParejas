<?php

namespace Database\Factories;

use App\Models\Badge;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Badge>
 */
class BadgeFactory extends Factory
{
    protected $model = Badge::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->sentence(),
            'icon' => $this->faker->randomElement(['🏆', '⭐', '🎯', '💎', '🌟']),
            'criteria' => [
                'type' => 'total_completed_plans',
                'value' => $this->faker->numberBetween(1, 10),
            ],
        ];
    }
}


