<?php

namespace App\Http\Controllers;

use App\Http\Resources\ColorResource;
use App\Http\Resources\FabricResource;
use App\Http\Resources\SizeResource;
use App\Models\Color;
use App\Models\Fabric;
use App\Models\Size;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function getColors(): JsonResponse {
        $colors = Color::all();

        return response()->json([
            'data' => ColorResource::collection($colors)
        ]);
    }

    public function getSizes(): JsonResponse {
        $colors = Size::all();

        return response()->json([
            'data' => SizeResource::collection($colors)
        ]);
    }

    public function getFabrics(): JsonResponse {
        $colors = Fabric::all();

        return response()->json([
            'data' => FabricResource::collection($colors)
        ]);
    }
}
