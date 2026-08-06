<?php

namespace App\Domains\Document\Events;

use App\Domains\Document\Models\Document;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;

class DocumentUpdated
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(public Document $document) {}
}
