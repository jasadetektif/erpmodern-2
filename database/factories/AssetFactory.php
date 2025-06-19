<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Asset>
 */
class AssetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
{
    return [
        'asset_code' => 'AST-' . $this->faker->unique()->randomNumber(5),
        'name' => $this->faker->randomElement(['Excavator', 'Dump Truck', 'Bulldozer', 'Genset']),
        'purchase_date' => $this->faker->date(),
        'purchase_price' => $this->faker->numberBetween(100000000, 1500000000),
        'status' => 'Tersedia',
    ];
}
}
