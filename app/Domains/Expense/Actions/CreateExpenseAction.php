<?php

namespace App\Domains\Expense\Actions;

use App\Domains\Expense\Models\Expense;
use App\Domains\Expense\DTO\ExpenseData;

class CreateExpenseAction
{
    public function execute(ExpenseData $data): Expense
    {
        return Expense::create($data->toArray());
    }
}
