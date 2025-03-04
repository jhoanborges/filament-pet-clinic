<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\Pet;
use App\Models\Appointment;
use App\Enums\AppointmentStatus;
use App\Models\Clinic;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startTime = $this->faker->dateTimeBetween('now', '+1 month'); // Random future date within 1 month
        $endTime = Carbon::instance($startTime)->addMinutes($this->faker->numberBetween(15, 60)); // End time 15-60 mins later

        $doctor = User::whereHas('role', function ($query) {
            $query->where('name', 'doctor');
        })->inRandomOrder()->first();

        return [

            'description' => $this->faker->sentence(), // e.g., "Routine checkup for pet"
            'pet_id' => Pet::factory(), // Creates or references a Pet
            'start_time' => $startTime,
            'clinic_id' => Clinic::inRandomOrder()->first()->id,
            'doctor_id' => $doctor->id,
            'date' => Carbon::now(),
            'end_time' => $endTime,
            'order_column' => $this->faker->optional()->randomNumber(3), // Nullable, random 3-digit number
            'sort_when_creating' => $this->faker->boolean(20), // 20% chance of true
            'created_at' => $this->faker->dateTimeThisYear(),
            'updated_at' => $this->faker->dateTimeThisYear(),

            'status' => AppointmentStatus::Created
        ];
    }
}
