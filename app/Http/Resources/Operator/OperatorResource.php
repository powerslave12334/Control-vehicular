<?php

namespace App\Http\Resources\Operator;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OperatorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'document_type' => $this->document_type,
            'document_number' => $this->document_number,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'blood_type' => $this->blood_type,
            'emergency_contact' => $this->emergency_contact,
            'emergency_phone' => $this->emergency_phone,
            'avatar' => $this->avatar,
            'license_type' => $this->license_type,
            'status' => $this->status,
            'assigned_vehicle' => $this->whenLoaded('assignedVehicle', fn() => [
                'id' => $this->assignedVehicle->id,
                'plate' => $this->assignedVehicle->plate,
            ]),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
