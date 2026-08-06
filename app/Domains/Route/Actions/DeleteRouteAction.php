<?php

namespace App\Domains\Route\Actions;

use App\Domains\Route\Models\Route;

class DeleteRouteAction
{
    public function execute(int $id): bool
    {
        $route = Route::findOrFail($id);
        return $route->delete();
    }
}
