<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Fuel;

use App\Domains\Fuel\Models\Refuel;
use App\Http\Resources\Fuel\RefuelResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class RefuelController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Refuel::query()->with(['route', 'vehicle', 'driver']);

        if ($request->filled('vehicle_id')) {
            $query->where('vehicle_id', (int) $request->input('vehicle_id'));
        }

        if ($request->filled('driver_id')) {
            $query->where('driver_id', (int) $request->input('driver_id'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->input('date_to'));
        }

        $refuels = $query->orderByDesc('date')->paginate((int) $request->input('per_page', 15));

        return response()->json([
            'data' => RefuelResource::collection($refuels),
            'meta' => [
                'current_page' => $refuels->currentPage(),
                'last_page' => $refuels->lastPage(),
                'per_page' => $refuels->perPage(),
                'total' => $refuels->total(),
            ],
        ]);
    }

    public function show(Refuel $refuel): JsonResponse
    {
        $refuel->load(['route', 'vehicle', 'driver', 'fuelStation', 'fuelCard']);

        return response()->json(new RefuelResource($refuel));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'route_id' => 'nullable|exists:routes,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'driver_id' => 'nullable|exists:operators,id',
            'driver_name' => 'nullable|string|max:255',
            'liters' => 'required|numeric|min:0',
            'amount' => 'required|numeric|min:0',
            'price_per_liter' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:50',
            'ticket_photo' => 'nullable|string|max:255',
            'odometer' => 'nullable|numeric|min:0',
            'folio' => 'nullable|string|max:50|unique:refuels,folio',
            'fuel_station_id' => 'nullable|exists:fuel_stations,id',
            'fuel_card_id' => 'nullable|exists:fuel_cards,id',
            'status' => 'nullable|string|max:50',
        ]);

        $refuel = Refuel::create($validated);

        return response()->json(new RefuelResource($refuel), 201);
    }

    public function update(Request $request, Refuel $refuel): JsonResponse
    {
        $validated = $request->validate([
            'date' => 'sometimes|required|date',
            'route_id' => 'nullable|exists:routes,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'driver_id' => 'nullable|exists:operators,id',
            'driver_name' => 'nullable|string|max:255',
            'liters' => 'sometimes|required|numeric|min:0',
            'amount' => 'sometimes|required|numeric|min:0',
            'price_per_liter' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:50',
            'ticket_photo' => 'nullable|string|max:255',
            'odometer' => 'nullable|numeric|min:0',
            'folio' => 'nullable|string|max:50|unique:refuels,folio,' . $refuel->id,
            'fuel_station_id' => 'nullable|exists:fuel_stations,id',
            'fuel_card_id' => 'nullable|exists:fuel_cards,id',
            'status' => 'nullable|string|max:50',
        ]);

        $refuel->update($validated);

        return response()->json(new RefuelResource($refuel->fresh()));
    }

    public function destroy(Refuel $refuel): JsonResponse
    {
        $refuel->delete();

        return response()->json(null, 204);
    }
}
