<?php

namespace Database\Factories;

use App\Models\Pet;
use App\Enums\PetType;
use App\Models\Client;
use App\Models\Clinic;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pet>
 */
class PetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'date_of_birth' => fake()->date(),
            'type' => 'dog',
            'avatar' => 'avatar.png',
            'client_id' => Client::inRandomOrder()->first()->id,

        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (Pet $user) {
            // ...
        })->afterCreating(function (Pet $pet) {
            DB::table('clinic_pet')->insert([
                'clinic_id' => Clinic::inRandomOrder()->first()->id,
                'pet_id' => $pet->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }

    
}
