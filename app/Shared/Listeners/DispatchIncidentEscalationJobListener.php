<?php

namespace App\Shared\Listeners;

use App\Jobs\Incident\NotifyIncidentEscalationJob;
use App\Domains\Incident\Events\IncidentEscalated;

class DispatchIncidentEscalationJobListener
{
    public function handle(IncidentEscalated $event): void
    {
        NotifyIncidentEscalationJob::dispatch($event->incident);
    }
}
