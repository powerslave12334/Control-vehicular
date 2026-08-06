<?php

namespace App\Jobs\Incident;

use App\Domains\Incident\Models\Incident;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class NotifyIncidentEscalationJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public int $tries = 3;

    public function __construct(public readonly Incident $incident) {}

    public function handle(): void
    {
        foreach (\App\Domains\User\Models\User::whereIn('role', ['Administrador del sistema', 'Dirección'])->get() as $user) {
            \App\Domains\Notification\Models\Notification::create([
                'user_id' => $user->id,
                'type' => 'critical',
                'priority' => 'critical',
                'title' => 'Incidente escalado',
                'message' => "Incidente #{$this->incident->id}: {$this->incident->description}",
                'read' => false,
                'related_id' => $this->incident->id,
                'related_type' => Incident::class,
            ]);
        }
    }
}
