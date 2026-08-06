<?php

namespace App\Domains\Incident\Actions;

use App\Domains\Incident\Models\Incident;
use App\Domains\Incident\DTO\IncidentData;

class CreateIncidentAction
{
    public function execute(IncidentData $data): Incident
    {
        return Incident::create($data->toArray());
    }
}
