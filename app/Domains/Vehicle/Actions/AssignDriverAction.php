<?php

namespace App\Domains\Vehicle\Actions;

use App\Domains\Vehicle\Models\Vehicle;
use App\Domains\User\Models\User;
use RuntimeException;

class AssignDriverAction
{
    public function execute(int $vehicleId, int $driverId): Vehicle
    {
        $vehicle = Vehicle::findOrFail($vehicleId);
        $driver = User::findOrFail($driverId);

        if ($vehicle->assigned_driver_id === $driver->id) {
            return $vehicle;
        }

        $alreadyAssigned = Vehicle::where('assigned_driver_id', $driver->id)
            ->where('id', '!=', $vehicle->id)
            ->exists();

        if ($alreadyAssigned) {
            throw new RuntimeException("El conductor {$driver->name} ya está asignado a otro vehículo.");
        }

        $vehicle->assigned_driver_id = $driver->id;
        $vehicle->save();

        return $vehicle;
    }
}
