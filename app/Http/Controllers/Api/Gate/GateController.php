<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Gate;

use App\Domains\Gate\Models\GateLog;
use App\Http\Controllers\Controller;
use App\Http\Resources\Gate\GateResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GateController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = GateLog::with('vehicle');

        if ($request->has('vehicle_id')) {
            $query->where('vehicle_id', $request->vehicle_id);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('date_from')) {
            $query->whereDate('logged_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('logged_at', '<=', $request->date_to);
        }

        $logs = $query->orderBy('logged_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'data' => GateResource::collection($logs),
            'meta' => [
                'total' => $logs->total(),
                'per_page' => $logs->perPage(),
                'current_page' => $logs->currentPage(),
                'last_page' => $logs->lastPage(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_name' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:50',
            'photo' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'logged_at' => 'nullable|date',
        ]);

        $gateLog = GateLog::create($validated);

        return response()->json([
            'data' => new GateResource($gateLog->load('vehicle')),
        ], 201);
    }

    public function show(Request $request, GateLog $gate): JsonResponse
    {
        $gate->load('vehicle');

        return response()->json([
            'data' => new GateResource($gate),
        ]);
    }

    public function update(Request $request, GateLog $gate): JsonResponse
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_name' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:50',
            'photo' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'logged_at' => 'nullable|date',
        ]);

        $gate->update($validated);

        return response()->json([
            'data' => new GateResource($gate->load('vehicle')),
        ]);
    }

    public function destroy(Request $request, GateLog $gate): JsonResponse
    {
        $gate->delete();

        return response()->json(['data' => ['message' => 'Deleted successfully.']]);
    }
}
