<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $fillable = [
        'customer_id',
        'user_id',
        'total_amount',
        'status',
        'phone',
        'address',
        'note',
        'order_date',
        'updated_at',
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // 1 Đơn hàng có nhiều Chi tiết đơn hàng
    public function details()
    {
        return $this->hasMany(OrderDetails::class, 'order_id');
    }

    // 1 Đơn hàng thuộc 1 User (nếu có đăng ký)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 1 Đơn hàng thuộc 1 Khách hàng (bắt buộc)
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
