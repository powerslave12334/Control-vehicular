<?php

namespace App\Domains\Fuel\Events;

use App\Domains\Fuel\Models\Refuel;
use Illuminate\Foundation\Events\Dispatchable;

class RefuelCreated
{
    use Dispatchable;

    public function __construct(public Refuel $refuel) {}
}
