<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FactoryServiceRepresentative>
 */
class FactoryServiceRepresentativeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'factory_id' => fake()->numberBetween(1, 10), // Assuming factories exist
            'service_representative_id' => fake()->numberBetween(1, 10), // Assuming service representatives exist
        ];
    }
}
