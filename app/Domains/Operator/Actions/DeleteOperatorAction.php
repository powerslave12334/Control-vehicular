<?php

namespace App\Domains\Operator\Actions;

use App\Domains\Operator\Models\Operator;

class DeleteOperatorAction
{
    public function execute(int $id): bool
    {
        $operator = Operator::findOrFail($id);
        return $operator->delete();
    }
}
