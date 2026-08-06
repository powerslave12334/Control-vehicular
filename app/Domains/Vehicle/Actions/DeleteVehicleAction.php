<?php

namespace App\Domains\Vehicle\Actions;

use App\Domains\Vehicle\Models\Vehicle;

class DeleteVehicleAction
{
    public function execute(int $id): bool
    {
        $vehicle = Vehicle::findOrFail($id);
        return $vehicle->delete();
    }
}
