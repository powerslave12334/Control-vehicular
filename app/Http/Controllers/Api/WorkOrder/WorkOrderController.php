<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\WorkOrder;

use App\Domains\WorkOrder\Models\WorkOrder;
use App\Http\Controllers\Controller;
use App\Http\Resources\WorkOrder\WorkOrderResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorkOrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $workOrders = WorkOrder::with(['vehicle', 'operator', 'maintenance', 'provider'])
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'data' => WorkOrderResource::collection($workOrders),
            'meta' => [
                'total' => $workOrders->total(),
                'per_page' => $workOrders->perPage(),
                'current_page' => $workOrders->currentPage(),
                'last_page' => $workOrders->lastPage(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:work_orders,code',
            'vehicle_id' => 'required|exists:vehicles,id',
            'operator_id' => 'nullable|exists:operators,id',
            'maintenance_id' => 'nullable|exists:maintenances,id',
            'provider_id' => 'nullable|exists:providers,id',
            'description' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'priority' => 'nullable|string|max:20',
            'status' => 'nullable|string|max:30',
            'estimated_cost' => 'nullable|numeric|min:0',
            'labor_cost' => 'nullable|numeric|min:0',
            'parts_cost' => 'nullable|numeric|min:0',
            'total_cost' => 'nullable|numeric|min:0',
            'requested_by' => 'nullable|exists:users,id',
            'approved_by' => 'nullable|exists:users,id',
            'assigned_to' => 'nullable|exists:providers,id',
            'started_at' => 'nullable|date',
            'completed_at' => 'nullable|date',
            'closed_at' => 'nullable|date',
            'mileage_at_request' => 'nullable|numeric|min:0',
            'mileage_at_completion' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'rejection_reason' => 'nullable|string',
        ]);

        $workOrder = WorkOrder::create($validated);

        return response()->json([
            'data' => new WorkOrderResource($workOrder->load(['vehicle', 'operator', 'maintenance', 'provider'])),
        ], 201);
    }

    public function show(Request $request, WorkOrder $workOrder): JsonResponse
    {
        $workOrder->load(['vehicle', 'operator', 'maintenance', 'provider']);

        return response()->json([
            'data' => new WorkOrderResource($workOrder),
        ]);
    }

    public function update(Request $request, WorkOrder $workOrder): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:work_orders,code,' . $workOrder->id,
            'vehicle_id' => 'required|exists:vehicles,id',
            'operator_id' => 'nullable|exists:operators,id',
            'maintenance_id' => 'nullable|exists:maintenances,id',
            'provider_id' => 'nullable|exists:providers,id',
            'description' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'priority' => 'nullable|string|max:20',
            'status' => 'nullable|string|max:30',
            'estimated_cost' => 'nullable|numeric|min:0',
            'labor_cost' => 'nullable|numeric|min:0',
            'parts_cost' => 'nullable|numeric|min:0',
            'total_cost' => 'nullable|numeric|min:0',
            'requested_by' => 'nullable|exists:users,id',
            'approved_by' => 'nullable|exists:users,id',
            'assigned_to' => 'nullable|exists:providers,id',
            'started_at' => 'nullable|date',
            'completed_at' => 'nullable|date',
            'closed_at' => 'nullable|date',
            'mileage_at_request' => 'nullable|numeric|min:0',
            'mileage_at_completion' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'rejection_reason' => 'nullable|string',
        ]);

        $workOrder->update($validated);

        return response()->json([
            'data' => new WorkOrderResource($workOrder->load(['vehicle', 'operator', 'maintenance', 'provider'])),
        ]);
    }

    public function destroy(Request $request, WorkOrder $workOrder): JsonResponse
    {
        $workOrder->delete();

        return response()->json(['data' => ['message' => 'Deleted successfully.']]);
    }
}
