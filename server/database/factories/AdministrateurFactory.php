<?php

namespace Database\Factories;

use App\Models\Etablissement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Administrateur>
 */
class AdministrateurFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    //Define how to generate fake data for Administateur table.
    public function definition(): array
    {
        return [

            'ppr' => fake()->unique()->randomNumber(),
            'nom' => fake()->lastname(),
            'prenom' => fake()->firstName(),
            'etablissement_id' => Etablissement::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id
            
        ];
    }
}
