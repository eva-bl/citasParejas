<?php

namespace Database\Factories;

use App\Models\Couple;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Couple>
 */
class CoupleFactory extends Factory
{
    protected $model = Couple::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'join_code' => Couple::generateJoinCode(),
        ];
    }
}





