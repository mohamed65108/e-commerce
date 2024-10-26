<?php

namespace App\Services;

use App\Events\OrderPlaced;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function store(array $validatedData)
    {
        $order = DB::transaction(function () use ($validatedData) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'status' => 'pending',
            ]);

            foreach ($validatedData['products'] as $productData) {
                $product = Product::findOrFail($productData['id']);

                if ($product->stock < $productData['quantity']) {
                    throw new \Exception('Insufficient stock');
                }

                $order->products()->attach($product, ['quantity' => $productData['quantity']]);
                $product->decrement('stock', $productData['quantity']);
            }
            return $order;
        });

        event(new OrderPlaced($order));

        return $order;
    }

    public function show($orderId)
    {
        return Order::with('products')->findOrFail($orderId);
    }
}