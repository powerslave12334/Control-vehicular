<?php

namespace App\Domains\Fuel\Actions;

use App\Domains\Fuel\Models\Refuel;

class DeleteRefuelAction
{
    public function execute(int $id): bool
    {
        $refuel = Refuel::findOrFail($id);
        return $refuel->delete();
    }
}
