<?php

namespace App\Domains\Part\Observers;

use App\Domains\Part\Models\Part;
use App\Domains\Part\Events\PartCreated;
use App\Domains\Part\Events\PartUpdated;
use App\Domains\Part\Events\PartDeleted;
use App\Domains\Part\Events\PartRestored;
use App\Shared\Services\AuditService;

class PartObserver
{
    public function __construct(protected AuditService $audit) {}

    public function created(Part $part): void
    {
        event(new PartCreated($part));
        $this->audit->log('created', $part, "Refacción '{$part->name}' creada");
    }

    public function updated(Part $part): void
    {
        event(new PartUpdated($part));
        $this->audit->log('updated', $part, "Refacción '{$part->name}' actualizada", $part->getChanges());
    }

    public function deleted(Part $part): void
    {
        event(new PartDeleted($part));
        $this->audit->log('deleted', $part, "Refacción '{$part->name}' eliminada");
    }

    public function restored(Part $part): void
    {
        event(new PartRestored($part));
        $this->audit->log('restored', $part, "Refacción '{$part->name}' restaurada");
    }
}
