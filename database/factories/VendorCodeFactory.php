<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vendor>
 */
class VendorCodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'vendor_code' => fake()->numberBetween(1, 14),
            'name' => 'Профиль опорный - нижний на 3 стекла',
            'img' => 'https://random.imagecdn.app/300/150',
            'price' => 800,
            'unit' => 'м.п.'
        ];
    }
}
