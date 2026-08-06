<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Maintenance;

use App\Domains\Maintenance\Models\Maintenance;
use App\Http\Resources\Maintenance\MaintenanceResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MaintenanceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $maintenances = Maintenance::paginate($request->get('per_page', 15));

        return response()->json(MaintenanceResource::collection($maintenances));
    }

    public function show(Maintenance $maintenance): JsonResponse
    {
        $maintenance->load('vehicle');

        return response()->json(new MaintenanceResource($maintenance));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'vehicle_id' => 'required|exists:vehicles,id',
            'type' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
            'workshop' => 'nullable|string|max:255',
            'workshop_id' => 'nullable|exists:workshops,id',
            'evidence' => 'nullable|string',
            'odometer' => 'nullable|integer|min:0',
            'scheduled_date' => 'nullable|date',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'nullable|string|max:255',
            'rejection_reason' => 'nullable|string',
        ]);

        $maintenance = Maintenance::create($validated);

        return response()->json(new MaintenanceResource($maintenance), 201);
    }

    public function update(Request $request, Maintenance $maintenance): JsonResponse
    {
        $validated = $request->validate([
            'date' => 'sometimes|required|date',
            'vehicle_id' => 'sometimes|required|exists:vehicles,id',
            'type' => 'sometimes|required|string|max:255',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
            'workshop' => 'nullable|string|max:255',
            'workshop_id' => 'nullable|exists:workshops,id',
            'evidence' => 'nullable|string',
            'odometer' => 'nullable|integer|min:0',
            'scheduled_date' => 'nullable|date',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'nullable|string|max:255',
            'rejection_reason' => 'nullable|string',
        ]);

        $maintenance->update($validated);

        return response()->json(new MaintenanceResource($maintenance->fresh()->load('vehicle')));
    }

    public function destroy(Maintenance $maintenance): JsonResponse
    {
        $maintenance->delete();

        return response()->json(null, 204);
    }
}
