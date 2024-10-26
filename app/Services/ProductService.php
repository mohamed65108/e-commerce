<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductService
{
    public function index(array $filters)
    {
        $cacheKey = 'products_' . ($filters['name'] ?? '') . '_' . ($filters['min_price'] ?? '') . '_' . ($filters['max_price'] ?? '') . '_' . ($filters['perPage'] ?? '') . '_' . ($filters['page'] ?? '');

        return Cache::tags(['products'])->remember($cacheKey, 600, function () use ($filters) {
            $query = Product::query();

           if (isset($filters['name'])) {
                $query->whereNameLike($filters['name']);
            }

            if (isset($filters['min_price'])) {
                $query->wherePriceGreaterThanOrEqual($filters['min_price']);
            }

            if (isset($filters['max_price'])) {
                $query->wherePriceLessThanOrEqual($filters['max_price']);
            }

            return Paginator::paginate($query, $filters['perPage'] ?? 10, $filters['page'] ?? 1);
        });
    }

    public function store(array $validatedData)
    {
        $product = Product::create($validatedData);

        Cache::tags(['products'])->flush();

        return $product;
    }
}