<?php

namespace App\Domains\Document\Actions;

use App\Domains\Document\Models\Document;
use App\Domains\Document\DTO\DocumentData;

class CreateDocumentAction
{
    public function execute(DocumentData $data): Document
    {
        return Document::create($data->toArray());
    }
}
