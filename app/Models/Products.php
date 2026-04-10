<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'description',
        'base_price',
        'image',
        'is_active',
    ];

    // 1 Sản phẩm có nhiều Biến thể
    public function variants()
    {
        return $this->hasMany(ProductVariants::class);
    }

    // 1 Sản phẩm thuộc 1 Danh mục
    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }
    
     public function brand()
    {
        return $this->belongsTo(Brands::class, 'brand_id');
    }
}
