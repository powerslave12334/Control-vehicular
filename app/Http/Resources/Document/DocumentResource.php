<?php

namespace App\Http\Resources\Document;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'name' => $this->name,
            'file_path' => $this->file_path,
            'issuance_date' => $this->issuance_date,
            'expiry_date' => $this->expiry_date,
            'status' => $this->status,
            'documentable_type' => $this->documentable_type,
            'documentable_id' => $this->documentable_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
