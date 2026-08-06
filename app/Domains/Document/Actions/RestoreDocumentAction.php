<?php

namespace App\Domains\Document\Actions;

use App\Domains\Document\Models\Document;

class RestoreDocumentAction
{
    public function execute(int $id): Document
    {
        $document = Document::withTrashed()->findOrFail($id);
        $document->restore();
        return $document;
    }
}
