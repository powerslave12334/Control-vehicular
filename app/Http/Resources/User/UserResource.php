<?php

declare(strict_types=1);

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'status' => $this->status,
            'phone' => $this->phone,
            'assigned_vehicle_id' => $this->assigned_vehicle_id,
            'document_type' => $this->document_type,
            'document_number' => $this->document_number,
            'license_type' => $this->license_type,
            'emergency_contact' => $this->emergency_contact,
            'emergency_phone' => $this->emergency_phone,
            'address' => $this->address,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
