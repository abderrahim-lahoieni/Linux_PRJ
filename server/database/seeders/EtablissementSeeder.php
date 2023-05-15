<?php 
use Illuminate\Database\Seeder;
use App\Models\Etablissement;

class EtablissementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Exemple d'insertion d'établissements
        Etablissement::create([
            'code' => 'ETAB001',
            'nom' => 'Faculté des lettres et des sciences humaines',
            'telephone' => '+212539979053',
            'faxe' => '+212539979128',
            'ville' => 'Martil',
            
        ]);

        Etablissement::create([
            'code' => 'ETAB002',
            'nom' => 'Faculté des sciences et techniques',
            'telephone' =>'+212539393954',
            'faxe' =>'+212539393953',
            
            
        ]);
     
        Etablissement::create([
            'code' => 'ETAB003',
            'nom' => 'Ecole nationale des sciences appliquées',
            'telephone' =>'',
            'faxe' =>'+212539393744',
            
            
        ]);
        Etablissement::create([
            'code' => 'ETAB004',
            'nom' => 'Ecole nationale des sciences appliquées',
            'telephone' =>'+212539805712',
            'faxe' =>'+212539805713',
            'ville' => 'AL-Hoceima',
            
        ]);
      
        Etablissement::create([
            'code' => 'ETAB005',
            'nom' => 'Ecole nationalede commerce et de gestion de Tanger',
            'telephone' =>'+212539313487',
            'faxe' =>'+212539313488',
            'ville' => 'AL-Hoceima',
            
        ]);
        

Etablissement::create([
    'code' => 'ETAB006',
    'nom' => 'Faculté polydisciplinaire',
    'telephone' =>'+212539523960',
    'faxe' =>'+212539523961',
    'ville' => 'Larache',
   
]);

        // Ajoutez d'autres enregistrements d'établissements si nécessaire
    }
}
