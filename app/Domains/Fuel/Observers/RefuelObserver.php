<?php

namespace App\Domains\Fuel\Observers;

use App\Domains\Fuel\Models\Refuel;
use App\Domains\Fuel\Events\RefuelCreated;
use App\Domains\Fuel\Events\RefuelDeleted;
use App\Domains\Fuel\Events\RefuelRestored;
use App\Shared\Services\AuditService;

class RefuelObserver
{
    public function __construct(protected AuditService $audit) {}

    public function created(Refuel $refuel): void
    {
        event(new RefuelCreated($refuel));
        $this->audit->log('created', $refuel, "Carga #{$refuel->id} registrada");
    }

    public function updated(Refuel $refuel): void
    {
        $changes = $refuel->getChanges();

        if (isset($changes['status'])) {
            $original = $refuel->getRawOriginal('status');
            $this->audit->log('status_changed', $refuel, "Carga #{$refuel->id}: {$original} → {$changes['status']}", $changes);
        } else {
            $this->audit->log('updated', $refuel, "Carga #{$refuel->id} actualizada", $changes);
        }
    }

    public function deleted(Refuel $refuel): void
    {
        event(new RefuelDeleted($refuel));
        $this->audit->log('deleted', $refuel, "Carga #{$refuel->id} eliminada");
    }

    public function restored(Refuel $refuel): void
    {
        event(new RefuelRestored($refuel));
        $this->audit->log('restored', $refuel, "Carga #{$refuel->id} restaurada");
    }
}
