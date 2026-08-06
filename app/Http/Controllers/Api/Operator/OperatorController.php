<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Operator;

use App\Domains\Operator\Models\Operator;
use App\Http\Resources\Operator\OperatorResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class OperatorController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $operators = Operator::query()
            ->with('assignedVehicle')
            ->paginate($request->integer('per_page', 15));

        return response()->json([
            'data' => OperatorResource::collection($operators),
            'meta' => [
                'current_page' => $operators->currentPage(),
                'last_page' => $operators->lastPage(),
                'per_page' => $operators->perPage(),
                'total' => $operators->total(),
            ],
        ]);
    }

    public function show(Operator $operator): JsonResponse
    {
        $operator->load(['assignedVehicle', 'licenses']);

        return response()->json(new OperatorResource($operator));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'document_type' => 'nullable|string|max:50',
            'document_number' => 'nullable|string|max:50|unique:operators,document_number',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'blood_type' => 'nullable|string|max:5',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|string|max:255',
            'license_type' => 'nullable|string|max:50',
            'status' => 'nullable|string|max:50',
        ]);

        $operator = Operator::create($validated);

        return response()->json(new OperatorResource($operator), 201);
    }

    public function update(Request $request, Operator $operator): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'document_type' => 'nullable|string|max:50',
            'document_number' => 'nullable|string|max:50|unique:operators,document_number,' . $operator->id,
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'blood_type' => 'nullable|string|max:5',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|string|max:255',
            'license_type' => 'nullable|string|max:50',
            'status' => 'nullable|string|max:50',
        ]);

        $operator->update($validated);

        return response()->json(new OperatorResource($operator->fresh()));
    }

    public function destroy(Operator $operator): JsonResponse
    {
        $operator->delete();

        return response()->json(null, 204);
    }
}
