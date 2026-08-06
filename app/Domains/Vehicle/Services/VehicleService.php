<?php

namespace App\Domains\Vehicle\Services;

use App\Domains\Vehicle\Models\Vehicle;
use App\Domains\Vehicle\DTO\VehicleData;
use App\Domains\Vehicle\Actions\CreateVehicleAction;
use App\Domains\Vehicle\Actions\UpdateVehicleAction;
use App\Domains\Vehicle\Actions\DeleteVehicleAction;
use App\Domains\Vehicle\Actions\RestoreVehicleAction;
use App\Domains\Vehicle\Actions\AssignDriverAction;
use App\Domains\Vehicle\Actions\ReleaseDriverAction;
use Illuminate\Pagination\LengthAwarePaginator;

class VehicleService
{
    public function __construct(
        protected CreateVehicleAction $createAction,
        protected UpdateVehicleAction $updateAction,
        protected DeleteVehicleAction $deleteAction,
        protected RestoreVehicleAction $restoreAction,
        protected AssignDriverAction $assignAction,
        protected ReleaseDriverAction $releaseAction,
    ) {}

    public function getAll(): \Illuminate\Support\Collection
    {
        return Vehicle::with(['refuels'])->get();
    }

    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return Vehicle::with(['vehicleBrand', 'vehicleModel', 'vehicleFuelType', 'assignedDriver'])->paginate($perPage);
    }

    public function getAllFiltered(array $filters = [], int $perPage = 9): LengthAwarePaginator
    {
        $query = Vehicle::with(['vehicleBrand', 'vehicleModel', 'vehicleFuelType', 'assignedDriver']);

        if (!empty($filters['search'])) {
            $term = trim($filters['search']);
            $query->where(fn($q) => $q
                ->where('brand', 'like', "%{$term}%")
                ->orWhere('model', 'like', "%{$term}%")
                ->orWhere('plate', 'like', "%{$term}%")
                ->orWhere('vin', 'like', "%{$term}%"));
        }

        foreach (['status', 'fuel_type', 'vehicle_type', 'gps_installed', 'assigned_driver_id'] as $field) {
            if (isset($filters[$field]) && $filters[$field] !== '' && $filters[$field] !== null) {
                $query->where($field, $filters[$field]);
            }
        }

        return $query->orderByDesc('created_at')->paginate($perPage);
    }

    public function pluckAuthorizedFuel(): array
    {
        return Vehicle::pluck('authorized_fuel', 'id')->toArray();
    }

    public function updateAuthorizedFuel(int $vehicleId, float $value): void
    {
        Vehicle::where('id', $vehicleId)->update(['authorized_fuel' => $value]);
    }

    public function getById(int $id): Vehicle
    {
        return Vehicle::with(['vehicleBrand', 'vehicleModel', 'vehicleFuelType', 'assignedDriver', 'tires', 'logs'])->findOrFail($id);
    }

    public function create(VehicleData $data): Vehicle
    {
        return $this->createAction->execute($data);
    }

    public function update(int $id, VehicleData $data): Vehicle
    {
        return $this->updateAction->execute($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->deleteAction->execute($id);
    }

    public function restore(int $id): Vehicle
    {
        return $this->restoreAction->execute($id);
    }

    public function assignDriver(int $vehicleId, int $driverId): Vehicle
    {
        return $this->assignAction->execute($vehicleId, $driverId);
    }

    public function releaseDriver(int $vehicleId): Vehicle
    {
        return $this->releaseAction->execute($vehicleId);
    }
}
