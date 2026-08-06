<?php

namespace App\Domains\Fuel\Actions;

use App\Domains\Fuel\Models\Refuel;

class RestoreRefuelAction
{
    public function execute(int $id): Refuel
    {
        $refuel = Refuel::withTrashed()->findOrFail($id);
        $refuel->restore();
        return $refuel;
    }
}
