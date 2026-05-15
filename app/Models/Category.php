<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'status', 'image', 'is_feature'];


    public function products()
    {
        return $this->hasMany(Product::class);
    }

    protected $appends = ['image'];

    public function getImageUrlAttribute()
    {
        return $this->image
            ? asset('storage/' . $this->image)
            : asset('images/default_product.png'); // fallback image
    }
}
