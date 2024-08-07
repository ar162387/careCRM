<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Component>
 */
class ComponentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->word(),
            'type' => fake()->randomElement(['motor', 'pump', 'bearing', 'other']),
            'site_id' => fake()->numberBetween(1, 10), // Assuming 'sites' table exists
        ];
    }
}
