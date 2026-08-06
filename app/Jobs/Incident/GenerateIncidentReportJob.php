<?php

namespace App\Jobs\Incident;

use App\Domains\Incident\Models\Incident;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class GenerateIncidentReportJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public int $tries = 2;

    public function __construct(public readonly Incident $incident) {}

    public function handle(): void
    {
        \Illuminate\Support\Facades\Log::info('Incident report generated', [
            'incident_id' => $this->incident->id,
            'severity' => $this->incident->severity,
            'status' => $this->incident->status,
        ]);
    }
}
