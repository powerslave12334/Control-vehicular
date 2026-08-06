<?php

namespace App\Domains\Document\Services;

use App\Domains\Document\Models\Document;
use App\Domains\Document\DTO\DocumentData;
use App\Domains\Document\Actions\CreateDocumentAction;
use App\Domains\Document\Actions\DeleteDocumentAction;
use App\Domains\Document\Actions\RestoreDocumentAction;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class DocumentService
{
    public function __construct(
        protected CreateDocumentAction $createAction,
        protected DeleteDocumentAction $deleteAction,
        protected RestoreDocumentAction $restoreAction,
    ) {}

    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return Document::orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function getById(int $id): Document
    {
        return Document::findOrFail($id);
    }

    public function getForModel(Model $model): \Illuminate\Database\Eloquent\Collection
    {
        return Document::where('documentable_type', get_class($model))
            ->where('documentable_id', $model->id)->get();
    }

    public function create(DocumentData $data): Document
    {
        return $this->createAction->execute($data);
    }

    public function delete(int $id): bool
    {
        return $this->deleteAction->execute($id);
    }

    public function restore(int $id): Document
    {
        return $this->restoreAction->execute($id);
    }
}
