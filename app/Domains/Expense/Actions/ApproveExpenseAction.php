<?php

namespace App\Domains\Expense\Actions;

use App\Domains\Expense\Models\Expense;
use App\Domains\Expense\Events\ExpenseApproved;

class ApproveExpenseAction
{
    public function execute(int $id, int $approvedBy): Expense
    {
        $expense = Expense::findOrFail($id);

        if ($expense->status->value !== 'pendiente') {
            throw new \DomainException('Solo gastos pendientes pueden ser aprobados.');
        }

        $expense->update(['status' => 'aprobado', 'approved_by' => $approvedBy]);

        event(new ExpenseApproved($expense));

        return $expense;
    }
}
