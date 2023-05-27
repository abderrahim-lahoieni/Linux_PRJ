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
            'brut' => fake()->randomFloat($nbMaxDecimals = 3, $min = 0, $max = 9000),
            'ir' => fake()->randomFloat($nbMaxDecimals = 3, $min = 1, $max = 30), // ir = Impot sur le revenu
            'net' =>fake()->randomFloat($nbMaxDecimals = 3, $min = 0, $max = 9000),
            'annee_univ' =>fake()->numerify('2020/202#') ,
            'semestre' => fake()->numerify('S#'),
            'id_intervenant' => Enseignant::inRandomOrder()->first()->id,
            'id_etab' => Etablissement::inRandomOrder()->first()->id,
        ];
    }
}
