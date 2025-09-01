<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductWebCreateRequest;
use App\Models\Collection;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Type;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Str;

class ProductWebController extends Controller
{
    public function createProduct(ProductWebCreateRequest $request)
    {
        try {
            $data = $request->validated();

            // Check if file exists (optional safeguard)
            if (!$request->hasFile('image')) {
                throw new \Exception('No image file uploaded.');
            }

            // Upload image to Cloudinary
            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();

            // Create the product
            $product = Product::create([
                'name' => $data['name'],
                'collection_id' => $data['collection_id'],
                'type_id' => $data['type_id'],
                'slug' => Str::slug($data['name']),
                'price' => $data['price'],
                'description' => $data['description'],
            ]);

            // Create product usage image
            $product->productUsageImages()->create([
                'product_id' => $product->id,
                'image_url' => $uploadedFileUrl,
            ]);

            return redirect()->route('products.index')->with('success', 'Product created successfully!');
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Product creation failed: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Failed to create product. Please try again.');
        }
    }

    public function getProducts(Request $request) {
        $collectionId = $request->query('collection');
        $typeId = $request->query('type');

        $query = Product::with(['collections', 'types', 'productUsageImages']);

        if ($collectionId) {
            $query->where('collection_id', $collectionId);
        }

        if ($typeId) {
            $query->where('type_id', $typeId);
        }

        $products = $query->paginate(12);

        $collections = Collection::all();
        $types = Type::all();

        return view('components.products.list_products', compact('products', 'collections', 'types'));
    }

    public function destroy($id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return redirect()->route('products.index')->with('error', 'Produk tidak ditemukan.');
            }

            // Delete related product usage images
            $product->productUsageImages()->delete();

            // Delete the product
            $product->delete();

            return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Product deletion failed: ' . $e->getMessage());

            return redirect()->route('products.index')->with('error', 'Gagal menghapus produk. Silakan coba lagi.');
        }
    }

    public function showProductDetail(int $productId)
    {
        $product = Product::with(['collections', 'types', 'productUsageImages'])->find($productId);

        if (!$product) {
            abort(404, 'Produk tidak ditemukan');
        }

        $variants = ProductVariant::with(['colors', 'fabrics', 'sizes'])->where('product_id', $productId)->get();

        return view('components.products.detail_product', [
            'product' => $product,
            'variants' => $variants
        ]);
    }
 
}
