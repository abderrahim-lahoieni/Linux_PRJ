<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;

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
    }
}
