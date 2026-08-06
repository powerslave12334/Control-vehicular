<?php

namespace App\Domains\Provider\Events;

use App\Domains\Provider\Models\Provider;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;

class ProviderUpdated
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(public Provider $provider) {}
}
