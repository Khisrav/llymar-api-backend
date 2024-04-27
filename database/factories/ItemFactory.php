<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //add item name, img_url, price, unit and vendor_code
            'name' => fake()->unique()->name(),
            // 'img_url' => fake()->imageUrl(),
            'price' => fake()->randomFloat(2, 100, 1000),
            'unit' => fake()->randomElement(['шт', 'кг', 'л']),
            'vendor_code' => fake()->unique()->numberBetween(1, 20)
        ];
    }
}
