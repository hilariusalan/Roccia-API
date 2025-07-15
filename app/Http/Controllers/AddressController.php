<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressCreateRequest;
use App\Http\Requests\AddressUpdateRequest;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;

class AddressController extends Controller
{
    public function createAddress(AddressCreateRequest $request): JsonResponse {
        $user = Auth::user();

        $decayMinutes = 1;
        $maxAttemps = 3;
        $key = 'create-address: ' . $user->email;

        if (RateLimiter::tooManyAttempts($key, $maxAttemps)) {
            $second = RateLimiter::availableIn($key);

            throw new HttpResponseException(response()->json([
                'error' => 'Too many attemps. Please try again after ' . $second . ' second'
            ]));
        }

        RateLimiter::hit($key, $decayMinutes * 60);

        $data = $request->validated();

        if (Address::where('is_default', $data['is_default'])->count() == 1) {
            throw new HttpResponseException(response()->json([
                'error' => 'Just allowed one default address.'
            ]));
        }

        $address = new Address($data);
        $address->user_id = $user->id;
        $address->save();

        return response()->json([
            'message' => 'Address created successfully.',
            'data' => new AddressResource($address)
        ])->setStatusCode(200);
    }

    public function getUserAddress(): JsonResponse {
        $user = Auth::user();

        $userAddresses = Address::where('user_id', $user->id)->get();
        if(!$userAddresses) {
            throw new HttpResponseException(response()->json([
                'error' => 'Address is empty.'
            ], 404));
        }

        return response()->json([
            'data' => AddressResource::collection($userAddresses)
        ])->setStatusCode(200);
    }

    public function updateUserAddress(int $id, AddressUpdateRequest $request): JsonResponse {
        $user = Auth::user();

        $decayMinutes = 1;
        $maxAttemps = 3;
        $key = 'update-address: ' . $user->email;

        if (RateLimiter::tooManyAttempts($key, $maxAttemps)) {
            $second = RateLimiter::availableIn($key);

            throw new HttpResponseException(response()->json([
                'error' => 'Too many attemps. Please try again after ' . $second . ' second'
            ])->setStatusCode(429));
        }

        $userAddress = Address::where('id', $id)->where('user_id', $user->id)->first();
        if(!$userAddress) {
            RateLimiter::hit($key, $decayMinutes * 60);

            throw new HttpResponseException(response()->json([
                'error' => 'Address not found.'
            ])->setStatusCode(404));
        }

        RateLimiter::clear($key);

        $data = $request->validated();

        if (Address::where('is_default', $data['is_default'])->count() == 1) {
            throw new HttpResponseException(response()->json([
                'error' => 'Just allowed one default address.'
            ]));
        }

        $userAddress->fill($data);
        $userAddress->save();

        return response()->json([
            'message' => 'Address updated successfully.',
            'data' => [
                'id' => $userAddress->id,
                'is_default' => $userAddress->is_default,
                'first_name' => $userAddress->first_name,
                'last_name' => $userAddress->last_name,
                'address' => $userAddress->address,
                'appartment_suite' => $userAddress->appartment_suite,
                'city' => $userAddress->city,
                'province' => $userAddress->province,
                'postal_code' => $userAddress->postal_code,
                'country' => $userAddress->country,
                'updated_at' => $userAddress->updated_at->format('d-M-Y')
            ]
        ])->setStatusCode(200);
    }

    public function deleteUserAddress(int $id): JsonResponse {
        $user = Auth::user();

        $userAddress = Address::where('id', $id)->where('user_id', $user->id)->first();
        if(!$userAddress) {
            throw new HttpResponseException(response()->json([
                'error' => 'Address not found.'
            ])->setStatusCode(404));
        }

        $userAddress->delete();

        return response()->json([
            'message' => 'Address deleted successfully.'
        ])->setStatusCode(200);
    }
}
