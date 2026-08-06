<?php

namespace App\Domains\Incident\Actions;

use App\Domains\Incident\Models\Incident;

class RestoreIncidentAction
{
    public function execute(int $id): Incident
    {
        $incident = Incident::withTrashed()->findOrFail($id);
        $incident->restore();
        return $incident;
    }
}
