<?php

namespace App\Domains\Expense\Actions;

use App\Domains\Expense\Models\Expense;

class DeleteExpenseAction
{
    public function execute(int $id): bool
    {
        $expense = Expense::findOrFail($id);

        if ($expense->status === 'aprobado') {
            throw new \DomainException('No se pueden eliminar gastos aprobados.');
        }

        return $expense->delete();
    }
}
