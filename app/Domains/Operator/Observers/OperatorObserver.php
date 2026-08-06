<?php

namespace App\Domains\Operator\Observers;

use App\Domains\Operator\Models\Operator;
use App\Domains\Operator\Events\OperatorCreated;
use App\Domains\Operator\Events\OperatorUpdated;
use App\Domains\Operator\Events\OperatorDeleted;
use App\Domains\Operator\Events\OperatorRestored;
use App\Shared\Services\AuditService;

class OperatorObserver
{
    public function __construct(protected AuditService $audit) {}

    public function created(Operator $operator): void
    {
        event(new OperatorCreated($operator));
        $this->audit->log('created', $operator, "Operador {$operator->name} creado");
    }

    public function updated(Operator $operator): void
    {
        event(new OperatorUpdated($operator));
        $this->audit->log('updated', $operator, "Operador {$operator->name} actualizado", $operator->getChanges());
    }

    public function deleted(Operator $operator): void
    {
        event(new OperatorDeleted($operator));
        $this->audit->log('deleted', $operator, "Operador {$operator->name} eliminado");
    }

    public function restored(Operator $operator): void
    {
        event(new OperatorRestored($operator));
        $this->audit->log('restored', $operator, "Operador {$operator->name} restaurado");
    }
}
