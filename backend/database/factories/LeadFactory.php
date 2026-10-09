<?php

namespace Database\Factories;

use App\Models\Lead;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lead>
 */
class LeadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_name' => fake()->company(),
            'customer_company' => fake()->company(),
            'deadline' => fake()->dateTimeBetween('now', '+3 months')->format('Y-m-d'),
        ];
    }
}
