<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $table = 'product_categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    // 1 Danh mục có nhiều Sản phẩm
    public function products()
    {
        return $this->hasMany(Products::class);
    }
}
