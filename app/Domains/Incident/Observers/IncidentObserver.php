<?php

namespace App\Domains\Incident\Observers;

use App\Domains\Incident\Models\Incident;
use App\Domains\Incident\Events\IncidentCreated;
use App\Domains\Incident\Events\IncidentUpdated;
use App\Domains\Incident\Events\IncidentDeleted;
use App\Domains\Incident\Events\IncidentRestored;
use App\Shared\Services\AuditService;

class IncidentObserver
{
    public function __construct(protected AuditService $audit) {}

    public function created(Incident $incident): void
    {
        event(new IncidentCreated($incident));
        $this->audit->log('created', $incident, "Incidencia #{$incident->id} reportada");
    }

    public function updated(Incident $incident): void
    {
        $changes = $incident->getChanges();

        if (isset($changes['status'])) {
            $original = $incident->getRawOriginal('status');
            $this->audit->log('status_changed', $incident, "Incidencia #{$incident->id}: {$original} → {$changes['status']}", $changes);
        } else {
            $this->audit->log('updated', $incident, "Incidencia #{$incident->id} actualizada", $changes);
        }

        event(new IncidentUpdated($incident));
    }

    public function deleted(Incident $incident): void
    {
        event(new IncidentDeleted($incident));
        $this->audit->log('deleted', $incident, "Incidencia #{$incident->id} eliminada");
    }

    public function restored(Incident $incident): void
    {
        event(new IncidentRestored($incident));
        $this->audit->log('restored', $incident, "Incidencia #{$incident->id} restaurada");
    }
}
