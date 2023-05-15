<?php
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(EtablissementSeeder::class);
        $this->call(EnseignantSeeder::class);
        $this->call(GradeSeeder::class);
        $this->call(AdministrateurSeeder::class);
    }
}
