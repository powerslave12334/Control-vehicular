<?php

namespace App\Domains\Incident\Events;

use App\Domains\Incident\Models\Incident;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;

class IncidentEscalated
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(public Incident $incident) {}
}
