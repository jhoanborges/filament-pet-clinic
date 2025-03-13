<?php

namespace Database\Factories;

use App\Models\Clinic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName(),
            'lastname' => $this->faker->lastName(),
            'ocupacion' => $this->faker->optional()->jobTitle(), // Nullable field
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->numerify('55########'), // Mexican phone number format (e.g., 5512345678)
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
            'birthday' => $this->faker->optional()->date('Y-m-d', 'now'), // Nullable, random past date
            'street_address' => $this->faker->optional()->streetAddress(), // Nullable
            'colony' => $this->faker->optional()->word(), // Nullable (e.g., "Condesa")
            'city' => $this->faker->optional()->city(), // Nullable
            'municipality' => $this->faker->optional()->word(), // Nullable
            'postal_code' => $this->faker->optional()->numerify('#####'), // Nullable, 5-digit postal code
            'allow_email_notification' => $this->faker->boolean(50), // 50% chance of true
            'created_at' => $this->faker->dateTimeThisYear(),
            'updated_at' => $this->faker->dateTimeThisYear(),
            'clinic_id' => Clinic::inRandomOrder()->first()->id,

        ];
    }
}
