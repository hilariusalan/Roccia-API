<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductAddRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function createProduct(ProductAddRequest $request): JsonResponse {
        $data = $request->validated();

        $product = Product::create([
            'name' => $data['name'],
            'collection_id' => $data['collection_id'],
            'type_id' => $data['type_id'],
            'slug' => $data['slug'],
            'price' => $data['price'],
            'description' => $data['description']
        ]);

        $product->productUsageImages()->create([
            'image_url' => $data['image_url']
        ]);

        $product->load(['collection', 'type', 'productUsageImage']);

        return response()->json([
            'message' => 'Product created successfully',
            'data' => new ProductResource($product)
        ], 201);
    }

    public function getProducts(): JsonResponse {
        $products = Product::with(['collection', 'type', 'productUsageImage'])->get();

        return response()->json([
            'data' => ProductResource::collection($products)
        ], 200);
    }
}
