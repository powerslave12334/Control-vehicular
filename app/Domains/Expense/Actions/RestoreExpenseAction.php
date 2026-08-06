<?php

namespace App\Domains\Expense\Actions;

use App\Domains\Expense\Models\Expense;

class RestoreExpenseAction
{
    public function execute(int $id): Expense
    {
        $expense = Expense::withTrashed()->findOrFail($id);
        $expense->restore();
        return $expense;
    }
}
