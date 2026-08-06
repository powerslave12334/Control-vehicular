<?php

namespace App\Domains\Part\Actions;

use App\Domains\Part\Models\Part;

class DeletePartAction
{
    public function execute(int $id): bool
    {
        $part = Part::findOrFail($id);
        return $part->delete();
    }
}
