<?php

namespace App\Shared\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuditService
{
    public function log(string $event, Model $subject, ?string $description = null, array $properties = []): void
    {
        DB::table('activity_logs')->insert([
            'subject_type' => get_class($subject),
            'subject_id' => $subject->id,
            'causer_type' => Auth::check() ? get_class(Auth::user()) : null,
            'causer_id' => Auth::id(),
            'event' => $event,
            'description' => $description ?? class_basename($subject) . " {$event}",
            'properties' => json_encode($properties ?: $subject->toArray()),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
