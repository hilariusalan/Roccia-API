<?php

namespace App\Http\Controllers;

use App\Http\Requests\CollectionCreateRequest;
use App\Http\Resources\CollectionResource;
use App\Models\Collection;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function createCollection(CollectionCreateRequest $request): JsonResponse {
        $data = $request->validated();

        $collection = new Collection($data);
        $collection->save();

        return response()->json([
            'message' => 'Collection created successfully',
            'data' => new CollectionResource($collection)
        ])->setStatusCode(201);
    }

    public function getCollections(): JsonResponse {
        $collections = Collection::all();

        return response()->json([
            'data' => CollectionResource::collection($collections)
        ])->setStatusCode(200);
    }
}
