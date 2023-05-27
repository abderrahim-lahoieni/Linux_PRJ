<?php

namespace Database\Seeders;
use App\Models\Administrateur;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdministrateurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {
       
        Administrateur::factory(10)->create();
        
        
        Administrateur::create([
            'ppr' => 'AA4456',
            'nom' => 'Chanaa',
            'prenom' => 'Aimane',
            'etablissement_id' => 1,
            'user_id' => 1
        ]);
    } 
}
