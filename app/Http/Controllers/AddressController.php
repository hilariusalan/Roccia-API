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

class AddressController extends Controller
{
    public function createAddress(AddressCreateRequest $request): JsonResponse {
        $data = $request->validated();

        $user = Auth::user();

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

        $userAddress = Address::where('id', $id)->where('user_id', $user->id)->first();

        if(!$userAddress) {
            throw new HttpResponseException(response()->json([
                'error' => 'Address not found.'
            ])->setStatusCode(404));
        }

        $data = $request->validated();
        $userAddress->fill($data);
        $userAddress->save();

        return response()->json([
            'message' => 'Address updated successfully.',
            'data' => new AddressResource($userAddress)
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
