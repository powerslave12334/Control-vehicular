<?php

namespace App\Domains\Incident\Actions;

use App\Domains\Incident\Models\Incident;

class DeleteIncidentAction
{
    public function execute(int $id): bool
    {
        $incident = Incident::findOrFail($id);
        return $incident->delete();
    }
}
