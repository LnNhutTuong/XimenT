<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Thương hiệu
        $brandsData = [
            ['name' => 'Maison Margiela', 'description' => 'Pháp - Haute Couture'],
            ['name' => 'Guidi', 'description' => 'Ý - Leather Artisans since 1896'],
            ['name' => 'Tornado Mart', 'description' => 'Nhật Bản - Visual Kei & Streetwear'],
            ['name' => 'Rick Owens', 'description' => 'Dark Glamour & Avant-garde'],
            ['name' => 'Saint Laurent', 'description' => 'Luxury Fashion House'],
        ];

        $brands = [];
        foreach ($brandsData as $b) {
            $brands[] = \App\Models\Brands::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($b['name'])],
                ['name' => $b['name'], 'description' => $b['description'], 'is_active' => true]
            );
        }

        // 2. Danh mục
        $categoriesData = [
            ['name' => 'Boots', 'slug' => 'boots'],
            ['name' => 'Leather Jackets', 'slug' => 'leather-jackets'],
            ['name' => 'Denim', 'slug' => 'denim'],
            ['name' => 'Accessories', 'slug' => 'accessories'],
        ];

        $categories = [];
        foreach ($categoriesData as $c) {
            $categories[] = \App\Models\Categories::firstOrCreate(['slug' => $c['slug']], ['name' => $c['name']]);
        }

        // 3. Kích thước
        $shoeSizes = [];
        foreach (range(39, 44) as $s) {
            $shoeSizes[] = \App\Models\Sizes::firstOrCreate(['name' => (string)$s]);
        }

        $clothSizes = [];
        foreach (['S', 'M', 'L', 'XL'] as $s) {
            $clothSizes[] = \App\Models\Sizes::firstOrCreate(['name' => $s]);
        }

        // Liên kết size cho danh mục
        $categories[0]->sizes()->sync(array_column($shoeSizes, 'id')); // Boots
        $categories[1]->sizes()->sync(array_column($clothSizes, 'id')); // Jackets
        $categories[2]->sizes()->sync(array_column($clothSizes, 'id')); // Denim
        $categories[3]->sizes()->sync(array_column($clothSizes, 'id')); // Accessories

        // 4. Sản phẩm (20 cái)
        $productPrefixes = [
            'Tabi', 'Leather', 'Distressed', 'Vintage', 'Artisan', 'Classic', 'Avant-Garde', 'Street', 'Luxury'
        ];
        
        for ($i = 1; $i <= 20; $i++) {
            $brand = $brands[array_rand($brands)];
            $category = $categories[array_rand($categories)];
            $name = $productPrefixes[array_rand($productPrefixes)] . ' ' . $category->name . ' #' . $i;
            $slug = \Illuminate\Support\Str::slug($name) . '-' . time() . '-' . $i;

            $product = \App\Models\Products::create([
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'name' => $name,
                'slug' => $slug,
                'description' => "Mô tả chi tiết cho sản phẩm $name từ thương hiệu {$brand->name}. Chất liệu cao cấp, đường may tinh xảo.",
                'base_price' => rand(50, 500) * 100000, // 5tr - 50tr
                'is_active' => true,
                'image' => 'https://via.placeholder.com/600x800?text=' . urlencode($name),
            ]);

            // Tao variant
            $possibleSizes = ($category->slug === 'boots') ? $shoeSizes : $clothSizes;
            $randomSizes = (array)array_rand($possibleSizes, rand(2, 4));
            
            foreach ($randomSizes as $idx) {
                $size = $possibleSizes[$idx];
                \App\Models\ProductVariants::create([
                    'product_id' => $product->id,
                    'size_id' => $size->id,
                    'stock_quantity' => rand(5, 50),
                    'price' => $product->base_price, // Mac dinh bang gia goc
                    'sku' => strtoupper(\Illuminate\Support\Str::random(10)),
                ]);
            }
        }
    }
}
