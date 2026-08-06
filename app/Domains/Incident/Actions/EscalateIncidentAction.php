<?php

namespace App\Domains\Incident\Actions;

use App\Domains\Incident\Models\Incident;
use App\Domains\Incident\Events\IncidentEscalated;

class EscalateIncidentAction
{
    public function execute(int $id, int $resolvedBy): Incident
    {
        $incident = Incident::findOrFail($id);

        if (!in_array($incident->status->value, ['reported', 'investigating'])) {
            throw new \DomainException('Solo incidentes en estado "Reportado" o "En investigación" pueden escalarse.');
        }

        $incident->update([
            'status' => 'investigating',
            'resolved_by' => $resolvedBy,
        ]);

        event(new IncidentEscalated($incident));

        return $incident;
    }
}
