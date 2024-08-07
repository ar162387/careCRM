<?php

namespace Database\Seeders;

use App\Models\ServiceRepresentative;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceRepresentativesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ServiceRepresentative::factory(10)->create();
    }
}
