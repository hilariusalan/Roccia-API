<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductVariantWebCreateRequest;
use App\Http\Requests\ProductVariantWebUpdateRequest;
use App\Models\Product;
use App\Models\ProductVariant;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Log;

class ProductVariantWebController extends Controller
{
    public function showCreateForm($productId) {
        $product = Product::findOrFail($productId);
        return view('components.products.create_product_variant', compact('productId'));
    }

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

    public function updateProductVariant($productId, $variantId, ProductVariantWebUpdateRequest $request) {
        try {
            // Verify product and variant exist
            $product = Product::findOrFail($productId);
            $productVariant = ProductVariant::findOrFail($variantId);

            $data = $request->validated();

            // Handle image upload if provided
            $imageUrl = $productVariant->image_url;
            if ($request->hasFile('image')) {
                $imageUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
            }

            // Update the product variant
            $productVariant->update([
                'image_url' => $imageUrl,
                'stock' => $data['stock'],
            ]);

            return redirect()->route('products.index')->with('success', 'Product variant updated successfully!');
        } catch (\Exception $e) {
            Log::error('Product variant update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update product variant. Please try again.');
        }
    }

    public function deleteProductVariant($productId, $variantId) {
        try {
            // Verify product and variant exist
            $product = Product::findOrFail($productId);
            $productVariant = ProductVariant::findOrFail($variantId);

            // Delete the variant
            $productVariant->delete();

            return redirect()->route('products.index')->with('success', 'Product variant deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Product variant deletion failed: ' . $e->getMessage());
            return redirect()->route('products.index')->with('error', 'Failed to delete product variant. Please try again.');
        }
    }

}
