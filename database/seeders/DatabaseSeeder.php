<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\AdministrateurSeeder;
use Database\Seeders\EnseignantSeeder;
use Database\Seeders\GradeSeeder;
use Database\Seeders\EtablissementSeeder;
use Database\Seeders\InterventionSeeder;
use Database\Seeders\PaiementSeeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('users')->insert([
            'email' => 'achanaa999@gmail.com',
            'password' => Hash::make('12345678'),
            'type' => 'PRESIDENT'
        ]);
        
        User::factory(20)->create();
        
        DB::table('users')->insert([
            'email' => 'achraf99@gmail.com',
            'password' => Hash::make('12345678'),
            'type' => 'ADMINISTRATEUR_UNIV'
        ]);

        DB::table('users')->insert([
            'email' => 'Messi777@gmail.com',
            'password' => Hash::make('12345678'),
            'type' => 'ADMINISTRATEUR_ETA'
        ]);

        DB::table('users')->insert([
            'email' => 'Ronaldo777@gmail.com',
            'password' => Hash::make('12345678'),
            'type' => 'DIRECTEUR'
        ]);

        DB::table('users')->insert([
            'email' => 'Suarez777@gmail.com',
            'password' => Hash::make('12345678'),
            'type' => 'ENSEIGNANT'
        ]);

        $this->call([
            EtablissementSeeder::class,
            GradeSeeder::class,
            EnseignantSeeder::class,
            AdministrateurSeeder::class,
            InterventionSeeder::class,
            PaiementSeeder::class,
        ]);
    }
}
