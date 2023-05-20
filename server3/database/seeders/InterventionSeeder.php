<?php

namespace Database\Seeders;
use App\Models\Intervention;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InterventionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Intervention::factory(10)->create();
    }
}
