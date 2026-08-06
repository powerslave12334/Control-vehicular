<?php

namespace App\Domains\Operator\Actions;

use App\Domains\Operator\Models\Operator;

class RestoreOperatorAction
{
    public function execute(int $id): Operator
    {
        $operator = Operator::withTrashed()->findOrFail($id);
        $operator->restore();
        return $operator;
    }
}
