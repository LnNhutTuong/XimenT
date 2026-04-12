<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $table = 'product_categories';

    protected $fillable = [
        'name',
        'slug',
    ];

    // 1 Danh mục có nhiều Sản phẩm
    public function products()
    {
        return $this->hasMany(Products::class, 'category_id');
    }

    // 1 Danh mục có nhiều Size tương ứng
    public function sizes()
    {
        return $this->belongsToMany(Sizes::class, 'category_size', 'category_id', 'size_id');
    }
}
