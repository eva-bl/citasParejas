<?php

namespace Database\Factories;

use App\Models\Rating;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rating>
 */
class RatingFactory extends Factory
{
    protected $model = Rating::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'plan_id' => Plan::factory(),
            'user_id' => User::factory(),
            'fun' => $this->faker->numberBetween(1, 5),
            'emotional_connection' => $this->faker->numberBetween(1, 5),
            'organization' => $this->faker->numberBetween(1, 5),
            'value_for_money' => $this->faker->numberBetween(1, 5),
            'overall' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->optional()->sentence(),
        ];
    }

    /**
     * Indicate a high rating
     */
    public function highRating(): static
    {
        return $this->state(fn (array $attributes) => [
            'fun' => $this->faker->numberBetween(4, 5),
            'emotional_connection' => $this->faker->numberBetween(4, 5),
            'organization' => $this->faker->numberBetween(4, 5),
            'value_for_money' => $this->faker->numberBetween(4, 5),
            'overall' => $this->faker->numberBetween(4, 5),
        ]);
    }
}




