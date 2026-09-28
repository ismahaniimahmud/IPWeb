<?php

namespace Database\Factories;

use App\Models\Action;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Action>
 */
class ActionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'action_code' => fake()->unique()->bothify('ACT-##'),
            'reference' => fake()->sentence(3),
            'theme' => fake()->word(),
            'required_action' => fake()->sentence(),
            'owner' => fake()->name(),
            'target' => fake()->sentence(2),
            'due_date' => fake()->dateTimeBetween('-30 days', '+30 days')->format('Y-m-d'),
            'status' => fake()->randomElement(['Selesai', 'Belum disahkan', 'Dalam tindakan', 'Pemantauan berterusan', 'Menunggu pihak luar']),
            'priority' => fake()->randomElement(['Tinggi', 'Sederhana', 'Rendah']),
            'basis' => fake()->word(),
            'progress' => fake()->sentence(),
            'evidence' => null,
            'updated_on' => fake()->date(),
            'source_note' => fake()->sentence(),
            'meeting_date' => fake()->date(),
        ];
    }
}
