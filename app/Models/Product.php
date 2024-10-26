<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'stock'];

    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot('quantity');
    }

    public function scopeWhereNameLike($query, $name)
    {
        return $query->where('name', 'LIKE', '%' . $name . '%');
    }

    public function scopeWherePriceGreaterThanOrEqual($query, $minPrice)
    {
        return $query->where('price', '>=', $minPrice);
    }

    public function scopeWherePriceLessThanOrEqual($query, $maxPrice)
    {
        return $query->where('price', '<=', $maxPrice);
    }
}
