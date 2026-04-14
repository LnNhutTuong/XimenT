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

    public function variants()
    {
        return $this->hasMany(ProductVariants::class);
    }

    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }
    
    public function brand()
    {
        return $this->belongsTo(Brands::class, 'brand_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImages::class, 'product_id');
    }

}
