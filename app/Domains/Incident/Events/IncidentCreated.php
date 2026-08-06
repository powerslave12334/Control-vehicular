<?php

namespace App\Domains\Incident\Events;

use App\Domains\Incident\Models\Incident;
use Illuminate\Foundation\Events\Dispatchable;

class IncidentCreated
{
    use Dispatchable;

    public function __construct(public Incident $incident) {}
}
