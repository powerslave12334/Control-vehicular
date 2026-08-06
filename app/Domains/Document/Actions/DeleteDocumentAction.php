<?php

namespace App\Domains\Document\Actions;

use App\Domains\Document\Models\Document;

class DeleteDocumentAction
{
    public function execute(int $id): bool
    {
        $document = Document::findOrFail($id);
        return $document->delete();
    }
}
