<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Catalog;

use App\Domains\Catalog\Models\Catalog;
use App\Http\Controllers\Controller;
use App\Http\Resources\Catalog\CatalogItemResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CatalogItemController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Catalog::query();

        if ($request->has('group')) {
            $query->byGroup($request->group);
        }

        $items = $query->orderBy('group')->orderBy('label')->paginate($request->get('per_page', 15));

        return response()->json([
            'data' => CatalogItemResource::collection($items),
            'meta' => [
                'total' => $items->total(),
                'per_page' => $items->perPage(),
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'group' => 'required|string|max:100',
            'value' => 'required|string|max:100',
            'label' => 'required|string|max:255',
        ]);

        $item = Catalog::create($validated);

        return response()->json([
            'data' => new CatalogItemResource($item),
        ], 201);
    }

    public function show(Request $request, Catalog $catalogItem): JsonResponse
    {
        return response()->json([
            'data' => new CatalogItemResource($catalogItem),
        ]);
    }

    public function update(Request $request, Catalog $catalogItem): JsonResponse
    {
        $validated = $request->validate([
            'group' => 'required|string|max:100',
            'value' => 'required|string|max:100',
            'label' => 'required|string|max:255',
        ]);

        $catalogItem->update($validated);

        return response()->json([
            'data' => new CatalogItemResource($catalogItem->fresh()),
        ]);
    }

    public function destroy(Request $request, Catalog $catalogItem): JsonResponse
    {
        $catalogItem->delete();

        return response()->json(['data' => ['message' => 'Deleted successfully.']]);
    }
}
