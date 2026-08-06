<?php

namespace App\Http\Resources\Vehicle;

use Illuminate\Http\Resources\Json\ResourceCollection;

class VehicleCollection extends ResourceCollection
{
    public $collects = VehicleResource::class;

    public function toArray($request): array
    {
        return [
            'data' => $this->collection,
        ];
    }

    public function paginationInformation($request, $paginated, $default): array
    {
        return [
            'meta' => [
                'current_page' => $paginated['current_page'],
                'last_page' => $paginated['last_page'],
                'per_page' => $paginated['per_page'],
                'total' => $paginated['total'],
            ],
        ];
    }
}
