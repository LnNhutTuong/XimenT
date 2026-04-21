<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_photo_path',
        'phone',
        'address',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
        'phone',
        'address',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => 'boolean',
        ];
    }

    // Lái số điện thọi
    public function getPhoneAttribute(){
        return $this->customer?->phone;
    }
    
    //Lái địa chỉ
    public function getAddressAttribute(){
        return $this->customer?->address;
    }


    /**
     * Kiểm tra xem người dùng có phải là Admin không.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Kiểm tra xem người dùng có phải là User bình thường không.
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Lấy thông tin khách hàng liên kết với user này.
     */
    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    public function order()
    {
        return $this->hasMany(Orders::class);
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }
}
