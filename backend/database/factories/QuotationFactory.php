<?php

namespace Database\Factories;

use App\Models\Lead;
use App\Models\Quotation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Quotation>
 */
class QuotationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'lead_id' => Lead::factory(),
            'revision' => 1,
            'status' => 'draft',
            'currency' => 'IDR',
            'customer_name' => fake()->company(),
            'subtotal' => '0.00',
            'total' => '0.00',
        ];
    }
}
