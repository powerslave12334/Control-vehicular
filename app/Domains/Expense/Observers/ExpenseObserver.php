<?php

namespace App\Domains\Expense\Observers;

use App\Domains\Expense\Models\Expense;
use App\Domains\Expense\Events\ExpenseRegistered;
use App\Domains\Expense\Events\ExpenseDeleted;
use App\Domains\Expense\Events\ExpenseRestored;
use App\Shared\Services\AuditService;

class ExpenseObserver
{
    public function __construct(protected AuditService $audit) {}

    public function created(Expense $expense): void
    {
        event(new ExpenseRegistered($expense));
        $this->audit->log('created', $expense, "Gasto #{$expense->id} registrado");
    }

    public function updated(Expense $expense): void
    {
        $changes = $expense->getChanges();
        if (isset($changes['status'])) {
            $original = $expense->getRawOriginal('status');
            $this->audit->log('status_changed', $expense, "Gasto #{$expense->id}: {$original} → {$changes['status']}", $changes);
        } else {
            $this->audit->log('updated', $expense, "Gasto #{$expense->id} actualizado", $changes);
        }
    }

    public function deleted(Expense $expense): void
    {
        event(new ExpenseDeleted($expense));
        $this->audit->log('deleted', $expense, "Gasto #{$expense->id} eliminado");
    }

    public function restored(Expense $expense): void
    {
        event(new ExpenseRestored($expense));
        $this->audit->log('restored', $expense, "Gasto #{$expense->id} restaurado");
    }
}
