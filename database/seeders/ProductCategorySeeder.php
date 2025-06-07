<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create pet clinic related categories
        $categories = [
            ['name' => 'Medications', 'description' => 'Prescription medications for various pet health conditions'],
            ['name' => 'Specialty Diet Food', 'description' => 'Specialized nutrition for pets with specific health needs'],
            ['name' => 'Dental Care', 'description' => 'Products for maintaining oral hygiene in pets'],
            ['name' => 'Parasite Control', 'description' => 'Products for flea, tick, and worm prevention and treatment'],
            ['name' => 'Supplements & Vitamins', 'description' => 'Nutritional supplements to support pet health'],
            ['name' => 'Senior Pet Care', 'description' => 'Specialized products for aging pets'],
        ];

        foreach ($categories as $category) {
            ProductCategory::create($category);
        }
    }
}
