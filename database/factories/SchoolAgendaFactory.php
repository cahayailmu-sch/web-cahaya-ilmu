<?php

namespace Database\Factories;

use App\Models\SchoolAgenda;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SchoolAgenda>
 */
class SchoolAgendaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'level' => fake()->randomElement(['Umum', 'TK', 'SD', 'SMP', 'SMA']),
            'event_date' => now()->addDays(fake()->numberBetween(1, 45))->toDateString(),
            'time_label' => '08.00 – 11.30 WITA',
            'location' => 'Aula Utama Cahaya Ilmu',
            'is_published' => true,
        ];
    }
}
