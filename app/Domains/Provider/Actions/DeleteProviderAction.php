<?php

namespace App\Domains\Provider\Actions;

use App\Domains\Provider\Models\Provider;

class DeleteProviderAction
{
    public function execute(int $id): bool
    {
        $provider = Provider::findOrFail($id);
        return $provider->delete();
    }
}
