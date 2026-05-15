<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Address;

class Customer extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'location',
        'otp',
        'otp_verified',
        'otp_expires_at',
        'user_image',
        'is_active',
    ];

    protected $casts = [
        'otp_expires_at' => 'datetime',
        'otp_verified'   => 'boolean',
        'is_active'      => 'boolean',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
}
