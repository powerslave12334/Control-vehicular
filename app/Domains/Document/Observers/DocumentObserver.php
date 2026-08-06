<?php

namespace App\Domains\Document\Observers;

use App\Domains\Document\Models\Document;
use App\Domains\Document\Events\DocumentUploaded;
use App\Domains\Document\Events\DocumentUpdated;
use App\Domains\Document\Events\DocumentDeleted;
use App\Domains\Document\Events\DocumentRestored;
use App\Shared\Services\AuditService;

class DocumentObserver
{
    public function __construct(protected AuditService $audit) {}

    public function created(Document $document): void
    {
        event(new DocumentUploaded($document));
        $this->audit->log('created', $document, "Documento '{$document->name}' subido");
    }

    public function updated(Document $document): void
    {
        event(new DocumentUpdated($document));
        $this->audit->log('updated', $document, "Documento '{$document->name}' actualizado", $document->getChanges());
    }

    public function deleted(Document $document): void
    {
        event(new DocumentDeleted($document));
        $this->audit->log('deleted', $document, "Documento '{$document->name}' eliminado");
    }

    public function restored(Document $document): void
    {
        event(new DocumentRestored($document));
        $this->audit->log('restored', $document, "Documento '{$document->name}' restaurado");
    }
}
