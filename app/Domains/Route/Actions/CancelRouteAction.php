<?php

namespace App\Domains\Route\Actions;

use App\Domains\Route\Models\Route;
use App\Domains\Route\Events\RouteCancelled;
use RuntimeException;

class CancelRouteAction
{
    public function execute(int $id, ?string $reason = null): Route
    {
        $route = Route::findOrFail($id);

        if (in_array($route->status->value, ['Completada', 'Cancelada'])) {
            throw new RuntimeException("No se puede cancelar una ruta ya completada o cancelada.");
        }

        $route->status = 'Cancelada';
        $route->cancelled_at = now();
        $route->cancellation_reason = $reason;
        $route->save();

        event(new RouteCancelled($route, $reason));

        return $route;
    }
}
