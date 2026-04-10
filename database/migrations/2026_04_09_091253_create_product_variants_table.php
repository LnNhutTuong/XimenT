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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            // Liên kết với sản phẩm gốc
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            // Liên kết với kích cỡ
            $table->foreignId('size_id')->constrained()->onDelete('cascade');
            // Thông tin quan trọng nhất: Kho hàng của riêng biến thể này
            $table->integer('stock_quantity')->default(0);      
            // Giá riêng (Ví dụ: Size XXL đắt hơn 50k thì điền vào đây, nếu không thì dùng giá gốc của sản phẩm)
            $table->decimal('price', 15, 2)->nullable();      
            $table->string('sku')->unique()->nullable(); // Mã định danh sản phẩm (Ví dụ: AO-THUN-DEN-L)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
