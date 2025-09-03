<?php

namespace App\Http\Controllers;

use App\Http\Requests\CollectionCreateRequest;
use App\Http\Requests\CollectionUpdateRequest;
use App\Models\Collection;
use App\Models\Product;
use App\Models\Type;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CollectionWebController extends Controller
{
    public function getCollections() {
        $collections = Collection::all();

        return view('components.collections.list_collection ', compact('collections'));
    }

    public function getProductsPerCollection(int $collectionId, Request $request) {
        $typeId = $request->query('type');

        $query = Product::query()->with([
            'collections', 
            'types', 
            'productUsageImages',
            'productVariants.colors'
        ])->where('collection_id', $collectionId);

        if($typeId) {
            $query->where('type_id', $typeId);
        }

        $products = $query->paginate(12);

        $types = Type::all();
        $collectionName = Collection::where('id', $collectionId)->first()->name;

        return view('components.collections.collection_products', compact('products', 'types', 'collectionId', 'collectionName'));
    }

    public function createCollection(CollectionCreateRequest $request)
    {
        try {
            $data = $request->validated();

            // Upload image to Cloudinary
            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();

            // Create the collection
            Collection::create([
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'image_url' => $uploadedFileUrl,
            ]);

            return redirect()->route('collections.index')->with('success', 'Collection created successfully!');
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Collection creation failed: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Failed to create collection. Please try again.');
        }
    }

    public function editCollection(int $id) {
        $collection = Collection::findOrFail($id);
        return view('collections.edit', compact('collection'));
    }

    public function updateCollection(int $id, CollectionUpdateRequest $request) {
        $data = $request->validated();

        $collection = Collection::findOrFail($id);
        $collection->update($data->only('name'));
        $collection->update($data->only('slug'));
        $collection->update($data->only('image_url'));

        return redirect()->route('collections.index')->with('success', 'Collection updated!');
    }

    public function deleteCollection(int $id) {
        $collection = Collection::findOrFail($id);
        $collection->delete();

        return redirect()->route('collections.index')->with('success', 'Collection deleted!');
    }
}
