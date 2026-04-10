<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sizes extends Model
{
    protected $fillable = [
        'name',
        'sort_order',
    ];

    // 1 Kích cỡ có nhiều Biến thể
    public function variants()
    {
        return $this->hasMany(ProductVariants::class);
    }
}
