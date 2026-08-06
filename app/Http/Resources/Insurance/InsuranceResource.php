<?php

namespace App\Http\Resources\Insurance;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InsuranceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'vehicle' => $this->whenLoaded('vehicle', fn() => [
                'id' => $this->vehicle->id,
                'plate' => $this->vehicle->plate,
            ]),
            'policy_number' => $this->policy_number,
            'insurer' => $this->insurer,
            'coverage_type' => $this->coverage_type,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'premium' => $this->premium,
            'deductible' => $this->deductible,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
