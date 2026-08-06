<?php

namespace App\Domains\Route\Services;

use App\Domains\Route\Actions\CancelRouteAction;
use App\Domains\Route\Actions\CreateRouteAction;
use App\Domains\Route\Actions\DeleteRouteAction;
use App\Domains\Route\Actions\FinishRouteAction;
use App\Domains\Route\Actions\RestoreRouteAction;
use App\Domains\Route\Actions\StartRouteAction;
use App\Domains\Route\Actions\UpdateRouteAction;
use App\Domains\Route\DTO\RouteData;
use App\Domains\Route\Models\Route;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class RouteService
{
    public function __construct(
        protected CreateRouteAction $createAction,
        protected UpdateRouteAction $updateAction,
        protected DeleteRouteAction $deleteAction,
        protected RestoreRouteAction $restoreAction,
        protected StartRouteAction $startAction,
        protected FinishRouteAction $finishAction,
        protected CancelRouteAction $cancelAction,
    ) {}

    public function getAll(): Collection
    {
        return Route::with(['driver', 'vehicle'])->orderBy('date', 'desc')->get();
    }

    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return Route::with(['driver', 'vehicle'])->orderBy('date', 'desc')->paginate($perPage);
    }

    public function getAllFiltered(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $q = Route::with(['driver', 'vehicle'])->orderBy('date', 'desc');

        if (! empty($filters['search'])) {
            $search = trim($filters['search']);
            $q->where(function ($query) use ($search) {
                $query->where('client_name', 'like', "%{$search}%")
                    ->orWhere('driver_name', 'like', "%{$search}%")
                    ->orWhere('vehicle_plate', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['vehicle_id'])) {
            $q->where('vehicle_id', (int) $filters['vehicle_id']);
        }

        if (! empty($filters['status'])) {
            $q->where('status', $filters['status']);
        }

        if (! empty($filters['date_from'])) {
            $q->whereDate('date', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $q->whereDate('date', '<=', $filters['date_to']);
        }

        return $q->paginate($perPage);
    }

    public function getById(int $id): Route
    {
        return Route::with(['driver', 'vehicle', 'steps', 'refuels', 'expenses', 'extraordinaryMovements'])->findOrFail($id);
    }

    public function create(RouteData $data): Route
    {
        return $this->createAction->execute($data);
    }

    public function update(int $id, RouteData $data): Route
    {
        return $this->updateAction->execute($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->deleteAction->execute($id);
    }

    public function restore(int $id): Route
    {
        return $this->restoreAction->execute($id);
    }

    public function getByDateRange(string $start, string $end): Collection
    {
        return Route::with('driver')->whereBetween('date', [$start, $end])->orderBy('date')->get();
    }

    public function start(int $id, float $startOdometer): Route
    {
        return $this->startAction->execute($id, $startOdometer);
    }

    public function finish(int $id, float $endOdometer): Route
    {
        return $this->finishAction->execute($id, $endOdometer);
    }

    public function cancel(int $id, ?string $reason = null): Route
    {
        return $this->cancelAction->execute($id, $reason);
    }
}
