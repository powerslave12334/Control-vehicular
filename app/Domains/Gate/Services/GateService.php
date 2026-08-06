<?php

namespace App\Domains\Gate\Services;

use App\Domains\Gate\Models\GateLog;
use Illuminate\Pagination\LengthAwarePaginator;

class GateService
{
    public function getAllPaginated(int $perPage = 30): LengthAwarePaginator
    {
        return GateLog::with(['vehicle', 'creator'])
            ->orderBy('logged_at', 'desc')
            ->paginate($perPage);
    }

    public function getAllFiltered(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $q = GateLog::with(['vehicle', 'creator'])->orderBy('logged_at', 'desc');

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $q->where(function ($query) use ($search) {
                $query->where('route_folio', 'like', "%{$search}%")
                    ->orWhere('driver_name', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%")
                    ->orWhere('vehicle_condition', 'like', "%{$search}%")
                    ->orWhereHas('vehicle', fn ($v) => $v->where('plate', 'like', "%{$search}%"));
            });
        }

        foreach (['type', 'vehicle_id', 'fuel_level', 'has_spare_tire'] as $key) {
            if (array_key_exists($key, $filters) && $filters[$key] !== '' && $filters[$key] !== null) {
                $q->where($key, $filters[$key]);
            }
        }

        if (! empty($filters['date_from'])) {
            $q->whereDate('logged_at', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $q->whereDate('logged_at', '<=', $filters['date_to']);
        }

        return $q->paginate($perPage);
    }

    public function getById(int $id): GateLog
    {
        return GateLog::with(['vehicle', 'creator'])->findOrFail($id);
    }

    public function create(array $data): GateLog
    {
        return GateLog::create($data);
    }

    public function delete(int $id): bool
    {
        return GateLog::findOrFail($id)->delete();
    }
}
