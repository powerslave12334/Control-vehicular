<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Part;

use App\Domains\Part\Models\Part;
use App\Http\Controllers\Controller;
use App\Http\Resources\Part\PartResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PartController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $parts = Part::with(['category', 'brand'])
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'data' => PartResource::collection($parts),
            'meta' => [
                'total' => $parts->total(),
                'per_page' => $parts->perPage(),
                'current_page' => $parts->currentPage(),
                'last_page' => $parts->lastPage(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'part_category_id' => 'nullable|exists:part_categories,id',
            'part_brand_id' => 'nullable|exists:part_brands,id',
            'sku' => 'nullable|string|max:100|unique:parts,sku',
            'description' => 'nullable|string',
            'unit_price' => 'nullable|numeric|min:0',
            'current_stock' => 'nullable|integer|min:0',
            'min_stock' => 'nullable|integer|min:0',
            'max_stock' => 'nullable|integer|min:0',
            'unit_type' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:30',
        ]);

        $part = Part::create($validated);

        return response()->json([
            'data' => new PartResource($part->load(['category', 'brand'])),
        ], 201);
    }

    public function show(Request $request, Part $part): JsonResponse
    {
        $part->load(['category', 'brand']);

        return response()->json([
            'data' => new PartResource($part),
        ]);
    }

    public function update(Request $request, Part $part): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'part_category_id' => 'nullable|exists:part_categories,id',
            'part_brand_id' => 'nullable|exists:part_brands,id',
            'sku' => 'nullable|string|max:100|unique:parts,sku,' . $part->id,
            'description' => 'nullable|string',
            'unit_price' => 'nullable|numeric|min:0',
            'current_stock' => 'nullable|integer|min:0',
            'min_stock' => 'nullable|integer|min:0',
            'max_stock' => 'nullable|integer|min:0',
            'unit_type' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:30',
        ]);

        $part->update($validated);

        return response()->json([
            'data' => new PartResource($part->load(['category', 'brand'])),
        ]);
    }

    public function destroy(Request $request, Part $part): JsonResponse
    {
        $part->delete();

        return response()->json(['data' => ['message' => 'Deleted successfully.']]);
    }
}
