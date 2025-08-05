<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Requests\ProductVariantCreateRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductVariantResource;
use App\Models\Product;
use App\Models\ProductVariant;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class ProductController extends Controller
{
    public function createProduct(ProductCreateRequest $request): JsonResponse {
        try {
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
                'product_id' => $product->id,
                'image_url' => $data['image_url']
            ]);
    
            $product->load(['collections', 'types', 'productUsageImages']);
    
            return response()->json([
                'message' => 'Product created successfully',
                'data' => new ProductResource($product),
                'isSuccess' => true
            ])->setStatusCode(201);
        } catch (Exception $ex) {
            throw new HttpResponseException(response()->json([
                'error' => 'Something went wrong.',
                'message' => $ex->getMessage(),
                'isSuccess' => false
            ])->setStatusCode(500));
        } 
    }

    public function getProducts(Request $request): JsonResponse {
        $size = $request->query('size', 4);
        $page = $request->query('page', 1);

        $minPrice = $request->query('min_price');
        $maxPrice = $request->query('max_price');
        $typeId = $request->query('type_id');
        $colorId = $request->query('color_id');
        
        $query = Product::query()->with([
            'collections', 
            'types', 
            'productUsageImages',
            'productVariants.color'
        ]);

        if($minPrice && $maxPrice != null) {
            $query->where('price', '>=', $minPrice)->where('price', '<=', $maxPrice);
        }

        if($typeId != null) {
            $query->where('type_id', $typeId);
        }

        if($colorId != null) {
            $query->whereHas('productVariants', function ($q) use ($colorId) {
                $q->where('colorId', $colorId);
            });
        }

        $products = $query->paginate($size, ['*'], 'page', $page);

        return response()->json([
            'data' => ProductResource::collection($products->items()),
            'pagination' => [
                'page' => $products->currentPage(),
                'size' => $products->perPage(),
                'total_pages' => $products->lastPage()
            ]
        ])->setStatusCode(200);
    }

    public function getProductsPerCollection(int $collectionId, Request $request): JsonResponse {
        $size = $request->query('size', 4);
        $page = $request->query('page', 1);

        $minPrice = $request->query('min_price');
        $maxPrice = $request->query('max_price');
        $typeId = $request->query('type_id');
        $colorId = $request->query('color_id');

        $query = Product::query()->with([
            'collections', 
            'types', 
            'productUsageImages',
            'productVariants.color'
        ])->where('collection_id', $collectionId);

        if($minPrice && $maxPrice != null) {
            $query->where('price', '>=', $minPrice)->where('price', '<=', $maxPrice);
        }

        if($typeId != null) {
            $query->where('type_id', $typeId);
        }

        if($colorId != null) {
            $query->whereHas('productVariants', function ($q) use ($colorId) {
                $q->where('colorId', $colorId);
            });
        }

        $products = $query->paginate($size, ['*'], 'page', $page);

        return response()->json([
            'data' => ProductResource::collection($products),
            'pagination' => [
                'page' => $products->currentPage(),
                'size' => $products->perPage(),
                'total_pages' => $products->lastPage()
            ]
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
        try {

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
                throw new HttpResponseException(response()->json([
                    'error' => 'Product not found.'
                ])->setStatusCode(404));
            }
                
            RateLimiter::hit($key, $decayMinutes * 60);
    
            $data = $request->validated();
            $product->fill($data);
            $product->save();
    
            return response()->json([
                'message' => 'Product updated successfully.',
                'data' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'collection' => $product->collections->name, 
                    'type' => $product->types->name , 
                    'slug' => $product->slug,
                    'price' => (int)$product->price,
                    'description' => $product->description,
                    'image_url' => $product->productUsageImages->image_url,
                    'updated_at' => $product->updated_at->format('d-m-Y'),  
                ],
                'isSuccess' => true
            ])->setStatusCode(200);
        } catch (Exception $ex) {
            throw new HttpResponseException(response()->json([
                'error' => 'Something went wrong.',
                'message' => $ex->getMessage(),
                'isSuccess' => false
            ])->setStatusCode(500));
        } 
    }

    public function deleteProduct(int $productId): JsonResponse {
        try {
            $product = Product::where('id', $productId)->first();

            if (!$product) {
                throw new HttpResponseException(response()->json([
                    'error' => 'Product not found.'
                ])->setStatusCode(404));
            }

            $product->delete();

            return response()->json([
                'message' => 'Product deleted successfully.',
                'isSuccess' => true
            ])->setStatusCode(200);
        } catch(Exception $ex) {
            throw new HttpResponseException(response()->json([
                'error' => 'Something went wrong.',
                'message' => $ex->getMessage(),
                'isSuccess' => false
            ])->setStatusCode(500));
        }
    } 
}
