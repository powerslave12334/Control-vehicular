<?php

namespace App\Domains\Inspection\Services;

use App\Domains\Inspection\Models\Inspection;
use App\Domains\Inspection\DTO\InspectionData;
use App\Domains\Inspection\Actions\CreateInspectionAction;
use App\Domains\Inspection\Actions\DeleteInspectionAction;
use App\Domains\Inspection\Actions\RestoreInspectionAction;
use Illuminate\Pagination\LengthAwarePaginator;

class InspectionService
{
    public function __construct(
        protected CreateInspectionAction $createAction,
        protected DeleteInspectionAction $deleteAction,
        protected RestoreInspectionAction $restoreAction,
    ) {}

    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return Inspection::with(['vehicle', 'operator'])
            ->orderBy('performed_at', 'desc')->paginate($perPage);
    }

    public function getById(int $id): Inspection
    {
        return Inspection::with(['vehicle', 'operator'])->findOrFail($id);
    }

    public function create(InspectionData $data): Inspection
    {
        return $this->createAction->execute($data);
    }

    public function delete(int $id): bool
    {
        return $this->deleteAction->execute($id);
    }

    public function restore(int $id): Inspection
    {
        return $this->restoreAction->execute($id);
    }
}
