<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Meal>
 */
class MealFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'price' => fake()->randomFloat(2, 20, 150),
            'description' => fake()->words(3, true),
            'available_quantity' => fake()->numberBetween(10, 50),
            'discount' => fake()->randomFloat(2, 0, 20), 
        ];
    }
}
