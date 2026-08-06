<?php

namespace App\Jobs\Incident;

use App\Domains\Incident\Models\Incident;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class ProcessIncidentEvidenceJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public int $tries = 2;

    public function __construct(public readonly Incident $incident, public readonly string $evidencePath) {}

    public function handle(): void
    {
        \Illuminate\Support\Facades\Log::info('Incident evidence queued for processing', [
            'incident_id' => $this->incident->id,
            'path' => $this->evidencePath,
        ]);
    }
}
