<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Brands;
use App\Models\Categories;
use App\Models\Products;
use App\Models\ProductVariants;
use App\Models\Sizes;
use Illuminate\Support\Str;

class BrandProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = Brands::all();
        $categories = Categories::all();

        if ($categories->isEmpty()) {
            $this->command->error('No categories found. Please seed categories first.');
            return;
        }

        $productPrefixes = [
            'Premium', 'Limited Edition', 'Luxury', 'Authentic', 'Vintage', 
            'Classic', 'Modern', 'Essential', 'Grand', 'Elite'
        ];

        $productSuffixes = [
            'Collection', 'Line', 'Series', 'Style', 'Selection'
        ];

        foreach ($brands as $brand) {
            $currentCount = $brand->products()->count();
            $needed = 4 - $currentCount;

            if ($needed > 0) {
                $this->command->info("Brand: {$brand->name} has {$currentCount} products. Adding {$needed} more.");

                for ($i = 0; $i < $needed; $i++) {
                    $category = $categories->random();
                    $name = $productPrefixes[array_rand($productPrefixes)] . ' ' . $brand->name . ' ' . $category->name . ' ' . $productSuffixes[array_rand($productSuffixes)] . ' ' . ($currentCount + $i + 1);
                    $slug = Str::slug($name) . '-' . Str::random(5);

                    $product = Products::create([
                        'category_id' => $category->id,
                        'brand_id' => $brand->id,
                        'name' => $name,
                        'slug' => $slug,
                        'description' => "This is a seeded product for brand {$brand->name}. High quality material and craftsmanship.",
                        'base_price' => rand(200, 2000) * 1000,
                        'is_active' => true,
                        'image' => 'https://via.placeholder.com/600x800?text=' . urlencode($name),
                    ]);

                    // Get sizes for this category
                    $validSizes = $category->sizes;
                    if ($validSizes->isEmpty()) {
                        // Fallback to all sizes if category has no linked sizes
                        $validSizes = Sizes::all();
                    }

                    if ($validSizes->isNotEmpty()) {
                        // Create 1-3 variants
                        $numVariants = rand(1, min(3, $validSizes->count()));
                        $selectedSizes = $validSizes->random($numVariants);

                        foreach ($selectedSizes as $size) {
                            ProductVariants::create([
                                'product_id' => $product->id,
                                'size_id' => $size->id,
                                'stock_quantity' => rand(10, 100),
                                'price' => null, // use base_price
                                'sku' => strtoupper($brand->slug . '-' . Str::random(4) . '-' . $size->name),
                            ]);
                        }
                    } else {
                        // No sizes available at all? Just create a variant without size if allowed or log error
                        $this->command->warn("No sizes found for product {$product->name}");
                    }
                }
            } else {
                $this->command->info("Brand: {$brand->name} already has {$currentCount} products. Skipping.");
            }
        }
    }
}
