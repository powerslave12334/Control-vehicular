<?php

namespace App\Domains\Insurance\Observers;

use App\Domains\Insurance\Models\Insurance;
use App\Domains\Insurance\Events\InsuranceCreated;
use App\Domains\Insurance\Events\InsuranceUpdated;
use App\Domains\Insurance\Events\InsuranceDeleted;
use App\Domains\Insurance\Events\InsuranceRestored;
use App\Shared\Services\AuditService;

class InsuranceObserver
{
    public function __construct(protected AuditService $audit) {}

    public function created(Insurance $insurance): void
    {
        event(new InsuranceCreated($insurance));
        $this->audit->log('created', $insurance, "Póliza #{$insurance->policy_number} registrada");
    }

    public function updated(Insurance $insurance): void
    {
        $changes = $insurance->getChanges();
        if (isset($changes['status'])) {
            $original = $insurance->getRawOriginal('status');
            $this->audit->log('status_changed', $insurance, "Póliza #{$insurance->policy_number}: {$original} → {$changes['status']}", $changes);
        } else {
            $this->audit->log('updated', $insurance, "Póliza #{$insurance->policy_number} actualizada", $changes);
        }
        event(new InsuranceUpdated($insurance));
    }

    public function deleted(Insurance $insurance): void
    {
        event(new InsuranceDeleted($insurance));
        $this->audit->log('deleted', $insurance, "Póliza #{$insurance->policy_number} eliminada");
    }

    public function restored(Insurance $insurance): void
    {
        event(new InsuranceRestored($insurance));
        $this->audit->log('restored', $insurance, "Póliza #{$insurance->policy_number} restaurada");
    }
}
