<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductVariantResource;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class ProductController extends Controller
{
    public function createProduct(ProductCreateRequest $request): JsonResponse {
        $user = Auth::user();

        $decayMinutes = 1;
        $maxAttemps = 3;
        $key = 'create-product: ' . $user->email;

        if (RateLimiter::tooManyAttempts($key, $maxAttemps)) {
            $second = RateLimiter::availableIn($key);

            throw new HttpResponseException(response()->json([
                'error' => 'Too many attemps. Please try again after ' . $second . ' second'
            ]));
        }

        RateLimiter::hit($key, $decayMinutes * 60);

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

        $product->load(['collections', 'types', 'productUsageImages']);

        return response()->json([
            'message' => 'Product created successfully',
            'data' => new ProductResource($product)
        ])->setStatusCode(201);
    }

    public function getProducts(): JsonResponse {
        $products = Product::with(['collections', 'types', 'productUsageImages'])->get();

        return response()->json([
            'data' => ProductResource::collection($products)
        ])->setStatusCode(200);
    }

    public function getProductsPerCollection(int $collectionId): JsonResponse {
        $products = Product::with(['collections', 'types', 'productUsageImages'])
                            ->where('collection_id', $collectionId)
                            ->get();

        return response()->json([
            'data' => ProductResource::collection($products)
        ])->setStatusCode(200);
    }

    public function getProductDetail(int $productId): JsonResponse {
        $product = Product::with(['collections', 'types', 'productUsageImages'])
                            ->where('id', $productId)
                            ->first();

        if (!$product) {
            throw new HttpResponseException(response()->json([
                'error' => 'Product not found.'
            ])->setStatusCode(404));
        }

        $productVariants = ProductVariant::with(['products, colors, fabrics, sizes'])->where('product_id', $productId)->get();

        return response()->json([
            'data' => [
                'id' => $product->id,
                'name' => $product->name,
                'collection' => $product->collections->name,
                'type' => $product->types->name,
                'slug' => $product->slug,
                'price' => (int)$product->price,
                'description' => $product->description,
                'variants' => ProductVariantResource::collection($productVariants),
                'usage_image' => $product->productUsageImage->url
            ]
        ])->setStatusCode(200);
    }

    public function updateProduct(int $productId, ProductUpdateRequest $request): JsonResponse {
        $user = Auth::user();

        $decayMinutes = 1;
        $maxAttemps = 3;
        $key = 'update-product: ' . $user->email;

        if (RateLimiter::tooManyAttempts($key, $maxAttemps)) {
            $second = RateLimiter::availableIn($key);

            throw new HttpResponseException(response()->json([
                'error' => 'Too many attemps. Please try again after ' . $second . ' second'
            ])->setStatusCode(429));
        }

        $product = Product::where('id', $productId)->first();
        if (!$product) {
            RateLimiter::hit($key, $decayMinutes * 60);

            throw new HttpResponseException(response()->json([
                'error' => 'Product not found.'
            ])->setStatusCode(404));
        }

        RateLimiter::clear($key);

        $data = $request->validated();
        $product->fill($data);
        $product->save();

        return response()->json([
            'message' => 'Product updated successfully.',
            'data' => new ProductResource($data)
        ])->setStatusCode(200);
    }

    public function deleteProduct(int $productId): JsonResponse {
        $product = Product::where('id', $productId)->first();

        if (!$product) {
            throw new HttpResponseException(response()->json([
                'error' => 'Product not found.'
            ])->setStatusCode(404));
        }

        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully.'
        ])->setStatusCode(200);
    }
}
