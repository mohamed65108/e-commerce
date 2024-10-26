<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\StoreProductRequest;
use App\Services\ProductService;


class ProductController extends Controller
{
    public function __construct(private ProductService $productService) {}

   public function index(Request $request)
   {
        $products = $this->productService->index($request->all());

        return response()->json($products);
   }

    public function store(StoreProductRequest $request)
    {
        $product = $this->productService->store($request->validated());

        return response()->json($product, 201);
    }
}

