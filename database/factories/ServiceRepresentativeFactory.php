<?php

namespace Database\Factories;

use App\Models\ServiceRepresentative;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceRepresentativeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ServiceRepresentative::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'service_rep_name' => $this->faker->name,
            'address' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'state' => $this->faker->state,
            'zip' => $this->faker->postcode,
            'country' => $this->faker->country,
            'contact_name' => $this->faker->name,
            'contact_title' => $this->faker->jobTitle,
            'phone_number' => $this->faker->phoneNumber,
            'alt_phone_number' => $this->faker->optional()->phoneNumber,
            'fax_number' => $this->faker->optional()->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
        ];
    }
}
