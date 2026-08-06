<?php

namespace App\Domains\Provider\Actions;

use App\Domains\Provider\Models\Provider;

class RestoreProviderAction
{
    public function execute(int $id): Provider
    {
        $provider = Provider::withTrashed()->findOrFail($id);
        $provider->restore();
        return $provider;
    }
}
