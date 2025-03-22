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
        $faker = \Faker\Factory::create();
        $faker->addProvider(new \Smknstd\FakerPicsumImages\FakerPicsumImagesProvider($faker));

        return [
            'name' => fake()->firstName(),
            'date_of_birth' => fake()->date(),
            'type' => 'dog',
            'avatar' => $faker->imageUrl(400, 400),
            'client_id' => Client::inRandomOrder()->first()->id,

        ];
    }

}
