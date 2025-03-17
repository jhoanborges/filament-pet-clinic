<?php

namespace Database\Seeders;

use App\Models\ClinicOrder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClinicOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ClinicOrder::factory(300)->create();
    }
}
