<?php

namespace App\Domains\Vehicle\Actions;

use App\Domains\Vehicle\Models\Vehicle;

class RestoreVehicleAction
{
    public function execute(int $id): Vehicle
    {
        $vehicle = Vehicle::withTrashed()->findOrFail($id);
        $vehicle->restore();
        return $vehicle;
    }
}
