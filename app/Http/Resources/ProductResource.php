<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'name' => $this->name,
            'collection' => $this->collections->name, 
            'type' => $this->types->name, 
            'slug' => $this->slug,
            'price' => (int)$this->price,
            'description' => $this->description,
            'image_url' => $this->productUsageImages->first()->image_url,
            'created_at' => $this->created_at->format('d-m-Y'),  
        ];
    }
}
