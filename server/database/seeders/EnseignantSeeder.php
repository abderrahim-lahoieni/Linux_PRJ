<?php
use Illuminate\Database\Seeder;
use App\Models\Enseignant;
use App\Models\User;
use App\Models\Etablissement;
use App\Models\Grade;
use Carbon\Carbon;

class EnseignantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Création de l'utilisateur associé à l'enseignant
        $user = User::create([
            'name' => 'Dahrabou',
            'email' => 'doha.dahrabou@etu.uae.ac.ma',
            'password' => bcrypt('2001@'),
            'type' => 'Enseignant',
        ]);

        // Récupération de l'établissement "Ecole National des sciences appliquées"
        $etablissement = Etablissement::where('nom', 'Ecole National des sciences appliquées')->first();

        // Récupération du grade "PA"
        $grade = Grade::where('designation', 'PA')->first();

        // Création de l'enseignant
        Enseignant::create([
            'nom' => 'Dahrabou',
            'prenom' => 'Doha',
            'ppr' => '145239',
            'email' => 'doha.dahrabou@etu.uae.ac.ma',
            'telephone' => '0610532179',
            'date_naissance' => Carbon::parse('2007-06-07'),
            'id_user' => $user->id,
            'id_etablissement' => $etablissement->id,
            'id_grade' => $grade->id,
        ]);
    }
}
