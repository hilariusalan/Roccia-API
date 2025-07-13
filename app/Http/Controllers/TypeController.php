<?php

namespace App\Http\Controllers;

use App\Http\Requests\TypeAddRequest;
use App\Http\Resources\TypeResource;
use App\Models\Type;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    public function createType(TypeAddRequest $request): JsonResponse {
        $data = $request->validated();

        $collection = new Type($data);
        $collection->save();

        return response()->json([
            'message' => 'Collection created successfully',
            'data' => new TypeResource($data)
        ], 201);
    }

    public function getTypes(): JsonResponse {
        $types = Type::all();

        return response()->json([
            'data' => TypeResource::collection($types)
        ]);
    }
}
