<?php

namespace App\Domains\Maintenance\Services;

use App\Domains\Maintenance\Actions\ApproveMaintenanceAction;
use App\Domains\Maintenance\Actions\CancelMaintenanceAction;
use App\Domains\Maintenance\Actions\CompleteMaintenanceAction;
use App\Domains\Maintenance\Actions\CreateMaintenanceAction;
use App\Domains\Maintenance\Actions\DeleteMaintenanceAction;
use App\Domains\Maintenance\Actions\RejectMaintenanceAction;
use App\Domains\Maintenance\Actions\RestoreMaintenanceAction;
use App\Domains\Maintenance\Actions\StartMaintenanceAction;
use App\Domains\Maintenance\Actions\UpdateMaintenanceAction;
use App\Domains\Maintenance\DTO\MaintenanceData;
use App\Domains\Maintenance\Models\Maintenance;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class MaintenanceService
{
    public function __construct(
        protected CreateMaintenanceAction $createAction,
        protected UpdateMaintenanceAction $updateAction,
        protected DeleteMaintenanceAction $deleteAction,
        protected RestoreMaintenanceAction $restoreAction,
        protected ApproveMaintenanceAction $approveAction,
        protected RejectMaintenanceAction $rejectAction,
        protected StartMaintenanceAction $startAction,
        protected CompleteMaintenanceAction $completeAction,
        protected CancelMaintenanceAction $cancelAction,
    ) {}

    public function getAll(): Collection
    {
        return Maintenance::with(['vehicle', 'maintenanceWorkshop', 'requester', 'approver'])
            ->orderBy('date', 'desc')->get();
    }

    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return Maintenance::with(['vehicle', 'maintenanceWorkshop', 'requester', 'approver'])
            ->orderBy('date', 'desc')->paginate($perPage);
    }

    public function getAllFiltered(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $q = Maintenance::with(['vehicle', 'maintenanceWorkshop', 'requester', 'approver'])
            ->orderBy('date', 'desc');

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $q->where(function ($query) use ($search) {
                $query->where('description', 'like', "%{$search}%")
                    ->orWhere('workshop', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhereHas('vehicle', fn ($v) => $v->where('plate', 'like', "%{$search}%"));
            });
        }

        foreach (['type', 'status', 'vehicle_id'] as $key) {
            if (! empty($filters[$key])) {
                $q->where($key, $filters[$key]);
            }
        }

        if (! empty($filters['date_from'])) {
            $q->whereDate('date', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $q->whereDate('date', '<=', $filters['date_to']);
        }

        return $q->paginate($perPage);
    }

    public function getById(int $id): Maintenance
    {
        return Maintenance::with(['vehicle', 'maintenanceWorkshop', 'requester', 'approver'])->findOrFail($id);
    }

    public function create(MaintenanceData $data): Maintenance
    {
        return $this->createAction->execute($data);
    }

    public function update(int $id, MaintenanceData $data): Maintenance
    {
        return $this->updateAction->execute($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->deleteAction->execute($id);
    }

    public function restore(int $id): Maintenance
    {
        return $this->restoreAction->execute($id);
    }

    public function approve(int $id, int $approvedBy): Maintenance
    {
        return $this->approveAction->execute($id, $approvedBy);
    }

    public function reject(int $id, string $reason): Maintenance
    {
        return $this->rejectAction->execute($id, $reason);
    }

    public function start(int $id): Maintenance
    {
        return $this->startAction->execute($id);
    }

    public function complete(int $id): Maintenance
    {
        return $this->completeAction->execute($id);
    }

    public function cancel(int $id, string $reason): Maintenance
    {
        return $this->cancelAction->execute($id, $reason);
    }
}
