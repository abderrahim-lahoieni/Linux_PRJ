<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /* $this->call(EtablissementSeeder::class);
        $this->call(EnseignantSeeder::class);
        $this->call(GradeSeeder::class);
        $this->call(AdministrateurSeeder::class); */
        
        User::factory(20)->create();
        
        DB::table('users')->insert([
            'name' => 'Aimane',
            'email' => 'achanaa999@gmail.com',
            'password' => Hash::make('123456'),
            'type' => 'Administrateur'
        ]);
        
        DB::table('users')->insert([
            'name' => 'Achraf',
            'email' => 'achraf99@gmail.com',
            'password' => Hash::make('123456'),
            'type' => 'Directeur'
        ]);
    }
}
