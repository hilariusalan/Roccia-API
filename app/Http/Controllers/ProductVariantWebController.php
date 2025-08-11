<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductVariantWebCreateRequest;
use App\Models\Product;
use App\Models\ProductVariant;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Log;

class ProductVariantWebController extends Controller
{
    public function createProductVariant($productId, ProductVariantWebCreateRequest $request)
    {
        try {
            $data = $request->validated();

            $product = Product::findOrFail($productId);

            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();

            // Create the product variant
            ProductVariant::create([
                'product_id' => $productId,
                'color_id' => $data['color_id'],
                'fabric_id' => $data['fabric_id'],
                'size_id' => $data['size_id'],
                'image_url' => $uploadedFileUrl,
                'stock' => $data['stock'],
            ]);

            return redirect()->route('products.index')->with('success', 'Product variant created successfully!');
        } catch (\Exception $e) {
            Log::error('Product variant creation failed: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Failed to create product variant. Please try again.');
        }
    }
}
