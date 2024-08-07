<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Transducer extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('transducers')->insert([
            [
                'title'=> 'Spectral',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'=> 'Time synchronous',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ]);
    }
}
