<?php

namespace App\Domains\Expense\Events;

use App\Domains\Expense\Models\Expense;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;

class ExpenseRejected
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(public Expense $expense) {}
}
