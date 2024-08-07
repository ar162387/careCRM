<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Sensitivity extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('sensitivities')->insert([
            [
                'value' => 0.1,
                'unit' => 'in',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'value' => 0.2,
                'unit' => 'in/s',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
