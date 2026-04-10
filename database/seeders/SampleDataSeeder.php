<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use \App\Models\Categories;
use \App\Models\Sizes;
use \App\Models\Products;
use \App\Models\ProductVariants;
use \App\Models\Brands;



class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Tạo Danh mục mẫu
        $cate = Categories::create([
            'name' => 'Áo Thun Nam',
            'slug' => 'ao-thun-nam',
            'description' => 'Các loại áo thun chất liệu cotton'
        ]);
        
        $sizeM = Sizes::create(['name' => 'M']);
        $sizeL = Sizes::create(['name' => 'L']);

        // 2. Tạo Thương hiệu mẫu
        $brand = Brands::create([
            'name' => 'Gucci',
            'slug' => 'gucci',
            'description' => 'Thương hiệu thời trang cao cấp'
        ]);

        // 3. Tạo 1 Sản phẩm mẫu
        $product = Products::create([
            'category_id' => $cate->id,
            'brand_id' => $brand->id,
            'name' => 'Áo Thun Basic 2024',
            'slug' => 'ao-thun-basic-2024',
            'description' => 'Áo thun siêu mát cho mùa hè',
            'base_price' => 250000,
            'is_active' => true
        ]);

        // 4. Tạo các biến thể cho sản phẩm đó
        ProductVariants::create([
        'product_id' => $product->id,
        'size_id' => $sizeM->id,
        'stock_quantity' => 50,
        'sku' => 'ATB-RED-M'
        ]);

        ProductVariants::create([
            'product_id' => $product->id,
            'size_id' => $sizeL->id,
            'stock_quantity' => 20,
            'sku' => 'ATB-BLUE-L'
        ]);
    }
}
