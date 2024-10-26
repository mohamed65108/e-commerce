<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Product;

class ProductExistsAndHasSufficientStock implements Rule
{
    public function passes($attribute, $value)
    {
    	dd($value['id']);
        $product = Product::find($value['id']);

        if (!$product || $product->stock < $value['quantity']) {
            return false;
        }

        return true;
    }

    public function message()
    {
        return 'The selected product does not exist or has insufficient stock.';
    }
}