<?php

namespace App\Domains\Inspection\Actions;

use App\Domains\Inspection\Models\Inspection;

class DeleteInspectionAction
{
    public function execute(int $id): bool
    {
        $inspection = Inspection::findOrFail($id);
        return $inspection->delete();
    }
}
