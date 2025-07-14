<?php

namespace App\Http\Controllers;

use App\Http\Requests\CollectionCreateRequest;
use App\Http\Requests\CollectionUpdateRequest;
use App\Http\Resources\CollectionResource;
use App\Models\Collection;
use App\Models\Product;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class CollectionController extends Controller
{
    public function createCollection(CollectionCreateRequest $request): JsonResponse {
        $user = Auth::user();

        $decayMinutes = 1;
        $maxAttemps = 3;
        $key = 'create-collection: ' . $user->email;

        if (RateLimiter::tooManyAttempts($key, $maxAttemps)) {
            $second = RateLimiter::availableIn($key);

            throw new HttpResponseException(response()->json([
                'error' => 'Too many attemps. Please try again after ' . $second . ' second'
            ]));
        }

        RateLimiter::hit($key, $decayMinutes * 60);

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

    public function updateCollection(int $collectionId, CollectionUpdateRequest $request): JsonResponse {
        $user = Auth::user();

        $decayMinutes = 1;
        $maxAttemps = 3;
        $key = 'update-collection: ' . $user->email;

        if (RateLimiter::tooManyAttempts($key, $maxAttemps)) {
            $second = RateLimiter::availableIn($key);

            throw new HttpResponseException(response()->json([
                'error' => 'Too many attemps. Please try again after ' . $second . ' second'
            ])->setStatusCode(429));
        }

        $collection = Collection::where('id', $collectionId)->first();
        if (!$collection) {
            RateLimiter::hit($key, $decayMinutes * 60);

            throw new HttpResponseException(response()->json([
                'error' => 'Collection not found.'
            ])->setStatusCode(404));
        }

        RateLimiter::clear($key);

        $data = $request->validated();
        $collection->fill($data);
        $collection->save();

        return response()->json([
            'message' => 'Collection updated successfully.',
            'data' => new CollectionResource($collection)
        ])->setStatusCode(200);
    }

    public function deleteCollection(int $collectionId): JsonResponse {
        $collection = Collection::where('id', $collectionId)->first();
        if (!$collection) {
            throw new HttpResponseException(response()->json([
                'error' => 'Collection not found.'
            ])->setStatusCode(404));
        }

        $collection->delete();

        return response()->json([
            'message' => 'Collection deleted successfully.'
        ])->setStatusCode(200);
    }

}
