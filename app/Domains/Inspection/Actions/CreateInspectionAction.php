<?php

namespace App\Domains\Inspection\Actions;

use App\Domains\Inspection\Models\Inspection;
use App\Domains\Inspection\DTO\InspectionData;

class CreateInspectionAction
{
    public function execute(InspectionData $data): Inspection
    {
        return Inspection::create($data->toArray());
    }
}
