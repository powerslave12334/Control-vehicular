<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Document;

use App\Domains\Document\Models\Document;
use App\Http\Controllers\Controller;
use App\Http\Resources\Document\DocumentResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $documents = Document::orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'data' => DocumentResource::collection($documents),
            'meta' => [
                'total' => $documents->total(),
                'per_page' => $documents->perPage(),
                'current_page' => $documents->currentPage(),
                'last_page' => $documents->lastPage(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'documentable_type' => 'nullable|string|max:255',
            'documentable_id' => 'nullable|integer',
            'type' => 'nullable|string|max:100',
            'name' => 'required|string|max:255',
            'file_path' => 'required|string|max:255',
            'issuance_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:issuance_date',
            'status' => 'nullable|string|max:30',
        ]);

        $document = Document::create($validated);

        return response()->json([
            'data' => new DocumentResource($document),
        ], 201);
    }

    public function show(Request $request, Document $document): JsonResponse
    {
        return response()->json([
            'data' => new DocumentResource($document),
        ]);
    }

    public function update(Request $request, Document $document): JsonResponse
    {
        $validated = $request->validate([
            'documentable_type' => 'nullable|string|max:255',
            'documentable_id' => 'nullable|integer',
            'type' => 'nullable|string|max:100',
            'name' => 'required|string|max:255',
            'file_path' => 'required|string|max:255',
            'issuance_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:issuance_date',
            'status' => 'nullable|string|max:30',
        ]);

        $document->update($validated);

        return response()->json([
            'data' => new DocumentResource($document->fresh()),
        ]);
    }

    public function destroy(Request $request, Document $document): JsonResponse
    {
        $document->delete();

        return response()->json(['data' => ['message' => 'Deleted successfully.']]);
    }
}
