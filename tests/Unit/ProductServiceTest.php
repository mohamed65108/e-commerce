<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $productService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->productService = app(ProductService::class);
    }

    public function testIndexReturnsPaginatedProducts()
    {
        Product::factory()->count(15)->create();

        $products = $this->productService->index([]);
        $this->assertCount(10, $products);
    }

    public function testStoreCreatesProductAndClearsCache()
    {
        Cache::shouldReceive('tags->flush')->once();

        $productData = [
            'name' => 'New Product',
            'description' => 'Product description',
            'price' => 100,
            'stock' => 10,
        ];

        $product = $this->productService->store($productData);

        $this->assertDatabaseHas('products', ['name' => 'New Product']);
        $this->assertEquals('New Product', $product->name);
    }
}
