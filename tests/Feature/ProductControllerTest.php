<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
    parent::setUp();

    $this->actingAs(User::factory()->create()); // or a user with proper permissions if needed
    }


    public function testIndexReturnsProducts()
    {
        Product::factory()->count(5)->create();
        $response = $this->getJson('/api/products');

        $response->assertStatus(200)->assertJsonStructure(['data']);
    }

    public function testStoreCreatesProduct()
    {
        $admin = User::factory()->create();
        $this->actingAs($admin, 'sanctum');

        $productData = [
            'name' => 'Test Product',
            'description' => 'Description',
            'price' => 20,
            'stock' => 10
        ];

        $response = $this->postJson('/api/products', $productData);
        $response->assertStatus(201)->assertJsonFragment(['name' => 'Test Product']);
    }
}
