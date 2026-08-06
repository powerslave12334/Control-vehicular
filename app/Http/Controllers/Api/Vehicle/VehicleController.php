<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Vehicle;

use App\Domains\Vehicle\Models\Vehicle;
use App\Http\Controllers\Controller;
use App\Http\Resources\Vehicle\VehicleCollection;
use App\Http\Resources\Vehicle\VehicleResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VehicleController extends Controller
{
    public function index(): VehicleCollection
    {
        return new VehicleCollection(Vehicle::paginate(15));
    }

    public function show(Vehicle $vehicle): VehicleResource
    {
        $vehicle->load('assignedDriver');

        return new VehicleResource($vehicle);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'plate' => 'required|string|max:20|unique:vehicles,plate',
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'vehicle_type' => 'required|string',
            'status' => 'sometimes|string|max:50',
            'color' => 'nullable|string|max:50',
            'vin' => 'nullable|string|max:50',
            'engine_number' => 'nullable|string|max:100',
            'fuel_type' => 'nullable|string|max:50',
            'tank_capacity' => 'nullable|numeric|min:0',
            'current_km' => 'nullable|numeric|min:0',
            'cargo_capacity' => 'nullable|numeric|min:0',
            'gps_installed' => 'nullable|boolean',
            'acquisition_date' => 'nullable|date',
            'acquisition_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $vehicle = Vehicle::create([
            'plate' => $validated['plate'],
            'brand' => $validated['brand'],
            'model' => $validated['model'],
            'year' => $validated['year'],
            'vehicle_type' => $validated['vehicle_type'],
            'status' => $validated['status'] ?? 'Activa',
            'color' => $validated['color'] ?? null,
            'vin' => $validated['vin'] ?? null,
            'engine' => $validated['engine_number'] ?? null,
            'fuel_type' => $validated['fuel_type'] ?? null,
            'tank_capacity' => $validated['tank_capacity'] ?? null,
            'current_odometer' => $validated['current_km'] ?? null,
            'cargo_capacity' => $validated['cargo_capacity'] ?? null,
            'gps_installed' => $validated['gps_installed'] ?? false,
            'acquisition_date' => $validated['acquisition_date'] ?? null,
            'acquisition_cost' => $validated['acquisition_cost'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        return response()->json([
            'data' => new VehicleResource($vehicle),
        ], Response::HTTP_CREATED);
    }

    public function update(Request $request, Vehicle $vehicle): VehicleResource
    {
        $validated = $request->validate([
            'plate' => 'sometimes|string|max:20|unique:vehicles,plate,' . $vehicle->id,
            'brand' => 'sometimes|string|max:100',
            'model' => 'sometimes|string|max:100',
            'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
            'vehicle_type' => 'sometimes|string',
            'status' => 'sometimes|string|max:50',
            'color' => 'nullable|string|max:50',
            'vin' => 'nullable|string|max:50',
            'engine_number' => 'nullable|string|max:100',
            'fuel_type' => 'nullable|string|max:50',
            'tank_capacity' => 'nullable|numeric|min:0',
            'current_km' => 'nullable|numeric|min:0',
            'cargo_capacity' => 'nullable|numeric|min:0',
            'gps_installed' => 'nullable|boolean',
            'acquisition_date' => 'nullable|date',
            'acquisition_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $vehicle->update([
            'plate' => $validated['plate'] ?? $vehicle->plate,
            'brand' => $validated['brand'] ?? $vehicle->brand,
            'model' => $validated['model'] ?? $vehicle->model,
            'year' => $validated['year'] ?? $vehicle->year,
            'vehicle_type' => $validated['vehicle_type'] ?? $vehicle->vehicle_type,
            'status' => array_key_exists('status', $validated) ? $validated['status'] : $vehicle->status,
            'color' => $validated['color'] ?? $vehicle->color,
            'vin' => $validated['vin'] ?? $vehicle->vin,
            'engine' => $validated['engine_number'] ?? $vehicle->engine,
            'fuel_type' => $validated['fuel_type'] ?? $vehicle->fuel_type,
            'tank_capacity' => $validated['tank_capacity'] ?? $vehicle->tank_capacity,
            'current_odometer' => $validated['current_km'] ?? $vehicle->current_odometer,
            'cargo_capacity' => $validated['cargo_capacity'] ?? $vehicle->cargo_capacity,
            'gps_installed' => $validated['gps_installed'] ?? $vehicle->gps_installed,
            'acquisition_date' => $validated['acquisition_date'] ?? $vehicle->acquisition_date,
            'acquisition_cost' => $validated['acquisition_cost'] ?? $vehicle->acquisition_cost,
            'notes' => $validated['notes'] ?? $vehicle->notes,
        ]);

        return new VehicleResource($vehicle);
    }

    public function destroy(Vehicle $vehicle): JsonResponse
    {
        $vehicle->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
