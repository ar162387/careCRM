<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PlantServiceRep>
 */
class PlantServiceRepFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'plant_id' => fake()->numberBetween(1, 10), // Assuming plants exist
            'service_rep_id' => fake()->numberBetween(1, 10), // Assuming service representatives exist
        ];
    }
}
