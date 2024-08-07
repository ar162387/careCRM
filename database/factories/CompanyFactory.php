<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_name' => fake()->company(),
            'address' => fake()->address(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'zip' => fake()->postcode(),
            'country' => fake()->country(),
            'contact_name' => fake()->name(),
            'contact_title' => fake()->jobTitle(),
            'phone_number' => fake()->phoneNumber(),
            'alt_phone_number' => fake()->phoneNumber(),
            'fax_number' => fake()->phoneNumber(),
            'email' => fake()->safeEmail(),
        ];
    }
}
