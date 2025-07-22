<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Models\Product;
use App\Models\Type;
use Illuminate\Http\Request;

class ProductWebController extends Controller
{
    public function index(Request $request)
    {
        $size = $request->query('size', 10);
        $minPrice = $request->query('min_price');
        $maxPrice = $request->query('max_price');
        $typeId = $request->query('type_id');
        $colorId = $request->query('color_id');

        $query = Product::with(['collections', 'types', 'productUsageImages', 'productVariants.color']);

        if ($minPrice && $maxPrice) {
            $query->whereBetween('price', [$minPrice, $maxPrice]);
        }

        if ($typeId) {
            $query->where('type_id', $typeId);
        }

        if ($colorId) {
            $query->whereHas('productVariants', function ($q) use ($colorId) {
                $q->where('colorId', $colorId);
            });
        }

        // $products = $query->paginate($size)->withQueryString();
        $products = $query->paginate($size);

        return view('products.components.list_products', [
            'products' => $products,
            'types' => Type::all(),
            'colors' => Color::all(),
            'filters' => $request->only(['min_price', 'max_price', 'type_id', 'color_id'])
        ]);
    }
}
