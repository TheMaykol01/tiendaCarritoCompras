<?php

namespace Database\Factories;

use App\Models\Batch;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Batch>
 */
class BatchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'expiry_date' => fake()->dateTimeBetween('now', '+2 years')->format('Y-m-d'),
            'quantity' => fake()->numberBetween(1, 200),
        ];
    }
}
