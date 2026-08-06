<?php

namespace App\Domains\Inspection\Observers;

use App\Domains\Inspection\Models\Inspection;
use App\Domains\Inspection\Events\InspectionCreated;
use App\Domains\Inspection\Events\InspectionDeleted;
use App\Domains\Inspection\Events\InspectionRestored;
use App\Shared\Services\AuditService;

class InspectionObserver
{
    public function __construct(protected AuditService $audit) {}

    public function created(Inspection $inspection): void
    {
        event(new InspectionCreated($inspection));
        $this->audit->log('created', $inspection, "Inspección #{$inspection->id} registrada");
    }

    public function updated(Inspection $inspection): void
    {
        $this->audit->log('updated', $inspection, "Inspección #{$inspection->id} actualizada", $inspection->getChanges());
    }

    public function deleted(Inspection $inspection): void
    {
        event(new InspectionDeleted($inspection));
        $this->audit->log('deleted', $inspection, "Inspección #{$inspection->id} eliminada");
    }

    public function restored(Inspection $inspection): void
    {
        event(new InspectionRestored($inspection));
        $this->audit->log('restored', $inspection, "Inspección #{$inspection->id} restaurada");
    }
}
