<?php

namespace Database\Seeders;

use App\Models\PlantServiceRep;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlantServiceRepsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PlantServiceRep::factory(20)->create();
    }
}
