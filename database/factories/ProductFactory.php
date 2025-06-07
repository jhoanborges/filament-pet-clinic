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
     * Base URL for product images
     */
    protected string $imagesBaseUrl = 'https://pet-clinic.hexagun.mx/products_demo_images/';
    
    /**
     * Pet food and product brands
     */
    protected array $petBrands = [
        'Royal Canin', 'Purina Pro Plan', 'Hill\'s Science Diet', 'Purina One', 'Blue Buffalo',
        'Iams', 'Eukanuba', 'Orijen', 'Acana', 'Wellness', 'Merrick', 'Nutro', 'Whiskas',
        'VetriScience', 'NexGard', 'Frontline', 'Revolution', 'Heartgard', 'Advantage',
        'Greenies', 'Zymox', 'Virbac', 'Vetoquinol', 'Nutramax', 'Zoetis'
    ];
    
    /**
     * Cat product name templates
     */
    protected array $catProductTemplates = [
        '{brand} Feline Health Monitoring System',
        '{brand} Hairball Control Formula',
        '{brand} Dental Care Kit for Cats',
        '{brand} Urinary Tract Support Formula',
        '{brand} Indoor Cat Formula',
        '{brand} Senior Cat Wellness Blend',
        '{brand} Kitten Development Formula',
        '{brand} Digestive Care Cat Food',
        '{brand} Feline Calming Solution',
        '{brand} Weight Management System for Cats',
        '{brand} Feline Joint Support Supplement',
        '{brand} Kidney Care Formula',
        '{brand} Skin & Coat Health Supplement',
        '{brand} Feline Oral Health Spray',
        '{brand} Sensitive Stomach Formula',
        '{brand} Feline Diabetes Care',
        '{brand} Prescription Diet for Thyroid Health',
        '{brand} Feline Probiotic Supplement',
        '{brand} Hydration Support Formula',
        '{brand} Feline Dental Treats'
    ];
    
    /**
     * Dog product name templates
     */
    protected array $dogProductTemplates = [
        '{brand} Canine Health Monitoring System',
        '{brand} Hip & Joint Support Formula',
        '{brand} Dental Care Kit for Dogs',
        '{brand} Digestive Health Support',
        '{brand} Large Breed Formula',
        '{brand} Senior Dog Wellness Blend',
        '{brand} Puppy Development Formula',
        '{brand} Sensitive Skin Care System',
        '{brand} Canine Calming Solution',
        '{brand} Weight Management System for Dogs',
        '{brand} Canine Joint Support Supplement',
        '{brand} Heart Health Formula',
        '{brand} Skin & Coat Health Supplement',
        '{brand} Canine Oral Health Spray',
        '{brand} Sensitive Stomach Formula',
        '{brand} Canine Diabetes Care',
        '{brand} Prescription Diet for Liver Health',
        '{brand} Canine Probiotic Supplement',
        '{brand} Hydration Support Formula',
        '{brand} Canine Dental Treats'
    ];
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'image'       => null, // Will be set by cat/dog states
            'name'        => $this->faker->words(3, true),
            'price'       => $this->faker->randomFloat(2, 19.99, 199.99),
            'sku'         => strtoupper(Str::random(10)),
            'description' => $this->faker->paragraph(3),
            'clinic_id'   => Clinic::inRandomOrder()->value('id') ?? null,
        ];
    }
    
    /**
     * Configure the model to use cat images.
     *
     * @return static
     */
    public function cat(): static
    {
        return $this->state(function (array $attributes) {
            // Get random brand and product template
            $brand = $this->petBrands[array_rand($this->petBrands)];
            $template = $this->catProductTemplates[array_rand($this->catProductTemplates)];
            
            // Replace {brand} placeholder with actual brand
            $productName = str_replace('{brand}', $brand, $template);
            
            // Store full URL to the image
            return [
                'image' => $this->imagesBaseUrl . 'cats/1.jpg',
                'name' => $productName,
                'description' => "Professional veterinary-grade $brand product specially formulated for feline health needs. This premium quality product is recommended by veterinarians for optimal cat health and wellness. Suitable for all life stages and breeds."
            ];
        });
    }
    
    /**
     * Configure the model to use dog images.
     *
     * @return static
     */
    public function dog(): static
    {
        return $this->state(function (array $attributes) {
            // Get random brand and product template
            $brand = $this->petBrands[array_rand($this->petBrands)];
            $template = $this->dogProductTemplates[array_rand($this->dogProductTemplates)];
            
            // Replace {brand} placeholder with actual brand
            $productName = str_replace('{brand}', $brand, $template);
            
            // Store full URL to the image
            return [
                'image' => $this->imagesBaseUrl . 'dogs/1.jpg',
                'name' => $productName,
                'description' => "Professional veterinary-grade $brand product specially formulated for canine health needs. This premium quality product is recommended by veterinarians for optimal dog health and wellness. Suitable for all life stages and breeds."
            ];
        });
    }
}
