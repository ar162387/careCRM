<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inspection>
 */
class InspectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'type' => fake()->randomElement(['visit', 'remote']),
            'scheduled_at' => fake()->dateTimeBetween('-1 week', '+1 week'),
            'visitor_name' => fake()->name(),
            'taken_up' => fake()->boolean(),
        ];
    }
}
