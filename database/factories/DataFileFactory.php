<?php

namespace Database\Factories;

use App\Models\Area;
use App\Models\Component;
use App\Models\DataFile;
use App\Models\Device;
use App\Models\Inspection;
use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\Factory;

class DataFileFactory extends Factory
{
    protected $model = DataFile::class;

    public function definition()
    {
        return [
            'file_name' => $this->faker->word . '.csv',
            'file_path' => $this->faker->filePath(),
            'component_id' => Component::inRandomOrder()->first()->id,
            'device_id' => Device::inRandomOrder()->first()->id,
            'area_id' => Area::inRandomOrder()->first()->id,
            'inspection_id' => Inspection::inRandomOrder()->first()->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
