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

    //Generate 10 Administrator using factory
    public function run(): void
    {
        Administrateur::factory(10)->create();

    }
}
