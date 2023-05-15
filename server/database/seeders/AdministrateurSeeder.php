<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Administrateur;

class AdministrateurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   
        public function run()
        {
            // Création de l'utilisateur associé à l'administrateur
            $user = User::create([
                'name' => 'AdminUniversitaire',
                'email' => 'doha.dahrabou@gmail.com',
                'password' => bcrypt('88888@'),
                'type'=>'AministrateurUniversitaire',
            ]);
    
            // Récupération d'un établissement existant
            $etablissement = Etablissement::where('nom', 'Ecole National des sciences appliquées')->first();
    
    
            // Création de l'administrateur
            Enseignant::create([
                'nom' => 'ADMIN',
                'prenom' => 'Admin',
                'ppr'=>'787878',
                'email' => 'doha.dahrabou@gmail.com',
                
                'id_user' => $user->id,
                'id_etablissement' => $etablissement->id,
                
            ]);
    
            
        }
    }
  

