<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Categories;
use App\Models\Brands;
use App\Models\Products;
use App\Models\Sizes;
use App\Models\ProductVariants;
use App\Models\Orders;
use App\Models\OrderDetails;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Tạo Users
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $userCustomer = User::create([
            'name' => 'Khách hàng Demo',
            'email' => 'khachhang@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        $customer1 = Customer::create([
            'user_id' => $userCustomer->id,
            'name' => $userCustomer->name,
            'email' => $userCustomer->email,
            'phone' => '0987654321',
            'address' => '123 Đường Nam Kỳ Khởi Nghĩa, Quận 1, TP HCM',
        ]);

        // Khách hàng mua không cần đăng nhập (Guest)
        $guestCustomer = Customer::create([
            'user_id' => null,
            'name' => 'Khách Vãng Lai',
            'email' => 'guest@example.com',
            'phone' => '0123456789',
            'address' => '456 Lê Lợi, Bến Nghé, Quận 1, TP HCM',
        ]);

        // 2. Tạo Category
        $catShirts = Categories::create([
            'name' => 'Áo Thun',
            'slug' => 'ao-thun',
        ]);
        $catPants = Categories::create([
            'name' => 'Quần Jean',
            'slug' => 'quan-jean',
        ]);

        // 3. Tạo Brands
        $brandNike = Brands::create([
            'name' => 'Nike',
            'slug' => 'nike',
            'description' => 'Thương hiệu thể thao Nike',
            'is_active' => true,
        ]);
        $brandAdidas = Brands::create([
            'name' => 'Adidas',
            'slug' => 'adidas',
            'description' => 'Thương hiệu thể thao Adidas',
            'is_active' => true,
        ]);

        // 4. Tạo Sizes
        $sizeS = Sizes::create(['name' => 'S']);
        $sizeM = Sizes::create(['name' => 'M']);
        $sizeL = Sizes::create(['name' => 'L']);
        
        $catShirts->sizes()->attach([$sizeS->id, $sizeM->id, $sizeL->id]);
        $catPants->sizes()->attach([$sizeM->id, $sizeL->id]);

        // 5. Tạo Products
        $prod1 = Products::create([
            'category_id' => $catShirts->id,
            'brand_id' => $brandNike->id,
            'name' => 'Áo Thun Nike Đen Thể Thao',
            'slug' => 'ao-thun-nike-den-the-thao',
            'description' => 'Áo thun thể thao thoáng mát, chất liệu polyester cao cấp.',
            'base_price' => 350000,
            'is_active' => true,
        ]);

        $prod2 = Products::create([
            'category_id' => $catPants->id,
            'brand_id' => $brandAdidas->id,
            'name' => 'Quần Jean Adidas Form Rộng',
            'slug' => 'quan-jean-adidas-form-rong',
            'description' => 'Quần jean phong cách đường phố, chất vải dày dặn.',
            'base_price' => 500000,
            'is_active' => true,
        ]);

        // 6. Tạo Product Variants
        $variant1 = ProductVariants::create([
            'product_id' => $prod1->id,
            'size_id' => $sizeM->id,
            'stock_quantity' => 50,
            'price' => null, // dùng giá base
            'discount_price' => null, // dùng giá base
            'sku' => 'NIKE-AT-M',
        ]);
        
        $variant2 = ProductVariants::create([
            'product_id' => $prod2->id,
            'size_id' => $sizeL->id,
            'stock_quantity' => 30,
            'price' => 550000, // Đắt hơn base một chút
            'discount_price' => 500000, // dùng giá base
            'sku' => 'ADI-QJ-L',
        ]);

        // 7. Tạo Order cho Khách hàng có tài khoản
        $order = Orders::create([
            'customer_id' => $customer1->id,
            'user_id' => $userCustomer->id,
            'total_amount' => 900000,
            'status' => 'pending',
            'phone' => $customer1->phone,
            'address' => $customer1->address,
            'note' => 'Giao hàng sau 5h chiều',
        ]);

        // Tạo Order cho Khách vãng lai
        $guestOrder = Orders::create([
            'customer_id' => $guestCustomer->id,
            'user_id' => null,
            'total_amount' => 350000,
            'status' => 'processing',
            'phone' => $guestCustomer->phone,
            'address' => $guestCustomer->address,
            'note' => 'Gọi điện trước khi giao',
        ]);

        // 8. Tạo Order Details
        OrderDetails::create([
            'order_id' => $order->id,
            'product_variant_id' => $variant1->id,
            'quantity' => 1,
            'price' => 350000,
        ]);

        OrderDetails::create([
            'order_id' => $order->id,
            'product_variant_id' => $variant2->id,
            'quantity' => 1,
            'price' => 550000,
        ]);

        OrderDetails::create([
            'order_id' => $guestOrder->id,
            'product_variant_id' => $variant1->id,
            'quantity' => 1,
            'price' => 350000,
        ]);
    }
}
