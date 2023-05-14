<?php

namespace Database\Factories;

use App\Models\Etablissement;
use App\Models\Grade;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Enseignant>
 */
class EnseignantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    //Define how to generate fake data for Enseignant table.
    public function definition(): array
    {
        return [
            'ppr' => fake()->unique()->randomNumber(), // unique because ppr it's unique for each teacher
            'name' => fake()->name(),
            'prenom' => fake()->firstName(),
            'date_naissance' => fake()->date('Y/m/d','now'),
            'etablissement_id' => Etablissement::inRandomOrder()->first()->id ,
            'grade_id' => Grade::inRandomOrder()->first()->id , 
            'user_id' => User::inRandomOrder()->first()->id
        ];
        
    }
}
