<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Type;
use Illuminate\Http\Request;

class ProductWebController extends Controller
{
    public function index(Request $request) {
        $collectionId = $request->query('collection');
        $typeId = $request->query('type');

        $query = Product::with(['collections', 'types']);

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
        $product = Product::find($id);

        if (!$product) {
            return redirect()->route('products.index')->with('error', 'Produk tidak ditemukan.');
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function showProductDetail(int $productId)
    {
        $product = Product::with(['collections', 'types', 'productUsageImage'])->find($productId);

        if (!$product) {
            abort(404, 'Produk tidak ditemukan');
        }

        $variants = ProductVariant::with(['colors', 'fabrics', 'sizes'])->where('product_id', $productId)->get();

        return view('components.products.detail_product', [
            'product' => $product,
            'variants' => $variants
        ]);
    }

    // public function getProducts(Request $request)
    // {
    //     $size = $request->query('size', 10);
    //     $minPrice = $request->query('min_price');
    //     $maxPrice = $request->query('max_price');
    //     $typeId = $request->query('type_id');
    //     $colorId = $request->query('color_id');

    //     $query = Product::with(['collections', 'types', 'productUsageImages', 'productVariants.color']);

    //     if ($minPrice && $maxPrice) {
    //         $query->whereBetween('price', [$minPrice, $maxPrice]);
    //     }

    //     if ($typeId) {
    //         $query->where('type_id', $typeId);
    //     }

    //     if ($colorId) {
    //         $query->whereHas('productVariants', function ($q) use ($colorId) {
    //             $q->where('colorId', $colorId);
    //         });
    //     }

    //     // $products = $query->paginate($size)->withQueryString();
    //     $products = $query->paginate($size);

    //     return view('products.components.list_products', [
    //         'products' => $products,
    //         'types' => Type::all(),
    //         'colors' => Color::all(),
    //         'filters' => $request->only(['min_price', 'max_price', 'type_id', 'color_id'])
    //     ]);
    // }
}
