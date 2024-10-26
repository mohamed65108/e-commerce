<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testStoreCreatesOrder()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10]);
        $this->actingAs($user, 'sanctum');

        $orderData = [
            'products' => [
                ['id' => $product->id, 'quantity' => 2]
            ]
        ];

        $response = $this->postJson('/api/orders', $orderData);

        $response->assertStatus(201)
                 ->assertJsonStructure(['id', 'status']);
        $this->assertDatabaseHas('orders', ['user_id' => $user->id]);
    }

    public function testShowReturnsOrder()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);
        $order->products()->attach($product->id, ['quantity' => 1]);

        $this->actingAs($user, 'sanctum');
        $response = $this->getJson('/api/orders/' . $order->id);

        $response->assertStatus(200)
                 ->assertJsonStructure(['id', 'status', 'products']);
    }
}
