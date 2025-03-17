<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClinicOrder>
 */
class ClinicOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id'   => Order::inRandomOrder()->value('id') ?? null, // Asigna una clínica aleatoria si hay
            'clinic_id'   => Clinic::inRandomOrder()->value('id') ?? null, // Asigna una clínica aleatoria si hay
        ];
    }
}
