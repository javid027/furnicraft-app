<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id','address_id', 'total_price', 'status'];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'user_id'); // Assuming 'user_id' refers to a customer
    }
    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
