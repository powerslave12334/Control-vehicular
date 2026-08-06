<?php

namespace App\Domains\Operator\Events;

use App\Domains\Operator\Models\Operator;
use Illuminate\Foundation\Events\Dispatchable;

class OperatorUpdated
{
    use Dispatchable;

    public function __construct(public Operator $operator) {}
}
