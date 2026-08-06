<?php

namespace App\Domains\Maintenance\Observers;

use App\Domains\Maintenance\Models\Maintenance;
use App\Domains\Maintenance\Events\MaintenanceCreated;
use App\Domains\Maintenance\Events\MaintenanceDeleted;
use App\Domains\Maintenance\Events\MaintenanceRestored;
use App\Shared\Services\AuditService;

class MaintenanceObserver
{
    public function __construct(protected AuditService $audit) {}

    public function created(Maintenance $maintenance): void
    {
        event(new MaintenanceCreated($maintenance));
        $this->audit->log('created', $maintenance, "Mantenimiento #{$maintenance->id} registrado");
    }

    public function updated(Maintenance $maintenance): void
    {
        $changes = $maintenance->getChanges();

        if (isset($changes['status'])) {
            $original = $maintenance->getRawOriginal('status');
            $this->audit->log('status_changed', $maintenance, "Mantenimiento #{$maintenance->id}: {$original} → {$changes['status']}", $changes);
        } else {
            $this->audit->log('updated', $maintenance, "Mantenimiento #{$maintenance->id} actualizado", $changes);
        }
    }

    public function deleted(Maintenance $maintenance): void
    {
        event(new MaintenanceDeleted($maintenance));
        $this->audit->log('deleted', $maintenance, "Mantenimiento #{$maintenance->id} eliminado");
    }

    public function restored(Maintenance $maintenance): void
    {
        event(new MaintenanceRestored($maintenance));
        $this->audit->log('restored', $maintenance, "Mantenimiento #{$maintenance->id} restaurado");
    }
}
