<?php

namespace App\Domains\Provider\Services;

use App\Domains\Provider\Models\Provider;
use App\Domains\Provider\DTO\ProviderData;
use App\Domains\Provider\Actions\CreateProviderAction;
use App\Domains\Provider\Actions\UpdateProviderAction;
use App\Domains\Provider\Actions\DeleteProviderAction;
use App\Domains\Provider\Actions\RestoreProviderAction;
use Illuminate\Pagination\LengthAwarePaginator;

class ProviderService
{
    public function __construct(
        protected CreateProviderAction $createAction,
        protected UpdateProviderAction $updateAction,
        protected DeleteProviderAction $deleteAction,
        protected RestoreProviderAction $restoreAction,
    ) {}

    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return Provider::orderBy('name')->paginate($perPage);
    }

    public function getById(int $id): Provider
    {
        return Provider::findOrFail($id);
    }

    public function create(ProviderData $data): Provider
    {
        return $this->createAction->execute($data);
    }

    public function update(int $id, ProviderData $data): Provider
    {
        return $this->updateAction->execute($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->deleteAction->execute($id);
    }

    public function restore(int $id): Provider
    {
        return $this->restoreAction->execute($id);
    }
}
