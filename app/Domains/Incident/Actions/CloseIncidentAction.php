<?php

namespace App\Domains\Incident\Actions;

use App\Domains\Incident\Models\Incident;
use App\Domains\Incident\Events\IncidentClosed;

class CloseIncidentAction
{
    public function execute(int $id, int $resolvedBy): Incident
    {
        $incident = Incident::findOrFail($id);

        if (!in_array($incident->status->value, ['resolved', 'investigating'])) {
            throw new \DomainException('El incidente debe estar en estado "En investigación" o "Resuelto" para cerrarse.');
        }

        $incident->update([
            'status' => 'closed',
            'resolved_by' => $resolvedBy,
            'resolved_at' => now(),
        ]);

        event(new IncidentClosed($incident));

        return $incident;
    }
}
