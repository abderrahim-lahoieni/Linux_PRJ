<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Etablissement>
 */
class EtablissementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    //Define how to generate fake data for Etablissement table.
    public function definition(): array
    {
        return [
            'code' => fake()->countryCode(),
            'nom' => fake()->name(),
            'telephone' => fake()->phoneNumber(),
            'faxe' => fake()->ean13(),
            'ville' => fake()->state(), 
            'nbr_enseignants' => fake()->randomNumber(),
            'etat' =>  fake()->boolean()
        ];
    }
}
