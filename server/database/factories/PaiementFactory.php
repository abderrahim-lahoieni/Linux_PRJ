<?php

namespace Database\Factories;

use App\Models\Enseignant;
use App\Models\Etablissement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Paiement>
 */
class PaiementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    //Define how to generate fake data for Paiement table.
    public function definition(): array
    {
        return [
            'vh' => fake()->numberBetween(10,100), // vh : Volume horaire
            'taux_h' => fake()->numberBetween(100,250), //taux_h : taux horaire
            'brut' => fake()->randomFloat($nbMaxDecimals = NULL, $min = 0, $max = NULL),
            'ir' => fake()->randomFloat($nbMaxDecimals = NULL, $min = 1, $max = 30), // ir = Impot sur le revenu
            'net' =>fake()->fake()->randomFloat($nbMaxDecimals = NULL, $min = 0, $max = NULL),
            'annee_univ' =>fake()->numerify('2020/202#') ,
            'semestre' => fake()->numerify('Hello #'),
            'enseignant_id' => Enseignant::inRandomOrder()->first()->id,
            'etablissement_id' => Etablissement::inRandomOrder()->first()->id,
        ];
    }
}
