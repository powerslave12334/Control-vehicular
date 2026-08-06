<?php

namespace App\Http\Resources\Part;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->whenLoaded('category', fn() => [
                'id' => $this->category->id,
                'name' => $this->category->name,
            ]),
            'brand' => $this->whenLoaded('brand', fn() => [
                'id' => $this->brand->id,
                'name' => $this->brand->name,
            ]),
            'sku' => $this->sku,
            'description' => $this->description,
            'unit_price' => $this->unit_price,
            'current_stock' => $this->current_stock,
            'min_stock' => $this->min_stock,
            'max_stock' => $this->max_stock,
            'unit_type' => $this->unit_type,
            'location' => $this->location,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
