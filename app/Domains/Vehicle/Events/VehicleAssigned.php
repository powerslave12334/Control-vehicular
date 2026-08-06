<?php

namespace App\Domains\Vehicle\Events;

use App\Domains\Vehicle\Models\Vehicle;
use App\Domains\Operator\Models\Operator;
use Illuminate\Foundation\Events\Dispatchable;

class VehicleAssigned
{
    use Dispatchable;

    public function __construct(public Vehicle $vehicle, public Operator $operator) {}
}
