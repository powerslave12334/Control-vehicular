<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Incident;

use App\Domains\Incident\Models\Incident;
use App\Http\Resources\Incident\IncidentResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class IncidentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Incident::query();

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $incidents = $query->paginate($request->get('per_page', 15));

        return response()->json(IncidentResource::collection($incidents));
    }

    public function show(Incident $incident): JsonResponse
    {
        $incident->load(['vehicle', 'driver']);

        return response()->json(new IncidentResource($incident));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'time' => 'nullable|string|max:255',
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'nullable|exists:operators,id',
            'driver_name' => 'nullable|string|max:255',
            'type' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'severity' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'cost' => 'nullable|numeric|min:0',
            'involves_third_party' => 'nullable|boolean',
            'third_party_data' => 'nullable|array',
            'photo' => 'nullable|string',
        ]);

        $incident = Incident::create($validated);

        return response()->json(new IncidentResource($incident), 201);
    }

    public function update(Request $request, Incident $incident): JsonResponse
    {
        $validated = $request->validate([
            'date' => 'sometimes|required|date',
            'time' => 'nullable|string|max:255',
            'vehicle_id' => 'sometimes|required|exists:vehicles,id',
            'driver_id' => 'nullable|exists:operators,id',
            'driver_name' => 'nullable|string|max:255',
            'type' => 'sometimes|required|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'severity' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|required|string|max:255',
            'cost' => 'nullable|numeric|min:0',
            'involves_third_party' => 'nullable|boolean',
            'third_party_data' => 'nullable|array',
            'photo' => 'nullable|string',
        ]);

        $incident->update($validated);

        return response()->json(new IncidentResource($incident->fresh()->load(['vehicle', 'driver'])));
    }

    public function destroy(Incident $incident): JsonResponse
    {
        $incident->delete();

        return response()->json(null, 204);
    }
}
