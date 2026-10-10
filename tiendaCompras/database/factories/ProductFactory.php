<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = Str::title(fake()->unique()->words(3, true));

        return [
            'name' => $name,
            'sku' => 'AZUL-'.strtoupper(fake()->unique()->bothify('###??')),
            'category' => fake()->randomElement(['Computación', 'Audio', 'Wearables', 'Móviles', 'Fotografía']),
            'price' => fake()->randomFloat(2, 10, 2000),
            'icon' => 'phone',
            'slug' => Str::slug($name),
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }
}
