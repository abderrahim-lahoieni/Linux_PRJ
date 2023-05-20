<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Grade>
 */
class GradeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    //Define how to generate fake data for Grade table.
    public function definition(): array
    {
        return [
            'designation' => fake()->text(20),
            'charge_statutaire' => fake()->numberBetween(0,200),
            'taux_horaire_vacation' => fake()->numberBetween(0,200)
        ];
    }
}
