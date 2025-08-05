<?php

namespace App\Http\Controllers;

use App\Http\Requests\CollectionCreateRequest;
use App\Http\Requests\CollectionUpdateRequest;
use App\Http\Resources\CollectionResource;
use App\Models\Collection;
use App\Models\Product;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class CollectionController extends Controller
{
    public function createCollection(CollectionCreateRequest $request): JsonResponse {
        try {
            $user = Auth::user();
    
            $decayMinutes = 1;
            $maxAttempts = 3;
            $key = 'create-collection: ' . $user->email;
    
            if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
                $second = RateLimiter::availableIn($key);
    
                throw new HttpResponseException(response()->json([
                    'error' => 'Too many attempts. Please try again after ' . $second . ' second'
                ])->setStatusCode(429));
            }
    
            RateLimiter::hit($key, $decayMinutes * 60);
    
            $data = $request->validated();
            $collection = new Collection($data);
            $collection->save();
    
            return response()->json([
                'message' => 'Collection created successfully',
                'data' => new CollectionResource($collection),
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


    public function getCollections(): JsonResponse {
        $collections = Collection::all();

        return response()->json([
            'data' => CollectionResource::collection($collections)
        ])->setStatusCode(200);
    }

    public function updateCollection(int $collectionId, CollectionUpdateRequest $request): JsonResponse {
        try {
            $user = Auth::user();
    
            $decayMinutes = 1;
            $maxAttempts = 3;
            $key = 'update-collection: ' . $user->email;
    
            if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
                $second = RateLimiter::availableIn($key);
    
                throw new HttpResponseException(response()->json([
                    'error' => 'Too many attempts. Please try again after ' . $second . ' second'
                ])->setStatusCode(429));
            }
    
            $collection = Collection::where('id', $collectionId)->first();
            if (!$collection) {    
                throw new HttpResponseException(response()->json([
                    'error' => 'Collection not found.'
                ])->setStatusCode(404));
            }
                
            RateLimiter::hit($key, $decayMinutes * 60);
    
            $data = $request->validated();
            $collection->fill($data);
            $collection->save();
    
            return response()->json([
                'message' => 'Collection updated successfully.',
                'data' => [
                    'id' => $collection->id,
                    'name' => $collection->name,
                    'slug' => $collection->slug,
                    'updated_at' => $collection->updated_at->format('d-M-y')
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

    public function deleteCollection(int $collectionId): JsonResponse {
        try {
            $collection = Collection::where('id', $collectionId)->first();
            if (!$collection) {
                throw new HttpResponseException(response()->json([
                    'error' => 'Collection not found.'
                ])->setStatusCode(404));
            }

            $collection->delete();

            return response()->json([
                'message' => 'Collection deleted successfully.',
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

}
