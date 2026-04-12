<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImages extends Model
{
    protected $fillable = [
        'product_id',
        'image_path',
        'sort_order',
    ];

    // 1 Ảnh thuộc về 1 Sản phẩm
    public function product()
    {
        return $this->belongsTo(Products::class);
    }
}
