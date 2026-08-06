<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Insurance;

use App\Domains\Insurance\Models\Insurance;
use App\Http\Controllers\Controller;
use App\Http\Resources\Insurance\InsuranceResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InsuranceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $insurances = Insurance::with('vehicle')
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'data' => InsuranceResource::collection($insurances),
            'meta' => [
                'total' => $insurances->total(),
                'per_page' => $insurances->perPage(),
                'current_page' => $insurances->currentPage(),
                'last_page' => $insurances->lastPage(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'policy_number' => 'required|string|max:100|unique:insurances,policy_number',
            'insurer' => 'required|string|max:255',
            'coverage_type' => 'nullable|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'premium' => 'nullable|numeric|min:0',
            'deductible' => 'nullable|numeric|min:0',
            'status' => 'nullable|string|max:30',
        ]);

        $insurance = Insurance::create($validated);

        return response()->json([
            'data' => new InsuranceResource($insurance->load('vehicle')),
        ], 201);
    }

    public function show(Request $request, Insurance $insurance): JsonResponse
    {
        $insurance->load('vehicle');

        return response()->json([
            'data' => new InsuranceResource($insurance),
        ]);
    }

    public function update(Request $request, Insurance $insurance): JsonResponse
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'policy_number' => 'required|string|max:100|unique:insurances,policy_number,' . $insurance->id,
            'insurer' => 'required|string|max:255',
            'coverage_type' => 'nullable|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'premium' => 'nullable|numeric|min:0',
            'deductible' => 'nullable|numeric|min:0',
            'status' => 'nullable|string|max:30',
        ]);

        $insurance->update($validated);

        return response()->json([
            'data' => new InsuranceResource($insurance->load('vehicle')),
        ]);
    }

    public function destroy(Request $request, Insurance $insurance): JsonResponse
    {
        $insurance->delete();

        return response()->json(['data' => ['message' => 'Deleted successfully.']]);
    }
}
