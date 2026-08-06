<?php

namespace App\Domains\Vehicle\Actions;

use App\Domains\Vehicle\Models\Vehicle;

class ReleaseDriverAction
{
    public function execute(int $vehicleId): Vehicle
    {
        $vehicle = Vehicle::findOrFail($vehicleId);

        if (!$vehicle->assigned_driver_id) {
            return $vehicle;
        }

        $vehicle->assigned_driver_id = null;
        $vehicle->save();

        return $vehicle;
    }
}
