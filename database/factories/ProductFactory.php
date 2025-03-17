<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
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
            'image'       => $faker->imageUrl(400, 400), // Imagen aleatoria
            'name'        => $faker->words(3, true),
            'price'       => $faker->randomFloat(2, 5, 500), // Precio entre 5 y 500
            'sku'         => strtoupper(Str::random(10)), // SKU aleatorio
            'description' => $faker->sentence(),
            'clinic_id'   => Clinic::inRandomOrder()->value('id') ?? null, // Asigna una clínica aleatoria si hay
        ];
    }
}
