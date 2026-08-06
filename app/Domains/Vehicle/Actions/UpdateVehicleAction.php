<?php
 
namespace App\Domains\Vehicle\Actions;
 
use App\Domains\Vehicle\Models\Vehicle;
use App\Domains\Vehicle\DTO\VehicleData;
 
class UpdateVehicleAction
{
    public function execute(int $id, VehicleData $data): Vehicle
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->update($data->toArray());
        return $vehicle;
    }
}
