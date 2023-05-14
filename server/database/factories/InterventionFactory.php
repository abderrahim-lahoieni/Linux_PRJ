<?php

namespace Database\Factories;

use App\Models\Enseignant;
use App\Models\Etablissement;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Intervention>
 */
class InterventionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    //Define how to generate fake data for Intervention table.
    public function definition(): array
    {
        return [
            'intitule_intervention' => fake()->text(20),
            'annee_univ' => fake()->year($max = 'now')  ,
            'email_verified_at' => now(),
            'semestre' => fake()->numerify('Hello #'), 
            'date_debut' => fake()->date($format = 'Y/m/d', $max = 'now') ,
            'date_fin' => fake()->date($format = 'Y/m/d', $max = 'now') ,
            'nbr_heures' => fake()->numberBetween($min = 100, $max = 250),
            'enseignant_id' => Enseignant::inRandomOrder()->first()->id ,
            'etablissement_id' => Etablissement::inRandomOrder()->first()->id ,
            'visa_etb' => fake()->numberBetween(0,1),
            'visa_uae' => fake()->numberBetween(0,1),
        ];
    }
}
