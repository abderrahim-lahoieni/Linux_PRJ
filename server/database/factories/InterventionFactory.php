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
            'annee_univ' => fake()->year($max = 'now') ,
            'semestre' => fake()->numerify('S#'), 
            'date_debut' => fake()->date($format = 'Y/m/d', $max = 'now') ,
            'date_fin' => fake()->date($format = 'Y/m/d', $max = 'now') ,
            'nbr_heures' => fake()->numberBetween($min = 100, $max = 250),
            'id_intervenant' => Enseignant::inRandomOrder()->first()->id ,
            'id_etab' => Etablissement::inRandomOrder()->first()->id ,
            'visa_etb' => fake()->boolean(),
            'visa_uae' => fake()->boolean()
        ];
    }
}
