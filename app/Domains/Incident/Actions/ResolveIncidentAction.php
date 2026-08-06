<?php

namespace App\Domains\Incident\Actions;

use App\Domains\Incident\Models\Incident;

class ResolveIncidentAction
{
    public function execute(int $id, int $resolvedBy, ?string $resolutionNotes = null): Incident
    {
        $incident = Incident::findOrFail($id);

        if (!in_array($incident->status->value, ['investigating'])) {
            throw new \DomainException('Solo incidentes en investigación pueden resolverse.');
        }

        $data = [
            'status' => 'resolved',
            'resolved_by' => $resolvedBy,
            'resolved_at' => now(),
        ];

        if ($resolutionNotes) {
            $data['description'] = $incident->description . "\n\n--- Resolución ---\n" . $resolutionNotes;
        }

        $incident->update($data);

        return $incident;
    }
}
