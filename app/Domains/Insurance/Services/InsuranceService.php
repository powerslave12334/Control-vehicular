<?php

namespace App\Domains\Insurance\Services;

use App\Domains\Insurance\Models\Insurance;
use App\Domains\Insurance\DTO\InsuranceData;
use App\Domains\Insurance\Actions\CreateInsuranceAction;
use App\Domains\Insurance\Actions\DeleteInsuranceAction;
use App\Domains\Insurance\Actions\RestoreInsuranceAction;
use Illuminate\Pagination\LengthAwarePaginator;

class InsuranceService
{
    public function __construct(
        protected CreateInsuranceAction $createAction,
        protected DeleteInsuranceAction $deleteAction,
        protected RestoreInsuranceAction $restoreAction,
    ) {}

    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return Insurance::with('vehicle')
            ->orderBy('start_date', 'desc')->paginate($perPage);
    }

    public function getById(int $id): Insurance
    {
        return Insurance::with('vehicle')->findOrFail($id);
    }

    public function getActiveForVehicle(int $vehicleId): ?Insurance
    {
        return Insurance::where('vehicle_id', $vehicleId)
            ->where('status', 'active')
            ->where('end_date', '>=', now())
            ->first();
    }

    public function create(InsuranceData $data): Insurance
    {
        return $this->createAction->execute($data);
    }

    public function delete(int $id): bool
    {
        return $this->deleteAction->execute($id);
    }

    public function restore(int $id): Insurance
    {
        return $this->restoreAction->execute($id);
    }
}
