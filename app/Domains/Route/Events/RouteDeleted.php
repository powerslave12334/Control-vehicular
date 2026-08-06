<?php

namespace App\Domains\Route\Events;

use App\Domains\Route\Models\Route;
use Illuminate\Foundation\Events\Dispatchable;

class RouteDeleted
{
    use Dispatchable;

    public function __construct(public Route $route) {}
}
