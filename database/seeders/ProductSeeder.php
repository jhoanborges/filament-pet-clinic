<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Number of products to create per type
     */
    protected int $productsPerType = 50;
    
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Make sure categories exist before creating products
        $this->call(ProductCategorySeeder::class);
        
        // Get all category IDs
        $categoryIds = ProductCategory::pluck('id')->toArray();
        
        // Use database transaction for better performance
        DB::transaction(function () use ($categoryIds) {
            // Create cat products in smaller batches
            $this->createProductsInBatches('cat', $categoryIds);
            
            // Create dog products in smaller batches
            $this->createProductsInBatches('dog', $categoryIds);
        });
        
        $this->command->info("Created {$this->productsPerType} cat products and {$this->productsPerType} dog products successfully.");
    }
    
    /**
     * Create products in smaller batches for better performance
     * 
     * @param string $type 'cat' or 'dog'
     * @param array $categoryIds Available category IDs
     * @return void
     */
    protected function createProductsInBatches(string $type, array $categoryIds): void
    {
        $batchSize = 10;
        $batches = ceil($this->productsPerType / $batchSize);
        
        for ($i = 0; $i < $batches; $i++) {
            // Calculate how many to create in this batch
            $remaining = $this->productsPerType - ($i * $batchSize);
            $count = min($batchSize, $remaining);
            
            // Create batch of products
            $factory = Product::factory($count)->{$type}();
            $products = $factory->create();
            
            // Assign random categories to products
            foreach ($products as $product) {
                $product->category_id = $categoryIds[array_rand($categoryIds)];
                $product->save();
            }
            
            // Show progress
            $this->command->getOutput()->write('.');
        }
        
        $this->command->getOutput()->writeln('');
    }
}
