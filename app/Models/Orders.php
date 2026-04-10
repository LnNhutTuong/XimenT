<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'total_amount',
        'status',
    ];

    // 1 Đơn hàng có nhiều Chi tiết đơn hàng
    public function details()
    {
        return $this->hasMany(OrderDetails::class);
    }

    // 1 Đơn hàng thuộc 1 User (nếu có đăng ký)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
