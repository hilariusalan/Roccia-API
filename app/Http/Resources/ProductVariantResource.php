<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'color' => $this->colors->name,
            'fabric' => $this->fabrics->name,
            'image' => $this->image_url,
            'price' => (int)$this->products->price,
            'stock' => $this->stock
        ];
    }
}
