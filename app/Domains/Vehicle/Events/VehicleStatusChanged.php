<?php

namespace App\Domains\Vehicle\Events;

use App\Domains\Vehicle\Models\Vehicle;
use Illuminate\Foundation\Events\Dispatchable;

class VehicleStatusChanged
{
    use Dispatchable;

    public function __construct(public Vehicle $vehicle, public string $oldStatus, public string $newStatus) {}
}
