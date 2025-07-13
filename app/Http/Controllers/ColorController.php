<?php

namespace App\Http\Controllers;

use App\Http\Resources\ColorResource;
use App\Models\Color;
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
}
