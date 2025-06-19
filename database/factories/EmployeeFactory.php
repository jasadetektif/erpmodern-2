<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
{
    return [
        'user_id' => null, // Kita akan link manual nanti jika perlu
        'employee_id_number' => 'EMP-' . $this->faker->unique()->randomNumber(5),
        'position' => $this->faker->randomElement(['Mandor', 'Tukang Besi', 'Tukang Kayu', 'Staf Proyek']),
        'join_date' => $this->faker->date(),
        'status' => 'Aktif',
        'basic_salary' => $this->faker->randomElement([3000000, 4500000, 5000000]),
    ];
}
}
