<?php

namespace App\Http\Resources\Notification;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'type' => $this->type,
            'priority' => $this->priority,
            'title' => $this->title,
            'message' => $this->message,
            'read' => $this->read,
            'read_at' => $this->read_at,
            'archived' => $this->archived,
            'archived_at' => $this->archived_at,
            'related_id' => $this->related_id,
            'related_type' => $this->related_type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
