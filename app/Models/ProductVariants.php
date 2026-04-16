<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariants extends Model
{
    protected $fillable = [
        'product_id',
        'size_id',
        'stock_quantity',
        'price',
        'discount_price',
        'sku',
    ];

    // 1 Biến thể thuộc 1 Sản phẩm
    public function product()
    {
        return $this->belongsTo(Products::class);
    }

    // 1 Biến thể thuộc 1 Kích cỡ
    public function size()
    {
        return $this->belongsTo(Sizes::class);
    }

    public function order_details()
    {
        return $this->hasMany(OrderDetails::class, 'product_variant_id');
    }
}
