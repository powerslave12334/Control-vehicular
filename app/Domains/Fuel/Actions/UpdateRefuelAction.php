<?php

namespace App\Domains\Fuel\Actions;

use App\Domains\Fuel\DTO\RefuelData;
use App\Domains\Fuel\Models\Refuel;

class UpdateRefuelAction
{
    public function execute(int $id, RefuelData $data): Refuel
    {
        $refuel = Refuel::findOrFail($id);
        $refuel->update($data->toArray());

        return $refuel;
    }
}
