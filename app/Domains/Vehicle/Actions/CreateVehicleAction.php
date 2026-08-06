<?php
 
namespace App\Domains\Vehicle\Actions;
 
use App\Domains\Vehicle\Models\Vehicle;
use App\Domains\Vehicle\DTO\VehicleData;
 
class CreateVehicleAction
{
    public function execute(VehicleData $data): Vehicle
    {
        return Vehicle::create($data->toArray());
    }
}
