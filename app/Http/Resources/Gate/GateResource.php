<?php

namespace App\Http\Resources\Gate;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'vehicle' => $this->whenLoaded('vehicle', fn() => [
                'id' => $this->vehicle->id,
                'plate' => $this->vehicle->plate,
            ]),
            'driver_name' => $this->driver_name,
            'type' => $this->type,
            'photo' => $this->photo,
            'notes' => $this->notes,
            'logged_at' => $this->logged_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
