<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Services\OrderService;
use App\Models\Order;

class OrderController extends Controller
{
    public function __construct(private OrderService $orderService) {}

    public function store(StoreOrderRequest $request)
    {
        $this->authorize('create', Order::class);

        $order = $this->orderService->store($request->validated());

        return response()->json($order, 201);
    }

    public function show($id)
    {
        $order = $this->orderService->show($id);

        $this->authorize('view', $order);

        return response()->json($order);
    }
}

