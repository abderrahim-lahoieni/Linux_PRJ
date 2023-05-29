<?php 
namespace Database\Seeders;
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
        //Generate fake data 
        Etablissement::factory(10)->create();
        // Exemple d'insertion d'établissements
        Etablissement::create([
            'code' => 'ETAB001',
            'nom' => 'Faculté des lettres et des sciences humaines',
            'telephone' => '+212539979053',
            'faxe' => '+212539979128',
            'ville' => 'Martil',
            'nbr_enseignants' => 66,
            'etat' => true
        ]);

        Etablissement::create([
            'code' => 'ETAB002',
            'nom' => 'Faculté des sciences et techniques',
            'telephone' =>'+212539393954',
            'faxe' =>'+212539393953',
            'nbr_enseignants' => 90,
            'etat' => true
        ]);
     
        Etablissement::create([
            'code' => 'ETAB003',
            'nom' => 'Ecole nationale des sciences appliquées',
            'telephone' =>'',
            'faxe' =>'+212539393744',
            'nbr_enseignants' => 74 ,
            'etat' => true
        ]);
        Etablissement::create([
            'code' => 'ETAB004',
            'nom' => 'Ecole nationale des sciences appliquées',
            'telephone' =>'+212539805712',
            'faxe' =>'+212539805713',
            'ville' => 'AL-Hoceima',
            'nbr_enseignants' => 50,
            'etat' => true
        ]);
      
        Etablissement::create([
            'code' => 'ETAB005',
            'nom' => 'Ecole nationalede commerce et de gestion de Tanger',
            'telephone' =>'+212539313487',
            'faxe' =>'+212539313488',
            'ville' => 'AL-Hoceima',
            'nbr_enseignants' => 70,
            'etat' => true
        ]);
        

        Etablissement::create([
            'code' => 'ETAB006',
            'nom' => 'Faculté polydisciplinaire',
            'telephone' =>'+212539523960',
            'faxe' =>'+212539523961',
            'ville' => 'Larache',
            'nbr_enseignants' => 88,
            'etat' => true
        ]);

        // Ajoutez d'autres enregistrements d'établissements si nécessaire
    }
}
