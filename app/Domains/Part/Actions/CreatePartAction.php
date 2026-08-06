<?php

namespace App\Domains\Part\Actions;

use App\Domains\Part\Models\Part;
use App\Domains\Part\DTO\PartData;

class CreatePartAction
{
    public function execute(PartData $data): Part
    {
        return Part::create($data->toArray());
    }
}
