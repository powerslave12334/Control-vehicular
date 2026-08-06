<?php

namespace App\Domains\Operator\Services;

use App\Domains\Operator\Models\Operator;
use App\Domains\Operator\Models\License;
use App\Domains\Operator\DTO\OperatorData;
use App\Domains\Operator\Actions\CreateOperatorAction;
use App\Domains\Operator\Actions\UpdateOperatorAction;
use App\Domains\Operator\Actions\DeleteOperatorAction;
use App\Domains\Operator\Actions\RestoreOperatorAction;
use Illuminate\Pagination\LengthAwarePaginator;

class OperatorService
{
    public function __construct(
        protected CreateOperatorAction $createAction,
        protected UpdateOperatorAction $updateAction,
        protected DeleteOperatorAction $deleteAction,
        protected RestoreOperatorAction $restoreAction,
    ) {}

    public function getAll(): \Illuminate\Support\Collection
    {
        return Operator::where('status', 'Activo')->get();
    }

    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return Operator::with([])->orderBy('name')->paginate($perPage);
    }

    public function getById(int $id): Operator
    {
        return Operator::with(['licenses', 'routes', 'incidents', 'assignedVehicle'])->findOrFail($id);
    }

    public function create(OperatorData $data): Operator
    {
        return $this->createAction->execute($data);
    }

    public function update(int $id, OperatorData $data): Operator
    {
        return $this->updateAction->execute($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->deleteAction->execute($id);
    }

    public function restore(int $id): Operator
    {
        return $this->restoreAction->execute($id);
    }

    public function getAvailableOperators(): \Illuminate\Support\Collection
    {
        return Operator::where('status', 'Activo')
            ->whereDoesntHave('assignedVehicle')
            ->get();
    }
}
