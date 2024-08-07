<?php

namespace Database\Seeders;

use App\Models\DataFile;
use Illuminate\Database\Seeder;

class DataFileSeeder extends Seeder
{
    public function run()
    {
        // Generate 10,000 random files
        DataFile::factory()->count(1000)->create();
    }
}
