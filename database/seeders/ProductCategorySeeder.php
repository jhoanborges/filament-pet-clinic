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
            ['name' => 'Prescription Medications', 'description' => 'Prescription medications for various pet health conditions'],
            ['name' => 'Preventive Care', 'description' => 'Products for preventing diseases and maintaining health'],
            ['name' => 'Specialty Diet Food', 'description' => 'Specialized nutrition for pets with specific health needs'],
            ['name' => 'Dental Care', 'description' => 'Products for maintaining oral hygiene in pets'],
            ['name' => 'Recovery & Rehabilitation', 'description' => 'Products to aid in post-surgery or injury recovery'],
            ['name' => 'Diagnostic Tools', 'description' => 'Home diagnostic kits for pet owners'],
            ['name' => 'Parasite Control', 'description' => 'Products for flea, tick, and worm prevention and treatment'],
            ['name' => 'Supplements & Vitamins', 'description' => 'Nutritional supplements to support pet health'],
            ['name' => 'Grooming & Hygiene', 'description' => 'Professional-grade grooming and hygiene products'],
            ['name' => 'Medical Supplies', 'description' => 'Bandages, syringes, and other medical supplies'],
            ['name' => 'Training & Behavior', 'description' => 'Products to assist with pet training and behavior modification'],
            ['name' => 'Senior Pet Care', 'description' => 'Specialized products for aging pets'],
        ];

        foreach ($categories as $category) {
            ProductCategory::create($category);
        }
    }
}
