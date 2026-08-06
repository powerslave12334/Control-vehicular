<?php

namespace App\Domains\Expense\Actions;

use App\Domains\Expense\Models\Expense;
use App\Domains\Expense\Events\ExpenseRejected;

class RejectExpenseAction
{
    public function execute(int $id, string $reason): Expense
    {
        $expense = Expense::findOrFail($id);

        if ($expense->status->value !== 'pendiente') {
            throw new \DomainException('Solo gastos pendientes pueden ser rechazados.');
        }

        $expense->update(['status' => 'rechazado', 'rejection_reason' => $reason]);

        event(new ExpenseRejected($expense));

        return $expense;
    }
}
