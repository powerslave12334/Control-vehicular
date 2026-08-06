<?php

namespace App\Domains\Incident\Services;

use App\Domains\Incident\Models\Incident;
use App\Domains\Incident\DTO\IncidentData;
use App\Domains\Incident\Actions\CreateIncidentAction;
use App\Domains\Incident\Actions\DeleteIncidentAction;
use App\Domains\Incident\Actions\RestoreIncidentAction;
use App\Domains\Incident\Actions\CloseIncidentAction;
use App\Domains\Incident\Actions\EscalateIncidentAction;
use App\Domains\Incident\Actions\ResolveIncidentAction;
use Illuminate\Pagination\LengthAwarePaginator;

class IncidentService
{
    public function __construct(
        protected CreateIncidentAction $createAction,
        protected DeleteIncidentAction $deleteAction,
        protected RestoreIncidentAction $restoreAction,
        protected CloseIncidentAction $closeAction,
        protected EscalateIncidentAction $escalateAction,
        protected ResolveIncidentAction $resolveAction,
    ) {}

    public function getAllFiltered(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Incident::with(['vehicle', 'driver', 'resolver']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['severity'])) {
            $query->where('severity', $filters['severity']);
        }
        if (!empty($filters['vehicle_id'])) {
            $query->where('vehicle_id', $filters['vehicle_id']);
        }
        if (!empty($filters['driver_id'])) {
            $query->where('driver_id', $filters['driver_id']);
        }
        if (!empty($filters['date_from'])) {
            $query->whereDate('date', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('date', '<=', $filters['date_to']);
        }

        return $query->orderBy('date', 'desc')->orderBy('id', 'desc')->paginate($perPage);
    }

    public function getAll(): \Illuminate\Support\Collection
    {
        return Incident::with(['vehicle', 'driver'])->orderBy('date', 'desc')->get();
    }

    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return Incident::with(['vehicle', 'driver', 'resolver'])
            ->orderBy('date', 'desc')->paginate($perPage);
    }

    public function getById(int $id): Incident
    {
        return Incident::with(['vehicle', 'driver', 'resolver'])->findOrFail($id);
    }

    public function create(IncidentData $data): Incident
    {
        return $this->createAction->execute($data);
    }

    public function update(int $id, array $data): Incident
    {
        $incident = $this->getById($id);
        $incident->update($data);
        return $incident->fresh(['vehicle', 'driver', 'resolver']);
    }

    public function delete(int $id): bool
    {
        return $this->deleteAction->execute($id);
    }

    public function restore(int $id): Incident
    {
        return $this->restoreAction->execute($id);
    }

    public function close(int $id, int $resolvedBy): Incident
    {
        return $this->closeAction->execute($id, $resolvedBy);
    }

    public function escalate(int $id, int $resolvedBy): Incident
    {
        return $this->escalateAction->execute($id, $resolvedBy);
    }

    public function resolve(int $id, int $resolvedBy, ?string $resolutionNotes = null): Incident
    {
        return $this->resolveAction->execute($id, $resolvedBy, $resolutionNotes);
    }
}
