<?php

namespace App\Domains\Fuel\Services;

use App\Domains\Fuel\Actions\ApproveRefuelAction;
use App\Domains\Fuel\Actions\CalculateFuelEfficiencyAction;
use App\Domains\Fuel\Actions\CreateRefuelAction;
use App\Domains\Fuel\Actions\DeleteRefuelAction;
use App\Domains\Fuel\Actions\RejectRefuelAction;
use App\Domains\Fuel\Actions\RestoreRefuelAction;
use App\Domains\Fuel\Actions\UpdateRefuelAction;
use App\Domains\Fuel\DTO\RefuelData;
use App\Domains\Fuel\Models\Refuel;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class FuelService
{
    public function __construct(
        protected CreateRefuelAction $createAction,
        protected UpdateRefuelAction $updateAction,
        protected DeleteRefuelAction $deleteAction,
        protected RestoreRefuelAction $restoreAction,
        protected ApproveRefuelAction $approveAction,
        protected RejectRefuelAction $rejectAction,
        protected CalculateFuelEfficiencyAction $efficiencyAction,
    ) {}

    public function getAll(): Collection
    {
        return Refuel::with(['vehicle', 'driver', 'route', 'fuelStation', 'fuelCard', 'approver'])
            ->orderBy('date', 'desc')->get();
    }

    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return Refuel::with(['vehicle', 'driver', 'route', 'fuelStation', 'approver'])
            ->orderBy('date', 'desc')->paginate($perPage);
    }

    public function getAllFiltered(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $q = Refuel::with(['vehicle', 'driver', 'route', 'fuelStation', 'fuelCard', 'approver'])
            ->orderBy('date', 'desc');

        if (! empty($filters['date_from'])) {
            $q->whereDate('date', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $q->whereDate('date', '<=', $filters['date_to']);
        }

        if (! empty($filters['vehicle_id'])) {
            $q->where('vehicle_id', $filters['vehicle_id']);
        }

        if (! empty($filters['status'])) {
            $q->where('status', $filters['status']);
        }

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $q->where(function ($query) use ($search) {
                $query->where('folio', 'like', "%{$search}%")
                    ->orWhere('driver_name', 'like', "%{$search}%")
                    ->orWhereHas('vehicle', fn ($v) => $v->where('plate', 'like', "%{$search}%"));
            });
        }

        return $q->paginate($perPage);
    }

    public function getById(int $id): Refuel
    {
        return Refuel::with(['vehicle', 'driver', 'route', 'fuelStation', 'fuelCard', 'approver'])->findOrFail($id);
    }

    public function create(RefuelData $data): Refuel
    {
        return $this->createAction->execute($data);
    }

    public function update(int $id, RefuelData $data): Refuel
    {
        return $this->updateAction->execute($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->deleteAction->execute($id);
    }

    public function restore(int $id): Refuel
    {
        return $this->restoreAction->execute($id);
    }

    public function approve(int $id, int $approvedBy): Refuel
    {
        return $this->approveAction->execute($id, $approvedBy);
    }

    public function reject(int $id, string $reason): Refuel
    {
        return $this->rejectAction->execute($id, $reason);
    }

    public function calculateEfficiency(int $refuelId): array
    {
        return $this->efficiencyAction->execute($refuelId);
    }
}
