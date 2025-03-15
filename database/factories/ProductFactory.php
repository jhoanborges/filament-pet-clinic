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
        return [
            'image'       => $this->faker->imageUrl(400, 400, 'products'), // Imagen aleatoria
            'name'        => $this->faker->words(3, true),
            'price'       => $this->faker->randomFloat(2, 5, 500), // Precio entre 5 y 500
            'sku'         => strtoupper(Str::random(10)), // SKU aleatorio
            'description' => $this->faker->sentence(),
            'clinic_id'   => Clinic::inRandomOrder()->value('id') ?? null, // Asigna una clínica aleatoria si hay
        ];
    }
}
