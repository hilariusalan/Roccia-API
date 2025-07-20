<?php

namespace App\Http\Controllers;

use App\Http\Requests\CollectionCreateRequest;
use App\Http\Requests\CollectionUpdateRequest;
use App\Models\Collection;
use Illuminate\Http\Request;

class CollectionWebController extends Controller
{
    public function getCollections() {
        $collections = Collection::all();

        return view('collections.list_collection', compact('collections'));
    }

    public function createCollection(CollectionCreateRequest $request)
    {
        $data = $request->validated();

        Collection::create([
            'name' => $data->input('name'),
            'slug' => $data->input('slug'),
            'image_url' => $data->input('image_url')
        ]);

        return redirect()->route('collections.add_collection')->with('success', 'Collection created successfully!');
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

        return redirect()->route('collections.list_collections')->with('success', 'Collection updated!');
    }

    public function deleteCollection(int $id) {
        $collection = Collection::findOrFail($id);
        $collection->delete();

        return redirect()->route('collections.list_collections')->with('success', 'Collection deleted!');
    }
}
