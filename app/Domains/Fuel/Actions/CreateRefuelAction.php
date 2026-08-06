<?php

namespace App\Domains\Fuel\Actions;

use App\Domains\Fuel\Models\Refuel;
use App\Domains\Fuel\DTO\RefuelData;

class CreateRefuelAction
{
    public function execute(RefuelData $data): Refuel
    {
        return Refuel::create($data->toArray());
    }
}
