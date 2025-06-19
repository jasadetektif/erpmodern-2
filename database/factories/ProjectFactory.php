<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
{
    return [
        'name' => 'Proyek ' . $this->faker->city,
        'client' => $this->faker->company, // Kolom lama
        'budget' => $this->faker->numberBetween(50000000, 2000000000),
        'status' => $this->faker->randomElement(['Baru', 'Berjalan', 'Selesai']),
        'start_date' => now()->subDays($this->faker->numberBetween(10, 30)),
        'end_date' => now()->addDays($this->faker->numberBetween(60, 180)),
    ];
}

}
