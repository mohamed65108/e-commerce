<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Product;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            foreach ($this->products as $productData) {
                $product = Product::find($productData['id']);
                if(!$product){
                    continue;
                }

                if ($product->stock < $productData['quantity']) {
                    $validator->errors()->add('products.' . $productData['id'] . '.quantity', "Insufficient stock for this product, the max quantity is $product->stock.");
                }
            }
        });
    }
}
