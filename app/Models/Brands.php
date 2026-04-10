<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brands extends Model
{
    protected $table = 'product_brands';

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'description',
        'is_active',
    ];

    // 1 Danh mục có nhiều Sản phẩm
    public function products()
    {
        return $this->hasMany(Products::class);
    }
}
