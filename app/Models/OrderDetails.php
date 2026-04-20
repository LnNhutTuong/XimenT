<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    protected $fillable = [
        'order_id',
        'product_variant_id',
        'quantity',
        'price',
    ];

    // 1 Chi tiết đơn hàng thuộc 1 Đơn hàng
    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }

    // 1 Chi tiết đơn hàng thuộc 1 Biến thể
    public function variant()
    {
        return $this->belongsTo(ProductVariants::class, 'product_variant_id');
    }
}
