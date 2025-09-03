<?php

namespace App\Http\Controllers;

use App\Http\Requests\ColorCreateRequest;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ColorWebController extends Controller
{
    public function createColor(ColorCreateRequest $request) {
        try {
            $data = $request->validated();
    
            $collection = new Color($data);
            $collection->save();

            return redirect()->route('other.other')->with('success', 'Color created successfully!');
        } catch (\Exception $ex) {
            Log::error('Color creation failed: ' . $ex->getMessage());
            return redirect()->back()->with('error', 'Failed to create color. Please try again.');
        } 
    }
}
