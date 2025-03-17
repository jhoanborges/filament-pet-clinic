<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Appointment;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ClinicSeeder::class,
            ClientSeeder::class,
            PetSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            AppointmentSeeder::class,
            ProductSeeder::class,
            OrderSeeder::class,
            ClinicOrderSeeder::class,
        ]);
    }
}
