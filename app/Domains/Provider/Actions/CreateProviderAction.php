<?php

namespace App\Domains\Provider\Actions;

use App\Domains\Provider\Models\Provider;
use App\Domains\Provider\DTO\ProviderData;

class CreateProviderAction
{
    public function execute(ProviderData $data): Provider
    {
        return Provider::create($data->toArray());
    }
}
