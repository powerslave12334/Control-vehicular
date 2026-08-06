<?php

namespace App\Domains\Provider\Actions;

use App\Domains\Provider\Models\Provider;
use App\Domains\Provider\DTO\ProviderData;

class UpdateProviderAction
{
    public function execute(int $id, ProviderData $data): Provider
    {
        $provider = Provider::findOrFail($id);
        $provider->update($data->toArray());
        return $provider;
    }
}
