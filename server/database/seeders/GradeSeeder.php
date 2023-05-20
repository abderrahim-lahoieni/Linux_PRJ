<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Grade;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Generate fake data
        Grade::factory(10)->create();
        
        // Exemple d'insertion de grades
        Grade::create([
            'designation' => 'PA',
            'charge_statutaire' => 240,
            'taux_horaire_vacation' =>440,
        ]);

        Grade::create([
            'designation' => 'PH',
            'charge_statutaire' => 200,
            'taux_horaire_vacation' => 400,
        ]);
        Grade::create([
            'designation' => 'PES',
            'charge_statutaire' => 190,
            'taux_horaire_vacation' => 390,
        ]);
        //Peuplement de Hajar
        Grade::create([
            'designation' => 'PA',
            'charge_statutaire' => 300,
            'taux_horaire_vacation' => 240
        ]);
        Grade::create([
            'designation' => 'PH',
            'charge_statutaire' => 400,
            'taux_horaire_vacation' => 200
        ]);
        Grade::create([
            'designation' => 'PES',
            'charge_statutaire' => 500,
            'taux_horaire_vacation' => 190
        ]);

        // Ajoutez d'autres enregistrements de grades si nécessaire
    }
}
