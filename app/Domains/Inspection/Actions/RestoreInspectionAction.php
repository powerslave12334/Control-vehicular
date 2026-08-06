<?php

namespace App\Domains\Inspection\Actions;

use App\Domains\Inspection\Models\Inspection;

class RestoreInspectionAction
{
    public function execute(int $id): Inspection
    {
        $inspection = Inspection::withTrashed()->findOrFail($id);
        $inspection->restore();
        return $inspection;
    }
}
