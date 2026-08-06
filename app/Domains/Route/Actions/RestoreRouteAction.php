<?php

namespace App\Domains\Route\Actions;

use App\Domains\Route\Models\Route;

class RestoreRouteAction
{
    public function execute(int $id): Route
    {
        $route = Route::withTrashed()->findOrFail($id);
        $route->restore();
        return $route;
    }
}
