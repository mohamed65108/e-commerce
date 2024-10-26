<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Support\Facades\Event;
use App\Events\OrderPlaced;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $orderService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->orderService = app(OrderService::class);
    }

    public function testStoreCreatesOrderAndDispatchesEvent()
    {
        Event::fake();
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 5]);

        $this->actingAs($user);

        $orderData = [
            'products' => [
                ['id' => $product->id, 'quantity' => 2]
            ]
        ];

        $order = $this->orderService->store($orderData);

        $this->assertDatabaseHas('orders', ['user_id' => $user->id]);
        $this->assertDatabaseHas('order_product', ['order_id' => $order->id, 'product_id' => $product->id]);

        Event::assertDispatched(OrderPlaced::class);
    }

    public function testShowReturnsOrderWithProducts()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);
        $order->products()->attach($product->id, ['quantity' => 1]);

        $foundOrder = $this->orderService->show($order->id);
        
        $this->assertEquals($order->id, $foundOrder->id);
        $this->assertCount(1, $foundOrder->products);
    }
}
