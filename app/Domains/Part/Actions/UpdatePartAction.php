<?php

namespace App\Domains\Part\Actions;

use App\Domains\Part\Models\Part;
use App\Domains\Part\DTO\PartData;

class UpdatePartAction
{
    public function execute(int $id, PartData $data): Part
    {
        $part = Part::findOrFail($id);
        $part->update($data->toArray());
        return $part;
    }
}
