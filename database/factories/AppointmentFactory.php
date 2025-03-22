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

        // Get or create a doctor
        $doctor = User::whereHas('role', function ($query) {
            $query->where('name', 'doctor');
        })->inRandomOrder()->first();

        if (!$doctor) {
            $doctor = User::factory()->create([
                'role_id' => \App\Models\Role::where('name', 'doctor')->first()->id
            ]);
        }

        // Get or create a client
        $client = User::whereHas('role', function ($query) {
            $query->where('name', 'owner');
        })->inRandomOrder()->first();

        if (!$client) {
            $client = User::factory()->create([
                'role_id' => \App\Models\Role::where('name', 'owner')->first()->id
            ]);
        }

        // Get or create a pet for the client
        $pet = Pet::where('client_id', $client->id)->inRandomOrder()->first();
        if (!$pet) {
            $pet = Pet::factory()->create([
                'client_id' => $client->id
            ]);
        }

        // Get or create a clinic
        $clinic = Clinic::inRandomOrder()->first();
        if (!$clinic) {
            $clinic = Clinic::factory()->create();
        }

        return [
            'description' => $this->faker->sentence(), // e.g., "Routine checkup for pet"
            'pet_id' => $pet->id,
            'start_time' => $startTime,
            'clinic_id' => $clinic->id,
            'doctor_id' => $doctor->id,
            'date' => Carbon::now()->addDays($this->faker->numberBetween(1, 30)),
            'end_time' => $endTime,
            'order_column' => $this->faker->optional()->randomNumber(3), // Nullable, random 3-digit number
            'sort_when_creating' => $this->faker->boolean(20), // 20% chance of true
            'created_at' => $this->faker->dateTimeThisYear(),
            'updated_at' => $this->faker->dateTimeThisYear(),
            'status' => AppointmentStatus::Created
        ];
    }
}
