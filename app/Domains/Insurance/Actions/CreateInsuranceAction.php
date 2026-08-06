<?php

namespace App\Domains\Insurance\Actions;

use App\Domains\Insurance\Models\Insurance;
use App\Domains\Insurance\DTO\InsuranceData;

class CreateInsuranceAction
{
    public function execute(InsuranceData $data): Insurance
    {
        return Insurance::create($data->toArray());
    }
}
