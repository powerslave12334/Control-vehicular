<?php

namespace App\Domains\Part\Actions;

use App\Domains\Part\Models\Part;

class RestorePartAction
{
    public function execute(int $id): Part
    {
        $part = Part::withTrashed()->findOrFail($id);
        $part->restore();
        return $part;
    }
}
