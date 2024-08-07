<?php

namespace Database\Seeders;

use App\Models\FactoryServiceRepresentative;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FactoryServiceRepresentativesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FactoryServiceRepresentative::factory(20)->create();
    }
}
