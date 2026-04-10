<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // Liên kết với bảng danh mục //Liên kết với brands
            $table->foreignId('category_id')->constrained('product_categories')->onDelete('cascade');
            $table->foreignId('brand_id')->constrained('product_brands')->onDelete('cascade');

            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->decimal('base_price', 15, 2); // Giá gốc
            $table->string('image')->nullable(); // Ảnh đại diện
            $table->boolean('is_active')->default(true); // Hiện/Ẩn sản phẩm
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
