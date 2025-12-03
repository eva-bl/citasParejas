<?php

namespace Database\Factories;

use App\Models\Plan;
use App\Models\Couple;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plan>
 */
class PlanFactory extends Factory
{
    protected $model = Plan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'couple_id' => Couple::factory(),
            'title' => $this->faker->sentence(3),
            'date' => $this->faker->dateTimeBetween('-1 year', '+1 month'),
            'category_id' => Category::factory(),
            'location' => $this->faker->optional()->address(),
            'cost' => $this->faker->optional()->randomFloat(2, 10, 500),
            'description' => $this->faker->optional()->paragraph(),
            'created_by' => User::factory(),
            'status' => $this->faker->randomElement(['pending', 'completed']),
            'overall_avg' => null,
            'fun_avg' => null,
            'emotional_connection_avg' => null,
            'organization_avg' => null,
            'value_for_money_avg' => null,
            'ratings_count' => 0,
            'photos_count' => 0,
            'last_rated_at' => null,
        ];
    }

    /**
     * Indicate that the plan is completed
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
        ]);
    }

    /**
     * Indicate that the plan is pending
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }
}




