<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Route;

use App\Domains\Route\Models\Route;
use App\Http\Resources\Route\RouteResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class RouteController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $routes = Route::query()
            ->with(['driver', 'vehicle'])
            ->orderByDesc('date')
            ->paginate((int) $request->input('per_page', 15));

        return response()->json([
            'data' => RouteResource::collection($routes),
            'meta' => [
                'current_page' => $routes->currentPage(),
                'last_page' => $routes->lastPage(),
                'per_page' => $routes->perPage(),
                'total' => $routes->total(),
            ],
        ]);
    }

    public function show(Route $route): JsonResponse
    {
        $route->load(['steps', 'refuels', 'extraordinaryMovements', 'driver', 'vehicle']);

        return response()->json(new RouteResource($route));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'week' => 'nullable|integer|min:1|max:53',
            'driver_id' => 'nullable|exists:users,id',
            'driver_name' => 'nullable|string|max:255',
            'assistant_id' => 'nullable|exists:operators,id',
            'assistant_name' => 'nullable|string|max:255',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'vehicle_plate' => 'nullable|string|max:20',
            'client_name' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'planned_km' => 'nullable|numeric|min:0',
            'actual_km' => 'nullable|numeric|min:0',
            'status' => 'nullable|string|max:50',
            'code' => 'nullable|string|max:50|unique:routes,code',
            'description' => 'nullable|string',
            'origin' => 'nullable|string|max:255',
            'destination' => 'nullable|string|max:255',
            'distance_km' => 'nullable|numeric|min:0',
            'estimated_duration' => 'nullable|integer|min:0',
            'start_odometer' => 'nullable|numeric|min:0',
            'end_odometer' => 'nullable|numeric|min:0',
        ]);

        $route = Route::create($validated);

        return response()->json(new RouteResource($route), 201);
    }

    public function update(Request $request, Route $route): JsonResponse
    {
        $validated = $request->validate([
            'date' => 'sometimes|required|date',
            'week' => 'nullable|integer|min:1|max:53',
            'driver_id' => 'nullable|exists:users,id',
            'driver_name' => 'nullable|string|max:255',
            'assistant_id' => 'nullable|exists:operators,id',
            'assistant_name' => 'nullable|string|max:255',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'vehicle_plate' => 'nullable|string|max:20',
            'client_name' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'planned_km' => 'nullable|numeric|min:0',
            'actual_km' => 'nullable|numeric|min:0',
            'status' => 'nullable|string|max:50',
            'code' => 'nullable|string|max:50|unique:routes,code,' . $route->id,
            'description' => 'nullable|string',
            'origin' => 'nullable|string|max:255',
            'destination' => 'nullable|string|max:255',
            'distance_km' => 'nullable|numeric|min:0',
            'estimated_duration' => 'nullable|integer|min:0',
            'start_odometer' => 'nullable|numeric|min:0',
            'end_odometer' => 'nullable|numeric|min:0',
        ]);

        $route->update($validated);

        return response()->json(new RouteResource($route->fresh()));
    }

    public function destroy(Route $route): JsonResponse
    {
        $route->delete();

        return response()->json(null, 204);
    }
}
